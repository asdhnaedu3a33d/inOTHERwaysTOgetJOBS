<?php
	if (TRUE) {
		$enable_add = TRUE;
	}else{
		$enable_add = FALSE;
	}
?>
<style type="text/css">
	.table-common, .table-display{
			border: 2px solid #000;
	}

	.table-common>thead>tr>th, .table-common>tbody>tr>th, .table-common>tfoot>tr>th, .table-common>thead>tr>td, .table-common>tbody>tr>td, .table-common>tfoot>tr>td ,
	.table-display>thead>tr>th, .table-display>tbody>tr>th, .table-display>tfoot>tr>th, .table-display>thead>tr>td, .table-display>tbody>tr>td, .table-display>tfoot>tr>td
	{
			border: 1px solid #000 !important;
	}
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
	var element;
	var tab_element;
	$(document).ready(function(){

		$("td.td-click").click(function(){
			close_all();
			if($(this).parent().next().is(":visible")){
				$(this).parent().next().fadeOut();
				return false;
			};

			$("tr.tr-frame").hide();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

			element = $(this).parent();
			var idt = element.attr("id-t");
			var idr = element.attr("id-r");
			var this_element = $(this);
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/get_sasaran"); ?>',
				data: {id_rpjmd: idr, id_tujuan: idt},
				success: function(msg){
					if (msg!="") {
						element.next().children().html(msg);
						element.next().fadeIn();
						element = this_element;
						tab_element = "program";
						$.blockUI({
							timeout: 1000,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};
				}
			});
		});

		$(".tbh_sasaran").click(function(){
			close_all();
			element = $(this).parent().parent().find("td.td-click");
			var idt = $(this).parent().parent().attr("id-t");
			var idr = $(this).parent().parent().attr("id-r");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/cru_sasaran"); ?>',
				data: {id_rpjmd: idr, id_tujuan: idt},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					};
				}
			});
		});

		$(document).on("click", ".close-strategi-urusan-frame", function(){
			close_indikator();
			close_program();
		});

		$(document).on("click", ".close-program-frame", function(){
			close_program();
		});

		$(document).on("click", "#kirim_renstra", function(){
			prepare_facebox();
			$.facebox({div: '<?php echo site_url("renstra/kirim_renstra"); ?>'});
		});

	});

	function close_indikator(){
		$("#indikator-frame article").remove();
	}

	function close_program(){
		$("#kegiatan-frame article").remove();
	}

	function close_all(){
		close_program();
		close_indikator();
	}
</script>

<article class="module width_full">
 	<header>
 		<h3>
			RPJMD
		</h3>
 	</header>
 	<div class="module_content">
		<table class="fcari" width="100%">
			<tbody>
				<tr>
					<td>Visi</td>
					<td>
						<?php echo $rpjmd->visi; ?>
					</td>
				</tr>
				<tr>
					<td>Misi</td>
					<td>
					<?php
						$i=0;
						$no_misi= array();
						foreach ($rpjmd_misi->result() as $row) {
							$i++;
							$no_misi[$row->id]=$i;
					?>
						<div class="misi"><?php echo $i.". ".$row->misi; ?></div>
					<?php
						}
					?>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="table-common" style="width: 99%">
			<thead>
				<tr>
					<th>No. Misi</th>
					<th>Tujuan</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$i=0;
				foreach ($rpjmd_tujuan as $row) {
					$i++;
			?>
				<tr class="tr-click" id-r="<?php echo $row->id_rpjmd; ?>" id-t="<?php echo $row->id; ?>">
					<td class="td-click" width="50px"><?php echo $no_misi[$row->id_misi]; ?></td>
					<td class="td-click"><?php echo $row->tujuan; ?></td>
					<td align="center" width="50px">
					<?php
						if ($enable_add) {
					?>
						<a href="javascript:void(0)" class="icon-plus-sign tbh_sasaran" title="Tambah Sasaran"/>
					<?php
						}
					?>
					</td>
				</tr>
				<tr class="tr-frame" style="display: none">
					<td colspan="3"></td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
 	</div>
</article>
<div id="indikator-frame">
</div>
<div id="kegiatan-frame">
</div>
