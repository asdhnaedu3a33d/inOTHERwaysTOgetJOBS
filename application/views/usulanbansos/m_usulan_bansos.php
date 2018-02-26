<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_usulan_bansos extends CI_Model 
{
	var $table = 't_usulanpro';	

	var $id_status_baru = "1";
	var $id_status_send = "2";
	var $id_status_revisi = "3";
	var $id_status_approved = "4";
	var $table_usulan = 't_usulanpro';
    var $primary_usulan = 'id';
	var $table_groups = 'm_groups';
	var $primary_groups = 'id_groups';

	var $table_musrenbang = 't_usulan_bansos';
	var $primary_musrenbang = 'id_usulan_bansos';

    public function __construct()
    {
        parent::__construct();           
    }    
	
	private function create_history($data){		
		$data['created_date'] = date("Y-m-d H:i:s");
		$data['created_by'] = $this->session->userdata('username');
		return $data;
	}

	private function change_history($data){		
		$data['changed_date'] = date("Y-m-d H:i:s");
		$data['changed_by'] = $this->session->userdata('username');
		return $data;
	}

	function add($data){		
		$data = $this->create_history($data);
		$result = $this->db->insert($this->table, $data);
		return $result;
	} 

	function edit($data, $id){
		$data = $this->change_history($data);
		$this->db->where('id', $id);
		$result = $this->db->update($this->table, $data);
		return $result;
	} 

	function delete($id, $id_group){		
		$this->db->where('id_usulan_bansos', $id);
		//$this->db->where("id_group", $id_group);
		$result = $this->db->delete('t_usulan_bansos'); 
		return $result;
	}
	
	/*function get_data_table($search, $start, $length, $order){
		$order_arr = array('id','kd_urusan','kd_bidang','kd_program','kd_kegiatan','jenis_kegiatan');
        $sql="
        SELECT DISTINCT `t_musrenbang`.*,renstra.`ket_program`,renstra.`ket_kegiatan`
            FROM (`t_musrenbang`) 
        LEFT JOIN t_renstra_rkpd AS renstra 
            ON t_musrenbang.`kd_urusan`=renstra.kd_urusan AND t_musrenbang.`kd_bidang`=renstra.kd_bidang AND t_musrenbang.`kd_program`=renstra.`kd_program` AND t_musrenbang.`kd_kegiatan`=renstra.`kd_kegiatan`
            WHERE `t_musrenbang`.`kd_urusan` LIKE '%".$search['value']."%' 
            OR `t_musrenbang`.`kd_bidang` LIKE '%".$search['value']."%' 
            OR `t_musrenbang`.`kd_program` LIKE '%".$search['value']."%' 
            OR `t_musrenbang`.`kd_kegiatan` LIKE '%".$search['value']."%' 
            OR `t_musrenbang`.`jenis_pekerjaan` LIKE '%".$search['value']."%'
        ";
		$this->db->limit($length, $start);
		$this->db->order_by($order_arr[$order["column"]], $order["dir"]); 
        
		$result = $this->db->query($sql);
		return $result->result();
	}*/

	function get_data_with_rincian($id)
	{
		/*
		$sql="
		SELECT u.id,u.id_groups,u.id_skpd,u.id_kec,u.id_desa,u.jenis_pekerjaan,u.volume,u.satuan,u.lokasi,u.catatan,d.nama_desa,
		k.nama_kec, t.nama_group,u.nama_dewan, u.file, s.nama_skpd
        FROM ".$this->table_usulan." AS u
        INNER JOIN m_groups AS t ON u.id_groups = t.id_groups
        INNER JOIN m_skpd AS s ON u.id_skpd = s.id_skpd
        INNER JOIN m_kecamatan AS k ON u.id_kec = k.id_kec
        INNER JOIN m_desa AS d ON u.id_desa = d.id_desa AND u.id_kec = d.id_kec
        INNER JOIN m_status_usulanpro AS m ON u.id_status = m.id 
        WHERE u.id=?
		";
		*/

		$sql = "select t.*,s.nama_skpd,g.nama_group,k.nama_kec,d.nama_desa from (select * from t_usulan_bansos where id_usulan_bansos = ?) as t
			left join m_skpd as s
			on t.id_skpd = s.id_skpd
			left join m_groups as g
			on t.id_groups = g.id_groups
			left join m_kecamatan as k
			on t.id_kecamatan = k.id_kec
			left join m_desa as d
			on t.id_desa = d.id_desa";

		$query = $this->db->query($sql, array($id));
		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

		return NULL;
	}

	//DIGANTI
	function get_one_usulan($id_usulan, $id_group=NULL){
		$this->db->from($this->table);
		if (!empty($id_group)) {
			$this->db->where("id_group", $id_group);
		}		
		$this->db->where("id", $id_usulan);		
		$result = $this->db->get();
		return $result->row();
	}

	//DIGANTI
	function get_one_usulan_detail($id_usulan, $status=NULL){
		$this->db->select("t_usulanpro.*");
		$this->db->select("status_usulanpro");
		$this->db->select("m_skpd.nama_skpd");
		$this->db->select("m_desa.nama_desa");
		$this->db->select("m_kecamatan.nama_kec");
		$this->db->from($this->table);
					
		$this->db->where("t_usulanpro.id", $id_usulan);
		if (!empty($id_group)) {
			$this->db->where("id_group", $id_group);
		}

		if ($status=="BARU") {
			$this->db->where("id_status", $this->id_status_baru);
		}elseif ($status=="VERIFIKASI") {
			$this->db->where("id_status", $this->id_status_send);
		}elseif ($status=="APPROVED") {
			$this->db->where("id_status", $this->id_status_approved);
		}

		$this->db->join("m_skpd","t_usulanpro.id_skpd = m_skpd.id_skpd","inner");
		$this->db->join("m_kecamatan","t_usulanpro.id_kec = m_kecamatan.id_kec","inner");
		$this->db->join("m_desa","t_usulanpro.id_desa = m_desa.id_desa AND t_usulanpro.id_kec = m_desa.id_kec","inner");
		$this->db->join("m_status_usulanpro","t_usulanpro.id_status = m_status_usulanpro.id","inner");
		
		$result = $this->db->get();
		return $result->row();
	}	

	//DIGANTI
	function get_all_usulan($search, $start, $length, $order, $order_arr, $status = NULL){		
		/*
		$this->db->select("t_usulanpro.*");
		$this->db->select("status_usulanpro");
		$this->db->select("m_skpd.nama_skpd");
		$this->db->select("m_desa.nama_desa");
		$this->db->select("m_kecamatan.nama_kec");
		$this->db->select("m_groups.nama_group");
		
		$this->db->from($this->table);
				
		if (!is_null($search)) {
			$this->db->where("(nama_skpd LIKE '%". $search['value'] ."%' OR nama_kec LIKE '%". $search['value'] ."%' OR nama_desa LIKE '%". $search['value'] ."%' OR jenis_pekerjaan LIKE '%". $search['value'] ."%' OR status_usulanpro LIKE '%". $search['value'] ."%')");
		}
		
		if (!is_null($length) && !is_null($start)) {
			$this->db->limit($length, $start);
		}
		//if (!is_null($order)) {
			$this->db->order_by($order_arr[$order["column"]], $order["dir"]); 
		//}		

		$this->db->join("m_skpd","t_usulanpro.id_skpd = m_skpd.id_skpd","inner");
		$this->db->join("m_groups","t_usulanpro.id_groups = m_groups.id_groups","inner");
		$this->db->join("m_kecamatan","t_usulanpro.id_kec = m_kecamatan.id_kec","inner");
		$this->db->join("m_desa","t_usulanpro.id_desa = m_desa.id_desa AND t_usulanpro.id_kec = m_desa.id_kec","inner");
		$this->db->join("m_status_usulanpro","t_usulanpro.id_status = m_status_usulanpro.id","inner");
		$result = $this->db->get();
		*/
		$ta = $this->m_settings->get_tahun_anggaran();
		$user_skpd = $this->session->userdata('username');
		$sql = "
			select * from (
				select t.*,s.nama_skpd,g.nama_group,k.nama_kec,d.nama_desa from (select * from t_usulan_bansos) as t
				left join m_skpd as s
				on t.id_skpd = s.id_skpd
				left join m_groups as g
				on t.id_groups = g.id_groups
				left join m_kecamatan as k
				on t.id_kecamatan = k.id_kec
				left join m_desa as d
				on t.id_desa = d.id_desa ) as a
			where (
				nama_skpd LIKE '%".$search['value']."%' 
				OR nama_kec LIKE '%".$search['value']."%' 
				OR nama_desa LIKE '%".$search['value']."%' 
				OR jenis_pekerjaan LIKE '%".$search['value']."%'
				) 
				AND tahun = ".$ta." 
				AND created_by = '".$user_skpd."'
			
		";
		return $this->db->query($sql)->result();
	}

	//DIGANTI
	function count_all_usulan($search, $status = NULL){
		/*
		$this->db->from($this->table);
		if (!is_null($search)) {
			$this->db->where("(nama_skpd LIKE '%". $search['value'] ."%' OR nama_kec LIKE '%". $search['value'] ."%' OR nama_desa LIKE '%". $search['value'] ."%' OR jenis_pekerjaan LIKE '%". $search['value'] ."%' OR status_usulanpro LIKE '%". $search['value'] ."%')");
		}

		$this->db->join("m_skpd","t_usulanpro.id_skpd = m_skpd.id_skpd","inner");
		$this->db->join("m_kecamatan","t_usulanpro.id_kec = m_kecamatan.id_kec","inner");
		$this->db->join("m_desa","t_usulanpro.id_desa = m_desa.id_desa AND t_usulanpro.id_kec = m_desa.id_kec","inner");
		$this->db->join("m_status_usulanpro","t_usulanpro.id_status = m_status_usulanpro.id","inner");
		$result = $this->db->count_all_results();
		*/
		$ta = $this->m_settings->get_tahun_anggaran();
		$user_skpd = $this->session->userdata('username');
		$sql = "
			select * from (
				select t.*,s.nama_skpd,g.nama_group,k.nama_kec,d.nama_desa from (select * from t_usulan_bansos) as t
				left join m_skpd as s
				on t.id_skpd = s.id_skpd
				left join m_groups as g
				on t.id_groups = g.id_groups
				left join m_kecamatan as k
				on t.id_kecamatan = k.id_kec
				left join m_desa as d
				on t.id_desa = d.id_desa ) as a
			where (
				nama_skpd LIKE '%".$search['value']."%' 
				OR nama_kec LIKE '%".$search['value']."%' 
				OR nama_desa LIKE '%".$search['value']."%' 
				OR jenis_pekerjaan LIKE '%".$search['value']."%' 
				) AND tahun = ".$ta." 
				AND created_by = '".$user_skpd."' 
		";
		return $this->db->query($sql)->num_rows();
	}

	function get_all_id_renstra_veri_or_approved_to_json($id_skpd){
		$this->db->select("GROUP_CONCAT(id) AS id");
		$this->db->where("id_status !=", $this->id_status_baru);
		$this->db->from($this->table);
		$result = $this->db->get();
		$data = $result->row();
		$id_array = explode(",", $data->id);
		return json_encode($id_array);
	}

	function get_all_renstra_by_in($id, $noresult=FALSE){
		$this->db->select("t_renstra.*");
		$this->db->select("status_renstra");
		$this->db->where_in('t_renstra.id', $id);
		$this->db->from($this->table);		
		$this->db->join("m_status_renstra","t_renstra.id_status = m_status_renstra.id","inner");
		$result = $this->db->get();
		if (!$noresult) {
			return $result->result();
		}else{
			return $result;
		}
	}

	function get_total_nominal_renstra($id_skpd=NULL, $status = NULL){
		$this->db->select('COUNT(t_renstra.id) AS count');
		$this->db->select_sum('nominal_1');
		$this->db->select_sum('nominal_2');
		$this->db->select_sum('nominal_3');
		$this->db->select_sum('nominal_4');
		$this->db->select_sum('nominal_5');

		if ($status=="BARU") {
			$this->db->where("id_status", $this->id_status_baru);
		}elseif ($status=="VERIFIKASI") {
			$this->db->where("id_status", $this->id_status_send);
		}elseif ($status=="APPROVED") {
			$this->db->where("id_status", $this->id_status_approved);
		}

		if (!is_null($id_skpd) && $id_skpd != "all") {
			$this->db->where("t_renstra.id_skpd", $id_skpd);
		}		

		$this->db->from($this->table);

		$result = $this->db->get();		
		return $result->row();		
	}

	function get_total_nominal_renstra_by_in($id){
		$this->db->select('COUNT(t_renstra.id) AS count');
		$this->db->select_sum('nominal_1');
		$this->db->select_sum('nominal_2');
		$this->db->select_sum('nominal_3');
		$this->db->select_sum('nominal_4');
		$this->db->select_sum('nominal_5');
		$this->db->where_in('t_renstra.id', $id);
		$this->db->where("id_status", $this->id_status_approved);
		$this->db->from($this->table);
		$this->db->join("m_status_renstra","t_renstra.id_status = m_status_renstra.id","inner");		
		$result = $this->db->get();		
		return $result->row();		
	}	

	function send_renstra($id, $id_skpd){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->select("id");
		$this->db->from($this->table);
		$this->db->where_in('id', $id);
		$this->db->where("id_skpd", $id_skpd);
		$this->db->where("(id_status=". $this->id_status_baru ." OR id_status=". $this->id_status_revisi .")");
		$result = $this->db->get();
		$result = $result->result();		
		
		foreach ($result as $value) {
			$id = $value->id;
			$this->db->set("id_status", $this->id_status_send);
			$this->db->where("id", $value->id);
			$this->db->update($this->table);

			$this->add_history($value->id, $this->send);
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function delete_sended_renstra($id, $id_skpd){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->where("id_skpd", $id_skpd);
		$this->db->set("id_status", $this->id_status_baru);		
		$result = $this->db->update($this->table);

		$this->add_history($id, $this->delete_from_sended_list);
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function approved_renstra($id){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->set("id_status", $this->id_status_approved);
		$result = $this->db->update($this->table);

		$this->add_history($id, $this->approved);
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function not_approved_renstra($id, $ket){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->set("id_status", $this->id_status_revisi);		
		$result = $this->db->update($this->table);

		$this->add_history($id, $this->revisi, $ket);
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	function add_file($file, $name, $ket, $location){
		$this->db->set('file', $file); 
		$this->db->set('name', $name); 
		$this->db->set('ket', $ket); 
		$this->db->set('location', $location); 
		$this->db->insert('t_upload_file'); 
		return $this->db->insert_id();
		
	}

	function delete_file($id){
		$this->db->where("id", $id);
		$result = $this->db->delete('t_upload_file'); 
		return $result;
	}

	function get_file($id = array(), $only = FALSE){		
		$this->db->where_in("id", $id);
		$this->db->from('t_upload_file');
		$result = $this->db->get(); 
		if ($only) {
			return $result;
		}else{
			return $result->result();
		}		
	}

	function get_one_file($id){		
		$this->db->where("id", $id);
		$this->db->from('t_upload_file');
		$result = $this->db->get();		
		return $result->row();		
	}

	function update_file($id, $data){		
		$this->db->where('id', $id);
		$result = $this->db->update('t_upload_file', $data); 
		return $result;
	}
	function insert($data,$table) {
        $this->db->insert($this->$table,$data);
        return $this->db->insert_id();
    }
    function update($id,$data,$table,$primary) {
        $this->db->where($this->$primary,$id);
        return $this->db->update($this->$table,$data);
    }
    function hard_delete($data,$table){
        return $this->db->delete($this->$table, $data); 
    }

    function get_data($data,$table){
        $this->db->where($data);
        $query = $this->db->get($this->$table);
        return $query->row();
    }
	
	function get_temuwirasa_cetak($ta){
		$sql = "SELECT * FROM (
					SELECT t.*,s.nama_skpd,g.nama_group,k.nama_kec,d.nama_desa,u.`nama` AS status, kep.`nama` AS nama_keputusan FROM (
						SELECT * FROM t_musrenbang WHERE id_groups <> '1') AS t
						LEFT JOIN m_skpd AS s 		ON t.id_skpd = s.id_skpd
						LEFT JOIN m_groups AS g 	ON t.id_groups = g.id_groups
						LEFT JOIN m_kecamatan AS k 	ON t.id_kecamatan = k.id_kec
						LEFT JOIN m_desa AS d		ON t.id_desa = d.id_desa
						LEFT JOIN m_status_usulan AS u 	ON t.id_status_usulan = u.`id`
						LEFT JOIN m_keputusan AS kep	ON t.id_keputusan = kep.`id_keputusan` 
						 
					) AS a
				WHERE tahun = ".$ta." AND (id_groups = 4 OR id_groups = 5)";
		return $this->db->query($sql)->result();
	}
	
	function get_pokir_cetak($ta){
		$sql = "SELECT * FROM (
					SELECT t.*,s.nama_skpd,g.nama_group,k.nama_kec,d.nama_desa,u.`nama` AS status, kep.`nama` AS nama_keputusan FROM (
						SELECT * FROM t_musrenbang WHERE id_groups <> '1' AND flag_delete !=1 ) AS t
						LEFT JOIN m_skpd AS s 		ON t.id_skpd = s.id_skpd
						LEFT JOIN m_groups AS g 	ON t.id_groups = g.id_groups
						LEFT JOIN m_kecamatan AS k 	ON t.id_kecamatan = k.id_kec
						LEFT JOIN m_desa AS d		ON t.id_desa = d.id_desa
						LEFT JOIN m_status_usulan AS u 	ON t.id_status_usulan = u.`id`
						LEFT JOIN m_keputusan AS kep	ON t.id_keputusan = kep.`id_keputusan`  
					) AS a
				WHERE tahun = ".$ta." AND id_groups = 6";
		return $this->db->query($sql)->result();
	}
}
?>