<style type="text/css">
	.no-border{
		border-collapse: collapse;
	}

	td.print-no-border{
		border: none;
	}
</style>

<div>	
	<table class="full_width no-border" style="font-size: 14px;">
    	<tr>
        	<th colspan="12" align="center"><?php echo "PERSETUJUAN USULAN HIBAH / BANSOS" ?></th>
        </tr>
       
        <tr>
        	<th colspan="12" align="center"><?php echo "KABUPATEN KLUNGKUNG"; ?></th>
        </tr>
        <tr>
        	<th colspan="12" align="center"><?php echo ""; ?></th>
        </tr>
        <tr>
        	<th colspan="12" align="center"><?php echo ""; ?></th>
        </tr>
       
    </table>
	
</div>
<div>
 
    <table class="full_width border" style="font-size: 11px;">
    	
			<thead>
				<tr>
					<th class="no-sort">No</th>
					<th>Pengusul</th>
					<th>Jenis Pekerjaan</th>
					<th>Desa Sasaran</th>
					<th>Kecamatan Sasaran</th>
					<th>Lokasi</th>
					<th>Jumlah Dana (Rp)</th>
					<th>SKPD</th>
					<th>Catatan</th>
					<th>Status RKPD</th>
					<th>Nominal Anggaran</th>
					<th>NO. Rekomendasi</th>
					<th>Tgl Rekomendasi</th>
					

				</tr>
			</thead>
			<tbody>
			
        	<?php
        	$no=1;
        	foreach ($usulanbansos as $row) {
        		
        		if ($row->pengusul=='3'){
				$pengusulbansos = $row->lainnya;
			}else{
				$pengusulbansos = $row->pengusulbansos;

			};
			if ($row->flag_masukRKPD=='0'){
				$StatusRKPD = "Tidak Disetujui";
			}else{
				$StatusRKPD = "Disetujui";
			};

			
        		?>
			
        	<tr> 
        	<td><?php echo $no ?></td>
			<td><?php echo $pengusulbansos ?></td>
			<td><?php echo $row->jenis_pekerjaan ?></td>
			<td><?php echo $row->nama_desa ?></td>
			<td><?php echo $row->nama_kec ?></td>
			<td><?php echo $row->lokasi ?></td>
			<td><?php echo  Formatting::currency($row->jumlah_dana,2) ?></td>
			<td><?php echo $row->nama_skpd ?></td>
			<td><?php echo $row->catatan ?></td>
			<td><?php echo $StatusRKPD ?></td>
			<td><?php echo  Formatting::currency($row->nominal_setuju,2) ?></td>
			<td><?php echo  $row->norekomendasi ?></td>
			<td><?php echo  $row->tglrekomendasi ?></td>
			
			
			</tr>
			<?php
				$no++;
			}
			?>
			
			</tbody>
		</table>
	
</div>		
