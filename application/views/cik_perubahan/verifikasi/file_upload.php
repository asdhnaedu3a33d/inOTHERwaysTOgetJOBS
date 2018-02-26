<style type="text/css">
	.child1-box{
		float: left;
		width: 85%;
		margin-top: 5px;
		border-radius: 5px;
		border: 2px solid #8AC007;
	}
	.child2-box{
		float: right;
		width: 13%;
		margin-top: 10px;
	}
	.parent{
		width: 90%;
		padding: 5px;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$("#add").click(function(){
			var index = $(".parent input#upload_length").val();
			index++;
			$("#add-box-upload input#name_file").attr("name", "name_file["+index+"]");
			$("#add-box-upload input#userfile").attr("name", "userfile["+index+"]");
			$(".parent input#upload_length").val(index);
			$("#frame").append($("#add-box-upload").html());
			$("#add-box-upload input#name_file").attr("name", "");
			$("#add-box-upload input#userfile").attr("name", "");
		});

		$(document).on('click', '.batal-upload', function(){
			var id = $(this).attr("idF");
			if (id != "") {
				$(".parent").append('<input type="hidden" name="hapus_file[]" value="'+ id +'"/>');
			};
			$(this).parent().parent().remove();
		});

		$("#frame .change").click(function(){
			$(this).parent().find(".userfile").show();
			$(this).parent().find("#old").hide();
			$(this).hide();
		});
	});
</script>
<div class="parent">
	<input type="button" id="add" value="Tambah File Upload" style="display:none"/>
	<input type="hidden" id="upload_length" name="upload_length" value="<?php if(!empty($mp_jmlfile)){echo $mp_jmlfile;} ?>" />
	<div id="frame">
	<?php
	if (!empty($mp_filefiles)) {
		$i = 0;
		foreach ($mp_filefiles as $row) {
			$i++;
	?>
			<div id="box">
				<div class="child1-box">
					<table style="border: 1px black;">
	        			<tr>
	        				<td>Nama File</td>
	        				<td><input type="text" id="name_file" name="name_file[<?php echo $i; ?>]" value="<?php echo $row->name; ?>"/></td>
	        			</tr>
	        			<tr>
	        				<td>File</td>
	        				<td>
	        					<a id="old" href="<?php echo site_url($row->location); ?>"><?php echo $row->file; ?></a>
	        					<input type="button" class="change" value="Ganti File" style="display:none"/>
	        					<input type="hidden" name="id_userfile[<?php echo $i; ?>]" value="<?php echo $row->id; ?> style="display:none"" />
	        					<input style="display: none" type="file" class="userfile" id="userfile" name="userfile[<?php echo $i; ?>]" accept="application/pdf" />
	        				</td>
	        			</tr>
	        			
	        		</table>
        		</div>
        		<div class="child2-box">
        			<a class="batal-upload" idF="<?php echo $row->id; ?>" href="#" title="Batalkan Upload File" style="display:none"><img src="<?php echo site_url('asset/images/icn_alert_error.png'); ?>"></a>
    			</div>
			</div>
	<?php
		}
	}
	?>
	</div>
	<div id="add-box-upload" style="display: none;">
		<div id="box">
			<div class="child1-box">
				<table style="border: 1px black;">
        			<tr>
        				<td>Nama File</td>
        				<td><input type="text" id="name_file" name="" /></td>
        			</tr>
        			<tr>
        				<td>File</td>
        				<td>
        					<input type="file" class="userfile" id="userfile" name="" accept="application/pdf" />
        				</td>
        			</tr>
        			<tr>
        				<td></td>
        				<td>
        					<i>*Keterangan dapat diisi nama lokasi yang lebih detail</i>
	        					<BR><i>*File harus dalam format pdf, Ukuran Maksimum file = 2Mb.</i>
        				</td>
        			</tr>
        		</table>
    		</div>
    		<div class="child2-box">
    			<a class="batal-upload" idF="" href="#" title="Batalkan Upload File"><img src="<?php echo site_url('asset/images/icn_alert_error.png'); ?>"></a>
			</div>
		</div>
	</div>
</div>
