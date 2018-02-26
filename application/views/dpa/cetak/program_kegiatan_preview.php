<?php
	$tahun_sekarang=$this->session->userdata('t_anggaran_aktif');
	$max_col_keg=1;
?>
			<thead>
				<tr>
					<th rowspan="3" colspan="4">Kode</th>
					<th rowspan="3" >Program dan Kegiatan</th>
					<th rowspan="3" >Kriteria Keberhasilan</th>
					<th rowspan="3" >Ukuran Keberhasilan</th>
					<th colspan="12" >Kinerja Bulanan</th>
					<th rowspan="3" >Total</th>
				</tr>
				<tr>
					<th>Bulan 1 (Rp.)</th>
          <th>Bulan 2 (Rp.)</th>
          <th>Bulan 3 (Rp.)</th>
          <th>Bulan 4 (Rp.)</th>
					<th>Bulan 5 (Rp.)</th>
          <th>Bulan 6 (Rp.)</th>
          <th>Bulan 7 (Rp.)</th>
          <th>Bulan 8 (Rp.)</th>
					<th>Bulan 9 (Rp.)</th>
          <th>Bulan 10 (Rp.)</th>
          <th>Bulan 11 (Rp.)</th>
          <th>Bulan 12 (Rp.)</th>
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
						<td colspan=\"16 \">
							<strong>".strtoupper($row_urusan->nama_urusan)."</strong>
						</td>

					</tr>";

					$bidang = $this->db->query("
						SELECT t.*,b.Nm_Bidang as nama_bidang from (
						SELECT
							pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
							SUM(keg.nominal_1) AS sum_nominal_1,
							SUM(keg.nominal_2) AS sum_nominal_2,
							SUM(keg.nominal_3) AS sum_nominal_3,
							SUM(keg.nominal_4) AS sum_nominal_4,
							SUM(keg.nominal_5) AS sum_nominal_5,
							SUM(keg.nominal_6) AS sum_nominal_6,
							SUM(keg.nominal_7) AS sum_nominal_7,
							SUM(keg.nominal_8) AS sum_nominal_8,
							SUM(keg.nominal_9) AS sum_nominal_9,
							SUM(keg.nominal_10) AS sum_nominal_10,
							SUM(keg.nominal_11) AS sum_nominal_11,
							SUM(keg.nominal_12) AS sum_nominal_12
						FROM
							(SELECT * FROM tx_dpa_prog_keg WHERE is_prog_or_keg=1) AS pro
						INNER JOIN
							(SELECT * FROM tx_dpa_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
						WHERE
							keg.id_skpd=".$id_skpd."
							AND keg.tahun=".$ta."
						AND keg.kd_urusan = ".$row_urusan->kd_urusan."
						GROUP BY pro.kd_urusan,kd_bidang
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
							<td colspan=\"16 \">
								<strong>".strtoupper($row_bidang->nama_bidang)."</strong>
							</td>
						</tr>";

					$program = $this->m_dpa->get_program_skpd_4_cetak($id_skpd,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);

					foreach ($program as $row) {
						$result = $this->m_kendali->get_kegiatan_dpa_4_cetak($row->id,$tahun_sekarang);
						$kegiatan = $result->result();
						$indikator_program = $this->m_kendali->get_indikator_prog_keg_dpa($row->id, FALSE, TRUE);
						$temp = $indikator_program->result();
						$total_temp = $indikator_program->num_rows();

						$col_indikator=1;
						$go_2_keg = FALSE;
						$total_for_iteration = $total_temp;
						if($total_temp > $max_col_keg){
							$total_temp = $max_col_keg;
							$go_2_keg = TRUE;
						}

						$nom_total = $row->sum_nominal_1+$row->sum_nominal_2+$row->sum_nominal_3+$row->sum_nominal_4+$row->sum_nominal_5+$row->sum_nominal_6+$row->sum_nominal_7+$row->sum_nominal_8+$row->sum_nominal_9+$row->sum_nominal_10+$row->sum_nominal_11+$row->sum_nominal_12;
				?>
				<tr bgcolor="#FF9933">
					<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_urusan; ?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_bidang; ?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_program; ?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_kegiatan; ?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
					<td>
						<?php
                            echo $temp[0]->indikator;
                        ?>
                    </td>
										<td>
											<?php
											echo $temp[0]->indikator." ".$temp[0]->target." ".$temp[0]->satuan_target;
											?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($row->sum_nominal_1); ?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($row->sum_nominal_2); ?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($row->sum_nominal_3); ?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($row->sum_nominal_4); ?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($row->sum_nominal_5); ?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($row->sum_nominal_6); ?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($row->sum_nominal_7); ?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($row->sum_nominal_8); ?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($row->sum_nominal_9); ?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($row->sum_nominal_10); ?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($row->sum_nominal_11); ?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($row->sum_nominal_12); ?>
										</td>
										<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
											<?php echo Formatting::currency($nom_total); ?>
										</td>
				</tr>
				<?php
					if ($total_for_iteration > 1) {
						for ($i=1; $i < $total_for_iteration; $i++) {
							$col_indikator++;
				?>
					<tr bgcolor="#FF9933">
							<td>
								<?php
									echo $temp[$i]->indikator;
								?>
							</td>
							<td>
								<?php
									echo $temp[$i]->indikator." ".$temp[$i]->target." ".$temp[$i]->satuan_target;
								?>
							</td>
						</tr>
					<?php
						}
					}
					foreach ($kegiatan as $row) {
							$indikator_kegiatan = $this->m_kendali->get_indikator_prog_keg_dpa($row->id, FALSE, TRUE);
							$temp = $indikator_kegiatan->result();
							$total_temp = $indikator_kegiatan->num_rows();

							$go_2_keg = FALSE;
							$col_indikator_keg=1;
							$total_for_iteration = $total_temp;
							if ($total_temp > $max_col_keg) {
								$total_temp = $max_col_keg;
								$go_2_keg = TRUE;
							}

							$nom_total_keg = $row->nominal_1+$row->nominal_2+$row->nominal_3+$row->nominal_4+$row->nominal_5+$row->nominal_6+$row->nominal_7+$row->nominal_8+$row->nominal_9+$row->nominal_10+$row->nominal_11+$row->nominal_12;
					?>
					<tr>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_urusan; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_bidang; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_program; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_kegiatan; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
						<td>
							<?php
								echo $temp[0]->indikator;
							?>
						</td>
						<td>
							<?php
								echo $temp[0]->indikator." ".$temp[0]->target." ".$temp[0]->satuan_target;
							?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($row->nominal_1); ?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($row->nominal_2); ?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($row->nominal_3); ?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($row->nominal_4); ?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($row->nominal_5); ?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($row->nominal_6); ?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($row->nominal_7); ?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($row->nominal_8); ?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($row->nominal_9); ?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($row->nominal_10); ?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($row->nominal_11); ?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($row->nominal_12); ?>
						</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>">
							<?php echo Formatting::currency($nom_total_keg); ?>
						</td>
					</tr>
					<?php
						if ($total_for_iteration > 1) {
						for ($i=1; $i < $total_for_iteration; $i++) {
							$col_indikator_keg++;
					?>
						<tr>
							<td>
								<?php
									echo $temp[$i]->indikator;
								?>
							</td>
							<td>
								<?php
									echo $temp[$i]->indikator." ".$temp[$i]->target." ".$temp[$i]->satuan_target;
								?>
							</td>
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
