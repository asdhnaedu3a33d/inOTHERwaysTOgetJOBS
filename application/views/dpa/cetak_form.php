<script type="text/javascript">
	$(document).ready(function () {
		$("#batal").click(function(){
			$.facebox.close();
		});

		$("#cetak").click(function(){
			var id = $('#id').val();
			var aksi = $('select[name=aksi]').val();
			var id_skpd = $('#id_skpd').val();
			var t_anggaran = $('#t_anggaran').val();
			var link = '<?php echo site_url('dpa/cetak_aksi'); ?>/'+id_skpd+'/'+t_anggaran+'/'+aksi+'/'+id;
			
			// alert(link);
			// $.blockUI({
			// 	css: window._css,
			// 	overlayCSS: window._ovcss
			// });

			window.location.replace(link);
	    	
		});
	});
</script>
<div style="width: 900px">
	<header>
		<h3>
			Cetak Aksi
		</h3>
	</header>
	<div class="module_content">
		<input type="hidden" id="id" value="<?php echo $id ?>">
		<input type="hidden" id="id_skpd" value="<?php echo $id_skpd ?>">
		<input type="hidden" id="t_anggaran" value="<?php echo $t_anggaran ?>">
		<?php echo form_dropdown('aksi', $aksi); ?>
	</div>
	<footer>
		<div class="submit_link">
  			<input id="cetak" type="button" value="Cetak">
  			<input id="batal" type="button" value="Batal">
		</div> 
	</footer>
</div>