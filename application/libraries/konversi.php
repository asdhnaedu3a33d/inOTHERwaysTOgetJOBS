<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Konversi{
    var $CI = NULL;
    function __construct()
    {
        // get CI's object
        $this->CI =& get_instance();
    }
    function ctword($x) {
            $x = abs($x);
            $number = array("", "satu", "dua", "tiga", "empat", "lima",
            "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
            $temp = "";

            if ($x <12) {
            $temp = "". $number[$x];
            } else if ($x <20) {
            $temp = $this->ctword($x - 10)." belas";
            } else if ($x <100) {
            $temp = $this->ctword($x/10)." puluh ". $this->ctword($x % 10);
            } else if ($x <200) {
            $temp = " seratus " . $this->ctword($x - 100);
            } else if ($x <1000) {
            $temp = $this->ctword($x/100) . " ratus " . $this->ctword($x % 100);
            } else if ($x <2000) {
            $temp = " seribu " . $this->ctword($x - 1000);
            } else if ($x <1000000) {
            $temp = $this->ctword($x/1000) . " ribu " .$this-> ctword($x % 1000);
            } else if ($x <1000000000) {
            $temp = $this->ctword($x/1000000) . " juta " . $this->ctword($x % 1000000);
            } else if ($x <1000000000000) {
            $temp = $this->ctword($x/1000000000) . " milyar " . $this->ctword(fmod($x,1000000000));
            } else if ($x <1000000000000000) {
            $temp = $this->ctword($x/1000000000000) . " trilyun " . $this->ctword(fmod($x,1000000000000));
            }
            return $temp;
            }
    function terbilang($x,$style=4,$strcomma=".") {
            if($x<0) {
            $result = "minus ". trim(ctword($x));
            } else {
            $arrnum=explode("$strcomma",$x);
            $arrcount=count($arrnum);
            if ($arrcount==1){
            $result = trim($this->ctword($x));
            }else if ($arrcount>1){
				if (trim($this->ctword($arrnum[1]))>0){
					$result = trim($this->ctword($arrnum[0])) . " koma " . trim($this->ctword($arrnum[1]));
				}else{
					$result = trim($this->ctword($arrnum[0]));
				}
            
            }
            }
            switch ($style) {
            case 1: //1=uppercase  dan
            $result = strtoupper($result);
            break;
            case 2: //2= lowercase
            $result = strtolower($result);
            break;
            case 3: //3= uppercase on first letter for each word
            $result = ucwords($result);
            break;
            default: //4= uppercase on first letter
            $result = ucfirst($result);
            break;
            }
            return $result;
            }
                
} 
   
// by irfani.firdausy.com

