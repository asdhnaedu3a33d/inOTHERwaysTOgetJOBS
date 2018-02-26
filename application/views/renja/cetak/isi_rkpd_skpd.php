<?php
	$max_col_keg=1;
	$total_nomrenja = 0;
	$total_nomrenja_thndpn = 0;
	$p=0;
	$k=0;
?>
<?php
	foreach ($urusan as $row_urusan) {
		echo "
		<tr bgcolor=\"#78cbfd\">
			<td>".$row_urusan->kd_urusan."</td>
			<td></td>
			<td></td>
			<td></td>
			<td colspan=\"4 \">
				<strong>".strtoupper($row_urusan->nama_urusan)."</strong>
			</td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nominal,2)."</td>
			<td colspan=\"2\"></td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nominal_thndpn,2)."</td>

		</tr>";
		$total_nomrenja += $row_urusan->sum_nominal;
		$total_nomrenja_thndpn += $row_urusan->sum_nominal_thndpn;

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
				<td colspan=\"4 \">
					<strong>".strtoupper($row_bidang->nama_bidang)."</strong>
				</td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nominal,2)."</td>
				<td colspan=\"2\"></td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nominal_thndpn,2)."</td>
			</tr>";

			$program = $this->m_renja_trx->get_program_skpd_4_cetak_v2($id_skpd,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);


			foreach ($program as $row) {
				$result = $this->m_renja_trx->get_kegiatan_skpd_4_cetak($row->id);
				$kegiatan = $result->result();
				$indikator_program = $this->m_renja_trx->get_indikator_prog_keg($row->id, FALSE, TRUE);
				$temp = $indikator_program->result();
				$total_temp = $indikator_program->num_rows();
				$p++;

				$col_indikator=1;
				$go_3_keg = FALSE;
				$total_for_iteration = $total_temp;
				if ($total_temp > $max_col_keg) {
					$total_temp = $max_col_keg;
					$go_3_keg = TRUE;
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
						echo $temp[0]->indikator;
					?>
				</td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi;?></td>
				<td align="center">
					<?php echo $temp[0]->target." ".$temp[0]->satuan_target;?>
				</td>
				<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nominal,2);?></td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->catatan;?></td>
				<td align="center">
					<?php echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target_thndpn;?>
				</td>
				<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nominal_thndpn,2);?></td>
			</tr>
		<?php
				if ($total_for_iteration > 1) {
					for ($i=1; $i < $total_for_iteration; $i++) {
						$col_indikator++;
		?>
				<tr>
		<?php
						if ($go_3_keg && $col_indikator > $max_col_keg) {
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
							echo $temp[$i]->indikator;
						?>
					</td>
		<?php
						if ($go_3_keg && $col_indikator > $max_col_keg) {
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
						if ($go_3_keg && $col_indikator > $max_col_keg) {
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
						if ($go_3_keg && $col_indikator > $max_col_keg) {
		?>
					<td style="border-top: 0;border-bottom: 0;" ></td>
		<?php
							}
		?>
				</tr>
		<?php
				 	}
				 }

				foreach ($kegiatan as $row) {
					$indikator_kegiatan = $this->m_renja_trx->get_indikator_prog_keg($row->id, FALSE, TRUE);
					$temp = $indikator_kegiatan->result();
					$total_temp = $indikator_kegiatan->num_rows();
					$k++;

					$go_3_keg = FALSE;
					$col_indikator_keg=1;
					$total_for_iteration = $total_temp;
					if ($total_temp > $max_col_keg) {
						$total_temp = $max_col_keg;
						$go_3_keg = TRUE;
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
						echo $temp[0]->indikator;
					?>
					</td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi;?></td>
					<td align="center">
					<?php
						echo $temp[0]->target." ".$temp[0]->satuan_target;
					?>
					</td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nominal,2);?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->catatan;?></td>
					<td align="center">
					<?php
						echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target_thndpn;
					?>
					</td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nominal_thndpn,2);?></td>
				</tr>
		<?php
					if ($total_for_iteration > 1) {
						for ($i=1; $i < $total_for_iteration; $i++) {
							$col_indikator_keg++;
		?>
				<tr>
		<?php
							if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
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
							echo $temp[$i]->indikator;
						?>
					</td>
		<?php
							if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
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
							if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
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
							if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
		?>
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
	<tr bgcolor="#999999">
        	<td colspan="4"></td>
			<td colspan="4">
            	<strong>
                	Jumlah Program
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo $p." Program" ;?>
                <strong>
            </td>
			<td colspan="2">
            	<strong>
                	Jumlah Kegiatan
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo $k." Kegiatan"; ?>
                </strong>
            </td>
		</tr>
        
	<tr bgcolor="#999999">
        	<td colspan="4"></td>
			<td colspan="4">
            	<strong>
                	Total Nominal Tahun Ini
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo Formatting::currency($total_nomrenja,2) ;?>
                <strong>
            </td>
			<td colspan="2">
            	<strong>
                	Total Nominal Tahun Depan
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo Formatting::currency($total_nomrenja_thndpn,2); ?>
                </strong>
            </td>
		</tr>