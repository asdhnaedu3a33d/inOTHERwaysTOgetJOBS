<script type="text/javascript">
	$(function() {
		prepare_chosen();

        $("#input_program").validate({
            rules: {
                kd_urusan : "required",
			    kd_bidang : "required",
			    kd_prog 	: "required",
			    ket_program : "required",
            },
            messages: {
                kd_urusan : "Mohon pilih kode urusan",
                kd_bidang : "Mohon diisi",
                kd_prog   : "Mohon diisi",
                ket_program : "Mohon diisi",
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
              prepare_chosen();
            }
        });

    $(document).on("change", "#kd_bidang", function () {
        var str = $(this).find('option:selected').text();

    });


	});

	// $(document).on("change", "#kd_bidang", function () {
 //        $.ajax({
 //            type: "POST",
 //            url: '<?php echo site_url("common/cmb_program"); ?>',
 //            data: {kd_urusan:$("#kd_urusan").val(), kd_bidang: $(this).val()},
 //            success: function(msg){
 //              $("#cmb-program").html(msg);
 //              $("#kd_kegiatan").val("");
 //              $("#kd_kegiatan").trigger("chosen:updated");
 //              prepare_chosen();
 //            }
 //        });
 //    });


</script>

<article class="module width_full">
	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit Data Program";
			} else{
			    echo "Input Data Program";
			}
		?>
		</h3>
 	</header>
  <form method="post" name='input_program' id='input_program' action="<?php echo site_url('data/save_program')?>" enctype="multipart/form-data" >
 	<div class="module-content">
		<input name='call_from' type='hidden' id="call_from" value='<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ?>'/>
		<input type="hidden" name="id" value="<?php if(!empty($id)){echo $id;} ?>" />
	   	  	<table id="program_input" class="fcari" width="100%">
	   	  		<tr>
			        <td style="width:20%">Kode Urusan</td>
			        <td style="width:80%">
			        	<?php echo $kd_urusan; ?>
			        </td>
		        </tr>
				<tr>
					<td>Kode Bidang</td>
					<td id="cmb-bidang">
						<?php echo $kd_bidang; ?>
					</td>
				</tr>
				<tr>
					<td>Kode Program</td>
					<td>
						<input type="text" name="kd_prog" id="kd_prog" placeholder="Kode Program"
						value="<?php echo isset($kd_prog) ? $kd_prog : ''; ?>" />
					</td>
				</tr>
				<tr>
					<td>Keterangan Program</td>
					<td>
						<input type="text" name="ket_program" id="ket_program" placeholder="Keterangan Program"
						value="<?php echo isset($ket_program) ? $ket_program : ''; ?>" />
					</td>
				</tr>
	   	  	</table>
	   	  	</div>
          <footer>
            <div class="submit_link">
      			<input type="submit" name="simpan"  id="simpan" value='Simpan'/>
      			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('data/view_program'); ?>'" />
    		    </div>
          </footer>
   		</form>
</article>
