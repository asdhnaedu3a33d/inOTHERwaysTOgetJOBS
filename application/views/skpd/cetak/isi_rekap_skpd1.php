<?php 
	$max_col_keg =1;
?>
<!-- CETAK DATA REKAPITULASI SKPD DENGAN NILAI id_keputusan = 1 (BELUM DITENTUKAN)-->
<?php if(!empty($rekap_skpd1)){ ?>
<thead>
	<tr >
		<th style="font-size:11px" >KODE KEGIATAN</th>
		<th style="font-size:11px" >NAMA PROGRAM | NAMA KEGIATAN</th>
		<th style="font-size:11px" >JENIS PEKERJAAN</th>
		<th style="font-size:11px" >SATUAN VOLUME</th>
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
		foreach ($rekap_skpd1 as $row) {
			$id_keputusan = 1;
			$rekap_skpd_detail = $this->m_musrenbang->get_data_rekap_skpd_detail($row->kode_kegiatan,$row->id_skpd,$id_keputusan,$row->tahun);
			$counter = $this->m_musrenbang->get_total_kegiatan_rekap_skpd($row->kode_kegiatan,$row->id_skpd,$id_keputusan,$row->tahun)->total;
			$go_2_keg = FALSE;
			$total_for_iteration = $counter;
			if ($counter > $max_col_keg){
				$counter = $max_col_keg;
				$go_2_keg = TRUE;
			}
	?>
			<tr >
				<td style="font-size:11px; border-bottom:0;" rowspan="<?php echo ($counter); ?>"align="center"><?php echo $row->kode_kegiatan; ?></td>
				<td style="font-size:11px; border-bottom:0;" rowspan="<?php echo ($counter); ?>"align="center">
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
					foreach ($rekap_skpd_detail as $row) {
				?>
				<tr >
						<?php 
							if($total_for_iteration>1){
						?>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
						<?php 
							}
							else{
						?>
							<td style="border-top: 0;"></td>
							<td style="border-top: 0;"></td>
						<?php
							}
						?>
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