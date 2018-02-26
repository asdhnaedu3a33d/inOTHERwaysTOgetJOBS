<div align="center">Tabel Capaian Aksi Program</div>
<table border="1" style="border-collapse: collapse"  width="100%">
	<thead>
		<tr>
			<th>NO.</th>
			<th>PROGRAM/KEGIATAN</th>
			<th>BULAN</th>
			<th>URAIAN KINERJA</th>
			<th>BOBOT</th>
			<th>TARGET</th>
			<th>REALISASI AKSI</th>
			<th>CAPAIAN (%)</th>
			<!-- <th>CAPAIAN KOMULATIF (%)</th> -->
			<th>ANGGARAN</th>
			<th>REALISASI</th>
			<th>SERAPAN ANGGARAN (%)</th>
		</tr>
	</thead>
	<tbody>
		<?php
		// print_r($aksi);
			$i = 1;
			foreach ($aksi as $key => $row) {
				$rencana_aksi = $this->m_dpa_perubahan->get_cetak_rencana_program($row->id, 0)->result();
				$count_for_number = $this->db->query('SELECT id FROM 
					tx_dpa_rencana_aksi_perubahan
					WHERE id_dpa_prog_keg
					IN (SELECT id FROM tx_dpa_prog_keg_perubahan WHERE parent = '.$row->id.')')->num_rows();
				$count_per_aksi = $this->m_dpa_perubahan->get_cetak_rencana_program($row->id)->num_rows();

				foreach ($rencana_aksi as $key2 => $row2) {
					 $aksi_per_bulan = $this->m_dpa_perubahan->get_cetak_rencana_program($row2->id_dpa_prog_keg,$row2->bulan)->result();
					 $count_per_bulan = count($aksi_per_bulan);

					 foreach ($aksi_per_bulan as $key3 => $row3) {
					 	$for_capaian = number_format(0, 2, ',', '.');
					 	if ($row3->target > 0) {
					 		$for_capaian = formatting::currency(($row3->capaian/$row3->target)*100);
					 	}
					 	
		?>
			<tr>
				<?php if ( $key2 == 0 && $key3 == 0) { ?>
					<td valign="top" rowspan="<?php echo $count_for_number+$count_per_aksi+1 ?>"><?php echo $i; ?></td>
					<td valign="top" rowspan="<?php echo $count_per_aksi ?>"><?php echo $row->kd_urusan.".".$row->kd_bidang.".".$row->kd_program." ".$row->nama_prog_or_keg; ?></td>
				<?php } ?>
				<?php if ( $key3 == 0) { ?>
					<td valign="top" rowspan="<?php echo $count_per_bulan ?>">BLN <?php echo $row2->bulan; ?></td>
				<?php } ?>
					<td valign="top"><?php echo $row3->aksi; ?></td>
					<td valign="top"><?php echo number_format($row3->bobot, 2, ',', '.'); ?></td>
					<td valign="top"><?php echo number_format($row3->target, 2, ',', '.'); ?></td>
					<td valign="top"><?php echo number_format($row3->capaian, 2, ',', '.'); ?></td>
					<td valign="top"><?php echo $for_capaian; ?></td>
					<!-- <td><?php echo formatting::currency(($row3->bobot*$for_capaian)/100); ?></td> -->
				<?php if ( $key2 == 0 && $key3 == 0) { ?>
					<td valign="top" rowspan="<?php echo $count_per_aksi; ?>" colspan="3">&nbsp;</td>
				<?php } ?>
			</tr>	
		<?php }} ?>
			<tr>
				<td colspan="10">&nbsp;</td>
			</tr>
		<?php

			$keg = $this->m_dpa_perubahan->get_all_kegiatan($row->id, $row->id_skpd, $row->tahun);
			foreach ($keg as $key_keg => $row_keg) { 
				$rencana_aksi_keg = $this->m_dpa_perubahan->get_cetak_rencana_program($row_keg->id, 0)->result();
				$count_per_aksi_keg = $this->m_dpa_perubahan->get_cetak_rencana_program($row_keg->id)->num_rows();
				$sum_anggaran_komulatif_keg = 0;
				$realisasi_komulatif_keg = 0;

				foreach ($rencana_aksi_keg as $key_keg2 => $row_keg2) {
					 $aksi_per_bulan_keg = $this->m_dpa_perubahan->get_cetak_rencana_program($row_keg2->id_dpa_prog_keg,$row_keg2->bulan)->result();
					 $count_per_bulan_keg = count($aksi_per_bulan_keg);
					 $sum_anggaran_aksi_keg = $this->m_dpa_perubahan->sum_anggaran_rencana_aksi($row_keg2->id_dpa_prog_keg,$row_keg2->bulan)->row();
					 $realisasi_keg = $this->m_dpa_perubahan->get_realisasi_dari_triwulan($row_keg2->id_dpa_prog_keg,$row_keg2->bulan)->row();

					 $sum_anggaran_komulatif_keg += $sum_anggaran_aksi_keg->sum_ang;
					 $realisasi_komulatif_keg += $realisasi_keg->anggaran;

					 foreach ($aksi_per_bulan_keg as $key_keg3 => $row_keg3) {
					 	$for_capaian_keg = number_format(0, 2, ',', '.');
					 	if ($row_keg3->target > 0) {
					 		$for_capaian_keg = number_format(($row_keg3->capaian/$row_keg3->target)*100, 2, ',', '.');
					 	}
					 	
					 	$for_serapan_keg = number_format(0, 2, ',', '.');
					 	if ($sum_anggaran_aksi_keg->sum_ang > 0) {
					 		$for_serapan_keg = number_format(($realisasi_keg->anggaran/$sum_anggaran_aksi_keg->sum_ang)*100, 2, ',', '.');
					 	}
		?>
			<tr>
				<?php if ($key_keg3 == 0 && $key_keg2 == 0) { ?>
					
					<td valign="top"  rowspan="<?php echo $count_per_aksi_keg; ?>"><?php echo $row_keg->kd_urusan.".".$row_keg->kd_bidang.".".$row_keg->kd_program.".".$row_keg->kd_kegiatan." ".$row_keg->nama_prog_or_keg; ?></td>
				<?php } ?>
				<?php if ($key_keg3 == 0) { ?>
					<td valign="top"  rowspan="<?php echo $count_per_bulan_keg; ?>">BLN <?php echo $row_keg2->bulan; ?></td>
				<?php } ?>
					<td valign="top" ><?php echo $row_keg3->aksi ?></td>
					<td valign="top" ><?php echo number_format($row_keg3->bobot, 2, ',', '.'); ?></td>
					<td valign="top" ><?php echo number_format($row_keg3->target, 2, ',', '.'); ?></td>
					<td valign="top" ><?php echo number_format($row_keg3->capaian, 2, ',', '.'); ?></td>
					<td valign="top" ><?php echo $for_capaian_keg; ?></td>
					<!-- <td valign="top" ><?php echo number_format($row3->anggaran, 2, ',', '.'); ?></td> -->
				<?php if ($key_keg3 == 0) { ?>
					<td valign="top"  rowspan="<?php echo $count_per_bulan_keg; ?>"><?php echo number_format($sum_anggaran_komulatif_keg, 2, ',', '.'); ?></td>
					<td valign="top"  rowspan="<?php echo $count_per_bulan_keg; ?>"><?php echo number_format($realisasi_komulatif_keg, 2, ',', '.'); ?></td>
					<td valign="top"  rowspan="<?php echo $count_per_bulan_keg; ?>"><?php echo $for_serapan_keg; ?></td>
				<?php } ?>
			</tr>
		<?php }}}$i++; } ?>
	</tbody>
</table>