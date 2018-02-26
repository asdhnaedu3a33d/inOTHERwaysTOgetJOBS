<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click", "#cetak", function(){
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = '<?php echo site_url("rpjmd/do_cetak"); ?>';
			$(location).attr('href',link);
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click", "#cetak1", function(){
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = '<?php echo site_url("rpjmd/do_cetak_misi_tujuan"); ?>';
			$(location).attr('href',link);
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click", "#cetak2", function(){
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = '<?php echo site_url("rpjmd/do_cetak_indikator_program"); ?>';
			$(location).attr('href',link);
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click", "#cetak3", function(){
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = '<?php echo site_url("rpjmd/do_cetak_program_daerah"); ?>';
			$(location).attr('href',link);
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click", "#CetakLaporan", function(){
			var link = '';
			var jenis=$('#jenis').val();
			if (jenis=='cetak') {
				link = '<?php echo site_url("rpjmd/do_cetak"); ?>';
			}else if(jenis=='cetak1'){
				link = '<?php echo site_url("rpjmd/do_cetak_misi_tujuan"); ?>';
			}else if(jenis=='cetak2'){
				link = '<?php echo site_url("rpjmd/do_cetak_indikator_program"); ?>';
			}else if(jenis=='cetak3'){
				link = '<?php echo site_url("rpjmd/do_cetak_indikasi_program_prioritas"); ?>';
			}else if(jenis=='cetak4'){
				link = '<?php echo site_url("rpjmd/do_cetak_indikator_tujuan"); ?>';
			}else if(jenis=='cetak5'){
				link = '<?php echo site_url("rpjmd/do_cetak_indikator_program_renstra"); ?>';
			}else if(jenis=='cetak61'){
				link = '<?php echo site_url("rpjmd/do_cetak_arah_kebijakan"); ?>';
			}else if(jenis=='cetak11'){
				link = '<?php echo site_url("rpjmd/do_cetak_pemerintahan_organisasi"); ?>';
			}else if(jenis=='cetak12'){
				link = '<?php echo site_url("rpjmd/do_cetak_organisasi_pemerintahan"); ?>';
			}else if(jenis=='cetak71'){
				link = '<?php echo site_url("rpjmd/do_cetak_program_prioritas"); ?>';
			};
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			$(location).attr('href',link);
		});
	});
</script>

<style type="text/css">
	.title{
		font-size: 14px;
		font-weight: bold;
	}
</style>
<article class="module width_full" style="width: 138%; margin-left: -19%;">
 	<header>
 		<h3>
			Cetak RPJMD
		</h3>
 	</header>
 	<div class="module_content">
 	 <div class="form-group">
              <select class="form-control"  id="jenis">
				<!-- <option  value="cetak">Cetak Misi Tujuan </option> -->
				<option  value="cetak1">Cetak Tabel 5.1 Tujuan dan Sasaran</option>
				<option  value="cetak61">Cetak Tabel 6.1 Strategi dan Arah Kebijkan</option>
				<option  value="cetak71">Cetak Tabel 7.1 Program Prioritas</option>
				<option  value="cetak3">Cetak Tabel 8.1 Indikasi Program Prioritas</option>
				<option  value="cetak11">Cetak Tabel 8.2 Ringkasan Anggaran Menurut Urusan Pemerintahan dan Organisasi</option>
				<option  value="cetak12">Cetak Tabel 8.3 Ringkasan Anggaran Menurut Organisasi dan Urusan Pemerintahan</option>
				<option  value="cetak4">Cetak Tabel 9.1 Indikator Tujuan</option>
				
            	</select>
              </div>
		<?php
			//echo $cetak;
		?>
 	</div>

 	<footer>
 		<div class="submit_link">
 			<input id="CetakLaporan" type="button" value="Cetak" />
			<input type="button" value="Back" onclick="history.go(-1)" />
		</div>
 	</footer>
</article>
