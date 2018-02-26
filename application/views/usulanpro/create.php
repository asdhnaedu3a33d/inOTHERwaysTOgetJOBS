<script type="text/javascript">
	$(document).ready(function(){
		$('#jumlah_dana').autoNumeric(numOptions);
		jQuery.validator.addMethod("kode_autocomplete", function(value, element, params){
		    if ($("input[name="+ params +"]").val()=="") {
		    	return false;
		    }else{
		    	return true;
		    }
		}, "Data tidak valid/belum di pilih, mohon pilih data setelah melakukan pencarian pada kolom ini.");
		<?php if(@$id_groups!='6'){
		?>

		$("#nama_dewan").hide();
		<?php
		}
		?>
		$("form#usulanpro").validate({
			rules: {
			  id_groups_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "id_groups"
			  },
			  id_skpd_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "id_skpd"
			  },
			  id_kec_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "id_kec"
			  },
			  id_desa_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "id_desa"
			  },
			  jenis_pekerjaan : "required",
			  volume : {
			  	required : true,
			  	number: true
			  },
			  satuan : "required",
			  lokasi : "required"
			}
	    });

		$("#simpan").click(function(){
		    var valid = $("form#usulanpro").valid();
		    if (valid) {
		    	$("#jumlah_dana").val($("#jumlah_dana").autoNumeric('get'));
		    	$("form#usulanpro").submit();
		    };
		});

		$("#id_skpd_autocomplete").autocomplete({
        appendTo: "#element_skpd_autocomplete",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#id_skpd").val("");
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_skpd'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: req,
	              success:
	              function(data){
	                add(data);
	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#id_skpd").val(ui.item.id);
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });

	    $("#id_kec_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_kec",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#id_kec").val("");

	          $("#id_desa_autocomplete").val("");
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kec'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: req,
	              success:
	              function(data){
	                add(data);
	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#id_kec").val(ui.item.id);
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });

	    $("#id_desa_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_desa",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#id_desa").val("");
	          req.id_kec = $("#id_kec").val();
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_desa'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: req,
	              success:
	              function(data){
	                add(data);
	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#id_desa").val(ui.item.id);
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });

		$("#id_groups_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_group",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#id_groups").val("");
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_groups'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: req,
	              success:
	              function(data){
	                add(data);
					//console.log(data);
	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#id_groups").val(ui.item.id);
			//console.log($("#id_groups").val());
			if($("#id_groups").val()== 6){
					// $("#nama_dewan").show();
				} else if($("#id_groups").val()!= 6){
					$("#nama_dewan").hide();
				}
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });

	});


</script>
<article class="module width_full">
 	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit Data Usulan";

			} else{
			    echo "Input Data Usulan";
			}
		?>
		</h3>
 	</header>
 	
 	<div class="module_content">
 		<form action="<?php echo site_url('usulanpro/save');?>" method="POST" name="usulanpro" id="usulanpro" accept-charset="UTF-8" enctype="multipart/form-data" >
 			<input type="hidden" name="id_usulanpro"  id='id_usulanpro' value="<?php if(!empty($id_musrenbang)){echo $id_musrenbang;} ?>" />
 			<table class="fcari" width="100%">
 				<tbody>
 					<tr>
 						<td width="20%">Group</td>
 						<td width="80%">
						<input type="text" id="id_groups_autocomplete" name="id_groups_autocomplete" class="common" value="<?php if(!empty($nama_group)){echo $nama_group;} ?>" />
				        <input type="hidden" id="id_groups" name="id_groups" value="<?php if(!empty($id_groups)){echo $id_groups;} ?>"/>
			            <div id="autocomplete_element_group" class="autocomplete_element"></div>
			            </td>
					</tr>
                    <tr id="nama_dewan" style="display: none">
                    	<td>Nama Dewan</td>
                    	<td>
                        <input type="text" id="nama_dewan" name="nama_dewan" class="common" value="<?php if(!empty($nama_dewan)){echo $nama_dewan;} ?>" />
                        </td>
                    </tr>
					<tr>
						<td>SKPD</td>
						<td>
							<input type="text" id="id_skpd_autocomplete" name="id_skpd_autocomplete" class="common" value="<?php if(!empty($nama_skpd)){echo $nama_skpd;} ?>" />
	            <input type="hidden" id="id_skpd" name="id_skpd" value="<?php if(!empty($id_skpd)){echo $id_skpd;} ?>"/>
              <div id="element_skpd_autocomplete" class="autocomplete_element"></div>
						</td>
					</tr>
					<tr style="background-color: white;">
						<td colspan="2"><hr></td>
					</tr>

					<tr>
						<td>Kecamatan Sasaran</td>
						<td>
							<input type="text" id="id_kec_autocomplete" name="id_kec_autocomplete" class="common" value="<?php if(!empty($nama_kec)){echo $nama_kec;} ?>" />
	            <input type="hidden" id="id_kec" name="id_kec" value="<?php if(!empty($id_kec)){echo $id_kec;} ?>"/>
              <div id="autocomplete_element_kec" class="autocomplete_element"></div>
						</td>
					</tr>
					<tr>
						<td>Desa Sasaran</td>
						<td>
							<input type="text" id="id_desa_autocomplete" name="id_desa_autocomplete" class="common" value="<?php if(!empty($nama_desa)){echo $nama_desa;} ?>" />
	            <input type="hidden" id="id_desa" name="id_desa" value="<?php if(!empty($id_desa)){echo $id_desa;} ?>"/>
              <div id="autocomplete_element_desa" class="autocomplete_element"></div>
						</td>
					</tr>
					<tr>
						<td>Jenis Pekerjaan</td>
						<td><input class="common" type="text" name="jenis_pekerjaan" id="jenis_pekerjaan" value="<?php if(!empty($jenis_pekerjaan)){echo $jenis_pekerjaan;} ?>"/></td>
					</tr>
					<tr>
						<td>Volume</td>
						<td><input class="common" type="text" name="volume" id="volume" value="<?php if(!empty($volume)){echo $volume;} ?>"/></td>
					</tr>
					<tr>
						<td>Satuan</td>
						<td><input class="common" type="text" name="satuan" id="satuan"value="<?php if(!empty($satuan)){echo $satuan;} ?>"/></td>
					</tr>
					<tr>
						<td>Jumlah Dana</td>
						<td><input class="common" type="text" name="jumlah_dana" id="jumlah_dana"value="<?php if(!empty($jumlah_dana)){echo $jumlah_dana;} ?>"/></td>
					</tr>
					<tr>
						<td>Lokasi</td>
						<td><input class="common" type="text" name="lokasi" id="lokasi"value="<?php if(!empty($lokasi)){echo $lokasi;} ?>"/></td>
					</tr>
					<tr>
						<td>Catatan</td>
						<td>
							<textarea class="common" name="catatan" id="catatan"><?php if(!empty($catatan)){echo $catatan;} ?></textarea>
						</td>
					</tr>
                    <tr>
                        <td></td>
                        <td colspan="2">
                        <?php
                            include_once("file_upload.php");
                        ?>
                        </td>
                    </tr>
 				</tbody>
 			</table>
 		</form>
 	</div>
 	<footer>
		<div class="submit_link">
  			<input type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('usulanpro'); ?>'" />
		</div>
	</footer>
</article>
