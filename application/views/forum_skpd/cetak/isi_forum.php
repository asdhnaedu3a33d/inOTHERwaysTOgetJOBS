<thead>
	<tr>
		<th style="font-size:11px" >Kode Kegiatan</th>
		<th style="font-size:11px" >Nama Program | Nama Kegiatan</th>
		<th style="font-size:11px" >Jenis Pekerjaan</th>
		<th style="font-size:11px" >Satuan Volume</th>
		<th style="font-size:11px" >Dana Kegiatan Disetujui</th>
		<th style="font-size:11px" >Lokasi</th>
		<th style="font-size:11px" >Nama Desa - Kecamatan</th>
		<th style="font-size:11px" >Asal Usulan</th>
		<th style="font-size:11px" >Status Usulan</th>
		<th style="font-size:11px" >Keputusan</th>
	</tr>
</thead>
<tbody>
	<?php
		foreach ($data_forum as $row) {
			$forum_detail = $this->m_musrenbang->get_data_forum_detail($row->kode_kegiatan,$row->id_skpd);
			$counter = $this->m_musrenbang->get_total_kegiatan($row->kode_kegiatan,$row->id_skpd)->total;
	?>
			<tr>
				<td style="font-size:12px" rowspan="<?php echo ($counter+1); ?>"align="center"><?php echo $row->kode_kegiatan; ?></td>
				<td style="font-size:12px" rowspan="<?php echo ($counter+1); ?>"align="center"><?php echo $row->nama_program_kegiatan; ?></td>
				<td ></td>
				<td ></td>
				<td style="font-size:11px" align="right"><?php echo "Rp ".Formatting::currency($row->total_jumlah_dana); ?></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<?php
					foreach ($forum_detail as $row) {
				?>
					<tr>
						<td style="font-size:11px" align="center"><?php echo $row->jenis_pekerjaan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->volume." ".$row->satuan; ?></td>
						<td style="font-size:11px" align="right"><?php echo "Rp ".Formatting::currency($row->jumlah_dana); ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->lokasi; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->nama_desa." - ".$row->nama_kec; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->asal_usulan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->status_usulan; ?></td>
						<td style="font-size:11px" align="center"><?php echo $row->status_keputusan; ?></td>
					</tr>
				<?php
					}
				?>
			</tr>
	<?php
		}
	?>
</tbody>