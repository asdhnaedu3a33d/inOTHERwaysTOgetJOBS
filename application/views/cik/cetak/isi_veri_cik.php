<?php
	$max_col_keg=1;
?>
	<?php
		foreach($program as $row){
		if($row->id == ""){
			echo "<tr><td colspan='13' align='center'><strong>Data Belum Terisikan..</strong></td></tr>";
		}else{
			$result = $this->m_cik->get_kegiatan_cik_4_cetak($row->id, $bulan);
			$cik_pro_keg = (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100;
			$default_rencana = 100.00;
			$kegiatan = $result->result();
			$indikator_program = $this->m_cik->get_indikator_prog_keg_preview($row->id, $bulan, FALSE, TRUE);
			$temp = $indikator_program->result();
			$total_temp = $indikator_program->num_rows();
			
			$col_indikator=1;
			$go_2_keg = FALSE;
			$total_for_iteration = $total_temp;
			if($total_temp > $max_col_keg){
				$total_temp = $max_col_keg;
				$go_2_keg = TRUE;
			}
	?>
    <tr>
    	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_urusan; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_bidang; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_program; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_kegiatan; ?></td>
        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->nama_prog_or_keg; ?></td>
        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->rencana; ?></td>
        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->realisasi; ?></td>
        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?></td>
        <td>
			<?php
				echo $temp[0]->indikator;
			?>
		</td>
		<td>
			<?php
				echo $default_rencana;
			?>
		</td>
        <td>
			<?php
				echo $temp[0]->target;
			?>
		</td>
        <td>
			<?php
				echo $temp[0]->target;
			?>
		</td>
        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">-</td>
        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"></td>
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
					<td>
						<?php
							echo $temp[$i]->target;
						?>
					</td>
					<td>
						<?php
							echo $temp[$i]->realisasi;
						?>
					</td>
					<td>
						<?php
							echo ($temp[$i]->target/$temp[$i]->realisasi)/100;
						?>
					</td>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
				</tr>
            <?php
				}
			}
				foreach ($kegiatan as $row) {
				$indikator_kegiatan = $this->m_cik->get_indikator_prog_keg_preview($row->id, $bulan, FALSE, TRUE);
				$temp = $indikator_kegiatan->result();			
				$total_temp = $indikator_kegiatan->num_rows();

				$go_2_keg = FALSE;
				$col_indikator_keg=1;
				$total_for_iteration = $total_temp;
				if ($total_temp > $max_col_keg) {
					$total_temp = $max_col_keg;
					$go_2_keg = TRUE;
				}
			?>
            <tr>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_urusan; ?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_bidang; ?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_program; ?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_kegiatan; ?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->nama_prog_or_keg; ?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->rencana; ?></td>
        		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->realisasi; ?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?></td>
                <td>
					<?php
                        echo $temp[0]->indikator;
                    ?>
                </td>
                <td>
                    <?php
                        echo $temp[0]->target;
                    ?>
                </td>
                <td>
                    <?php
                        echo $temp[0]->realisasi;
                    ?>
                </td>
                <td>
                    <?php
                        echo (empty($temp[0]->realisasi))?0:round($temp[0]->realisasi/$temp[0]->rencana)*100;
                    ?>
                </td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">-</td>
                <td>
                	<a href="javascript:void(0)" idK="<?php echo $row->id; ?>" class="icon-pencil edit-kegiatan" title="Edit Kegiatan"/></a>
                </td>
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
					<td>
						<?php
							echo $temp[$i]->target;
						?>
					</td>
					<td>
						<?php
							echo $temp[$i]->realisasi;
						?>
					</td>
					<td>
						<?php
							(empty($temp[$i]->realisasi))?0:round($temp[$i]->realisasi/$temp[$i]->rencana)*100;
						?>
					</td>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
                    <td>
                    	<a href="javascript:void(0)" idK="<?php echo $row->id; ?>" class="icon-pencil edit-kegiatan" title="Edit Kegiatan"/></a>
                    </td>
				</tr>
	<?php
			}
		}
	}
		}
	}
		?>