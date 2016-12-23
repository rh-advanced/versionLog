<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class VersionLogAjaxRequestController extends Controller {

    private function checkControllerMethods($controller,$method){


        if(method_exists($controller,$method)){
            return true;
        }
        else{

            return false;

        }

    }
    private function checkController($controller){

        if(class_exists($controller)){
            return true;
        }
        else {
            return false;
        }


    }

    public function isArrayOrSet($product)
    {
        if(strpos($product, ',') !== false){
            $lenght = strlen($product);
            if(strpos($product, '[') == 0 && (strpos($product,']') ==  $lenght-1)){
                $product = substr($product, 1, $lenght-2);
                $product = explode(',', $product);
                return $product;
            }

            else{
                $product = explode(',', $product);
                return $product;
            }
        }
        return $product;
    }


    public function run($controller, $method, $product, $inEx, $key, $limit, $pub){




        $parsedProduct = $this->isArrayOrSet($product);


        if ($key == "ySfduUttHi"){
            $controllerName = 'App\\Http\\Controllers\\'.ucfirst($controller).'Controller';




            if ($this->checkController($controllerName) == false) {
                $resArr["status"] = false;
                $resArr["msg"] = "The requested controller does not exist";
                $jsonStr = json_encode($resArr);
                return $jsonStr;
            }

            elseif ($this->checkControllerMethods($controllerName, $method) == false){
                $resArr["status"] = false;
                $resArr["msg"] = "The requested method does not exist in ". $controllerName;
                $jsonStr = json_encode($resArr);
                return $jsonStr;
            }

            else {


                if($this){
                    $obj = new $controllerName();
                }

                if($method == 'getProduct'){
                    $result = $obj->$method($pub, $inEx, $parsedProduct, $limit);
                }

                elseif( $method == 'allProducts') {
                    $result = $obj->$method($index = 'id');
                }


                $arr = array();
                if($result !== false){
                    $arr["status"] = true;
                    $arr["items"] = (isset($result["items"]) ? $result["items"] : '');
                    $arr["msg"] = (isset($result["msg"]) ? $result["msg"] : "Your request returned ". count($arr['items'])." items");

                }else{
                    $arr["status"] = false;
                    $arr["msg"] = "Your request returned ". count($arr['items'])." items";

                }


                $json = json_encode($arr);
                return $json;



            }
        }

        else {
            return "<pre>
                      __------__
                    /~          ~\
                   |    //^\//^\|         Oh noes! Your key is invalid!
                 /~~\  ||  o| |o|:~\     /
                | |6   ||___|_|_||:|    /
                 \__.  /      o  \/'
                  |   (       O   )
         /~~~~\    `\  \         /
        | |~~\ |     )  ~------~`\
       /' |  | |   /     ____ /~~~)\
      (_/'   | | |     /'    |    ( |
             | | |     \    /   __)/ \
             \  \ \      \/    /' \   `\
               \  \|\        /   | |\___|
                 \ |  \____/     | |
                 /^~&gt;  \        _/ &lt;
                |  |         \       \
                |  | \        \        \
                -^-\  \       |        )
                     `\_______/^\______/

</pre></td>";
        }
    }


}


