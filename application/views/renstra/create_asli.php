<style type="text/css">
	.misi{
		margin-top: 2px;
		margin-bottom: 2px;
	}

	.tujuan{
		margin-top: 2px;
		margin-bottom: 2px;
	}

	.table-common textarea.common{
		border: none; 
		width: 100%; 
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box; 
			box-sizing: border-box;
	}

	.hapus_misi{
		vertical-align: middle;
		text-align: center;
	}	
</style>
<script type="text/javascript">
	$(document).ready(function(){				
		$('form#renstra').validate({rules: {
			  visi : "required"
			}			
		});	    

		$("#simpan").click(function(){
			$('#misi_frame .misi_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .tujuan_val').each(function () {				
			    $(this).rules('add', {
			        required: true
			    });
			});

		    var valid = $("form#renstra").valid();
		    if (valid) {
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
		    	$("form#renstra").submit();
		    };
		});

		$(document).on("click", "#tambah_misi", function(){
			key = $("#misi_frame").attr("key");
			key++;
			$("#misi_frame").attr("key", key);

			var name = "misi["+ key +"]";
			$("#misi_box textarea.misi_val").attr("name", name);
			$("#misi_box a#tambah_tujuan").attr("id-m", key);			
			var name = "tujuan["+ key +"][1]";
			$("#misi_box textarea.tujuan_val").attr("name", name);
			$("#misi_frame").append($("#misi_box").html());
		});
		
		$(document).on("click", ".hapus_misi", function(){
			$(this).parent().parent().next(".tujuan_frame").remove();
			$(this).parent().parent().remove();			
		});
		
		$(document).on("click", "#tambah_tujuan", function(){
			var id_misi = $(this).attr("id-m");
			var tbody = $(this).parent().parent().parent().next();
			key = tbody.attr("key");
			key++;
			tbody.attr("key", key);

			var name = "tujuan["+ id_misi +"]["+ key +"]";
			$("#tujuan_box textarea").attr("name", name);
			tbody.append($("#tujuan_box").html());
		});
		
		$(document).on("click", ".hapus_tujuan", function(){
			$(this).parent().parent().remove();
		});
	});
</script>
<article class="module width_full">
 	<header>
 		<h3>
		<?php
			if (!empty($renstra)){
			    echo "Edit Data Renstra";
			} else{
			    echo "Input Data Renstra";
			}
		?>
		</h3>
 	</header>
 	<div class="module_content">
 		<form action="<?php echo site_url('renstra/save_skpd');?>" method="POST" name="renstra" id="renstra" accept-charset="UTF-8" enctype="multipart/form-data" >
 			<input type="hidden" name="id_renstra" value="<?php if(!empty($renstra->id)){echo $renstra->id;} ?>" />
 			<table class="fcari" width="100%">
 				<tbody>
 					<tr>
 						<td width="20%">SKPD</td>
 						<td width="80%"><?php echo $skpd->nama_skpd; ?></td>
					</tr> 					
					<tr class="bg-warning table-visi">
						<td>Visi</td>
						<td>
							<textarea class="common" name="visi"><?php if(!empty($renstra->visi)){echo $renstra->visi;} ?></textarea>
						</td>
					</tr>					
 				</tbody>
 			</table>
 			<table class="table-common" width="99%">
 				<thead>
 					<tr>
 						<th>Misi <a id="tambah_misi" class="icon-plus-sign" href="javascript:void(0)"></a></th>
 						<th width="50px">Action</th>
 					</tr>
 				</thead>
 				<tbody id="misi_frame" key="<?php echo (!empty($renstra_misi))?$renstra_misi->num_rows():'1'; ?>">
 				<?php
					if (!empty($renstra_misi)) {
						$i=0;						
						foreach ($renstra_misi->result() as $row) {
							$i++;
				?>
					<input type="hidden" name="id_misi[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
					<tr class="bg-info table-misi">
 						<td> 							
 							<textarea class="common misi_val" name="misi[<?php echo $i; ?>]"><?php echo $row->misi; ?></textarea>
						</td>
 						<td align="center">
 				<?php
 							if ($i > 1) { 								
 				?>
 							<a class="icon-remove hapus_misi" href="javascript:void(0)"></a>
 				<?php
 							}
 				?> 							
						</td>
 					</tr>
 					<tr class="tujuan_frame">
 						<td colspan="2">
 							<table class="table-common" width="100%">
 								<thead>
				 					<tr>
				 						<th>Tujuan <a id-m="<?php echo $i; ?>" id="tambah_tujuan" class="icon-plus-sign" href="javascript:void(0)"></a></th>
				 						<th width="50px">Action</th>
				 					</tr>
				 				</thead>
			 				<?php
			 					$renstra_tujuan = $this->m_renstra_trx->get_each_renstra_tujuan($row->id_renstra, $row->id);			 					
			 				?>
				 				<tbody key="<?php echo (!empty($renstra_tujuan))?$renstra_tujuan->num_rows():'1'; ?>">
				 				<?php
									if (!empty($renstra_tujuan)) {
										$j=0;										
										foreach ($renstra_tujuan->result() as $row1) {
											$j++;											
								?>
									<input type="hidden" name="id_tujuan[<?php echo $i; ?>][<?php echo $j; ?>]" value="<?php echo $row1->id; ?>">
									<tr>
				 						<td>				 							
				 							<textarea class="common tujuan_val" name="tujuan[<?php echo $i; ?>][<?php echo $j; ?>]"><?php echo $row1->tujuan; ?></textarea>
			 							</td>
				 						<td align="center">
				 				<?php
				 							if ($j > 1) {
				 				?>
				 							<a class="icon-remove hapus_tujuan" href="javascript:void(0)"></a>
				 				<?php
				 							}
				 				?>
			 							</td>
				 					</tr>
								<?php
										}
									}

									if(empty($renstra_tujuan)){
								?>
									<tr>
				 						<td><textarea class="common tujuan_val" name="tujuan[<?php echo $i; ?>][1]"></textarea></td>
				 						<td align="center"></td>
				 					</tr>
								<?php
									}
								?>				 					
				 				</tbody>
 							</table>
 						</td>
 					</tr>
				<?php
						}
					}else{
				?>					
 					<tr class="table-misi">
						<td><textarea class="common misi_val" name="misi[1]"></textarea></td>
						<td align="center"></td>
					</tr>
					<tr class="tujuan_frame">
						<td colspan="2">
							<table class="table-common" width="100%">
								<thead>
									<tr>
										<th>Tujuan <a id-m="1" id="tambah_tujuan" class="icon-plus-sign" href="javascript:void(0)"></a></th>
										<th width="50px">Action</th>
									</tr>
								</thead>
								<tbody key="1">
									<tr>
										<td><textarea class="common tujuan_val" name="tujuan[1][1]"></textarea></td>
										<td align="center"></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				<?php
					}
				?>				
 				</tbody>
 			</table>
 		</form> 		
 	</div> 	
 	<footer>
		<div class="submit_link">  
  			<input type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type="button" value="Back" onclick="history.go(-1)" />
		</div> 
	</footer>
</article>
<div style="display: none">
	<table>
		<tbody id="misi_box">
			<tr class="bg-info table-misi">
				<td><textarea class="common misi_val" name="misi[1]"></textarea></td>
				<td align="center"><a class="icon-remove hapus_misi" href="javascript:void(0)"></a></td>
			</tr>
			<tr class="tujuan_frame">
				<td colspan="2">
					<table class="table-common" width="100%">
						<thead>
							<tr>
								<th>Tujuan <a id-m="1" id="tambah_tujuan" class="icon-plus-sign" href="javascript:void(0)"></a></th>
								<th width="50px">Action</th>
							</tr>
						</thead>						
						<tbody key="1">
							<tr>
								<td><textarea class="common tujuan_val" name="tujuan[1][1]"></textarea></td>
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
		<tbody id="tujuan_box">
			<tr>
				<td><textarea class="common tujuan_val" name="tujuan[1][1]"></textarea></td>
				<td align="center"><a class="icon-remove hapus_tujuan" href="javascript:void(0)"></a></td>
			</tr>
		</tbody>		
	</table>
</div>