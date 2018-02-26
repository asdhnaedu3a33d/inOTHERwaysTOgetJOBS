<div align="center">Tabel Urusan Pemerintahan dan Organisasi</div>
<table border="1" style="border-collapse: collapse"  width="100%">
	<thead>
		<tr>
			<th rowspan="2" colspan="3">KODE</th>
			<th rowspan="2">SATUAN KERJA PERANGKAT DAERAH</th>			
			<th colspan="5">BELANJA LANGSUNG</th>
			<th rowspan="2">JUMLAH</th>
		</tr>
		<tr>
			<th>TAHUN 1</th>
			<th>TAHUN 2</th>
			<th>TAHUN 3</th>
			<th>TAHUN 4</th>
			<th>TAHUN 5</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$tot_crots1 = 0;
			$tot_crots2 = 0;
			$tot_crots3 = 0;
			$tot_crots4 = 0;
			$tot_crots5 = 0;
			$tot_crotsall = 0;
			foreach ($rpjmd as $row) { 
				$bidang = $this->m_rpjmd_trx->get_bidang_urusan($row->kd);
				$tot_crots1 += $row->nom1;
				$tot_crots2 += $row->nom2;
				$tot_crots3 += $row->nom3;
				$tot_crots4 += $row->nom4;
				$tot_crots5 += $row->nom5;
				$tot_crotsall += floatval($row->nom1)+floatval($row->nom2)+floatval($row->nom3)+floatval($row->nom4)+floatval($row->nom5);
		?>
			<tr>
				<td><b><?php echo $row->kd; ?></b></td>
				<td></td>
				<td></td>
				<td><b style="text-transform: uppercase;"><?php echo $row->nama; ?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row->nom1));?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row->nom2));?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row->nom3));?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row->nom4));?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row->nom5));?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row->nom1)+floatval($row->nom2)+floatval($row->nom3)+floatval($row->nom4)+floatval($row->nom5)); ?></b></td>
			</tr>
		<?php
			foreach ($bidang as $row2) { 
				$skpd = $this->m_rpjmd_trx->get_skpd_bidang_urusan($row->kd, $row2->kd); 
		?>
			<tr>
				<td><b><?php echo $row->kd; ?></b></td>
				<td><b><?php echo $row2->kd; ?></b></td>
				<td></td>
				<td><b style="text-transform: uppercase;">&emsp;<?php echo $row2->nama; ?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row2->nom1));?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row2->nom2));?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row2->nom3));?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row2->nom4));?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row2->nom5));?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row2->nom1)+floatval($row2->nom2)+floatval($row2->nom3)+floatval($row2->nom4)+floatval($row2->nom5)); ?></b></td>
			</tr>
		<?php
			foreach ($skpd as $row3) {
			$kd_skpd = explode('.', $row3->kode_skpd);
		?>
		<tr>
			<td><?php echo $kd_skpd[0]; ?></td>
			<td><?php echo $kd_skpd[1]; ?></td>
			<td><?php echo $kd_skpd[2]; ?></td>
			<td style="text-transform: uppercase;">&emsp;&emsp;<?php echo $row3->nama; ?></td>
			<td align="right"><?php echo Formatting::currency(floatval($row3->nom1));?></td>
			<td align="right"><?php echo Formatting::currency(floatval($row3->nom2));?></td>
			<td align="right"><?php echo Formatting::currency(floatval($row3->nom3));?></td>
			<td align="right"><?php echo Formatting::currency(floatval($row3->nom4));?></td>
			<td align="right"><?php echo Formatting::currency(floatval($row3->nom5));?></td>
			<td align="right"><?php echo Formatting::currency(floatval($row3->nom1)+floatval($row3->nom2)+floatval($row3->nom3)+floatval($row3->nom4)+floatval($row3->nom5)); ?></td>
		</tr>
		<?php }}} ?>
		<tr>
			<th colspan="4">TOTAL</th>
			<th align="right"><?php echo Formatting::currency($tot_crots1); ?></th>
			<th align="right"><?php echo Formatting::currency($tot_crots2); ?></th>
			<th align="right"><?php echo Formatting::currency($tot_crots3); ?></th>
			<th align="right"><?php echo Formatting::currency($tot_crots4); ?></th>
			<th align="right"><?php echo Formatting::currency($tot_crots5); ?></th>
			<th align="right"><?php echo Formatting::currency($tot_crotsall); ?></th>
		</tr>
	</tbody>

</table>
