<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');
		$this->load->model('m_users');
		$this->load->library('grocery_CRUD');
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function _example_output($output = null,$judul=NULL)
	{
		//$this->load->view('example',$output);
		$output->judul=$judul;
		$this->template->load('template_grocery','master',$output);
	}

	function index()
	{
		redirect('home');
	}

	function check_password($post_array, $primary_key = null) {
			$id_usr = $this->session->userdata('id_user');
			if ($primary_key != $id_usr) {
				echo "<textarea>".json_encode(array('success' => false ))."</textarea>";
				$crud->set_echo_and_die();
			}

	        if(!empty($post_array['password'])){
	        	if ($post_array['password'] != $post_array['password2']) {
	        		//gagal update, password tidak sama
					echo "<textarea>".json_encode(array('success' => true , 'success_message' => '<b style="color: red">FAILED! Password yang di masukan berbeda.</b>'))."</textarea>";
					$crud->set_echo_and_die();
	        	}
	        	$data_user = $this->m_users->get_user_by_id($primary_key);

	        	if (md5($post_array['password_old']) != $data_user->password) {
	        		//gagal update, password tidak sama dengan password lama
	        		echo "<textarea>".json_encode(array('success' => true , 'success_message' => '<b style="color: red">FAILED! Old Password yang di masukan salah.</b>'))."</textarea>";
					$crud->set_echo_and_die();
	        	}

	            $post_array['password']=  md5($post_array['password']);
	            unset($post_array['password2']);
	            unset($post_array['password_old']);
	        }else{
	            //kalo passwordnya dikosongin, jangan lakukan update data pada field ini
	            unset($post_array['password']);
	            unset($post_array['password2']);
	            unset($post_array['password_old']);
	        }
	        return $post_array;
	}


	function edit_profile()
	{
		echo ($this->session->userdata('id_user'));
		try{
			$halo = $this->input->post();
			if ($halo){
				if ($this->grocery_crud->getStateInfo()->primary_key != $this->session->userdata('id_user')) {
					redirect("home");
				}
			}

			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('m_users');
			$crud->set_subject('User');
			$crud->fields('user_nama','user_email','user_phone','user_phone', 'password_old', 'password', 'password2');
			$crud->change_field_type('password_old', 'password');
			$crud->change_field_type('password', 'password');
			$crud->change_field_type('password2', 'password');
			$crud->display_as('password_old','Password Lama');
			$crud->display_as('password1','Ubah Password');
			$crud->display_as('password2','Re-type Password');
			$crud->unset_back_to_list();
			$crud->unset_add();
    		$crud->unset_delete();
    		$crud->unset_list();

    		$crud->getModel()->set_default_value("password", "");
    		$crud->getModel()->set_default_value("password2", "");
    		$crud->getModel()->set_default_value("password_old", "");

    		$crud->callback_before_update(array($this,'check_password'));

			$output = $crud->render();

			$this->template->load('template','edit_profile',$output);
		}catch(Exception $e){
			$id_user = $this->session->userdata('id_user');
			redirect('users/edit_profile/edit/'.$id_user);
		}
	}

	function edit_skpd(){
		$id_skpd = $this->uri->segment(4);
		$idu = $this->session->userdata('id_skpd');

		if ($id_skpd != $idu) {
			$this->session->set_userdata('msg_typ','err');
      		$this->session->set_userdata('msg', 'Pengguna Tidak Memiliki Hak Akses Ke Halaman Ini');
			redirect('home');
		}

		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_skpd');
			$crud->set_subject('SKPD');
			$crud->fields('id_bidkoor','kode_skpd','nama_skpd','kodepos_skpd','alamat','telp_skpd','fax','kaskpd_nama','kaskpd_nip','nama_jabatan','pangkat_golongan');
			$crud->set_relation('id_bidkoor','m_bidkoordinasi','nama_koor');
			$crud->display_as('kode_skpd','Kode SKPD');
			$crud->display_as('nama_skpd','Nama SKPD');
            $crud->display_as('kodepos_skpd','Kode Pos SKPD');
            $crud->display_as('alamat','Alamat SKPD');
            $crud->display_as('telp_skpd','Telpon SKPD');
            $crud->display_as('fax','Fax');
            $crud->display_as('kaskpd_nama','Nama Kepala SKPD');
            $crud->display_as('kaskpd_nip','NIP Kepala SKPD');
            $crud->display_as('nama_jabatan','Nama Jabatan');
            $crud->display_as('pangkat_golongan','Pangkat dan Golongan');

			//$crud->columns('nama_unit','nama_unit_singkat');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->unset_list();
            $crud->unset_back_to_list();

			$output = $crud->render();

			$this->_example_output($output,'Setting Identittas SKPD');
		}catch(Exception $e){
			if($e->getCode() == 14) //The 14 is the code of the "You don't have permissions" error on grocery CRUD.
			{
			    redirect('home');
			}
			else
			{
				show_error($e->getMessage().' --- '.$e->getTraceAsString());
			}
		}
	}

	function edit_desa(){
		//echo ($idu = $this->session->userdata('id_desa'));
		$id_desa = $this->uri->segment(4);
		$idu = $this->session->userdata('id_desa');
		//$su  = $this->session->userdata('id_subunit');

		if ($id_desa != $idu) {
			$this->session->set_userdata('msg_typ','err');
      		$this->session->set_userdata('msg', 'Pengguna Tidak Memiliki Hak Akses Ke Halaman Ini');
			redirect('home');
		}

		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_desa');
			$crud->set_subject('Desa');
			$crud->fields('id_kec','kode_desa','kades','nama_desa','alamat','kodepost_desa');
			$crud->set_relation('id_kec','m_kecamatan','nama_kec');
			$crud->display_as('kode_desa','Kode Desa');
			$crud->display_as('nama_desa','Nama Desa');
			$crud->display_as('kades','Nama Kepala Desa');
			$crud->display_as('alamat','Alamat Desa');
            $crud->display_as('kodepost_desa','Kode Pos Desa');

			//$crud->columns('nama_unit','nama_unit_singkat');
            $crud->unset_add();
            $crud->unset_delete();
            $crud->unset_list();
            $crud->unset_back_to_list();

			$output = $crud->render();

			$this->_example_output($output,'Setting Identittas Desa');
		}catch(Exception $e){
			if($e->getCode() == 14) //The 14 is the code of the "You don't have permissions" error on grocery CRUD.
			{
			    redirect('home');
			}
			else
			{
				show_error($e->getMessage().' --- '.$e->getTraceAsString());
			}
		}
	}

}
