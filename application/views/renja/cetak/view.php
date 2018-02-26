<style type="text/css">
	.button-action{
		float: right;
		margin-top: 5px;
		margin-bottom: 5px;
		margin-left: 5px;
		margin-right: 15px;
	}
</style>
<script type="text/javascript">	
	$(document).ready(function(){
		$("select#ss_skpd").change(function(){
			$.blockUI({
				message: 'Web page sedang di akses, mohon ditunggu ...',
				css: window._css,
				overlayCSS: window._ovcss
			});

			var link = '<?php echo site_url("renja/cetak_renja_skpd_all"); ?>/'+$(this).val();
			$(location).attr('href',link);
		});		

		$("#cetak").click(function(){
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});

			if ($("#ss_skpd").val() != "") {
				skpd = "/"+$("#ss_skpd").val();
				var link = '<?php echo site_url("renja/do_cetak_renja"); ?>'+skpd;
				$(location).attr('href',link);
			}			
		});
	});	

</script>
<article class="module width_full">
	<header>
	  <h3>Cetak Data Renja</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
		<table class="tablesorter" style="width:99%">			
		
			<tr>
				<td>SKPD</td>
				<td colspan="6"><?php echo $dd_skpd; ?></td>
			</tr>
		
			<tr>		
				<td rowspan="2" align="center">Jumlah Data Kegiatan</td>
				<td align="center" colspan="2">Total Setiap Tahun</td>				
				<td rowspan="2" align="center">Total Keseluruhan</td>
			</tr>
			<tr>				
				<td align="center">Triwulan 1</td>
				<td align="center">Triwulan 2</td>
			</tr>
			<tr>						
				<td align="center"><?php echo $total_nominal_renja->count; ?> Data</td>		
				<td align="right"><?php echo (!empty($total_nominal_renja->nominal))?Formatting::currency($total_nominal_renja->nominal):0; ?></td>
				<td align="right"><?php echo (!empty($total_nominal_renja->nominal_thndpn))?Formatting::currency($total_nominal_renja->nominal_thndpn):0; ?></td>
				<td align="right"><?php echo Formatting::currency($total_nominal_renja->nominal+$total_nominal_renja->nominal_thndpn); ?></td>
			</tr>
		</table>
	</div>		
	<footer>
		<input type="button" class="button-action" id="cetak" value="Cetak" />
	</footer>
</article>