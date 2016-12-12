<?php
namespace App\Models;

use Cartalyst\Sentry\Users\Eloquent\User as SentryUser;
use App\Interfaces\ReadLevelFilter;

class User extends SentryUser implements ReadLevelFilter {

    use \App\Traits\AccessSwitch;
	use \App\Traits\Ad4matUserLoggingTrait;

    protected $fillable = [];
    private $resourceName = 'users';

    public function partners(){
        return $this->belongsToMany('\App\Models\Partner')->withTimestamps();
    }

    public function groups(){
        return $this->belongsToMany('\App\Models\Group', 'users_groups');
    }

    public function partnerRoles(){
        return $this->belongsToMany('\App\Models\PartnerRole')->withTimestamps();
    }

    public function userAdvertisers(){
        return $this->belongsToMany('\App\Models\AdvertiserUsers','advertiser_has_users','user_id','advertiser_id');
    }

    public function userProfile()
    {
        return $this->hasOne('\App\Models\UserProfile');
    }

	// wrapper for the push functionality to log the user doing the action
	public function pushWithLogging(){
		if(\Sentry::getUser() != null){
			$this->last_updated_by = \Sentry::getUser()->getId();
		}
		$this->push();
	}

	public function delete(){

		$this->partners()->detach();
		$this->groups()->detach();
		$this->partnerRoles()->detach();
        $this->userAdvertisers()->detach();
        $this->userProfile()->delete();
		return parent::delete();
	}
    /**
     * @return mixed
     * $this->filter() lives in \App\Traits\AccessSwitch and is a trait
     */
    public function filterReadLevel(){
        return $this->readFilter();
    }

    public function getOwn(){
        // logic for "own" user result
        // returns an empty collection to not break the view loops
        // implement logic if real result is needed
        return new \Illuminate\Database\Eloquent\Collection;
    }

    public function getSelf(){
        // logic for "self"
        $currentUserId = \Sentry::getUser()->id;
        return $this->where('id', $currentUserId )->get();
    }


}