<script type="text/javascript">
	$(function() {
		prepare_chosen();

        $("#input_bidang").validate({
            rules: {
                kd_urusan : "required",
			    kd_bidang : "required",
			    nm_bidang : "required"
            },
            messages: {
                kd_urusan : "Mohon pilih kode urusan",
                kd_bidang : "Mohon diisi",
                nm_bidang : "Mohon diisi",
            },
			submitHandler: function(form){
				form.submit();
			}
        });
    });

	prepare_chosen();
    $(document).on("change", "#kd_urusan", function () {
        $.ajax({
            type: "POST",
            url: '<?php echo site_url("common/cmb_bidang"); ?>',
            data: {kd_urusan: $(this).val()},
            success: function(msg){
              $("#cmb-bidang").html(msg);
              $("#kd_program").val("");
              $("#kd_program").trigger("chosen:updated");
              prepare_chosen();
            }
        });


	});


</script>

<article class="module width_full">
	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit Data Bidang";
			} else{
			    echo "Input Data Bidang";
			}
		?>
		</h3>
 	</header>
	<form method="post" name='input_bidang' id='input_bidang' action="<?php echo site_url('data/save_bidang')?>" enctype="multipart/form-data" >
  <div class="module-content">
		<input name='call_from' type='hidden' id="call_from" value='<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ?>'/>
		<input type="hidden" name="id" value="<?php if(!empty($id)){echo $id;} ?>" />
  	<table id="bidang_input" class="fcari" width="100%">
 	  		<tr>
	        <td style="width:20%">Kode Urusan</td>
	        <td style="width:80%">
	        	<?php echo $kd_urusan; ?>
	        </td>
        </tr>
				<tr>
					<td>Kode Bidang</td>
					<td>
						<input type="text" name="kd_bidang" id="kd_bidang" placeholder="Kode Bidang"
						value="<?php echo isset($kd_bidang) ? $kd_bidang : ''; ?>" />
					</td>
				</tr>
				<tr>
					<td>Nama Bidang</td>
					<td>
						<input type="text" name="nm_bidang" id="nm_bidang" placeholder="Nama Bidang"
						value="<?php echo isset($nm_bidang) ? $nm_bidang : ''; ?>" />
					</td>
				</tr>
				<tr>
					<td>Kode Fungsi</td>
					<td>
						<input type="text" name="kd_fungsi" id="kd_fungsi" placeholder="Kode Fungsi"
						value="<?php echo isset($kd_fungsi) ? $kd_fungsi : ''; ?>" />
					</td>
				</tr>
   	</table>
	</div>
  <footer>
    <div class="submit_link">
		<input type="submit" name="simpan"  id="simpan" value='Simpan'/>
		<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('data/view_bidang'); ?>'" />
    </div>
  </footer>
  </form>   	
</article>
