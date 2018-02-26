<article class="module width_full" style="width: 130%; margin-left: -15%;">
	<header>
	  <h3 style="width: auto;">
	  	Verifikasi Evaluasi Renja
	  </h3>
	  <!-- <a href="<?php echo site_url('evaluasi_renja/preview_evaluasi_renja') ?>"><input style="margin: 5px 12px 5px 3px; float: right;" type="button" value="Preivew" /></a>
	  <a href="<?php echo site_url('evaluasi_renja/preview_evaluasi_renja') ?>"><input style="margin: 5px 3px; float: right;" type="button" value="Cetak" /></a>	  
	  <a href="javascript:void(0)"><input style="margin: 5px 3px; float: right;" type="button" id="veri_btn" value="Verifikasi" /></a> -->
	</header>    
    <div class="module_content" style="overflow:auto">    	
    	<table class="table-common tablesorter" style="width:99%" >
    		<thead>
    			<tr>
    				<th width="20px">No</th>
    				<th>SKPD</th>
    				<th width="100px">Triwulan</th>
    				<th width="100px">Jumlah Data Verifikasi</th>
    				<th width="10px"><i style="margin:5px" class="icon2-script_gear" title="Aksi"></i></th>
    			</tr>
    		</thead>
    		<tbody>
		<?php
			$no=0;
			foreach ($veri_data as $row) {
				$no++;
		?>
				<tr>
					<td align="center"><?= $no ?></td>
					<td><?= $row->nama_skpd ?></td>
					<td align="center"><?= $row->triwulan ?></td>
					<td align="center"><?= $row->jumlah ?></td>
					<td align="center"><a href="<?= base_url('evaluasi_renja/veri_skpd/'.$row->id_skpd.'/'.$row->triwulan.'/'.$tahun) ?>" class="icon2-folder_go" title="Verifikasi"></a></td>
				</tr>
		<?php
			}
		?>
    		</tbody>
    	</table>
    </div>
</article>