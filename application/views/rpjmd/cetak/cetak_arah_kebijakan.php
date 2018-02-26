<div align="center">Tabel Strategi dan Arah Kebijakan</div>

	<table border="1"  style="border-collapse: collapse"  width="100%" >
	<thead>
		<tr>
			<th colspan="2"> Tujuan</th>
			<th colspan="2">Sasaran</th>
			<th colspan="2">Strategi</th>
			<th colspan="2">Arah Kebijakan</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="8"><strong>Visi : <?php echo $visi[0]->visi; ?> </strong></td>
		</tr>
		<?php $no= 1;  foreach ($misi as $row ) {  $count = 0;
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
				<td colspan="8"><strong>Misi <?php echo $huruf ?>. : <?php echo $row->misi; ?> </strong></td>
			</tr>
			<?php
				$tujuan = $this->m_rpjmd_trx->get_tujuan_rpjmd_cetak($row->id);
			 	$noTujuan= 1;  foreach ($tujuan as $rowtujuan ) {  $counttujuan = 0;
					//dalam perulangan tujuan
						$arahkebijakanbyTujuan=$this->m_rpjmd_trx->get_kebijakan_by_tujuan($rowtujuan->id);
						$rowspanTujuan = count($arahkebijakanbyTujuan);
						$sasaran=$this->m_rpjmd_trx->get_sasaran_tujuan_cetak_($rowtujuan->id);
						$noSasaran= 1;foreach ($sasaran as $rowsasaran ) { $countsasaran = 0;
							//dalam perulangan sasaran
							$arahkebijakanbySasaran=$this->m_rpjmd_trx->get_kebijakan_by_sasaran($rowsasaran->id);
							$rowspanSasaran = count($arahkebijakanbySasaran);
							$strategi=$this->m_rpjmd_trx->get_strategi_sasaran_cetak($rowsasaran->id);
						//	print_r(count($strategi));

							$noStrategi= 1; foreach ($strategi as $rowstrategi) { $countstrategi = 0;
								//dalam perulangan strategi
								$arahkebijakan=$this->m_rpjmd_trx->get_kebijakan_strategi_cetak($rowstrategi->id);
								$rowspanStrategi = count($arahkebijakan);
								$noKebijakan= 1;foreach ($arahkebijakan as $rowkebijakan ) {
									// dalam perulangan Kebijakan
						?>
							<tr>
								<?php if($counttujuan==0){ ?>
								<td rowspan="<?php echo $rowspanTujuan ?>"><?php echo $no.".".$noTujuan ?> </td>
								<td rowspan="<?php echo $rowspanTujuan ?>"><?php echo $rowtujuan->tujuan?></td>
								<?php } ?>
								<?php if ($countsasaran==0) {?>
							  <td rowspan="<?php echo $rowspanSasaran ?>"><?php echo $no.".".$noTujuan.".".$noSasaran ?> </td>
							  <td rowspan="<?php echo $rowspanSasaran ?>"><?php echo $rowsasaran->sasaran?></td>
							  <?php }?>
								<?php if ($countstrategi==0) {?>
								<td rowspan="<?php echo $rowspanStrategi ?>"><?php echo $no.".".$noTujuan.".".$noSasaran.".".$noStrategi ?> </td>
								<td rowspan="<?php echo $rowspanStrategi ?>"><?php echo $rowstrategi->strategi?></td>
								<?php }?>
								<td ><?php echo $no.".".$noTujuan.".".$noSasaran.".".$noStrategi.".".$noKebijakan ?> </td>
								<td ><?php echo $rowkebijakan->kebijakan?></td>
							</tr>
						<?php
								$counttujuan++;$countsasaran++;$countstrategi++; $noKebijakan++;}
							 $noStrategi++;}
						$noSasaran++;}
			 $noTujuan++;}
			?>
		<?php $no++;}?>

	</tbody>
	</table>
