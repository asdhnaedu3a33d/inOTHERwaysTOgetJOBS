<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** 
 * @brief Library untuk membuat menu
 * 
 * Library untuk membuat menu mulai dari ambil kode menu diijinkan dari 
 * database sampai membuat kode HTML
 * 
 * @author Putu Praba Santika
 */
class Menu {
    
    public $menu_semua=array();
    public $menu_with_parent=array();
    public $menu_sudah=array();
    /**
     * konstruktor, load semua menu yang tersedia masukkan dalam array
     */
    public function __construct(){
        $CI =& get_instance();
        
        $CI->db->from('m_menu');
        $CI->db->order_by("kode", "asc");
        $query = $CI->db->get();
        $this->menu_semua= $query->result_array();
    }
    /**
     * Pecarian dalam array dalam array untuk key tertentu
     * @param array array yang akan dilakukan pencarian
     * @param key key array yang mau dicari
     * @param value value yang akan dicari
     * @return array hasil search, atau bolean false jika tidak ditemukan
     */
    function search_in_array($array, $key, $value){
        if (is_array($array)){
            $hasil_search=array();
            foreach ($array as $subarray){
                if (isset($subarray[$key]) && $subarray[$key] == $value)
                    $hasil_search[]= $subarray;
                }
            }
        if(isset($hasil_search)){
            return $hasil_search;
        } else return false;
    }
    /**
     * Pencarian kode menu, apakah sudah ada di list atau belum
     * @param kode_menu kode menu yang akan di dicari
     * @return array hasil search, atau bolean false jika tidak ditemukan
     */
    function search($kode_menu){
        $hasil= $this->search_in_array($this->menu_semua,'kode',$kode_menu);
        if (is_array($hasil) && isset($hasil[0])){
            //echo '---'.$kode_menu.'---->>>';print_r($hasil);
            //print_r($hasil);
            return $hasil[0];
        } else return false;
        
    }
    /**
     * menambahkan parent untuk semua menu
     * @param str_diijinkan kode menu yang diijinkan masih dalam bentuk string
     * @return array menu yang sudah berbentuk array
     */
    function tambah_parent_diijinkan($str_diijinkan){
        $arr_diijinkan = explode(",", $str_diijinkan);
         foreach($arr_diijinkan as $ijin){
            $this->menu_parent($ijin);
        }
        return implode(",",$this->menu_sudah);
    }
    /**
     * menambahkan parent untuk tiap kode menu
     * @param kode_parent kode menu yang akan ditambahkan parrent
     * @return array kode_perent yang sudah ditambah parrent
     */
    function menu_parent($kode_parent){
        $detail_menu=$this->search($kode_parent);
        if(!in_array($detail_menu['parent'], $this->menu_sudah,true)){
            if($detail_menu['parent']!=0){
                $this->menu_parent($detail_menu['parent']);
            } 
        }        
            $this->menu_sudah[]=$detail_menu['kode'];
    }
    /**
     * menambahkan detail untuk masing masing kode menu.
     * @param str_diijinkan kode menu yang diijinkan masih dalam bentuk string
     * @return array detil menu
     */
    function detail_menu_diijinkan($str_diijinkan){
        
        $arr_diijinkan = explode(",", $str_diijinkan);
        $arr_detail_diijinkan=array();
         foreach($arr_diijinkan as $ijin){
            //echo 'ijin: '.$ijin;
            $detail_ijin=$this->search($ijin);
           // echo '$detail_ijin: '; print_r($detail_ijin);
            $arr_detail_diijinkan[]= $detail_ijin;
            //echo '$arr_detail_diijinkan: '; print_r($arr_detail_diijinkan);
        }
        return $arr_detail_diijinkan;
    }
    
    public $Section = array();
    /**
     * membuat kode html dari menu
     * @param arr_detail_diijinkan array menu yang sudah ditambahkan parent
     * @return  string kode html menu
     */
    function menu_aktif($arr_detail_diijinkan,$root){
        $hasil=$this->search_in_array($arr_detail_diijinkan, 'parent', $root);
        
        if ($root==0){
            $do=true;
        }else{
            $ada=$this->search_in_array($arr_detail_diijinkan, 'parent', $root);
            //print_r($ada);

            if (isset($ada[0])){
                $do=true;
            } else $do=false;

        }
        
       // echo '   -root-> '.$root;
        //echo '   -do-> '.$do;
        
        if ($do===true){
            $this->Section[] = '<ul>';
            foreach ($hasil as $has){
                $link=base_url().$has['link'];
                $this->Section[] = '<li>';
                if($has['link']!=''){
                  $link=site_url($has['link']);
                    $this->Section[] = anchor($link,$has['nama']);  
                } else{
                    $this->Section[] = '<a>'.$has['nama'].'</a>';
                }
                
                $this->menu_aktif($arr_detail_diijinkan,$has['kode']); //asli
                
                $this->Section[] = '</li>';
            }
            $this->Section[] = '</ul>';
        }
        return $this->Section;
    }
    
}
?>
