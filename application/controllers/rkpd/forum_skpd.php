<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum_skpd extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance(); 
        parent::__construct();
        $this->load->helper(array('form','url', 'text_helper','date'));        
        $this->load->database();
        $this->load->model('m_rkpd','',TRUE);
        $this->load->model('m_lov','',TRUE);
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

    function index()
	{
		$this->auth->restrict();
	
        $data['url_edit_data'] = site_url('rkpd/forum_skpd/edit_data');
        $data['url_load_data'] = site_url('rkpd/forum_skpd/load_data');
        $data['url_save_data'] = site_url('rkpd/forum_skpd/save_data');
        
		$this->template->load('template','rkpd/forum_skpd',$data);
	}
    
    function save_data(){
        $date=date("Y-m-d");
        $time=date("H:i:s");
        $this->auth->restrict();
        //action save cekbank di table t_rkpd
        $id_rkpd 	      = $this->input->post('id_rkpd');
        $id_status_rkpd   = $this->input->post('status');
        $call_from		  = $this->input->post('call_from');
        
        
        if(strpos($call_from, 'rkpd/forum_rkpd/edit_data') != FALSE) {
			$call_from = '';
		}
        
        //check data pada t_musrenbang
        //status belum terpakai di rkpd = 0
        
        $data_ = array(
            'id_rkpd'     => $id_rkpd,
        );
        
        $data_rkpd = $this->m_rkpd->get_data($data_,'table_rkpd');
        
        if($data_rkpd){
            //data rkpd ada
            //update id status
            
            //set status menjadi 1
            
            $flag = TRUE;
            //$id_history_rkpd = TRUE;
            
            //start transaction
            $this->db->trans_start();
            
            //ubah status rkpd
            
            $flag = $this->m_rkpd->update($id_rkpd,array('id_status_verifikasi'=>$id_status_rkpd),'table_rkpd','primary_rkpd');
            
            $status = $this->m_rkpd->get_data(array('id_status_rkpd'=>$id_status_rkpd),'table_status_rkpd');
            
            //insert status ke t_history_rkpd
            $data_history_rkpd = array(
                'id_rkpd'       => $id_rkpd,
                'keterangan'    => $status->status_rkpd,
                'created_by'    => $this->session->userdata('id_user'),
                'created_date'  => $date." ".$time                
            );
            
            $id_history_rkpd = $this->m_rkpd->insert($data_history_rkpd,'table_history_rkpd');
            //update ke t_rkpd
            $data__ = array(
                'id_history_rkpd'   => $id_history_rkpd
            );
            $this->m_rkpd->update($id_rkpd,$data__,'table_rkpd','primary_rkpd');
            
            //cek ke tabel renja, ada atau tidak bedasarkan tahun,kd_urusan,kd_bidang,kd_program,kd_kegiatan
            //kalau tidak ada, insert ke sana
            
            $this->m_rkpd->sync_to_renja($id_rkpd);
            $this->db->trans_complete();
            
            
            //suksess kah?
            
            if($flag===FALSE){
                $this->session->set_userdata('msg_typ','err');
                $this->session->set_userdata('msg', 'Perubahan Status Usulan RKPD Gagal!');
            }else{  
                $this->session->set_userdata('msg_typ','ok');
                $this->session->set_userdata('msg', 'Perubahan Status Usulan RKPD Berhasil!');
            }
            
        }else{
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data RKPD tidak ada!');
        }

		if(!empty($call_from))
			redirect($call_from);

        redirect('rkpd/forum_skpd');
        
		//var_dump($cekbank);
		//print_r ($id_cek);
    }
    
    function load_data(){
        
        $search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$rkpd = $this->m_rkpd->get_data_table_rkpd($search, $start, $length, $order["0"]);		
		$alldata = $this->m_rkpd->count_data_table_rkpd($search, $start, $length, $order["0"]);		
		
		$data = array();
		$no=0;
		foreach ($rkpd as $row) {
			$no++;
			$data[] = array(
							$no,
                            $row->kode_kegiatan,
                            $row->nama_program_kegiatan,
                            $row->lokasi,
                            $row->nama_skpd,
                            $row->prioritas,
                            $row->status_rkpd,
							'<a href="javascript:void(0)" onclick="ubah_usulan_rkpd('. $row->id_rkpd .')" class="icon2-page_white_edit" title="Ubah status usulan RKPD"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
    }
    
    function edit_data($id_rkpd=NULL){
        
        $this->auth->restrict();
        $data['url_save_data'] = site_url('rkpd/forum_skpd/save_data');

		$data['isEdit'] = FALSE;
        if (!empty($id_rkpd)) {
            $data_ = array('id_rkpd'=>$id_rkpd);
            
            $result = $this->m_rkpd->get_data_rkpd_detail($id_rkpd);
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data musrenbang tidak ditemukan.');
				redirect('rkpd/forum_skpd');
			}
			
            
            $data['id_rkpd']		= $result->id_rkpd;
            $data['id_renstra']         = $result->id_renstra;
            $data['id_skpd']         = $result->id_skpd;
            $data['id_bid_koor']         = $result->id_bid_koor;
            $data['tahun'] = $result->tahun;
    		
            $data['urusan'] = $result->kd_urusan;
    		$data['bidang'] = $result->kd_bidang;
    		$data['program'] = $result->kd_program;
    		$data['kegiatan'] = $result->kd_kegiatan;
    		
    		$data['nm_urusan'] = $result->nm_urusan;
    		$data['nm_bidang'] = $result->nm_bidang;
    		$data['nm_program'] = $result->ket_program;
    		$data['nm_kegiatan'] = $result->ket_kegiatan;
            
            $data['jenis_pekerjaan'] = $result->jenis_pekerjaan;
            $data['jumlah_dana'] = $result->jumlah_dana;
            $data['lokasi'] = $result->lokasi;
            $data['nama_skpd']         = $result->nama_skpd;
            $data['nama_bid_koor']         = $result->nama_bid_koor;
            
            $data['combo_status']   = $this->m_lov->create_lov('table_status_rkpd','id_status_rkpd','status_rkpd',$result->id_status_verifikasi);
            
            $data['isEdit']				= TRUE;
            
            $data['combo_prioritas']    = $this->m_lov->create_lov('table_prioritas','id_prioritas','nama',$result->id_prioritas);
            $data['combo_sumberdana']   = $this->m_lov->create_lov('table_sumberdana','id_sumberdana','nama',$result->id_sumberdana);
            
            
            //preparation table
            
            $list_renja = $this->m_rkpd->get_from_renja($result->tahun,$result->kd_urusan,$result->kd_bidang,$result->kd_program,$result->kd_kegiatan);
            $output_renja = '';
            $no = 1;
            foreach($list_renja as $l){
                $output_renja .= '<tr><td >';
                $output_renja .= $no++;
                $output_renja .= '</td><td>';
                $output_renja .= $l->kode_kegiatan;
                $output_renja .= '</td><td>';
                $output_renja .= $l->nama_kegiatan;
                $output_renja .= '</td><td>';
                $output_renja .= $l->indikator_kinerja;
                $output_renja .= '</td><td>';
                $output_renja .= $l->catatan;
                $output_renja .= '</td><td>';
                $output_renja .= $l->nominal;
                $output_renja .= '</td><tr>';
            }
            $data['list_renja'] = $output_renja;
		}
        //var_dump($data);
  
    	$this->template->load('template','rkpd/edit_usulan_rkpd', $data);
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

}