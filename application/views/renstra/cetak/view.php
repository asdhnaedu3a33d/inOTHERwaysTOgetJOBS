<style type="text/css">
	.button-action{		
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

			var link = '<?php echo site_url("renstra/cetak_renstra_skpd_all"); ?>/'+$(this).val();
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
				var link = '<?php echo site_url("renstra/do_cetak_renstra"); ?>'+skpd;
				$(location).attr('href',link);
			}			
		});
	});	

</script>
<article class="module width_full">
	<header>
	  <h3>Cetak Data Renstra</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
		<table class="tablesorter" style="width:99%">			
		
			<tr>
				<td>SKPD</td>
				<td colspan="6"><?php echo $dd_skpd; ?></td>
			</tr>
		
			<tr>		
				<td rowspan="2" align="center">Jumlah Data Kegiatan</td>
				<td align="center" colspan="5">Total Setiap Tahun</td>				
				<td rowspan="2" align="center">Total Keseluruhan</td>
			</tr>
			<tr>				
				<td align="center">Tahun 1</td>
				<td align="center">Tahun 2</td>				
				<td align="center">Tahun 3</td>				
				<td align="center">Tahun 4</td>				
				<td align="center">Tahun 5</td>				
			</tr>
			<tr>						
				<td align="center"><?php echo $total_nominal_renstra->count; ?> Data</td>		
				<td align="right"><?php echo (!empty($total_nominal_renstra->nominal_1))?Formatting::currency($total_nominal_renstra->nominal_1):0; ?></td>
				<td align="right"><?php echo (!empty($total_nominal_renstra->nominal_2))?Formatting::currency($total_nominal_renstra->nominal_2):0; ?></td>
				<td align="right"><?php echo (!empty($total_nominal_renstra->nominal_3))?Formatting::currency($total_nominal_renstra->nominal_3):0; ?></td>
				<td align="right"><?php echo (!empty($total_nominal_renstra->nominal_4))?Formatting::currency($total_nominal_renstra->nominal_4):0; ?></td>
				<td align="right"><?php echo (!empty($total_nominal_renstra->nominal_5))?Formatting::currency($total_nominal_renstra->nominal_5):0; ?></td>
				<td align="right"><?php echo Formatting::currency($total_nominal_renstra->nominal_1+$total_nominal_renstra->nominal_2+$total_nominal_renstra->nominal_3+$total_nominal_renstra->nominal_4+$total_nominal_renstra->nominal_5); ?></td>
			</tr>
		</table>
	</div>		
	<footer>
		<div class="submit_link text-right">
			<input type="button" class="button-action" id="cetak" value="Cetak" />
		</div>
	</footer>
</article>