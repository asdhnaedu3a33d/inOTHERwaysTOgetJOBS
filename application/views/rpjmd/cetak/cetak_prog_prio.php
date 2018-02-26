<?php
 
 header("Content-type: application/vnd-ms-excel");
 
 header("Content-Disposition: attachment; filename=$namafile.xls");
 
 header("Pragma: no-cache");
 
 header("Expires: 0");

 ?>

<style type="text/css">
	table td{
		vertical-align: top;
	}
</style>

<div align="center" style="font-size: 24px;">Tabel 7.1 Program Prioritas</div>
<table border="1" style="border-collapse: collapse;" width="100%">
	<thead>
		<tr>
			<th width="55px">NO.</th>
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
					$program = $this->m_rpjmd_trx->get_all_program_ng_row($row_sasaran->id);

					$indikator_sasaran = $this->m_rpjmd_trx->get_indikator_program_per_sasaran($row_sasaran->id)->result();
					$tot_indikator_sasaran = count($indikator_sasaran);

					$program_skpd = $this->m_rpjmd_trx->get_program_skpd_from_renstra($program->id);
					$tot_prog_skpd = count($program_skpd);

					$strategi = $this->m_rpjmd_trx->get_all_strategi($row_sasaran->id);
					$tot_strategi = count($strategi);

					$tot_kebijakan_p_sasaran = $this->m_rpjmd_trx->get_total_kebijakan_strategi_cetak($row_sasaran->id);
					$tot_kebijakan_p_sasaran = $tot_kebijakan_p_sasaran->jumlah;
// print_r($this->db->last_query());
					$kebijakan = $this->m_rpjmd_trx->get_all_kebijakan($strategi[0]->id);
					$tot_kebijakan = count($kebijakan);

					$tot_for_rowspan = count($program_skpd);
					if ($tot_prog_skpd < $tot_kebijakan_p_sasaran || $tot_prog_skpd < $tot_indikator_sasaran) {
						if ($tot_kebijakan_p_sasaran >= $tot_indikator_sasaran) {

							for ($i=$tot_prog_skpd; $i < $tot_kebijakan_p_sasaran; $i++) { 
								$program_skpd[$i] = (object) array('nama_prog_or_keg' => '');
							}
							$tot_for_rowspan = $tot_kebijakan_p_sasaran;

						}else{
							
							for ($i=$tot_prog_skpd; $i < $tot_indikator_sasaran; $i++) { 
								$program_skpd[$i] = (object) array('nama_prog_or_keg' => '');
							}
							$tot_for_rowspan = $tot_indikator_sasaran;

						}
						
					}

					$key_kebijakan = 0;
					$key_strategi = 0;
					foreach ($program_skpd as $key_prog_skpd => $row_prog_skpd) {
						$nama_skpd = $this->db->query("SELECT nama_skpd FROM m_skpd WHERE id_skpd IN (SELECT id_skpd FROM t_renstra_prog_keg WHERE id_prog_rpjmd = '".$row_prog_skpd->id_prog_rpjmd."')")->result();



		 ?>
		 	<tr>
		 		<?php if ($key_prog_skpd == 0): ?>
		 			<td rowspan="<?php echo $tot_for_rowspan; ?>"><?php echo $no; ?></td>
		 			<td rowspan="<?php echo $tot_for_rowspan; ?>"><?php echo $row_sasaran->sasaran; ?></td>
		 		<?php endif ?>

		 		<?php
		 			if ($key_kebijakan == 0 && $tot_strategi >= ($key_strategi+1)) {
		 				$rowspan_strategi = $tot_kebijakan;

		 				if ($tot_strategi == ($key_strategi+1) && $key_prog_skpd == 0) {
		 					$rowspan_strategi = $tot_for_rowspan;
		 				}elseif ($tot_strategi == ($key_strategi+1)) {
		 					$rowspan_strategi = $tot_for_rowspan - $tot_kebijakan_p_sasaran + $tot_kebijakan;
		 				}

		 				echo "<td rowspan='".$rowspan_strategi."'>".$strategi[$key_strategi]->strategi."</td>";
		 				$key_strategi++;
		 			}

		 		?>		 		

		 		<?php 

		 			if ($tot_kebijakan >= ($key_kebijakan) && $tot_strategi >= $key_strategi) {
		 				
		 				if (($key_kebijakan+1) == $tot_kebijakan && $tot_strategi == $key_strategi && $key_prog_skpd == 0) {
							echo "<td rowspan='".$tot_for_rowspan."'>".$kebijakan[$key_kebijakan]->kebijakan."</td>";
			 				$kebijakan = $this->m_rpjmd_trx->get_all_kebijakan($strategi[$key_strategi]->id);
							$tot_kebijakan = count($kebijakan);
							$key_kebijakan = 0;
			 			}elseif(($key_kebijakan+1) == $tot_kebijakan && $key_prog_skpd == 0) {
			 				echo "<td>".$kebijakan[$key_kebijakan]->kebijakan."</td>";
			 				$kebijakan = $this->m_rpjmd_trx->get_all_kebijakan($strategi[$key_strategi]->id);
							$tot_kebijakan = count($kebijakan);
							$key_kebijakan = 0;
			 			}elseif(($key_kebijakan+1) == $tot_kebijakan && $tot_strategi == $key_strategi && $tot_prog_skpd < $tot_kebijakan_p_sasaran) {
			 				$rowspan_kebijakan = $tot_for_rowspan - $tot_kebijakan_p_sasaran + $tot_kebijakan;
			 				echo "<td>".$kebijakan[$key_kebijakan]->kebijakan."</td>";
			 				$kebijakan = $this->m_rpjmd_trx->get_all_kebijakan($strategi[$key_strategi]->id);
							$tot_kebijakan = count($kebijakan);
							$key_kebijakan = 0;
			 			}elseif(($key_kebijakan+1) == $tot_kebijakan && $tot_strategi == $key_strategi && $tot_kebijakan == 1) {
			 				$rowspan_kebijakan = $tot_for_rowspan - $tot_kebijakan_p_sasaran + $tot_kebijakan;
			 				echo "<td rowspan='".$rowspan_kebijakan."'>".$kebijakan[$key_kebijakan]->kebijakan."</td>";
			 				$kebijakan = $this->m_rpjmd_trx->get_all_kebijakan($strategi[$key_strategi]->id);
							$tot_kebijakan = count($kebijakan);
							$key_kebijakan = 0;
			 			}elseif(($key_kebijakan+1) == $tot_kebijakan && $tot_strategi == $key_strategi) {
			 				$rowspan_kebijakan = $rowspan_strategi - $tot_kebijakan+1;
			 				echo "<td rowspan='".$rowspan_kebijakan."'>".$kebijakan[$key_kebijakan]->kebijakan."</td>";
			 				$kebijakan = $this->m_rpjmd_trx->get_all_kebijakan($strategi[$key_strategi]->id);
							$tot_kebijakan = count($kebijakan);
							$key_kebijakan = 0;
			 			}elseif(($key_kebijakan+1) < $tot_kebijakan && $tot_strategi < $key_strategi){
			 				echo "<td>".$kebijakan[$key_kebijakan]->kebijakan."</td>";
							$key_kebijakan++;
			 			}elseif(($key_kebijakan+1) < $tot_kebijakan){
			 				echo "<td>".$kebijakan[$key_kebijakan]->kebijakan."</td>";
							$key_kebijakan++;
			 			}elseif(($key_kebijakan+1) == $tot_kebijakan){
			 				echo "<td>".$kebijakan[$key_kebijakan]->kebijakan."</td>";
							$kebijakan = $this->m_rpjmd_trx->get_all_kebijakan($strategi[$key_strategi]->id);
							$tot_kebijakan = count($kebijakan);
							$key_kebijakan = 0;
			 			}
		 			}
		 		?>

		 		 <?php 
		 		 	if ($tot_indikator_sasaran == 1 && $key_prog_skpd == 0) {
	 		 			echo "<td rowspan='".$tot_for_rowspan."'>".$indikator_sasaran[$key_prog_skpd]->indikator."</td>";
			 		 	echo "<td rowspan='".$tot_for_rowspan."'>".$indikator_sasaran[$key_prog_skpd]->kondisi_awal."</td>";
			 		 	echo "<td rowspan='".$tot_for_rowspan."'>".$indikator_sasaran[$key_prog_skpd]->kondisi_akhir."</td>";
	 		 		}elseif (($key_prog_skpd+1) < $tot_indikator_sasaran){
		 		 		echo "<td>".$indikator_sasaran[$key_prog_skpd]->indikator."</td>";
			 		 	echo "<td>".$indikator_sasaran[$key_prog_skpd]->kondisi_awal."</td>";
			 		 	echo "<td>".$indikator_sasaran[$key_prog_skpd]->kondisi_akhir."</td>";
			 	 	}elseif (($key_prog_skpd+1) == $tot_indikator_sasaran) {
		 				echo "<td rowspan='".($tot_for_rowspan - $tot_indikator_sasaran + 1)."'>".$indikator_sasaran[$key_prog_skpd]->indikator."</td>";
			 		 	echo "<td rowspan='".($tot_for_rowspan - $tot_indikator_sasaran + 1)."'>".$indikator_sasaran[$key_prog_skpd]->kondisi_awal."</td>";
			 		 	echo "<td rowspan='".($tot_for_rowspan - $tot_indikator_sasaran + 1)."'>".$indikator_sasaran[$key_prog_skpd]->kondisi_akhir."</td>";	 		
		 	 		}
		 		?>

		 		<?php if ($key_prog_skpd == 0): ?>
					<td rowspan="<?php echo $tot_for_rowspan; ?>"><?php echo $program->nama_prog; ?></td>
				<?php endif ?>

				<?php 
					$nm_skpd = "";
					if (!empty($nama_skpd)) {
						foreach ($nama_skpd as $row_sk) {
							$nm_skpd .= $row_sk->nama_skpd."; <br>";
						}
					}

		 		  	if ($tot_prog_skpd <= 1 && $key_prog_skpd == 0) {
		 		  		echo "<td rowspan='".$tot_for_rowspan."'>".$row_prog_skpd->nama_prog_or_keg."</td>";
		 		  		echo "<td rowspan='".$tot_for_rowspan."'>".$nm_skpd."</td>";
		 		  	}elseif (($key_prog_skpd+1) < $tot_prog_skpd){
		 		 		echo "<td>".$row_prog_skpd->nama_prog_or_keg."</td>";
		 		  		echo "<td>".$nm_skpd."</td>";
			 	 	}elseif (($key_prog_skpd+1) == $tot_prog_skpd) {
		 				echo "<td rowspan='".($tot_for_rowspan-$tot_prog_skpd+1)."'>".$row_prog_skpd->nama_prog_or_keg."</td>";
		 		  		echo "<td rowspan='".($tot_for_rowspan-$tot_prog_skpd+1)."'>".$nm_skpd."</td>";
		 	 		}

		 		?>
		 	</tr>

		 <?php 
		 	}
		 	$no++;
			}}
		  ?>
	</tbody>

</table>
