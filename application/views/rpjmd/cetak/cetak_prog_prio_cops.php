<style type="text/css">
	table td{
		vertical-align: top;
	}
</style>

<div align="center">Tabel Organisasi dan Urusan Pemerintahan</div>
<table border="1" style="border-collapse: collapse"  width="100%">
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
					$tot_indikator_sasaran = count($indikator_sasaran);
					$indk = 0;

					$tot_kebijakan_p_strategi = $this->m_rpjmd_trx->get_total_kebijakan_strategi_cetak($row_sasaran->id);
					$tot_kebijakan_p_strategi = $tot_kebijakan_p_strategi->jumlah;
					$tot_strategi = count($strategi);
					foreach ($strategi as $key_strategi => $row_strategi) {
						$kebijakan = $this->m_rpjmd_trx->get_all_kebijakan($row_strategi->id);
						$tot_kebijakan = count($kebijakan);
						foreach ($kebijakan as $key_kebijakan => $row_kebijakan) {
		 ?>
		 	<tr>
		 		<?php if ($key_strategi == 0 && $key_kebijakan == 0): ?>
			 		<td rowspan="<?php echo $tot_kebijakan_p_strategi ?>"><?php echo $no; ?></td>
			 		<td rowspan="<?php echo $tot_kebijakan_p_strategi ?>"><?php echo $row_sasaran->sasaran; ?></td>
		 		<?php endif ?>
		 		<?php if ($key_kebijakan == 0): ?>
		 			<td rowspan="<?php echo $tot_kebijakan; ?>"><?php echo $row_strategi->strategi; ?></td>
		 		<?php endif ?>

		 		<td><?php echo $row_kebijakan->kebijakan; ?></td>		 		

		 		<?php 
			 		if ($tot_indikator_sasaran > $indk+1) {
			 			echo "<td>".$indikator_sasaran[$indk]->indikator."</td>";
			 			echo "<td>".$indikator_sasaran[$indk]->kondisi_awal."</td>";
			 			echo "<td>".$indikator_sasaran[$indk]->kondisi_akhir."</td>";
			 		}elseif ($tot_indikator_sasaran == $indk+1) {
			 			echo "<td rowspan=".($tot_kebijakan_p_strategi-$tot_indikator_sasaran+1).">".$indikator_sasaran[$indk]->indikator."</td>";
			 			echo "<td rowspan=".($tot_kebijakan_p_strategi-$tot_indikator_sasaran+1).">".$indikator_sasaran[$indk]->kondisi_awal."</td>";
			 			echo "<td rowspan=".($tot_kebijakan_p_strategi-$tot_indikator_sasaran+1).">".$indikator_sasaran[$indk]->kondisi_akhir."</td>";
			 		} 
		 		?>

		 		<?php if ($key_strategi == 0 && $key_kebijakan == 0): ?>
			 		<td rowspan="<?php echo $tot_kebijakan_p_strategi ?>"><?php echo $program->nama_prog; ?></td>
			 		<td rowspan="<?php echo $tot_kebijakan_p_strategi ?>">
			 			<p style="border-bottom: 1px solid black;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
			 			</p>
			 			<p>quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			 			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse</p>


			 		</td>
			 		<td rowspan="<?php echo $tot_kebijakan_p_strategi ?>"><b>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			 		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</b></td>
		 		<?php endif ?>
		 	</tr>

		 <?php 
		 	$indk++;
		 	}}
		 	$no++;
			}}
		  ?>
	</tbody>

</table>
