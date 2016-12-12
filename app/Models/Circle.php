<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Circle extends Model {

	//
    protected $table = 'circles';


    public function users(){
        return $this->belongsToMany('App\Models\User', 'circle_has_users', 'circle_id','user_id');
    }
}
