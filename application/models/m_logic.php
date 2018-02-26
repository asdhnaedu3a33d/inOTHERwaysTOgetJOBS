<?php /**
	* @brief Model Class yang terkait dengan uraian data user
	* uraian data user
	*
	* Class Model ini untuk mengelola datauraian data artist / user
	*
	*
	* @author I Made Agus Setiawan
	*
	*/

    class m_logic extends CI_Model {

        /**
         * get_nilai_sp2d_unit function.
         *
         * @access public
         * @param mixed $ta
         * @param mixed $idu
         * @param mixed $type
         * @param mixed $spj (default: NULL)
         * @return void
         */
        function get_nilai_sp2d_unit ($ta, $idu, $type, $spj = NULL)
        {
            //set konversi false = 0, true = 1, null = %, pergunakan operator "==="

            $spj = ($spj === NULL ? "%" : ($spj === true ? "1" : "0"));

            switch($type)
            {
                case Constant::JB_UP:
                case Constant::JB_TUP:
                    $sql = "
        				SELECT IFNULL(SUM(e.jumlah), 0) AS jumlah
        				FROM t_sp2d a, t_spm b, t_usulan c, t_usulan_item d, t_uraian e
        				WHERE  a.id_spm    = b.id_spm
        					AND b.id_spm   = c.ref_id_spm
        					AND c.id_usulan = d.ref_id_usulan
							AND d.id_usulan_item = e.id_usulan_item
        					AND c.id_unit = ?
        					AND c.sifat_bayar = ?
        					AND YEAR(a.tgl_sp2d) = ?
        					AND a.flag_spj LIKE ?
                    ";
                    break;
                case Constant::JB_GUP:
                case Constant::JB_GUP_NHL:
                case Constant::JB_TUP_NHL:
                case Constant::JB_LS:
                    $sql = "
        				SELECT IFNULL(SUM(b.nominal), 0) AS jumlah
        				FROM t_sp2d a INNER JOIN t_kwitansi b
        					ON a.id_sp2d = b.id_sp2d
        				WHERE  b.id_subunit = ?
        					AND b.sifat_bayar = ?
        					AND YEAR(a.tgl_sp2d) = ?
        					AND a.flag_spj LIKE ?
                    ";
                	break;
            }

        	if($sql !== "") {
        		$query = $this->db->query($sql, array($su, $type, $ta, $spj));
                if ($query->num_rows() > 0) {
                    $result = $query->row();
                    return $result->jumlah;
                }
            }

            return FALSE;
        }

        /**
         * Fungsi untuk mencari nilai nominal sp2d berdasarkan type sp2d yang dilakukan
         * $su sub unit tertentu pada $ta tahun anggaran tertentu
         *
         * @param $ta   Tahun Anggaran : 4 digit
         * @param $su   Sub Unit pengusul
         * @param $type Tipe Usulan/belanja (UP, TUP, GUP, LS)
         * @param $spj  Apakah sp2d yang dicek sudah di-spj-kan apa belum. default parameter nya adalah NULL, jika
         *              field spj tidak diperhitungkan
         *
         * @return nilai dari SP2D atau 0 jika tidak ada records
         */
        function get_nilai_sp2d ($ta, $su, $type, $spj = NULL)
        {
            //set konversi false = 0, true = 1, null = %, pergunakan operator "==="

            $spj = ($spj === NULL ? "%" : ($spj === true ? "1" : "0"));

            switch($type)
            {
                case Constant::JB_UP:
                case Constant::JB_TUP:
                    $sql = "
        				SELECT IFNULL(SUM(e.jumlah), 0) AS jumlah
        				FROM t_sp2d a,t_spm b, t_usulan c, t_usulan_item d, t_uraian e
        				WHERE  a.id_spm    = b.id_spm
        					AND b.id_spm   = c.ref_id_spm
        					AND c.id_usulan = d.ref_id_usulan
							AND d.id_usulan_item = e.id_usulan_item
        					AND c.id_subunit = ?
        					AND c.sifat_bayar = ?
        					AND YEAR(a.tgl_sp2d) = ?
        					AND a.flag_spj LIKE ?
                    ";
                    break;
                case Constant::JB_GUP:
                case Constant::JB_GUP_NHL:
                case Constant::JB_TUP_NHL:
                case Constant::JB_LS:
                    $sql = "
        				SELECT IFNULL(SUM(b.nominal), 0) AS jumlah
        				FROM t_sp2d a INNER JOIN t_kwitansi b
        					ON a.id_sp2d = b.id_sp2d
        				WHERE  b.id_subunit = ?
        					AND b.sifat_bayar = ?
        					AND YEAR(a.tgl_sp2d) = ?
        					AND a.flag_spj LIKE ?
                    ";
                	break;
            }

        	if($sql !== "") {
        		$query = $this->db->query($sql, array($su, $type, $ta, $spj));
                if ($query->num_rows() > 0) {
                    $result = $query->row();
                    return $result->jumlah;
                }
            }

            return FALSE;
        }

        /**
         * Fungsi ini digunakan untuk mencari nilai sp2d UP yang telah diajukan dan disetujui
         * tanpa memperhatikan apakah telah dispjkan atau tidak. Fungsi ini untuk menentukan
         * nilaiUP yang telah diusulkan.
         *
         * @param $ta   Tahun Anggaran : 4 digit
         * @param $su   Sub Unit pengusul
         * @return nilai usulan UP yang sudah SP2D atau 0 jika usulannya belum sampai SP2D
         */
        function get_nilai_sp2d_up ($ta, $su) {

            $type   = Constant::JB_UP;

            //spj tidak diperhitungkan
            return $this->get_nilai_sp2d($ta, $su, $type);

        }

        /**
         * Fungsi ini digunakan untuk memperoleh nilai UP yang telah diajukan dan disetujui oleh
         * BLU pusat. Dapat diperoleh dengan mencari nilai SP2D UP nya.
         *
         * @param $ta   Tahun Anggaran : 4 digit
         * @param $su   Sub Unit pengusul
         * @return nilai usulan UP yang sudah SP2D atau 0 jika usulannya belum sampai SP2D
         */
        function get_nilai_up ($ta, $su) {
            return $this->get_nilai_sp2d_up($ta, $su);
        }

        function get_nilai_up_sunit ($ta, $idsu) {
            return $this->get_nilai_sp2d_up($ta, $idsu);
        }

        function get_nilai_up_unit ($ta, $idu) {

            $type = Constant::JB_UP;

            //sementara masih menggunakan tahun 2 digit
            //$ta  = substr($ta, -2);

            $sql = "
				SELECT IFNULL(SUM(c.total_usulan), 0) AS jumlah
				FROM t_sp2d a,t_spm b, t_usulan c
				WHERE  a.id_spm    = b.id_spm
					AND b.id_spm   = c.ref_id_spm
                    AND b.id_unit = {$idu}
                    AND c.sifat_bayar = '{$type}'
                    AND YEAR(a.tgl_sp2d) = {$ta}
                ";

            if($sql !== "") {
                $result = $this->db->query($sql);
                if ($result) {
                    $result = $result->row();
                    return $result->jumlah;
                }
            }

            return 0;

        }


        /**
         * Fungsi ini untuk melakukan pengecekan apakah sudah mengusulkan UP sampai keluar SP2D
         * apa tidak. Pada dasarnya bisa memanfaatkan fungsi get_nilai_sp2d_up() dengan mengannalisis
         * output yang dihasilkan. jika output = 0, maka UP yang diusulkan belum keluar SP2D
         *
         * @param $ta   Tahun Anggaran : 4 digit
         * @param $su   Sub Unit pengusul
         * @return true jika sudah ada sp2d up, false jika belum ada
         */
        function check_sp2d_up ($ta, $su) {
            $nilai_sp2d_up = $this->get_nilai_sp2d_up($ta, $su);

            if ($nilai_sp2d_up > 0) return true;

            return false;
        }

        /**
         * FUngsi ini digunakan untuk mencari nilai sp2d tup yang telah diajukan $su sub unit
         * pada $ta tahun anggaran tertentu, dengan pemilihan sudah dispjkan atau belum.
         * NIlai ini dipakai ketika akan mengajukan SPJ-TUP
         *
         * @param   $ta   Tahun Anggaran : 4 digit
         * @param   $su   Sub Unit pengusul
         * @param   $spj  Apakah sp2d yang dicek sudah di-spj-kan apa belum.
         *                default parameter nya adalah false.
         *
         * @return  Jumlah nilai sp2d tup.
         *          Jika $spj = false,
         *              0 jika semua sudah dispjkan atau error, dan
         *              > 0 jika ada sp2d tup yang belum dispjkan
         *          Jika $spj = true,
         *              0 jika belum ada yang dispjkan
         *              > 0 terdapat sp2d tup yang sudah dispjkan
         */
        function get_nilai_sp2d_tup ($ta, $su, $spj = false) {
            $type = Constant::JB_TUP;

            return $this->get_nilai_sp2d($ta, $su, $type, $spj);
        }

        /**
         * Fungsi ini digunakan untuk mencari jumlah nilai TUP baik yang belum di-spj-kan,
         * atau yang sedang diajukan.
         *
         * @param   $ta tahun anggaran : 4 digit
         * @param   $su Sub unit pengusul
         *
         * @return  Jumlah nilai TUP
         */
        function get_nilai_tup ($ta, $su){

            $this->load->model('m_usulan');
            //Kedua kondisi dibawah (1 dan 2) HARUS nya tidak mungkin akan terjadi secara bersamaan
            //karena usulan TUP tidak akan bisa diajukan jika ada SP2D TUP yang belum di SPJ kan

            //1. Hitung nilai sp2d tup yang belum di spj kan.
            //send false value to $spj parameter for "Belum di SPJ kan"
            $nilai_sp2d_tup_belum_spj = $this->get_nilai_sp2d_tup($ta, $su, false);

            //2. Hitung nilai uraian tup yang sudah diajukan, tapi belum sampai keluar SP2D TUP
            //dapat ditentukan dengan mencari semua uraian usulan bertipe TUP dengan status
            // kurang dari (<) 15 (15 = status uraian sudah dibuatkan sp2d)
            //5/10/13-REVISI: status 15 tidak digunakan lagi.
            //nilai usulan tup dapat dicari dengan melihat
            $nilai_usulan_tup = $this->m_usulan->get_nilai_uraian_usulan($ta, $su, Constant::JB_TUP, 15);

            //TODO: Disini dapat dilakukan pengecekan silang, konsistensi kondisi 1 & 2
            //tidak boleh ada nilai usulan jika masih ada sp2d tup yg belum di spj kan.
            if($nilai_usulan_tup > 0 && $nilai_sp2d_tup_belum_spj > 0){
                //TODO: harusnya tidak boleh terjadi, tentukan statement penanganannya
            }

    		return ( $nilai_sp2d_tup_belum_spj + $nilai_usulan_tup );
        }

		/**
         * Fungsi ini digunakan untuk mencari jumlah nilai TUP baik yang belum di-spj-kan,
         * atau yang sedang diajukan.
         *
         * @param   $ta tahun anggaran : 4 digit
         * @param   $idu  unit pengusul
         *
         * @return  Jumlah nilai TUP
         */
        function get_nilai_tup_unit ($ta, $idu){

		}


        /**
         * Fungsi ini menghitung jumlah nilai kwitansi yang telah diajukan oleh $su sub unit pada
         * $ta tahun anggaran tertentu berdasarkan $type dari kwitansi (TUP, GUP, LS) dengan
         * mempertimbangkan status terakhir dari kwitansi tersebut, atau dengan status tertentu
         *
         * @param $ta   Tahun Anggaran : 4 digit
         * @param $su   Sub Unit Pembuat
         * @param $type TUP-GUP-LS-ALL
         * @param $max_status   hanya memperhitungkan kwitansi dengan status terakhir yg ditentukan
         * @param $where        akan dipergunakan jika $max_status = NULL
         *
         * @return  Jika benar, maka fungsi ini akan mengembalikan nilai total kwitansi yang
         *          telah dibuat. Jika salah, maka akan mengembalikan nilai 0
         */
        function get_nilai_kwitansi($ta, $su, $type, $max_status, $where="") {
            //type:TUP-GUP-LS-all

            //sementara masih menggunakan tahun 2 digit
            //$ta  = substr($ta, -2);

            $this->db->select('IFNULL(SUM(nominal), 0) AS jumlah', FALSE);
            $this->db->from('t_kwitansi');
            $this->db->where('YEAR(tgl_kwitansi)',$ta);
            $this->db->where('id_subunit',$su);

            switch ($type) {
               	case Constant::JB_TUP:
               	case Constant::JB_GUP:
               	case Constant::JB_LS:
            		$this->db->where('sifat_bayar',$type);
            		break;
            	default:
            		$this->db->where("sifat_bayar like '".Constant::JB_ALL."'");
            }

            if($max_status === NULL) {
               if($where === ""){
            	   return 0;
               } else {
            	   $this->db->where($where);
               }
            } else {
              $this->db->where("id_status_kwitansi < {$max_status}");
            }

            $result = $this->db->get();
            if($result) {
                $result = $result->row();
                return $result->jumlah;
            }

            return 0;
       	}

        /**
         * Fungsi ini untuk mendapatkan nilai pagu realisasi penerimaan dari suatu $su sub unit
         * pada $ta tahun anggaran tertentu.
         *
         * @param $ta   Tahun Anggaran : 4 digit
         * @param $su   Sub Unit
         *
         * @return nilai pagu real, atau 0 jika belum memiliki pagu real
         */
        function get_pagu_real($ta, $su){
            // nilai pagu real

            $this->db->select('pagu_real');
            $this->db->from('t_pagu_real');
            $this->db->where('subunit',$su);
            $this->db->where('tahun',$ta);

            $result = $this->db->get();
            if($result) {
                if($result->num_rows() > 0) {
                    $result = $result->row();
                    return $result->pagu_real;
                }
            }
            return 0;
        }

		function get_pagu_real_sunit($ta, $idsu) {
			return $this->get_pagu_real($ta, $idsu);
		}

        function get_pagu_real_unit($ta, $idu) {
            $sql = "
                SELECT SUM(pagu_real) AS pagu_real
                FROM t_pagu_real a INNER JOIN m_subunit b
                    ON a.subunit=b.id_subunit
                WHERE id_unit = '$idu'
                AND tahun=$ta
            ";

            $result = $this->db->query($sql);
            $tes = $result->row();
            $pagu = $tes->pagu_real;
            if($pagu!=NULL){
                return $pagu;
            }else{
               return 0;
            }

        }

        function get_pok_unit($ta, $idu) {
            $sql = "
                SELECT SUM(jumlah) AS pok
                FROM t_rkakl a INNER JOIN m_subunit b ON a.kode_unit=b.kode_subunit
                WHERE id_unit = '$idu'
                AND tahun=$ta
            ";

            $result = $this->db->query($sql);
            if($result){
                $result = $result->row();
                return $result->pok;
            }
            return 0;
        }

        function get_pok_sunit($ta, $idsu) {
            $sql = "
                SELECT SUM(jumlah) AS pok
                FROM t_rkakl a INNER JOIN m_subunit b ON a.kode_unit=b.kode_subunit
                WHERE id_subunit = '$idsu'
                AND tahun=$ta
            ";

            $result = $this->db->query($sql);
            if($result){
                $result = $result->row();
                return $result->pok;
            }
            return 0;
        }


	//====Berikut adalah kumpulan fungsi perhitungan limit pengajuan=====

        /**
         * Fungsi ini bertujuan untuk mengecek apakah suatu $su sub unit sudah memiliki
         * pagu realisasi pada $ta tahun anggaran tertentu. di cek di tabel t_pagu_real.
         * Pada dasarnya dapat dilakukan dengan menggunakan function get_pagu_real, jika
         * didapatkan nilai 0, maka belum memiliki pagu real.
         *
         * @param $ta   Tahun Anggaran : 4 digit
         * @param $su   Sub Unit
         *
         * @return true jika ada, false jika belum ada
         */
        function cek_ada_pagu_real ($ta, $su){
            $pagu_real = $this->get_pagu_real($ta, $su);

            if ($pagu_real > 0) return true;

            return false;
        }

        /**
         * Fungsi ini digunakan untuk menghitung nilai SISA PAGU SEMU (SPS), untuk membatasi
         * apakah suatu sub unit dapat mengajukan pembuatan kwitansi (spj) maupun pengajuan
         * tup. Hanya saja masih bersifat SEMU, tidak sama dengan yang sudah tercatat di SPP
         * maupun MP. SPS ini adalah KONDISI TERKINI dari PAGU REALISASI Sub Unit.
         *
         * @param $ta   Tahun Anggaran : 4 digit
         * @param $su   Sub unit
         *
         * @return nilai sisa pagu semu (sps)
         */
        function get_sps ($ta, $su) {

    		$pagu_real 		= $this->get_pagu_real($ta, $su);

            $sisa_mp        = $this->get_sisa_mp($ta, $su);
            $nilai_dpt      = $this->get_nilai_dpt($ta, $su);

            $nilai_up       = $this->get_nilai_up($ta, $su);
            $nilai_tup      = $this->get_nilai_tup($ta, $su);

            $all_kwitansi_less_6    = $this->get_nilai_kwitansi($ta, $su, Constant::JB_ALL, 6);

            $is_mp_available= $this->check_mp_available($ta, $su);
            $sps = 0;
    		if($is_mp_available) {
                //MP sudah dibuatkan
                $sps = $sisa_mp - $nilai_dpt - $nilai_tup - $all_kwitansi_less_6;
    		} else {
                // MP belum ada
    			$sps = $pagu_real - $nilai_up - $nilai_dpt - $nilai_tup - $all_kwitansi_less_6;
    		}

            return $sps < 0 ? 0 : $sps;
    	}


        /**
         * Fungsi ini digunakan untuk mencari nilai limit pagu untuk pengajuan SPJ UP (GUP)
         *
         * @param $ta   Tahun anggaran : 4 digit
         * @param $su   Sub Unit
         *
         * @return nilai limit pengajuan
         */
        function get_limit_spjup($ta, $su) {

            $sps            = $this->get_sps($ta, $su);
            $nilai_up       = $this->get_nilai_up($ta, $su);
            $gup_kwitansi_less_6    = $this->get_nilai_kwitansi($ta, $su, Constant::JB_GUP, 6);

            //untuk mencari limit pengajuan spj up (GUP), dicari nilai minimal antara
            //(Nilai UP - kwitansinew6)  dengan SPS
            $sisa_jatah_spj = $nilai_up - $gup_kwitansi_less_6;
            $sisa_jatah_spj = $sisa_jatah_spj < 0 ? 0 : $sisa_jatah_spj;

			if($sps > 0) {
				return min($sisa_jatah_spj, $sps);
			} else {
				//ini terjadi diakhir masa periode tahunanggaran, GUP NIHIL
				return $sisa_jatah_spj;
			}

        }

		function get_limit_spjtup($ta, $su) {
            $nilai_tup       = $this->get_nilai_tup($ta, $su);
            $tup_kwitansi_less_6    = $this->get_nilai_kwitansi($ta, $su, Constant::JB_TUP_NHL, 6);

            //untuk mencari limit pengajuan spj tup (TUP NIHIL), dicari selisih
            //(Nilai TUP - kwitansinew6)
            $sisa_jatah_spj = $nilai_tup - $tup_kwitansi_less_6;

			//TUP NIHIL
			return $sisa_jatah_spj;
		}

        /**
         * Fungsi ini untuk melakukan pengecekan apakah sudah dibuatkan MP untuk $su sub unit
         * yang ditentukan pada $ta tahun anggaran tersebut.
         *
         * @param $ta   tahun anggaran : 4 digit
         * @param $su   sub unit
         *
         * @return true jika sudah ada, false jika belum ada
         */
        function check_mp_available($ta, $su){
            $sql = "
                SELECT *
                FROM t_mp a INNER JOIN t_mp_det b ON a.id_mp=b.id_mp
                WHERE year(a.tgl_mp)={$ta}
                    AND b.id_sunit={$su}
            ";

            $result = $this->db->query($sql);
            if ($result) {
                return ($result->num_rows() > 0 ? true : false);
            }

            return false;
        }


        /**
         * Fungsi ini digunakan untuk mencari nilai Dana Parkir Tender (DPT) yang di-reserve
         * untuk alokasi tender masing-masing $su sub unit pada $ta tahun anggaran tertentu.
         * Tabel: t_dpt
         * Nilai DPT yang diperhitungkan adalah selisih antara  jum_dpt dan realisasi JIKA
         * status nya masih OPEN. jika CLOSED, maka default Nilai DPT = 0
         *
         * @param   $ta     Tahun Anggaran : 4 digit
         * @param   $su     Sub Unit
         *
         * @return  0 jika belum ada usulan ATAU usulan sudah CLOSED
         *          > 0 Jika sudah ada usulan dan OPEN
         */
        function get_nilai_dpt($ta, $su){
            //nilai dpt
            $sql = "
                SELECT IFNULL( SUM(jum_dpt) - SUM(jum_realisasi), 0) as jumlah
                FROM t_dpt
                WHERE id_subunit = {$su}
                    AND tahun = {$ta}
                    AND status='OPEN'
            ";

            //karena 1 subunit 1 tahun anggaran hanya memiliki 1 record di t_dpt,
            //maka jika subunit belum mengajukan DPT atau DPT yang diajukan sudah
            //CLOSED, dengan query diatas otomatis nilai nya adalah 0. sehingga
            //tidak perlu lagi melakukan pengecekan

            $result = $this->db->query($sql);
            if ($result) {
                $result = $result->row();
                return $result->jumlah;
            }

            return 0;
	    }

		/**
		 *
		 * @param $params array, isi
		 */
		function get_nilai_dpt_by_params($ta, $su, $params) {
			//params : $kdgiat, $kdoutput, $kdkomponen, $kdakun, $hdr

			$sql = "
				SELECT IFNULL(SUM(b.jumlah), 0) as jumlah
				FROM t_dpt a INNER JOIN t_dpt_det b USING(id_dpt)
                WHERE a.id_subunit = {$su}
                    AND a.tahun = {$ta}
                    AND a.status='OPEN'
					AND b.kdgiat = ?
					AND b.kdoutput = ?
					AND b.kdkomponen = ?
					AND b.kdakun = ?
			";

			if(isset($params['hdr'])){
				$sql .= "AND b.hdr = ?";
			}

			$result = $this->db->query($sql, $params);
            if ($result->num_rows() > 0) {
                $result = $result->row();
                return $result->jumlah;
            }

			return 0;
		}

        /**
         * Fungsi ini bertujuan untuk menghitung nilai maksimum pencairan dana yang dapat
         * digunakan, dan sudah tercatat (DEFINITIF). Nilai ini tidak termasuk SPP yang
         * diajukan saat ini.
         *
         * @param $ta   Tahun Anggaran : 4 digit
         * @param $su   Sub Unit
         *
         * @return Nilai MP Sub Unit
         */
        function get_nilai_mp ($ta, $su) {
           $sql = "
                SELECT IFNULL( (b.alokasi_pnbp + b.tmb_alokasi_pnbp) -
                               (b.spp_up + b.spp_tup + b.spp_gu + b.spp_ls),
                               0 ) AS mp
                FROM t_mp a INNER JOIN t_mp_det b ON a.id_mp=b.id_mp
                WHERE year(a.tgl_mp)={$ta}
                    AND b.id_sunit={$su}
                ORDER BY a.tgl_mp DESC
                LIMIT 1
            ";

            $result = $this->db->query($sql);
            if ($result) {
                $result = $result->row();
                return $result->mp;
            }

            return 0;
        }

        /**
         *
         *
         */
		function get_sisa_mp_unit ($ta, $idu)
		{
		 $sql = "

		 ";
		}

         /**
          *
          *
          */
         function get_sisa_mp_sunit ($ta, $idsu)
         {
	         $this->get_sisa_mp($ta, $idsu);
         }

        /**
         * Fungsi ini digunakan untuk mendapatkan nilai sisa pagu realisasi DEFINITIF Tercatat
         * (Maksimum Pencairan Dana) Sisa MP berdasarkan SPP yang telah diajukan. Fungsi ini
         * akan mencari nilai sisa MP untuk $su SUB UNIT tertentu, bukan UNIT,
         * pada $ta tahun anggaran tertentu. Sehingga data ini diambil dari t_mp_det.
         * Tabel : t_mp (unit) dan t_mp_det(sub unit)
         *
         * @param $ta   Tahun Anggaran : 4 digit
         * @param $su   Sub Unit
         *
         * @return nilai Sisa MP sub unit
         */
        function get_sisa_mp ($ta, $su){


            $sql = "
                SELECT IFNULL( (b.alokasi_pnbp + b.tmb_alokasi_pnbp) -
                               (b.spp_gu + b.spp_ls) - b.spp_ini,
                               0 ) AS sisa_mp
                FROM t_mp a INNER JOIN t_mp_det b ON a.id_mp=b.id_mp
                WHERE year(a.tgl_mp)={$ta}
                    AND b.id_sunit={$su}
                ORDER BY a.tgl_mp DESC
                LIMIT 1
            ";

            $result = $this->db->query($sql);
			if($result->num_rows() > 0) {
			    $result = $result->row();
			    if(isset($result->sisa_mp)){
			      return $result->sisa_mp;
			    }
			}

			return 0;
        }

	/**
	 *
	 */
	function get_nilai_max_up($ta, $idsu) {
		$sql = "
			SELECT nilai_max
			FROM t_max_up
			WHERE tahun = ?
				AND id_subunit = ?
		";

		$query = $this->db->query($sql, array($ta, $idsu));

		//return FALSE jika nilai_max belum ada
		if($query->num_rows() > 0){
			$nilai_max = $query->row()->nilai_max;
			return $nilai_max > 0 ? $nilai_max : FALSE;
		}

		return 0;
	}

	function get_penerimaan_total_sunit($ta, $idsu) {
            $sql = "
                SELECT SUM(jum_pro) AS total
                FROM t_proporsi a
                WHERE subunit_asal = '$idsu'
                AND tahun='$ta'
            ";

            $result = $this->db->query($sql);
            $tes = $result->row();
            $pagu = $tes->total;
            if($pagu!=NULL){
                return $pagu;
            }else{
               return 0;
            }

	}

	function get_sisa_pagu_sunit($ta, $idsu) {
		$this->get_pagu_real_sunit($ta, $idsu);
	}
	
	function get_nilai_spm_up ($ta, $su) {

            $type   = Constant::JB_UP;

            //spj tidak diperhitungkan
            return $this->get_nilai_spm($ta, $su, $type);

        }
		
	function get_nilai_spm_tup ($ta, $su) {

            $type   = Constant::JB_TUP;

            //spj tidak diperhitungkan
            return $this->get_nilai_spm($ta, $su, $type);

        }
		

	function get_nilai_spm ($ta, $su, $type, $spj = NULL)
        {
            //set konversi false = 0, true = 1, null = %, pergunakan operator "==="

            $spj = ($spj === NULL ? "%" : ($spj === true ? "1" : "0"));

            switch($type)
            {
                case Constant::JB_UP:
                case Constant::JB_TUP:
                    $sql = "
        				SELECT IFNULL(SUM(e.jumlah), 0) AS jumlah
        				FROM t_spm b, t_usulan c, t_usulan_item d, t_uraian e
        				WHERE b.id_spm
        					AND b.id_spm   = c.ref_id_spm
        					AND c.id_usulan = d.ref_id_usulan
							AND d.id_usulan_item = e.id_usulan_item
        					AND c.id_subunit = ?
        					AND c.sifat_bayar = ?
        					AND YEAR(b.tgl_spm) = ?
        					
                    ";
                    break;
                case Constant::JB_GUP:
                case Constant::JB_GUP_NHL:
                case Constant::JB_TUP_NHL:
                case Constant::JB_LS:
                    $sql = "
        				SELECT IFNULL(SUM(b.nominal), 0) AS jumlah
        				FROM t_spm a INNER JOIN t_kwitansi b
        					ON a.id_spm = b.id_spm
        				WHERE  b.id_subunit = ?
        					AND b.sifat_bayar = ?
        					AND YEAR(a.tgl_spm) = ?
        					AND a.flag_spj LIKE ?
                    ";
                	break;
            }

        	if($sql !== "") {
        		$query = $this->db->query($sql, array($su, $type, $ta, $spj));
                if ($query->num_rows() > 0) {
                    $result = $query->row();
                    return $result->jumlah;
                }
            }

            return FALSE;
        }
	
	function get_nilai_spm_up_unit ($ta, $idu) {

            $type = Constant::JB_UP;

            //sementara masih menggunakan tahun 2 digit
            //$ta  = substr($ta, -2);

            $sql = "
				SELECT IFNULL(SUM(c.total_usulan), 0) AS jumlah
				FROM t_spm b, t_usulan c
				WHERE b.id_spm
					AND b.id_spm   = c.ref_id_spm
                    AND b.id_unit = {$idu}
                    AND c.sifat_bayar = '{$type}'
                    AND YEAR(b.tgl_spm) = {$ta}
                ";

           if($sql !== "") {
                $result = $this->db->query($sql);
                if ($result) {
                    $result = $result->row();
                    return $result->jumlah;
                }
            }

            return 0;
         }
	
	function get_nilai_spm_tup_unit ($ta, $idu) {

            $type = Constant::JB_TUP;

            //sementara masih menggunakan tahun 2 digit
            //$ta  = substr($ta, -2);

            $sql = "
				SELECT IFNULL(SUM(c.total_usulan), 0) AS jumlah
				FROM t_spm b, t_usulan c
				WHERE b.id_spm
					AND b.id_spm   = c.ref_id_spm
                    AND b.id_unit = {$idu}
                    AND c.sifat_bayar = '{$type}'
                    AND YEAR(b.tgl_spm) = {$ta}
                ";

           if($sql !== "") {
                $result = $this->db->query($sql);
                if ($result) {
                    $result = $result->row();
                    return $result->jumlah;
                }
            }

            return 0;
         }

	function get_nilai_max_up_sunit ($su, $ta) {
            // mencari nilai max up untuk subunit

            $sql = "
				SELECT nilai_max AS jumlah
				FROM t_max_up
				WHERE id_subunit = {$su}
                AND tahun = {$ta}
                ";
            $result = $this->db->query($sql);
            $result = $result->row();
           if(!empty($result->jumlah)){
                return $result->jumlah;
            }
            return 0;
         }
		 
	function get_nilai_max_up_unit ($idu, $ta) {
            // mencari nilai max up untuk unit

            $sql = "
				SELECT SUM(nilai_max) AS jumlah
				FROM t_max_up a
				INNER JOIN m_subunit b ON a.id_subunit = b.id_subunit
				INNER JOIN m_unit c ON b.id_unit = c.id_unit 
				WHERE c.id_unit = {$idu}
                AND tahun = {$ta}
                ";
            $result = $this->db->query($sql);
            $result = $result->row();
           if(!empty($result->jumlah)){
                return $result->jumlah;
            }
            return 0;
         }

}

/**
 * NOTE CI DB query
 *
 * FALSE : jika query error
 * num_rows = 0 jika tidak menghasilkan row
 * num_rows > 0 jika ada row yang dihasilkan
 * result() mengembalikan array row
 * row() mengembalikan baris pertama data
 */
?>
