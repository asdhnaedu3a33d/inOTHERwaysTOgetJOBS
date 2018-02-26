<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @brief library Class auth yang berisi fungsi-fungsi authentifikasi user
 *
 * library Class auth yang berisi fungsi-fungsi authentifikasi user login
 *
 *
 * @author I Made Agus Setiawan
 */

class Auth {

	var $CI = NULL;

	function __construct()
	{
		// get CI's object
		$this->CI =& get_instance();
	}


	function get_username()
	{
		$this->restrict();

		return $this->CI->session->userdata('username');
	}

	//$oper hanya digunakan jika $permissions adalah array
	function has_privileges($permissions, $oper = NULL)
	{
		$granted = TRUE;

		//cek login dulu baru cek permission
        if ($this->is_logged_in()){

            $user = $this->get_user();
            if(is_array($permissions)) {
                //set $oper=OR jika $permissions array dan $oper=null
                $oper = (is_null($oper) || !in_array(strtoupper($oper), array('OR', 'AND'))) ?
                        'OR' : strtoupper($oper);

                foreach($permissions as $perm) {
                    if($oper == 'OR') {
                        if( $user->hasPrivilege($perm) ) {
                            //salah satu saja punya privilege, loloskan
                            $granted = TRUE;
                            break;
                        } else {
                            $granted = FALSE;
                        }
                    } else {
                        if( !$user->hasPrivilege($perm) ) {
                            //salah satu saja tidak punya privilege, batalkan
                            $granted = FALSE;
                            break;
                        }
                    }
                }
            } else {
                if( !$user->hasPrivilege($permissions) ) {
                    $granted = FALSE;
                }
            }
        } else{
            $granted = FALSE;
        }

		if( !$granted ) {
			//TODO: NOT GRANTED special page needed
			$this->has_no_privilege();
		}
	}

	function get_user()
	{
		$user = $this->CI->session->userdata('user_obj');
		if( !empty($user) ){
			return unserialize($user);
		}

		return FALSE;
	}

	function has_no_privilege()
	{
		//TODO: NOT GRANTED special page needed
		//exit("don't have privilege");

		//set response header to : forbidden
		//set_status_header(403);
		show_error("don't have privilege");

	}

	function set_user($user)
	{
		$this->CI->session->set_userdata('user_obj', serialize($user));
		$this->CI->session->set_userdata('active_menu', $user->getActiveIdMenu());
	}

	public function create_menu($menus_id)
	{
		$this->CI->load->library('rbac/menu_rbac');

		$menuCreator = new Menu_rbac();

		//add home menu paling depan, home id = 1
		if(is_array($menus_id)) {
			$menus_id = array_merge(array(1), $menus_id);
		} else {
			$menus_id = array(1);
		}

		return $menuCreator->buat_menu($menus_id);
	}

	/**
	* Fungsi untuk mengecek login user
	* @return boolean
	*/
	function is_logged_in()
	{
		//TODO: perlu direview kembali mekanisme nya.
		if($this->CI->session->userdata('id_user') == '')
		{
			return false;
		}
		return true;
	}
	/**
	* Fungsi untuk untuk validasi di setiap halaman yang mengharuskan authentikasi
	*/
	function restrict()
	{
		if($this->is_logged_in() === false)
		{
			if(isset($_SERVER['PATH_INFO']))
				redirect('authenticate/login?call_from='.$_SERVER['PATH_INFO']);
			else
				redirect('authenticate/login');
		}
	}

	function restrict_ajax_login()
	{
        //batasi harus sudah login / session expired
        if($this->is_logged_in() === FALSE) {

	        $response['errno'] 	= 403;
	        $response['message']= site_url('authenticate');
	        echo json_encode($response);
	        return FALSE;
        }

        return TRUE;
	}

	function restrict_referer($ref)
	{
        //pastikan method ini direquest dari usulan $_SERVER['HTTP_REFERER'] = '/usulan'
        if(!(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $ref) !== FALSE)) {

           show_error('No direct script access allowed');
        }
	}


	function restrict_ajax_request($ref = NULL)
	{
        //dan dipanggil menggunakan ajax $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest'
        if(!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {

           show_error('No direct script access allowed');
        }

        if(!is_null($ref)) {
	        $this->restrict_referer($ref);
        }
	}

	/**
	* Fungsi untuk untuk menglogout user
	*/
	function do_logout()
	{
		$this->CI->session->sess_destroy();
	}

	function get_client_ip()
	{
		$ip_address = '-';

		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];

		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];

		} else {
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}

		return $ip_address;
	}

	function get_browser_agent()
	{
		return $_SERVER['HTTP_USER_AGENT'];
	}

}
