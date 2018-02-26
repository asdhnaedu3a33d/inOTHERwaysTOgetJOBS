<style type="text/css">
	table td{
		vertical-align: top;
	}
</style>

<div align="center">Tabel 7.1 Program Prioritas</div>
<table border="1" style="border-collapse: collapse;" width="100%">
	<thead>
		<tr>
			<th>NO.</th>
			<th>SASARAN</th>
			<th>STRATEGI</th>			
			<th>ARAH KEBIJAKAN</th>
			<th>INDIKATOR SASARAN</th>
			<th width="15px">KONDISI AWAL</th>
			<th width="15px">KONDISI AKHIR</th>
			<th>PROGRAM PRIORITAS</th>
			<th>PROGRAM SKPD</th>
			<th>SKPD PENANGGUNG JAWAB</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$no = 1;
			foreach ($rpjmd as $row_rpjmd) {
				$sasaran = $this->m_rpjmd_trx->get_all_sasaran($row_rpjmd->id);
				foreach ($sasaran as $key_sasaran => $row_sasaran) {
					$strategi = $this->m_rpjmd_trx->get_all_strategi($row_sasaran->id);
					$program = $this->m_rpjmd_trx->get_all_program_ng_row($row_sasaran->id);
					$indikator_sasaran = $this->m_rpjmd_trx->get_indikator_program_per_sasaran($row_sasaran->id)->result();
					$program_skpd = $this->m_rpjmd_trx->get_program_skpd_from_renstra($program->id);
					$tot_indikator_sasaran = count($indikator_sasaran);
					$tot_prog_skpd = count($program_skpd);
					$count_tot_prog_skpd = count($program_skpd);
					$indk = 0;
					$inst = 0;
					$in_indik = 0;

		 			$kebijakan = $this->m_rpjmd_trx->get_all_kebijakan($strategi[0]->id);
					$tot_kebijakan = count($kebijakan);

					$tot_kebijakan_p_strategi = $this->m_rpjmd_trx->get_total_kebijakan_strategi_cetak($row_sasaran->id);
					$tot_kebijakan_p_strategi = $tot_kebijakan_p_strategi->jumlah;
					$tot_strategi = count($strategi);

					if ($tot_prog_skpd < $tot_kebijakan_p_strategi) {
						for ($i=$tot_prog_skpd; $i < $tot_kebijakan_p_strategi; $i++) { 
							$program_skpd[$i] = (object) array('nama_prog_or_keg' => '');
						}
						$tot_prog_skpd = $tot_kebijakan_p_strategi;
					}

					foreach ($program_skpd as $key_prog_skpd => $row_prog_skpd) {
						$nama_skpd = $this->db->query("SELECT nama_skpd FROM m_skpd WHERE id_skpd IN (SELECT id_skpd FROM t_renstra_prog_keg WHERE id_prog_rpjmd = '".$row_prog_skpd->id_prog_rpjmd."')")->result();



		 ?>
		 	<tr>
		 		<?php if ($key_prog_skpd == 0): ?>
			 		<td rowspan="<?php echo $tot_prog_skpd; ?>"><?php echo $no; ?></td>
			 		<td rowspan="<?php echo $tot_prog_skpd; ?>"><?php echo $row_sasaran->sasaran; ?></td>
		 		<?php endif ?>

		 		<?php if ($inst == $tot_strategi-1 && $tot_kebijakan == $indk+1 && $indk == 0) {
		 			echo "<td rowspan=".($tot_prog_skpd - $tot_kebijakan_p_strategi+1).">".$strategi[$inst]->strategi."</td>";
		 			$inst++;
		 		}elseif ($inst == $tot_strategi-1 && $indk == 0 && $tot_kebijakan_p_strategi == $tot_kebijakan) {
		 			echo "<td rowspan=".($tot_prog_skpd - $tot_kebijakan_p_strategi+$tot_kebijakan).">".$strategi[$inst]->strategi."</td>";
		 			$inst++;
		 		}elseif ($inst == $tot_strategi-1 && $indk == 0 && $tot_kebijakan_p_strategi > $count_tot_prog_skpd) {
		 			echo "<td rowspan=".$tot_kebijakan.">".$strategi[$inst]->strategi."</td>";
		 			$inst++;
		 		}elseif ($inst == $tot_strategi-1 && $indk == 0) {
		 			echo "<td rowspan=".($tot_prog_skpd - $tot_kebijakan_p_strategi+$tot_kebijakan+1).">".$strategi[$inst]->strategi."</td>";
		 			$inst++;
		 		}elseif ($indk == 0 && $tot_strategi == 1) {
		 			echo "<td rowspan=".$tot_prog_skpd.">".$strategi[$inst]->strategi."</td>";
		 			$inst++;
		 		}elseif($indk == 0){
		 			echo "<td rowspan=".$tot_kebijakan.">".$strategi[$inst]->strategi."</td>";
		 			$inst++;
		 		} ?>


		 		<?php if ($tot_kebijakan > $indk+1) {
		 			echo "<td>".$kebijakan[$indk]->kebijakan."</td>";
		 		}elseif ($tot_kebijakan == $indk+1 && $inst < $tot_strategi) {
		 			echo "<td>".$kebijakan[$indk]->kebijakan."</td>";
		 			$kebijakan = $this->m_rpjmd_trx->get_all_kebijakan($strategi[$inst]->id);
					$tot_kebijakan = count($kebijakan);
		 			$indk = -1;	
		 		}elseif  ($inst == $tot_strategi and $tot_kebijakan == $indk+1 and $indk == 0) {
		 			echo "<td rowspan=".($tot_prog_skpd - $tot_kebijakan_p_strategi+1).">".$kebijakan[$indk]->kebijakan."</td>";
		 		}elseif  ($inst == $tot_strategi and $tot_kebijakan == $indk+1 and $indk != 0) {
		 			echo "<td rowspan=".($tot_prog_skpd - $tot_kebijakan_p_strategi+1).">".$kebijakan[$indk]->kebijakan."</td>";
		 		}
		 		?>

		 		<?php 
			 		if ($tot_indikator_sasaran > $in_indik+1) {
			 			echo "<td>".$indikator_sasaran[$in_indik]->indikator."</td>";
			 			echo "<td>".$indikator_sasaran[$in_indik]->kondisi_awal."</td>";
			 			echo "<td>".$indikator_sasaran[$in_indik]->kondisi_akhir."</td>";
			 		}elseif ($tot_indikator_sasaran == $in_indik+1) {
			 			echo "<td rowspan=".($tot_prog_skpd-$tot_indikator_sasaran+1).">".$indikator_sasaran[$in_indik]->indikator."</td>";
			 			echo "<td rowspan=".($tot_prog_skpd-$tot_indikator_sasaran+1).">".$indikator_sasaran[$in_indik]->kondisi_awal."</td>";
			 			echo "<td rowspan=".($tot_prog_skpd-$tot_indikator_sasaran+1).">".$indikator_sasaran[$in_indik]->kondisi_akhir."</td>";
			 		} 
		 		?>
			 		
			 		<?php if ($key_prog_skpd == 0): ?>
			 			<td rowspan="<?php echo $tot_prog_skpd; ?>"><?php echo $program->nama_prog; ?> TEST</td>
			 		<?php endif ?>


			 		<?php 
			 			if ($key_prog_skpd < $count_tot_prog_skpd){
			 				if (empty($program_skpd[$key_prog_skpd+1]->nama_prog_or_keg)) {
			 					echo "<td rowspan=".($tot_kebijakan_p_strategi-$count_tot_prog_skpd+1).">".$row_prog_skpd->nama_prog_or_keg."</td>";
			 					echo "<td rowspan=".($tot_kebijakan_p_strategi-$count_tot_prog_skpd+1).">";
			 					foreach ($nama_skpd as $row_skpd) {
			 						echo $row_skpd->nama_skpd."; <br>";
			 					}
			 					echo "</td>";
			 				}else{
			 					echo "<td>".$row_prog_skpd->nama_prog_or_keg."</td>";
			 					echo "<td>";
			 					foreach ($nama_skpd as $row_skpd) {
			 						echo $row_skpd->nama_skpd."; <br>";
			 					}
			 					echo "</td>";
			 				}
			 			}elseif ($count_tot_prog_skpd == 0 && $key_prog_skpd == 0) {
			 				echo "<td rowspan=".($tot_kebijakan_p_strategi-$count_tot_prog_skpd).">".$row_prog_skpd->nama_prog_or_keg."</td>";
		 					echo "<td rowspan=".($tot_kebijakan_p_strategi-$count_tot_prog_skpd).">";
		 					foreach ($nama_skpd as $row_skpd) {
		 						echo $row_skpd->nama_skpd."; <br>";
		 					}
		 					echo "</td>";
			 			}
			 		 ?>

			 		
			 		
		 	</tr>

		 <?php 
		 	$indk++; $in_indik++;}
		 	$no++;
			}}
		  ?>
	</tbody>

</table>
