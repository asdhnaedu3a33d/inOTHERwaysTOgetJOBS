<script type="text/javascript">
	var element_program;
	$(document).ready(function() {

		$(".tbh_program").click(function(){
			var idpi = $(this).attr("id-pi");
			var ids = $(this).attr("id-s");
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("prioritas_pembangunan_rkpd/cru_program"); ?>',
				data: {idpi : idpi, ids : ids},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".edit_program").click(function(){
			var idpi = $(this).parent().parent().attr("id-pi");
			var ids = $(this).parent().parent().attr("id-s");
			var idpr = $(this).attr("id-pr");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("prioritas_pembangunan_rkpd/cru_program"); ?>',
				data: {idpi : idpi, ids : ids, idpr : idpr},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".delete_program").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data program ini?')) {
				var idpr = $(this).attr("id-pr");

		    	close_all();
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

				$.ajax({
					type: "POST",
					url: '<?php echo site_url("prioritas_pembangunan_rkpd/delete_program"); ?>',
					data: {idpr : idpr},
					success: function(msg){
						if (msg!="") {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							element_sasaran.trigger('click');
						}
					}
				});
			}
		});

		
		$("#program td.td-click").click(function(){
			if($(this).parent().next().is(":visible")){
				$(this).parent().next().fadeOut();
				return false;
			};

			$("tr.tr-frame-kegiatan").hide();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

			element_program = $(this).parent();
			var idpi = $(this).parent().attr("id-pi");
			var ids = $(this).parent().attr("id-s");
			var idpr = $(this).parent().attr("id-pr");
			var this_element = $(this);
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("prioritas_pembangunan_rkpd/view_kegiatan"); ?>',
				data: {idpi : idpi, ids : ids, idpr : idpr},
				success: function(msg){
					if (msg!="") {
						element_program.next().children().html(msg);
						element_program.next().fadeIn();
						element_program = this_element;
						$.blockUI({
							timeout: 1000,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};
				}
			});
		});

	});
</script>

<article class="module width_full">
 	<header>
 		<h3>
			Program dan Kegiatan
		</h3>
 	</header>
 	<div class="module_content">
		<table id="program" class="table-common" style="width: 100%">
			<tr>
				<th colspan="4">
					Program Pembangunan
					<a href="javascript:void(0)" id-pi="<?php echo $id_prioritas; ?>" id-s="<?php echo $id_sasaran; ?>" class="icon-plus-sign tbh_program" style="float: right" title="Tambah Program"></a>
				</th>
			</tr>
			<tr>
				<th width="40px">No</th>
				<th>Program</th>
				<th>Indikator</th>
				<th width="70px">Action</th>
			</tr>
			<?php if (!empty($program)): ?>
				<?php foreach ($program as $key => $value): ?>
					<tr class="tr-click" id-pi="<?php echo $id_prioritas; ?>" id-s="<?php echo $id_sasaran; ?>" id-pr="<?php echo $value->id_prio; ?>">
						<td class="td-click"><?php echo ($key+1).'.'; ?></td>
						<td class="td-click"><?php echo $value->prog_keg; ?></td>
						<td class="td-click">
							<?php 
								$indikator = $this->m_prioritas_pembangunan_rkpd->get_indikator_prog_keg($value->id_prio)->result();
								foreach ($indikator as $key_indikator => $value_indikator) {
									echo ($key_indikator+1).'. '.$value_indikator->indikator.'<br>';
								}
							?>
						</td>
						<td align="center">
							<a href="javascript:void(0)" id-pr="<?php echo $value->id_prio; ?>" class="icon-pencil edit_program" title="Edit Program Pembangunan"/>
							<a href="javascript:void(0)" id-pr="<?php echo $value->id_prio; ?>" class="icon-remove delete_program" title="Hapus Program"/>
						</td>
					</tr>
					<tr class="tr-frame-kegiatan" style="display: none">
						<td colspan="4"></td>
					</tr>
				<?php endforeach ?>
			<?php else: ?>
				<tr>
					<td colspan="4" align="center"><strong>Tidak Ada Data</strong></td>
				</tr>
				<tr class="tr-frame-kegiatan" style="display: none">
					<td colspan="4"></td>
				</tr>
			<?php endif ?>
		</table>
	</div>
</article>
