<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Master_skpd extends CI_Controller
{
	var $CI = NULL;
	public function __construct(){
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->model(array('m_renstra_trx', 'm_skpd', 'm_template_cetak', 'm_lov', 'm_urusan', 'm_bidang', 'm_program', 'm_kegiatan',
				'm_jenis_belanja', 'm_kategori_belanja', 'm_subkategori_belanja', 'm_kode_belanja', 'm_rpjmd_trx'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}



	function skpd_view(){
		$data['data'] = $this->m_skpd->get_skpd_all();
		$this->template->load('template','skpd/skpd_view_new',$data);
	}
	function skpd_add(){
		$data['bidkoor'] = $this->m_skpd->get_bidkoor_all();
		$data['urusan'] = $this->m_urusan->get_urusan();
		$data['bidang'] = $this->m_bidang->get_bidang_new();
		
		$this->template->load('template','skpd/skpd_add_new',$data);
	}
	function skpd_edit($idskpd){
		$data['skpd'] = $this->m_skpd->get_skpd_byid($idskpd);
		$data['bidkoor'] = $this->m_skpd->get_bidkoor_all();
		$data['urusan'] = $this->m_urusan->get_urusan();
		$data['bidang'] = $this->m_bidang->get_bidang_new();
		$data['bidangedit'] = $this->m_skpd->get_UrusanBidang_byskpd($idskpd);

		$this->template->load('template','skpd/skpd_edit_new',$data);
	}
	function select_bidang_byurusan(){
		$idurusan = $this->input->post('idurusan');
		$data = $this->m_bidang->get_bidang($idurusan);
		echo '<label>Bidang</label>';
		echo  '<select id="IdBidangUrusan" class="form-control select2" style="width: 100%;" name="IdBidangUrusan[]" multiple="multiple"> ';
		foreach ($data as $row ) {
                    echo ' <option value="'.$row->id.'" >'.$row->nama.'</option>';
		}
		echo '</select>';
	}
//`````````````orderby```````````````
function skpd_addDB(){

		$dataskpd = array('id_bidkoor' => $this->input->post('IdKoor'),
	 				 		'kode_skpd' => $this->input->post('KodeSkpd'),
	 				 		'kodepos_skpd' => $this->input->post('KodePos'),
	 						'nama_skpd' => $this->input->post('NamaSkpd'),
	 						'alamat' => $this->input->post('AlamatSkpd'),
	 						'telp_skpd' => $this->input->post('TelptSkpd'),
	 						'fax' => $this->input->post('FaxSkpd'),
	 						'kaskpd_nama' => $this->input->post('KepalaSkpd'),
	 						'kaskpd_nip' => $this->input->post('KepalaSkpd'),	
	 						'nama_jabatan' => $this->input->post('NipSkpd'),	
	 						'pangkat_golongan' =>  $this->input->post('PangkatGolongan'),
	 						'kode_urusan' =>  $this->input->post('IdUrusan')
	 						
	 		 			);
		
		$BidangUrusan = $this->input->post('IdBidangUrusan');
		//print_r($BidangUrusan);exit();
		$BidangUrusan1 =  array_combine(range(1, count($BidangUrusan)), array_values($BidangUrusan));
		$this->m_skpd->add_skpd($dataskpd,$BidangUrusan1);
		redirect('master_skpd/skpd_view');

	}
	function skpd_editDB(){

		$dataskpd = array('id_bidkoor' => $this->input->post('IdKoor'),
	 				 		'kode_skpd' => $this->input->post('KodeSkpd'),
	 				 		'kodepos_skpd' => $this->input->post('KodePos'),
	 						'nama_skpd' => $this->input->post('NamaSkpd'),
	 						'alamat' => $this->input->post('AlamatSkpd'),
	 						'telp_skpd' => $this->input->post('TelptSkpd'),
	 						'fax' => $this->input->post('FaxSkpd'),
	 						'kaskpd_nama' => $this->input->post('KepalaSkpd'),
	 						'kaskpd_nip' => $this->input->post('KepalaSkpd'),	
	 						'nama_jabatan' => $this->input->post('NipSkpd'),	
	 						'pangkat_golongan' =>  $this->input->post('PangkatGolongan'),
	 						'kode_urusan' =>  $this->input->post('IdUrusan')
	 						
	 		 			);
		$idskpd = $this->input->post('IdSkpd');
		$BidangUrusan = $this->input->post('IdBidangUrusan');
		//print_r($BidangUrusan);exit();
		$BidangUrusan1 =  array_combine(range(1, count($BidangUrusan)), array_values($BidangUrusan));
		$this->m_skpd->edit_skpd($dataskpd,$BidangUrusan1,$idskpd);
		redirect('master_skpd/skpd_view');

	}
}

