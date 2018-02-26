<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_rkpd extends CI_Model 
{
	var $table_musrenbang = 't_musrenbang';
    var $primary_musrenbang = 'id_musrenbang';
    
    var $table_renstra = 't_renstra';
    
    var $table_skpd = 'm_skpd';
    var $primary_skpd = 'id_skpd';
    
    var $table_rkpd = 't_rkpd';
    var $primary_rkpd = 'id_rkpd';
    
    var $table_history_rkpd = 't_history_rkpd';
    var $primary_history_rkpd = 'id_history_rkpd';
    
    var $table_status_rkpd = 'm_status_rkpd';
    var $primary_status_rkpd = 'id_status_rkpd';
 
    
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

    function get_data($data,$table){
        $this->db->where($data);
        $query = $this->db->get($this->$table);
        return $query->row();
    }
    
    function get_data_with_rincian($id,$table){
        $sql = "
					SELECT a.*, u.`Nm_Urusan`AS nm_urusan, b.`Nm_Bidang` AS nm_bidang, p.`Ket_Program` AS ket_program, k.`Ket_Kegiatan` AS ket_kegiatan
                FROM ".$this->$table." AS a
                INNER JOIN m_urusan AS u ON a.`kd_urusan`=u.`Kd_Urusan`
                INNER JOIN m_bidang AS b ON a.`kd_urusan`=b.`Kd_Urusan` 
                                        AND a.`kd_bidang`=b.`Kd_Bidang`
                INNER JOIN m_program AS p ON a.`kd_urusan`=p.`Kd_Urusan` 
                                        AND a.`kd_bidang`=p.`Kd_Bidang` 
                                        AND a.`kd_program`=p.`Kd_Prog`
                INNER JOIN m_kegiatan AS k ON a.`kd_urusan`=k.`Kd_Urusan` 
                                        AND a.`kd_bidang`=k.`Kd_Bidang` 
                                        AND a.`kd_program`=k.`Kd_Prog` 
                                        AND a.`kd_kegiatan`=k.`Kd_Keg`
                WHERE id_musrenbang = ?
				";

			$query = $this->db->query($sql, array($id));
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
    
    function get_data_rkpd_detail($id){
        $sql = "
            SELECT r.*,s.nama_skpd,b.nama_koor as nama_bid_koor,st.status_rkpd
            FROM t_rkpd as r
            LEFT JOIN m_skpd as s
            ON r.id_skpd = s.id_skpd
            LEFT JOIN m_bidkoordinasi as b
            ON r.id_bid_koor=b.id_bidkoor
            LEFT JOIN m_status_rkpd as st
            on r.id_status_verifikasi = st.id_status_rkpd
            WHERE id_rkpd = ?
        ";
        $query = $this->db->query($sql, array($id));

			if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
        
    }    
	
    //get data musrenbang
    //flag_used = '0'
	function get_data_table_musrenbang($search, $start, $length, $order){
		$order_arr = array('id_musrenbang','kode_kegiatan','nama_program_kegiatan','jenis_pekerjaan','nama_skpd');
        $sql = "SELECT * FROM (
                    SELECT 
                        m.*,
                        CONCAT(m.kd_urusan,'.',m.kd_bidang,'.',m.kd_program,'.',m.kd_kegiatan) as kode_kegiatan,
                        CONCAT(p.`Ket_Program`,' | ',k.`Ket_Kegiatan`) as nama_program_kegiatan,
                        ms.nama_skpd
                    FROM t_musrenbang AS m
     			    LEFT JOIN m_program AS p ON m.`kd_urusan`= p.`Kd_Urusan` 
						AND m.`kd_bidang`=p.`Kd_Bidang` 
						AND m.`kd_program`=p.`Kd_Prog`
        			LEFT JOIN m_kegiatan AS k ON m.`kd_urusan`= k.`Kd_Urusan` 
						AND m.`kd_bidang`=k.`Kd_Bidang` 
						AND m.`kd_program`=k.`Kd_Prog` 
						AND m.`kd_kegiatan`=k.`Kd_Keg`
        			LEFT JOIN m_skpd as ms on m.id_skpd=ms.id_skpd ) as a
              	WHERE (
                  kode_kegiatan LIKE '%".$search['value']."%' 
                  OR nama_program_kegiatan LIKE '%".$search['value']."%' 
                  OR jenis_pekerjaan LIKE '%".$search['value']."%'
                  OR nama_skpd LIKE '%".$search['value']."%' )
        		AND  id_status_usulan='0'
        		AND flag_delete='0'
                ORDER BY ".$order_arr[$order["column"]]." ".$order["dir"]."
                LIMIT ".$start.",".$length."";
        return $this->db->query($sql)->result();
	}

	function count_data_table_musrenbang($search, $start, $length, $order){		
         $sql = "
                SELECT * FROM (
                    SELECT 
                        m.*,
                        CONCAT(m.kd_urusan,'.',m.kd_bidang,'.',m.kd_program,'.',m.kd_kegiatan) as kode_kegiatan,
                        CONCAT(p.`Ket_Program`,' | ',k.`Ket_Kegiatan`) as nama_program_kegiatan,
                        ms.nama_skpd
                    FROM t_musrenbang AS m
     			    LEFT JOIN m_program AS p ON m.`kd_urusan`= p.`Kd_Urusan` 
						AND m.`kd_bidang`=p.`Kd_Bidang` 
						AND m.`kd_program`=p.`Kd_Prog`
        			LEFT JOIN m_kegiatan AS k ON m.`kd_urusan`= k.`Kd_Urusan` 
						AND m.`kd_bidang`=k.`Kd_Bidang` 
						AND m.`kd_program`=k.`Kd_Prog` 
						AND m.`kd_kegiatan`=k.`Kd_Keg`
        			LEFT JOIN m_skpd as ms on m.id_skpd=ms.id_skpd ) as a
              	WHERE (
                  kode_kegiatan LIKE '%".$search['value']."%' 
                  OR nama_program_kegiatan LIKE '%".$search['value']."%' 
                  OR jenis_pekerjaan LIKE '%".$search['value']."%' )
        		AND  id_status_usulan='0'
        		AND flag_delete='0'";
        return $this->db->query($sql)->num_rows();    
    }
    
    //get data table from rkpd
    function get_data_table_rkpd($search, $start, $length, $order){
		$order_arr = array('id_rkpd','kode_kegiatan','ket_kegiatan','nama_skpd','nama_bid_koor');
        $sql = "
            SELECT * 
            FROM (
                select r.*,CONCAT(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) as kode_kegiatan,CONCAT(ket_program,' | ',ket_kegiatan) as nama_program_kegiatan,s.nama_skpd,b.nama_koor as nama_bid_koor,st.status_rkpd,p.nama as prioritas from t_rkpd as r
                left join m_skpd as s
                on r.id_skpd = s.id_skpd
                left join m_bidkoordinasi as b
                on r.id_bid_koor=b.id_bidkoor
                left join m_status_rkpd as st
                on r.id_status_verifikasi = st.id_status_rkpd
                left join m_prioritas as p
                on r.id_prioritas=p.id_prioritas
            ) as a
            WHERE (
                kode_kegiatan like '%".$search['value']."%' or
                nama_program_kegiatan like '%".$search['value']."%' or
                nama_skpd like '%".$search['value']."%' or
                nama_bid_koor like '%".$search['value']."%'
            )
            ORDER BY ".$order_arr[$order["column"]]." ".$order["dir"]."
            LIMIT ".$start.",".$length."";
        return $this->db->query($sql)->result();
	}

	function count_data_table_rkpd($search, $start, $length, $order){		
         $sql = "
           SELECT * 
            FROM (
                select r.*,CONCAT(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) as kode_kegiatan,CONCAT(ket_program,' | ',ket_kegiatan) as nama_program_kegiatan,s.nama_skpd,b.nama_koor as nama_bid_koor,st.status_rkpd from t_rkpd as r
                left join m_skpd as s
                on r.id_skpd = s.id_skpd
                left join m_bidkoordinasi as b
                on r.id_bid_koor=b.id_bidkoor
                left join m_status_rkpd as st
                on r.id_status_verifikasi = st.id_status_rkpd
            ) as a
            WHERE (
                kode_kegiatan like '%".$search['value']."%' or
                nama_program_kegiatan like '%".$search['value']."%' or
                nama_skpd like '%".$search['value']."%' or
                nama_bid_koor like '%".$search['value']."%'
            )";
        return $this->db->query($sql)->num_rows();    
    }
    
    function get_from_renja($tahun,$kd_urusan,$kd_bidang,$kd_program,$kd_kegiatan){
        $sql = "
        select 
            CONCAT(a.kd_urusan,'.',a.kd_bidang,'.',a.kd_program,'.',a.kd_kegiatan) as kode_kegiatan,
           	CONCAT(p.Ket_Program,' | ',k.Ket_Kegiatan) as nama_kegiatan,
           	a.*
        from 
       	    (select * from t_renja_prog_keg where is_prog_or_keg=2 
               and tahun='$tahun'
               and kd_urusan='$kd_urusan'
               and kd_bidang='$kd_bidang'
               and kd_program='$kd_program'
               and kd_kegiatan='$kd_kegiatan'
        ) as a
        left join m_program as p
        on a.kd_urusan=p.Kd_Urusan and a.kd_bidang=p.Kd_Bidang and a.kd_program=p.Kd_Prog 
        left join m_kegiatan as k
        on a.kd_urusan=k.Kd_Urusan and a.kd_bidang=k.Kd_Bidang and a.kd_program=k.Kd_Prog  and a.kd_kegiatan=k.Kd_Keg";
        
        return $this->db->query($sql)->result();

    }
    
    function get_from_renja_count($tahun,$kd_urusan,$kd_bidang,$kd_program,$kd_kegiatan){
        $sql = "
        select 
            CONCAT(a.kd_urusan,'.',a.kd_bidang,'.',a.kd_program,'.',a.kd_kegiatan) as kode_kegiatan,
           	CONCAT(p.Ket_Program,' | ',k.Ket_Kegiatan) as nama_kegiatan,
           	a.*
        from 
       	    (select * from t_renja_prog_keg where is_prog_or_keg=2 
               and tahun='$tahun'
               and kd_urusan='$kd_urusan'
               and kd_bidang='$kd_bidang'
               and kd_program='$kd_program'
               and kd_kegiatan='$kd_kegiatan'
        ) as a
        left join m_program as p
        on a.kd_urusan=p.Kd_Urusan and a.kd_bidang=p.Kd_Bidang and a.kd_program=p.Kd_Prog 
        left join m_kegiatan as k
        on a.kd_urusan=k.Kd_Urusan and a.kd_bidang=k.Kd_Bidang and a.kd_program=k.Kd_Prog  and a.kd_kegiatan=k.Kd_Keg";
        
        return $this->db->query($sql)->num_rows();

    }
    
    function get_renja_program($tahun,$kd_urusan,$kd_bidang,$kd_program){
        $sql = "
        select * from t_renja_prog_keg where is_prog_or_keg=1 
               and tahun='$tahun'
               and kd_urusan='$kd_urusan'
               and kd_bidang='$kd_bidang'
               and kd_program='$kd_program'";
        
        return $this->db->query($sql)->row();

    }
    
    
    function sync_to_renja($id_rkpd){
        $data = $this->get_data_rkpd_detail($id_rkpd);
        $count = $this->get_from_renja_count($data->tahun,$data->kd_urusan,$data->kd_bidang,$data->kd_program,$data->kd_kegiatan);
        if($count==0){
            //insert to renja
            //get id_parent
            $renja_parent = $this->get_renja_program($data->tahun,$data->kd_urusan,$data->kd_bidang,$data->kd_program);
            if($renja_parent){
                //ada parent
                $id_parent = $renja_parent->parent;
                $data_post = array(
                    'tahun' => $data->tahun,
                    'is_prog_or_keg' => '2',
                    'kd_urusan' => $data->kd_urusan,
                    'kd_bidang' => $data->kd_bidang,
                    'kd_program' => $data->kd_program,
                    'kd_kegiatan' => $data->kd_kegiatan,
                    'nama_prog_or_keg' => $data->ket_kegiatan,
                    'indikator_kinerja' => $data->jenis_pekerjaan,
                    'id_skpd' => $data->id_skpd,
                    'lokasi' => $data->lokasi,
                    'nominal' => $data->jumlah_dana,
                    'id_status' => 99,
                    'id_status_renja' => 3,  
                    'parent' => $id_parent,  
                );
                $this->db->insert('t_renja_prog_keg',$data_post);
                $last_id = $this->db->insert_id();
                
            }else{
                //tidak ada parent
                //2 kali insert
                $data_post = array(
                    'tahun' => $data->tahun,
                    'is_prog_or_keg' => '1',
                    'kd_urusan' => $data->kd_urusan,
                    'kd_bidang' => $data->kd_bidang,
                    'kd_program' => $data->kd_program,
                    'nama_prog_or_keg' => $data->ket_program,
                    'indikator_kinerja' => $data->jenis_pekerjaan,
                    'id_skpd' => $data->id_skpd,
                    'lokasi' => $data->lokasi,
                    'nominal' => $data->jumlah_dana,
                    'id_status' => 99,
                    'id_status_renja' => 3,    
                );
                $this->db->insert('t_renja_prog_keg',$data_post);
                $id_parent = $this->db->insert_id();
                
                $data_post = array(
                    'tahun' => $data->tahun,
                    'is_prog_or_keg' => '2',
                    'kd_urusan' => $data->kd_urusan,
                    'kd_bidang' => $data->kd_bidang,
                    'kd_program' => $data->kd_program,
                    'kd_kegiatan' => $data->kd_kegiatan,
                    'nama_prog_or_keg' => $data->ket_kegiatan,
                    'indikator_kinerja' => $data->jenis_pekerjaan,
                    'id_skpd' => $data->id_skpd,
                    'lokasi' => $data->lokasi,
                    'nominal' => $data->jumlah_dana,
                    'id_status' => 99,
                    'id_status_renja' => 3,  
                    'parent' => $id_parent,  
                );
               $this->db->insert('t_renja_prog_keg',$data_post);
                $last_id = $this->db->insert_id();
                
                
            }
        }
    }

    function get_data_table_renja($search, $start, $length, $order){
        $id_skpd = $this->session->userdata('id_skpd');
        $order_arr = array('id','kode_kegiatan','nama_program_kegiatan','indikator_kinerja','nama_skpd');
        $sql = "
            select * from 
                (select r.*,CONCAT(r.kd_urusan,'.',r.kd_bidang,'.',r.kd_program,'.',r.kd_kegiatan) as kode_kegiatan,CONCAT(p.Ket_Program,' | ',r.nama_prog_or_keg) as nama_program_kegiatan,s.nama_skpd from (select * from t_renja_prog_keg where is_prog_or_keg = '2') as r
                left join m_program as p
                on r.kd_urusan=p.Kd_Urusan and r.kd_bidang = p.Kd_Bidang and r.kd_program = p.Kd_Prog
                left join m_skpd as s
                on r.id_skpd = s.id_skpd) as a 
            WHERE (
                kode_kegiatan like '%".$search['value']."%' or
                nama_program_kegiatan like '%".$search['value']."%' or
                nama_skpd like '%".$search['value']."%' or
                indikator_kinerja like '%".$search['value']."% '
            )
            and id_skpd = '$id_skpd'
            ORDER BY ".$order_arr[$order["column"]]." ".$order["dir"]."
            LIMIT ".$start.",".$length."";

        return $this->db->query($sql)->result();

    }   

    function get_data_table_renja_count($search, $start, $length, $order){
        $id_skpd = $this->session->userdata('id_skpd');
        $sql = "
            select * from 
                (select r.*,CONCAT(r.kd_urusan,'.',r.kd_bidang,'.',r.kd_program,'.',r.kd_kegiatan) as kode_kegiatan,CONCAT(p.Ket_Program,' | ',r.nama_prog_or_keg) as nama_program_kegiatan,s.nama_skpd from (select * from t_renja_prog_keg where is_prog_or_keg = '2') as r
                left join m_program as p
                on r.kd_urusan=p.Kd_Urusan and r.kd_bidang = p.Kd_Bidang and r.kd_program = p.Kd_Prog
                left join m_skpd as s
                on r.id_skpd = s.id_skpd) as a 
            WHERE (
                kode_kegiatan like '%".$search['value']."%' or
                nama_program_kegiatan like '%".$search['value']."%' or
                nama_skpd like '%".$search['value']."%' or
                indikator_kinerja like '%".$search['value']."%'
            )
            and id_skpd = '$id_skpd'";
            

        return $this->db->query($sql)->num_rows();

    }   

    function get_list_musrenbangcam($kode_kegiatan,$id_skpd){
        $sql = "
        SELECT t.*,d.nama_desa,k.nama_kec,a.nama as nama_asal_usulan,sr.status_rkpd FROM (
            SELECT  *
            FROM
                (
                    SELECT
                        *, CONCAT(kd_urusan,'.',kd_bidang,'.',kd_program,'.',kd_kegiatan) AS kode_kegiatan
                    FROM
                        t_musrenbang
                ) AS t
        WHERE
            t.kode_kegiatan = '$kode_kegiatan'
        AND id_skpd = '$id_skpd') as t
        left join m_desa as d
        on t.id_desa=d.id_desa
        left join m_kecamatan as k
        on t.id_kecamatan = k.id_kec
        left join m_asal_usulan as a
        on t.id_asal_usulan = a.id
        left join m_status_rkpd as sr
        on t.id_status_usulan = sr.id_status_rkpd
        order by t.id_prioritas asc

        ";
        return $this->db->query($sql)->result();
    }

    function get_data_musrenbang($id_musrenbang){
        $sql = "
        SELECT
            t.*, d.nama_desa,
            k.nama_kec,
            a.nama AS nama_asal_usulan,
            sr.status_rkpd,
            u.Nm_Urusan as nm_urusan,
            b.Nm_Bidang as nm_bidang,
            p.Ket_Program as ket_program,
            ke.Ket_Kegiatan as ket_kegiatan,
            sk.nama_skpd as nama_skpd

        FROM
            (
                SELECT
                    *
                FROM
                    t_musrenbang
                WHERE
                    id_musrenbang = '$id_musrenbang'
            ) AS t
        LEFT JOIN m_urusan as u ON t.kd_urusan = u.Kd_Urusan
        LEFT JOIN m_bidang as b on t.kd_urusan = b.Kd_Urusan and t.kd_bidang = b.Kd_Bidang
        LEFT JOIN m_program as p on t.kd_urusan = p.Kd_Urusan and t.kd_bidang = p.Kd_Bidang and t.kd_program = p.Kd_Prog
        LEFT JOIN m_kegiatan as ke on t.kd_urusan = ke.Kd_Urusan and t.kd_bidang = ke.Kd_Bidang and t.kd_program = ke.Kd_Prog and t.kd_kegiatan = ke.Kd_Keg
        LEFT JOIN m_desa AS d ON t.id_desa = d.id_desa
        LEFT JOIN m_kecamatan AS k ON t.id_kecamatan = k.id_kec
        LEFT JOIN m_asal_usulan AS a ON t.id_asal_usulan = a.id
        LEFT JOIN m_status_rkpd AS sr ON t.id_status_usulan = sr.id_status_rkpd
        LEFT JOIN m_skpd as sk on t.id_skpd = sk.id_skpd

        ";
        return $this->db->query($sql)->row();
        
    }
    
}
?>