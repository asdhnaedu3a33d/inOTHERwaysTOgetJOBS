<?php
/**
 *
 * Class ini digunakan sebagai common gateway ke internal data
 *
 *
 */
class Common extends CI_Controller
{
    var $CI = NULL;
    public function __construct()
    {
        $this->CI =& get_instance();
        parent::__construct();

        $this->load->model(array('m_bidang', 'm_urusan', 'm_kegiatan', 'm_program', 'm_skpd', 'm_kecamatan', 'm_desa', 'm_groups','m_jenis_pendapatan','m_asal_pendapatan',
        'm_jenis_belanja','m_kategori_belanja','m_subkategori_belanja','m_kode_belanja', 'm_rpjmd_trx', 'm_lov'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }

    }

    function cmb_program_rpjmd(){
      $id_program = $this->input->post('id_program');
      $result = $this->m_rpjmd_trx->get_one_program_rpjmd_for_me($id_program);
      $kd_urusan_edit = $result[0]->kd_urusan;
      $kd_bidang_edit = $result[0]->kd_bidang;
      $kd_program_edit = $result[0]->kd_program;
      $kd_urusan = array("" => "");
  		foreach ($this->m_urusan->get_urusan() as $row) {
  			$kd_urusan[$row->id] = $row->id .". ". $row->nama;
  		}
      $kd_bidang = array("" => "");
      foreach ($this->m_bidang->get_bidang($kd_urusan_edit) as $row) {
          $kd_bidang[$row->id] = $row->id .". ". $row->nama;
      }
      $kd_program = array("" => "");
      foreach ($this->m_program->get_prog($kd_urusan_edit, $kd_bidang_edit) as $row) {
          $kd_program[$row->id] = $row->id .". ". $row->nama;
      }
      $cmb['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
      $cmb['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang" class="common chosen-select" id="kd_bidang"');
      $cmb['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Kode Program" class="common chosen-select" id="kd_program"');
      // echo $cmb;
      echo json_encode($cmb);
    }

    function table_indikator_program_rpjmd(){
      $id_program = $this->input->post('id_program');
	  $data['program_rpjmd'] = $this->m_rpjmd_trx->get_one_program_rpjmd_for_me($id_program);
      $data['indikator_program_rpjmd'] = $this->m_rpjmd_trx->get_indikator_program_rpjmd_for_me($id_program);
	  $data['pagu_sisa'] = $this->m_rpjmd_trx->get_sisa_pagu_rpjmd($id_program);
      echo $this->load->view('renstra/indikator_program_rpjmd', $data);
    }

    function edit_jenis_belanja(){
      $nama = $this->input->post('nama');
      $jenis = $this->input->post('jenis');
      $cb_jenis_belanja = array("" => "");
      foreach ($this->m_jenis_belanja->get_jenis_belanja() as $row) {
          $cb_jenis_belanja[$row->id] = $row->id .". ". $row->nama;
      }
      $cmb = form_dropdown($nama, $cb_jenis_belanja, $jenis, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="'.$nama.'"');
      echo $cmb;
    }
    function edit_kategori_belanja(){
      $nama = $this->input->post('nama');
      $cb_jenis_belanja = $this->input->post('jenis');
      $kategori = $this->input->post('kategori');
      $cb_kategori_belanja = array("" => "");
      foreach ($this->m_kategori_belanja->get_kategori_belanja($cb_jenis_belanja) as $row) {
          $cb_kategori_belanja[$row->id] = $row->id .". ". $row->nama;
      }
      $cmb = form_dropdown($nama, $cb_kategori_belanja, $kategori, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="'.$nama.'"');
      echo $cmb;
    }
    function edit_sub_belanja(){
      $nama = $this->input->post('nama');
      $cb_jenis_belanja = $this->input->post('jenis');
      $cb_kategori_belanja = $this->input->post('kategori');
      $sub = $this->input->post('sub');
      $cb_subkategori_belanja = array("" => "");
      foreach ($this->m_subkategori_belanja->get_subkategori_belanja($cb_jenis_belanja, $cb_kategori_belanja) as $row) {
          $cb_subkategori_belanja[$row->id] = $row->id .". ". $row->nama;
      }
      $cmb = form_dropdown($nama, $cb_subkategori_belanja, $sub, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="'.$nama.'"');
      echo $cmb;
    }
    function edit_belanja_belanja(){
      $nama = $this->input->post('nama');
      $cb_jenis_belanja = $this->input->post('jenis');
      $cb_kategori_belanja = $this->input->post('kategori');
      $cb_subkategori_belanja = $this->input->post('sub');
      $belanja = $this->input->post('belanja');
      $cb_belanja = array("" => "");
      foreach ($this->m_kode_belanja->get_belanja($cb_jenis_belanja, $cb_kategori_belanja, $cb_subkategori_belanja) as $row) {
          $cb_belanja[$row->id] = $row->id .". ". $row->nama;
      }
      $cmb = form_dropdown($nama, $cb_belanja, $belanja, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="'.$nama.'"');
      echo $cmb;
    }
    function edit_sumber_dana(){
      $nama = $this->input->post('nama');
      $id = $this->input->post('id');
      $sumber_dana = array("" => "");
      foreach ($this->m_lov->get_all_sumber_dana() as $row) {
  			$sumber_dana[$row->id]=$row->sumber_dana;
  		}
      $cmb = form_dropdown($nama, $sumber_dana, $id, 'data-placeholder="Pilih Sumber Dana" class="common chosen-select" id="'.$nama.'"');
      echo $cmb;
    }
//------------------------TAHUN 1
    function cmb_jenis_belanja_1(){
      $cb_jenis_belanja = array("" => "");
      foreach ($this->m_jenis_belanja->get_jenis_belanja() as $row) {
          $cb_jenis_belanja[$row->id] = $row->id .". ". $row->nama;
      }
      $cmb = form_dropdown('cb_jenis_belanja_1', $cb_jenis_belanja, NULL, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_1"');
      echo $cmb;
    }
    function cmb_kategori_belanja_1(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_1');
          $cb_kategori_belanja = array("" => "");
          foreach ($this->m_kategori_belanja->get_kategori_belanja($cb_jenis_belanja) as $row) {
              $cb_kategori_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_kategori_belanja_1', $cb_kategori_belanja, NULL, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_1"');
          echo $cmb;
    }
    function cmb_subkategori_belanja_1(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_1');
          $cb_kategori_belanja = $this->input->post('cb_kategori_belanja_1');
          $cb_subkategori_belanja = array("" => "");
          foreach ($this->m_subkategori_belanja->get_subkategori_belanja($cb_jenis_belanja, $cb_kategori_belanja) as $row) {
              $cb_subkategori_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_subkategori_belanja_1', $cb_subkategori_belanja, NULL, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_1"');
          echo $cmb;
    }
    function cmb_belanja_1(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_1');
          $cb_kategori_belanja = $this->input->post('cb_kategori_belanja_1');
          $cb_subkategori_belanja = $this->input->post('cb_subkategori_belanja_1');
          $cb_belanja = array("" => "");
          foreach ($this->m_kode_belanja->get_belanja($cb_jenis_belanja, $cb_kategori_belanja, $cb_subkategori_belanja) as $row) {
              $cb_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_belanja_1', $cb_belanja, NULL, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_1"');
          echo $cmb;
    }

//------------------------TAHUN 2
    function cmb_jenis_belanja_2(){
      $cb_jenis_belanja = array("" => "");
      foreach ($this->m_jenis_belanja->get_jenis_belanja() as $row) {
          $cb_jenis_belanja[$row->id] = $row->id .". ". $row->nama;
      }
      $cmb = form_dropdown('cb_jenis_belanja_2', $cb_jenis_belanja, NULL, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_2"');
      echo $cmb;
    }
    function cmb_kategori_belanja_2(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_2');
          $cb_kategori_belanja = array("" => "");
          foreach ($this->m_kategori_belanja->get_kategori_belanja($cb_jenis_belanja) as $row) {
              $cb_kategori_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_kategori_belanja_2', $cb_kategori_belanja, NULL, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_2"');
          echo $cmb;
    }
    function cmb_subkategori_belanja_2(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_2');
          $cb_kategori_belanja = $this->input->post('cb_kategori_belanja_2');
          $cb_subkategori_belanja = array("" => "");
          foreach ($this->m_subkategori_belanja->get_subkategori_belanja($cb_jenis_belanja, $cb_kategori_belanja) as $row) {
              $cb_subkategori_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_subkategori_belanja_2', $cb_subkategori_belanja, NULL, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_2"');
          echo $cmb;
    }
    function cmb_belanja_2(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_2');
          $cb_kategori_belanja = $this->input->post('cb_kategori_belanja_2');
          $cb_subkategori_belanja = $this->input->post('cb_subkategori_belanja_2');
          $cb_belanja = array("" => "");
          foreach ($this->m_kode_belanja->get_belanja($cb_jenis_belanja, $cb_kategori_belanja, $cb_subkategori_belanja) as $row) {
              $cb_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_belanja_2', $cb_belanja, NULL, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_2"');
          echo $cmb;
    }

//------------------------TAHUN 3
    function cmb_jenis_belanja_3(){
      $cb_jenis_belanja = array("" => "");
      foreach ($this->m_jenis_belanja->get_jenis_belanja() as $row) {
          $cb_jenis_belanja[$row->id] = $row->id .". ". $row->nama;
      }
      $cmb = form_dropdown('cb_jenis_belanja_3', $cb_jenis_belanja, NULL, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_3"');
      echo $cmb;
    }
    function cmb_kategori_belanja_3(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_3');
          $cb_kategori_belanja = array("" => "");
          foreach ($this->m_kategori_belanja->get_kategori_belanja($cb_jenis_belanja) as $row) {
              $cb_kategori_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_kategori_belanja_3', $cb_kategori_belanja, NULL, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_3"');
          echo $cmb;
    }
    function cmb_subkategori_belanja_3(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_3');
          $cb_kategori_belanja = $this->input->post('cb_kategori_belanja_3');
          $cb_subkategori_belanja = array("" => "");
          foreach ($this->m_subkategori_belanja->get_subkategori_belanja($cb_jenis_belanja, $cb_kategori_belanja) as $row) {
              $cb_subkategori_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_subkategori_belanja_3', $cb_subkategori_belanja, NULL, 'data-placeholder="Pilih Sub Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_3"');
          echo $cmb;
    }
    function cmb_belanja_3(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_3');
          $cb_kategori_belanja = $this->input->post('cb_kategori_belanja_3');
          $cb_subkategori_belanja = $this->input->post('cb_subkategori_belanja_3');
          $cb_belanja = array("" => "");
          foreach ($this->m_kode_belanja->get_belanja($cb_jenis_belanja, $cb_kategori_belanja, $cb_subkategori_belanja) as $row) {
              $cb_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_belanja_3', $cb_belanja, NULL, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_3"');
          echo $cmb;
    }

//------------------------TAHUN 4
    function cmb_jenis_belanja_4(){
      $cb_jenis_belanja = array("" => "");
      foreach ($this->m_jenis_belanja->get_jenis_belanja() as $row) {
          $cb_jenis_belanja[$row->id] = $row->id .". ". $row->nama;
      }
      $cmb = form_dropdown('cb_jenis_belanja_4', $cb_jenis_belanja, NULL, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_4"');
      echo $cmb;
    }
    function cmb_kategori_belanja_4(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_4');
          $cb_kategori_belanja = array("" => "");
          foreach ($this->m_kategori_belanja->get_kategori_belanja($cb_jenis_belanja) as $row) {
              $cb_kategori_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_kategori_belanja_4', $cb_kategori_belanja, NULL, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_4"');
          echo $cmb;
    }
    function cmb_subkategori_belanja_4(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_4');
          $cb_kategori_belanja = $this->input->post('cb_kategori_belanja_4');
          $cb_subkategori_belanja = array("" => "");
          foreach ($this->m_subkategori_belanja->get_subkategori_belanja($cb_jenis_belanja, $cb_kategori_belanja) as $row) {
              $cb_subkategori_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_subkategori_belanja_4', $cb_subkategori_belanja, NULL, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_4"');
          echo $cmb;
    }
    function cmb_belanja_4(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_4');
          $cb_kategori_belanja = $this->input->post('cb_kategori_belanja_4');
          $cb_subkategori_belanja = $this->input->post('cb_subkategori_belanja_4');
          $cb_belanja = array("" => "");
          foreach ($this->m_kode_belanja->get_belanja($cb_jenis_belanja, $cb_kategori_belanja, $cb_subkategori_belanja) as $row) {
              $cb_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_belanja_4', $cb_belanja, NULL, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_4"');
          echo $cmb;
    }

//------------------------TAHUN 5
    function cmb_jenis_belanja_5(){
      $cb_jenis_belanja = array("" => "");
      foreach ($this->m_jenis_belanja->get_jenis_belanja() as $row) {
          $cb_jenis_belanja[$row->id] = $row->id .". ". $row->nama;
      }
      $cmb = form_dropdown('cb_jenis_belanja_5', $cb_jenis_belanja, NULL, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_5"');
      echo $cmb;
    }
    function cmb_kategori_belanja_5(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_5');
          $cb_kategori_belanja = array("" => "");
          foreach ($this->m_kategori_belanja->get_kategori_belanja($cb_jenis_belanja) as $row) {
              $cb_kategori_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_kategori_belanja_5', $cb_kategori_belanja, NULL, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_5"');
          echo $cmb;
    }
    function cmb_subkategori_belanja_5(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_5');
          $cb_kategori_belanja = $this->input->post('cb_kategori_belanja_5');
          $cb_subkategori_belanja = array("" => "");
          foreach ($this->m_subkategori_belanja->get_subkategori_belanja($cb_jenis_belanja, $cb_kategori_belanja) as $row) {
              $cb_subkategori_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_subkategori_belanja_5', $cb_subkategori_belanja, NULL, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_5"');
          echo $cmb;
    }
    function cmb_belanja_5(){
          $cb_jenis_belanja = $this->input->post('cb_jenis_belanja_5');
          $cb_kategori_belanja = $this->input->post('cb_kategori_belanja_5');
          $cb_subkategori_belanja = $this->input->post('cb_subkategori_belanja_5');
          $cb_belanja = array("" => "");
          foreach ($this->m_kode_belanja->get_belanja($cb_jenis_belanja, $cb_kategori_belanja, $cb_subkategori_belanja) as $row) {
              $cb_belanja[$row->id] = $row->id .". ". $row->nama;
          }
          $cmb = form_dropdown('cb_belanja_5', $cb_belanja, NULL, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_5"');
          echo $cmb;
    }





    function autocomplete_kdjenisbelanja(){
        $req = $this->input->post('term');
        $result = $this->m_jenis_belanja->get_value_autocomplete_kd_jenis_belanja($req);
        echo json_encode($result);
    }

    function autocomplete_kdkategoribelanja(){
        $kd_jenis_belanja = $this->input->post('kd_jenis_belanja');
        $req = $this->input->post('term');
        $result = $this->m_kategori_belanja->get_value_autocomplete_kd_kategori_belanja($req, $kd_jenis_belanja);
        echo json_encode($result);
    }
    function autocomplete_kdsubkategoribelanja(){
        $kd_jenis_belanja = $this->input->post('kd_jenis_belanja');
        $kd_kategori_belanja = $this->input->post('kd_kategori_belanja');
        $req = $this->input->post('term');
        $result = $this->m_subkategori_belanja->get_value_autocomplete_kd_subkategori_belanja($req, $kd_jenis_belanja, $kd_kategori_belanja);
        echo json_encode($result);
    }
    function autocomplete_kdkodebelanja(){
        $kd_jenis_belanja  = $this->input->post('kd_jenis_belanja');
        $kd_kategori_belanja  = $this->input->post('kd_kategori_belanja');
        $kd_subkategori_belanja    = $this->input->post('kd_subkategori_belanja');
        $req = $this->input->post('term');
        $result = $this->m_kode_belanja->get_value_autocomplete_kd_belanja($req, $kd_jenis_belanja, $kd_kategori_belanja, $kd_subkategori_belanja);
        echo json_encode($result);
    }


    function autocomplete_kdurusan(){
        $req = $this->input->post('term');
        //$req2 = $this->input->post('kode_urusan_autocomplete');

        $result = $this->m_urusan->get_value_autocomplete_kd_urusan($req);
        echo json_encode($result);
    }

    function autocomplete_kdbidang(){
        $kd_urusan = $this->input->post('kd_urusan');
        $req = $this->input->post('term');
        $result = $this->m_bidang->get_value_autocomplete_kd_bidang($req, $kd_urusan);
        echo json_encode($result);
    }

    function autocomplete_kdprog(){
        $kd_urusan = $this->input->post('kd_urusan');
        $kd_bidang = $this->input->post('kd_bidang');
        $req = $this->input->post('term');
        $result = $this->m_program->get_value_autocomplete_kd_prog($req, $kd_urusan, $kd_bidang);
        echo json_encode($result);
    }

    function autocomplete_keg(){
        $kd_urusan  = $this->input->post('kd_urusan');
        $kd_bidang  = $this->input->post('kd_bidang');
        $kd_prog    = $this->input->post('kd_prog');
        $req = $this->input->post('term');
        $result = $this->m_kegiatan->get_value_autocomplete_kd_keg($req, $kd_urusan, $kd_bidang, $kd_prog);
        echo json_encode($result);
    }

    function autocomplete_skpd(){
        $req = $this->input->post('term');
        $result = $this->m_skpd->get_skpd_autocomplete($req);
        echo json_encode($result);
    }

    function autocomplete_kec(){
        $req = $this->input->post('term');
        $result = $this->m_kecamatan->get_kec_autocomplete($req);
        echo json_encode($result);
    }

    function autocomplete_desa(){
        $req = $this->input->post('term');
        $id_kec = $this->input->post('id_kec');
        $result = $this->m_desa->get_desa_autocomplete($req, $id_kec);

        echo json_encode($result);
    }

	function autocomplete_groups(){
        $req = $this->input->post('term');
        $result = $this->m_groups->get_groups_autocomplete($req);
        echo json_encode($result);
    }

    function cmb_desa(){
      $id_kec = $this->input->post('id_kec');
      $id_desa = array("" => "");
      foreach ($this->m_desa->get_desa_dee($id_kec) as $row) {
  				$id_desa[$row->id] = $row->label;
  		}
      $cmb = form_dropdown('id_desa', $id_desa, NULL, 'data-placeholder="Pilih Desa Sasaran" class="common chosen-select" id="id_desa_dee"');
      echo $cmb;
  	}

    function cmb_bulan(){
        $id_bulan = array("" => "");
        foreach ($this->m_bulan->get_bulan() as $row) {
            $id_bulan[$row->id] = $row->id .". ". $row->nama;
        }
        $cmb = form_dropdown('id_bulan', $id_bulan, NULL, 'data-placeholder="Pilih Bulan" class="common chosen-select" id="id_bulan"');
        echo $cmb;
    }

    function cmb_skpd(){
        $id_skpd = array("" => "");
        foreach ($this->m_skpd->get_skpd_chosen() as $row) {
            $id_skpd[$row->id] = $row->id .". ". $row->label;
        }
        $cmb = form_dropdown('id_skpd', $id_skpd, NULL,'data-placeholder="Pilih SKPD" class="common chosen-select" id="id_skpd"');
        echo $cmb;
    }

    function cmb_urusan(){
        $kd_urusan = array("" => "");
        foreach ($this->m_urusan->get_urusan() as $row) {
            $kd_urusan[$row->id] = $row->id .". ". $row->nama;
        }
        $cmb = form_dropdown('kd_urusan', $kd_urusan, NULL, 'data-placeholder="Pilih Kode Urusan" class="common chosen-select" id="kd_urusan"');
        echo $cmb;
    }

	function cmb_bidang(){
        $kd_urusan = $this->input->post('kd_urusan');
        $kd_bidang = array("" => "");
        foreach ($this->m_bidang->get_bidang($kd_urusan) as $row) {
            $kd_bidang[$row->id] = $row->id .". ". $row->nama;
        }
        $cmb = form_dropdown('kd_bidang', $kd_bidang, NULL, 'data-placeholder="Pilih Kode Bidang" class="common chosen-select" id="kd_bidang"');
        echo $cmb;
    }

    function cmb_program(){
        $kd_urusan = $this->input->post('kd_urusan');
        $kd_bidang = $this->input->post('kd_bidang');
        $modul = $this->input->post('modul');
        $id_modul = $this->input->post('id_modul');

        $kd_program = array("" => "");
        foreach ($this->m_program->get_prog($kd_urusan, $kd_bidang, $modul, $id_modul) as $row) {
            $kd_program[$row->id] = $row->id .". ". $row->nama;
        }
        $cmb = form_dropdown('kd_program', $kd_program, NULL, 'data-placeholder="Pilih Kode Program" class="common chosen-select" id="kd_program"');
        echo $cmb;
    }

    function cmb_kegiatan(){
        $kd_urusan = $this->input->post('kd_urusan');
        $kd_bidang = $this->input->post('kd_bidang');
        $kd_program = $this->input->post('kd_program');

        $kd_kegiatan = array("" => "");
        foreach ($this->m_kegiatan->get_keg($kd_urusan, $kd_bidang, $kd_program) as $row) {
            $kd_kegiatan[$row->id] = $row->id .". ". $row->nama;
        }
        $cmb = form_dropdown('kd_kegiatan', $kd_kegiatan, NULL, 'data-placeholder="Pilih Kode Kegiatan" class="common chosen-select" id="kd_kegiatan"');
        echo $cmb;
    }

	function cmb_groups(){
		$id_groups = $this->input->post('id_groups');
		$id_groups = array("" => "");
        foreach ($this->m_groups->get_group($id_groups) as $row) {
            $id_groups[$row->id] = $row->id .". ". $row->nama;
        }
        $cmb = form_dropdown('id_groups', $id_groups, NULL, 'data-placeholder="Pilih Jenis Grup" class="common chosen-select" id="id_groups"');
        echo $cmb;

	}

  function autocomplete_jenispendapatan(){
      $req = $this->input->post('term');
      $result = $this->m_jenis_pendapatan->get_value_autocomplete_jenis_pendapatan($req);
      echo json_encode($result);
  }
  function autocomplete_asalpendapatan(){
      $kd_jenis = $this->input->post('kd_jenis');
      $req = $this->input->post('term');
      $result = $this->m_asal_pendapatan->get_value_autocomplete_asal_pendapatan($req, $kd_jenis);
      echo json_encode($result);
  }

  function multi_skpd_bidang(){
    $kd_urusan = $this->input->post('kd_urusan');
    $kd_bidang = $this->input->post('kd_bidang');

    $skpd_bidang = "";
		foreach ($this->m_rpjmd_trx->skpd_bidang($kd_urusan, $kd_bidang) as $row) {
			$skpd_bidang .= "<input type='checkbox' name='skpd_bidang[]' value='".$row->id_skpd."' checked>".$row->nama_skpd."<br>";
		}
    echo $skpd_bidang;
  }
}

?>
