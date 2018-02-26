<?php

class Home extends CI_Controller {

    public function __construct()
	{
		$this->CI =& get_instance();
		parent::__construct();
		$this->load->helper('url');
		$this->load->model(array('m_umum'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		//jika lagi diperbaiki
		//$this->output->enable_profiler(TRUE);
		$uc = $this->m_umum->get_under_construction();
		if (empty($uc)) {
			redirect('under_construction');
		}

		$this->auth->restrict();

        //prepare session data
        $idsu   = $this->session->userdata('id_subunit');
        $cdsu   = $this->session->userdata('kode_subunit');

        $idu = $this->session->userdata('id_unit');

        $data['idu']=$idu;
        $data['idsu']=$idsu;
        $data['link_unit']=base_url('users/unit/edit/'.$idu);
        $data['link_subunit']=base_url('users/subunit/edit/'.$idsu);
		$data['slider']=$this->m_umum->get_slider();

		$this->template->load('template', 'home', $data);
	}

  function kosong($param)
	{
		//jika lagi diperbaiki
		//$this->output->enable_profiler(TRUE);
		$uc = $this->m_umum->get_under_construction();
		if (empty($uc)) {
			redirect('under_construction');
		}

		$this->auth->restrict();

        //prepare session data
        $idsu   = $this->session->userdata('id_subunit');
        $cdsu   = $this->session->userdata('kode_subunit');

        $idu = $this->session->userdata('id_unit');

        $data['idu']=$idu;
        $data['idsu']=$idsu;
        $data['link_unit']=base_url('users/unit/edit/'.$idu);
        $data['link_subunit']=base_url('users/subunit/edit/'.$idsu);
		    $data['slider']=$this->m_umum->get_slider();
        $data['pesan'] = $param." tidak tersedia";

		$this->template->load('template', 'renstra/renstra_kosong', $data);
	}

}
?>
