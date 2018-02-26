<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @brief Controller Class fungsi-fungsi login ke sistem
 *
 * Controller Class fungsi-fungsi login
 *
 *
 * @author I Made Agus Setiawan
 */

class Authenticate extends CI_Controller
{
    var $CI = NULL;
	var $login_theme = "modify-style";

	/**
	*	Fungsi ini digunakan untuk membentuk halaman
	*/
    public function __construct()
	{
		$this->CI =& get_instance();
		parent::__construct();
		$this->load->helper(array('form','url', 'text_helper','date'));
		$this->load->database();
		$this->load->library(array('Pagination','image_lib'));
		$this->load->library(array('rbac/user', 'rbac/role', 'rbac/menu_rbac'));
		$this->load->model(array('m_users','m_umum'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	private function _user_login($uname, $enc_passwd)
	{
		$db_aktif = $this->input->post('periode');

		$this->load->database($db_aktif, FALSE, TRUE);
		$_user = $this->m_users->get_user_by_credential($uname, $enc_passwd);

		if($_user === FALSE)
			return FALSE;

		//gagalkan jika user tidak aktif
		if($_user->user_active != '1')
			return FALSE;

		//VALID: user exists dan aktif, lanjutkan pembuatan session
		$_userdata = $this->m_users->get_user_by_id_complete($_user->id_user);
		if($_userdata === FALSE)
			return FALSE;

		//create token, md5 password + ip_address + agent_browser
		$_token 	= md5($_userdata->password .
					  $this->auth->get_client_ip() .
					  $this->auth->get_browser_agent());

		//create user object, berisi role dan permission
		$_user_obj = $this->user->getByIdUser($_userdata->id_user);

        /*
        if (isset($_userdata->id_unit)){
            $unit=$this->m_umum->get_nama_unit_by_id($_userdata->id_unit);
        }else{
            $unit="";
        }
        if(isset($_userdata->id_subunit)){
            $subunit=$this->m_umum->get_nama_sunit_by_id($_userdata->id_subunit);
        }else{
            $subunit="";
        }
        */

		$session_data = array(
			//'kode_digit_unit' => $_userdata->kode_digit,
			'db_aktif'    	=> $db_aktif,
			'id_user'    	=> $_userdata->id_user,
			'nama'        	=> $_userdata->username,
			'nama_p'		=> $_userdata->user_nama,
			'username'		=> $_userdata->username,
			'kode_skpd'     => $_userdata->kode_skpd,
			'kode_desa'     => $_userdata->kode_desa,
			'id_skpd'       => $_userdata->id_skpd,
			'id_desa'     	=> $_userdata->id_desa,
      'id_kecamatan'  => $_userdata->id_kecamatan,
      'nama_skpd' 	=> $_userdata->nama_skpd,
			'nama_desa' 	=> $_userdata->nama_desa,
			'id_kategori'   => $_userdata->id_kategori,
			'id_group'      => $_userdata->id_group,
			'token' 		=> $_token,
			'user_obj'		=> serialize($_user_obj),
			'active_menu'	=> $_user_obj->getActiveIdMenu(),
			't_anggaran_aktif' => $this->m_settings->get_tahun_anggaran_aktif_db()
		);

		// buat session
		$this->session->set_userdata($session_data);

		return TRUE;
	}

	function index ()
	{
		redirect(site_url('authenticate/login'));
	}

	function login()
	{
		//jika lagi diperbaiki
		$uc = $this->m_umum->get_under_construction();
		if (empty($uc)) {
			redirect('under_construction');
		}

		//jika sudah login, arahkan ke home page
		if($this->auth->is_logged_in() == TRUE)
		{
			redirect('./home');
		}

		//jika belum, munculkan form login
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			//tampilkan form login
			$data['title']	= 'Login SIRENBANGDA';
			$this->load->view('login.php', $data);
			return;
		}

		//validasi login
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$result = $this->_user_login($username, md5($password));

		if($result == TRUE) {
			$target = '';
			$call_from = $this->input->get('call_from');

			if(!empty($call_from))
				$target = $call_from;
			else
				$target = 'home';

			$response['errno'] 		= 0;
			$response['message'] 	= site_url($target);
			echo json_encode($response);

		} else {

			//ajax response
			$response['errno']  	= 1;
			$response['message']   	= "Kombinasi username dan password salah.";
			echo json_encode($response);
		}
	}

    /**
	* Fungsi ini digunakan untuk halaman logout
	*/
    function logout()
	{
		if($this->auth->is_logged_in() == TRUE)
		{
			$this->session->sess_destroy();
		}

		redirect('authenticate');
	}

    function js_err()
	{
        $this->load->view('js_err');
    }

	function change_role()
	{
		$this->auth->restrict();

		$rolename = $this->input->post('rolename');
		$user = $this->auth->get_user();

		if(!in_array($rolename, array_keys($user->roles))) {
			$response['errno'] 	= 1;
			$response['message']= "Anda tidak memiliki hak sebagai role '$rolename'";
			echo json_encode($response);
			return;
		}

		//set session
		$user->active_role = $rolename;
		$this->auth->set_user($user);

		//
		$response['errno'] 	= 0;
		$response['message']= site_url('home');
		echo json_encode($response);
	}

	function change_base_sunit()
	{
		$this->auth->restrict();

		$idsu = $this->input->post('idsunit');

		//set session
		$user->active_role = $rolename;
		$this->auth->set_user($user);

		//
		$response['errno'] 	= 0;
		$response['message']= site_url('home');
		echo json_encode($response);

	}
}
