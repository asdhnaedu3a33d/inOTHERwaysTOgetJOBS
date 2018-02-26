<script type="text/javascript">
	$(document).ready(function(){
		prepare_chosen();
		$('#jumlah_dana').autoNumeric(numOptionsNotRound);

		jQuery.validator.addMethod("kode_autocomplete", function(value, element, params){
		    if ($("input[name="+ params +"]").val()=="") {
		    	return false;
		    }else{
		    	return true;
		    }
		}, "Data tidak valid/belum di pilih, mohon pilih data setelah melakukan pencarian pada kolom ini.");

		$("form#musrenbangcam").validate({
			rules: {
              jenis_pekerjaan: "required",
              volume: "required",
              satuan: "required",
              jumlah_dana : "required",
			  // Kd_Urusan_autocomplete : {
			  // 	required : true,
			  // 	kode_autocomplete : "kd_urusan"
			  // },
			  // Kd_Bidang_autocomplete : {
			  // 	required : true,
			  // 	kode_autocomplete : "kd_bidang"
			  // },
			  // Kd_Prog_autocomplete : {
			  // 	required : true,
			  // 	kode_autocomplete : "kd_program"
			  // },
			  // Kd_Keg_autocomplete : {
			  // 	required : true,
			  // 	kode_autocomplete : "kd_kegiatan"
			  // }

			}
	    });

		$("#simpan").click(function(){
		    var valid = $("form#musrenbangcam").valid();
		    if (valid) {
                $("#jumlah_dana").val($("#jumlah_dana").autoNumeric('get'));

		    	$("form#musrenbangcam").submit();
		    };
		});

		// $("#Kd_Urusan_autocomplete").autocomplete({
	 //      minLength: 0,
	 //      source:
	 //      function(req, add){
	 //          $("#Kd_Urusan").val("");
	 //          $("#Kd_Bidang_autocomplete").val("");
	 //          $("#Kd_Prog_autocomplete").val("");
	 //          $("#Kd_Keg_autocomplete").val("");
	 //          $.ajax({
	 //              url: "<?php echo base_url('common/autocomplete_kdurusan'); ?>",
	 //              dataType: 'json',
	 //              type: 'POST',
	 //              data: req,
	 //              success:
	 //              function(data){
	 //                add(data);
	 //              },
	 //          });
	 //      },
	 //      select:
	 //      function(event, ui) {
	 //        $("#Kd_Urusan").val(ui.item.id);
	 //      }
	 //    }).focus(function(){
	 //        $(this).trigger('keydown.autocomplete');
	 //    });

		// $("#Kd_Bidang_autocomplete").autocomplete({
		//   minLength: 0,
		//   source:
		//   function(req, add){
		//       $("#Kd_Bidang").val("");
		//       $("#Kd_Prog_autocomplete").val("");
		//       $("#Kd_Keg_autocomplete").val("");

		//       req.kd_urusan = $("#Kd_Urusan").val();
		//       $.ajax({
		//           url: "<?php echo base_url('common/autocomplete_kdbidang'); ?>",
		//           dataType: 'json',
		//           type: 'POST',
		//           data: req,
		//           success:
		//           function(data){
		//             add(data);
		//           },
		//       });
		//   },
		//   select:
		//   function(event, ui) {
		//     $("#Kd_Bidang").val(ui.item.id);
		//   }
		// }).focus(function(){
		//     $(this).trigger('keydown.autocomplete');
		// });

		// $("#Kd_Prog_autocomplete").autocomplete({
		//   minLength: 0,
		//   source:
		//   function(req, add){
		//       $("#Kd_Prog").val("");
		//       $("#Kd_Keg_autocomplete").val("");

		//       req.kd_urusan = $("#Kd_Urusan").val();
		//       req.kd_bidang = $("#Kd_Bidang").val();
		//       $.ajax({
		//           url: "<?php echo base_url('common/autocomplete_kdprog'); ?>",
		//           dataType: 'json',
		//           type: 'POST',
		//           data: req,
		//           success:
		//           function(data){
		//             add(data);
		//           },
		//       });
		//   },
		//   select:
		//   function(event, ui) {
		//     $("#Kd_Prog").val(ui.item.id);
		//   }
		// }).focus(function(){
		//     $(this).trigger('keydown.autocomplete');
		// });

		// $("#Kd_Keg_autocomplete").autocomplete({
		//   minLength: 0,
		//   source:
		//   function(req, add){
		//       $("#Kd_Keg").val("");

		//       req.kd_urusan = $("#Kd_Urusan").val();
		//       req.kd_bidang = $("#Kd_Bidang").val();
		//       req.kd_prog   = $("#Kd_Prog").val();
		//       $.ajax({
		//           url: "<?php echo base_url('common/autocomplete_keg'); ?>",
		//           dataType: 'json',
		//           type: 'POST',
		//           data: req,
		//           success:
		//           function(data){
		//             add(data);
		//           },
		//       });
		//   },
		//   select:
		//   function(event, ui) {
		//     $("#Kd_Keg").val(ui.item.id);
		//   }
		// }).focus(function(){
		//     $(this).trigger('keydown.autocomplete');
		// });

		$(document).on("change", "#kd_urusan", function () {
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("common/cmb_bidang"); ?>',
				data: {kd_urusan: $(this).val()},
				success: function(msg){
					$("#cmb-bidang").html(msg);
					$("#kd_program").val("");
					$("#kd_kegiatan").val("");
					$("#kd_program").trigger("chosen:updated");
					$("#kd_kegiatan").trigger("chosen:updated");
					prepare_chosen();
				}
			});
		});


		$(document).on("change", "#kd_bidang", function () {
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("common/cmb_program"); ?>',
				data: {kd_urusan:$("#kd_urusan").val(), kd_bidang: $(this).val()},
				success: function(msg){
					$("#cmb-program").html(msg);
					$("#kd_kegiatan").val("");
					$("#kd_kegiatan").trigger("chosen:updated");
					// $("#kd_kegiatan").val("");
					prepare_chosen();
				}
			});
		});

		$(document).on("change", "#kd_program", function () {
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("common/cmb_kegiatan"); ?>',
				data: {kd_urusan:$("#kd_urusan").val(), kd_bidang:$("#kd_bidang").val(), kd_program: $(this).val()},
				success: function(msg){
					$("#cmb-kegiatan").html(msg);
					prepare_chosen();
				}
			});
		});
	});


</script>
<article class="module width_full">
 	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit Data Musrenbangcam";
			} else{
			    echo "Input Data Musrenbangcam";
			}
		?>
		</h3>
 	</header>
 	<div class="module_content" style="height:100%">
 		<form action="<?php echo $url_save_data;?>" method="POST" name="musrenbangcam" id="musrenbangcam" accept-charset="UTF-8" enctype="multipart/form-data" >
 			<input type="hidden" name="id_musrenbang" id="id_musrenbang" value="<?php if(!empty($id_musrenbang)){echo $id_musrenbang;} ?>" />
      <input type="hidden" name="id_asal_usulan" id="id_asal_usulan" value="<?php if(!empty($id_asal_usulan)){echo $id_asal_usulan;} ?>" />
      <table id="musrembang_input" class="fcari" width="100%">

            <tr>
                <td style="width:20%">Nama Urusan</td>
                <!-- <td>
                    <label for="Kd_Urusan_autocomplete"></label>
                    <input type="text" id="Kd_Urusan_autocomplete" name="Kd_Urusan_autocomplete" class="autocomplete_field" value="<?php //if(!empty($nm_urusan)){echo $nm_urusan;} ?>" />
                    <input type="hidden"id="Kd_Urusan" name="Kd_Urusan" value="<?php //if(!empty($urusan)){echo $urusan;} ?>"/>
                </td> -->
                <td>
					<?php echo $kd_urusan; ?>
    			</td>
            </tr>
            <tr>
                <td>Nama Bidang</td>
                <!-- <td>
                  <label for="Kd_Bidang_autocomplete"></label>
                  <input type="text" id="Kd_Bidang_autocomplete" name="Kd_Bidang_autocomplete" class="autocomplete_field" value="<?php //if(!empty($nm_bidang)){echo $nm_bidang;} ?>" />
                  <input type="hidden"id="Kd_Bidang" name="Kd_Bidang" value="<?php //if(!empty($bidang)){echo $bidang;} ?>"/>
                </td> -->
                <td id="cmb-bidang">
					<?php echo $kd_bidang; ?>
				</td>
            </tr>
            <tr>
                <td>Nama Program</td>
                <!-- <td>
                  <label for="Kd_Prog_autocomplete"></label>
                  <input type="text" id="Kd_Prog_autocomplete" name="Kd_Prog_autocomplete" class="autocomplete_field" value="<?php //if(!empty($nm_program)){echo $nm_program;} ?>"/>
                  <input type="hidden"id="Kd_Prog" name="Kd_Prog" value="<?php //if(!empty($program)){echo $program;} ?>"/>
                </td> -->
                <td id="cmb-program">
					<?php echo $kd_program; ?>
				</td>
            </tr>
            <tr>
                <td>Nama Kegiatan</td>
                <!-- <td>
                  <label for="Kd_Keg_autocomplete"></label>
                  <input type="text" id="Kd_Keg_autocomplete" name="Kd_Keg_autocomplete" class="autocomplete_field" value="<?php //if(!empty($nm_kegiatan)){echo $nm_kegiatan;} ?>"/>
                  <input type="hidden"id="Kd_Keg" name="Kd_Keg" value="<?php //if(!empty($kegiatan)){echo $kegiatan;} ?>"/>
                </td> -->
                <td id="cmb-kegiatan">
					<?php echo $kd_kegiatan; ?>
				</td>
            </tr>
            <tr>
                <td>Jenis Pekerjaan</td>
                <td>
                    <input type="text" name="jenis_pekerjaan" id="jenis_pekerjaan" placeholder="Jenis Pekerjaan"
                    value="<?php echo isset($jenis_pekerjaan) ? $jenis_pekerjaan : ''; ?>" style="width:40%"/>
                </td>
            </tr>
            <tr>
                <td>Volume</td>
                <td>
                    <input type="text" name="volume" id="volume" placeholder="Volume"
                    value="<?php echo isset($volume) ? $volume : ''; ?>" style="width:40%"/>
                </td>
            </tr>
            <tr>
                <td>Satuan</td>
                <td>
                    <input type="text" name="satuan" id="satuan" placeholder="Satuan"
                    value="<?php echo isset($satuan) ? $satuan : ''; ?>" style="width:40%"/>
                </td>
            </tr>
            <tr>
                <td>Jumlah Dana</td>
                <td>
                    <input name='jumlah_dana' type='text' id="jumlah_dana" class='currency'
                     value="<?php
                    echo isset($jumlah_dana) ? $jumlah_dana : '0'
                  ?>" style="width:40%">
              </td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td>
                    <input name='lokasi' type='text' id="lokasi"
                     value="<?php
                    echo isset($lokasi) ? $lokasi : ''
                  ?>" style="width:40%">
              </td>
            </tr>
            <tr>
                <td>SKPD</td>
                <!-- <td>
                    <select name="id_skpd" id="id_skpd" >
                        <?php //echo $combo_skpd;?>
                    </select>
              </td> -->
              <td id="cmb-skpd">
					<?php echo $id_skpd; ?>
				</td>
            </tr>
            <tr>
                <td>Keputusan</td>
                <td>
                    <select name="id_keputusan" id="id_keputusan" style="width: 40%">
                        <?php echo $combo_keputusan;?>
                    </select>
                </td>
            </tr>
						<tr id="alasan_prioritas">
							<td>Alasan</td>
							<td>
								<textarea class="common" name="alasan_keputusan" id="alasan_keputusan" rows="7"><?php if(!empty($alasan_keputusan)){echo $alasan_keputusan;} ?></textarea>
							</td>
						</tr>

          </table>
 		</form>
 	</div>
 	<footer>
		<div class="submit_link">
  			<input type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('skpd'); ?>'" />
		</div>
	</footer>
</article>
