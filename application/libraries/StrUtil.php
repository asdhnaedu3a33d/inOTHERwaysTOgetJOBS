<?php

/**
 * @author I Made Agus setiawan
 * @copyright 2013
 */

class StrUtil {
    
    
    static function getShortStr($str, $length) 
    {
        $dots = "...";
        if (strlen($str) < $length)
            $dots = "";
            
        return substr($str,0,$length).$dots;
    }
}


?>