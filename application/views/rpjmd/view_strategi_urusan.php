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
	var element_urusan;
	$(".tbh_urusan").click(function(){
		var idr = $(this).attr("id-r");
		var idt = $(this).attr("id-t");
		var ids = $(this).attr("id-s");
		var idp = $(this).attr("id-p");

		prepare_facebox();
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("rpjmd/cru_urusan"); ?>',
			data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_program : idp},
			success: function(msg){
				if (msg!="") {
					$.facebox(msg);
				};
			}
		});
	});

	$(".edit-urusan").click(function(){
		var idt = $(this).parent().parent().attr("id-t");
		var idr = $(this).parent().parent().attr("id-r");
		var ids = $(this).parent().parent().attr("id-s");
		var idp = $(this).parent().parent().attr("id-p");

		prepare_facebox();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("rpjmd/cru_urusan"); ?>',
			data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_program : idp, id_urusan: $(this).attr("id-ur")},
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

	$(".remove-urusan").click(function(){
		if (confirm('Apakah anda yakin untuk menghapus data urusan ini?')) {
			close_all();

			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/delete_urusan"); ?>',
				data: {id_urusan: $(this).attr("id-ur")},
				dataType: "json",
				success: function(msg){
					if (msg.success==1) {
						tab_element = "urusan";
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
});
</script>


<article class="module width_full">
	<header>
 		<h3>
			Strategi dan Urusan
			<a href="javascript:void(0)" class="icon-remove-sign close-strategi-urusan-frame" title="Tutup Layar Kebijakan"></a>
		</h3>
 	</header>
 	<div class="module_content">
		<div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#strategi_tab" data-toggle="tab">Strategi</a></li>
        <li><a href="#urusan_tab" data-toggle="tab">Urusan</a></li>
      </ul>
      <div class="tab-content">
				<!-- <div class="active tab-pane"> -->
					<div class="active tab-pane" id="strategi_tab">
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
				<div class="tab-pane" id="urusan_tab">
					<table id="urusan" class="table-common" style="width: 100%">
						<thead>
							<tr>
								<th colspan="10">
									Urusan
									<a href="javascript:void(0)" class="icon-plus-sign tbh_urusan" style="float: right" title="Tambah Urusan" id-r="<?php echo $id_rpjmd; ?>" id-t="<?php echo $id_tujuan; ?>" id-s="<?php echo $id_sasaran; ?>" id-p="<?php echo $id_prog; ?>"></a>
								</th>
							</tr>
							<tr>
								<th>No</th>
								<th>Bidang</th>
								<th>SKPD</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if (!empty($urusan)) {
								$i=0;
								foreach ($urusan as $row2) {
									$skpd_bidang = $this->m_rpjmd_trx->get_all_skpd_bidang($row2->id);
									$i++;
						?>
							<tr class="tr-click" id-r="<?php echo $row2->id_rpjmd; ?>" id-t="<?php echo $row2->id_tujuan; ?>" id-s="<?php echo $row2->id_sasaran; ?>" id-p="<?php echo $row2->id_prog; ?>" id-ur="<?php echo $row2->id; ?>">
								<td class="td-click" width="50px"><?php echo $i; ?></td>
								<td class="td-click"><?php echo $row2->Nm_Bidang; ?></td>
								<td class="td-click">
								<?php
											$j = 0;
											foreach ($skpd_bidang as $row3) {
												$j++;
												echo $j.". ".$row3->nama_skpd."<BR>";
											}
								?>
								</td>
								<td align="center" width="50px">
								<?php
									if ($enable_edit) {
								?>
									<a href="javascript:void(0)" id-ur="<?php echo $row2->id; ?>" class="icon-pencil edit-urusan" title="Edit Urusan"/>
								<?php
									}

									if ($enable_delete) {
								?>
									<a href="javascript:void(0)" id-ur="<?php echo $row2->id; ?>" class="icon-remove remove-urusan" title="Hapus Urusan"/>
								<?php
									}
								?>
								</td>
							</tr>
							<tr class="tr-frame-urusan" style="display: none">
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
			</div>
		</div>
	</div>
</article>
