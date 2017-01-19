<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Tools\GeneralHelper;
use App\Models\Circle;
use App\Models\CircleUsers;
use App\Models\PartnerUser;
use App\Models\User;
use DB;
use App\Models\VersionCircles;
use App\Models\VersionLog;
use App\Models\VersionUsers;
use App\Models\ProductTypes;

class LoadingController extends Controller
{
    public function getProduct($pub = "all", $inEx=1, $parsedProduct, $limit=5){




//var_dump("pub = " . $pub, "inex = " . $inEx, "parsedprod = " . $parsedProduct, "limit = " . $limit);

        $data = VersionLog::where(function($query) use ($pub, $inEx,  $parsedProduct) {

            if ($pub == 'pnd') {

                $query->where(DB::raw('DATE(tools_version_logs.publish_start)'), '>', '1970-12-11');
            }

            elseif ($pub == 'p') {
//var_dump("p wird erreicht");
                $query->where(DB::raw('DATE(tools_version_logs.publish_start)'), '!=', '1970-12-12');
            }
            else {

                $query->where(DB::raw('DATE(tools_version_logs.publish_start)'), '=', '1970-12-12');
            }

            if ($inEx == 1) {

                $query->where('tools_version_logs.intern_extern', '=', 1);
            }

            else {
                //var_dump("< 2 wird erreicht");
                $query->where('tools_version_logs.intern_extern', '<', 2);
            }

            if($parsedProduct != "all"){
                //var_dump("!= all wird nicht erreicht");
                if(is_numeric($parsedProduct)){

                    $query->where('tools_version_logs.product_type', '=', $parsedProduct);

                }
                elseif(is_array($parsedProduct)){

                    $query->whereIn('tools_version_logs.product_type', $parsedProduct);
                }

            }
        })

            ->leftjoin('product_types', 'tools_version_logs.product_type', '=', 'product_types.id')

            ->select('tools_version_logs.id', 'tools_version_logs.intern_extern',
                'tools_version_logs.content', 'tools_version_logs.title', 'tools_version_logs.publish_start',
                'tools_version_logs.preview_link', DB::raw('product_types.title AS product_type'),
                DB::raw('product_types.id AS pid'))
            ->orderBy('tools_version_logs.publish_start','desc')
            ->limit($limit)
            ->get()->toArray();



        $dataIdArr = array();
        foreach ($data as $item){
            array_push($dataIdArr, $item['id']);
        }

        $dataIndexArr = array();
        foreach ($data as $item){
            $dataIndexArr[$item['id']] = $item;
        }

        $versionUsers = VersionUsers::whereIn('version_has_users.version_id', $dataIdArr )
            ->leftjoin('users', 'version_has_users.user_id', '=', 'users.id' )
            ->select('users.id', 'version_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as display_name"))->get()->toArray();

        $groupedUsers = array();
        foreach ($versionUsers as $user){
            $groupedUsers[$user['version_id']][$user['id']] = $user;
        };

        $versionCircles = VersionCircles::whereIn('version_has_circles.version_id', $dataIdArr)
            ->leftjoin('circles', 'version_has_circles.circle_id', '=', 'circles.id')
            ->select('circles.id', 'version_id', 'circles.title')
            ->get()->toArray();

        $groupedCircles = array();
        foreach ($versionCircles as $circle){
            $groupedCircles[$circle['version_id']][$circle['id']] = $circle;
        }

        foreach (array_keys($dataIndexArr) as $key) {
            if(isset($groupedUsers[$key])){
                $dataIndexArr[$key]['users'] = $groupedUsers[$key];
            }
            if(isset($groupedCircles[$key])) {
                $dataIndexArr[$key]['circles'] = $groupedCircles[$key];
            }
        }

        $resArr = array();

        foreach ($dataIndexArr as $item){
            $resArr["items"][$item['id']] = $item;
        }

        return $resArr;
    }



    /*
    public function allProducts($index ='id'){
        $data = ProductTypes::select('id', 'title')->get()->toArray();

        $resArr = array();

        foreach ($data as $item){
            $resArr["items"][$item['id']] = $item;
        }

        return $resArr;

    }
    */
}
