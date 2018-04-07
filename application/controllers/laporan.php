<?php
class Laporan extends CI_Controller{

    public function __construct()
    {
        $this->CI =& get_instance();
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
    {
        $this->template->load('laporan/template','laporan/laporan',null);
    }
}