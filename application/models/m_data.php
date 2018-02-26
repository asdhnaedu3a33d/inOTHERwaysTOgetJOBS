<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class M_data extends CI_Model
	{
		//variabel tabel target
		var $table_urusan = 'm_urusan';
		var $table_bidang = 'm_bidang';
		var $table_program = 'm_program';
		var $table_kegiatan = 'm_kegiatan';
		var $table_fungsi = 'm_fungsi';

		//variabel id target
		var $primary_bidang = 'id';
		var $primary_program = 'id';
		var $primary_kegiatan = 'id';

//------------------------------------FUNGSI GLOBAL---------------------------------------//
		function add_data()
		{
		$data = $this->global_function->add_array($data, $add);		

		$result = $this->db->insert($this->table_bidang, $data);
		return $result;
		}

		function get_data($data,$table){
	        $this->db->where($data);
	        $query = $this->db->get($this->$table);
	        return $query->row();
	    }

	    function get_data_with_rincian($id,$table)
		{
			$sql="
				SELECT * FROM ".$this->$table."
				WHERE id = ?
			";

			$query = $this->db->query($sql, array($id));

			if($query) {
					if($query->num_rows() > 0) {
						return $query->row();
					}
				}

				return NULL;
		}
//------------------------------------------------------------------------------------------//

//---------------------------------------FUNGSI BIDANG--------------------------------------//
	    function get_data_table_bidang($search, $start, $length, $order)
		{
			$order_arr = array('id','Kd_Urusan','Kd_Bidang');
			$sql="
				SELECT * FROM (
				SELECT b.`id`,b.`Kd_Urusan`,b.`Kd_Bidang`,b.`Nm_Bidang`,b.`Kd_Fungsi`,u.`Nm_Urusan`,f.`Nm_Fungsi` 
				FROM ".$this->table_bidang." AS b 
					LEFT JOIN ".$this->table_urusan." AS u ON b.Kd_Urusan=u.Kd_Urusan
					LEFT JOIN ".$this->table_fungsi." AS f ON b.Kd_Fungsi=f.Kd_Fungsi
				WHERE (b.Kd_Urusan LIKE '%".$search['value']."%'
	            OR b.Kd_Bidang LIKE '%".$search['value']."%'
	            OR b.Nm_Bidang LIKE '%".$search['value']."%'
	            OR b.Kd_Fungsi LIKE '%".$search['value']."%'
	            OR u.Nm_Urusan LIKE '%".$search['value']."%'
	            OR f.Nm_Fungsi LIKE '%".$search['value']."%')
	            ) AS a
				ORDER BY ".$order_arr[$order["column"]]." ".$order["dir"]."
            	LIMIT $start,$length
			";
			//$this->db->limit($length, $start);
			//$this->db->order_by($order_arr[$order["column"]], $order["dir"]);

			$result = $this->db->query($sql);
			return $result->result();
		}

		function count_data_table_bidang($search, $start, $length, $order)
		{
			/*
			$this->db->from($this->table_bidang);
			
			$this->db->like("Kd_Urusan", $search['value']);
			$this->db->or_like("Kd_Bidang", $search['value']);
			$this->db->or_like("Nm_Bidang", $search['value']);
					
			$result = $this->db->count_all_results();
			return $result; */
			$sql="
			SELECT COUNT(*) AS jumlah FROM (
				SELECT b.`id`,b.`Kd_Urusan`,b.`Kd_Bidang`,b.`Nm_Bidang`,b.`Kd_Fungsi`,u.`Nm_Urusan`,f.`Nm_Fungsi` 
				FROM ".$this->table_bidang." AS b 
					LEFT JOIN ".$this->table_urusan." AS u ON b.Kd_Urusan=u.Kd_Urusan
					LEFT JOIN ".$this->table_fungsi." AS f ON b.Kd_Fungsi=f.Kd_Fungsi
				WHERE b.Kd_Urusan LIKE '%".$search['value']."%'
	            OR b.Kd_Bidang LIKE '%".$search['value']."%'
	            OR b.Nm_Bidang LIKE '%".$search['value']."%'
	            OR b.Kd_Fungsi LIKE '%".$search['value']."%'
	            OR u.Nm_Urusan LIKE '%".$search['value']."%'
	            OR f.Nm_Fungsi LIKE '%".$search['value']."%'
	            ) AS a
			";
			$result = $this->db->query($sql)->row();
			return $result->jumlah;
		}

		function get_bidang_by_id($id)
		{
			$sql = "
					SELECT b.`id`,b.`Kd_Urusan`,b.`Kd_Bidang`,b.`Nm_Bidang`,b.`Kd_Fungsi`
					FROM m_bidang AS b
					WHERE id = ?
				";

			$query = $this->db->query($sql, array($id));
			var_dump($this->query);
			if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
		}

		function simpan_bidang($data_bidang)			
		{
			$this->db->trans_strict(FALSE);
			$this->db->trans_start();
			
			$this->db->set($data_bidang);
	    	$this->db->insert('m_bidang');

			$this->db->trans_complete();
			return $this->db->trans_status();		
		}

		function update_bidang($data,$id,$table,$primary) 
		{
	        $this->db->where($this->$primary,$id);
	        return $this->db->update($this->$table,$data);
    	}

    	function delete_bidang($id)
    	{
	   	    $this->db->trans_strict(FALSE);
			$this->db->trans_start();
		    
			$this->db->where('id',$id);
	        $this->db->delete('m_bidang');

			$this->db->trans_complete();

			return $this->db->trans_status();
    	}
//------------------------------------------------------------------------------------------//
    	//p.id,p.Kd_Urusan,p.Kd_Bidang,p.Kd_Prog,p.Ket_Program,u.Nm_Urusan,b.Nm_Bidang
//-----------------------------------FUNGSI PROGRAM-----------------------------------------//
    	function get_data_table_program($search, $start, $length, $order)
		{
			$order_arr = array('id','Kd_Urusan','Kd_Bidang','Kd_Prog','Ket_Kegiatan');
			$sql="
				SELECT * FROM (
				SELECT p.`id`,p.`Kd_Urusan`,p.`Kd_Bidang`,p.`Kd_Prog`,p.`Ket_Program`,u.`Nm_Urusan`,b.`Nm_Bidang` 
				FROM ".$this->table_program." AS p 
					LEFT JOIN ".$this->table_urusan." AS u ON p.Kd_Urusan=u.Kd_Urusan
					LEFT JOIN ".$this->table_bidang." AS b ON p.`Kd_Urusan`=b.`Kd_Urusan` AND p.`Kd_Bidang`=b.`Kd_Bidang`
				WHERE (p.Kd_Urusan LIKE '%".$search['value']."%'
	            	OR p.Kd_Bidang LIKE '%".$search['value']."%'
	            	OR p.Kd_Prog LIKE '%".$search['value']."%'
	            	OR b.Nm_Bidang LIKE '%".$search['value']."%'
	            	OR u.Nm_Urusan LIKE '%".$search['value']."%')
	            )AS a
				ORDER BY ".$order_arr[$order["column"]]." ".$order["dir"]."
            	LIMIT $start,$length
			";
			//$this->db->limit($length, $start);
			//$this->db->order_by($order_arr[$order["column"]], $order["dir"]);

			$result = $this->db->query($sql);
			return $result->result();
		}

		function count_data_table_program($search, $start, $length, $order)
		{
			$this->db->from($this->table_program);
			
			$this->db->like("Kd_Urusan", $search['value']);
			$this->db->or_like("Kd_Bidang", $search['value']);
			$this->db->or_like("Kd_Prog", $search['value']);
			$this->db->or_like("Ket_Program", $search['value']);
					
			$result = $this->db->count_all_results();
			return $result;
		}

		function get_program_by_id($id)
		{
			$sql = "

					SELECT p.`id`,p.`Kd_Urusan`,p.`Kd_Bidang`,p.`Kd_Prog`,p.`Ket_Program`
					FROM m_program AS p
					LEFT JOIN m_urusan AS u ON p.Kd_Urusan=u.Kd_Urusan
					LEFT JOIN m_bidang AS b ON p.`Kd_Urusan`=b.`Kd_Urusan` AND p.`Kd_Bidang`=b.`Kd_Bidang`
					WHERE p.id = ?
				";

			$query = $this->db->query($sql, array($id));
			var_dump($this->query);
			if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
		}

		function simpan_program($data_program)			
		{
			$this->db->trans_strict(FALSE);
			$this->db->trans_start();
			
			$this->db->set($data_program);
	    	$this->db->insert('m_program');

			$this->db->trans_complete();
			return $this->db->trans_status();		
		}

		function update_program($data,$id,$table,$primary) 
		{
	        $this->db->where($this->$primary,$id);
	        return $this->db->update($this->$table,$data);
    	}

    	function delete_program($id)
    	{
	   	    $this->db->trans_strict(FALSE);
			$this->db->trans_start();
		    
			$this->db->where('id',$id);
	        $this->db->delete('m_program');

			$this->db->trans_complete();

			return $this->db->trans_status();
    	}
//------------------------------------------------------------------------------------------//

//-----------------------------------FUNGSI KEGIATAN----------------------------------------//
    	function get_data_table_kegiatan($search, $start, $length, $order)
		{
			$order_arr = array('id','Kd_Urusan','Kd_Bidang','Kd_Prog','Kd_Keg','Ket_Kegiatan');
			$sql="
				SELECT * FROM (
				SELECT k.`id`,k.`Kd_Urusan`,k.`Kd_Bidang`,k.`Kd_Prog`,k.`Kd_Keg`,k.`Ket_Kegiatan`,u.`Nm_Urusan`,b.`Nm_Bidang`,p.`Ket_Program` 
				FROM ".$this->table_kegiatan." AS k 
				LEFT JOIN ".$this->table_urusan." AS u ON k.`Kd_Urusan`=u.`Kd_Urusan`
				LEFT JOIN ".$this->table_bidang." AS b  ON k.`Kd_Urusan`=b.`Kd_Urusan` AND k.`Kd_Bidang`=b.`Kd_Bidang`
				LEFT JOIN ".$this->table_program." AS p ON k.`Kd_Urusan`=p.`Kd_Urusan` AND k.`Kd_Bidang`=p.`Kd_Bidang` AND k.`Kd_Prog`=p.`Kd_Prog`
				WHERE (p.Kd_Urusan LIKE '%".$search['value']."%'
	            	OR p.Kd_Bidang LIKE '%".$search['value']."%'
	            	OR p.Kd_Prog LIKE '%".$search['value']."%'
	            	OR k.Kd_Keg LIKE '%".$search['value']."%'
	            	OR b.Nm_Bidang LIKE '%".$search['value']."%'
	            	OR u.Nm_Urusan LIKE '%".$search['value']."%'
	            	OR k.Ket_Kegiatan LIKE '%".$search['value']."%')
				) AS a
				ORDER BY ".$order_arr[$order["column"]]." ".$order["dir"]."
            	LIMIT $start,$length
			";
			//$this->db->limit($length, $start);
			//$this->db->order_by($order_arr[$order["column"]], $order["dir"]);

			$result = $this->db->query($sql);
			return $result->result();
		}

		function count_data_table_kegiatan($search, $start, $length, $order)
		{
			$this->db->from($this->table_kegiatan);
			
			$this->db->like("Kd_Urusan", $search['value']);
			$this->db->or_like("Kd_Bidang", $search['value']);
			$this->db->or_like("Kd_Prog", $search['value']);
			$this->db->or_like("Kd_Keg", $search['value']);
			$this->db->or_like("Ket_Kegiatan", $search['value']);
					
			$result = $this->db->count_all_results();
			return $result;
		}

		function get_kegiatan_by_id($id)
		{
			$sql = "
					SELECT k.`id`,k.`Kd_Bidang`,k.`Kd_Bidang`,k.`Kd_Prog`,k.`Kd_Keg`
					FROM m_kegiatan AS k
					LEFT JOIN m_urusan AS u ON k.`Kd_Urusan`=u.`Kd_Urusan`
					LEFT JOIN m_bidang AS b  ON k.`Kd_Urusan`=b.`Kd_Urusan` AND k.`Kd_Bidang`=b.`Kd_Bidang`
					LEFT JOIN m_program AS p ON k.`Kd_Urusan`=p.`Kd_Urusan` AND k.`Kd_Bidang`=p.`Kd_Bidang` AND k.`Kd_Prog`=p.`Kd_Prog`
					WHERE k.id = ?
				";

			$query = $this->db->query($sql, array($id));
			var_dump($this->query);
			if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
		}

		function simpan_kegiatan($data_kegiatan)			
		{
			$this->db->trans_strict(FALSE);
			$this->db->trans_start();
			
			$this->db->set($data_kegiatan);
	    	$this->db->insert('m_kegiatan');

			$this->db->trans_complete();
			return $this->db->trans_status();		
		}

		function update_kegiatan($data,$id,$table,$primary) 
		{
	        $this->db->where($this->$primary,$id);
	        return $this->db->update($this->$table,$data);
    	}

    	function delete_kegiatan($id)
    	{
	   	    $this->db->trans_strict(FALSE);
			$this->db->trans_start();
		    
			$this->db->where('id',$id);
	        $this->db->delete('m_kegiatan');

			$this->db->trans_complete();

			return $this->db->trans_status();
    	}
//------------------------------------------------------------------------------------------//

    	function ubah_status_renstra($id_skpd, $tahun){
    		$this->db->trans_strict(FALSE);
			$this->db->trans_start();

    		$this->db->query('UPDATE t_renstra_prog_keg SET id_status = 1 WHERE id_skpd = "'.$id_skpd.'"');

    		$this->db->trans_complete();
			return $this->db->trans_status();
    	}

    	function hapus_data_renja($id_skpd, $tahun, $induk){
    		$this->db->trans_strict(FALSE);
			$this->db->trans_start();

    		$this->db->query('DELETE FROM t_renja_indikator_prog_keg WHERE id_prog_keg IN (SELECT id FROM t_renja_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM t_renja_belanja_kegiatan WHERE id_keg IN (SELECT id FROM t_renja_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM t_renja_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');

    		if ($induk) {
    			$this->db->query('DELETE FROM t_renja WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');
    		}

    		$this->db->trans_complete();
			return $this->db->trans_status();
    	}

    	function hapus_data_ppas($id_skpd, $tahun, $induk){
    		$this->db->trans_strict(FALSE);
			$this->db->trans_start();

    		$this->db->query('DELETE FROM t_ppas_indikator_prog_keg WHERE id_prog_keg IN (SELECT id FROM t_ppas_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM t_ppas_belanja_kegiatan WHERE id_keg IN (SELECT id FROM t_ppas_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM t_ppas_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');

    		if ($induk) {
    			$this->db->query('DELETE FROM t_ppas WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');
    		}

    		$this->db->trans_complete();
			return $this->db->trans_status();
    	}

    	function hapus_data_rka($id_skpd, $tahun, $induk){
    		$this->db->trans_strict(FALSE);
			$this->db->trans_start();

    		$this->db->query('DELETE FROM tx_rka_indikator_prog_keg WHERE id_prog_keg IN (SELECT id FROM tx_rka_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_rka_belanja_kegiatan WHERE id_keg IN (SELECT id FROM tx_rka_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_rka_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');

    		if ($induk) {
    			$this->db->query('DELETE FROM tx_rka WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');
    		}
    		$this->db->trans_complete();
			return $this->db->trans_status();
    	}

    	function hapus_data_dpa($id_skpd, $tahun, $induk){
    		$this->db->trans_strict(FALSE);
			$this->db->trans_start();

    		$this->db->query('DELETE FROM tx_dpa_indikator_prog_keg WHERE id_prog_keg IN (SELECT id FROM tx_dpa_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_dpa_belanja_kegiatan WHERE id_keg IN (SELECT id FROM tx_dpa_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_dpa_rencana_aksi WHERE id_dpa_prog_keg IN (SELECT id FROM tx_dpa_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_dpa_prog_keg_triwulan WHERE id_dpa_prog_keg IN (SELECT id FROM tx_dpa_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_dpa_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');

    		if ($induk) {
    			$this->db->query('DELETE FROM tx_dpa WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');
    		}

    		$this->db->trans_complete();
			return $this->db->trans_status();
    	}

    	function hapus_data_cik($id_skpd, $tahun, $induk){
    		$this->db->trans_strict(FALSE);
			$this->db->trans_start();

    		$this->db->query('DELETE FROM tx_cik_indikator_prog_keg WHERE id_prog_keg IN (SELECT id FROM tx_cik_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_cik_prog_keg WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');

    		if ($induk) {
    			$this->db->query('DELETE FROM tx_cik WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');
    		}

    		$this->db->trans_complete();
			return $this->db->trans_status();
    	}

    	function hapus_data_renja_perubahan($id_skpd, $tahun, $induk){
    		$this->db->trans_strict(FALSE);
			$this->db->trans_start();
			
			$this->db->query('DELETE FROM t_renja_indikator_prog_keg_perubahan WHERE id_prog_keg IN (SELECT id FROM t_renja_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM t_renja_perubahan_belanja_kegiatan WHERE id_keg IN (SELECT id FROM t_renja_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM t_renja_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');

    		if ($induk) {
    			$this->db->query('DELETE FROM t_renja_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');
    		}

    		$this->db->trans_complete();
			return $this->db->trans_status();
    	}

    	function hapus_data_ppas_perubahan($id_skpd, $tahun, $induk){
    		$this->db->trans_strict(FALSE);
			$this->db->trans_start();
			
			$this->db->query('DELETE FROM t_ppas_indikator_prog_keg_perubahan WHERE id_prog_keg IN (SELECT id FROM t_ppas_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM t_ppas_belanja_kegiatan_perubahan WHERE id_keg IN (SELECT id FROM t_ppas_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM t_ppas_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');

    		if ($induk) {
    			$this->db->query('DELETE FROM t_ppas_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');
    		}

    		$this->db->trans_complete();
			return $this->db->trans_status();
    	}

    	function hapus_data_rka_perubahan($id_skpd, $tahun, $induk){
    		$this->db->trans_strict(FALSE);
			$this->db->trans_start();
			
			$this->db->query('DELETE FROM tx_rka_indikator_prog_keg_perubahan WHERE id_prog_keg IN (SELECT id FROM tx_rka_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_rka_belanja_kegiatan_perubahan WHERE id_keg IN (SELECT id FROM tx_rka_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_rka_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');

    		if ($induk) {
    			$this->db->query('DELETE FROM tx_rka_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');
    		}

    		$this->db->trans_complete();
			return $this->db->trans_status();
    	}

    	function hapus_data_dpa_perubahan($id_skpd, $tahun, $induk){
    		$this->db->trans_strict(FALSE);
			$this->db->trans_start();
			
			$this->db->query('DELETE FROM tx_dpa_indikator_prog_keg_perubahan WHERE id_prog_keg IN (SELECT id FROM tx_dpa_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_dpa_belanja_kegiatan_perubahan WHERE id_keg IN (SELECT id FROM tx_dpa_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_dpa_rencana_aksi_perubahan WHERE id_dpa_prog_keg IN (SELECT id FROM tx_dpa_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_dpa_prog_keg_triwulan_perubahan WHERE id_dpa_prog_keg IN (SELECT id FROM tx_dpa_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_dpa_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');

    		if ($induk) {
    			$this->db->query('DELETE FROM tx_dpa_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');
    		}

    		$this->db->trans_complete();
			return $this->db->trans_status();
    	}

    	function hapus_data_cik_perubahan($id_skpd, $tahun, $induk){
    		$this->db->trans_strict(FALSE);
			$this->db->trans_start();
			
			$this->db->query('DELETE FROM tx_cik_indikator_prog_keg_perubahan WHERE id_prog_keg IN (SELECT id FROM tx_cik_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'")');
    		$this->db->query('DELETE FROM tx_cik_prog_keg_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');

    		if ($induk) {
    			$this->db->query('DELETE FROM tx_cik_perubahan WHERE id_skpd = "'.$id_skpd.'" AND tahun = "'.$tahun.'"');
    		}

    		$this->db->trans_complete();
			return $this->db->trans_status();
    	}
	}
?>