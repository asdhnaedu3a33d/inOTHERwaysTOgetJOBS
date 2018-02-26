
	<table style="width: 100%" border="0" > 
		<tr>
			<td> Visi :</td>
			<td> <strong> <?php echo $visi[0]->visi; ?> </strong> <br></td>
		</tr>
	</table>

	<table border="1"  style="border-collapse: collapse"  width="100%" >
	<thead>
		<tr>
			<th> NO</th>
			<th >Misi</th>
			<th style="border-right : none; "></th>
			<th style="border-left : none; ">Tujuan</th>
			<th>Indikator Tujuan</th>
			<th>Indikator Awal</th>
			<th >Indikator Akhir</th>

		</tr>
	</thead>	
	<tbody>
	<?php $no= 1;  foreach ($misi as $row ) {  $count = 0; ?>
		
			
		<?php	$tujuan = $this->m_rpjmd_trx->get_tujuan_rpjmd_cetak($row->id); 
			$roswspantujuan = count($tujuan);
		?>

		

		
	<?php $notujuan=1; foreach ($tujuan as $rowtujuan ) {  $count2 = 0; ?>
				<?php	$indikator_tujuan = $this->m_rpjmd_trx->get_indikator_tujuan_rpjmd_cetak1($rowtujuan->id); 

			$count_indikator_bymisi = $this->m_rpjmd_trx->get_count_indikator_tujuan_bymisi($row->id);
			$roswspanindikatoruntukmisi = count($count_indikator_bymisi);
			$roswspanindikator = count($indikator_tujuan);

		?>
			<?php  foreach ($indikator_tujuan as $rowindikator ) {  ?>

						<tr>
							<?php if ($count==0) { ?>
							<td rowspan=" <?php echo $roswspanindikatoruntukmisi; ?>"> <?php  echo $no ?></td>
							<td rowspan="<?php echo $roswspanindikatoruntukmisi; ?> "> <?php  echo $row->misi ?></td>
							<?php $count++; } else{ ?>
							
							<?php } ?>
							<?php if ($count2 == 0 ) {?>
							<td  rowspan="<?php echo $roswspanindikator; ?> " valign = "top" style="border-right : none; " > <?php echo  $no.'.'.$notujuan.'.' ?></td>
							<td rowspan="<?php echo $roswspanindikator; ?> " valign = "top" style="border-left : none; "><?php  echo  $rowtujuan->tujuan ?></td>
							<?php $count2++; } ?>

							<td><?php  echo $rowindikator->indikator ?></td>
							<td><?php  echo $rowindikator->kondisi_awal ?></td>
							<td><?php  echo $rowindikator->kondisi_akhir ?></td>
						</tr>
		<?php  } ?> 	
	<?php $notujuan++; } ?> 

	<?php $no++; $roswspantujuan = 0; } ?> 
		
	</tbody>
	</table>
