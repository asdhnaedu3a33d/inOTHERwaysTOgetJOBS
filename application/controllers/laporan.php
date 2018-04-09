<?php
class Laporan extends CI_Controller{

    public function __construct()
    {
        $this->CI =& get_instance();
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('m_laporan');
    }

    public function index()
    {
        $this->template->load('laporan/template','laporan/laporan-realisasi-uang',null);
    }

    public function program()
    {
        $this->template->load('laporan/template','laporan/laporan-program',null);
    }

    public function test()
    {
        $data = $this->m_laporan->getRealisasiUang();
        echo json_encode($data);
    }

    public function hello(){
        echo $this->input->get_request_header('Content-Type', TRUE);
    }
}