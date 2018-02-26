<div align="center">Tabel Organisasi dan Urusan Pemerintahan</div>
<table border="1" style="border-collapse: collapse"  width="100%">
	<thead>
		<tr>
			<th colspan="2" rowspan="2">KODE</th>
			<th rowspan="2">URUSAN PEMERINTAHAN DAERAH</th>			
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
				$kd_skpd = explode('.', $row->kode_skpd);
				$sum_skpd = $this->m_rpjmd_trx->get_sum_urusan_per_skpd($row->id_skpd);
				// print_r($this->db->last_query());
				// print_r('<br>');
				$urusan_bidang = $this->m_rpjmd_trx->get_sum_urusan_per_skpd($row->id_skpd, TRUE);
				$tot_crots1 += $sum_skpd[0]->crots1;
				$tot_crots2 += $sum_skpd[0]->crots2;
				$tot_crots3 += $sum_skpd[0]->crots3;
				$tot_crots4 += $sum_skpd[0]->crots4;
				$tot_crots5 += $sum_skpd[0]->crots5;
				$tot_crotsall += floatval($sum_skpd[0]->crots1) + floatval($sum_skpd[0]->crots2) + floatval($sum_skpd[0]->crots3) + floatval($sum_skpd[0]->crots4) + floatval($sum_skpd[0]->crots5);
		?>
			<tr>
				<td><b><?php echo $kd_skpd[0].".".$kd_skpd[1].".".$kd_skpd[2]; ?></b></td>
				<td></td>
				<td><b><?php echo $row->nama_skpd; ?></b></td>
				<td align="right"><b><?php echo Formatting::currency($sum_skpd[0]->crots1); ?></b></td>
				<td align="right"><b><?php echo Formatting::currency($sum_skpd[0]->crots2); ?></b></td>
				<td align="right"><b><?php echo Formatting::currency($sum_skpd[0]->crots3); ?></b></td>
				<td align="right"><b><?php echo Formatting::currency($sum_skpd[0]->crots4); ?></b></td>
				<td align="right"><b><?php echo Formatting::currency($sum_skpd[0]->crots5); ?></b></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($sum_skpd[0]->crots1) + floatval($sum_skpd[0]->crots2) + floatval($sum_skpd[0]->crots3) + floatval($sum_skpd[0]->crots4) + floatval($sum_skpd[0]->crots5)); ?></b></td>
			</tr>
		<?php
			foreach ($urusan_bidang as $row2) { 
		?>
			<tr>
				<td></td>
				<td><?php echo $row2->kd_urusan.".".$row2->kd_bidang; ?></td>
				<td>&emsp;<?php echo $row2->urusan." ".$row2->bidang; ?></td>
				<td align="right"><?php echo Formatting::currency($row2->crots1); ?></td>
				<td align="right"><?php echo Formatting::currency($row2->crots2); ?></td>
				<td align="right"><?php echo Formatting::currency($row2->crots3); ?></td>
				<td align="right"><?php echo Formatting::currency($row2->crots4); ?></td>
				<td align="right"><?php echo Formatting::currency($row2->crots5); ?></td>
				<td align="right"><?php echo Formatting::currency(floatval($row2->crots1)+floatval($row2->crots2)+floatval($row2->crots3)+floatval($row2->crots4)+floatval($row2->crots5)); ?></td>
			</tr>
		<?php }} ?>
		<tr>
			<th colspan="3">TOTAL</th>
			<th align="right"><?php echo Formatting::currency($tot_crots1); ?></th>
			<th align="right"><?php echo Formatting::currency($tot_crots2); ?></th>
			<th align="right"><?php echo Formatting::currency($tot_crots3); ?></th>
			<th align="right"><?php echo Formatting::currency($tot_crots4); ?></th>
			<th align="right"><?php echo Formatting::currency($tot_crots5); ?></th>
			<th align="right"><?php echo Formatting::currency($tot_crotsall); ?></th>
		</tr>
	</tbody>

</table>
