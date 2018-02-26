<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Usulan_tak_terakomodir extends CI_Controller {
		
		var $CI = NULL;
		public function __construct(){
			$this->CI =& get_instance();
			parent::__construct();
			$this->load->model(array('m_usulan_tak_terakomodir','m_skpd', 'm_template_cetak', 'm_lov', 'm_urusan', 'm_bidang',
									 'm_program', 'm_kegiatan','m_settings'));
	        if (!empty($this->session->userdata("db_aktif"))) {
	            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
	        }
		}
		
		function index(){
			$this->auth->restrict();
			$id_group = $this->session->userdata('id_group');
			$ta = $this->session->userdata('t_anggaran_aktif');
			if (empty($id_group)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'User tidak memiliki akses untuk melihat Usulan Tidak Terakomodir, mohon menghubungi administrator.');
				redirect('home');
			}
			$data = $this->get_urusan($ta);
	
			$this->template->load('template', 'usulan_tak_terakomodir/preview_usulan', $data);
	
		}
		
		private function get_urusan($ta){
			//$proses = $this->m_renja_trx->count_jendela_kontrol($id_skpd);
			$data['usulan_type'] = "Usulan Tidak Terakomodir";
	
			//$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			//$header = $this->m_template_cetak->get_value("GAMBAR");
			//$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
			//$data['header'] = $this->m_template_cetak->get_value("HEADER");
	
			$data2['urusan'] = $this->m_usulan_tak_terakomodir->get_urusan_usulan($ta);
	
			$data2['ta'] = $ta;
			$data['usulan'] = $this->load->view('usulan_tak_terakomodir/cetak/usulan_tak_terakomodir_all', $data2, TRUE);
			return $data;
		}
		
		function do_cetak_usulan(){
		ini_set('memory_limit','-1');
		$ta = $this->session->userdata('t_anggaran_aktif');
			$data = $this->get_urusan($ta);
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Usulan Tidak Terakomodir '. date("d-m-Y_H-i-s"), 1);
			$html = $this->template->load('template_cetak', 'usulan_tak_terakomodir/cetak/cetak_usulan', $data, TRUE);

			$filename = 'Usulan-Tidak-Terakomodir_'. date("d-m-Y_H-i_s") .'.pdf';
			pdf_create($html,$filename,"A4","Landscape");
		}
		
		function do_export_usulan(){
			$this->auth->restrict();
	
			ini_set('memory_limit','-1');		
			
			$this->load->library('Export_excel');				
					 
			$ta = $this->session->userdata('t_anggaran_aktif');
			
			$this->export_excel->create_header(array(
														"USULAN TIDAK TERAKOMODIR", 
														NULL,
														NULL,
														NULL,
														NULL,
														NULL,
														NULL,
														NULL,
														NULL,
														NULL,
														NULL,
														NULL,
														NULL,
														NULL													
													)
												);
												
			$this->export_excel->create_header(array(
														"KODE", 
														NULL,
														NULL,
														NULL,
														"PROGRAM DAN KEGIATAN",
														"JENIS PEKERJAAN",
														"VOLUME",
														"SATUAN",
														"JUMLAH DANA (Rp.)",
														"NAMA DESA",
														"NAMA KECAMATAN",
														"SKPD PENANGGUNGJAWAB",
														"ASAL USULAN",
														"NAMA DEWAN"													
													)
												);
			
			$this->export_excel->merge_cell('A1','N1');
			$this->export_excel->merge_cell('A2','D2');
			
			$urusan = $this->m_usulan_tak_terakomodir->get_urusan_usulan($ta);
			
			foreach($urusan as $row_urusan){
				if($row_urusan->id_musrenbang == ""){
				$this->export_excel->set_row(array("Data Belum Terisikan.."));
				$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'N'.$this->export_excel->get_last_row());
				}else{
					if($row_urusan->nama_urusan!=NULL){
						$nama_urusan = $row_urusan->nama_urusan;
					}
					else{
						$nama_urusan = "Kode Urusan Belum Ditentukan";
					}
					if($row_urusan->kd_urusan!=0){
						$kode_urusan = $row_urusan->kd_urusan;
					}
					else{
						$kode_urusan = 0;
					}
					$bidang = $this->m_usulan_tak_terakomodir->get_bidang_usulan($ta,$kode_urusan);
					$this->export_excel->set_row(array($row_urusan->kd_urusan, 
														NULL, 
														NULL, 
														NULL, 
														$nama_urusan,
														NULL, 
														NULL, 
														NULL, 
														Formatting::currency($row_urusan->sum_jumlah_dana,2), 
														NULL, 
														NULL, 
														NULL, 
														NULL,
														NULL));
					$this->export_excel->merge_cell('E'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
					$this->export_excel->merge_cell('J'.$this->export_excel->get_last_row(),'N'.$this->export_excel->get_last_row());
					$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":N".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '78cbfd'))));
		    		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						foreach ($bidang as $row_bidang) {
							if($row_bidang->nama_bidang!=NULL){
							$nama_bidang = $row_bidang->nama_bidang;
							}
							else{
								$nama_bidang = "Kode Bidang Belum Ditentukan";
							}
							if($row_bidang->kd_bidang!=0){
							$kode_bidang = $row_bidang->kd_bidang;
							}
							else{
								$kode_bidang = 0;
							}
							$program = $this->m_usulan_tak_terakomodir->get_program_usulan($ta,$kode_urusan,$kode_bidang);
							$this->export_excel->set_row(array($row_urusan->kd_urusan, 
																$row_bidang->kd_bidang, 
																NULL, 
																NULL, 
																$nama_bidang,
																NULL, 
																NULL, 
																NULL, 
																Formatting::currency($row_bidang->sum_jumlah_dana,2), 
																NULL, 
																NULL, 
																NULL, 
																NULL,
																NULL));
							$this->export_excel->merge_cell('E'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
							$this->export_excel->merge_cell('J'.$this->export_excel->get_last_row(),'N'.$this->export_excel->get_last_row());
							$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":N".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '00ff33'))));
							$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							foreach ($program as $row_program){
								if($row_program->nama_program!=NULL){
									$nama_program = $row_program->nama_program;
								}
								else{
									$nama_program = "Kode Program Belum Ditentukan";
								}
								if($row_program->kd_program!=0){
								$kode_program = $row_program->kd_program;
								}
								else{
									$kode_program = 0;
								}
								$kegiatan = $this->m_usulan_tak_terakomodir->get_kegiatan_usulan($ta,$kode_urusan,$kode_bidang,$kode_program);
								$this->export_excel->set_row(array($row_urusan->kd_urusan, 
																$row_bidang->kd_bidang, 
																$row_program->kd_program, 
																NULL, 
																$nama_program,
																NULL, 
																NULL, 
																NULL, 
																Formatting::currency($row_program->sum_jumlah_dana,2), 
																NULL, 
																NULL, 
																NULL, 
																NULL,
																NULL));
								$this->export_excel->merge_cell('E'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
								$this->export_excel->merge_cell('J'.$this->export_excel->get_last_row(),'N'.$this->export_excel->get_last_row());
								$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":N".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'ff8000'))));
								$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								foreach ($kegiatan as $row_kegiatan){
									if($row_kegiatan->nama_kegiatan!=NULL){
										$nama_kegiatan = $row_kegiatan->nama_kegiatan;
									}
									else{
										$nama_kegiatan = "Kode Kegiatan Belum Ditentukan";
									}
									$this->export_excel->set_row(array($row_urusan->kd_urusan, 
																$row_bidang->kd_bidang, 
																$row_program->kd_program, 
																$row_kegiatan->kd_kegiatan, 
																$nama_kegiatan,
																$row_kegiatan->jenis_pekerjaan, 
																$row_kegiatan->volume, 
																$row_kegiatan->satuan, 
																Formatting::currency($row_kegiatan->jumlah_dana,2), 
																$row_kegiatan->nama_desa, 
																$row_kegiatan->nama_kecamatan, 
																$row_kegiatan->nama_skpd, 
																$row_kegiatan->asal_usulan,
																$row_kegiatan->nama_dewan));
									$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								}
							}
						}
				}
			}
			
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(1))->setAutoSize(true);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(2))->setAutoSize(true);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(3))->setAutoSize(true);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(4))->setAutoSize(true);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(5))->setWidth(40);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(6))->setWidth(45);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(7))->setWidth(15);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(8))->setWidth(15);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(9))->setAutoSize(true);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(10))->setWidth(20);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(11))->setWidth(20);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(12))->setWidth(30);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(13))->setAutoSize(true);
			$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(14))->setAutoSize(true);
			
			$this->export_excel->getActiveSheet()->getStyle("E". $this->export_excel->first_col .":E".$this->export_excel->last_col)->getAlignment()->setWrapText(true);
			$this->export_excel->getActiveSheet()->getStyle("F". $this->export_excel->first_col .":F".$this->export_excel->last_col)->getAlignment()->setWrapText(true);
			$this->export_excel->getActiveSheet()->getStyle("G". $this->export_excel->first_col .":G".$this->export_excel->last_col)->getAlignment()->setWrapText(true);
			$this->export_excel->getActiveSheet()->getStyle("H". $this->export_excel->first_col .":H".$this->export_excel->last_col)->getAlignment()->setWrapText(true);
			$this->export_excel->getActiveSheet()->getStyle("J". $this->export_excel->first_col .":J".$this->export_excel->last_col)->getAlignment()->setWrapText(true);
			$this->export_excel->getActiveSheet()->getStyle("K". $this->export_excel->first_col .":K".$this->export_excel->last_col)->getAlignment()->setWrapText(true);
			$this->export_excel->getActiveSheet()->getStyle("L". $this->export_excel->first_col .":L".$this->export_excel->last_col)->getAlignment()->setWrapText(true);
			$this->export_excel->getActiveSheet()->getStyle("N". $this->export_excel->first_col .":N".$this->export_excel->last_col)->getAlignment()->setWrapText(true);
		
			$this->export_excel->set_border("A".$this->export_excel->first_col.":N".$this->export_excel->last_col);
			
			$this->export_excel->filename = "Usulan_Tidak_Terakomodir -".$ta." ";
			$this->export_excel->set_readonly();
			$this->export_excel->execute();
		}
	}
?>