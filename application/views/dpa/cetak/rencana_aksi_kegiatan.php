<?php
 
 header("Content-type: application/vnd-ms-excel");
 
 header("Content-Disposition: attachment; filename=".$filenameEX.".xls");
 
 header("Pragma: no-cache");
 
 header("Expires: 0");

 ?>

<div align="center" style="font-size: 20px; font-weight: bold;">Tabel Rencana Aksi Kegiatan</div>
<table border="1" style="border-collapse: collapse"  width="100%">
	<thead>
		<tr>
			<th>NO.</th>
			<th>KEGIATAN</th>
			<th>UKURAN KEBERHASILAN</th>
			<th>BULAN</th>
			<th>URAIAN KINERJA</th>
			<th>BOBOT</th>
			<th>TARGET</th>
			<th>ANGGARAN</th>
			<!-- <th>REALISASI</th> -->
			<!-- <th>SERAPAN ANGGARAN (%)</th> -->
		</tr>
	</thead>
	<tbody>
		<?php  
			$i = 1;
			$aksi = (array)$aksi;
			$kegiatan = $this->m_dpa->get_all_kegiatan($aksi['id'], $aksi['id_skpd'], $aksi['tahun']);
		?>

		<tr>
			<td colspan="8"><b><?php echo  $aksi['kd_urusan'].".".$aksi['kd_bidang'].".".$aksi['kd_program']." ".$aksi['nama_prog_or_keg']; ?></b></td>
		</tr>
		
		<?php 

			foreach ($kegiatan as $key => $row) { 
				$rencana_aksi = $this->m_dpa->get_cetak_rencana_program($row->id, 0)->result();
				$count_per_aksi = $this->m_dpa->get_cetak_rencana_program($row->id)->num_rows();
				$indikator = $this->m_dpa->get_indikator_prog_keg($row->id, true, true);
				$sum_anggaran_komulatif = 0;
				foreach ($rencana_aksi as $key2 => $row2) {
					 $aksi_per_bulan = $this->m_dpa->get_cetak_rencana_program($row2->id_dpa_prog_keg,$row2->bulan)->result();
					 $count_per_bulan = count($aksi_per_bulan);
					 $sum_anggaran_aksi = $this->m_dpa->sum_anggaran_rencana_aksi($row2->id_dpa_prog_keg,$row2->bulan)->row();
					 $realisasi = $this->m_dpa->get_realisasi_dari_triwulan($row2->id_dpa_prog_keg,$row2->bulan)->row();

					 $sum_anggaran_komulatif += $sum_anggaran_aksi->sum_ang;

					 foreach ($aksi_per_bulan as $key3 => $row3) {
					 	$for_serapan = number_format(0, 2, ',', '.');
					 	if ($sum_anggaran_aksi->sum_ang != 0) {
					 		$for_serapan = number_format(($realisasi->anggaran/$sum_anggaran_aksi->sum_ang)*100, 2, ',', '.');
					 	}	
		?>
			<tr>
				<?php if ($key3 == 0 && $key2 == 0) { ?>
					<td align="center" valign="top" rowspan="<?php echo $count_per_aksi; ?>"><?php echo $i; ?></td>
					<td valign="top" rowspan="<?php echo $count_per_aksi; ?>"><?php echo $row->kd_urusan.".".$row->kd_bidang.".".$row->kd_program.".".$row->kd_kegiatan." ".$row->nama_prog_or_keg; ?></td>
					<td valign="top" rowspan="<?php echo $count_per_aksi; ?>">
						<?php 
							foreach ($indikator as $row_indik) {
								echo $row_indik->indikator." ".$row_indik->target." ".$row_indik->nama_value."<p>";
							}
						?>
					</td>
				<?php } ?>
				<?php if ($key3 == 0) { ?>
					<td valign="top" rowspan="<?php echo $count_per_bulan; ?>">BLN <?php echo $row2->bulan; ?></td>
				<?php } ?>
					<td valign="top"><?php echo $row3->aksi ?></td>
					<td valign="top"><?php echo number_format($row3->bobot, 2, ',', '.'); ?></td>
					<td valign="top"><?php echo number_format($row3->target, 2, ',', '.'); ?></td>
					<!-- <td><?php echo number_format($row3->anggaran, 2, ',', '.'); ?></td> -->
				<?php if ($key3 == 0) { ?>
					<td valign="top" align="right" rowspan="<?php echo $count_per_bulan; ?>"><?php echo number_format($sum_anggaran_komulatif, 2, ',', '.'); ?></td>
					<!-- <td rowspan="<?php echo $count_per_bulan; ?>"><?php echo number_format($realisasi->anggaran, 2, ',', '.'); ?></td> -->
					<!-- <td rowspan="<?php echo $count_per_bulan; ?>"><?php echo $for_serapan; ?></td> -->
				<?php } ?>
			</tr>
		<?php }}$i++;} ?>
	</tbody>
</table>