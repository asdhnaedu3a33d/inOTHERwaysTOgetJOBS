<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class M_musrembang extends CI_Model 
	{
	       
	    public function __construct()
	    {
	        parent::__construct();           
	    }

	    var $table_urusan = 'm_urusan';
	    var $table_bidang = 'm_bidang';
	    var $table_program = 'm_program';
	    var $table_kegiatan = 'm_kegiatan';
	    var $table_desa = 'm_desa';
	    var $table_kecamatan = 'm_kecamatan';
        var $table_renstra = 't_renstra';


		function get_list_bidang() 
		{
			$sql = "
					SELECT id, Nm_Bidang
					FROM m_bidang
				";
			$query = $this->db->query($sql,array());
			if($query) {
				if($query->num_rows() > 0) {
					return $query->result();
				}
			}
			return NULL();
		}

		function get_list_desa() 
		{
			$sql = "
					SELECT id_desa, nama_desa
					FROM m_desa
				";
			$query = $this->db->query($sql,array());
			if($query) {
				if($query->num_rows() > 0) {
					return $query->result();
				}
			}
			return NULL();
		}
        
        
		function get_list_desa_where($id_kecamatan) 
		{
			$sql = "
					SELECT id_desa, nama_desa
					FROM m_desa
                    WHERE id_kec = ?
				";
			$query = $this->db->query($sql,array($id_kecamatan));
			if($query) {
				if($query->num_rows() > 0) {
					return $query->result();
				}
			}
		}

		function get_list_kecamatan() 
		{
			$sql = "
					SELECT id_kec, nama_kec
					FROM m_kecamatan
				";
			$query = $this->db->query($sql,array());
			if($query) {
				if($query->num_rows() > 0) {
					return $query->result();
				}
			}
			return NULL();
		}
//<========================================BAGIAN MUSREMBANGDES================================================>
		function get_musrembangdes_search($desa) 
		{
			$desa 		= $desa === FALSE ? '%' : $desa;
			$sql = "
					SELECT m.*,d.nama_desa
					FROM t_musrembangdes as m
                    LEFT JOIN m_desa as d
                    ON m.id_desa=d.id_desa
					WHERE m.id_desa LIKE ?
					ORDER BY m.id_musrembang DESC
				";

			$query = $this->db->query($sql, array($desa));

			if($query) {
				if($query->num_rows() > 0) {
					return $query->result();
				}
			}

			return NULL;			
		}

		function get_musrembangdes_by_id($id_musrembang)
		{
			$sql = "
					SELECT *
					FROM t_musrembangdes
					WHERE id_musrembang = ?
				";

			$query = $this->db->query($sql, array($id_musrembang));

			if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
		}

		function get_musrembangdes_by_id_with_rincian($id_musrembang)
		{
			$sql = "
					SELECT t_musrembangdes.*, Nm_Urusan, Nm_Bidang, Ket_Program, Ket_Kegiatan
					FROM t_musrembangdes
					INNER JOIN m_urusan ON t_musrembangdes.Kd_Urusan=m_urusan.Kd_Urusan
					INNER JOIN m_bidang ON t_musrembangdes.Kd_Urusan=m_bidang.Kd_Urusan AND t_musrembangdes.Kd_Bidang=m_bidang.Kd_Bidang
					INNER JOIN m_program ON t_musrembangdes.Kd_Urusan=m_program.Kd_Urusan AND t_musrembangdes.Kd_Bidang=m_program.Kd_Bidang AND t_musrembangdes.Kd_Prog=m_program.Kd_Prog
					INNER JOIN m_kegiatan ON t_musrembangdes.Kd_Urusan=m_kegiatan.Kd_Urusan AND t_musrembangdes.Kd_Bidang=m_kegiatan.Kd_Bidang AND t_musrembangdes.Kd_Prog=m_kegiatan.Kd_Prog AND t_musrembangdes.Kd_Keg=m_kegiatan.Kd_Keg
					WHERE id_musrembang = ?
				";

			$query = $this->db->query($sql, array($id_musrembang));

			if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
		}

		//simpan rekapitulasi kegiatan desa
		function simpan_rekap_keg_desa($cekmusrembang)			
		{
			$this->db->trans_strict(FALSE);
			$this->db->trans_start();
			
			$this->db->set($cekmusrembang);
	    	$this->db->insert('t_musrembangdes');

			$this->db->trans_complete();
			return $this->db->trans_status();		
		}
		//update rekapitulasi kegiatan desa
		function update_rekap_keg_desa($cekmusrembang, $id_musrembang)			
		{
			$this->db->trans_strict(FALSE);
			$this->db->trans_start();

			$this->db->where('id_musrembang', $id_musrembang);
	    	$this->db->update('t_musrembangdes', $cekmusrembang);

			$this->db->trans_complete();
			return $this->db->trans_status();		
		}
		
		function view_list_musrembang($id_musrembang) 
		{
            $sql=$this->db->query("
				SELECT *
				FROM t_musrembangdes
				WHERE id_musrembang=$id_musrembang
			");
            return $sql->result();
		}

		function hapus_musrembang($id) 
		{  
			$this->db->trans_strict(FALSE);
			$this->db->trans_start();
		    
			$this->db->where('id_musrembang',$id);
	        $this->db->delete('t_musrembangdes');

			
			$this->db->trans_complete();

			return $this->db->trans_status();
    	}
//<=====================================AKHIR BAGIAN MUSREMBANGDES=============================================>

//<========================================BAGIAN MUSREMBANGCAM================================================>
    	function get_musrembangcam_search($desa) 
		{
			$desa 		= $desa === FALSE ? '%' : $desa;
			$sql = "
					SELECT a.*,d.nama_desa,k.nama_kec as nama_kecamatan
					FROM t_musrembangdes as a
                    LEFT JOIN m_desa as d
                    ON a.id_desa = d.id_desa
                    LEFT JOIN m_kecamatan as k
                    on a.id_kecamatan = k.id_kec
					WHERE a.id_desa LIKE ?
					ORDER BY a.id_musrembang DESC
				";

			$query = $this->db->query($sql, array($desa));

			if($query) {
				if($query->num_rows() > 0) {
					return $query->result();
				}
			}

			return NULL;			
		}

		function get_musrembangcam_by_id($id_musrembangcam)
		{
			$sql = "
					SELECT t_musrembangdes.*, Nm_Urusan, Nm_Bidang, Ket_Program, Ket_Kegiatan
					FROM t_musrembangdes
					INNER JOIN m_urusan ON t_musrembangdes.Kd_Urusan=m_urusan.Kd_Urusan
					INNER JOIN m_bidang ON t_musrembangdes.Kd_Urusan=m_bidang.Kd_Urusan AND t_musrembangdes.Kd_Bidang=m_bidang.Kd_Bidang
					INNER JOIN m_program ON t_musrembangdes.Kd_Urusan=m_program.Kd_Urusan AND t_musrembangdes.Kd_Bidang=m_program.Kd_Bidang AND t_musrembangcam.Kd_Prog=m_program.Kd_Prog
					INNER JOIN m_kegiatan ON t_musrembangdes.Kd_Urusan=m_kegiatan.Kd_Urusan AND t_musrembangdes.Kd_Bidang=m_kegiatan.Kd_Bidang AND t_musrembangdes.Kd_Prog=m_kegiatan.Kd_Prog AND t_musrembangcam.Kd_Keg=m_kegiatan.Kd_Keg
					WHERE id_musrembang = ?
				";

			$query = $this->db->query($sql, array($id_musrembangcam));

			if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
		}

		function get_musrembangcam_by_id_with_rincian($id_musrembang)
		{
			$sql = "
					SELECT t_musrembangdes.*, Nm_Urusan, Nm_Bidang, Ket_Program, Ket_Kegiatan
					FROM t_musrembangdes
					INNER JOIN m_urusan ON t_musrembangdes.Kd_Urusan=m_urusan.Kd_Urusan
					INNER JOIN m_bidang ON t_musrembangdes.Kd_Urusan=m_bidang.Kd_Urusan AND t_musrembangdes.Kd_Bidang=m_bidang.Kd_Bidang
					INNER JOIN m_program ON t_musrembangdes.Kd_Urusan=m_program.Kd_Urusan AND t_musrembangdes.Kd_Bidang=m_program.Kd_Bidang AND t_musrembangdes.Kd_Prog=m_program.Kd_Prog
					INNER JOIN m_kegiatan ON t_musrembangdes.Kd_Urusan=m_kegiatan.Kd_Urusan AND t_musrembangdes.Kd_Bidang=m_kegiatan.Kd_Bidang AND t_musrembangdes.Kd_Prog=m_kegiatan.Kd_Prog AND t_musrembangdes.Kd_Keg=m_kegiatan.Kd_Keg
					WHERE id_musrembang = ?
				";

			$query = $this->db->query($sql, array($id_musrembang));

			if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
		}

		//simpan rekapitulasi kegiatan kecamatan
		function simpan_rekap_keg_kecamatan($cekmusrembangcam)			
		{
			$this->db->trans_strict(FALSE);
			$this->db->trans_start();
			
			$this->db->set($cekmusrembangcam);
	    	$this->db->insert('t_musrembangcam');

			$this->db->trans_complete();
			return $this->db->trans_status();		
		}
		//update rekapitulasi kegiatan kecamatan
		function update_rekap_keg_kecamatan($cekmusrembangcam, $id_musrembangcam)			
		{
			$this->db->trans_strict(FALSE);
			$this->db->trans_start();

			$this->db->where('id_musrembangcam', $id_musrembangcam);
	    	$this->db->update('t_musrembangcam', $cekmusrembangcam);

			$this->db->trans_complete();
			return $this->db->trans_status();		
		}

		function view_list_musrembangcam($id_musrembang) 
		{
            $sql=$this->db->query("
				SELECT *
				FROM t_musrembang
				WHERE id_musrembang=$id_musrembang
			");
            return $sql->result();
		}

		function hapus_musrembangcam($id) 
		{  
			$this->db->trans_strict(FALSE);
			$this->db->trans_start();
		    
			$this->db->where('id_musrembangcam',$id);
	        $this->db->delete('t_musrembangcam');

			
			$this->db->trans_complete();

			return $this->db->trans_status();
    	}
//<=====================================AKHIR BAGIAN MUSREMBANGDES=============================================>		
    	function create_lov_urusan($pilih){
	        $sql = "select kd_urusan,nm_urusan from t_renstra GROUP BY kd_urusan";
            //$query = $this->db->get($this->table_renstra);
            $query = $this->db->query($sql);
	        $data = "";
	        foreach ($query->result() as $row)
	        {
	            if($pilih===$row->kd_urusan){
	                $data .= "<option value='".$row->kd_urusan."' selected='selected'>".$row->nm_urusan."</option>";
	            }else{
	                $data .= "<option value='".$row->kd_urusan."'>".$row->nm_urusan."</option>";
	            }
	        }
	        return $data;
    	}

    	function create_lov_bidang($Kd_Urusan,$Kd_Bidang){
    		$this->db->where('kd_urusan',$Kd_Urusan);
    		$query = $this->db->get($this->table_renstra);
	        
            $data = '<option value="">--- Pilih Bidang ---</option>';
	        foreach ($query->result() as $row)
	        {
	            if($Kd_Bidang===$row->Kd_Bidang){
	                $data .= "<option value='".$row->Kd_Bidang."' selected='selected'>".$row->Nm_Bidang."</option>";
	            }else{
	                $data .= "<option value='".$row->Kd_Bidang."'>".$row->Nm_Bidang."</option>";
	            }
	        }
	        return $data;
    	}

    	function create_lov_program($Kd_Urusan,$Kd_Bidang,$Kd_Prog){
    		$this->db->where('Kd_Urusan',$Kd_Urusan);
    		$this->db->where('Kd_Bidang',$Kd_Bidang);
    		$query = $this->db->get($this->table_program);
	        $data = '<option value="">--- Pilih Program ---</option>';
	        foreach ($query->result() as $row)
	        {
	            if($Kd_Prog===$row->Kd_Prog){
	                $data .= "<option value='".$row->Kd_Prog."' selected='selected'>".$row->Ket_Program."</option>";
	            }else{
	                $data .= "<option value='".$row->Kd_Prog."'>".$row->Ket_Program."</option>";
	            }
	        }
	        return $data;
    	}

    	function create_lov_kegiatan($Kd_Urusan,$Kd_Bidang,$Kd_Prog,$Kd_Keg){
    		$this->db->where('Kd_Urusan',$Kd_Urusan);
    		$this->db->where('Kd_Bidang',$Kd_Bidang);
    		$this->db->where('Kd_Prog',$Kd_Prog);
    		$query = $this->db->get($this->table_kegiatan);
	        $data = '<option value="">--- Pilih Kegiatan ---</option>';
	        foreach ($query->result() as $row)
	        {
	            if($Kd_Keg===$row->Kd_Keg){
	                $data .= "<option value='".$row->Kd_Keg."' selected='selected'>".$row->Ket_Kegiatan."</option>";
	            }else{
	                $data .= "<option value='".$row->Kd_Keg."'>".$row->Ket_Kegiatan."</option>";
	            }
	        }
	        return $data;
    	}

    	function create_lov_desa($id_desa){
    		$query = $this->db->get($this->table_desa);
	        $data = '';
	        foreach ($query->result() as $row)
	        {
	            if($id_desa===$row->id_desa){
	                $data .= "<option value='".$row->id_desa."' selected='selected'>".$row->nama_desa."</option>";
	            }else{
	                $data .= "<option value='".$row->id_desa."'>".$row->nama_desa."</option>";
	            }
	        }
	        return $data;
    	}

    	function create_lov_kecamatan($id_kec){
    		$query = $this->db->get($this->table_kecamatan);
	        $data = '';
	        foreach ($query->result() as $row)
	        {
	            if($id_kec===$row->id_kec){
	                $data .= "<option value='".$row->id_kec."' selected='selected'>".$row->nama_kec."</option>";
	            }else{
	                $data .= "<option value='".$row->id_kec."'>".$row->nama_kec."</option>";
	            }
	        }
	        return $data;
    	}

    	function get_value_autocomplete_kd_urusan($search){
    		$this->db->select('kd_urusan AS id, nm_urusan AS label');
    		$this->db->like('kd_urusan', $search);
    		$this->db->or_like('nm_urusan', $search);
    		$this->db->group_by('kd_urusan');
            $result = $this->db->get($this->table_renstra);
    		return $result->result();
    	}

    	function get_value_autocomplete_kd_bidang($search, $kd_urusan){
    		$this->db->select('kd_bidang AS id, nm_bidang AS label');
    		$this->db->where('kd_urusan', $kd_urusan);
    		$this->db->where('(kd_bidang LIKE \'%'. $search .'%\' OR nm_bidang LIKE \'%'. $search .'%\')');    		 
    		$this->db->group_by('kd_bidang');
            $result = $this->db->get($this->table_renstra);
    		return $result->result();
    	}

    	function get_value_autocomplete_kd_prog($search, $kd_urusan, $kd_bidang){
    		$this->db->select('kd_program AS id, ket_program AS label');
    		$this->db->where('kd_urusan', $kd_urusan);
    		$this->db->where('kd_bidang', $kd_bidang);
    		$this->db->where('(kd_program LIKE \'%'. $search .'%\' OR ket_program LIKE \'%'. $search .'%\')');
    		$this->db->group_by('kd_program');
            $result = $this->db->get($this->table_renstra);
    		return $result->result();
    	}

    	function get_value_autocomplete_kd_keg($search, $kd_urusan, $kd_bidang, $kd_prog){
    		$this->db->select('kd_kegiatan AS id, ket_kegiatan AS label');
    		$this->db->where('kd_urusan', $kd_urusan);
    		$this->db->where('kd_bidang', $kd_bidang);
    		$this->db->where('kd_program', $kd_prog);
    		$this->db->where('(kd_kegiatan LIKE \'%'. $search .'%\' OR ket_kegiatan LIKE \'%'. $search .'%\')');
   			$this->db->group_by('kd_kegiatan');
            $result = $this->db->get($this->table_renstra);
    		return $result->result();
    	}
        
        function get_value_autocomplete_kd_urusan_cam($search,$id_skpd){
    		$this->db->select('kd_urusan AS id, nm_urusan AS label');
            $this->db->where('id_skpd',$id_skpd);
    		$this->db->like('kd_urusan', $search);
    		$this->db->or_like('nm_urusan', $search);
    		$this->db->group_by('kd_urusan');
            $result = $this->db->get($this->table_renstra);
    		return $result->result();
    	}

    	function get_value_autocomplete_kd_bidang_cam($search, $kd_urusan,$id_skpd){
    		$this->db->select('kd_bidang AS id, nm_bidang AS label');
    		$this->db->where('id_skpd',$id_skpd);
            $this->db->where('kd_urusan', $kd_urusan);
    		$this->db->where('(kd_bidang LIKE \'%'. $search .'%\' OR nm_bidang LIKE \'%'. $search .'%\')');    		 
    		$this->db->group_by('kd_bidang');
            $result = $this->db->get($this->table_renstra);
    		return $result->result();
    	}

    	function get_value_autocomplete_kd_prog_cam($search, $kd_urusan, $kd_bidang,$id_skpd){
    		$this->db->select('kd_program AS id, ket_program AS label');
    		$this->db->where('id_skpd',$id_skpd);
            $this->db->where('kd_urusan', $kd_urusan);
    		$this->db->where('kd_bidang', $kd_bidang);
    		$this->db->where('(kd_program LIKE \'%'. $search .'%\' OR ket_program LIKE \'%'. $search .'%\')');
    		$this->db->group_by('kd_program');
            $result = $this->db->get($this->table_renstra);
    		return $result->result();
    	}

    	function get_value_autocomplete_kd_keg_cam($search, $kd_urusan, $kd_bidang, $kd_prog,$id_skpd){
    		$this->db->select('kd_kegiatan AS id, ket_kegiatan AS label');
    		$this->db->where('id_skpd',$id_skpd);
            $this->db->where('kd_urusan', $kd_urusan);
    		$this->db->where('kd_bidang', $kd_bidang);
    		$this->db->where('kd_program', $kd_prog);
    		$this->db->where('(kd_kegiatan LIKE \'%'. $search .'%\' OR ket_kegiatan LIKE \'%'. $search .'%\')');
   			$this->db->group_by('kd_kegiatan');
            $result = $this->db->get($this->table_renstra);
    		return $result->result();
    	}    	
	}
?>