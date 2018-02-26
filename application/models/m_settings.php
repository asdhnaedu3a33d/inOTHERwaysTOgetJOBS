<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 */
    class M_settings extends CI_Model {
		function get_tahun_anggaran(){

			//buatkan query mengambil tahun anggaran di tabel setting
			$ta = $this->session->userdata('t_anggaran_aktif');
            $result = strval($ta);
			return $result;
		}
		function get_id_tahun(){
			$ta=$this->m_settings->get_tahun_anggaran();
			$query = $this->db->query("SELECT
                                            id
                                        FROM
                                            m_tahun_anggaran
                                        WHERE
                                            tahun_anggaran = '$ta' ");
            $id = $query->row();
            return $id->id;

		}
        function get_tahun_anggaran_db(){

            $query = $this->db->query("SELECT
                                            tahun_anggaran, aktif
                                        FROM
                                            m_tahun_anggaran
                                        ORDER BY
                                            tahun_anggaran ASC");
             return $query->result();
        }
        function get_tahun_anggaran_aktif_db(){

            $query = $this->db->query("SELECT
                                            tahun_anggaran
                                        FROM
                                            m_tahun_anggaran
                                        WHERE
                                            aktif=1
                                        ORDER BY
                                            tahun_anggaran ASC");
            $ta = $query->row();
            return $ta->tahun_anggaran;
        }
		function get_nilai_maxpersen(){

			//nilai persen untuk pengajuan / kirim kwitansi ke unit

			return '0.50';
		}

        function get_max_nominal_belanja_barang()
        {
            //max belanja barang : 20.000.000
            return 50000000;
        }

        function get_bas_uang_muka(){

			//buatkan query mengambil tahun anggaran di tabel setting
			$akun = array('kode_akun'=>'113891', 'nama_akun' =>'Uang Muka Kerja');

			return $akun;
		}

        function getUnitList($id_unit){
             $w = $this->db->query("
                                    SELECT *
                                    FROM m_subunit
                                    WHERE id_unit=$id_unit;
                                   ");
             return $w->result();
        }

        function cari_jumlah($ta,$id_unit){
            $q=$this->db->query("
                                  SELECT t_max_up.id_subunit,nilai_max
                                  FROM t_max_up
                                  JOIN(
                                        SELECT *
                                        FROM m_subunit
                                        WHERE id_unit='$id_unit'
                                  ) as apa
                                  ON t_max_up.id_subunit = apa.id_subunit
                                  WHERE tahun= $ta;
                                ") ;
            return $q->result();
        }

         function cari_pagu_real($ta,$id_unit){
            $q=$this->db->query("
                                SELECT subunit as id_subunit,pagu_real
                                FROM t_pagu_real
                                JOIN (
                                    SELECT *
                                    FROM m_subunit
                                    WHERE id_unit='$id_unit'
                                ) as apa
                                ON t_pagu_real.subunit=apa.id_subunit
                                WHERE tahun= $ta;
                                ");
            return $q->result();
        }

        function detailgetSubUnitList($id_subunit){
            $this->db->select('*');
            $this->db->from('m_subunit');
            $this->db->where('id_subunit',$id_subunit);
            $result = $this->db->get();
            return $result;
        }

        function banyak_mp_max($ta,$id_subunit){
            $tahun = date('Y');
            $this->db->select("nilai_max");
            $this->db->from("t_max_up");
            $this->db->where('tahun',$ta);
            $this->db->where('id_subunit',$id_subunit);
            $result = $this->db->get();
            return $result ;
        }
        function simpan_maks($max_up){
            $tgl_skrg = date("Y-m-d");
            $tahun = date("Y");
            $id_subunit = $this->input->post('id_subunit');
            $nama_user= $this->session->userdata('username');
            $w = $this->db->query("insert into t_max_up(id_subunit,tahun, nilai_max,created_date,created_by) values('$id_subunit','$tahun','$max_up','$tgl_skrg','$nama_user')");
        }
        function getSettingVal($kode){
            $this->db->select("value");
            $this->db->from("m_setting");
            $this->db->where('kode',$kode);
            $result = $this->db->get();
            $row=$result->row();
            if (isset($row->value)){
                return $row->value;
            }else return $kode;

        }

        function get_akun_bas(){
            /*
            SELECT * FROM m_bas_unit
            JOIN m_bas ON m_bas_unit.akun_bas=m_bas.akun_kode
            JOIN m_subunit ON m_bas_unit.id_subunit=m_subunit.id_subunit
            */
            $this->db->from('m_bas_unit');
            $this->db->join('m_bas','m_bas_unit.akun_bas=m_bas.akun_kode');
            $this->db->join('m_subunit','m_bas_unit.id_subunit=m_subunit.id_subunit');
            $this->db->order_by('akun_bas','ASC');
            $dbget=$this->db->get();
            $hasil =$dbget->result();
            return $hasil;
        }


        function get_bas($subunit='', $level='', $kelompok=''){
            /*
                SELECT * FROM (`m_bas`)
                LEFT JOIN m_bas_unit ON m_bas.akun_kode=m_bas_unit.`akun_bas`
                WHERE `level` = '8' AND LEFT(akun_kode, 1) = '1' ORDER BY `akun_kode` ASC
             */
            $result = array();
            $this->db->from('m_bas');
            $this->db->join('m_bas_unit',"m_bas.akun_kode=m_bas_unit.akun_bas AND m_bas_unit.id_subunit='$subunit'",'left');
            if($level!==''){
                $this->db->where('level',$level);
            }

            if($kelompok!==''){
                $this->db->where("LEFT(akun_kode, 1) = '$kelompok'",NULL, false);
            }

            $this->db->order_by('akun_kode','ASC');
            $dbget=$this->db->get();
            $hasil =$dbget->result();
            return $hasil;
        }

        function bas_unit_add_sim($bas){
            $this->db->trans_strict(FALSE);
            $this->db->trans_start();
            foreach($bas as $key=>$value) {
                //echo $key ."    -     ".$value."<br/>";
                $data = array(
                    'akun_bas' => $key ,
                    'id_subunit' => $value
                );

                $this->db->insert('m_bas_unit', $data);
            }
            $this->db->trans_complete();
            return $this->db->trans_status() ;
        }

        function bas_unit_del_sim($bas){
            $this->db->trans_strict(FALSE);
            $this->db->trans_start();
            foreach($bas as $key=>$value) {
                //echo $key ."    -     ".$value."<br/>";
                $this->db->where('id_basunit', $key);
                $this->db->delete('m_bas_unit');
            }
            $this->db->trans_complete();
            return $this->db->trans_status() ;
        }

        function mstr_get($tabel){
    		$query = $this->db->get($tabel);
            return $query->result();
		}
        function pim_unit_update($tabel,$kolom,$id,$objek){
    		$this->db->where($kolom, $id);
    	    $this->db->update($tabel, $objek);
        }

}?>
