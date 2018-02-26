<?php
	if (TRUE) {
		$enable_edit = TRUE;
		$enable_delete = TRUE;
	}else{
		$enable_edit = FALSE;
		$enable_delete = FALSE;
	}
?>
<!-- for strategi -->
<script type="text/javascript">
	var element_strategi;
	$(document).ready(function(){
		$(".tbh_strategi").click(function(){
			var idr = $(this).attr("id-r");
			var idt = $(this).attr("id-t");
			var ids = $(this).attr("id-s");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/cru_strategi"); ?>',
				data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					};
				}
			});
		});

		$(".remove-strategi").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data strategi ini?')) {
				close_all();

				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("rpjmd/delete_strategi"); ?>',
					data: {id_strategi: $(this).attr("id-st")},
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {
							element_sasaran.trigger( "click" );
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
						};
					}
				});
			}
		});

		$(".edit-strategi").click(function(){
			var idt = $(this).parent().parent().attr("id-t");
			var idr = $(this).parent().parent().attr("id-r");
			var ids = $(this).parent().parent().attr("id-s");

			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/cru_strategi"); ?>',
				data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_strategi: $(this).attr("id-st")},
				success: function(msg){
					if (msg != "") {
						$.facebox(msg);
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};
				}
			});
		});

		$("#strategi td.td-click").click(function(){
			if($(this).parent().next().is(":visible")){
				$(this).parent().next().fadeOut();
				return false;
			};

			$("tr.tr-frame-kebijakan").hide();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

			element_strategi = $(this).parent();
			var idt = $(this).parent().attr("id-t");
			var idr = $(this).parent().attr("id-r");
			var ids = $(this).parent().attr("id-s");
			var idst = $(this).parent().attr("id-st");
			var this_element = $(this);
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/get_kebijakan"); ?>',
				data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_strategi: idst},
				success: function(msg){
					if (msg!="") {
						element_strategi.next().children().html(msg);
						element_strategi.next().fadeIn();
						element_strategi = this_element;
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
<!-- end strategi -->
<!-- end urusan -->
<script>
$(document).ready(function(){
	//var element_program;
	$(".tbh_program").click(function(){
		var idr = $(this).attr("id-r");
		var idt = $(this).attr("id-t");
		var ids = $(this).attr("id-s");

		prepare_facebox();
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("rpjmd/cru_program"); ?>',
			data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids},
			success: function(msg){
				if (msg!="") {
					$.facebox(msg);
				};
			}
		});
	});

	$(".edit-program").click(function(){
		var idt = $(this).parent().parent().attr("id-t");
		var idr = $(this).parent().parent().attr("id-r");
		var ids = $(this).parent().parent().attr("id-s");

		prepare_facebox();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("rpjmd/cru_program"); ?>',
			data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_program : $(this).attr("id-p")},
			success: function(msg){
				if (msg != "") {
					$.facebox(msg);
					$.blockUI({
						message: msg.msg,
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
				};
			}
		});
	});

	$(".remove-program").click(function(){
		if (confirm('Apakah anda yakin untuk menghapus data program ini?')) {
			close_all();

			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/delete_program"); ?>',
				data: {id_program: $(this).attr("id-p")},
				dataType: "json",
				success: function(msg){
					if (msg.success==1) {
						tab_element = "program";
						element_sasaran.trigger( "click" );
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};
				}
			});
		}
	});

	$("#program td.td-click").click(function(){
		if($(this).parent().next().is(":visible")){
			$(this).parent().next().fadeOut();
			return false;
		};

		$("tr.tr-frame-skpd-bidang").hide();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});

		element_program = $(this).parent();
		var idr = $(this).parent().attr("id-r");
		var idt = $(this).parent().attr("id-t");
		var ids = $(this).parent().attr("id-s");
		var idp = $(this).parent().attr("id-p");

		var this_element = $(this);
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("rpjmd/get_urusan"); ?>',
			data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_program: idp},
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
			Program dan Strategi
			<a href="javascript:void(0)" class="icon-remove-sign close-strategi-urusan-frame" title="Tutup Layar"></a>
		</h3>
 	</header>
 	<div class="module_content">
		<div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#program_tab" data-toggle="tab">Program</a></li>
        <li><a href="#strategi_tab" data-toggle="tab">Strategi</a></li>
      </ul>
      <div class="tab-content">
				<!-- <div class="active tab-pane"> -->
					<div class="tab-pane" id="strategi_tab">
						<table id="strategi" class="table-common" style="width: 100%">
							<thead>
								<tr>
									<th colspan="10">
										Strategi
										<a href="javascript:void(0)" class="icon-plus-sign tbh_strategi" style="float: right" title="Tambah Strategi" id-r="<?php echo $id_rpjmd; ?>" id-t="<?php echo $id_tujuan; ?>" id-s="<?php echo $id_sasaran; ?>"></a>
									</th>
								</tr>
								<tr>
									<th>No</th>
									<th>Strategi</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							<?php
								if (!empty($strategi)) {
									$i=0;
									foreach ($strategi as $row) {
										$i++;
							?>
								<tr class="tr-click" id-r="<?php echo $row->id_rpjmd; ?>" id-t="<?php echo $row->id_tujuan; ?>" id-s="<?php echo $row->id_sasaran; ?>" id-st="<?php echo $row->id; ?>">
									<td class="td-click" width="50px"><?php echo $i; ?></td>
									<td class="td-click"><?php echo $row->strategi; ?></td>
									<td align="center" width="50px">
									<?php
										if ($enable_edit) {
									?>
										<a href="javascript:void(0)" id-st="<?php echo $row->id; ?>" class="icon-pencil edit-strategi" title="Edit Strategi"/>
									<?php
										}

										if ($enable_delete) {
									?>
										<a href="javascript:void(0)" id-st="<?php echo $row->id; ?>" class="icon-remove remove-strategi" title="Hapus Strategi"/>
									<?php
										}
									?>
									</td>
								</tr>
								<tr class="tr-frame-kebijakan" style="display: none">
									<td colspan="4"></td>
								</tr>
							<?php
									}
								}else{
							?>
								<tr>
									<td colspan="10" align="center">Tidak ada data...</td>
								</tr>
							<?php
								}
							?>
							</tbody>
						</table>
					</div>
				<!-- </div> -->
				<div class="active tab-pane" id="program_tab">
					<table id="program" class="table-common" style="width: 100%">
						<thead>
							<tr>
								<th colspan="10">
									Program
									<a href="javascript:void(0)" class="icon-plus-sign tbh_program" style="float: right" title="Tambah Program" id-r="<?php echo $id_rpjmd; ?>" id-t="<?php echo $id_tujuan; ?>" id-s="<?php echo $id_sasaran; ?>"></a>
								</th>
							</tr>
							<tr>
								<th>No</th>
								<th>Nama program</th>
								<th>Indikator</th>
								<th>Pagu RPJMD</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if (!empty($program)) {
								$i=0;
								foreach ($program as $row2) {
									$indikator = $this->m_rpjmd_trx->get_all_indikator_program_ng($row2->id);
									$i++;
						?>
							<tr class="tr-click" id-r="<?php echo $row2->id_rpjmd; ?>" id-t="<?php echo $row2->id_tujuan; ?>" id-s="<?php echo $row2->id_sasaran; ?>" id-p="<?php echo $row2->id; ?>">
								<td class="td-click" width="50px"><?php echo $i; ?></td>
								<td class="td-click"><?php echo $row2->nama_prog; ?></td>
								<td class="td-click">
								<?php
											$j = 0;
											foreach ($indikator as $row3) {
												$j++;
												echo $j.". ".$row3->indikator."<BR>";
											}
								?>
								</td>
								<td class="td-click">Rp. <?php echo formatting::currency($row2->pagu_rpjmd); ?></td>
								<td align="center" width="50px">
								<?php
									if ($enable_edit) {
								?>
									<a href="javascript:void(0)" id-p="<?php echo $row2->id; ?>" class="icon-pencil edit-program" title="Edit Program"/>
								<?php
									}

									if ($enable_delete) {
								?>
									<a href="javascript:void(0)" id-p="<?php echo $row2->id; ?>" class="icon-remove remove-program" title="Hapus Program"/>
								<?php
									}
								?>
								</td>
							</tr>
							<tr class="tr-frame-skpd-bidang" style="display: none">
								<td colspan="5"></td>
							</tr>
						<?php
								}
							}else{
						?>
							<tr>
								<td colspan="10" align="center">Tidak ada data...</td>
							</tr>
						<?php
							}
						?>
						</tbody>

					</table>
				</div>
			</div>
		</div>
	</div>
</article>
