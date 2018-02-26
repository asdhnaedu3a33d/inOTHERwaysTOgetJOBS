<?php 
$th_anggaran = $this->m_settings->get_tahun_anggaran_db(); ?>

<div align="center">Tabel Indikator Tujuan</div>
	<table border="1"  style="border-collapse: collapse"  width="100%" >
	<thead>
		<tr>
			<th> NO</th>
			<th>Sasaran Strategis</th>
			<th>Indikator Kinerja Utama</th>
			<th><?php echo $th_anggaran[0]->tahun_anggaran; ?> </th>
			<th><?php echo $th_anggaran[1]->tahun_anggaran; ?></th>
			<th><?php echo $th_anggaran[2]->tahun_anggaran; ?></th>
			<th><?php echo $th_anggaran[3]->tahun_anggaran; ?></th>
			<th><?php echo $th_anggaran[4]->tahun_anggaran; ?></th>
      <th>Penjelasan</th>

		</tr>
	</thead>
	<tbody>
	<?php  $no=1; foreach ($tujuan as $rowtujuan )  { $count=0;
		$indikator = $this->m_rpjmd_trx->get_indikator_bytujuan($rowtujuan->id);
		$countIndikator = count($indikator);
		// /print_r(count($tujuan));exit();
		?>
	 <?php  foreach ($indikator as $rowindikator )  {  ?>

	 		 <tr>
		       <?php if ($count==0) {  ?>
					<td rowspan="<?php echo $countIndikator;?>"><?php echo $no?></td>
					<td rowspan="<?php echo $countIndikator;?>"><?php echo $rowtujuan->tujuan;?></td>
				<?php } ?>
					<td><?php echo $rowindikator->indikator;?></td>
					<td><?php echo $rowindikator->target_1;?></td>
					<td><?php echo $rowindikator->target_2;?></td>
					<td><?php echo $rowindikator->target_3;?></td>
					<td><?php echo $rowindikator->target_4;?></td>
					<td><?php echo $rowindikator->target_5;?></td>
          <td><?php echo $rowindikator->cara_pengukuran;?></td>
		       </tr>



		   <?php $count++; }?>
		<?php  $no++;}?>








</tbody>
	</table>
