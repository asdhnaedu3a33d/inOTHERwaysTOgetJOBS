<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_skpd extends CI_Model
{
    var $table = 'm_skpd';

    public function __construct()
    {
        parent::__construct();
    }

    function get_skpd($where = array()){
		$this->db->from($this->table);

		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$this->db->where($key, $value);
			}
		}

		$result = $this->db->get();
		return $result->result_array();
	}
	function get_skpd_all(){
		return $this->db->query("SELECT * FROM m_skpd")->result();
	}
	function get_skpd_byid($id){
		return $this->db->query("SELECT * FROM m_skpd where id_skpd='$id' ")->row();
	}
	
	function get_bidkoor_all(){
		return $this->db->query("SELECT * FROM `m_bidkoordinasi`")->result();
	}
	function get_bidkoor_byid($id){
		return $this->db->query("SELECT * FROM `m_bidkoordinasi` where id_bidkoor='$id'")->row();
	}
	function get_urusan_bybidang($idbidang){
		$data= $this->db->query("SELECT Kd_Urusan FROM `m_bidang` where id='$idbidang'")->row();
		return $data->Kd_Urusan;
	}
	function get_bidang_bybidang($idbidang){
		$data= $this->db->query("SELECT Kd_Bidang FROM `m_bidang` where id='$idbidang'")->row();
		return $data->Kd_Bidang;
		//print_r($data->Kd_Bidang);exit();
	}
	function get_UrusanBidang_byskpd($idskpd){
		$data= $this->db->query("SELECT * FROM m_bidang 
								WHERE `Kd_Bidang` IN (SELECT  Kd_Bidang FROM `m_skpd_urusan` WHERE id_skpd = '$idskpd') AND 
								`Kd_Urusan` IN (SELECT  Kd_Urusan FROM `m_skpd_urusan` WHERE id_skpd = '$idskpd')");
		return $data->result();
	}

	function add_skpd($dataskpd, $databidang){
		//print_r($dataskpd);exit();
 			$this->db->insert( 'm_skpd', $dataskpd);
  			$idskpd=$this->db->insert_id();
 		
 		$Jumlah = count($databidang);
		for ($i=1; $i <= $Jumlah ; $i++){ 
			$Kdurusan = $this->get_urusan_bybidang($databidang[$i]);
			$Kdbidang=$this->get_bidang_bybidang($databidang[$i]);

			$dataBidangUrusan[] = array(
				'id_skpd' => $idskpd,
				'kd_urusan' => $this->get_urusan_bybidang($databidang[$i]),
				'kd_bidang' =>$Kdbidang
			);
		}
			$this->db->insert_batch( 'm_skpd_urusan', $dataBidangUrusan); 
			//print_r($dataBidangUrusan);exit();
	}
	function edit_skpd($dataskpd, $databidang,$idskpd){
			$this->db->where( 'id_skpd', $idskpd);
 			$this->db->update('m_skpd', $dataskpd);

  			$this->db->where( 'id_skpd', $idskpd);
  			$this->db->delete('m_skpd_urusan');
 		
 		$Jumlah = count($databidang);
		for ($i=1; $i <= $Jumlah ; $i++){ 
			$Kdurusan = $this->get_urusan_bybidang($databidang[$i]);
			$Kdbidang=$this->get_bidang_bybidang($databidang[$i]);
			//print_r($Kdurusan);exit();
			$dataBidangUrusan[] = array(
				'id_skpd' => $idskpd,
				'kd_urusan' => $this->get_urusan_bybidang($databidang[$i]),
				'kd_bidang' =>$Kdbidang
			);
		}
			$this->db->insert_batch( 'm_skpd_urusan', $dataBidangUrusan); 
			//print_r($dataBidangUrusan);exit();

	}
	

	

	function get_one_skpd($where = array()){
		$this->db->from($this->table);

		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$this->db->where($key, $value);
			}
		}

		$result = $this->db->get();
		return $result->row();
	}

	function get_data_dropdown_skpd($where = array(), $all=FALSE, $first_null=FALSE){
		$this->db->from($this->table);

		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$this->db->where($key, $value);
			}
		}

		$result = $this->db->get();
		$data = $result->result();

		$result= array();

		if ($first_null) {
			$result[''] = "~~ Pilih SKPD ~~";
		}

		if ($all) {
			$result['all'] = "~~ Semua SKPD ~~";
		}

		foreach ($data as $row) {
			$result[$row->id_skpd] = $row->kode_skpd .". ". $row->nama_skpd;
		}
		return $result;
	}

	function get_skpd_detail($where = array()){
		$this->db->select('m_skpd.*, m_bidkoordinasi.nama_koor');
		$this->db->from($this->table);
		$this->db->join('m_bidkoordinasi', 'm_bidkoordinasi.id_bidkoor=m_skpd.id_bidkoor', 'inner');

		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$this->db->where($key, $value);
			}
		}

		$result = $this->db->get();
		return $result;
	}

	function get_skpd_autocomplete($search){
		$this->db->select('id_skpd AS id, nama_skpd AS label');
		$this->db->like('nama_skpd', $search);
		$result = $this->db->get($this->table);
		return $result->result();
	}

	function get_skpd_chosen(){
		$this->db->select('id_skpd AS id, nama_skpd AS label');
		$result = $this->db->get($this->table);
		return $result->result();
	}

  function get_kode_unit($id_skpd){
		$this->db->select('kode_unit');
    $this->db->where('id_skpd', $id_skpd);
		$result = $this->db->get($this->table);
    $kode_unit = $result->row();
		return $kode_unit->kode_unit;
	}

  function get_kode_sub_unit($id_skpd){
		$this->db->select('id_skpd');
    $this->db->where('kode_unit', $id_skpd);
		$result = $this->db->get($this->table);
    $kode_unit = $result->result();
		return $kode_unit;
	}

  function get_kode_unit_dari_asisten($id_skpd){
    $query = "SELECT id_skpd from m_asisten_sekda where id_asisten = '$id_skpd' limit 1";
    $result = $this->db->query($query);
    $kode_unit = $result->row();
		return $kode_unit->id_skpd;
	}
}
?>
