<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class File_publik extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        //$this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
				$this->load->library('upload');
        $this->load->model('m_file_publik','',TRUE);
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		$this->auth->restrict();
		$data['url_delete_data'] = site_url('file_publik/delete_file_publik');
		$this->template->load('template','file_publik/file_publik_view',$data);
	}

	function load_file_publik()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$data_file = $this->m_file_publik->get_data_table($search, $start, $length, $order["0"]);
		$alldata = $this->m_file_publik->count_data_table($search, $start, $length, $order["0"]);

		$data = array();
		$no=0;


		foreach ($data_file as $row) {
			$no++;
			$data[] = array(
							$no,
							$row->title,
                            $row->keterangan,
                            $row->nama_file,
							'<a href="javascript:void(0)" onclick="edit_file_publik('. $row->id .')" class="icon2-page_white_edit" title="Edit File Publik"/>
							<a href="javascript:void(0)" onclick="delete_file_publik('. $row->id .')" class="icon2-delete" title="Hapus File Publik"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);

        echo json_encode($json);
	}

	function cru_file_publik()
	{
		$this->template->load('template','file_publik/cru_file_publik');
	}

	function save_file_publik(){
			$this->auth->restrict();

			$id 		 	= $this->input->post('id');
			$call_from		= $this->input->post('call_from');
			$title			= $this->input->post('title');
			$keterangan 	= $this->input->post('keterangan');
			//$nama_file 		= $this->input->post('nama_file');
			$user_cru 		= $this->session->userdata("nama");
			$date_cru 		= date("d-m-Y");

			if(strpos($call_from, 'file_publik/cru_file_publik') != FALSE) {
					$call_from = '';
			}
			//cek apakah cekbank tsb ada
			$data_file_publik = $this->m_file_publik->get_file_publik_by_id($id);
			if(empty($data_file_publik)) {
					//cek bank baru
					$data_file_publik = new stdClass();
					$id = '';
			}

			//all
			$data_file_publik->id			= $id;
			$data_file_publik->title		= $title;
			$data_file_publik->keterangan	= $keterangan;
			//$data_file_publik->nama_file	= $nama_file;
			$data_file_publik->user_cru 	= $user_cru;
			$data_file_publik->date_cru		= $date_cru;

			$ret = TRUE;
			if(empty($id)) {
					//insert
					$id_return = $this->m_file_publik->simpan_file_publik($data_file_publik);
					$this->do_upload($id_return);
					//echo $this->db->last_query();
			} else {
					//update
					$ret = $this->m_file_publik->update_file_publik($data_file_publik, $id,'table_file_publik', 'primary_file_publik');
					$this->do_upload($id);
					//echo $this->db->last_query();
			}
			if ($ret === FALSE){
				  $this->session->set_userdata('msg_typ','err');
				  $this->session->set_userdata('msg', 'Data File Publik gagal disimpan');
			} else {
				  $this->session->set_userdata('msg_typ','ok');
				  $this->session->set_userdata('msg', 'Data File Publik berhasil disimpan');
			}

			if(!empty($call_from))
					redirect($call_from);

			redirect('file_publik');


    }

    function edit_file_publik($id = NULL)
	{
        $this->auth->restrict();
        $data['url_save_data'] = site_url('file_publik/save_file_publik');

        $data['isEdit'] = FALSE;
        if (!empty($id)) {
            $data_ = array('id'=>$id);
            $result = $this->m_file_publik->get_data_with_rincian($id,'table_file_publik');
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data File Publik tidak ditemukan.');
				redirect('file_publik');
			}

            $data['id']					= $result->id;
            $data['title']				= $result->title;
            $data['keterangan']			= $result->keterangan;
            $data['nama_file']			= $result->nama_file;
            $data['isEdit']				= TRUE;

		}
        $this->template->load('template','file_publik/cru_file_publik', $data);

	}

	function delete_file_publik()
	{
    $id = $this->input->post('id');

    $result = $this->m_file_publik->delete_file_publik($id);
    if ($result) {
			$msg = array('success' => '1', 'msg' => 'File Publik berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! File Publik gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function do_upload($id){
		$status = "";
		$msg = "";
		$file_upload = "";
		$file_element_name = 'file_publik';
		$kategori = "file_publik";
		$upload_file_path = "";
		if ($status != "error"){
				if(!is_dir(getcwd().'/file_upload/'.$kategori.'_dir')){
						mkdir(getcwd().'/file_upload/'.$kategori.'_dir');
				}
				$config_['upload_path'] = getcwd().'/file_upload/'.$kategori.'_dir';
				$config_['allowed_types'] = 'jpg|png|pdf|doc|docx|xls|xlsx';
				$config_['encrypt_name'] = false;

				$this->upload->initialize($config_);

				if (!$this->upload->do_upload($file_element_name)){
						$status = 'error';
						$msg = $this->upload->display_errors('', '');
				}
				else{
						$data = $this->upload->data();
						$file_path = $data['full_path'];
						$file_upload = $data['file_name'];

						if(file_exists($file_path)){
								$status = "success";
								$msg = "File successfully uploaded";
								$upload_file_path = 'uploads/'.$kategori.'_dir/'.$file_upload;

								//remove file sebelumnya
								//digunakan saat edit data_identitas
								/*
								if ($data_identitas->$kategori!='') {
										$arr_file_path = explode('/', $data_identitas->$kategori);
										if (file_exists(getcwd().'/uploads/'.$kategori.'_dir'.'/'.$arr_file_path[2])) {
												unlink(getcwd().'/uploads/'.$kategori.'_dir'.'/'.$arr_file_path[2]);
										}
								}
								*/

								//update file di database
								$data_post['nama_file'] = 'file_upload/'.$kategori.'_dir'.'/'.$file_upload;
								$result = $this->m_file_publik->update_file_publik($data_post, $id,'table_file_publik', 'primary_file_publik');
								//$result = $this->bkd_model->update($data_identitas->id_data_dosen,$data_post,"table_data_dosen","primary_data_dosen");

						}
						else{
								$status = "error";
								$msg = "Something went wrong when saving the file, please try again.";
						}
				}
				@unlink($_FILES[$file_element_name]);
		}
		//echo json_encode(array('status' => $status, 'msg' => $msg, 'img' => $file_upload));
		echo $upload_file_path;
	}
}
