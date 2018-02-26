
	

	<table border="1"  style="border-collapse: collapse"  width="100%" >
	<thead>
		<tr>
			<th rowspan="2"> NO</th>
			<th rowspan="2">Tujuan</th>
			<th rowspan="2">&nbsp;</th>
			<th rowspan="2">Sasaran</th>
			<th rowspan="2">Indikator</th>
			<th rowspan="2">Cara Pengukuran</th>
			<th colspan="7">Target</th>
		</tr>
		<tr>
			<th>Kondisi Awal</th>
			<th>Target 1</th>
			<th>Target 2</th>
			<th>Target 3</th>
			<th>Target 4</th>
			<th>Target 5</th>
			<th>Kondisi Akhir</th>
		</tr>
	</thead>
	<tbody>
	<?php $no= 1;$noTujuan= 1;  foreach ($misi as $rowmisi ) {  $count = 0;
			switch ($no) {
				case 1:
        $huruf = "A";
				break;
    		case 2:
				$huruf = "B";
				break;
				case 3:
				$huruf = "C";
				break;
				case 4:
				$huruf = "D";
				break;
				case 5:
				$huruf = "E";
				break;
				case 6:
				$huruf = "F";
				break;
				case 7:
				$huruf = "G";
				break;
				case 8:
				$huruf = "H";
				break;
				case 9:
				$huruf = "I";
				break;
				case 10:
				$huruf = "J";
				break;
				case 11:
				$huruf = "K";
				break;
				case 12:
				$huruf = "L";
				break;
				case 13:
				$huruf = "M";
				break;
				case 14:
				$huruf = "N";
				break;
				case 15:
				$huruf = "O";
				break;
			}
			?>
			<tr>
				<td colspan="13"><strong>Misi <?php echo $huruf ?>. : <?php echo $rowmisi->misi; ?> </strong></td>
			</tr>

							<?php  $tujuan=$this->m_rpjmd_trx->get_tujuan_rpjmd_cetak($rowmisi->id);
							 foreach ($tujuan as $rowtujuan )  { $count= 0;
								$sasaran = $this->m_rpjmd_trx->get_sasaran_tujuan_cetak_($rowtujuan->id);
								$rowspansasaran = count($sasaran);
								$program = $this->m_rpjmd_trx->get_program_sasaran_cetak($rowtujuan->id);

								$indikatorcount = $this->m_rpjmd_trx->get_count_indikator_program_bytujuan($rowtujuan->id);

								$rowspanindikator_untuktujuan = count($indikatorcount);
								//print_r($sasaran);

							?>

							<?php
							 foreach ($sasaran as $rowsasaran )  { $count2=0;
									$indikator = $this->m_rpjmd_trx->get_indikator_program_cetak_by_sasaran($rowsasaran->id);
									//print_r($indikator);exit();
									$program_sasaran = $this->m_rpjmd_trx->get_program_sasaran_cetak_bysasaran($rowsasaran->id);
									$rowspanindikator = count($indikator);


									 ?>

							<?php foreach ($program_sasaran as $rowprogram)  { $count3=0;
									$indikator_program = $this->m_rpjmd_trx->get_indikator_program_cetak($rowprogram->id);
									$rowspanindikator_program = count($indikator_program);
									?>

									<?php foreach ($indikator_program as $rowindikator )  { ?>

						<tr>
						<?php  if ($count==0) { ?>
							<td rowspan="<?php echo $rowspanindikator_untuktujuan;?>"> <?php echo $noTujuan;?></td>

							<td rowspan="<?php echo $rowspanindikator_untuktujuan;?>"> <?php echo $rowtujuan->tujuan;?></td>
						<?php $count++;  } ?>

						<?php $noSasaran=1; if ($count2==0) { ?>
						<td rowspan="<?php echo $rowspanindikator;?>"><?php echo  $noTujuan.'.'.$noSasaran?> </td>
							<td rowspan="<?php echo $rowspanindikator;?>"><?php echo  $rowsasaran->sasaran;?> </td>
						<?php $count2++;$noTujuan++;$noSasaran++;} ?>



							<td><?php echo $rowindikator->indikator;?></td>
							<td><?php echo $rowindikator->cara_pengukuran;?></td>
							<td><?php echo $rowindikator->kondisi_awal;?></td>
							<td><?php echo $rowindikator->target_1;?></td>
							<td><?php echo $rowindikator->target_2;?></td>
							<td><?php echo $rowindikator->target_3;?></td>
							<td><?php echo $rowindikator->target_4;?></td>
							<td><?php echo $rowindikator->target_5;?></td>
							<td><?php echo $rowindikator->kondisi_akhir;?></td>

						</tr>

									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php $no++; } ?>





		<?php $no++;}?>





</tbody>
	</table>
