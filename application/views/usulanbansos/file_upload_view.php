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
		$("#add1").click(function(){
			var index = $(".parent input#upload_length").val();
			index++;
			$("#add-box-upload input#name_file").attr("name", "name_file1["+index+"]");
			$("#add-box-upload input#ket_file").attr("name", "ket_file1["+index+"]");
			$("#add-box-upload input#userfile").attr("name", "userfile1["+index+"]");
			$(".parent input#upload_length").val(index);
			$("#frame").append($("#add-box-upload").html());
			$("#add-box-upload input#name_file").attr("name", "");
			$("#add-box-upload input#ket_file").attr("name", "");
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
	<input type="hidden" id="upload_length" name="upload_length" value="<?php if(!empty($mp_jmlfile_view)){echo $mp_jmlfile_view;} ?>" />        				
	<div id="frame">
	<?php
	if (!empty($mp_filefiles_view)) {						
		$i = 0;
		foreach ($mp_filefiles_view as $row) {
			$i++;
	?>
			<div id="box">
				<div class="child1-box">
					<table style="border: 1px black;">
	        			<tr>
	        				<td>Nama File</td>
	        				<td><input type="text" id="name_file" name="name_file1[<?php echo $i; ?>]" value="<?php echo $row->name; ?>"/></td>
	        			</tr>
	        			<tr>
	        				<td>Keterangan</td>
	        				<td>
	        					<input type="text" id="ket_file" name="ket_file1[<?php echo $i; ?>]" value="<?php echo $row->ket; ?>"/>	        					
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>File</td>
	        				<td>
	        					<input type="hidden" id="id_file" name = "id_file1" value="<?php echo $row->id; ?>" />
	        					<a id="old" href="<?php echo site_url($row->location); ?>"><?php echo $row->file; ?></a>
	        					<input type="button" class="change" value="Ganti File"/>
	        					<input type="hidden" name="id_userfile1[<?php echo $i; ?>]" value="<?php echo $row->id; ?>" />
	        					<input type="file" class="userfile" id="userfile" name="userfile1[<?php echo $i; ?>]" accept="application/pdf" value="?php echo $row->file; ?>" />	        					
	        				</td>
	        			</tr>
	        			<tr>
	        				<td></td>
	        				<td>
	        					<i>*Keterangan dapat diisi nama lokasi yang lebih detail</i>
	        					<BR><i>*File harus dalam format gambar, Ukuran Maksimum file = 1Mb.</i>
	        				</td>
	        			</tr>	        			
	        		</table>
        		</div>
        		<div class="child2-box">
        			<a class="batal-upload" idF="<?php echo $row->id; ?>" href="#" title="Batalkan Upload File"><img src="<?php echo site_url('asset/images/icn_alert_error.png'); ?>"></a>
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
						<input type="hidden" id="id_file1" name = "id_file1" value="" />
	        				
					<tr>
        				<td>Nama File</td>
        				<td><input type="text" id="name_file1" name="" /></td>
        			</tr>
        			<tr>
        				<td>Keterangan</td>
        				<td>
        					<input type="text" id="ket_file1" name="" />	        					
        				</td>
        			</tr>
        			<tr>
        				<td>File</td>
        				<td>
        					<input type="file" class="userfile" id="userfile1" name="" accept="application/pdf" />	        					
        				</td>
        			</tr>
        			<tr>
        				<td></td>
        				<td>
        					<i>*Keterangan dapat diisi nama lokasi yang lebih detail</i>
	        					<BR><i>*File harus dalam format pdf, Ukuran Maksimum file = 1Mb.</i>
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