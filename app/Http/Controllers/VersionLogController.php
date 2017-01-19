<?php namespace App\Http\Controllers;


use App\Http\Controllers\VersionLogAjaxRequestController;
use App\Models\VersionLog;
use App\Tools\GeneralHelper;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartnerUser;
use App\Models\Circle;
use App\Models\VersionUsers;
use App\Models\VersionCircles;
use App\Models\ProductTypes;
use Illuminate\Support\Facades\DB;


class VersionLogController extends Controller
{


    public function loadinterntv ()
    {
        $call = new VersionLogAjaxRequestController();


        $result = $call->run($controller = 'Loading',
            $method = 'getProduct', $product = 'all', $inEx = '0',
            $key = 'ySfduUttHi', $limit = 50, $pub = 'p');

        return $this->generateResult($result);
    }

    public function loadinterndraft()
    {
        $call = new VersionLogAjaxRequestController();


        $result = $call->run($controller = 'Loading',
            $method = 'getProduct', $product = 'all', $inEx = '0',
            $key = 'ySfduUttHi', $limit = 50, $pub = 'd');


        return $this->generateResult($result);
    }

    public function loadinternpublish()
    {
        $call = new VersionLogAjaxRequestController();


        $result = $call->run($controller = 'Loading',
            $method = 'getProduct', $product = 'all', $inEx = '0',
            $key = 'ySfduUttHi', $limit = 50, $pub = 'p');



        return $this->generateResult($result);
    }

    public function loadextern()
    {

        $call = new VersionLogAjaxRequestController();

        $result = $call->run($controller = 'Loading',
            $method = 'getProduct', $product = 'all', $inEx = '1',
            $key = 'ySfduUttHi', $limit = 50, $pub = 'p');

        return $this->generateResult($result);
    }

    public function generateResult($result, $paramsArr = array())
    {

        $result = json_decode($result);

        $obj2Arr = new GeneralHelper();
        $resultArr = $obj2Arr->object2array($result);


        if (!empty($resultArr['items'])){
        foreach ($resultArr['items'] as $resItem) {


            $dateTime = $resItem['publish_start'];
            $dt = new \DateTime($dateTime);
            $date = $dt->format('d.m.y');


            $resultArr['items'][$resItem['id']]['date'] = $date;

            if (isset($resItem['users'])){
                if (count($resItem['users']) > 1) {
                    $userArr = array();
                    if (array_key_exists('circles', $resItem)) {
                        foreach ($resItem['circles'] as $circle) {
                            array_push($userArr, $circle['title']);
                        }
                    }

                    foreach ($resItem['users'] as $user) {
                        array_push($userArr, $user['display_name']);
                    }


                    $userstring = implode(", ", $userArr);
                    $resultArr['items'][$resItem['id']]['userstring'] = $userstring;
                } else {
                    foreach ($resItem['users'] as $user) {
                        $resultArr['items'][$resItem['id']]['userstring'] = $user['display_name'];
                    }
                }
            }

        }
        $jsonArr = array();

        foreach ($resultArr['items'] as $resItem) {
            array_push($jsonArr, $resItem['product_type']);

            if (isset($resItem['users'])) {
                foreach ($resItem['users'] as $user) {
                    array_push($jsonArr, $user['display_name']);
                }
            }
            if (isset($resItem['circles'])) {
                foreach ($resItem['circles'] as $circle) {
                    array_push($jsonArr, $circle['title']);
                }
            }

        }
        }
        $currentDate = date("Y-m-d h:i:s");


        if(isset($jsonArr)) {
        $jsonArr = array_unique($jsonArr);
        $dataString = '["' . implode('","', $jsonArr) . '"]';
        }

        else {
            $dataString = "";
        }
        /***************************************/

        $statusCollection = array("items" => array(), "selected" => "");
        $statusCollection["items"] = array("1" => "active", "0" => "inactive");
        $statusCollection["selected"] = (isset($inputs["status"]) ? $inputs["status"] : "inactive");

        $extInt = array("items" => array(), "selected" => "");
        $extInt["items"] = array("0" => "intern", "1" => "extern");
        $extInt["selected"] = (isset($inputs["status"]) ? $inputs["status"] : "intern");

        $pubStatus= array("items" => array(), "selected" => "");
        $pubStatus["items"] = array("1" => "Draft", "0" => "Published");
        $pubStatus["selected"] = (isset($pubStatus["status"]) ? $pubStatus["status"] : "Draft");

        $toolsCollection = array("items" => array(), "selected" => "");
        $toolsCollection["items"] = $this->getProductTypes();
        $toolsCollection["selected"] = (isset($inputs["toolType"]) ? $inputs["toolType"] : "");

        $data = (object)array();
        $data->publish_start = date('Y-m-d H:i');
        $data->publish_end = date('Y-m-d 23:59', strtotime('+50 years'));

        $productTypes = $this->loadProductTypes($paramsArr);
        $tree = $this->treeBuilder($productTypes);
        $selectedKey = "0";
        $html = '<option value="" '.($selectedKey == "" ? 'selected="selected" ' : '' ).' ></option>';
        $html .= $this->selectTreeBuilder($tree,$selectedKey);


        $circles = Circle::select('id', 'title')->get();



        $users = PartnerUser::where('partner_id', '=', '4')
            ->leftJoin('users', 'users.id', '=', 'user_id')
            ->select('partner_id', 'user_id', 'users.id', 'users.first_name', 'users.last_name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as display_name"))
            ->orderBy('display_name', 'asc')
            ->get();


        return view('VersionLog.content')->with(array('result' => $resultArr, 'dataString' => $dataString, 'currentDate' => $currentDate,
            'ProductTypes' => $toolsCollection,
            'statuses' => $statusCollection, 'extInt' => $extInt,'users' => $users, 'html' => $html, 'circles' => $circles,
            'pubstates' => $pubStatus));
    }



    function selectTreeBuilder($elements, $selectedKey='', $count = 0, $asc = "")
    {

        $html = "";
        $count += 2;
        $spaces = "";
        $asc .= "&#8627";


        for ($i = 0; $i < $count - 2; $i++) {
            $spaces .= '&nbsp;';
        }

        if (!empty($spaces)) {
            $spaces .= $asc;
        }


        foreach ($elements as $key => $value) {
            $html .= '<option value="' . $value['id'] . '" '.($selectedKey == $value['id'] ? ' selected="selected" ' : '').' >' . $spaces . ' ' . $value['title'] . '</option>';

            if (array_key_exists('children', $value)) {
                $html .= $this->selectTreeBuilder($value['children'],$selectedKey, $count);
            }
            //$html .= '</option>';
        }

        return $html;

    }


    public function getProductTypes($params = array(), $index = "id")
    {

        $itemRes = (object)ProductTypes::where(function ($query) use ($params) {
            if (isset($params['status'])) {
                if (strlen($params['status']) > 0) {
                    if ($params["status"] != "all") {
                        $query->where('status', '=', $params['status']);
                    }
                }//strlen
            }
        })->orderBy('title', 'asc')
            ->get()->toArray();

        $itemsArr = array();
        if ($itemRes !== false) {
            foreach ($itemRes as $item) {
                $item = (object)$item;
                if (isset($item->$index)) {
                    $itemsArr[$item->$index] = $item;
                } else {
                    $itemsArr[$item->id] = $item;
                }

            }//foreach
        }

        return $itemsArr;
    }

    function treeBuilder($elements, $parentId = 0)
    {

        $tree = [];

        foreach ($elements as $element) {

            if ($element->parent_id == $parentId) {

                $children = self::treeBuilder($elements, $element->id);

                if ($children) {
                    $element->children = $children;
                }
                $tree[] = $element;
            }


        }


        $treeArray = new GeneralHelper();
        $tree = $treeArray->object2array($tree);
        return $tree;
    }

    public function loadProductTypes($params = array(), $index = "id")
    {

        $res = (object)ProductTypes::where(function ($query) use ($params) {

            if (isset($params['status'])) {
                if (strlen($params['status']) > 0) {
                    if ($params["status"] != "all") {
                        $query->where('status', '=', $params['status']);;
                    }
                }//strlen
            }
        })->get()->toArray();


        $itemsArr = array();
        if ($res != false) {
            foreach ($res as $item) {
                $item = (object)$item;
                if (isset ($item->$index)) {
                    $itemsArr[$item->$index] = $item;
                } else {
                    $itemsArr[] = $item;
                }
            }
        }

        return $itemsArr;

    }

    public function store(Request $request) {

        $inputs = $request->input();
        //dd($inputs['product_type']);

        $p = isset($inputs['product_type']);

        $version = new VersionLog();

        if (isset($inputs['extern'])) {
            $version->intern_extern = "1";
        }
        else {
            $version->intern_extern = "0";
        }

        if (isset($inputs['draft'])) {
            $version->publish_start = "1970-12-12 12:12:12";
        }
        else {
            $version->publish_start = date('Y-m-d H:i:s');
        }

        if (!is_numeric($request->input('product_type'))) {
            $version->product_type = "1";
        }

        else {
            $version->product_type = $request->input('product_type');
        }

        $version->title = $request->input('title');
        $version->content = $request->input('content');
        $version->version_development = "tba";
        $version->version = "tba";
        $version->preview_link = "tba";
        $version->status = "1";
        $version->last_updated_by = Auth::user()->id;

        $version->save();

        $inputs = $request->input();


        if (isset($inputs['circles']) && isset($version->id)) {
            foreach ($inputs['circles'] as $circle) {
                $toolCircles = new VersionCircles();

                if ($request->input('product_type') == "") {
                    $toolCircles->product_type = "1";
                }

                else {
                    $toolCircles->product_type = $request->input('product_type');
                }
                $toolCircles->version_id = $version->id;
                $toolCircles->circle_id = $circle;
                $toolCircles->save();
            }
        }
        if (isset($inputs['users']) && isset($version->id)) {
            foreach ($inputs['users'] as $user) {
                $toolUsers = new VersionUsers();
                $toolUsers->version_id = $version->id;
                $toolUsers->user_id = $user;
                $toolUsers->product_type = $version->product_type;
                $toolUsers->save();
            }
        }

        return \Redirect::action('VersionLogController@loadinternpublish');

    }

    public function edit(){
        $id = $_POST['id'];


        $version = VersionLog::find($id);

        $users = PartnerUser::where('partner_id', '=', '4')
            ->leftJoin('users', 'users.id', '=', 'user_id')
            ->select('partner_id', 'user_id', 'users.id', 'users.first_name', 'users.last_name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as display_name"))
            ->orderBy('display_name', 'asc')
            ->get();

        $activeusers = VersionUsers::where('version_id', '=', $id)
            ->leftjoin('users', 'user_id', '=', 'users.id')
            ->select('version_id', 'users.id', 'users.first_name', 'users.last_name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as display_name"))
            ->orderBy('display_name')->get();

        $circles = Circle::select('id', 'title')->get();


        $activecircles = VersionCircles::where('version_id', '=', $id)
            ->leftjoin('circles', 'circle_id', '=', 'circles.id')
            ->select('version_id', 'circles.id', 'circles.title')->get();


        $productTypes = $this->loadProductTypes();
        $tree = $this->treeBuilder($productTypes);

        $selectedKey = $version->product_type;
        $html = '<option value="" '.($selectedKey == "" ? 'selected="selected" ' : '' ).' ></option>';
        $html .= $this->selectTreeBuilder($tree,$selectedKey);

        $modalstring = \View::make('VersionLog.editmodal')->with(array('activecircles' => $activecircles,
            'circles'=>$circles, 'activeusers' => $activeusers, 'users' => $users,
            'version' => $version, 'html' => $html))->render();

        return $modalstring;
    }


    public function destroy($id){

        $version = VersionLog::where('id','=', $id)->first();
        $versionUsers = VersionUsers::where('version_id', '=', $id)->get();
        $versionCircles = VersionCircles::where('version_id', '=', $id)->get();


        foreach($versionUsers as $user){
            $user->delete();
        }

        foreach($versionCircles as $circle){
            $circle->delete();
        }

        if (isset($version->id)) {
            $version->delete();

        }

        return \Redirect::action('VersionLogController@loadinternpublish');
    }

    public function  update (Request $request, $id) {

        $version = VersionLog::find($id);
        $inputs = $request->input();

        if (isset($inputs['extern'])) {
            $version->intern_extern = "1";
        }
        else {
            $version->intern_extern = "0";
        }

        if (isset($inputs['draft'])) {
            $version->publish_start = "1970-12-12 12:12:12";
        }
        else {
            $version->publish_start = date('Y-m-d H:i:s');
        }

        if (!is_numeric($request->input('product_type'))) {
            $version->product_type = "1";
        }

        else {
            $version->product_type = $request->input('product_type');
        }

        $version->title = $request->input('title');
        $version->content = $request->input('content');
        $version->version_development = "tba";
        $version->version = "tba";
        $version->preview_link = "tba";
        $version->status = "1";
        $version->last_updated_by = Auth::user()->id;

        $version->save();

        if (isset($inputs['circles']) && isset($version->id)) {

            VersionCircles::where('version_id', '=', $version->id)->delete();
            foreach ($inputs['circles'] as $circle) {
                $toolCircles = new VersionCircles();

                if ($request->input('product_type') == "") {
                    $toolCircles->product_type = "1";
                }

                else {
                    $toolCircles->product_type = $request->input('product_type');
                }
                $toolCircles->version_id = $version->id;
                $toolCircles->circle_id = $circle;
                $toolCircles->save();
            }
        }
        if (isset($inputs['users']) && isset($version->id)) {

            VersionUsers::where('version_id', '=', $version->id)->delete();

            foreach ($inputs['users'] as $user) {
                $toolUsers = new VersionUsers();
                $toolUsers->version_id = $version->id;
                $toolUsers->user_id = $user;
                $toolUsers->product_type = $version->product_type;
                $toolUsers->save();
            }
        }

        return \Redirect::action('VersionLogController@loadinternpublish');

    }

}
