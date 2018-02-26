<script type="text/javascript">
	function veri_renstra(urusan, bidang){
		window.location = '<?php echo site_url("renstra/veri_view_akhir")?>/' + urusan + '/' +bidang;
	}
</script>
<article class="module width_full">
	<header>
	  <h3>Verifikasi Renstra Sesuai RPJMD (Verifikasi RPJMD)</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
		<table id="renstra" class="table-common" style="width:99%">
			<thead>
				<tr>
					<th width="10px">No</th>								
					<th>Bidang Urusan</th>					
					<th>Total Kegiatan Pada Semua Program</th>
					<th>Action</th>
				</tr>				
			</thead>
			<tbody>
		<?php
			$i=0;
			foreach ($bidang_urusan as $row) {		
				$i++;
		?>
				<tr>
					<td align="center"><?php echo $i; ?></td>
					<td><?php echo $row->Nm_Urusan." ".$row->Nm_Bidang; ?></td>
					<td align="center"><?php echo $row->jml_data; ?></td>
					<td align="center"><a href="javascript:void(0)" onclick="veri_renstra('<?php echo $row->Kd_Urusan; ?>','<?php echo $row->Kd_Bidang; ?>')" class="icon-edit" title="Verifikasi Renstra"/></td>
				</tr>
		<?php
			}
		?>
			</tbody>
		</table>
	</div>
</article>