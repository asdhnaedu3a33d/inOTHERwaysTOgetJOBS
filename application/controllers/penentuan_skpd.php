<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penentuan_skpd extends CI_Controller
{
    var $CI = NULL;

    public function __construct(){
        $this->CI =& get_instance();
        parent::__construct();

        $this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        $this->load->model('m_musrenbang','',TRUE);
        $this->load->model('m_lov','',TRUE);
        $this->load->model('m_skpd','',TRUE);
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }

    }

    function index(){
        $this->auth->restrict();

        $data['url_add_data'] = site_url('penentuan_skpd/edit_data');
        $data['url_load_data'] = site_url('penentuan_skpd/load_data');
        $data['url_delete_data'] = site_url('penentuan_skpd/delete_data');
        $data['url_edit_data'] = site_url('penentuan_skpd/edit_data');
        $data['url_save_data'] = site_url('penentuan_skpd/save_data');
        $data['url_show_gallery'] = site_url('penentuan_skpd/show_gallery');

        $data['url_summary_biaya'] = site_url('penentuan_skpd/get_summary_biaya');

        $this->template->load('template','penentuan_skpd/penentuan_skpd',$data);
	}

    function save_data(){
        $date=date("Y-m-d");
        $time=date("H:i:s");
        $this->auth->restrict();
        //action save cekbank di table t_cmusrenbangdes
        $id_musrenbang 	= $this->input->post('id_musrenbang');
        $call_from			= $this->input->post('call_from');
        $data_post = array(
            'tahun'             => $this->session->userdata('t_anggaran_aktif'),
            'jenis_pekerjaan'	=> $this->input->post('jenis_pekerjaan'),
            'volume'			=> $this->input->post('volume'),
            'lokasi'			=> $this->input->post('lokasi'),
            'satuan'			=> $this->input->post('satuan'),
            'jumlah_dana'		=> $this->input->post('jumlah_dana'),
            'id_skpd'			=> $this->input->post('id_skpd'),
            'id_kecamatan' => $this->input->post('id_kecamatan')=='' ? $this->session->userdata('id_kecamatan') : $this->input->post('id_kecamatan'),
            'id_asal_usulan' => $this->input->post('id_asal_usulan')==''? '2' : $this->input->post('id_asal_usulan'),
            'id_status_usulan' => '2'

        );

        if(strpos($call_from, 'musrenbangdes/edit_data') != FALSE) {
            $call_from = '';
        }

        $cekmusrenbang = $this->m_musrenbang->get_data(array('id_musrenbang'=>$id_musrenbang),'table_musrenbang');
            if($cekmusrenbang === empty($cekmusrenbang)) {
            $cekmusrenbang = new stdClass();
            $id_musrenbang = '';
        }

        $ret = TRUE;
        if(empty($id_musrenbang)) {
            //insert
            $data_post['created_by'] = $this->session->userdata('id_user');
            $data_post['created_date'] = $date." ".$time;
            $ret = $this->m_musrenbang->insert($data_post,'table_musrenbang');
            //echo $this->db->last_query();
        } else {
            //update
            $data_post['changed_by'] = $this->session->userdata('id_user');
            $data_post['changed_date'] = $date." ".$time;
            $ret = $this->m_musrenbang->update($id_musrenbang,$data_post,'table_musrenbang','primary_musrenbang');
            echo $this->db->last_query();
        }
        if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data musrenbang Gagal disimpan');
        } else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data musrenbang Berhasil disimpan');
        }

        if(!empty($call_from))
            redirect($call_from);
        redirect('penentuan_skpd');
        //var_dump($cekbank);
        //print_r ($id_cek);
    }

    function load_data(){
        $search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$renstra = $this->m_musrenbang->get_data_table_penentuan_skpd($search, $start, $length, $order["0"]);
		$alldata = $this->m_musrenbang->count_data_table_penentuan_skpd($search, $start, $length, $order["0"]);

		$data = array();
		$no=0;
		foreach ($renstra as $row) {
            $no++;
            $data[] = array(
                $no,
                $row->jenis_pekerjaan,
                $row->volume,
                $row->satuan,
                $row->jumlah_dana,
                $row->nama_desa,
                $row->nama_skpd,
                '<a href="javascript:void(0)" onclick="edit_penentuan_skpd('. $row->id_musrenbang .')" class="icon2-page_white_edit" title="Edit Penentuan SKPD"/>
                <a href="javascript:void(0)" onclick="delete_penentuan_skpd('. $row->id_musrenbang .')" class="icon2-delete" title="Hapus Data Musrenbang"/>
                <a href="javascript:void(0)" onclick="show_gallery('. $row->id_musrenbang .')" class="icon-search" title="Lihat Gambar"/>'
            );
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
    }

    function edit_data($id_musrenbang=NULL){
        $this->auth->restrict();

        $data['url_save_data'] = site_url('penentuan_skpd/save_data');

        $data['isEdit'] = FALSE;
        $id_skpd_edit = NULL;
        // $data['combo_skpd']         = $this->m_musrenbang->create_lov_skpd('');
        if (!empty($id_musrenbang)) {
            $data_ = array('id_musrenbang'=>$id_musrenbang);
            $result = $this->m_musrenbang->get_data_with_rincian($id_musrenbang,'table_musrenbang');
						if (empty($result)) {
							$this->session->set_userdata('msg_typ','err');
							$this->session->set_userdata('msg', 'Data musrenbang tidak ditemukan.');
							redirect('penentuan_skpd');
						}

            $data['id_musrenbang']		= $result->id_musrenbang;

            $data['jenis_pekerjaan']	= $result->jenis_pekerjaan;
            $data['lokasi']				= $result->lokasi;
            $data['volume']				= $result->volume;
            $data['satuan']				= $result->satuan;
            $data['jumlah_dana']		= $result->jumlah_dana;
            $data['id_asal_usulan']= $result->id_asal_usulan;
            $data['id_kecamatan'] = $result->id_kecamatan;
            $data['isEdit']				= TRUE;
            $id_skpd_edit = $result->id_skpd;
            //$data['combo_skpd']         = $this->m_musrenbang->create_lov_skpd($result->id_skpd);

        }

        $id_skpd = array("" => "");
        foreach ($this->m_skpd->get_skpd_chosen() as $row) {
            $id_skpd[$row->id] = $row->id .". ". $row->label;
        }
        $data['id_skpd'] = form_dropdown('id_skpd', $id_skpd, $id_skpd_edit, 'data-placeholder="Pilih SKPD" class="common chosen-select" id="id_skpd"');
        //var_dump($data['id_skpd']);


    	$this->template->load('template','penentuan_skpd/penentuan_skpd_view', $data);
    }

    function delete_data() {
    	if(!$this->auth->restrict_ajax_login()) return;
        $date=date("Y-m-d");
        $time=date("H:i:s");
		//$idu = $this->session->userdata('id_unit');
		//$idsu  = $this->session->userdata('id_subunit');
		//$ta  = $this->m_settings->get_tahun_anggaran();
		//$cdsu= $this->session->userdata('kode_subunit');

		$id_musrenbang = $this->input->post('id_musrenbang');

		//cek apakah musrembang itu ada

		$musrenbang = $this->m_musrenbang->get_data(array('id_musrenbang'=>$id_musrenbang),'table_musrenbang');

		if(empty($musrenbang)) {
			$this->session->set_userdata('msg_typ','err');
    	$this->session->set_userdata('msg', 'Musrembang yang dipilih tidak ada');
			redirect('musrenbangdes');
		}

		//hapus musrembangdes
        $data_ = array(
            'flag_delete' => '1',
            'changed_date' => $date." ".$time,
            'changed_by' => $this->session->userdata('id_user')
        );
		$result = $this->m_musrenbang->delete($id_musrenbang,$data_,'table_musrenbang','primary_musrenbang');
		if($result) {
			$response['errno'] = 0;
			$response['message'] = 'Musrembang berhasil dihapus';
		} else {
			$response['errno'] = 1;
			$response['message'] = 'Musrembang gagal dihapus';
		}

		echo json_encode($response);
    }

    function autocomplete_kdurusan(){
    	$req = $this->input->post('term');
    	$result = $this->m_musrenbang->get_value_autocomplete_kd_urusan($req);
    	echo json_encode($result);
    }

    function autocomplete_kdbidang(){
    	$kd_urusan = $this->input->post('kd_urusan');
    	$req = $this->input->post('term');
    	$result = $this->m_musrenbang->get_value_autocomplete_kd_bidang($req, $kd_urusan);
    	echo json_encode($result);
    }

    function autocomplete_kdprog(){
    	$kd_urusan = $this->input->post('kd_urusan');
    	$kd_bidang = $this->input->post('kd_bidang');
    	$req = $this->input->post('term');
    	$result = $this->m_musrenbang->get_value_autocomplete_kd_prog($req, $kd_urusan, $kd_bidang);
    	echo json_encode($result);
    }

    function autocomplete_keg(){
    	$kd_urusan 	= $this->input->post('kd_urusan');
    	$kd_bidang 	= $this->input->post('kd_bidang');
    	$kd_prog 	= $this->input->post('kd_prog');
    	$req = $this->input->post('term');
    	$result = $this->m_musrenbang->get_value_autocomplete_kd_keg($req, $kd_urusan, $kd_bidang, $kd_prog);
    	echo json_encode($result);
    }

    function formatRupiah($rupiah)
    {
        return "Rp".number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $rupiah)),2);
    }

    function show_gallery(){
        $id = $this->input->post('id_musrenbang');
        $result = $this->db->query("SELECT file FROM t_musrenbang WHERE id_musrenbang=?", array($id));
        $id_photo = $result->row();

        $this->db->where_in("id", explode(',',$id_photo->file));
        $this->db->from("t_upload_file");
        $result = $this->db->get();
        $result = $result->result();
        //print_r($result);
        $arr = array();
        $i=0;
        foreach($result as $results){
            $arr[$i]['href'] = base_url().$results->location;
            $arr[$i]['title'] = $results->name;
            $i++;
        }
        //print_r($arr);
        /*$arr = array();
        $arr[0]['href'] = '1_b.jpg';
        $arr[1]['href'] = '2_b.jpg';
        $arr[2]['href'] = '3_b.jpg';
        */
        echo json_encode($arr);
    }

    function get_summary_biaya(){
		$arr = array(
			'total_biaya' => $this->m_musrenbang->get_summary_biaya_penentuan_skpd()
		);

		echo json_encode($arr);
	}

}
