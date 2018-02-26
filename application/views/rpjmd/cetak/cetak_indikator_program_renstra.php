
	<h1> Tabel Indikator Program  Renstra</h1> 
	<table border="1"  style="border-collapse: collapse"  width="100%" >
	<thead>
		<tr>
			<th rowspan="2"> NO</th>
			<th rowspan="2" colspan="2">Program Daerah</th>
			<th rowspan="2">Indikator</th>
			<th rowspan="2">Kondisi Awal</th>
			<th colspan="10">Tahun</th>
			<th rowspan="2">Kondisi Akhir</th>
		</tr>
		<tr>
			
			<th colspan="2">Target 1</th>
			<th colspan="2">Target 2</th>
			<th colspan="2">Target 3</th>
			<th colspan="2">Target 4</th>
			<th colspan="2">Target 5</th>
			
		</tr>
	</thead>	
	<tbody>
	

		<?php $no=1; foreach ($dataprogram as $rowprogram ) { 
			$dataindikator =  $this->m_rpjmd_trx->get_indikator_program_cetak_renstra($rowprogram->id);
			
			$kodeprogram = $rowprogram->kd_urusan.'.'.$rowprogram->kd_bidang.'.'.$rowprogram->kd_program;
			$totalindikator = count($dataindikator); ?>

		
	

		<?php $count=0; foreach ($dataindikator as $rowindikator) { ?>
		<tr>
		<?php if ($count==0) { ?>
			<td rowspan="<?php echo $totalindikator; ?>"><?php echo $no;?> </td>
			<td align="left" style="border-right: 0; padding-right : 0px; " rowspan="<?php echo $totalindikator; ?>"><?php echo $kodeprogram;?> </td>
			<td align="left" style="border-left: 0;"rowspan="<?php echo $totalindikator; ?>"><?php echo $rowprogram->nama_prog_or_keg;?> </td>.
		<?php } ?>
		

		<td ><?php echo $rowindikator->indikator;?> </td>
		<td ><?php echo $rowindikator->kondisi_awal.' '.$rowindikator->satuan_target;?> </td>
		<td >10000 </td>
		<td ><?php echo $rowindikator->target_1.' '.$rowindikator->satuan_target ;?> </td>
		<td >10000 </td>
		<td ><?php echo $rowindikator->target_2.' '.$rowindikator->satuan_target;?> </td>
		<td >10000 </td>
		<td ><?php echo $rowindikator->target_3.' '.$rowindikator->satuan_target;?> </td>
		<td >10000 </td>
		<td ><?php echo $rowindikator->target_4.' '.$rowindikator->satuan_target;?> </td>
		<td >10000 </td>
		<td ><?php echo $rowindikator->target_5.' '.$rowindikator->satuan_target;?> </td>
		<td ><?php echo $rowindikator->target_kondisi_akhir.' '.$rowindikator->satuan_target;?> </td>
	</tr>

		<?php $count++;} ?>
		

		<?php $no++;} ?>

		

	
</tbody>
	</table>
