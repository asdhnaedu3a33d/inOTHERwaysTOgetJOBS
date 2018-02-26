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

		$(document).on("click", "#tambah_sasaran", function(){
			var tbody = $(this).parent().parent().parent().next();
			key = tbody.attr("key");
			key++;
			tbody.attr("key", key);

			var name = "sasaran["+ key +"]";
			$("#sasaran_box textarea.sasaran_val").attr("name", name);

			$("#sasaran_box a#tambah_strategi").attr("id-s", "1");
			var name = "strategi["+ key +"][1]";
			$("#sasaran_box textarea.strategi_val").attr("name", name);

			$("#sasaran_box a#tambah_kebijakan").attr("id-s", "1");
			$("#sasaran_box a#tambah_kebijakan").attr("id-st", "1");
			var name = "kebijakan["+ key +"][1][1]";
			$("#sasaran_box textarea.kebijakan_val").attr("name", name);
			tbody.append($("#sasaran_box").html());
		});

		$(document).on("click", ".hapus_sasaran", function(){
			$(this).parent().parent().next(".strategi_frame").remove();
			$(this).parent().parent().remove();
		});

		$(document).on("click", "#tambah_strategi", function(){
			var id_sasaran = $(this).attr("id-s");
			var tbody = $(this).parent().parent().parent().next();
			key = tbody.attr("key");
			key++;
			tbody.attr("key", key);

			$("#strategi_box a#tambah_strategi").attr("id-s", key);
			var name = "strategi["+ id_sasaran +"]["+ key +"]";
			$("#strategi_box textarea.strategi_val").attr("name", name);

			$("#strategi_box a#tambah_kebijakan").attr("id-s", key);
			$("#strategi_box a#tambah_kebijakan").attr("id-st", "1");
			var name = "kebijakan["+ id_sasaran +"]["+ key +"][1]";
			$("#strategi_box textarea.kebijakan_val").attr("name", name);
			tbody.append($("#strategi_box").html());
		});

		$(document).on("click", ".hapus_strategi", function(){
			$(this).parent().parent().next(".kebijakan_frame").remove();
			$(this).parent().parent().remove();
		});

		$(document).on("click", "#tambah_kebijakan", function(){
			var id_sasaran = $(this).attr("id-s");
			var id_strategi = $(this).attr("id-st");
			var tbody = $(this).parent().parent().parent().next();
			key = tbody.attr("key");
			key++;
			tbody.attr("key", key);

			$("#kebijakan_box a#tambah_kebijakan").attr("id-s", id_sasaran);
			$("#kebijakan_box a#tambah_kebijakan").attr("id-st", key);
			var name = "kebijakan["+ id_sasaran +"]["+ id_strategi +"]["+ key +"]";
			$("#kebijakan_box textarea.kebijakan_val").attr("name", name);
			tbody.append($("#kebijakan_box").html());
		});

		$(document).on("click", ".hapus_kebijakan", function(){
			$(this).parent().parent().remove();
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
			<input type="hidden" name="id_sasaran" value="<?php if(!empty($sasaran->id)){echo $sasaran->id;} ?>" />
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
		<tbody id="sasaran_box" key="1">
			<tr class="bg-info table-sasaran">
				<td><textarea class="common sasaran_val" name="sasaran[1]"></textarea></td>
				<td align="center"></td>
			</tr>
			<tr class="strategi_frame">
				<td colspan="2">
					<table width="100%">
						<thead>
							<tr>
								<th>Strategi <a id-s="1" id="tambah_strategi" class="icon-plus-sign" href="javascript:void(0)"></a></th>
								<th width="50px">Action</th>
							</tr>
						</thead>
						<tbody>
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
										<tbody>
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
						<tbody>
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
