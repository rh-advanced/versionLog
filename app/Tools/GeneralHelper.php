<?php

namespace App\Tools;

class GeneralHelper{

    public function __construct(){

    }


    public static function generateSelectOptions($optionArray,$selected){
        $options = '';

        $multiSelect = (is_array($selected) ? true : false);

        foreach($optionArray as $key => $value){
            if($multiSelect){
                $options .= '<option value="'.$key.'" '.(in_array($key,$selected) === true ? ' selected="selected" ': '').' >'.$value.'</option>'."\n";
            }
            else{
                $options .= '<option value="'.$key.'" '.($key == $selected ? ' selected="selected"':'').'>'.$value.'</option>'."\n";
            }
        }
        return $options;
    }



    public static function generateMultiBoxSelectOptions($optionArray,$selected,$colName,$boxSize=5){
        $options = '';

        $splitArray = array_chunk($optionArray, $boxSize,true);

        $multiSelect = (is_array($selected) ? true : false);

        $options .= '<div class="multiSelectBoxOptions">';
        foreach($splitArray as $box => $boxContainer){

            $options .= '<div id="box_'.$colName.'" class="multiSelectBox">';
            $containerSize = count($boxContainer);
            $options .= '<select name="'.$colName.'[]" size="'.$containerSize.'" multiple="multiple" >';
            foreach($boxContainer as $key => $selectFieldName){
                if($multiSelect){
                    $options .= '<option value="'.$key.'" '.(in_array($key,$selected) === true ? ' selected="selected" ': '').' >'.$selectFieldName.'</option>'."\n";
                }
                else{
                    $options .= '<option value="'.$key.'" '.($key == $selected ? ' selected="selected"':'').'>'.$selectFieldName.'</option>'."\n";
                }
            }
            $options .= '</select>';
            $options .= '</div>';
        }
        $options .= '<div class="clear"></div>';
        $options .= '</div>';

        return $options;
    }

    /**
     * Generate Checkbox-Options  as ul-list
     *
     * @param string $typename
     * @param array $optionArray
     * @param array $selected
     *
     * @return string $options
     */

    public static function generateCheckboxOptions($typename="default[]",$optionArray=array(),$selected=array(),$elementId=""){
        $options = '<ul class="checkboxList">';
        //Generate
        foreach($optionArray as $key => $selectFieldName){
            $options .= '<li><div style="float:left;"><input type="checkbox" '.(strlen($elementId)>0 ? 'id="'.$elementId.'" ' : '').'  name="'.$typename.'" value="'.$key.'" '.(in_array($key,$selected) === true ? ' checked="checked" ': '').' /></div><div style="float:left;padding:4px 3px 0px 3px;">'.$selectFieldName.'</div><div class="clear"></li>';
            //$options .= '<li '.(strlen($elementId)>0 ? 'id="'.$key.'" ' : '').' ><div style="float:left;"><input type="checkbox" '.(strlen($elementId)>0 ? 'id="'.$elementId.'" ' : '').'  name="'.$typename.'" value="'.$key.'" '.(in_array($key,$selected) === true ? ' checked="checked" ': '').' /></div><div style="float:left;padding:4px 3px 0px 3px;">'.$selectFieldName.'</div><div class="clear"></li>';
        }
        $options .= '</ul>';

        return $options;
    }

    public function object2array($object, $out = array() ){

        foreach ( (array) $object as $index => $node )
            if(count($node)>0){
                $out[$index] = ( is_object ( $node )  ||  is_array ( $node )  ) ? self::object2array ( $node ) : $node;
            }
            else{
                $out[$index] = "";
            }

        return $out;
    }

    /*************************** JSON **************************************************/

    public static function isJson($string,$showError = false) {

        if($showError){
            json_decode($string);
            var_dump(json_last_error());
            return (json_last_error() == JSON_ERROR_NONE);
        }else{
            return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                    is_array(json_decode($string))))) ? true : false;
        }

    }

    public static function validateJsonString($jsonStr){

        if(is_object(json_decode($jsonStr))){
            return json_decode($jsonStr);
        }
        else{
            $jsonStr = preg_replace('/[ ]{2,}|[\t]/', ' ', trim($jsonStr));
            return self::validateJsonString($jsonStr);
            //return $this->checkJsonString(substr($jsonStr,0,-3));
        }
    }



    /******************************** DEBUGGER ***************************************/

    public static function debug(){
        static $start_time = NULL;
        static $start_code_line = 0;

        $debugArr = debug_backtrace();
        $call_info = array_shift( $debugArr );
        $code_line = $call_info['line'];

        $callFile =  explode('/', $call_info['file']);
        $file = array_pop($callFile);

        if( $start_time === NULL )
        {
            print "debug ".$file."> initialize\n";
            $start_time = time() + microtime();
            $start_code_line = $code_line;
            return 0;
        }

        printf("debug %s> code-lines: %d-%d time: %.4f mem: %d KB\n", $file, $start_code_line, $code_line, (time() + microtime() - $start_time), ceil( memory_get_usage()/1024));
        $start_time = time() + microtime();
        $start_code_line = $code_line;
    }


}

?>