<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_musrenbang extends CI_Model
{
	var $table_musrenbang = 't_musrenbang';
    var $primary_musrenbang = 'id_musrenbang';
    var $table_renstra = 't_renstra_rkpd';
    var $table_skpd = 'm_skpd';
    var $primary_skpd = 'id_skpd';

    public function __construct()
    {
        parent::__construct();
    }

    function insert($data,$table) {
        $this->db->insert($this->$table,$data);
        return $this->db->insert_id();
    }
    function update($id,$data,$table,$primary) {
        $this->db->where($this->$primary,$id);
        return $this->db->update($this->$table,$data);
    }
    function delete($id,$data,$table,$primary) {
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

    function get_data_with_rincian($id_musrenbang,$table){
    //     $sql = "
    //                 SELECT a.*, nm_urusan, nm_bidang, ket_program, ket_kegiatan
				// 	FROM ".$this->$table." as a
    //                 INNER JOIN t_renstra_rkpd AS renstra
    //                 ON a.kd_urusan=renstra.kd_urusan AND a.kd_bidang=renstra.kd_bidang AND a.kd_program=renstra.kd_program AND a.kd_kegiatan=renstra.kd_kegiatan
				// 	WHERE id_musrenbang = ?
				// ";
        $sql ="
            SELECT a.*, u.`Nm_Urusan`AS nm_urusan, b.`Nm_Bidang` AS nm_bidang, p.`Ket_Program` AS ket_program, k.`Ket_Kegiatan` AS ket_kegiatan
                FROM ".$this->$table." AS a
                left JOIN m_urusan AS u ON a.`kd_urusan`=u.`Kd_Urusan`
                left JOIN m_bidang AS b ON a.`kd_urusan`=b.`Kd_Urusan`
                                        AND a.`kd_bidang`=b.`Kd_Bidang`
                left JOIN m_program AS p ON a.`kd_urusan`=p.`Kd_Urusan`
                                        AND a.`kd_bidang`=p.`Kd_Bidang`
                                        AND a.`kd_program`=p.`Kd_Prog`
                left JOIN m_kegiatan AS k ON a.`kd_urusan`=k.`Kd_Urusan`
                                        AND a.`kd_bidang`=k.`Kd_Bidang`
                                        AND a.`kd_program`=k.`Kd_Prog`
                                        AND a.`kd_kegiatan`=k.`Kd_Keg`
                WHERE id_musrenbang = ?
        ";

			$query = $this->db->query($sql, array($id_musrenbang));
                    /*INNER JOIN m_urusan ON a.kd_urusan=m_urusan.Kd_Urusan
                    INNER JOIN m_bidang ON a.kd_urusan=m_bidang.Kd_Urusan AND a.kd_bidang=m_bidang.Kd_Bidang
                    INNER JOIN m_program ON a.kd_urusan=m_program.Kd_Urusan AND a.kd_bidang=m_program.Kd_Bidang AND a.kd_program=m_program.Kd_Prog
                    INNER JOIN m_kegiatan ON a.kd_urusan=m_kegiatan.Kd_Urusan AND a.kd_bidang=m_kegiatan.Kd_Bidang AND a.kd_program=m_kegiatan.Kd_Prog AND a.kd_kegiatan=m_kegiatan.Kd_Keg*/
			if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
    }


	function get_data_table($search, $start, $length, $order){
			$order_arr = array('id_musrenbang','kode_kegiatan','nama_program_kegiatan','jenis_kegiatan');
        $id_desa = $this->session->userdata('id_desa');
				$tahun = $this->session->userdata('t_anggaran_aktif');
        $sql="
            SELECT * FROM (
                SELECT m.*,
                    CONCAT(m.kd_urusan,'.',m.kd_bidang,'.',m.kd_program,'.',m.kd_kegiatan) as kode_kegiatan,
                    CONCAT(p.`Ket_Program`,' | ',k.`Ket_Kegiatan`) as nama_program_kegiatan
                    FROM t_musrenbang AS m
                    LEFT JOIN m_program AS p ON m.`kd_urusan`= p.`Kd_Urusan`
                        AND m.`kd_bidang`=p.`Kd_Bidang`
                        AND m.`kd_program`=p.`Kd_Prog`
                    LEFT JOIN m_kegiatan AS k ON m.`kd_urusan`= k.`Kd_Urusan`
                        AND m.`kd_bidang`=k.`Kd_Bidang`
                        AND m.`kd_program`=k.`Kd_Prog`
                        AND m.`kd_kegiatan`=k.`Kd_Keg`
            ) as a
            WHERE (kode_kegiatan LIKE '%".$search['value']."%'
                OR nama_program_kegiatan LIKE '%".$search['value']."%'
                OR jenis_pekerjaan LIKE '%".$search['value']."%')
            AND id_desa = '$id_desa'
						AND tahun = '$tahun'
						AND flag_delete='0'
            order by ".$order_arr[$order["column"]]." ".$order["dir"]."
            limit $start,$length

        ";
		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table($search, $start, $length, $order){
        $id_desa = $this->session->userdata('id_desa');
				$tahun = $this->session->userdata('t_anggaran_aktif');
				$sql="
            SELECT * FROM (
                SELECT m.*,
                    CONCAT(m.kd_urusan,'.',m.kd_bidang,'.',m.kd_program,'.',m.kd_kegiatan) as kode_kegiatan,
                    CONCAT(p.`Ket_Program`,' | ',k.`Ket_Kegiatan`) as nama_program_kegiatan
                    FROM t_musrenbang AS m
                    LEFT JOIN m_program AS p ON m.`kd_urusan`= p.`Kd_Urusan`
                        AND m.`kd_bidang`=p.`Kd_Bidang`
                        AND m.`kd_program`=p.`Kd_Prog`
                    LEFT JOIN m_kegiatan AS k ON m.`kd_urusan`= k.`Kd_Urusan`
                        AND m.`kd_bidang`=k.`Kd_Bidang`
                        AND m.`kd_program`=k.`Kd_Prog`
                        AND m.`kd_kegiatan`=k.`Kd_Keg`
            ) as a
            WHERE (kode_kegiatan LIKE '%".$search['value']."%'
                OR nama_program_kegiatan LIKE '%".$search['value']."%'
                OR jenis_pekerjaan LIKE '%".$search['value']."%')
            AND id_desa = '$id_desa'
						AND tahun = '$tahun'
						AND flag_delete='0'
        ";
		return $this->db->query($sql)->num_rows();
    }

		//get data table from rkpd
    function get_data_table_cam($search, $start, $length, $order){
			$id_skpd = $this->session->userdata('id_skpd');
			$tahun = $this->session->userdata('t_anggaran_aktif');
			$order_arr = array('id_musrenbang','kode_kegiatan','nama_program_kegiatan','nama_skpd');
        $sql = "
            SELECT *
            FROM (
							SELECT
								m.*,CONCAT(p.Ket_Program,' | ',k.Ket_Kegiatan) as nama_program_kegiatan,s.nama_skpd
							FROM
							(
								SELECT
									id_musrenbang,
									tahun,
									id_skpd,
									kd_urusan,
									kd_bidang,
									kd_program,
									kd_kegiatan,
									concat(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan,
									sum(if(id_keputusan='2',jumlah_dana,0)) as total_jumlah_dana
								FROM
									t_musrenbang
								WHERE
									id_skpd = '$id_skpd'
									AND tahun = '$tahun'
									AND flag_delete = '0'
									GROUP BY kode_kegiatan
							) AS m
							left join m_program as p
							on m.kd_urusan = p.Kd_Urusan and m.kd_bidang=p.Kd_Bidang and m.kd_program=p.Kd_Prog
							left join m_kegiatan as k
							on m.kd_urusan = k.Kd_Urusan and m.kd_bidang=k.Kd_Bidang and m.kd_program=k.Kd_Prog and m.kd_kegiatan=k.Kd_Keg
							left join m_skpd as s
							on m.id_skpd=s.id_skpd
            ) as a
            WHERE (
                kode_kegiatan like '%".$search['value']."%' or
                nama_program_kegiatan like '%".$search['value']."%' or
                nama_skpd like '%".$search['value']."%'
            )
            ORDER BY ".$order_arr[$order["column"]]." ".$order["dir"]."
            LIMIT ".$start.",".$length."";
        return $this->db->query($sql)->result();
	}

	function count_data_table_cam($search, $start, $length, $order){
		$id_skpd = $this->session->userdata('id_skpd');
		$tahun = $this->session->userdata('t_anggaran_aktif');
         $sql = "
				 SELECT *
				 FROM (
					 SELECT
						 m.*,CONCAT(p.Ket_Program,' | ',k.Ket_Kegiatan) as nama_program_kegiatan,s.nama_skpd
					 FROM
					 (
						 SELECT
							 id_musrenbang,
							 tahun,
							 id_skpd,
							 kd_urusan,
							 kd_bidang,
							 kd_program,
							 kd_kegiatan,
							 concat(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan,
							 sum(if(id_keputusan='2',jumlah_dana,0)) as total_jumlah_dana
						 FROM
							 t_musrenbang
						 WHERE
							 id_skpd = '$id_skpd'
							 AND tahun = '$tahun'
							 AND flag_delete = '0'
							 AND id_status_usulan >= '3'
							 GROUP BY kode_kegiatan
					 ) AS m
					 left join m_program as p
					 on m.kd_urusan = p.Kd_Urusan and m.kd_bidang=p.Kd_Bidang and m.kd_program=p.Kd_Prog
					 left join m_kegiatan as k
					 on m.kd_urusan = k.Kd_Urusan and m.kd_bidang=k.Kd_Bidang and m.kd_program=k.Kd_Prog and m.kd_kegiatan=k.Kd_Keg
					 left join m_skpd as s
					 on m.id_skpd=s.id_skpd
				 ) as a
				 WHERE (
						 kode_kegiatan like '%".$search['value']."%' or
						 nama_program_kegiatan like '%".$search['value']."%' or
						 nama_skpd like '%".$search['value']."%'
				 )";
        return $this->db->query($sql)->num_rows();
    }

		function get_list_musrenbangcam($kode_kegiatan,$id_skpd){
				$tahun = $this->session->userdata('t_anggaran_aktif');
				$sql = "select m.*,d.nama_desa,k.nama_kec,au.nama as asal_usulan,u.nama as status_usulan,ke.nama as status_keputusan from (
						select * from (SELECT *,concat(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan
						FROM
							t_musrenbang
						WHERE
						id_skpd = '$id_skpd'
						AND tahun = '$tahun'
						AND flag_delete = '0') as a
						where kode_kegiatan = '$kode_kegiatan'
						) as m
						left join m_desa as d on m.id_desa=d.id_desa
						left join m_kecamatan as k on m.id_kecamatan=k.id_kec
						left join m_asal_usulan as au on m.id_asal_usulan = au.id
						left join m_status_usulan as u on m.id_status_usulan=u.id
						left join m_keputusan as ke on m.id_keputusan = ke.id_keputusan
						order by m.id_keputusan asc";
				return $this->db->query($sql)->result();
		}

    function get_data_table_penentuan_skpd($search, $start, $length, $order){
			//pada level kecamatan, data yang muncul hanya sesuai dengan kecamatan nya
			$tahun = $this->session->userdata('t_anggaran_aktif');
      $id_kecamatan = $this->session->userdata('id_kecamatan');

      $order_arr = array('id_musrenbang','jenis_pekerjaan','volume','satuan','jumlah_dana','nama_desa','nama_skpd');
      $sql = "
				SELECT * FROM (
					select m.*,d.nama_desa,s.nama_skpd,p.nama as status_keputusan from (
						select * from t_musrenbang
						where tahun = '$tahun' and id_kecamatan = '$id_kecamatan' and flag_delete = '0'
					) as m
					left join m_desa as d on m.id_desa = d.id_desa
					left join m_skpd as s on m.id_skpd = s.id_skpd
					left join m_keputusan as p on m.id_keputusan=p.id_keputusan)
				as a
        WHERE (
          jenis_pekerjaan LIKE '%".$search['value']."%' OR
          nama_desa LIKE '%".$search['value']."%' OR
          nama_skpd LIKE '%".$search['value']."%' OR
					status_keputusan LIKE '%".$search['value']."%'
        )
        order by ".$order_arr[$order["column"]]." ".$order["dir"]."
        limit ".$start.",".$length."
      ";


		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table_penentuan_skpd($search, $start, $length, $order){
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$id_kecamatan = $this->session->userdata('id_kecamatan');
    $sql = "
		SELECT * FROM (
			select m.*,d.nama_desa,s.nama_skpd,p.nama as status_keputusan  from (
				select * from t_musrenbang
				where tahun = '$tahun' and id_kecamatan = '$id_kecamatan' and flag_delete = '0'
			) as m
			left join m_desa as d on m.id_desa = d.id_desa
			left join m_skpd as s on m.id_skpd = s.id_skpd
			left join m_keputusan as p on m.id_keputusan=p.id_keputusan)
		as a
		WHERE (
			jenis_pekerjaan LIKE '%".$search['value']."%' OR
			nama_desa LIKE '%".$search['value']."%' OR
			nama_skpd LIKE '%".$search['value']."%' OR
			status_keputusan LIKE '%".$search['value']."%'
		)";


		return $this->db->query($sql)->num_rows();

	}

	function get_data_table_skpd($search, $start, $length, $order){
		//pada level kecamatan, data yang muncul hanya sesuai dengan kecamatan nya
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$id_skpd = $this->session->userdata('id_skpd');

		$order_arr = array('id_musrenbang','kode_kegiatan','nama_program_kegiatan','jenis_pekerjaan','volume','jumlah_dana','nama_desa','status_keputusan');
		$sql = "
			SELECT * FROM (
				select m.*,d.nama_desa,kec.nama_kec,p.nama as status_keputusan from (
					select
						m.*,
						concat(p.Ket_Program,' | ',k.Ket_Kegiatan) as nama_program_kegiatan,
						CONCAT(m.kd_urusan,'.',m.kd_bidang,'.',m.kd_program,'.',m.kd_kegiatan) as kode_kegiatan,
						CONCAT(m.volume,' ',m.satuan) as volume_satuan

					from
						(select * from t_musrenbang where tahun = '$tahun' and id_skpd = '$id_skpd' and flag_delete = '0') as m
					left join m_program as p
					on m.kd_urusan=p.Kd_Urusan and m.kd_bidang=p.Kd_Bidang and m.kd_program=p.Kd_Prog
					left join m_kegiatan as k
					on m.kd_urusan=k.Kd_Urusan and m.kd_bidang=k.Kd_Bidang and m.kd_program=k.Kd_Prog and m.kd_kegiatan=k.Kd_Keg
				) as m
				left join m_desa as d on m.id_desa = d.id_desa
				left join m_keputusan as p on m.id_keputusan=p.id_keputusan
				left join m_kecamatan as kec on m.id_kecamatan=kec.id_kec)
			as a
			WHERE (
				kode_kegiatan LIKE '%".$search['value']."%' OR
				nama_program_kegiatan LIKE '%".$search['value']."%' OR
				jenis_pekerjaan LIKE '%".$search['value']."%' OR
				nama_desa LIKE '%".$search['value']."%' OR
				status_keputusan LIKE '%".$search['value']."%'
			)
			order by ".$order_arr[$order["column"]]." ".$order["dir"]."
			limit ".$start.",".$length."
		";


		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table_skpd($search, $start, $length, $order){
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$id_skpd = $this->session->userdata('id_skpd');
		$sql = "
		SELECT * FROM (
			select m.*,d.nama_desa,kec.nama_kec,p.nama as status_keputusan from (
				select
					m.*,
					concat(p.Ket_Program,' | ',k.Ket_Kegiatan) as nama_program_kegiatan,
					CONCAT(m.kd_urusan,'.',m.kd_bidang,'.',m.kd_program,'.',m.kd_kegiatan) as kode_kegiatan,
					CONCAT(m.volume,' ',m.satuan) as volume_satuan
				from
					(select * from t_musrenbang where tahun = '$tahun' and id_skpd = '$id_skpd' and flag_delete = '0') as m
				left join m_program as p
				on m.kd_urusan=p.Kd_Urusan and m.kd_bidang=p.Kd_Bidang and m.kd_program=p.Kd_Prog
				left join m_kegiatan as k
				on m.kd_urusan=k.Kd_Urusan and m.kd_bidang=k.Kd_Bidang and m.kd_program=k.Kd_Prog and m.kd_kegiatan=k.Kd_Keg
			) as m
			left join m_desa as d on m.id_desa = d.id_desa
			left join m_keputusan as p on m.id_keputusan=p.id_keputusan
			left join m_kecamatan as kec on m.id_kecamatan=kec.id_kec)
		as a
		WHERE (
			kode_kegiatan LIKE '%".$search['value']."%' OR
			nama_program_kegiatan LIKE '%".$search['value']."%' OR
			jenis_pekerjaan LIKE '%".$search['value']."%' OR
			nama_desa LIKE '%".$search['value']."%' OR
			status_keputusan LIKE '%".$search['value']."%'
		)";


		return $this->db->query($sql)->num_rows();

	}

	function get_data_table_rekap($search, $start, $length, $order){
		$id_kecamatan = $this->session->userdata('id_kecamatan');
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$sql="
			SELECT * FROM (
				SELECT m.*,d.`nama_desa` AS nama_desa ,p.`nama` AS keputusan, skpd.nama_skpd AS nama_skpd FROM t_musrenbang AS m
				LEFT JOIN m_desa AS d ON m.`id_desa`= d.`id_desa`
				LEFT JOIN m_keputusan AS p ON m.id_keputusan=p.id_keputusan
				LEFT JOIN m_skpd AS skpd ON m.id_skpd=skpd.id_skpd
				WHERE m.id_kecamatan = ".$id_kecamatan."
						AND m.tahun = ".$tahun."
						AND m.flag_delete = '0'
				ORDER BY p.`id_keputusan` ASC
			) AS result
			WHERE (
				nama_desa LIKE '%".$search['value']."%' OR
				keputusan LIKE '%".$search['value']."%' OR
				jenis_pekerjaan LIKE '%".$search['value']."%'
			)
			ORDER BY id_desa ASC
		";
		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table_rekap($search, $start, $length, $order){
		$id_kecamatan = $this->session->userdata('id_kecamatan');
		$tahun = $this->session->userdata('t_anggaran_aktif');
         $sql = "
         		SELECT * FROM (
					SELECT m.*,d.`nama_desa` AS nama_desa ,p.`nama` AS keputusan, skpd.nama_skpd AS nama_skpd FROM t_musrenbang AS m
					LEFT JOIN m_desa AS d ON m.`id_desa`= d.`id_desa`
					LEFT JOIN m_keputusan AS p ON m.id_keputusan=p.id_keputusan
					LEFT JOIN m_skpd AS skpd ON m.id_skpd=skpd.id_skpd
					WHERE m.id_kecamatan = ".$id_kecamatan."
							AND m.tahun = ".$tahun."
							AND m.flag_delete = '0'
					ORDER BY p.`id_keputusan` ASC
				) AS result
				WHERE (
					nama_desa LIKE '%".$search['value']."%' OR
					keputusan LIKE '%".$search['value']."%' OR
					jenis_pekerjaan LIKE '%".$search['value']."%'
				)
				ORDER BY id_desa ASC
         		";
        return $this->db->query($sql)->num_rows();
    }
    //Revisi : Autocomplete sudah ada di controller common, tinggal ambil ke sana
    /*
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
    		$this->db->select('kd_kegiatan AS id, ket_kegiatan AS label,id AS id_renstra');
    		$this->db->where('kd_urusan', $kd_urusan);
    		$this->db->where('kd_bidang', $kd_bidang);
    		$this->db->where('kd_program', $kd_prog);
    		$this->db->where('(kd_kegiatan LIKE \'%'. $search .'%\' OR ket_kegiatan LIKE \'%'. $search .'%\')');
   			$this->db->group_by('kd_kegiatan');
            $result = $this->db->get($this->table_renstra);
    		return $result->result();
    	}
      */
        function create_lov_skpd($id_skpd){
    		$query = $this->db->get($this->table_skpd);
	        $data = '<option value="">--- Pilih SKPD ---</option>';
	        foreach ($query->result() as $row)
	        {
	            if($id_skpd===$row->id_skpd){
	                $data .= "<option value='".$row->id_skpd."' selected='selected'>".$row->nama_skpd."</option>";
	            }else{
	                $data .= "<option value='".$row->id_skpd."'>".$row->nama_skpd."</option>";
	            }
	        }
	        return $data;
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

	function usulan_not_in_renja($search, $start, $length, $order){
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$order_arr = array('id_musrenbang','kode_kegiatan','nama_program_kegiatan','jenis_pekerjaan','volume','satuan','jumlah_dana','nama_desa','nama_skpd');

		$sql = "select * from (select t.*,CONCAT(t.kd_urusan,'.',t.kd_bidang,'.',t.kd_program,'.',t.kd_kegiatan) as kode_kegiatan, CONCAT(p.Ket_Program,' | ',k.Ket_Kegiatan) as nama_program_kegiatan,d.nama_desa,s.nama_skpd,a.nama as asal_usulan from (
			select * from (
			select r.id,m.* from t_renja_prog_keg as r right join t_musrenbang as m
			on r.tahun = m.tahun and r.kd_program=m.kd_program and r.kd_bidang=m.kd_bidang and r.kd_program=m.kd_program and r.kd_kegiatan=m.kd_kegiatan
			) as a
			where a.id is null) as t
			left join m_program as p
			on p.Kd_Urusan=t.kd_urusan and p.Kd_Bidang=t.kd_bidang and p.Kd_Prog=t.kd_program
			left join m_kegiatan as k
			on k.Kd_Urusan=t.kd_urusan and k.Kd_Bidang=t.kd_bidang and k.Kd_Prog=t.kd_program and k.Kd_Keg=t.kd_kegiatan
			left join m_desa as d
			on d.id_desa = t.id_desa
			left join m_skpd as s
			on s.id_skpd = t.id_skpd
			left join m_asal_usulan as a
			on t.id_asal_usulan = a.id) as a
			WHERE (
					kode_kegiatan LIKE '%".$search['value']."%' OR
					nama_program_kegiatan LIKE '%".$search['value']."%' OR
					jenis_pekerjaan LIKE '%".$search['value']."%' OR
					nama_desa LIKE '%".$search['value']."%' OR
					nama_skpd LIKE '%".$search['value']."%'
			)
			and tahun = ".$tahun."
			order by ".$order_arr[$order["column"]]." ".$order["dir"]."
			limit ".$start.",".$length."

			";
		return $this->db->query($sql)->result();
	}

	function usulan_not_in_renja_count($search, $start, $length, $order){
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$sql = "select * from (select * from (
			select r.id,m.* from t_renja_prog_keg as r right join t_musrenbang as m
			on r.tahun = m.tahun and r.kd_program=m.kd_program and r.kd_bidang=m.kd_bidang and r.kd_program=m.kd_program and r.kd_kegiatan=m.kd_kegiatan
			) as a
			where a.id is null) as a
			where tahun = ".$tahun;
		return $this->db->query($sql)->num_rows();
	}
	//--------------------------------FUNGSI YANG DIPANGGIL UNTUK MELAKUKAN CETAK------------------------------//
	#     CETAK MUSRENBANGDES   #
	function get_musrenbangdes_cetak($id_desa,$tahun){
		$sql = "
			SELECT m.*,p.Ket_Program AS ket_program, k.Ket_Kegiatan AS ket_kegiatan,s.nama_skpd,kep.nama AS nama_kep,stat.nama AS nama_stat
			FROM ".$this->table_musrenbang." AS m
			LEFT JOIN m_program AS p ON p.Kd_Urusan = m.kd_urusan AND p.Kd_Bidang = m.kd_bidang AND p.Kd_Prog = m.kd_program
			LEFT JOIN m_kegiatan AS k ON  k.Kd_Urusan = m.kd_urusan AND k.Kd_Bidang = m.kd_bidang AND k.Kd_Prog = m.kd_program AND k.Kd_Keg = m.kd_kegiatan
			LEFT JOIN m_keputusan AS kep ON kep.id_keputusan = m.id_keputusan
			LEFT JOIN m_skpd AS s ON s.id_skpd = m.id_skpd
			LEFT JOIN m_status_usulan AS stat ON stat.id = m.id_status_usulan
			WHERE m.id_desa = ".$id_desa." AND m.tahun = ".$tahun."
			ORDER BY kd_urusan ASC,kd_bidang ASC,kd_program ASC, kd_kegiatan ASC
			";
		$data = array($id_desa);
		$result = $this->db->query($sql, $data);
		return $result->result();
	}

	function get_one_musrenbangdes($id_desa, $detail=FALSE){
		$this->db->select($this->table_musrenbang.".*");
		$this->db->from($this->table_musrenbang);
		$this->db->where($this->table_musrenbang.".id_desa", $id_desa);

		if ($detail) {
			$this->db->select("nama_desa");
			$this->db->join("m_desa","t_musrenbang.id_desa = m_desa.id_desa","inner");
		}

		$result = $this->db->get();
		return $result->row();
	}

	#     CETAK MUSRENBANGCAM   #
	function get_musrenbangcam_cetak($id_skpd,$tahun,$id_keputusan){
		$sql ="
			SELECT
				m.*,CONCAT(p.Ket_Program,' | ',k.Ket_Kegiatan) as nama_program_kegiatan,s.nama_skpd
				FROM
				(
					SELECT
						id_musrenbang,
						tahun,
						id_skpd,
						kd_urusan,
						kd_bidang,
						kd_program,
						kd_kegiatan,
						concat(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan,
						sum(if(id_keputusan=".$id_keputusan.",jumlah_dana,0)) as total_jumlah_dana
					FROM
						t_musrenbang
					WHERE
						id_skpd = ".$id_skpd."
						AND tahun = ".$tahun."
						AND flag_delete = '0'
						AND id_keputusan = ".$id_keputusan."
						GROUP BY kode_kegiatan
				) AS m
				left join m_program as p
				on m.kd_urusan = p.Kd_Urusan and m.kd_bidang=p.Kd_Bidang and m.kd_program=p.Kd_Prog
				left join m_kegiatan as k
				on m.kd_urusan = k.Kd_Urusan and m.kd_bidang=k.Kd_Bidang and m.kd_program=k.Kd_Prog and m.kd_kegiatan=k.Kd_Keg
				left join m_skpd as s
				on m.id_skpd=s.id_skpd
			";
		$data = array($id_skpd);
		$result = $this->db->query($sql, $data);
		return $result->result();
	}

	function get_one_musrenbangcam($id_skpd, $detail=FALSE){
		$this->db->select($this->table_musrenbang.".*");
		$this->db->from($this->table_musrenbang);
		$this->db->where($this->table_musrenbang.".id_skpd", $id_skpd);

		if ($detail) {
			$this->db->select("nama_skpd");
			$this->db->join("m_skpd","t_musrenbang.id_skpd = m_skpd.id_skpd","inner");
		}

		$result = $this->db->get();
		return $result->row();
	}

	function get_data_musrenbangcam_detail($kode_kegiatan,$id_skpd,$id_keputusan){
		$tahun = $this->session->userdata('t_anggaran_aktif');
				$sql = "
					SELECT m.*,d.nama_desa,k.nama_kec,au.nama AS asal_usulan,u.nama AS status_usulan,ke.nama AS status_keputusan
						FROM (
							SELECT * FROM (SELECT *,CONCAT(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan
							FROM
								t_musrenbang
							WHERE
							id_skpd = ".$id_skpd."
							AND tahun = ".$tahun."
							AND flag_delete = '0') AS a
							WHERE kode_kegiatan = '".$kode_kegiatan."'
							AND id_keputusan = ".$id_keputusan."
							) AS m
							LEFT JOIN m_desa AS d ON m.id_desa=d.id_desa
							LEFT JOIN m_kecamatan AS k ON m.id_kecamatan=k.id_kec
							LEFT JOIN m_asal_usulan AS au ON m.id_asal_usulan = au.id
							LEFT JOIN m_status_usulan AS u ON m.id_status_usulan=u.id
							LEFT JOIN m_keputusan AS ke ON m.id_keputusan = ke.id_keputusan
							ORDER BY m.id_kecamatan ASC
							";
				$data = array($kode_kegiatan);
				$result = $this->db->query($sql, $data);
				return $result->result();
	}

	function get_total_kegiatan_musrenbangcam($kode_kegiatan,$id_skpd,$id_keputusan){
		$tahun = $this->session->userdata('t_anggaran_aktif');
				$sql = "
						SELECT COUNT(*) AS total FROM (SELECT *,concat(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan
						FROM
							t_musrenbang
						WHERE
						id_skpd = '$id_skpd'
						AND tahun = '$tahun'
						AND flag_delete = '0') AS a
						WHERE kode_kegiatan = '$kode_kegiatan'
						AND id_keputusan = '$id_keputusan'
						";
				$data = array($kode_kegiatan);
				$result = $this->db->query($sql, $data);
				return $result->row();
	}

	#     CETAK FORUM SKPD   #
	function get_forum_skpd_cetak($id_skpd,$tahun){
		$sql = "
			SELECT
				m.*,CONCAT(p.Ket_Program,' | ',k.Ket_Kegiatan) as nama_program_kegiatan,s.nama_skpd
				FROM
				(
					SELECT
						id_musrenbang,
						tahun,
						id_skpd,
						kd_urusan,
						kd_bidang,
						kd_program,
						kd_kegiatan,
						concat(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan,
						sum(if(id_keputusan='2',jumlah_dana,0)) as total_jumlah_dana
					FROM
						t_musrenbang
					WHERE
						id_skpd = ".$id_skpd."
						AND tahun = ".$tahun."
						AND flag_delete = '0'
						AND id_status_usulan >= '5'
						GROUP BY kode_kegiatan
				) AS m
				left join m_program as p
				on m.kd_urusan = p.Kd_Urusan and m.kd_bidang=p.Kd_Bidang and m.kd_program=p.Kd_Prog
				left join m_kegiatan as k
				on m.kd_urusan = k.Kd_Urusan and m.kd_bidang=k.Kd_Bidang and m.kd_program=k.Kd_Prog and m.kd_kegiatan=k.Kd_Keg
				left join m_skpd as s
				on m.id_skpd=s.id_skpd
			";
		$data = array($id_skpd);
		$result = $this->db->query($sql, $data);
		return $result->result();
	}

	function get_one_forum_skpd($id_skpd, $detail=FALSE){
		$this->db->select($this->table_musrenbang.".*");
		$this->db->from($this->table_musrenbang);
		$this->db->where($this->table_musrenbang.".id_skpd", $id_skpd);

		if ($detail) {
			$this->db->select("nama_skpd");
			$this->db->join("m_skpd","t_musrenbang.id_skpd = m_skpd.id_skpd","inner");
		}

		$result = $this->db->get();
		return $result->row();
	}

	function get_data_forum_detail($kode_kegiatan,$id_skpd){
		$tahun = $this->session->userdata('t_anggaran_aktif');
				$sql = "
						SELECT m.*,d.nama_desa,k.nama_kec,au.nama AS asal_usulan,u.nama AS status_usulan,ke.nama AS status_keputusan FROM (
							SELECT * FROM (SELECT *,CONCAT(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan
							FROM
								t_musrenbang
							WHERE
							id_skpd = '$id_skpd'
							AND tahun = '$tahun'
							AND flag_delete = '0'
							AND id_status_usulan >='5') AS a
							WHERE kode_kegiatan = '$kode_kegiatan'
							) AS m
							LEFT JOIN m_desa AS d ON m.id_desa=d.id_desa
							LEFT JOIN m_kecamatan AS k ON m.id_kecamatan=k.id_kec
							LEFT JOIN m_asal_usulan AS au ON m.id_asal_usulan = au.id
							LEFT JOIN m_status_usulan AS u ON m.id_status_usulan=u.id
							LEFT JOIN m_keputusan AS ke ON m.id_keputusan = ke.id_keputusan
							ORDER BY m.id_keputusan ASC
							";
				$data = array($kode_kegiatan);
				$result = $this->db->query($sql, $data);
				return $result->result();
	}

	function get_total_kegiatan($kode_kegiatan,$id_skpd){
		$tahun = $this->session->userdata('t_anggaran_aktif');
				$sql = "
						SELECT COUNT(*) AS total FROM (SELECT *,concat(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan
						FROM
							t_musrenbang
						WHERE
						id_skpd = '$id_skpd'
						AND tahun = '$tahun'
						AND flag_delete = '0'
						AND id_status_usulan >='5') AS a
						WHERE kode_kegiatan = '$kode_kegiatan'
						";
				$data = array($kode_kegiatan);
				$result = $this->db->query($sql, $data);
				return $result->row();
	}

	#		CETAK REKAPITULASI KECAMATAN 		#
	function get_rekap_kecamatan_cetak($id_kecamatan,$tahun){
		$sql = "
			SELECT * FROM (
				SELECT m.*,d.`nama_desa` AS nama_desa ,p.`nama` AS keputusan,skpd.nama_skpd AS nama_skpd FROM t_musrenbang AS m
				LEFT JOIN m_desa AS d ON m.`id_desa`= d.`id_desa`
				LEFT JOIN m_keputusan AS p ON m.id_keputusan=p.id_keputusan
				LEFT JOIN m_skpd AS skpd ON m.id_skpd=skpd.id_skpd
				WHERE m.id_kecamatan = ".$id_kecamatan."
						AND m.tahun = ".$tahun."
						AND m.flag_delete = '0'
				ORDER BY p.`id_keputusan` ASC
			) AS result
			ORDER BY id_desa ASC
			";
		$data = array($id_kecamatan);
		$result = $this->db->query($sql, $data);
		return $result->result();
	}

	function get_one_rekap_kecamatan($id_kecamatan, $detail=FALSE){
		$this->db->select($this->table_musrenbang.".*");
		$this->db->from($this->table_musrenbang);
		$this->db->where($this->table_musrenbang.".id_kecamatan", $id_kecamatan);

		if ($detail) {
			$this->db->select("nama_kec");
			$this->db->join("m_kecamatan","t_musrenbang.id_kecamatan = m_kecamatan.id_kec","inner");
		}

		$result = $this->db->get();
		return $result->row();
	}

	#		CETAK REKAPITULASI SKPD 		#
	function get_rekap_skpd_cetak($id_skpd,$tahun,$id_keputusan){
		$sql = "
			SELECT
				m.*,CONCAT(p.Ket_Program,' | ',k.Ket_Kegiatan) AS nama_program_kegiatan,s.nama_skpd
				FROM
				(
					SELECT
						id_musrenbang,
						tahun,
						id_skpd,
						kd_urusan,
						kd_bidang,
						kd_program,
						kd_kegiatan,
						CONCAT(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan,
						SUM(IF(id_keputusan=".$id_keputusan.",jumlah_dana,0)) AS total_jumlah_dana
					FROM
						t_musrenbang
					WHERE
						id_skpd = ".$id_skpd."
						AND tahun = ".$tahun."
						AND flag_delete = '0'
						AND id_keputusan = ".$id_keputusan."
						GROUP BY kode_kegiatan
				) AS m
				LEFT JOIN m_program AS p
				ON m.kd_urusan = p.Kd_Urusan AND m.kd_bidang=p.Kd_Bidang AND m.kd_program=p.Kd_Prog
				LEFT JOIN m_kegiatan AS k
				ON m.kd_urusan = k.Kd_Urusan AND m.kd_bidang=k.Kd_Bidang AND m.kd_program=k.Kd_Prog AND m.kd_kegiatan=k.Kd_Keg
				LEFT JOIN m_skpd AS s
				ON m.id_skpd=s.id_skpd
			";
		$data = array($id_skpd);
		$result = $this->db->query($sql, $data);
		return $result->result();
	}

	function get_one_rekap_skpd($id_skpd, $detail=FALSE){
		$this->db->select($this->table_musrenbang.".*");
		$this->db->from($this->table_musrenbang);
		$this->db->where($this->table_musrenbang.".id_skpd", $id_skpd);

		if ($detail) {
			$this->db->select("nama_skpd");
			$this->db->join("m_skpd","t_musrenbang.id_skpd = m_skpd.id_skpd","inner");
		}

		$result = $this->db->get();
		return $result->row();
	}

	function get_data_rekap_skpd_detail($kode_kegiatan,$id_skpd,$id_keputusan,$tahun){
		//$tahun = $this->session->userdata('t_anggaran_aktif');
				$sql = "
					SELECT m.*,d.nama_desa,k.nama_kec,au.nama AS asal_usulan,u.nama AS status_usulan,ke.nama AS status_keputusan
						FROM (
							SELECT * FROM (SELECT *,CONCAT(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan
							FROM
								t_musrenbang
							WHERE
							id_skpd = ".$id_skpd."
							AND tahun = ".$tahun."
							AND flag_delete = '0') AS a
							WHERE kode_kegiatan = '".$kode_kegiatan."'
							AND id_keputusan = ".$id_keputusan."
							) AS m
							LEFT JOIN m_desa AS d ON m.id_desa=d.id_desa
							LEFT JOIN m_kecamatan AS k ON m.id_kecamatan=k.id_kec
							LEFT JOIN m_asal_usulan AS au ON m.id_asal_usulan = au.id
							LEFT JOIN m_status_usulan AS u ON m.id_status_usulan=u.id
							LEFT JOIN m_keputusan AS ke ON m.id_keputusan = ke.id_keputusan
							ORDER BY m.id_kecamatan ASC
							";
				$data = array($kode_kegiatan);
				$result = $this->db->query($sql, $data);
				return $result->result();
	}

	function get_total_kegiatan_rekap_skpd($kode_kegiatan,$id_skpd,$id_keputusan,$tahun){
		//$tahun = $this->session->userdata('t_anggaran_aktif');
				$sql = "
						SELECT COUNT(*) AS total FROM (SELECT *,concat(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan
						FROM
							t_musrenbang
						WHERE
						id_skpd = '$id_skpd'
						AND tahun = '$tahun'
						AND flag_delete = '0') AS a
						WHERE kode_kegiatan = '$kode_kegiatan'
						AND id_keputusan = '$id_keputusan'
						";
				$data = array($kode_kegiatan);
				$result = $this->db->query($sql, $data);
				return $result->row();
	}
	//=========================================================================================================//

    function get_summary_biaya(){
        $id_desa = $this->session->userdata('id_desa');
		$tahun = $this->session->userdata('t_anggaran_aktif');

        $sql = "
        select sum(jumlah_dana) as total_biaya from t_musrenbang where id_desa = ".$id_desa." and tahun = ".$tahun." and flag_delete = 0
        ";
        return $this->db->query($sql)->row()->total_biaya;
    }

    function get_summary_biaya_penentuan_skpd(){

        $tahun = $this->session->userdata('t_anggaran_aktif');
        $id_kecamatan = $this->session->userdata('id_kecamatan');

        $sql = "
        select sum(jumlah_dana) as total_biaya from t_musrenbang where id_kecamatan = ".$id_kecamatan." and tahun = ".$tahun." and flag_delete = 0
        ";
        return $this->db->query($sql)->row()->total_biaya;
    }

    function get_summary_biaya_skpd(){

        $tahun = $this->session->userdata('t_anggaran_aktif');
        $id_skpd = $this->session->userdata('id_skpd');

        $sql = "
        select sum(jumlah_dana) as total_biaya from t_musrenbang where id_skpd = ".$id_skpd." and tahun = ".$tahun." and flag_delete = 0
        ";
        return $this->db->query($sql)->row()->total_biaya;
    }

    function get_summary_biaya_kecamatan(){

        $tahun = $this->session->userdata('t_anggaran_aktif');
        $id_skpd = $this->session->userdata('id_skpd');

        $sql = "
        select sum(jumlah_dana) as total_biaya from t_musrenbang where id_skpd = ".$id_skpd." and tahun = ".$tahun." and flag_delete = 0 and id_status_usulan >= '3'
        ";
        return $this->db->query($sql)->row()->total_biaya;
    }
    function get_summary_biaya_usulan_tidak_di_renja(){
        $tahun = $this->session->userdata('t_anggaran_aktif');

        $sql = "
        select sum(jumlah_dana) as total_biaya from (select t.*,CONCAT(t.kd_urusan,'.',t.kd_bidang,'.',t.kd_program,'.',t.kd_kegiatan) as kode_kegiatan, CONCAT(p.Ket_Program,' | ',k.Ket_Kegiatan) as nama_program_kegiatan,d.nama_desa,s.nama_skpd,a.nama as asal_usulan from (
        select * from (
        select r.id,m.* from t_renja_prog_keg as r right join t_musrenbang as m
        on r.tahun = m.tahun and r.kd_program=m.kd_program and r.kd_bidang=m.kd_bidang and r.kd_program=m.kd_program and r.kd_kegiatan=m.kd_kegiatan
        ) as a
        where a.id is null) as t
        left join m_program as p
        on p.Kd_Urusan=t.kd_urusan and p.Kd_Bidang=t.kd_bidang and p.Kd_Prog=t.kd_program
        left join m_kegiatan as k
        on k.Kd_Urusan=t.kd_urusan and k.Kd_Bidang=t.kd_bidang and k.Kd_Prog=t.kd_program and k.Kd_Keg=t.kd_kegiatan
        left join m_desa as d
        on d.id_desa = t.id_desa
        left join m_skpd as s
        on s.id_skpd = t.id_skpd
        left join m_asal_usulan as a
        on t.id_asal_usulan = a.id) as a
        where tahun = ".$tahun."
        ";
        return $this->db->query($sql)->row()->total_biaya;
    }



}
?>
