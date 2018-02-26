<?php
	$tahun_sekarang=$this->session->userdata('t_anggaran_aktif'); 
	$nama_skpd = $this->session->userdata('nama_skpd');
	$max_col_keg=1;
?>
			<thead>
				<tr>
					<th rowspan="3" colspan="4">KODE</th>
					<th rowspan="3" >PROGRAM DAN KEGIATAN</th>
					<th rowspan="2" colspan="2">INDIKATOR KINERJA PROGRAM / KEGIATAN</th>
					<th colspan="6">RENCANA TAHUN (<?php echo $tahun_sekarang;?>)</th>
					<th colspan="4">PRAKIRAAN RENCANA TAHUN (<?php echo $tahun_sekarang+1;?>)</th>
					<th rowspan="3">KESESUAIAN</th>
					<th rowspan="3">HASIL PENGENDALIAN</th>
					<th rowspan="3">TINDAK LANJUT</th>
					<th rowspan="3">HASIL TINDAK LANJUT</th>
                    <th rowspan="3">AKSI</th>
				</tr>
				<tr>
					<th colspan = "2">LOKASI</th>
					<th colspan = "2">TARGET CAPAIAN</th>
					<th colspan = "2">DANA</th>
					<th colspan = "2">TARGET CAPAIAN KINERJA</th>
					<th colspan = "2">DANA</th>
				</tr>
				<tr>
					<th>RENJA</th>
					<th>RKA</th>
					<th>RENJA</th>
					<th>RKA</th>
					<th>RENJA</th>
					<th>RKA</th>
					<th>RENJA (Rp.)</th>
					<th>RKA (Rp.)</th>
					<th>RENJA</th>
					<th>RKA</th>
					<th>RENJA (Rp.)</th>
					<th>RKA (Rp.)</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ($urusan as $row_urusan) {
					echo "
					<tr bgcolor=\"#78cbfd\">
						<td>".$row_urusan->kd_urusan."</td>
						<td></td>
						<td></td>
						<td></td>
						<td colspan=\"7 \">
							<strong>".strtoupper($row_urusan->nama_urusan)."</strong>
						</td>
						<td align=\"right\">".Formatting::currency($row_urusan->sum_nominal)."</td>
						<td align=\"right\">".Formatting::currency($row_urusan->sum_nominal_thndpn)."</td>
						<td colspan=\"2\"></td>
						<td align=\"right\">".Formatting::currency($row_urusan->sum_nomrenja)."</td>
						<td align=\"right\">".Formatting::currency($row_urusan->sum_nomrenja_thndpn)."</td>
						<td colspan=\"5\"></td>
					</tr>";
					
					$bidang = $this->db->query("
						SELECT t.*,b.Nm_Bidang as nama_bidang from (
						SELECT pro.*, 
							   SUM(keg.nominal) AS sum_nominal, 
							   SUM(keg.nominal_thndpn) AS sum_nominal_thndpn,
							   SUM(keg.nomrenja) AS sum_nomrenja,
							   SUM(keg.nomrenja_thndpn) AS sum_nomrenja_thndpn
						FROM 
							(SELECT a.`id`, a.`tahun`, a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`, a.`nama_prog_or_keg`,
									a.`nominal`, a.`nominal_thndpn`, b.`nominal` AS nomrenja, b.`nominal_thndpn` AS nomrenja_thndpn, a.`id_skpd`,
									a.kesesuaian,a.hasil_kendali,a.tindak_lanjut,a.hasil_tl,a.id_status 
							 FROM tx_rka_prog_keg_perubahan a
							 LEFT JOIN t_renja_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
										  AND a.`kd_bidang`=b.`kd_bidang`
										  AND a.`kd_program`=b.`kd_program`
										  AND a.`kd_kegiatan`=b.`kd_kegiatan`
										  AND a.`is_prog_or_keg`=b.`is_prog_or_keg` 
							 WHERE a.is_prog_or_keg=1
							 GROUP BY a.`id`) AS pro 
						INNER JOIN 
							(SELECT a.`id`, a.`id_skpd`,a.`tahun`, a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`, a.`parent`,
									a.`nominal`, a.`nominal_thndpn`, b.`nominal` AS nomrenja, b.`nominal_thndpn` AS nomrenja_thndpn 
							 FROM tx_rka_prog_keg_perubahan a
							 LEFT JOIN t_renja_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
										  AND a.`kd_bidang`=b.`kd_bidang`
										  AND a.`kd_program`=b.`kd_program`
										  AND a.`kd_kegiatan`=b.`kd_kegiatan`
										  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
							 WHERE a.is_prog_or_keg=2
							 GROUP BY a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`,a.`id`) AS keg ON keg.parent=pro.id 
						WHERE 
							keg.id_skpd=".$id_skpd."
						AND keg.tahun= ".$ta."
						AND keg.kd_urusan = ".$row_urusan->kd_urusan."
						GROUP BY pro.kd_urusan, pro.kd_bidang
						ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
						) t
						left join m_bidang b
						on t.kd_urusan = b.Kd_Urusan and t.kd_bidang = b.Kd_Bidang
					")->result();
					
					foreach ($bidang as $row_bidang) {
						echo "
						<tr bgcolor=\"#00FF33\">
							<td>".$row_urusan->kd_urusan."</td>
							<td>".$row_bidang->kd_bidang."</td>
							<td></td>
							<td></td>
							<td colspan=\"7 \">
								<strong>".strtoupper($row_bidang->nama_bidang)."</strong>
							</td>
							<td align=\"right\">".Formatting::currency($row_bidang->sum_nominal)."</td>
							<td align=\"right\">".Formatting::currency($row_bidang->sum_nominal_thndpn)."</td>
							<td colspan=\"2\"></td>
							<td align=\"right\">".Formatting::currency($row_bidang->sum_nomrenja)."</td>
							<td align=\"right\">".Formatting::currency($row_bidang->sum_nomrenja_thndpn)."</td>
							<td colspan=\"5\"></td>
						</tr>";
					
					$program = $this->m_kendali_perubahan->get_program_rka_4_cetak($id_skpd,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);
					foreach ($program as $row) {
						$result = $this->m_kendali_perubahan->get_kegiatan_rka_4_cetak($row->id,$tahun_sekarang);
						$kegiatan = $result->result();
						//echo $this->db->last_query();
						//$indikator_program = $this->m_kendali_perubahan->get_indikator_prog_keg($row->id, FALSE, TRUE);
						$get_id_renja = $this->m_kendali_perubahan->get_id_renja($row->id_skpd, $row->tahun, $row->kd_urusan,
						                     $row->kd_bidang, $row->kd_program);
						//echo "renja".$this->db->last_query()."<BR>";
						$get_id_rka = $this->m_kendali_perubahan->get_id_rka($row->id_skpd, $row->tahun, $row->kd_urusan,
						                     $row->kd_bidang, $row->kd_program);
					    //echo "rka".$this->db->last_query()."<BR>";
						$indikator_program_renja = $this->m_kendali_perubahan->get_indikator_renja($get_id_renja);
						$indikator_program_rka = $this->m_kendali_perubahan->get_indikator_rka($get_id_rka);
						//echo "data ".$this->db->last_query()."<BR>";
						$temp = $indikator_program_rka->result();
						$temp1 = $indikator_program_renja->result();
						$total_temp = $indikator_program_rka->num_rows();
						$total_temp1 = $indikator_program_renja->num_rows();
						
						$col_indikator=1;
						$go_2_keg = FALSE;
						$total_for_iteration = $total_temp;
						if($total_temp > $max_col_keg){
							$total_temp = $max_col_keg;
							$go_2_keg = TRUE;
						}
				?>
					<tr bgcolor="#FF9933">
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_urusan; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_bidang; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_program; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
						<td>
							<?php
								echo $temp1[0]->indikator;
							?>
						</td>
						<td>
							<?php
								echo $temp[0]->indikator;
							?>
						</td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
						<td align="center">
							<?php echo $temp1[0]->target." ".$temp1[0]->satuan_target;?>
						</td>
						<td align="center">
							<?php echo $temp[0]->target." ".$temp[0]->satuan_target;?>
						</td>
						<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nomrenja);?></td>
						<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nominal);?></td>
						<td align="center">
							<?php echo $temp1[0]->target_thndpn." ".$temp1[0]->satuan_target;?>
						</td>
						<td align="center">
							<?php echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target;?>
						</td>
						<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nomrenja_thndpn);?></td>
						<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nominal_thndpn);?></td>
						<td rowspan="<?php echo $total_temp; ?>" style="border-bottom: 0;">&nbsp;</td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                        <a href="javascript:void(0)" idP="<?php echo $row->id; ?>" class="icon-pencil edit-program" title="Kendali Program"/>
                        </td>
					</tr>
					<?php 
						if ($total_for_iteration > 1) {			
						for ($i=1; $i < $total_for_iteration; $i++) { 
							$col_indikator++;
					?>
						<tr>
							<?php
								if ($go_2_keg && $col_indikator > $max_col_keg) { 
							?>
								<td style="border-top: 0;border-bottom: 0;" ></td>
								<td style="border-top: 0;border-bottom: 0;" ></td>
								<td style="border-top: 0;border-bottom: 0;" ></td>
								<td style="border-top: 0;border-bottom: 0;" ></td>
								<td style="border-top: 0;border-bottom: 0;" ></td>
							<?php
								}
							?>
							<td>
                            	<?php
									echo $temp1[$i]->indikator;
								?>
                            </td>
							<td>
								<?php
									echo $temp[$i]->indikator;
								?>
							</td>
							<?php
								if ($go_2_keg && $col_indikator > $max_col_keg) {
							?>
								<td style="border-top: 0;border-bottom: 0;" ></td>
							<?php
								}
							?>
							<td align="center">
								<?php
									echo $temp[$i]->target." ".$temp[$i]->satuan_target;
								?>
							</td>
								<?php
									if ($go_2_keg && $col_indikator > $max_col_keg) {
								?>
									<td style="border-top: 0;border-bottom: 0;" ></td>
									<td style="border-top: 0;border-bottom: 0;" ></td>
								<?php
									}
								?>
							<td align="center">
								<?php
									echo $temp[$i]->target_thndpn." ".$temp[$i]->satuan_target_thndpn;
								?>
							</td>
								<?php
									if ($go_2_keg && $col_indikator > $max_col_keg) {
								?>
							<td style="border-top: 0;border-bottom: 0;" ></td>
                            <td style="border-top: 0;border-bottom: 0;" ></td>
                            <td style="border-top: 0;border-bottom: 0;" ></td>
                            <td style="border-top: 0;border-bottom: 0;" ></td>
                            <td style="border-top: 0;border-bottom: 0;" ></td>
                            <td style="border-top: 0;border-bottom: 0;" ></td>
                            <td style="border-top: 0;border-bottom: 0;" ></td>
                            <td style="border-top: 0;border-bottom: 0;" ></td>
                            <td style="border-top: 0;border-bottom: 0;" ></td>
                            <td style="border-top: 0;border-bottom: 0;" ></td>
						<?php
											}
						?>
						</tr>
					<?php
							}
						}

						foreach ($kegiatan as $row) {						
						//$indikator_kegiatan = $this->m_kendali->get_indikator_prog_keg($row->id, FALSE, TRUE);
						//$indikator_kegiatan = $this->m_kendali->get_indikator_kegiatan($row->id_skpd, $row->tahun, $row->kd_urusan,
											  //$row->kd_bidang, $row->kd_program, $row->kd_kegiatan);
						$get_id_renja1 = $this->m_kendali_perubahan->get_id_renja1($row->id_skpd, $row->tahun, $row->kd_urusan,
										 $row->kd_bidang, $row->kd_program , $row->kd_kegiatan );
						//echo "renja".$this->db->last_query()."<BR>";
						$get_id_rka1 = $this->m_kendali_perubahan->get_id_rka1($row->id_skpd, $row->tahun, $row->kd_urusan,
										 $row->kd_bidang, $row->kd_program, $row->kd_kegiatan);
						//echo "rka".$this->db->last_query()."<BR>";
						$indikator_kegiatan_renja = $this->m_kendali_perubahan->get_indikator_renja($get_id_renja1);
						$indikator_kegiatan_rka = $this->m_kendali_perubahan->get_indikator_rka($get_id_rka1);
											  
						$temp = $indikator_kegiatan_rka->result();	
						$temp1 = $indikator_kegiatan_renja->result();			
						$total_temp = $indikator_kegiatan_rka->num_rows();
						$total_temp1 = $indikator_kegiatan_renja->num_rows();
						//echo $this->db->last_query()."<BR>";

						$go_2_keg = FALSE;
						$col_indikator_keg=1;
						$total_for_iteration = $total_temp;
						if ($total_temp > $max_col_keg) {
							$total_temp = $max_col_keg;
							$go_2_keg = TRUE;
						}
					?>
					<tr>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_urusan; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_bidang; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_program; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
						<td>
							<?php
								echo $temp1[0]->indikator;
							?>
						</td>
						<td>
							<?php
								echo $temp[0]->indikator;
							?>
						</td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasirenja;?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi;?></td>
						<td align="center">
							<?php
								echo $temp1[0]->target." ".$temp1[0]->satuan_target;
							?>
						</td>
						<td align="center">
							<?php
								echo $temp[0]->target." ".$temp[0]->satuan_target;
							?>
						</td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nomrenja);?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nominal);?></td>
						<td align="center">
							<?php
								echo $temp1[0]->target_thndpn." ".$temp1[0]->satuan_target_thndpn;
							?>
						</td>
						<td align="center">
							<?php
								echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target_thndpn;
							?>
						</td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nomrenja_thndpn);?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nominal_thndpn);?></td>
						<td rowspan="<?php echo $total_temp; ?>" align="right" style="border-bottom: 0;" >&nbsp;</td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" >-</td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" >-</td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" >-</td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                        <a href="javascript:void(0)" idP="<?php echo $row->id; ?>" class="icon-pencil edit-kegiatan" title="Kendali Kegiatan"/>
                        </td>
					</tr>
					<?php
					if ($total_for_iteration > 1) {
						for ($i=1; $i < $total_for_iteration; $i++) { 					
							$col_indikator_keg++;
					?>
						<tr>
					<?php
						if ($go_2_keg && $col_indikator_keg > $max_col_keg) {
					?>
						<td style="border-top: 0;border-bottom: 0;" ></td>
						<td style="border-top: 0;border-bottom: 0;" ></td>
						<td style="border-top: 0;border-bottom: 0;" ></td>
						<td style="border-top: 0;border-bottom: 0;" ></td>
						<td style="border-top: 0;border-bottom: 0;" ></td>
					<?php
						}
					?>
					<td>
                    	<?php
							echo $temp1[$i]->indikator;
						?>
                    </td>
					<td>
						<?php
							echo $temp[$i]->indikator;
						?>
					</td>
					<?php
						if ($go_2_keg && $col_indikator_keg > $max_col_keg) {
					?>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
					<?php
						}
					?>
					<td align="center">
                    	<?php
							echo $temp1[$i]->target." ".$temp1[$i]->satuan_target;
						?>
                    </td>
					<td align="center">
						<?php
							echo $temp[$i]->target." ".$temp[$i]->satuan_target;
						?>
					</td>
					<?php
						if ($go_2_keg && $col_indikator_keg > $max_col_keg) {
					?>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
					<?php
						}
					?>
					<td align="center">
                    	<?php
							echo $temp1[$i]->target_thndpn." ".$temp1[$i]->satuan_target_thndpn;
						?>
                    </td>
					<td align="center">
						<?php
							echo $temp[$i]->target_thndpn." ".$temp[$i]->satuan_target_thndpn;
						?>
					</td>
					<?php
						if ($go_2_keg && $col_indikator_keg > $max_col_keg) {
					?>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
                            <td style="border-top: 0;border-bottom: 0;" ></td>
					<?php
						}
					?>
					</tr>
			<?php
						}
						}
					}
				}
			}
		}
		
			?>
			</tbody>