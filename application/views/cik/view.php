<?php		
	if (!empty($jendela_kontrol->baru) || !empty($jendela_kontrol->revisi) || (empty($jendela_kontrol->baru) 
	    && empty($jendela_kontrol->revisi) && empty($jendela_kontrol->kirim))) {
		$enable_add = TRUE;
		$enable_edit = TRUE;
		$enable_delete = TRUE;
	}else{
		$enable_add = FALSE;
		$enable_edit = FALSE;
		$enable_delete = FALSE;
	}
?>

<style type="text/css">
	.misi{
		margin: 5px;
	}

	.tujuan{
		margin-top: 2px;
		margin-bottom: 2px;
	}
	tr.tr-click:hover{		
		background-color: pink;
	}
	td.td-click{
		cursor: pointer;
	}

	#kegiatan-frame{
		margin-bottom: 25px;
	}

	#jendela_kontrol header, #jendela_kontrol footer, #jendela_kontrol h3{
		background: #337ab7;
		color: white;
	}

	#jendela_kontrol, #jendela_kontrol .module_content{
		background: #d9edf7;
	}

</style>
<script type="text/javascript">	
	var element_program;
	$(document).ready(function(){
		$(".tbh_program").click(function(){
			var idr = $(this).attr("id-r");
			var ids = $(this).attr("id-s");
			var idta = $(this).attr("id-ta");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("cik/cru_program_skpd"); ?>',
				data: {id_skpd: ids, tahun: idta, id: idr},
				success: function(msg){
					if (msg!="") {						
						$.facebox(msg);
					};	
				}
			});			
		});

		$(".remove-program").click(function(){			
			if (confirm('Apakah anda yakin untuk menghapus data program ini?')) {				
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("cik/delete_program"); ?>',
					data: {id: $(this).attr("idP")},
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {							
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});				
							reload_jendela_kontrol();			
						};	
					}
				});
			}
		});

		$(".edit-program").click(function(){
			var idr = $(this).parent().parent().attr("id-r");

			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("cik/cru_program_skpd"); ?>',
				data: { id: $(this).attr("idP")},
				success: function(msg){
					if (msg!="") {						
						$.facebox(msg);
						$.blockUI({							
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};	
				}
			});			
		});

		$("#program td.td-click").click(function(){
			element_program = $(this);
			if($(this).parent().next().is(":visible")){
				$(this).parent().next().fadeOut();
				return false;
			};

			$("tr.tr-frame-pro").hide();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			
			var idr = $(this).parent().attr("id-r");
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("cik/get_kegiatan_skpd"); ?>',
				data: {id: idr},				
				success: function(msg){
					if (msg!="") {						
						element_program.parent().next().children().html(msg);
						element_program.parent().next().fadeIn();
						$.blockUI({
							timeout: 1000,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};	
				}
			});
		});
		
		$(document).on("click", "#cetak", function(){
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = '<?php //echo site_url("renstra/do_cetak_renstra"); ?>';
			$(location).attr('href',link);
		});

		$(document).on("click", "#get_dpa", function(){
			$.blockUI({				
				css: window._css,				
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("cik/get_dpa"); ?>',
				dataType: "json",
				success: function(msg){
					catch_expired_session2(msg);
					if (msg.success==1) {						
						$.blockUI({
							message: msg.msg,
							css: window._css,
							timeout: 2000,
							overlayCSS: window._ovcss
						});
						location.reload();
					}
				}
				
			});
		});		
		
		reload_jendela_kontrol();
	});
	
	function reload_jendela_kontrol(){
		$("#jendela_kontrol").load("<?php echo site_url('cik/get_jendela_kontrol'); ?>");
	}
</script>

<article class="module width_full" id="jendela_kontrol"> 	
</article>

<article class="module width_full">
	<header>
 		<h3>
			Indikator Kinerja <?php echo $nama_skpd; ?>
		</h3>			
 	</header>
 	<div class="module_content">
		<table id="program" class="table-common" style="width: 99%">
			<thead>
				<tr>
					<th colspan="11">
					Program
                     <?php
						if ($enable_add) {
					?>
					<!--<a href="javascript:void(0)" class="icon-plus-sign tbh_program" style="float: right" title="Tambah Program" 
                     id-s="<?php echo $id_skpd; ?>" id-ta="<?php echo $ta; ?>"></a>-->
					<?php
							}
					?>
                    </th>
				</tr>
				<tr>
					<th>No</th>
					<th width="10%">Kode</th>
					<th>Program</th>
					<th>Indikator</th>					
					<!--<th>Action</th>-->
				</tr>
			</thead>
			<tbody>
			<?php
				if (!empty($program)) {					
					$i=0;
					foreach ($program as $row) {
						$i++;
						$style='';
						$indikator_program = $this->m_cik->get_indikator_prog_keg($row->id);
						$id= $row->id;
					if($row->id_dpa != NULL){
						$style='bgcolor="#D0BE2D"';
					} else {
						$style='bgcolor="#96CC3F"';
					}
			?>
				<tr class="tr-click" id-r="<?php echo $row->id; ?>">
					<td class="td-click" width="50px"><?php echo $i; ?></td>
					<td class="td-click" <?php echo $style;?>><?php echo $row->kd_urusan.". ".$row->kd_bidang.". ".$row->kd_program; ?></td>
					<td class="td-click"><?php echo $row->nama_prog_or_keg; ?></td>
					<td class="td-click">
			<?php
						$j = 0;
						foreach ($indikator_program as $row1) {
							$j++;
							echo $j.". ".$row1->indikator."<BR>";
						}
			?>				
					</td>
					<!--<td align="center" width="50px">
                     <?php
						if ($enable_edit) {
					?>
						<a href="javascript:void(0)" idP="<?php echo $id; ?>" class="icon-pencil edit-program" title="Edit Program"/>
                    <?php
						}
						if ($enable_delete) {
					?>
						<a href="javascript:void(0)" idP="<?php echo $id; ?>" class="icon-remove remove-program" title="Hapus Program"/>
                    <?php
						}
					?>	

					</td>-->
				</tr>
				<tr class="tr-frame-pro" id="kegiatan-frame" style="display: none">
					<td colspan="5"></td>
				</tr>				
			<?php
					}
				}else{
			?>
				<tr>
					<td colspan="5" align="center">Tidak ada data...</td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
	</div>
</article>
<div id="program-frame">	
</div>