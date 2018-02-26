<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author putuariepra
 */

class Global_function {
	var $CI = NULL;
	function __construct(){
		$this->CI =& get_instance();
	}

    function clean_array($data=array(), $clean=array()){
        foreach ($clean as $value) {
            unset($data[$value]);
        }
        return $data;
    }

    function add_array($data=array(), $add=array()){
        foreach ($add as $key => $value) {
            $data[$key] = $value;
        }
        return $data;
    }

    function change_array($data=array(), $change=array()){
        foreach ($change as $key => $value) {
            $data[$value] = $data[$key];
            unset($data[$key]);
        }
        return $data;
    }

		function re_index($arraygen){
			$arraygen['kode_sumber_dana'] = array_combine(range(1, count($arraygen['kode_sumber_dana'])), array_values($arraygen['kode_sumber_dana']));
			$arraygen['kode_jenis_belanja'] = array_combine(range(1, count($arraygen['kode_jenis_belanja'])), array_values($arraygen['kode_jenis_belanja']));
			$arraygen['kode_kategori_belanja'] = array_combine(range(1, count($arraygen['kode_kategori_belanja'])), array_values($arraygen['kode_kategori_belanja']));
			$arraygen['kode_sub_kategori_belanja'] = array_combine(range(1, count($arraygen['kode_sub_kategori_belanja'])), array_values($arraygen['kode_sub_kategori_belanja']));
			$arraygen['kode_belanja'] = array_combine(range(1, count($arraygen['kode_belanja'])), array_values($arraygen['kode_belanja']));
			$arraygen['uraian_belanja'] = array_combine(range(1, count($arraygen['uraian_belanja'])), array_values($arraygen['uraian_belanja']));
			$arraygen['detil_uraian_belanja'] = array_combine(range(1, count($arraygen['detil_uraian_belanja'])), array_values($arraygen['detil_uraian_belanja']));
			$arraygen['volume'] = array_combine(range(1, count($arraygen['volume'])), array_values($arraygen['volume']));
			$arraygen['satuan'] = array_combine(range(1, count($arraygen['satuan'])), array_values($arraygen['satuan']));
			$arraygen['nominal_satuan'] = array_combine(range(1, count($arraygen['nominal_satuan'])), array_values($arraygen['nominal_satuan']));
			$arraygen['subtotal'] = array_combine(range(1, count($arraygen['subtotal'])), array_values($arraygen['subtotal']));
			return $arraygen;
		}
}
