<script type="text/javascript">
	
	function listBelanja(){
			var tbl = document.getElementById("listbelanja");
			var row = tbl.insertRow(tbl.rows.length);
			var td0 = document.createElement("td");
			var td1 = document.createElement("td");
			var td2 = document.createElement("td");
			var td3 = document.createElement("td");
			var td4 = document.createElement("td");
			var td5 = document.createElement("td");
			var td6 = document.createElement("td");
			var td7 = document.createElement("td");
			var td8 = document.createElement("td");

			td0.appendChild(generateIndex(row.rowIndex));
			td0.appendChild(generateTeksIndex(row.rowIndex));
			td1.appendChild(generateJenisBelanja(row.rowIndex));
			td1.appendChild(generateTeksJenisBelanja());
			td2.appendChild(generateKategoriBelanja(row.rowIndex));
			td2.appendChild(generateTeksKategoriBelanja());
			td3.appendChild(generateSubKategoriBelanja(row.rowIndex));
			td3.appendChild(generateTeksSubKategoriBelanja());
			td4.appendChild(generateBelanja(row.rowIndex));
			td4.appendChild(generateTeksBelanja());
			td5.appendChild(generateUraian(row.rowIndex));
			td5.appendChild(generateTeksUraian());		
			td6.appendChild(generateVolume(row.rowIndex));
			td6.appendChild(generateTeksVolume());
			td7.appendChild(generateSatuan(row.rowIndex));
			td7.appendChild(generateTeksSatuan());
			td8.appendChild(generateNominal(row.rowIndex));
			td8.appendChild(generateTeksNominal());				

			row.appendChild(td0);
			row.appendChild(td1);
			row.appendChild(td2);
			row.appendChild(td3);
			row.appendChild(td4);
			row.appendChild(td5);
			row.appendChild(td6);
			row.appendChild(td7);
			row.appendChild(td8);
		}

		function generateIndex(index) {
			var idx = document.createElement("input");

			idx.type = "hidden";
			idx.name = "index[ ]";
			idx.id = "index["+index+"]";
			idx.value = index;
			return idx;
		}

		function generateTeksIndex(index){
		var teks = document.createTextNode(index);
			return teks;
		}

		function generateJenisBelanja(index) {
			var jnsbelanja = document.createElement("input");

			jnsbelanja.type = "hidden";
			jnsbelanja.name = "jnsbelanja[ ]";
			jnsbelanja.id = "jnsbelanja["+index+"]";
			jnsbelanja.value = document.getElementById("kd_jenis_belanja").value;
			return jnsbelanja;
		}

		function generateTeksJenisBelanja(){
			var nilai = document.getElementById("kode_jenis_belanja_autocomplete").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}

		function generateKategoriBelanja(index) {
			var katbelanja = document.createElement("input");

			katbelanja.type = "hidden";
			katbelanja.name = "katbelanja[ ]";
			katbelanja.id = "katbelanja["+index+"]";
			katbelanja.value = document.getElementById("kd_kategori_belanja").value;
			return katbelanja;
		}

		function generateTeksKategoriBelanja(){
			var nilai = document.getElementById("kode_kategori_belanja_autocomplete").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}

		function generateSubKategoriBelanja(index) {
			var subkatbelanja = document.createElement("input");

			subkatbelanja.type = "hidden";
			subkatbelanja.name = "subkatbelanja[ ]";
			subkatbelanja.id = "subkatbelanja["+index+"]";
			subkatbelanja.value = document.getElementById("kd_subkategori_belanja").value;
			return subkatbelanja;
		}

		function generateTeksSubKategoriBelanja(){
			var nilai = document.getElementById("kode_subkategori_belanja_autocomplete").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}

		function generateBelanja(index) {
			var belanja = document.createElement("input");

			belanja.type = "hidden";
			belanja.name = "belanja[ ]";
			belanja.id = "belanja["+index+"]";
			belanja.value = document.getElementById("kd_belanja").value;
			return belanja;
		}

		function generateTeksBelanja(){
			var nilai = document.getElementById("kode_belanja_autocomplete").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}

		function generateUraian(index) {
			var uraian = document.createElement("input");

			uraian.type = "hidden";
			uraian.name = "uraian[ ]";
			uraian.id = "uraian["+index+"]";
			uraian.value = document.getElementById("uraian").value;
			return uraian;
		}

		function generateTeksUraian(){
			var nilai = document.getElementById("uraian").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}	
		function generateVolume(index) {
			var volume = document.createElement("input");

			volume.type = "hidden";
			volume.name = "volume[ ]";
			volume.id = "volume["+index+"]";
			volume.value = document.getElementById("volume").value;
			return volume;
		}

		function generateTeksVolume(){
			var nilai = document.getElementById("volume").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}

		function generateSatuan(index) {
			var satuan = document.createElement("input");

			satuan.type = "hidden";
			satuan.name = "satuan[ ]";
			satuan.id = "satuan["+index+"]";
			satuan.value = document.getElementById("satuan").value;
			return satuan;
		}

		function generateTeksSatuan(){
			var nilai = document.getElementById("satuan").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}

		function generateNominal(index) {
			var nominal = document.createElement("input");

			nominal.type = "hidden";
			nominal.name = "nominal[ ]";
			nominal.id = "nominal["+index+"]";
			nominal.value = document.getElementById("nominal").value;
			return nominal;
		}

		function generateTeksNominal(){
			var nilai = document.getElementById("nominal").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}			



		


	$(document).ready(function(){

		$('#nominal').autoNumeric(numOptions);

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
		$("form#belanja_renstra").validate({
			rules: {
			  kode_urusan_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_urusan"
			  },
			  kode_bidang_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_bidang"
			  },
			   kode_jenis_belanja_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_jenis_belanja"
			  },
			   kode_kategori_belanja_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_kategori_belanja"
			  },
			   kode_subkategori_belanja_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_subkategori_belanja"
			  },
			   kode_belanja_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_belanja"
			  },
			  kode_kegiatan_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_keg"
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
		    var valid = $("form#belanja_renstra").valid();
		    if (valid) {
		    	$("#nominal").val($("#nominal").autoNumeric('get'));
		    	$("form#belanja_renstra").submit();
		    };
		});

		$("#kode_bidang_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_bidang",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_bidang").val("");
	          var kdurusan = $("#kd_urusan").val();
	          var s = $("#kode_bidang_autocomplete").val();
	          
	          console.log(kdurusan);
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdbidang'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"kd_urusan": kdurusan,"term" : s},
	              success:
	              function(data){
	                add(data);
					
	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_bidang").val(ui.item.id);
			//console.log($("#id_groups").val());
			
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });

		$("#kode_program_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_prog",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_prog").val("");
	          var kdurusan = $("#kd_urusan").val();
	          var kdbidang = $("#kd_bidang").val();
	          var s = $("#kode_program_autocomplete").val();
	          
	          
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdprog'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"kd_urusan": kdurusan,"kd_bidang": kdbidang,"term" : s},
	              success:
	              function(data){
	                add(data);
					
	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_prog").val(ui.item.id);
			//console.log($("#id_groups").val());
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });


		$("#kode_kegiatan_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_keg",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_keg").val("");
	          var kdurusan = $("#kd_urusan").val();
	          var kdbidang = $("#kd_bidang").val();
	          var kdprog = $("#kd_prog").val();
	          var s = $("#kode_kegiatan_autocomplete").val();
	          
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_keg'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"kd_urusan": kdurusan,"kd_bidang": kdbidang,"kd_prog":kdprog,"term" : s},
	              success:
	              function(data){
	                add(data);
					
	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_keg").val(ui.item.id);
			//console.log($("#id_groups").val());
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });





		$("#kode_jenis_belanja_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_jenis_belanja",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_jenis_belanja").val("");
	          var s = $("#kode_jenis_belanja_autocomplete").val();
	          
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdjenisbelanja'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"term" : s},
	              success:
	              function(data){
	                add(data);
					
	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_jenis_belanja").val(ui.item.id);
			//console.log($("#id_groups").val());
			
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });


		$("#kode_kategori_belanja_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_kategori_belanja",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_kategori_belanja").val("");
	          var kdjenis = $("#kd_jenis_belanja").val();
	          var s = $("#kode_kategori_belanja_autocomplete").val();
	          
	         
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdkategoribelanja'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"kd_jenis_belanja": kdjenis,"term" : s},
	              success:
	              function(data){
	                add(data);
					
	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_kategori_belanja").val(ui.item.id);
			//console.log($("#id_groups").val());
			
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });

		$("#kode_subkategori_belanja_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_subkategori_belanja",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_subkategori_belanja").val("");
	          var kdjenis= $("#kd_jenis_belanja").val();
	          var kdkategori = $("#kd_kategori_belanja").val();
	          var s = $("#kode_subkategori_belanja_autocomplete").val();
	          
	         
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdsubkategoribelanja'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"kd_jenis_belanja": kdjenis,"kd_kategori_belanja": kdkategori,"term" : s},
	              success:
	              function(data){
	                add(data);
					
	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_subkategori_belanja").val(ui.item.id);
			//console.log($("#id_groups").val());
			
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });

		$("#kode_belanja_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_belanja",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_belanja").val("");
	          var kdjenis= $("#kd_jenis_belanja").val();
	          var kdkategori = $("#kd_kategori_belanja").val();
	          var kdsubkategori = $("#kd_subkategori_belanja").val();
	          
	          var s = $("#kode_belanja_autocomplete").val();
	          
	         
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdkodebelanja'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"kd_jenis_belanja": kdjenis,"kd_kategori_belanja": kdkategori,"kd_subkategori_belanja":kdsubkategori,"term" : s},
	              success:
	              function(data){
	                add(data);
					
	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_belanja").val(ui.item.id);
			//console.log($("#id_groups").val());
			
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });



		$("#kode_urusan_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_urusan",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_urusan").val("");
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdurusan'); ?>",
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
	        $("#kd_urusan").val(ui.item.id);
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
			    echo "Edit Data Belanja Renstra";

			} else{
			    echo "Input Data Belanja Renstra";
			}
		?>
		</h3>
 	</header>
 	
 	<div class="module_content">
 		<form action="<?php echo site_url('belanja_renstra/sape');?>" method="POST" name="belanja_renstra" id="belanja_renstra" accept-charset="UTF-8" enctype="multipart/form-data" >
 			
 			<input type="hidden" name="id_belanja_renstra"  id='id_belanja_renstra' value="<?php if(!empty($id_belanja_renstra)){echo $id_belanja_renstra;} ?>" />
 			<table class="fcari" width="100%">
 				<tbody>
 					<tr>
 						<td width="20%">Kode Urusan</td>
 						<td width="80%">
	                      	<input type="text" id="kode_urusan_autocomplete" name="kode_urusan_autocomplete" class="common" value="<?php if(!empty($nm_urusan)){echo $nm_urusan;} ?>" />
					        <input type="hidden" id="kd_urusan" name="kd_urusan" value="<?php if(!empty($kode_urusan)){echo $kode_urusan;} ?>"/>
				            <div id="autocomplete_element_urusan" class="autocomplete_element"></div>
                        </td>
                    </tr>
                    <tr>
                    	<td width="20%">Kode Bidang</td>
 						<td width="80%">
	                        <input type="text" id="kode_bidang_autocomplete" name="kode_bidang_autocomplete" class="common" value="<?php if(!empty($nm_bidang)){echo $nm_bidang;} ?>"/>
						    <input type="hidden" id="kd_bidang" name="kd_bidang" value="<?php if(!empty($kode_bidang)){echo $kode_bidang;} ?>" />
				        	<div id="autocomplete_element_bidang" class="autocomplete_element"></div>
                        </td>
                    </tr>
                    <tr >
                    	<td>Kode Program</td>
                    	<td>
                    	    <input type="text" id="kode_program_autocomplete" name="kode_program_autocomplete" class="common" value="<?php if(!empty($nm_program)){echo $nm_program;} ?>"/>
						    <input type="hidden" id="kd_prog" name="kd_prog" value="<?php if(!empty($kode_program)){echo $kode_program;} ?>"/>
				        	<div id="autocomplete_element_prog" class="autocomplete_element"></div>
                        </td>
                    </tr>
                    <tr >
                    	<td>Kode Kegiatan</td>
                    	<td>
                    	    <input type="text" id="kode_kegiatan_autocomplete" name="kode_kegiatan_autocomplete" class="common" value="<?php if(!empty($nm_kegiatan)){echo $nm_kegiatan;} ?>" />
						    <input type="hidden" id="kd_keg" name="kd_keg" value="<?php if(!empty($kode_kegiatan)){echo $kode_kegiatan;} ?>"/>
				        	<div id="autocomplete_element_keg" class="autocomplete_element"></div>
                        </td>
                    </tr>
                    <tr style="background-color: white;">
						<td colspan="2"><hr></td>
					</tr>
 					<tr>
                    	<td>Jenis Belanja</td>
                    	<td>
                    		<input type="text" id="kode_jenis_belanja_autocomplete" name="kode_jenis_belanja_autocomplete" class="common" value="<?php if(!empty($nm_jenis_belanja)){echo $nm_jenis_belanja;} ?>"/>
						    <input type="hidden" id="kd_jenis_belanja" name="kd_jenis_belanja" value="<?php if(!empty($kode_jenis_belanja)){echo $kode_jenis_belanja;} ?>" />
				        	<div id="autocomplete_element_jenis_belanja" class="autocomplete_element"></div>
                        </td>
                    </tr>
                    <tr>
                    	<td>Kategori Belanja</td>
                    	<td>
                    		<input type="text" id="kode_kategori_belanja_autocomplete" name="kode_kategori_belanja_autocomplete" class="common" value="<?php if(!empty($nm_kategori_belanja)){echo $nm_kategori_belanja;} ?>"/>
						    <input type="hidden" id="kd_kategori_belanja" name="kd_kategori_belanja" value="<?php if(!empty($kode_kategori_belanja)){echo $kode_kategori_belanja;} ?>"/>
				        	<div id="autocomplete_element_kategori_belanja" class="autocomplete_element"></div>
                        </td>
                    </tr>
                    <tr>
                    	<td>Sub Kategori Belanja</td>
                    	<td>
                    		<input type="text" id="kode_subkategori_belanja_autocomplete" name="kode_subkategori_belanja_autocomplete" class="common" value="<?php if(!empty($nm_subkategori_belanja)){echo $nm_subkategori_belanja;} ?>"/>
						    <input type="hidden" id="kd_subkategori_belanja" name="kd_subkategori_belanja" value="<?php if(!empty($kode_subkategori_belanja)){echo $kode_subkategori_belanja;} ?>" />
				        	<div id="autocomplete_element_subkategori_belanja" class="autocomplete_element"></div>
                        </td>
                    </tr>
                    <tr >
                    	<td>Belanja</td>
                    	<td>
                    		<input type="text" id="kode_belanja_autocomplete" name="kode_belanja_autocomplete" class="common" value="<?php if(!empty($nm_belanja)){echo $nm_belanja;} ?>"/>
						    <input type="hidden" id="kd_belanja" name="kd_belanja" value="<?php if(!empty($kode_belanja)){echo $kode_belanja;} ?>" />
				        	<div id="autocomplete_element_belanja" class="autocomplete_element"></div>
                        </td>
                    </tr>
                    
					<tr>
                    	<td>Uraian</td>
                    	<td>
                        <input type="text" id="uraian" name="uraian" class="common" value="<?php if(!empty($uraian)){echo $uraian;} ?>" />
                        </td>
                    </tr>
                    <tr>
						<td>Volume</td>
						<td><input class="common" type="text" name="volume" id="volume" value="<?php if(!empty($volume)){echo $volume;} ?>"/></td>
					</tr>
					<tr>
                    	<td>Satuan</td>
                    	<td>
                    		<input type="text" id="satuan" name="satuan" class="common" value="<?php if(!empty($satuan)){echo $satuan;} ?>" />
                        </td>	
                    </tr>
                    <tr>
						<td>Nominal</td>
						<td><input class="common" type="text" name="nominal" id="nominal"value="<?php if(!empty($nominal)){echo $nominal;} ?>"/></td>
					</tr>
 				</tbody>
 			</table>
 		
 	</div>
 	<footer>
		<div class="submit_link">
			<span id="tambah" onclick="listBelanja();" style="cursor:pointer;">Tambah</span>
  			<input type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('belanja_renstra'); ?>'" />
		</div>
		<table id="listbelanja">
			<tr>
				<th>No</th>
				<th>Jenis Belanja</th>
				<th>Kategori Belanja</th>
				<th>Sub Kategori Belanja</th>
				<th>Belanja</th>
				<th>Uraian</th>
				<th>Volume</th>
				<th>Satuan</th>
				<th>Nominal</th>
			</tr>

		</table>
	</footer>
	</form>
</article>
