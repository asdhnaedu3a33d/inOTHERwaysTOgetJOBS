<!-- CETAK DATA MUSRENBANGCAM DENGAN NILAI id_keputusan = 1 (BELUM DITENTUKAN)-->
<?php if(!empty($musrenbangcam1)){ ?>
<thead>
	<tr >
		<th style="font-size:11px" >KODE KEGIATAN</th>
		<th style="font-size:11px" >NAMA PROGRAM | NAMA KEGIATAN</th>
		<th style="font-size:11px" >JENIS PEKERJAAN</th>
		<th style="font-size:11px" >SATUAN/VOLUME</th>
		<th style="font-size:11px" >DANA KEGIATAN DISETUJUI</th>
		<th style="font-size:11px" >LOKASI</th>
		<th style="font-size:11px" >NAMA DESA - KECAMATAN</th>
		<th style="font-size:11px" >ASAL USULAN</th>
		<th style="font-size:11px" >STATUS USULAN</th>
		<th style="font-size:11px" >KEPUTUSAN</th>
		<th style="font-size:11px" >ALASAN</th>
	</tr>
</thead>
<tbody>
	<?php
		foreach ($musrenbangcam1 as $row) {
			$total = 0;
			$id_keputusan = 1;
			$musrenbangcam_detail = $this->m_musrenbang->get_data_musrenbangcam_detail($row->kode_kegiatan,$row->id_skpd,$id_keputusan);
			$counter = $this->m_musrenbang->get_total_kegiatan_musrenbangcam($row->kode_kegiatan,$row->id_skpd,$id_keputusan)->total;
	?>
			<tr >
				<td style="font-size:12px; " rowspan="<?php echo ($counter+1); ?>"align="center"><?php echo $row->kode_kegiatan; ?></td>
				<td style="font-size:12px; " rowspan="<?php echo ($counter+1); ?>"align="center">
					<?php
						if(!$row->nama_program_kegiatan)
						{
							echo "-";
						}
						else {
						 	echo $row->nama_program_kegiatan;
						}  
					?>
				</td>
				<td colspan="2" align="center"> - </td>
				<td style="font-size:11px" align="right"><?php echo "Rp ".Formatting::currency($row->total_jumlah_dana); ?></td>
				<td colspan="6" align="center"> - </td>
				<?php
					foreach ($musrenbangcam_detail as $row) {
				?>
					<tr >
						<td style="font-size:11px" align="center"><?php echo $row->jenis_pekerjaan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->volume." ".$row->satuan; ?></td>
						<td style="font-size:11px" align="right"><?php echo "Rp ".Formatting::currency($row->jumlah_dana); ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->lokasi; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->nama_desa." - ".$row->nama_kec; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->asal_usulan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->status_usulan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->status_keputusan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->alasan_keputusan; ?></td>
					</tr>
				<?php
					}
				?>
			</tr>
	<?php
		}
	?>
</tbody>
<?php } ?>
<!-- CETAK DATA MUSRENBANGCAM DENGAN NILAI id_keputusan = 2 (DITERIMA)-->
<?php if(!empty($musrenbangcam2)){ ?>
<thead>
	<tr >
		<th style="font-size:11px" >KODE KEGIATAN</th>
		<th style="font-size:11px" >NAMA PROGRAM | NAMA KEGIATAN</th>
		<th style="font-size:11px" >JENIS PEKERJAAN</th>
		<th style="font-size:11px" >SATUAN/VOLUME</th>
		<th style="font-size:11px" >DANA KEGIATAN DISETUJUI</th>
		<th style="font-size:11px" >LOKASI</th>
		<th style="font-size:11px" >NAMA DESA - KECAMATAN</th>
		<th style="font-size:11px" >ASAL USULAN</th>
		<th style="font-size:11px" >STATUS USULAN</th>
		<th style="font-size:11px" >KEPUTUSAN</th>
		<th style="font-size:11px" >ALASAN</th>
	</tr>
</thead>
<tbody>
	<?php
		foreach ($musrenbangcam2 as $row) {
			$id_keputusan = 2;
			$musrenbangcam_detail = $this->m_musrenbang->get_data_musrenbangcam_detail($row->kode_kegiatan,$row->id_skpd,$id_keputusan);
			$counter = $this->m_musrenbang->get_total_kegiatan_musrenbangcam($row->kode_kegiatan,$row->id_skpd,$id_keputusan)->total;
	?>
			<tr >
				<td style="font-size:12px" rowspan="<?php echo ($counter+1); ?>"align="center"><?php echo $row->kode_kegiatan; ?></td>
				<td style="font-size:12px" rowspan="<?php echo ($counter+1); ?>"align="center"><?php echo $row->nama_program_kegiatan; ?></td>
				<td colspan="2" align="center"> - </td>
				<td style="font-size:11px" align="right"><?php echo "Rp ".Formatting::currency($row->total_jumlah_dana); ?></td>
				<td colspan="6" align="center"> - </td>
				<?php
					foreach ($musrenbangcam_detail as $row) {
				?>
					<tr >
						<td style="font-size:11px" align="center"><?php echo $row->jenis_pekerjaan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->volume." ".$row->satuan; ?></td>
						<td style="font-size:11px" align="right"><?php echo "Rp ".Formatting::currency($row->jumlah_dana); ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->lokasi; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->nama_desa." - ".$row->nama_kec; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->asal_usulan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->status_usulan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->status_keputusan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->alasan_keputusan; ?></td>
					</tr>
				<?php
					}
				?>
			</tr>
	<?php
		}
	?>
</tbody>
<?php } ?>
<!-- CETAK DATA MUSRENBANGCAM DENGAN NILAI id_keputusan = 3 (DITOLAK)-->
<?php if(!empty($musrenbangcam3)){ ?>
<thead>
	<tr >
		<th style="font-size:11px" >KODE KEGIATAN</th>
		<th style="font-size:11px" >NAMA PROGRAM | NAMA KEGIATAN</th>
		<th style="font-size:11px" >JENIS PEKERJAAN</th>
		<th style="font-size:11px" >SATUAN/VOLUME</th>
		<th style="font-size:11px" >DANA KEGIATAN DISETUJUI</th>
		<th style="font-size:11px" >LOKASI</th>
		<th style="font-size:11px" >NAMA DESA - KECAMATAN</th>
		<th style="font-size:11px" >ASAL USULAN</th>
		<th style="font-size:11px" >STATUS USULAN</th>
		<th style="font-size:11px" >KEPUTUSAN</th>
		<th style="font-size:11px" >ALASAN</th>
	</tr>
</thead>
<tbody>
	<?php
		foreach ($musrenbangcam3 as $row) {
			$id_keputusan = 3;
			$musrenbangcam_detail = $this->m_musrenbang->get_data_musrenbangcam_detail($row->kode_kegiatan,$row->id_skpd,$id_keputusan);
			$counter = $this->m_musrenbang->get_total_kegiatan_musrenbangcam($row->kode_kegiatan,$row->id_skpd,$id_keputusan)->total;
	?>
			<tr >
				<td style="font-size:12px" rowspan="<?php echo ($counter+1); ?>"align="center"><?php echo $row->kode_kegiatan; ?></td>
				<td style="font-size:12px" rowspan="<?php echo ($counter+1); ?>"align="center"><?php echo $row->nama_program_kegiatan; ?></td>
				<td colspan="2" align="center"> - </td>
				<td style="font-size:11px" align="right"><?php echo "Rp ".Formatting::currency($row->total_jumlah_dana); ?></td>
				<td colspan="6" align="center"> - </td>
				<?php
					foreach ($musrenbangcam_detail as $row) {
				?>
					<tr >
						<td style="font-size:11px" align="center"><?php echo $row->jenis_pekerjaan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->volume." ".$row->satuan; ?></td>
						<td style="font-size:11px" align="right"><?php echo "Rp ".Formatting::currency($row->jumlah_dana); ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->lokasi; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->nama_desa." - ".$row->nama_kec; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->asal_usulan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->status_usulan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->status_keputusan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->alasan_keputusan; ?></td>
					</tr>
				<?php
					}
				?>
			</tr>
	<?php
		}
	?>
</tbody>
<?php } ?>