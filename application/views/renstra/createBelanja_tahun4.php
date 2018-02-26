
<script type="text/javascript">

if( typeof helper == 'undefined' ) {
  var helper = { } ;
}
helper.arr = {
    multisort: function(arr, columns, order_by) {
        if(typeof columns == 'undefined') {
            columns = []
            for(x=0;x<arr[0].length;x++) {
                columns.push(x);
            }
        }
        if(typeof order_by == 'undefined') {
            order_by = []
            for(x=0;x<arr[0].length;x++) {
                order_by.push('ASC');
            }
        }
        function multisort_recursive(a,b,columns,order_by,index) {
            var direction = order_by[index] == 'DESC' ? 1 : 0;
            var is_numeric = !isNaN(+a[columns[index]] - +b[columns[index]]);
            var x = is_numeric ? +a[columns[index]] : a[columns[index]].toLowerCase();
            var y = is_numeric ? +b[columns[index]] : b[columns[index]].toLowerCase();
            if(x < y) {
                    return direction == 0 ? -1 : 1;
            }
            if(x == y)  {
                return columns.length-1 > index ? multisort_recursive(a,b,columns,order_by,index+1) : 0;
            }
            return direction == 0 ? 1 : -1;
        }
        return arr.sort(function (a,b) {
            return multisort_recursive(a,b,columns,order_by,0);
        });
    }
};

$(document).ready(function(){
  window.asdfghjkl=[] ;
  $('#nominal_satuan_4').autoNumeric(numOptionsNotRound);
  $('#volume_4').autoNumeric(numOptionsNotRound);
  $('#nominal_4').autoNumeric(numOptionsNotRound);

  jQuery.validator.addMethod("kode_autocomplete_4", function(value, element, params){
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
      kode_urusan_autocomplete_4 : {
        required : true,
        kode_autocomplete_4 : "kd_urusan_4"
      },
      kode_bidang_autocomplete_4 : {
        required : true,
        kode_autocomplete_4 : "kd_bidang_4"
      },
       kode_jenis_belanja_autocomplete_4 : {
        required : true,
        kode_autocomplete_4 : "kd_jenis_belanja_4"
      },
       kode_kategori_belanja_autocomplete_4 : {
        required : true,
        kode_autocomplete_4 : "kd_kategori_belanja_4"
      },
       kode_subkategori_belanja_autocomplete_4 : {
        required : true,
        kode_autocomplete_4 : "kd_subkategori_belanja_4"
      },
       kode_belanja_autocomplete_4 : {
        required : true,
        kode_autocomplete_4 : "kd_belanja_4"
      },
      kode_kegiatan_autocomplete_4 : {
        required : true,
        kode_autocomplete_4 : "kd_keg_4"
      },
      jenis_pekerjaan_4 : "required",
      volume : {
        required : true,
        number: true
      },
      satuan : "required",
      lokasi : "required"
    }
    });

//baru ----------------------------------->>>>>>>>>>>>>>>>>

prepare_chosen();
$(document).on("change", "#cb_jenis_belanja_4", function () {

  $.ajax({
    type: "POST",
    url: '<?php echo site_url("common/cmb_kategori_belanja_4"); ?>',
    data: {cb_jenis_belanja_4: $(this).val()},
    success: function(msg){
      $("#combo_kategori_4").html(msg);
      $("#cb_subkategori_belanja_4").val(" ");
      $("#cb_belanja_4").val(" ");
      $("#cb_subkategori_belanja_4").trigger("chosen:updated");
      $("#cb_belanja_4").trigger("chosen:updated");
      prepare_chosen();
    }
  });
});

$(document).on("change", "#cb_kategori_belanja_4", function () {
  $.ajax({
    type: "POST",
    url: '<?php echo site_url("common/cmb_subkategori_belanja_4"); ?>',
    data: {cb_jenis_belanja_4:$("#cb_jenis_belanja_4").val(), cb_kategori_belanja_4: $(this).val()},
    success: function(msg){
      $("#combo_subkategori_4").html(msg);
      $("#cb_belanja_4").val("");
      $("#cb_belanja_4").trigger("chosen:updated");
      prepare_chosen();
    }
  });
});

$(document).on("change", "#cb_subkategori_belanja_4", function () {
  $.ajax({
    type: "POST",
    url: '<?php echo site_url("common/cmb_belanja_4"); ?>',
    data: {cb_jenis_belanja_4:$("#cb_jenis_belanja_4").val(), cb_kategori_belanja_4:$("#cb_kategori_belanja_4").val(), cb_subkategori_belanja_4: $(this).val()},
    success: function(msg){
      $("#combo_belanja_4").html(msg);
      prepare_chosen();
    }
  });
});





  $("#kode_jenis_belanja_autocomplete_4").autocomplete({
      appendTo: "#autocomplete_element_jenis_belanja_4",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_jenis_belanja_4").val("");
          var s = $("#kode_jenis_belanja_autocomplete_4").val();

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
        $("#kd_jenis_belanja_4").val(ui.item.id);
    //console.log($("#id_groups").val());

      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });


  $("#kode_kategori_belanja_autocomplete_4").autocomplete({
      appendTo: "#autocomplete_element_kategori_belanja_4",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_kategori_belanja_4").val("");
          var kdjenis = $("#kd_jenis_belanja_4").val();
          var s = $("#kode_kategori_belanja_autocomplete_4").val();


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
        $("#kd_kategori_belanja_4").val(ui.item.id);
    //console.log($("#id_groups").val());

      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });

  $("#kode_subkategori_belanja_autocomplete_4").autocomplete({
      appendTo: "#autocomplete_element_subkategori_belanja_4",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_subkategori_belanja_4").val("");
          var kdjenis= $("#kd_jenis_belanja_4").val();
          var kdkategori = $("#kd_kategori_belanja_4").val();
          var s = $("#kode_subkategori_belanja_autocomplete_4").val();


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
        $("#kd_subkategori_belanja_4").val(ui.item.id);
    //console.log($("#id_groups").val());

      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });

  $("#kode_belanja_autocomplete_4").autocomplete({
      appendTo: "#autocomplete_element_belanja_4",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_belanja_4").val("");
          var kdjenis= $("#kd_jenis_belanja_4").val();
          var kdkategori = $("#kd_kategori_belanja_4").val();
          var kdsubkategori = $("#kd_subkategori_belanja_4").val();

          var s = $("#kode_belanja_autocomplete_4").val();


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
        $("#kd_belanja_4").val(ui.item.id);
    //console.log($("#id_groups").val());

      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });


  $("#kode_urusan_autocomplete_4").autocomplete({
      appendTo: "#autocomplete_element_urusan_4",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_urusan_4").val("");
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
 	<div class="module_content">

 			<input type="hidden" name="id_belanja_renstra_4"  id='id_belanja_renstra_4' value="<?php if(!empty($id_belanja_renstra_4)){echo $id_belanja_renstra_4;} ?>" />
 			<table class="fcari" width="100%">
 				<tbody>
          <input type="hidden" id="inIndex_4" name="inIndex_4" value="1"/>
          <input type="hidden" id="isEdit_4" value="0"/>
									<tr>
										<td>&nbsp;&nbsp;Lokasi Tahun 4</td>
										<td>
											<textarea class="common" id="lokasi_4" name="lokasi_4"><?php echo (!empty($kegiatan->lokasi_4))?$kegiatan->lokasi_4:''; ?></textarea>
										</td>
									</tr>

											<textarea style="display: none;" class="common" id="uraian_kegiatan_4" name="uraian_kegiatan_4">-<?php echo (!empty($kegiatan->uraian_kegiatan_4))?$kegiatan->uraian_kegiatan_4:''; ?></textarea>

          <tr>
              <td width="20%">Kelompok Belanja</td>
            	<td width="80%" id="combo_jenis_belanja_4">
                <?php echo $cb_jenis_belanja_4; ?>

              </td>
          </tr>
          <tr>
            	<td>Jenis Belanja</td>
            	<td id="combo_kategori_4">
                <?php echo $cb_kategori_belanja_4; ?>

              </td>
          </tr>
          <tr>
          	<td>Obyek Belanja</td>
          	<td id="combo_subkategori_4">
          	    <?php echo $cb_subkategori_belanja_4; ?>
            </td>
          </tr>
          <tr >
          	<td>Rincian Obyek</td>
          	<td id="combo_belanja_4">
          		   <?php echo $cb_belanja_4; ?>
            </td>
          </tr>
					<tr>
          	<td>Rincian Belanja</td>
          	<td>
                  <input type="text" id="uraian_4" name="uraian_4" class="common" value="<?php if(!empty($uraian_4)){echo $uraian_4;} ?>" />
            </td>
          </tr>
          <tr >
          	<td>Sumber Dana </td>
          	<td id="combo_sumberdana_4">
              <?php echo form_dropdown('sumberdana_4', $sumber_dana, NULL, 'data-placeholder="Pilih Sumber Dana" class="common chosen-select" id="sumberdana_4" name="sumberdana_4"'); ?>
          		 <!-- <select id="sumberdana_4" name="sumberdana_4" class="common" >
          			  <option value="1"  >DAU/PAD</option>
                  <option value="2"  >DAU Infrastruktur</option>
                  <option value="3"  >DAK</option>
                  <option value="4"  >BKK Provisi</option>
                  <option value="5"  >BKK Badung</option>

		           </select> -->
          </tr>
          <tr>
          	<td>Sub Rincian Belanja</td>
          	<td>
                  <input type="text" id="det_uraian_4" name="det_uraian_4" class="common" value="<?php if(!empty($deturaian_4)){echo $deturaian_4;} ?>" />
            </td>
          </tr>
          <tr>
						<td>Volume</td>
						<td><input class="common" type="text" name="volume_4" id="volume_4" value="<?php if(!empty($volume_4)){echo $volume_4;} ?>"/></td>
					</tr>
					<tr>
          	<td>Satuan</td>
          	<td>
              <input class="common" type="text" name="satuan_4" id="satuan_4" />
          		<!-- <?php echo form_dropdown('satuan_4', $satuan, NULL, 'class="common " id="satuan_4" name="satuan_4"'); ?> -->
            </td>
          </tr>
          <tr>
						<td>Nominal Satuan</td>
						<td><input class="common" type="text" name="nominal_satuan_4" id="nominal_satuan_4" value="<?php if(!empty($nominal_satuan_4)){echo $nominal_satuan_4;} ?>"/></td>
					</tr>


 				</tbody>
 			</table>

 	</div>
 	<footer>

    <input type="hidden" id="id_belanja_4" value="">

    <div class="alert alert-warning alert-white rounded" id="cusAlert_4" role="alert" style="display:none;">
      <div class="icon">
          <i class="fa fa-warning"></i>
      </div>
      <font color="#d68000" size="4px"> <strong >Perhatian..!! </strong>
        <span id="pesan_4"></span>
      </font>
    </div>

		<div class="submit_link">
      <input type='button' id="ambilbelanjasebelumnya" onclick="copyrowng(4);" style="cursor:pointer;" value='+ Ambil Tahun -1'>
      <input type='button' id="tambahjnsbelanja" onclick="save_belanja_renstra(4, 'jns');" style="cursor:pointer;" value='+ Kelompok Belanja'>
      <input type='button'  id="tambahkatbelanja" onclick="save_belanja_renstra(4,'kat');" style="cursor:pointer;" value='+ Jenis Belanja'>
      <input type='button'  id="tambahsubkatbelanja" onclick="save_belanja_renstra(4,'subkat');" style="cursor:pointer;" value='+ Obyek Belanja'>
      <input type='button'  id="tambahbelanja" onclick="save_belanja_renstra(4,'belanja');" style="cursor:pointer;" value='+ Rincian Obyek'>
      <input type='button'  id="tambahuraian" onclick="save_belanja_renstra(4,'uraian');" style="cursor:pointer;" value='+ Rincian Belanja'>
      <input type='button'  id="tambahdeturaian" onclick="save_belanja_renstra(4,'deturaian');" style="cursor:pointer;" value='+ Sub Rincian Belanja'>

		</div>
		
  <tr>
    <td>Nominal Tahun 4 (Rp.)</td>
    <td><input readonly="readonly" type="text" id="nominal_4" name="nominal_4" value="<?php if(!empty($kegiatan->nominal_4)){echo $kegiatan->nominal_4;} ?>"/></td>
  </tr>
  
<br>
		<table id="listbelanja_4">
			<tr>
				<th>No</th>

				<th>Kelompok Belanja</th>
				<th>Jenis Belanja</th>
				<th>Obyek Belanja</th>
				<th>Rincian Obyek</th>
				<th>Rincian Belanja</th>
        <th>Sumber Dana</th>
				<th>Sub Rincian</th>

				<th>Volume</th>
				<th>Satuan</th>
				<th>Nominal</th>
        <th>Sub Total</th>
				<th colspan="2">Action</th>

				<th style="display:none;">1</th>
				<th style="display:none;">2</th>
				<th style="display:none;">3</th>
				<th style="display:none;">4</th>
				<th style="display:none;">5</th>
				<th style="display:none;">6</th>
			</tr>
      <tbody id="list_tahun_4">
      <?php $th_anggaran = $this->m_settings->get_tahun_anggaran_db(); ?>
      <?php if(!empty($detil_kegiatan)){
              $gIndex_4 = 1;
              $total = 0;
              foreach ($detil_kegiatan as $row) {
                if ($row->tahun == $th_anggaran[3]->tahun_anggaran) {
                  if (!empty($row->kode_sumber_dana)) {
                  $vol = Formatting::currency($row->volume, 2);
                  $nom = Formatting::currency($row->nominal_satuan, 2);
                  $sub = Formatting::currency($row->subtotal, 2);
      ?>
      <tr id="<?php echo $row->id; ?>">
        <td> <?php echo $gIndex_4 ?> </td>

        <td> <?php echo $row->kode_jenis_belanja.". ".$row->jenis_belanja ?> </td>
        <td> <?php echo $row->kode_kategori_belanja.". ".$row->kategori_belanja ?> </td>
        <td> <?php echo $row->kode_sub_kategori_belanja.". ".$row->sub_kategori_belanja ?> </td>
        <td> <?php echo $row->kode_belanja.". ".$row->belanja ?> </td>
        <td> <?php echo $row->uraian_belanja ?> </td>
        <td> <?php echo $row->Sumber_dana ?> </td>
        <td> <?php echo $row->detil_uraian_belanja ?> </td>
        <td> <?php echo $vol ?> </td>
        <td> <?php echo $row->satuan ?> </td>
        <td> <?php echo $nom ?> </td>
        <td> <?php echo $sub ?> </td>
        <td> <span id="ubahrowng" class="icon-pencil" onclick="ubahrowng_4(<?php echo $row->id; ?>)" style="cursor:pointer;" value="ubah" title="Ubah Belanja"></span></td>
        <td> <span id="hapusrowng" class="icon-remove" onclick="hapusrowng_4(<?php echo $row->id; ?>)" style="cursor:pointer;" value="hapus" title="Hapus Belanja"></span></td>
        <?php $total += $row->subtotal; ?>

        <!-- <td style="display:none;"><input type="text" name="kd_sumber_dana_4[<?php echo $gIndex_4 ?>]" id="kd_sumber_dana_4[<?php echo $gIndex_4 ?>]" value="<?php echo $row->kode_sumber_dana ?>" /> </td>
        <td style="display:none;"><input type="text" name="r_kd_jenis_belanja_4[<?php echo $gIndex_4 ?>]" id="r_kd_jenis_belanja_4[<?php echo $gIndex_4 ?>]" value="<?php echo $row->kode_jenis_belanja ?>" /> </td>
        <td style="display:none;"><input type="text" name="r_kd_kategori_belanja_4[<?php echo $gIndex_4 ?>]" id="r_kd_kategori_belanja_4[<?php echo $gIndex_4 ?>]" value="<?php echo $row->kode_kategori_belanja ?>" /> </td>
        <td style="display:none;"><input type="text" name="r_kd_subkategori_belanja_4[<?php echo $gIndex_4 ?>]" id="r_kd_subkategori_belanja_4[<?php echo $gIndex_4 ?>]" value="<?php echo $row->kode_sub_kategori_belanja ?>" /> </td>
        <td style="display:none;"><input type="text" name="r_kd_belanja_4[<?php echo $gIndex_4 ?>]" id="r_kd_belanja_4[<?php echo $gIndex_4 ?>]" value="<?php echo $row->kode_belanja ?>" /> </td>
        <td style="display:none;"><input type="text" name="r_uraian_4[<?php echo $gIndex_4 ?>]" id="r_uraian_4[<?php echo $gIndex_4 ?>]" value="<?php echo $row->uraian_belanja ?>" /> </td>
        <td style="display:none;"><input type="text" name="r_det_uraian_4[<?php echo $gIndex_4 ?>]" id="r_det_uraian_4[<?php echo $gIndex_4 ?>]" value="<?php echo $row->detil_uraian_belanja ?>" /> </td>
        <td style="display:none;"><input type="text" name="r_volume_4[<?php echo $gIndex_4 ?>]" id="r_volume_4[<?php echo $gIndex_4 ?>]" value="<?php echo str_replace('.','',$vol) ?>" /> </td>
        <td style="display:none;"><input type="text" name="r_satuan_4[<?php echo $gIndex_4 ?>]" id="r_satuan_4[<?php echo $gIndex_4 ?>]" value="<?php echo $row->satuan ?>" /> </td>
        <td style="display:none;"><input type="text" name="r_nominal_satuan_4[<?php echo $gIndex_4 ?>]" id="r_nominal_satuan_4[<?php echo $gIndex_4 ?>]" value="<?php echo str_replace('.','',$nom) ?>" /> </td>
        <td style="display:none;"><input type="text" name="r_subtotal_4[<?php echo $gIndex_4 ?>]" id="r_subtotal_4[<?php echo $gIndex_4 ?>]" value="<?php echo str_replace('.','',$sub) ?>" /> </td> -->
      </tr>

      <?php $gIndex_4++; }}
        echo "<script> document.getElementById('inIndex_4').value = $gIndex_4; </script> ";
        echo "<script type='text/javascript'>$('#nominal_4').autoNumeric('set', ".$total.");</script>";
      }} ?>
    </tbody>
		</table>

	</footer>

  <p>
  <p>

</article>
<script src="<?php echo base_url('assets/renstra/createbelanja_tahun4.js');?>"></script>
<script src="<?php echo base_url('assets/renstra/custom-alert.js');?>"></script>
<link href="<?php echo base_url('assets/renstra/custom-alert.css') ?>" rel="stylesheet" type="text/css" />

<script>
  function errorMessage_4(clue) {
    var lokasi = $('#lokasi_4').val();
    var uraian_kegiatan = $('#uraian_kegiatan_4').val();
    var jenis_belanja = $('#cb_jenis_belanja_4').val();
    var kategori_belanja = $('#cb_kategori_belanja_4').val();
    var subkategori_belanja = $('#cb_subkategori_belanja_4').val();
    var kode_belanja = $('#cb_belanja_4').val();
    var uraian = $('#uraian_4').val();
    var det_uraian = $('#det_uraian_4').val();
    var volume = $('#volume_4').val();
    var satuan = $('#satuan_4').val();
    var nominal = $('#nominal_satuan_4').val();
    var sumberdana = $('#sumberdana_4').val();
    eliminationName(lokasi, uraian_kegiatan, jenis_belanja, kategori_belanja, subkategori_belanja, kode_belanja, uraian, det_uraian, volume, satuan, nominal, sumberdana, clue, '#cusAlert_4', 'pesan_4');

  }


  function jenis_belanjanya_4(p_nama, p_jenis) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_jenis_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis},
      success: function(msg){
        $("#combo_jenis_belanja_4").html(msg);
        prepare_chosen();
      }
    });
  }
  function kategori_belanjanya_4(p_nama, p_jenis, p_kategori) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_kategori_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori},
      success: function(msg){
        $("#combo_kategori_4").html(msg);
        prepare_chosen();
      }
    });
  }
  function sub_belanjanya_4(p_nama, p_jenis, p_kategori, p_sub) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_sub_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori, sub: p_sub},
      success: function(msg){
        $("#combo_subkategori_4").html(msg);
        prepare_chosen();
      }
    });
  }
  function belanja_belanjanya_4(p_nama, p_jenis, p_kategori, p_sub, p_belanja) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_belanja_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori, sub: p_sub, belanja: p_belanja},
      success: function(msg){
        $("#combo_belanja_4").html(msg);
        prepare_chosen();
      }
    });
  }

  function sumber_dananya_4(p_nama, p_id) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_sumber_dana"); ?>',
      data: {nama: p_nama, id: p_id},
      success: function(msg){
        $("#combo_sumberdana_4").html(msg);
        prepare_chosen();
      }
    });
  }


  function hapusrowng_4(id_belanja){
    var tahun = 4;
    var id_kegiatan = $('input[name="id_kegiatan"]').val();

    $.ajax({
          type: "POST",
          url: '<?php echo site_url("renstra/belanja_kegiatan_hapus"); ?>',
          dataType: 'json',
          data: {
          id_kegiatan : id_kegiatan,
          id_belanja : id_belanja,
          tahun : tahun
          },
          success: function(msg){
            $('#list_tahun_'+tahun).html('');
            var no = 1;
            var total = 0;
            for (var i = 0; i < msg.list.length; i++) {
              
              var row = '<tr>';
              row += '<td>'+no+'</td>';
              row += '<td>'+msg.list[i].kode_jenis_belanja+'. '+msg.list[i].jenis_belanja+'</td>';
              row += '<td>'+msg.list[i].kode_kategori_belanja+'. '+msg.list[i].kategori_belanja+'</td>';
              row += '<td>'+msg.list[i].kode_sub_kategori_belanja+'. '+msg.list[i].sub_kategori_belanja+'</td>';
              row += '<td>'+msg.list[i].kode_belanja+'. '+msg.list[i].belanja+'</td>';
              row += '<td>'+msg.list[i].uraian_belanja+'</td>';
              row += '<td>'+msg.list[i].Sumber_dana+'</td>';
              row += '<td>'+msg.list[i].detil_uraian_belanja+'</td>';
              row += '<td>'+float_to_num(msg.list[i].volume)+'</td>';
              row += '<td>'+msg.list[i].satuan+'</td>';
              row += '<td>'+float_to_num(msg.list[i].nominal_satuan)+'</td>';
              row += '<td>'+float_to_num(msg.list[i].subtotal)+'</td>';
              row += "<td><span id='ubahrowng' class='icon-pencil' onclick='ubahrowng_4("+msg.list[i].id+")' style='cursor:pointer' title='Ubah Belanja'></span></td>";
              row += "<td><span id='hapusrowng' class='icon-remove' onclick='hapusrowng_4("+msg.list[i].id+")' style='cursor:pointer' title='Hapus Belanja'></span></td>";
              row += '</tr>';
              $('#list_tahun_'+tahun).append(row);
              no++;
              total += parseFloat(msg.list[i].subtotal);
            }
            $('#nominal_4').autoNumeric('set', total);
          }
      });

  }

  function ubahrowng_4(id_belanja){
    var tahun = 4;
    var check = $('#id_belanja_'+tahun).val();
    var id_kegiatan = $('input[name="id_kegiatan"]').val();

    if (check == '' || check == null) {
      $('#id_belanja_'+tahun).val(id_belanja);

      $.ajax({
          type: "POST",
          url: '<?php echo site_url("renstra/belanja_kegiatan_edit"); ?>',
          dataType: 'json',
          data: {
          id_kegiatan : id_kegiatan,
          id_belanja : id_belanja,
          tahun : tahun
          },
          success: function(msg){
            $('#list_tahun_'+tahun).html('');
            
            var no = 1;
            var total = 0;
            for (var i = 0; i < msg.list.length; i++) {
              
              var row = '<tr>';
              row += '<td>'+no+'</td>';
              row += '<td>'+msg.list[i].kode_jenis_belanja+'. '+msg.list[i].jenis_belanja+'</td>';
              row += '<td>'+msg.list[i].kode_kategori_belanja+'. '+msg.list[i].kategori_belanja+'</td>';
              row += '<td>'+msg.list[i].kode_sub_kategori_belanja+'. '+msg.list[i].sub_kategori_belanja+'</td>';
              row += '<td>'+msg.list[i].kode_belanja+'. '+msg.list[i].belanja+'</td>';
              row += '<td>'+msg.list[i].uraian_belanja+'</td>';
              row += '<td>'+msg.list[i].Sumber_dana+'</td>';
              row += '<td>'+msg.list[i].detil_uraian_belanja+'</td>';
              row += '<td>'+float_to_num(msg.list[i].volume)+'</td>';
              row += '<td>'+msg.list[i].satuan+'</td>';
              row += '<td>'+float_to_num(msg.list[i].nominal_satuan)+'</td>';
              row += '<td>'+float_to_num(msg.list[i].subtotal)+'</td>';
              row += "<td><span id='ubahrowng' class='icon-pencil' onclick='ubahrowng_4("+msg.list[i].id+")' style='cursor:pointer' title='Ubah Belanja'></span></td>";
              row += "<td><span id='hapusrowng' class='icon-remove' onclick='hapusrowng_4("+msg.list[i].id+")' style='cursor:pointer' title='Hapus Belanja'></span></td>";
              row += '</tr>';
              $('#list_tahun_'+tahun).append(row);
              no++;
              total += parseFloat(msg.list[i].subtotal);
            }
            var jenis = msg.edit.kode_jenis_belanja;
            var kategori = msg.edit.kode_kategori_belanja;
            var sub = msg.edit.kode_sub_kategori_belanja;
            var belanja = msg.edit.kode_belanja;
            var sumber_dana = msg.edit.kode_sumber_dana;
            jenis_belanjanya_4("cb_jenis_belanja_4", jenis);
            kategori_belanjanya_4("cb_kategori_belanja_4", jenis, kategori);
            sub_belanjanya_4("cb_subkategori_belanja_4", jenis, kategori, sub);
            belanja_belanjanya_4("cb_belanja_4", jenis, kategori, sub, belanja);
            sumber_dananya_4("sumberdana_4", sumber_dana);
            $('#uraian_4').val(msg.edit.uraian_belanja);
            $('#det_uraian_4').val(msg.edit.detil_uraian_belanja);
            $('#volume_4').autoNumeric('set', msg.edit.volume);
            $('#satuan_4').val(msg.edit.satuan);
            $('#nominal_satuan_4').autoNumeric('set', msg.edit.nominal_satuan);

            $('#nominal_4').autoNumeric('set', total);
          }
      });

      
    }
  }

</script>
