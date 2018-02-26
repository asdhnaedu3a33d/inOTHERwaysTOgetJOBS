<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rkpd_preview extends CI_Controller {

    var $CI = NULL;
	public function __construct(){
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->model(array('m_renja_trx', 'm_skpd', 'm_template_cetak', 'm_lov', 'm_urusan', 'm_bidang',
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
			$this->session->set_userdata('msg', 'User tidak memiliki akses untuk melihat Renja pusat, mohon menghubungi administrator.');
			redirect('home');
		}
		$data = $this->get_urusan($ta, TRUE);

		$this->template->load('template', 'renja/preview_rkpd', $data);

	}

	private function get_urusan($ta, $rkpd=FALSE){
		//$proses = $this->m_renja_trx->count_jendela_kontrol($id_skpd);
		$pro_in = "";
		if ($rkpd) {
			$pro_in = "AND pro.id IN (SELECT id_prog_keg FROM t_renja_indikator_prog_keg WHERE id_prog_keg = pro.id AND (target > 0 OR target_thndpn > 0))";
		}
		$data['rkpd_type'] = "RKPD";

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");

		$data2['urusan'] = $this->db->query("
			select t.*,u.Nm_Urusan as nama_urusan from (
			SELECT
				pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
				SUM(keg.nominal) AS sum_nominal,
				SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
			FROM
				(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=1 AND id_skpd > 0) AS pro
			INNER JOIN
				(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=2 AND id_skpd > 0) AS keg ON keg.parent=pro.id
			WHERE
				keg.tahun= ".$ta." ".$pro_in." 

			GROUP BY pro.kd_urusan
			ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
			) t
			left join m_urusan u
			on t.kd_urusan = u.Kd_Urusan
		")->result();

		$data2['ta'] = $ta;
		$data['rkpd'] = $this->load->view('renja/cetak/program_kegiatan_all', $data2, TRUE);
		return $data;
	}
	
	function do_cetak_rkpd(){
		ini_set('memory_limit','-1');
		$ta = $this->session->userdata('t_anggaran_aktif');
			$data = $this->get_urusan($ta);
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'RKPD '. date("d-m-Y_H-i-s"), 1);
			$html = $this->template->load('template_cetak', 'renja/cetak/cetak_rkpd', $data, TRUE);

			$filename = 'RKPD'. date("d-m-Y_H-i_s") .'.pdf';
			pdf_create($html,$filename,"A4","Landscape");
	}

	function do_export(){	
		$this->auth->restrict();

		ini_set('memory_limit','-1');
		$ta = $this->session->userdata('t_anggaran_aktif');
		
		$this->load->library('Export_excel');				

		$this->export_excel->create_header(array(
													"RKPD"
												)
											);

		$this->export_excel->create_header(array(
													"Kode", 
													NULL,
													NULL,
													NULL,
													"Program dan Kegiatan",
													"Indikator Kinerja Program/Kegiatan",
													"Rencana Tahun ".$ta,
													NULL,
													NULL,
													"Catatan",
													"Perkiraan Maju Rencana Tahun ".($ta+1)													
												)
											);

		$this->export_excel->create_header(array(
													NULL, 
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													"Lokasi",
													"Target Capaian Kinerja",
													"Kebutuhan Dana/Pagu Indikatif (Rp.)",
													NULL,
													"Target Capaian Kinerja",
													"Kebutuhan Dana/Pagu Indikatif (Rp.)"													
												)
											);
		
		$this->export_excel->merge_cell('A1','L1');
		$this->export_excel->merge_cell('A2','D3');		
		$this->export_excel->merge_cell('E2','E3');
		$this->export_excel->merge_cell('F2','F3');
		$this->export_excel->merge_cell('G2','I2');
		$this->export_excel->merge_cell('J2','J3');
		$this->export_excel->merge_cell('K2','L2');
		
		$urusan = $this->db->query("
									select t.*,u.Nm_Urusan as nama_urusan from (
									SELECT
										pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
										SUM(keg.nominal) AS sum_nominal,
										SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
									FROM
										(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=1) AS pro
									INNER JOIN
										(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
									WHERE
										keg.tahun= ".$ta."
									GROUP BY pro.kd_urusan
									ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
									) t
									left join m_urusan u
									on t.kd_urusan = u.Kd_Urusan
								")->result();

		foreach ($urusan as $row_urusan) {
			$this->export_excel->set_row(array($row_urusan->kd_urusan, NULL, NULL, NULL, strtoupper($row_urusan->nama_urusan), NULL, NULL, NULL, Formatting::currency($row_urusan->sum_nominal), NULL,  NULL, Formatting::currency($row_urusan->sum_nominal_thndpn)));			
			$this->export_excel->merge_cell('E'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
			$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":L".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '78CBFD'))));
			
			$bidang = $this->db->query("
										select t.*,b.Nm_Bidang as nama_bidang from (
										SELECT
											pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
											SUM(keg.nominal) AS sum_nominal,
											SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
										FROM
											(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=1) AS pro
										INNER JOIN
											(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
										WHERE
											keg.tahun=".$ta."
										AND keg.kd_urusan = ".$row_urusan->kd_urusan."
										GROUP BY pro.kd_urusan,kd_bidang
										ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
										) t
										left join m_bidang b
										on t.kd_urusan = b.Kd_Urusan and t.kd_bidang = b.Kd_Bidang
									")->result();

			foreach ($bidang as $row_bidang) {
				$this->export_excel->set_row(array($row_urusan->kd_urusan, $row_bidang->kd_bidang, NULL, NULL, strtoupper($row_bidang->nama_bidang), NULL, NULL, NULL, Formatting::currency($row_bidang->sum_nominal), NULL, NULL, Formatting::currency($row_bidang->sum_nominal_thndpn)));
				$this->export_excel->merge_cell('E'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
				$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":L".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '00FF33'))));
				$skpd = $this->db->query("
											select t.*,b.nama_skpd from (
											SELECT
												keg.kd_urusan,keg.kd_bidang,keg.kd_program,keg.kd_kegiatan,keg.id_skpd,
												SUM(keg.nominal) AS sum_nominal,
												SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
											FROM
												(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=1) AS pro
											INNER JOIN
												(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
											WHERE
												keg.tahun=".$ta."
											AND keg.kd_urusan = ".$row_urusan->kd_urusan."
											AND keg.kd_bidang = ".$row_bidang->kd_bidang."
											GROUP BY keg.kd_urusan,kd_bidang,id_skpd
											ORDER BY CONVERT(keg.id_skpd,DECIMAL) ASC
											) t
											left join m_skpd b
											on b.id_skpd = t.id_skpd
										")->result();

				foreach ($skpd as $row_skpd) {
					$this->export_excel->set_row(array(strtoupper($row_skpd->nama_skpd)));
					$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
					$id_skpd = $row_skpd->id_skpd;

					$program = $this->m_renja_trx->get_program_skpd_4_cetak_v2($id_skpd,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);

					foreach ($program as $row) {
					    $result = $this->m_renja_trx->get_kegiatan_skpd_4_cetak($row->id);
					    $kegiatan = $result->result();
					    $indikator_program = $this->m_renja_trx->get_indikator_prog_keg($row->id, FALSE, TRUE);
					    $temp = $indikator_program->result();
					    $total_temp = $indikator_program->num_rows();

					    $this->export_excel->set_row(array(
					    									$row->kd_urusan, 
					    									$row->kd_bidang, 
					    									$row->kd_program, 
					    									$row->kd_kegiatan, 
					    									strtoupper($row->nama_prog_or_keg), 
					    									$temp[0]->indikator, 
					    									$row->lokasi, 
					    									$temp[0]->target." ".$temp[0]->satuan_target,
					    									Formatting::currency($row->sum_nominal),
					    									$row->catatan,
					    									$temp[0]->target_thndpn." ".$temp[0]->satuan_target_thndpn,
					    									Formatting::currency($row->sum_nominal_thndpn),
				    									)
				    								);					    
						$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":L".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FF8000'))));
					    $total_for_iteration = $total_temp;
					    if ($total_for_iteration > 1) {
					    	$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'A'.($this->export_excel->get_last_row()+$total_for_iteration-1));
						    $this->export_excel->merge_cell('B'.$this->export_excel->get_last_row(),'B'.($this->export_excel->get_last_row()+$total_for_iteration-1));
						    $this->export_excel->merge_cell('C'.$this->export_excel->get_last_row(),'C'.($this->export_excel->get_last_row()+$total_for_iteration-1));
						    $this->export_excel->merge_cell('D'.$this->export_excel->get_last_row(),'D'.($this->export_excel->get_last_row()+$total_for_iteration-1));
						    $this->export_excel->merge_cell('E'.$this->export_excel->get_last_row(),'E'.($this->export_excel->get_last_row()+$total_for_iteration-1));
						    $this->export_excel->merge_cell('G'.$this->export_excel->get_last_row(),'G'.($this->export_excel->get_last_row()+$total_for_iteration-1));
						    $this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'I'.($this->export_excel->get_last_row()+$total_for_iteration-1));
						    $this->export_excel->merge_cell('J'.$this->export_excel->get_last_row(),'J'.($this->export_excel->get_last_row()+$total_for_iteration-1));
						    $this->export_excel->merge_cell('L'.$this->export_excel->get_last_row(),'L'.($this->export_excel->get_last_row()+$total_for_iteration-1));

					        for ($i=1; $i < $total_for_iteration; $i++) {					            
					            $this->export_excel->set_row(array(
					    									NULL, 
					    									NULL, 
					    									NULL, 
					    									NULL, 
					    									NULL, 
					    									$temp[$i]->indikator, 
					    									NULL, 
					    									$temp[$i]->target." ".$temp[$i]->satuan_target,
					    									NULL,
					    									NULL,
					    									$temp[$i]->target_thndpn." ".$temp[$i]->satuan_target_thndpn,
					    									NULL
				    									)
				    								);
								$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":L".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FF8000'))));
					        }
					     }

					    foreach ($kegiatan as $row) {
					        $indikator_kegiatan = $this->m_renja_trx->get_indikator_prog_keg($row->id, FALSE, TRUE);
					        $temp = $indikator_kegiatan->result();
					        $total_temp = $indikator_kegiatan->num_rows();
					        
					        $total_for_iteration = $total_temp;
					        $this->export_excel->set_row(array(
					    									$row->kd_urusan, 
					    									$row->kd_bidang, 
					    									$row->kd_program, 
					    									$row->kd_kegiatan, 
					    									$row->nama_prog_or_keg, 
					    									$temp[0]->indikator, 
					    									$row->lokasi, 
					    									$temp[0]->target." ".$temp[0]->satuan_target,
					    									Formatting::currency($row->nominal),
					    									$row->catatan,
					    									$temp[0]->target_thndpn." ".$temp[0]->satuan_target_thndpn,		    									
					    									Formatting::currency($row->nominal_thndpn)
				    									)
				    								);

					        if ($total_for_iteration > 1) {
					        	$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'A'.($this->export_excel->get_last_row()+$total_for_iteration-1));
							    $this->export_excel->merge_cell('B'.$this->export_excel->get_last_row(),'B'.($this->export_excel->get_last_row()+$total_for_iteration-1));
							    $this->export_excel->merge_cell('C'.$this->export_excel->get_last_row(),'C'.($this->export_excel->get_last_row()+$total_for_iteration-1));
							    $this->export_excel->merge_cell('D'.$this->export_excel->get_last_row(),'D'.($this->export_excel->get_last_row()+$total_for_iteration-1));
							    $this->export_excel->merge_cell('E'.$this->export_excel->get_last_row(),'E'.($this->export_excel->get_last_row()+$total_for_iteration-1));
							    $this->export_excel->merge_cell('G'.$this->export_excel->get_last_row(),'G'.($this->export_excel->get_last_row()+$total_for_iteration-1));
							    $this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'I'.($this->export_excel->get_last_row()+$total_for_iteration-1));
							    $this->export_excel->merge_cell('J'.$this->export_excel->get_last_row(),'J'.($this->export_excel->get_last_row()+$total_for_iteration-1));
							    $this->export_excel->merge_cell('L'.$this->export_excel->get_last_row(),'L'.($this->export_excel->get_last_row()+$total_for_iteration-1));

					            for ($i=1; $i < $total_for_iteration; $i++) {					                
					                $this->export_excel->set_row(array(
					    									NULL, 
					    									NULL, 
					    									NULL, 
					    									NULL, 
					    									NULL, 
					    									$temp[$i]->indikator, 
					    									NULL, 
					    									$temp[$i]->target." ".$temp[$i]->satuan_target,
					    									NULL,
					    									NULL,
					    									$temp[$i]->target_thndpn." ".$temp[$i]->satuan_target_thndpn,
					    									NULL
				    									)
				    								);										   
					            }
					        }
					    }
					}

				}
			}
		}		

		$this->export_excel->filename = "RKPD ".$ta." ";

		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(1))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(2))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(3))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(4))->setAutoSize(true);
        $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(5))->setWidth(45);
        $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(6))->setWidth(45);  
        $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(7))->setWidth(15);  
        $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(8))->setWidth(12);
        $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(9))->setWidth(20);
        $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(10))->setWidth(50);  
        $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(11))->setWidth(12);
        $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(12))->setWidth(20);
        
        $this->export_excel->getActiveSheet()->getStyle("A1:L3")->getAlignment()->setWrapText(true);
        $this->export_excel->getActiveSheet()->getStyle("E". $this->export_excel->first_col .":L".$this->export_excel->last_col)->getAlignment()->setWrapText(true);

        $this->export_excel->set_border("A".$this->export_excel->first_col.":L".$this->export_excel->last_col);
		
		$id_skpd = 1;
		$skpd = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		$styleBorder = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_NONE
			)
		  )
		);
		$styleUnderline = array(
		  'font' => array(
			'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
		  )
		);
		switch(date('F')){
			case 'January';
			default:
				$bulan="Januari";
				break;
			case 'February';
			default:
				$bulan="Februari";
				break;
			case 'March';
			default:
				$bulan="Maret";
				break;
			case 'April';
			default:
				$bulan="April";
				break;
			case 'May';
			default:
				$bulan="Mei";
				break;
			case 'June';
			default:
				$bulan="Juni";
				break;
			case 'July';
			default:
				$bulan="Juli";
				break;
			case 'August';
			default:
				$bulan="Agustus";
				break;
			case 'September';
			default:
				$bulan="September";
				break;
			case 'October';
			default:
				$bulan="Oktober";
				break;
			case 'November';
			default:
				$bulan="November";
				break;
			case 'December';
			default:
				$bulan="Desember";
				break;
		}
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "Semarapura, ".date('j')." ".$bulan." ".date('Y'), NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $skpd->nama_jabatan, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $skpd->kaskpd_nama, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->applyFromArray($styleUnderline);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $skpd->pangkat_golongan, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "NIP. ".$skpd->kaskpd_nip, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

        $this->export_excel->set_readonly();

		$this->export_excel->execute();
	}
	
	function rkpd_tanpa_program()
	{
		$this->auth->restrict();
		$id_group = $this->session->userdata('id_group');
		$ta = $this->session->userdata('t_anggaran_aktif');
		if (empty($id_group)) {
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'User tidak memiliki akses untuk melihat Renja pusat, mohon menghubungi administrator.');
			redirect('home');
		}
		$data = $this->get_urusan_khusus($ta, TRUE);

		$this->template->load('template', 'renja/preview_rkpd_tanpa_program', $data);
	}
	
	private function get_urusan_khusus($ta, $rkpd = FALSE){
		//$proses = $this->m_renja_trx->count_jendela_kontrol($id_skpd);
		$pro_in = "";
		if ($rkpd) {
			$pro_in = "AND pro.id IN (SELECT id_prog_keg FROM t_renja_indikator_prog_keg WHERE id_prog_keg = pro.id AND (target > 0 OR target_thndpn > 0))";
		}

		$data['rkpd_type'] = "RKPD";
		$data2['urusan'] = $this->db->query("
			select t.*,u.Nm_Urusan as nama_urusan from (
			SELECT
				pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
				SUM(keg.nominal) AS sum_nominal,
				SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
			FROM
				(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=1) AS pro
			INNER JOIN
				(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
			WHERE
				keg.tahun= ".$ta." ".$pro_in."
			GROUP BY pro.kd_urusan
			ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
			) t
			left join m_urusan u
			on t.kd_urusan = u.Kd_Urusan
		")->result();

		$data2['ta'] = $ta;
		$data['rkpd'] = $this->load->view('renja/cetak/tanpa_program_kegiatan', $data2, TRUE);
		return $data;
	}
	function do_cetak_rkpd_tanpa_program(){
		ini_set('memory_limit','-1');
		$ta = $this->session->userdata('t_anggaran_aktif');
			$data = $this->get_urusan_khusus($ta);
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'RKPD Tanpa Program Kegiatan'. date("d-m-Y_H-i-s"), 1);
			$html = $this->template->load('template_cetak', 'renja/cetak/cetak_rkpd_tanpa_program', $data, TRUE);

			$filename = 'RKPD Tanpa Program Kegiatan'. date("d-m-Y_H-i_s") .'.pdf';
			pdf_create($html,$filename,"A4","Landscape");
	}
	
	function rkpd_skpd(){
		$this->auth->restrict();
		$id_group = $this->session->userdata('id_group');
		$ta = $this->session->userdata('t_anggaran_aktif');
		if (empty($id_group)) {
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'User tidak memiliki akses untuk melihat Renja pusat, mohon menghubungi administrator.');
			redirect('home');
		}
		
		//$data = $this->get_urusan_khusus($ta);
		$data['tahun_renja'] = $ta;
		
		$id_skpd = array("" => "~~ Pilh SKPD ~~");
        foreach ($this->m_skpd->get_skpd_chosen() as $row) {
            $id_skpd[$row->id] = $row->label;
        }
		
		$data['cmb_skpd'] = form_dropdown('id_skpd', $id_skpd, NULL,'data-placeholder="Pilih SKPD" class="common chosen-select" id="id_skpd"');

		$this->template->load('template', 'renja/preview_rkpd_skpd', $data);
	}
	
	function get_data_rkpd_skpd()
	{
		$ta = $this->input->post("tahun");
		$id_skpd = $this->input->post("id_skpd");
		
		$data['ta']	= $ta;
		$data['id_skpd'] = $id_skpd;
		$data['urusan'] = $this->m_renja_trx->get_urusan_skpd($ta, $id_skpd);
		
		$this->load->view('renja/cetak/isi_rkpd_skpd', $data);
	}
	
	function do_cetak_rkpd_skpd($id_skpd = NULL, $tahun = NULL)
	{
		ini_set('memory_limit','-1');
		$ta = $this->session->userdata('t_anggaran_aktif');
		$skpd_detail_cetak = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		$data = $this->get_rkpd_skpd_cetak($id_skpd,$tahun);
		$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'RKPD SKPD'.$skpd_detail_cetak->nama_skpd.' '. date("d-m-Y_H-i-s"), 1);
		$html = $this->template->load('template_cetak', 'renja/cetak/cetak_rkpd_skpd', $data, TRUE);

		$filename = 'RKPD'. date("d-m-Y_H-i_s") .'.pdf';
		pdf_create($html,$filename,"A4","Landscape");
	}
	
	private function get_rkpd_skpd_cetak($id_skpd,$tahun){
		$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		$data['rkpd_type'] = "RKPD ".strtoupper($skpd_detail->nama_skpd);
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		/*$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");*/
		$data2['urusan'] = $this->m_renja_trx->get_urusan_skpd($tahun, $id_skpd);
		$data2['ta'] = $tahun;
		$data2['id_skpd'] = $id_skpd;
		$data['rkpd'] = $this->load->view('renja/cetak/isi_rkpd_skpd', $data2, TRUE);
		return $data;
	}
	
	function rkpd_skpd_tanpa_program()
	{
		$this->auth->restrict();
		$id_group = $this->session->userdata('id_group');
		$ta = $this->session->userdata('t_anggaran_aktif');
		if (empty($id_group)) {
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'User tidak memiliki akses untuk melihat Renja pusat, mohon menghubungi administrator.');
			redirect('home');
		}
		$data = $this->get_urusan_skpd_khusus($ta);

		$this->template->load('template', 'renja/preview_rkpd_skpd_tanpa_program', $data);
	}
	
	private function get_urusan_skpd_khusus($ta)
	{
		$data['rkpd_type'] = "RKPD";
		$data2['skpd_select'] = $this->db->query("
							SELECT * FROM m_skpd
						 ")->result();
		$data2['ta'] = $ta;
		$data['rkpd'] = $this->load->view('renja/cetak/skpd_tanpa_program_kegiatan', $data2, TRUE);
		return $data;
	}
	
	function do_cetak_rkpd_skpd_tanpa_program()
	{
		ini_set('memory_limit','-1');
		$ta = $this->session->userdata('t_anggaran_aktif');
			$data = $this->get_urusan_skpd_khusus($ta);
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'RKPD SKPD Tanpa Program Kegiatan'. date("d-m-Y_H-i-s"), 1);
			$html = $this->template->load('template_cetak', 'renja/cetak/cetak_rkpd_skpd_tanpa_program', $data, TRUE);

			$filename = 'RKPD SKPD Tanpa Program Kegiatan'. date("d-m-Y_H-i_s") .'.pdf';
			pdf_create($html,$filename,"A4","Landscape");
	}
}
