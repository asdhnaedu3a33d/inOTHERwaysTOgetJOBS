<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*! library untuk konfersi uang dan angka */
/** 
 * @brief Library untuk konfersi uang dan angka dan tanggal
 * 
 * Library untuk konfersi uang dan angka dan tanggal
 * 
 * @author Putu Praba Santika
 */
class Uang {
    public function __construct(){
        $CI =& get_instance();
    }
    /**
     * Konversi angka menjadi format uang dengan Rp dan pemisah ribuan
     * @angka integer angkan yang akan dikonversi
     * @return string angka yang sudah diformat
     */
    static function angka($angka, $currency = false){
        $cur = '';

        $rupiah =  number_format($angka,2, ",","."); 
        
        if($currency) $cur = 'Rp. ';

        $rupiah = $cur . $rupiah; 

        return $rupiah;
    }
    /**
     * Konversi angka menjadi format persen
     * @angka integer angkan yang akan dikonversi
     * @return string angka yang sudah diformat
     */
    function persen($angka){
        $rupiah =  number_format($angka,2, ",","."); 
        $rupiah = $rupiah . " %"; 
        return $rupiah;
    }
    /**
     * konversi angka ke huruf terbilang per blok
     * @param x angka  yang akan dikonversi
     * @return string terbilang
     */
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
    /**
     * penyesuaian format konversi angka ke terbilang
     * @param x angka yang akan konversi
     * @param style jenis penulisan(kapital, kecil)
     * @param strcomma pemisah angka dibelakang koma
     * @return string terbilang
     */
    function terbilang($x,$style=4,$strcomma=".") {
        if($x<0) {
            $result = "minus ". trim(ctword($x));
        }else if ($x==0){
            $result=" nol ";
        } else {
            $arrnum=explode("$strcomma",$x);
            $arrcount=count($arrnum);
            if ($arrcount==1){
                $result = trim($this->ctword($x));
            }else if ($arrcount>1){
                $result = trim($this->ctword($arrnum[0]));
                if($arrnum[1]!=0){
                   $result.=" koma " . trim($this->ctword($arrnum[1]));
                }
            }
        }
        
        $result.=" rupiah";
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
    /**
     * Konversi tahun ajaran dalam format 20122013E ke 2012/2013 Genap
     * @param thn tahun ajaran (20122013E)
     * @return string hasil konversi 2012/2013 Genap
     */
    function th_ajaran($thn){
        if(substr($thn,8,1)=='E'){
            $semester='Genap';
        }else{
            $semester='Ganjil';
        }
        return substr($thn,0,4)."/".substr($thn,4,4)." ".$semester;
    }
    public $bulan=array(
        '1'=>'Januari',
        '2'=>'Februari',
        '3'=>'Maret',
        '4'=>'April',
        '5'=>'Mei',
        '6'=>'Juni',
        '7'=>'Juli',
        '8'=>'Agustus',
        '9'=>'September',
        '10'=>'Oktober',
        '11'=>'November',
        '12'=>'Desember'
    );
    /**
     * Konversi format tanggal ke format bahasa indonesia
     * @param tgl tanggal dalam format 2013-04-15
     * @return string hasil konversi  15 April 2013
     */
    function tanggal($tgl){
        $date = date_create($tgl);
        //return date_format($date, 'd/m/Y');
        return date_format($date, 'd')." ".$this->bulan[date_format($date, 'n')]." ".date_format($date, 'Y');
    }
    
    /**
     * Konversi format tanggal jam ke format bahasa indonesia
     * @param tgl tanggal dalam format 2013-04-15 14:40:37
     * @return string hasil konversi  15 April 2013 14:40:37
     */
    function tanggal_jam($tgl){
        $date = date_create($tgl);
        //return date_format($date, 'd/m/Y');
        return date_format($date, 'd')." ".$this->bulan[date_format($date, 'n')]." ".date_format($date, 'Y')." ".date_format($date, 'H:i:s');
    }

}
