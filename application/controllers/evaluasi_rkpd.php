<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluasi_rkpd extends CI_controller
{
	var $CI = NULL;
	public $triwulan = array(
								"1" => array(
												"nama" => "Triwulan 1",
												"awal" => 1,
												"akhir" => 3,
												"romawi" => "I"
											),
								"2" => array(
												"nama" => "Triwulan 2",
												"awal" => 4,
												"akhir" => 6,
												"romawi" => "II"
											),
								"3" => array(
												"nama" => "Triwulan 3",
												"awal" => 7,
												"akhir" => 9,
												"romawi" => "III"
											),
								"4" => array(
												"nama" => "Triwulan 4",
												"awal" => 10,
												"akhir" => 12,
												"romawi" => "IV"
											),
							);

	public function __construct()
	{
				$this->CI =& get_instance();
        parent::__construct();
        $this->load->database();
        $this->load->model(array('m_evaluasi_rkpd', 'm_evaluasi_renja'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		$this->auth->restrict();
		$this->template->load('template','evaluasi/rkpd/view');
	}

	function get_table_data()
	{
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$tw = $this->input->post("tw");

		$html = $this->evaluasi_rkpd_func($tw, $tahun, "class='header_tw'", 'class="table-common tablesorter" style="width:99%"');
		echo json_encode(array("html" => $html['evaluasi']));
	}

	private function evaluasi_rkpd_func($triwulan, $tahun, $css_header_tw='', $css_table=''){
		$data['triwulan'] = $triwulan;
		$data['tahun'] = $tahun;
		$data['tahun_terakhir'] = $this->m_evaluasi_renja->get_max_tahun();
		$tahun_sebelum = $this->m_evaluasi_renja->get_less_tahun($tahun);
		if (empty($tahun_sebelum)) {
			$tahun_sebelum = $tahun;
		}
		$data['tahun_sebelum'] = $tahun_sebelum;

		$data['evaluasi_rkpd'] = $this->m_evaluasi_rkpd->get_rkpd_urusan_bidang($tahun, $triwulan);

		$data['css_header_tw'] = $css_header_tw;
		$data['css_table'] = $css_table;
		$data['evaluasi'] = $this->load->view('evaluasi/rkpd/table_rkpd', $data, TRUE);
		return $data;
	}

	function export($triwulan=NULL){
		if (empty($triwulan)) {
			return FALSE;
		}
		ini_set('memory_limit','-1');

		$this->auth->restrict();

		$tahun = $this->session->userdata('t_anggaran_aktif');

		$tahun_terakhir = $this->m_evaluasi_renja->get_max_tahun();
		$tahun_sebelum = $this->m_evaluasi_renja->get_less_tahun($tahun);
		if (empty($tahun_sebelum)) {
			$tahun_sebelum = $tahun;
		}

		$evaluasi_rkpd = $this->m_evaluasi_rkpd->get_rkpd_urusan_bidang($tahun, $triwulan);

		// Do export
		$this->load->library('Export_excel');
		$this->export_excel->create_header(array(
													"Evaluasi RKPD Triwulan ". $this->triwulan[$triwulan]["romawi"]
												)
											);

		$this->export_excel->create_header(array(
													"No",
													"KODE",
													NULL,
													NULL,
													NULL,
													"Urusan/Bidang Urusan Pemerintahan Daerah dan Program / Kegiatan",
													"Indikator Kinerja Program (Outcome) / Kegiatan(Output)",
													"Target Renstra SKPD Pada Tahun ". $tahun_terakhir,
													NULL,
								          "Realisasi Capaian Kinerja Renstra SKPD s./d. Renja SKPD Tahun Lalu (". $tahun_sebelum .")",
													NULL,
								          "Target Kinerja & Anggaran Renja SKPD Tahun Berjalan Yang Dievaluasi ". $tahun,
													NULL,
								          "Realisasi Kinerja Pada Triwulan",
													NULL,
													NULL,
													NULL,
													NULL,
								          NULL,
													NULL,
								          NULL,
								          "Realisasi Capaian Kinerja dan Anggaran Renja KSPD Yang Dievaluasi (". $tahun .")",
													NULL,
								          "Tingkat Capaian Kinerja & Anggaran Renja SKPD Yang Dievaluasi (". $tahun .")",
													NULL,
								          "Realisasi Kinerja & Anggaran Renstra SKPD s/d Tahun Berjalan (". $tahun .")",
													NULL,
								          "Tingkat Capaian Kinerja & Realisasi Anggaran Renstra SKPD s/d Tahun ". $tahun ." (%)",
													NULL,
								          "Unit SKPD Penanggung Jawab",
								          "Ket",
                          NULL,
								          NULL,
												)
											);
		$this->export_excel->create_header(array(
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
													NULL,
													"I",
													NULL,
													"II",
													NULL,
								          "III",
													NULL,
								          "IV",
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
								          NULL,
												)
											);
		$this->export_excel->create_header(array(
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													"K",
													"Rp",
													"K",
													"Rp",
													"K",
													"Rp",
													"K",
													"Rp",
													"K",
													"Rp",
													"",
													"Rp,",
													"K",
													"Rp",
													"K",
													"Rp",
													"K",
													"Rp",
													"K",
													"Rp",
													"K",
													"Rp",
                          NULL,
                          "5t",
                          "1t",
                          "R",
												)
											);

		$this->export_excel->merge_cell('A1','AG1');
		$this->export_excel->merge_cell('A2','A4');
		$this->export_excel->merge_cell('B2','E4');
		$this->export_excel->merge_cell('F2','F4');
		$this->export_excel->merge_cell('G2','G4');
		$this->export_excel->merge_cell('H2','I3');
		$this->export_excel->merge_cell('J2','K3');
		$this->export_excel->merge_cell('L2','M3');
		$this->export_excel->merge_cell('N2','U3');
		$this->export_excel->merge_cell('N3','O3');
		$this->export_excel->merge_cell('P3','Q3');
		$this->export_excel->merge_cell('R3','S3');
		$this->export_excel->merge_cell('T3','U3');
		$this->export_excel->merge_cell('V2','W3');
		$this->export_excel->merge_cell('X2','Y3');
		$this->export_excel->merge_cell('Z2','AA3');
		$this->export_excel->merge_cell('AB2','AC3');
		$this->export_excel->merge_cell('AD2','AD4');
		$this->export_excel->merge_cell('AE2','AG3');

		$no=0;
		$tot_tingkat_capaian_k = 0;
		$tot_tingkat_capaian_rp = 0;
		$tot_tingkat_capaian_total_k = 0;
		$tot_tingkat_capaian_total_rp = 0;
		$tot_count_k = 0;
		$tot_count_rp = 0;
		foreach ($evaluasi_rkpd['kode_urusan'] as $row_urusan) {
			$this->export_excel->set_row(array(
																				NULL,
																				$row_urusan->kd_urusan,
																				NULL,
																				NULL,
																				NULL,
																				$row_urusan->urusan,
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
																				NULL,
																				NULL,
																				NULL
																));

	    if (!empty($evaluasi_rkpd['kode_bidang'][$row_urusan->kd_urusan])) {
	      foreach ($evaluasi_rkpd['kode_bidang'][$row_urusan->kd_urusan] as $row_bidang) {
	        $var_tingkat_capaian_k[1] = 0;
	        $var_tingkat_capaian_rp[1] = 0;
	        $var_tingkat_capaian_total_k[1] = 0;
	        $var_tingkat_capaian_total_rp[1] = 0;
	        $count_k[1] = 0;
	        $count_rp[1] = 0;

	        $var_tingkat_capaian_k[2] = 0;
	        $var_tingkat_capaian_rp[2] = 0;
	        $var_tingkat_capaian_total_k[2] = 0;
	        $var_tingkat_capaian_total_rp[2] = 0;
	        $count_k[2] = 0;
	        $count_rp[2] = 0;

					$this->export_excel->set_row(array(
																						NULL,
																						$row_bidang->kd_urusan,
																						$row_bidang->kd_bidang,
																						NULL,
																						NULL,
																						$row_bidang->bidang,
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
																						NULL,
																						NULL,
																						NULL
																		));

	        $skpd_result = $this->m_evaluasi_rkpd->get_rkpd_skpd($tahun, $triwulan, $row_urusan->kd_urusan, $row_bidang->kd_bidang);
	        foreach ($skpd_result as $row_skpd) {
						$this->export_excel->set_row(array(
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							NULL,
																							$row_skpd->nama_skpd,
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
																							NULL,
																							NULL,
																							NULL
																			));

	          $prog_keg_result = $this->m_evaluasi_rkpd->get_rkpd_prog_keg($tahun, $triwulan, $row_urusan->kd_urusan, $row_bidang->kd_bidang, $row_skpd->id_skpd);
	          foreach ($prog_keg_result['evaluasi_prog_keg'] as $key => $row) {
	            if (!empty($prog_keg_result['evaluasi'][$row->id])) {
	              $row_indikator = $prog_keg_result['evaluasi'][$row->id][0];
	              $no++;

	              $var_tingkat_capaian_k[$row->is_prog_or_keg] += $row_indikator->tingkat_capaian_k;
	              $var_tingkat_capaian_rp[$row->is_prog_or_keg] += $row->tingkat_capaian_rp;
	              $var_tingkat_capaian_total_k[$row->is_prog_or_keg] += $row_indikator->tingkat_capaian_total_k;
	              $var_tingkat_capaian_total_rp[$row->is_prog_or_keg] += $row->tingkat_capaian_total_rp;
	              $count_k[$row->is_prog_or_keg]++;
	              $count_rp[$row->is_prog_or_keg]++;

								$this->export_excel->set_row(array(
																										$no,
																										$row->kd_urusan,
																										$row->kd_bidang,
																										$row->kd_program,
																										$row->kd_kegiatan,
																										$row->nama_prog_or_keg,
																										$row_indikator->indikator,
																										$row_indikator->target_akhir_renstra_k." ".$row_indikator->satuan,
																										FORMATTING::currency($row->target_akhir_renstra_rp),
																										$row_indikator->realisasi_kinerja_sebelum_k,
																										FORMATTING::currency($row->realisasi_kinerja_sebelum_rp),
																										$row_indikator->target_anggaran_berjalan_k,
																										FORMATTING::currency($row->target_anggaran_berjalan_rp),
																										@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][1]->realisasi_k,
																										FORMATTING::currency(@$evaluasi_renja['realisasi_evaluasi_renja_prog_keg'][$row->id][1]->realisasi_rp),
																										@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][2]->realisasi_k,
																										FORMATTING::currency(@$evaluasi_renja['realisasi_evaluasi_renja_prog_keg'][$row->id][2]->realisasi_rp),
																										@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][3]->realisasi_k,
																										FORMATTING::currency(@$evaluasi_renja['realisasi_evaluasi_renja_prog_keg'][$row->id][3]->realisasi_rp),
																										@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][4]->realisasi_k,
																										FORMATTING::currency(@$evaluasi_renja['realisasi_evaluasi_renja_prog_keg'][$row->id][4]->realisasi_rp),
																										$row_indikator->realisasi_kinerja_berjalan_k,
																						        FORMATTING::currency($row->realisasi_kinerja_berjalan_rp),
																						        $row_indikator->tingkat_capaian_k,
																						        FORMATTING::currency($row->tingkat_capaian_rp),
																						        $row_indikator->realisasi_kinerja_k,
																						        FORMATTING::currency($row->realisasi_kinerja_rp),
																						        $row_indikator->tingkat_capaian_total_k,
																						        $row->tingkat_capaian_total_rp,
																						        $row->penanggung_jawab,
				                                            $row_indikator->status_5t,
				                                            $row_indikator->status_1t,
				                                            $row_indikator->status_r
																						));

	              if ($prog_keg_result['jumlah_evaluasi'][$row->id] > 1) {
	                for ($i=1; $i < $prog_keg_result['jumlah_evaluasi'][$row->id]; $i++) {
	                  $row_indikator = $prog_keg_result['evaluasi'][$row->id][$i];

	                  $var_tingkat_capaian_k[$row->is_prog_or_keg] += $row_indikator->tingkat_capaian_k;
	                  $var_tingkat_capaian_total_k[$row->is_prog_or_keg] += $row_indikator->tingkat_capaian_total_k;
	                  $count_k[$row->is_prog_or_keg]++;

										$this->export_excel->set_row(array(
																												NULL,
																												NULL,
																												NULL,
																												NULL,
																												NULL,
																												NULL,
																												$row_indikator->indikator,
																												$row_indikator->target_akhir_renstra_k." ".$row_indikator->satuan,
																												NULL,
																												$row_indikator->realisasi_kinerja_sebelum_k,
																												NULL,
																												$row_indikator->target_anggaran_berjalan_k,
																												NULL,
																												@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][1]->realisasi_k,
																												NULL,
																												@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][2]->realisasi_k,
																												NULL,
																												@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][3]->realisasi_k,
																												NULL,
																												@$evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][4]->realisasi_k,
																												NULL,
																												$row_indikator->realisasi_kinerja_berjalan_k,
																								        NULL,
																								        $row_indikator->tingkat_capaian_k,
																								        NULL,
																								        $row_indikator->realisasi_kinerja_k,
																								        NULL,
																								        $row_indikator->tingkat_capaian_total_k,
																								        NULL,
																								        NULL,
				                                                $row_indikator->status_5t,
				                                                $row_indikator->status_1t,
				                                                $row_indikator->status_r
																								));
	                }

									// Merge cel if indikator more than 1
									$row_merge = $this->export_excel->get_last_row() - $prog_keg_result['jumlah_evaluasi'][$row->id] + 1;
									$this->export_excel->merge_cell('A'.$row_merge,'A'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('B'.$row_merge,'B'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('C'.$row_merge,'C'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('D'.$row_merge,'D'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('E'.$row_merge,'E'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('F'.$row_merge,'F'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('I'.$row_merge,'I'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('K'.$row_merge,'K'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('M'.$row_merge,'M'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('O'.$row_merge,'O'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('Q'.$row_merge,'Q'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('S'.$row_merge,'S'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('U'.$row_merge,'U'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('W'.$row_merge,'W'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('Y'.$row_merge,'Y'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('AA'.$row_merge,'AA'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('AC'.$row_merge,'AC'.$this->export_excel->get_last_row());
									$this->export_excel->merge_cell('AD'.$row_merge,'AD'.$this->export_excel->get_last_row());
	              }
	            }

	            if (empty($prog_keg_result['evaluasi_prog_keg'][$key+1]) || (!empty($prog_keg_result['evaluasi_prog_keg'][$key+1]) && $prog_keg_result['evaluasi_prog_keg'][$key+1]->is_prog_or_keg==1)) {
									$this->export_excel->set_row(array(
																											"Rata-rata Capaian Kinerja (%)",
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
																							        $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_k[2], $count_k[2]),
																							        $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_rp[2], $count_rp[2]),
																							        NULL,
																							        NULL,
																							        $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_total_k[2], $count_k[2]),
																							        $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_total_rp[2], $count_rp[2]),
																							        NULL,
																							        NULL,
					                                            NULL,
																							        NULL,
																							));
									$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row());
									$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

									$this->export_excel->set_row(array(
																											"Predikat Kinerja",
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
																							        $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_k[2], $count_k[2]),
																							        $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_rp[2], $count_rp[2]),
																							        NULL,
																							        NULL,
																							        $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_total_k[2], $count_k[2]),
																							        $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_total_rp[2], $count_rp[2]),
																							        NULL,
																							        NULL,
					                                            NULL,
																							        NULL,
																							));
									$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row());
									$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

							    $var_tingkat_capaian_k[2] = 0;
							    $var_tingkat_capaian_rp[2] = 0;
							    $var_tingkat_capaian_total_k[2] = 0;
							    $var_tingkat_capaian_total_rp[2] = 0;
							    $count_k[2] = 0;
							    $count_rp[2] = 0;
	            }
	          }
	        }

					$this->export_excel->set_row(array(
																							"Total Rata-rata Capaian Kinerja dan Anggaran Dari Seluruh Program Dalam Bidang Urusan (%)",
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
																							$this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_k[1], $count_k[1]),
																							$this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_rp[1], $count_rp[1]),
																							NULL,
																							NULL,
																							$this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_total_k[1], $count_k[1]),
																							$this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_total_rp[1], $count_rp[1]),
																							NULL,
																							NULL,
					                                    NULL,
					                                    NULL,
																			));
					$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row());
					$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$this->export_excel->set_row(array(
																							"Predikat Kinerja Dari Seluruh Program Dalam Bidang Urusan",
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
																							$this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_k[1], $count_k[1]),
																							$this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_rp[1], $count_rp[1]),
																							NULL,
																							NULL,
																							$this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_total_k[1], $count_k[1]),
																							$this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_total_rp[1], $count_rp[1]),
																							NULL,
																							NULL,
					                                    NULL,
					                                    NULL,
																			));
					$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row());
					$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$tot_tingkat_capaian_k += $var_tingkat_capaian_k[1];
          $tot_tingkat_capaian_rp += $var_tingkat_capaian_rp[1];
          $tot_tingkat_capaian_total_k += $var_tingkat_capaian_total_k[1];
          $tot_tingkat_capaian_total_rp += $var_tingkat_capaian_total_rp[1];
          $tot_count_k += $count_k[1];
          $tot_count_rp += $count_rp[1];
	      }
	    }
		}

		$this->export_excel->set_row(array(
																				"Total Rata-rata Capaian Kinerja dan Anggaran Dari Seluruh Program (%)",
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
																				$this->m_evaluasi_renja->hitung_capaian_lap($tot_tingkat_capaian_k, $tot_count_k),
																				$this->m_evaluasi_renja->hitung_capaian_lap($tot_tingkat_capaian_rp, $tot_count_rp),
																				NULL,
																				NULL,
																				$this->m_evaluasi_renja->hitung_capaian_lap($tot_tingkat_capaian_total_k, $tot_count_k),
																				$this->m_evaluasi_renja->hitung_capaian_lap($tot_tingkat_capaian_total_rp, $tot_count_rp),
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																));
		$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row().':AG'.$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CFCFCF'))));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		$this->export_excel->set_row(array(
																				"Predikat Kinerja Dari Seluruh Program",
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
																				$this->m_evaluasi_renja->predikat_capaian_lap($tot_tingkat_capaian_k, $tot_count_k),
																				$this->m_evaluasi_renja->predikat_capaian_lap($tot_tingkat_capaian_rp, $tot_count_rp),
																				NULL,
																				NULL,
																				$this->m_evaluasi_renja->predikat_capaian_lap($tot_tingkat_capaian_total_k, $tot_count_k),
																				$this->m_evaluasi_renja->predikat_capaian_lap($tot_tingkat_capaian_total_rp, $tot_count_rp),
																				NULL,
																				NULL,
																				NULL,
																				NULL,
																));
		$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row().':AG'.$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CFCFCF'))));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle('A'.$this->export_excel->get_last_row(),'W'.$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		$this->export_excel->getActiveSheet()->getStyle("A1:AG".$this->export_excel->get_last_row())->applyFromArray($this->export_excel->default_border);

		$this->export_excel->filename = "Evaluasi RKPD Triwulan ". $this->triwulan[$triwulan]["romawi"] ." ";

		$this->export_excel->set_readonly();
		$this->export_excel->execute();
	}
}
