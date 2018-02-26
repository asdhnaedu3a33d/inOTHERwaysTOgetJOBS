<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @brief Library untuk membuat menu
 *
 * Library untuk membuat menu mulai dari ambil kode menu diijinkan dari
 * database sampai membuat kode HTML
 *
 * @author Putu Praba Santika
 */
class Menu_rbac {

    public $menu_semua=array();
    public $menu_with_parent=array();
    public $menu_sudah=array();
    /**
     * konstruktor, load semua menu yang tersedia masukkan dalam array
     */
    public function __construct(){
        $CI =& get_instance();

        //ambil semua menu
        $CI->db->from('rbac_menu');
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
        $hasil= $this->search_in_array($this->menu_semua,'id_menu',$kode_menu);
        if (is_array($hasil) && isset($hasil[0])){
            return $hasil[0];
        } else return false;

    }
    /**
     * menambahkan parent untuk semua menu
     * @param str_diijinkan kode menu yang diijinkan masih dalam bentuk string
     * @return array menu yang sudah berbentuk array
     */
    function tambah_parent_diijinkan($arr_diijinkan){
         foreach($arr_diijinkan as $ijin){
            $this->menu_parent($ijin);
        }
        return $this->menu_sudah;
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
            $this->menu_sudah[]=$detail_menu['id_menu'];
    }
    /**
     * menambahkan detail untuk masing masing kode menu.
     * @param arr_diijinkan kode menu yang diijinkan masih dalam bentuk array
     * @return array detil menu
     */
    function detail_menu_diijinkan($arr_diijinkan){

        $arr_detail_diijinkan=array();
         foreach($arr_diijinkan as $ijin){
            $detail_ijin=$this->search($ijin);
            $arr_detail_diijinkan[]= $detail_ijin;
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
        if ($do===true){
            $this->Section[] = '<ul>';
            foreach ($hasil as $has){
                $link=base_url().$has['link'];
                $this->Section[] = '<li>';
                if($has['link']!=''){
                  $link=site_url($has['link']);
                    $this->Section[] = anchor($link,$has['text']);
                } else{
                    $this->Section[] = '<a>'.$has['text'].'</a>';
                }

                $this->menu_aktif($arr_detail_diijinkan,$has['id_menu']); //asli

                $this->Section[] = '</li>';
            }
            $this->Section[] = '</ul>';
        }
        return $this->Section;
    }
    /**
     * membuat menu dari array dari kode menu yg boleh diakses
     * @param array array_menu id menu yang ditampilkan berbentuk array
     *
     * @return string kode menu dalam bentuk html, sudah isi <ul> <li>
     */
    function buat_menu($array_menu){
        $withparent=$this->tambah_parent_diijinkan($array_menu);
        $detail_menu_diijinkan=$this->detail_menu_diijinkan($withparent);
        $menu_hasil=$this->menu_aktif($detail_menu_diijinkan,0);
        $menu_jadi=implode("\r\n", $menu_hasil);

        return $this->get_menu_html($detail_menu_diijinkan, 0);
        //return $menu_jadi;
    }

	/**
	 * Build the HTML for the menu
	 */
	function get_menu_html( $items, $root_id = 0 )
	{
    $CI =& get_instance();

    // $current_menu = $CI->db->query("SELECT * FROM rbac_menu WHERE link LIKE '". uri_string() ."%'")->row();
    $current_menu = $CI->db->query("SELECT * FROM rbac_menu WHERE link=?;", array(uri_string()))->row();
    if (!empty($current_menu)) {
      $current_menu = $current_menu->id_menu;
      $CI->session->set_userdata("last_active_menu", $current_menu);
    }else {
      $session_last_menu = $CI->session->userdata("last_active_menu");
      $current_menu = $session_last_menu;
    }

		$this->html  = array();

		foreach ( $items as $item )
			$children[$item['parent']][] = $item;

		// loop will be false if the root has no children (i.e., an empty menu!)
		$loop = !empty( $children[$root_id] );

		// initializing $parent as the root
		$parent = $root_id;
		$parent_stack = array();

		// HTML wrapper for the menu (open)
		$this->html[] = '<ul class="sidebar-menu"><li class="header">MAIN NAVIGATION</li>';

		while ( $loop && ( ( $option = each( $children[$parent] ) ) || ( $parent > $root_id ) ) )
		{
      $flag_active="";
			if ( $option === false )
			{
				$parent = array_pop( $parent_stack );

				// HTML for menu item containing childrens (close)
				$this->html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 ) . '</ul>';
				$this->html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 ) . '</li>';
			}
			elseif ( !empty( $children[$option['value']['id_menu']] ) )
			{
        if ($current_menu==$option['value']['id_menu']) {
          $flag_active = " active";
        }

				// HTML for menu item containing childrens (open)
				$this->html[] = sprintf(
					'<li class="treeview%4$s"><a href="%2$s"><i class="%1$s"></i> <span>%3$s</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>',
					(!empty($option['value']['icon'])?$option['value']['icon']:'fa fa-circle-o'),   // %1$s = tabulation
					'#',//site_url($option['value']['link']),   // %2$s = link (URL)
					$option['value']['text'],   // %3$s = title
          $flag_active
				);
				$this->html[] = '<ul class="treeview-menu">';

				array_push( $parent_stack, $option['value']['parent'] );
				$parent = $option['value']['id_menu'];
			}else{
        if ($current_menu==$option['value']['id_menu']) {
          $flag_active = "active";
        }

				// HTML for menu item with no children (aka "leaf")
				$this->html[] = sprintf(
					'<li class="%4$s"><a href="%2$s"><i class="%1$s"></i> <span>%3$s</span></a></li>',
					(!empty($option['value']['icon'])?$option['value']['icon']:'fa fa-circle-o'),   // %1$s = tabulation
					site_url($option['value']['link']),   // %2$s = link (URL)
					$option['value']['text'],   // %3$s = title
          $flag_active
				);
      }
		}

		// HTML wrapper for the menu (close)
		$this->html[] = '</ul>';

		return implode( "\r\n", $this->html );
	}
}
?>
