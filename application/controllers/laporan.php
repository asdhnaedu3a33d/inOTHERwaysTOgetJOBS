<?php

class Laporan extends CI_Controller {

    public function __construct()
    {
        $this->CI =& get_instance();
        parent::__construct();
        $this->load->helper('url', 'input');
        $this->load->model('m_laporan');
    }

    public function index()
    {
        $this->template->load('laporan/template', 'laporan/laporan-realisasi-uang', null);
    }

    public function rkpd_apbd()
    {
        $this->template->load('laporan/template', 'laporan/laporan-program-kegiatan', null);
    }

    public function getChartDataJson($code)
    {
        switch ($code)
        {
            case 'realisasi':
                $data = $this->m_laporan->getRealisasiUang();
                echo json_encode($data);
                break;
            case 'rkpd-apbd-kegiatan':
                $data = $this->m_laporan->getRkpdApbdKegiatan();
                echo json_encode($data);
                break;
            case 'rkpd-apbd-program':
                $data = $this->m_laporan->getRkpdApbdProgram();
                echo json_encode($data);
                break;
            default:
                return;
        }
    }
}