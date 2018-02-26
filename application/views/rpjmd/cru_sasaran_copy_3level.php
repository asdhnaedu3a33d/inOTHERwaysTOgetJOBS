<script type="text/javascript">
	$(document).ready(function(){

		$('form#sasaran').validate({
			rules: {
				sasaran : "required",
				strategi : "required",
			}
		});

		$("#simpan").click(function(){
			$('#kebijakan_frame .kebijakan_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

		    var valid = $("form#sasaran").valid();
		    if (valid) {
		    	element.parent().next().hide();
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#sasaran").attr("action"),
					data: $("form#sasaran").serialize(),
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							$.facebox.close();
							element.trigger( "click" );
						};
					}
				});
		    };
		});


	});
</script>
<div style="width: 900px">
	<header>
		<h3>
	<?php
		if (!empty($sasaran)){
		    echo "Edit Data RPJMD";
		} else{
		    echo "Input Data RPJMD";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('rpjmd/save_sasaran');?>" method="POST" name="sasaran" id="sasaran" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_rpjmd" value="<?php echo $id_rpjmd; ?>" />
			<input type="hidden" name="id_tujuan" value="<?php echo $tujuan->id; ?>" />
			<table class="fcari" width="100%">
				<tbody>
					<tr>
						<td width="20%">Tujuan</td>
						<td width="80%"><?php echo $tujuan->tujuan; ?></td>
					</tr>
				</tbody>
			</table>


			<table width="100%">
				<thead>
					<tr>
						<th>Sasaran <a id="tambah_sasaran" class="icon-plus-sign" href="javascript:void(0)"></a></th>
						<th width="50px">Action</th>
					</tr>
				</thead>
				<tbody id="sasaran_frame" key="1">
					<tr class="bg-info table-sasaran">
						<td><textarea class="common sasaran_val" name="sasaran[1]"></textarea></td>
						<td align="center"></td>
					</tr>
					<tr>
						<td colspan="2">
							<table width="100%">
								<thead>
									<tr>
										<th>Strategi <a id-s="1" id="tambah_strategi" class="icon-plus-sign" href="javascript:void(0)"></a></th>
										<th width="50px">Action</th>
									</tr>
								</thead>
								<tbody id="strategi_frame" key="1">
									<tr class="bg-warning table-strategi">
										<td><textarea class="common strategi_val" name="strategi[1][1]"></textarea></td>
										<td align="center"></td>
									</tr>
									<tr>
										<td colspan="2">
											<table width="100%">
												<thead>
													<tr>
														<th>Arah Kebijakan <a id-s="1" id-st="1" id="tambah_kebijakan" class="icon-plus-sign" href="javascript:void(0)"></a></th>
														<th width="50px">Action</th>
													</tr>
												</thead>
												<tbody id="kebijakan_frame" key="1">
													<tr class="table-kebijakan">
														<td><textarea class="common kebijakan_val" name="kebijakan[1][1][1]"></textarea></td>
														<td align="center"></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>


		</form>
	</div>
	<footer>
		<div class="submit_link">
  			<input id="simpan" type="button" value="Simpan">
		</div>
	</footer>
</div>

<div style="display: none">
	<table>
		<tbody id="sasaran_box">
			<tr class="bg-info table-sasaran">
				<td><textarea class="common sasaran_val" name="sasaran[1]"></textarea></td>
				<td align="center"><a class="icon-remove hapus_sasaran" href="javascript:void(0)"></a></td>
			</tr>
			<tr class="strategi_frame">
				<td colspan="2">
					<table width="100%">
						<thead>
							<tr>
								<th>Strategi <a id="tambah_strategi" class="icon-plus-sign" href="javascript:void(0)"></a></th>
								<th width="50px">Action</th>
							</tr>
						</thead>
						<tbody key="1">
							<tr class="bg-warning table-strategi">
								<td><textarea class="common strategi_val" name="strategi[1][1]"></textarea></td>
								<td align="center"></td>
							</tr>
							<tr>
								<td colspan="2">
									<table width="100%">
										<thead>
											<tr>
												<th>Arah Kebijakan <a id-s="1" id-st="1" id="tambah_kebijakan" class="icon-plus-sign" href="javascript:void(0)"></a></th>
												<th width="50px">Action</th>
											</tr>
										</thead>
										<tbody key="1">
											<tr class="table-kebijakan">
												<td><textarea class="common kebijakan_val" name="kebijakan[1][1][1]"></textarea></td>
												<td align="center"></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div style="display: none">
	<table>
		<tbody id="strategi_box">
			<tr class="bg-warning table-strategi">
				<td><textarea class="common strategi_val" name="strategi[1][1]"></textarea></td>
				<td align="center"><a class="icon-remove hapus_strategi" href="javascript:void(0)"></a></td>
			</tr>
			<tr class="kebijakan_frame">
				<td colspan="2">
					<table width="100%">
						<thead>
							<tr>
								<th>Arah Kebijakan <a id-s="1" id-st="1" id="tambah_kebijakan" class="icon-plus-sign" href="javascript:void(0)"></a></th>
								<th width="50px">Action</th>
							</tr>
						</thead>
						<tbody key="1">
							<tr class="table-kebijakan">
								<td><textarea class="common kebijakan_val" name="kebijakan[1][1][1]"></textarea></td>
								<td align="center"></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div style="display: none">
	<table>
		<tbody id="kebijakan_box">
			<tr class="table-kebijakan">
				<td><textarea class="common kebijakan_val" name="kebijakan[1][1][1]"></textarea></td>
				<td align="center"><a class="icon-remove hapus_kebijakan" href="javascript:void(0)"></a></td>
			</tr>
		</tbody>
	</table>
</div>
