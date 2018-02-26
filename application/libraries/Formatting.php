<?php

/**
 * @author I Made Agus Setiawan
 * @copyright 2013
 */

class Formatting {

    static function currency($str, $decimal = "0", $cur_flag = false, $country = "id"){
    	/*
    		pre : $str berisi data number
    	*/

        #### ADDED BY ARIE, UNTUK SEN OTOMATIS TAMPIL JIKA ADA SAJA ####
        if (is_numeric( $str ) && floor( $str ) != $str) {
            $decimal = "2";
        }
    	$cur_sign = "";
    	switch($country){
    		case "id":
    			if($cur_flag) $cur_sign = "Rp.";
    			$str = $cur_sign.number_format($str,$decimal,",",".");
    		break;
    	}

    	return $str;
    }

    static function bas_format($str) {

        $pattern = '/(\d{6})(\d{3})(\d{3})/';
        $replace = '\1-\2.\3';
        $formated_kode = preg_replace($pattern, $replace, $str);

        return $formated_kode;
    }

    static function nip_format($str) {

        if(strlen($str) <= 9) {
            return $str;
        }

        $pattern = '/(\d{8})(\d{6})(\d{1})(\d+)/';
        $replace = '\1 \2 \3 \4';
        $formated_kode = preg_replace($pattern, $replace, $str);

        return $formated_kode;
    }

    static function get_datetime() {
        return date("Y-m-d H:i:s");
    }

    static function date_format($date, $type = "datetime") {

        switch($type) {
            case "date_mysql":
                return date("Y-m-d", strtotime($date));
            case "datetime_mysql":
                return date("Y-m-d H:i:s", strtotime($date));
            case "datetime":
                return date("d-m-Y H:i:s", strtotime($date));
            case "date":
                return date("d-m-Y", strtotime($date));
            case "date_locale":
                return strftime("%d %B %Y", strtotime($date));
        }
    }

    static function dateINA($date, $with_date=FALSE, $format="Y-m-d"){
        $bln_arr = array(
                            1 => "Januari",
                            2 => "Februari",
                            3 => "Maret",
                            4 => "April",
                            5 => "Mei",
                            6 => "Juni",
                            7 => "Juli",
                            8 => "Agustus",
                            9 => "September",
                            10 => "Oktober",
                            11 => "November",
                            12 => "Desember"
                        );
        if (!empty($date)) {
            $d = DateTime::createFromFormat($format, $date);

            $tahun = $d->format("Y");
            $bulan = $d->format("n");
            $tgl   = $d->format("d");

            $result = $tgl . " " . $bln_arr[$bulan] . " ". $tahun;

            if ($with_date) {
                $result .= " ".$d->format("H");
                $result .= ":".$d->format("i");
                $result .= ":".$d->format("s");
            }
            return $result;
        }else{
            return NULL;
        }
    }
}

?>
