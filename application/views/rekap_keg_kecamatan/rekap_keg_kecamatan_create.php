<style type="text/css">
  table.fcari tr:nth-child(odd) {
    background-color: #EEE;
  }

  table.fcari tr:nth-child(even) {
    background-color: #FFF;
  }

  .autocomplete_field{
    width: 500px;
  }
</style>


<script language="javascript">
    $(document).ready(function(){
    $('#jumlah_dana').autoNumeric(numOptions);

    $("#input_musrembang").validate({
      rules: {
          Kd_Urusan_autocomplete : "required",
          Kd_Bidang_autocomplete: "required",
          Kd_Prog_autocomplete : 'required',
          Kd_Keg_autocomplete: "required",
          jenis_pekerjaan: "required",
          volume: "required",
          satuan: "required",
          jumlah_dana : "required",
          id_desa : "required"
      },
      messages: {
          Kd_Urusan_autocomplete: "Mohon diisi kode urusan",
          Kd_Bidang_autocomplete: "Mohon diisi kode bidang",
          Kd_Prog_autocomplete : "Mohon diisi kode program",
          Kd_Keg_autocomplete: "Mohon diisi kode kegiatan",
          jenis_pekerjaan: "Mohon diisi jenis_pekerjaan",
          volume: "Mohon diisi volumenya",
          satuan: "Mohon diisi satuannya",
          jumlah_dana : "Mohon diisi jumlah dana",
          id_desa : "Mohon diisi id desa"
      },
      submitHandler: function(form){
        $("#jumlah_dana").val($("#jumlah_dana").autoNumeric('get'));
        form.submit();
        
      }
    });
    
    $("#Kd_Urusan_autocomplete").autocomplete({
      minLength: 0,
      source: 
      function(req, add){
          $("#Kd_Urusan").val("");
          $("#Kd_Bidang_autocomplete").val("");
          $("#Kd_Prog_autocomplete").val("");
          $("#Kd_Keg_autocomplete").val("");
          req.id_skpd = <?php echo $this->session->userdata('id_skpd')?>;
          $.ajax({
              url: "<?php echo base_url('musrenbang/musrenbangcam/autocomplete_kdurusan'); ?>",
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
        $("#Kd_Urusan").val(ui.item.id);                          
      }
    }).focus(function(){            
        $(this).trigger('keydown.autocomplete');
    });

    $("#Kd_Bidang_autocomplete").autocomplete({
      minLength: 0,
      source: 
      function(req, add){
          $("#Kd_Bidang").val("");              
          $("#Kd_Prog_autocomplete").val("");
          $("#Kd_Keg_autocomplete").val("");
          req.id_skpd = <?php echo $this->session->userdata('id_skpd')?>;
          req.kd_urusan = $("#Kd_Urusan").val();
          $.ajax({
              url: "<?php echo base_url('musrenbang/musrenbangcam/autocomplete_kdbidang'); ?>",
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
        $("#Kd_Bidang").val(ui.item.id);              
      }
    }).focus(function(){            
        $(this).trigger('keydown.autocomplete');
    });

    $("#Kd_Prog_autocomplete").autocomplete({
      minLength: 0,
      source: 
      function(req, add){
          $("#Kd_Prog").val("");                            
          $("#Kd_Keg_autocomplete").val("");
          req.id_skpd = <?php echo $this->session->userdata('id_skpd')?>;
          req.kd_urusan = $("#Kd_Urusan").val();
          req.kd_bidang = $("#Kd_Bidang").val();
          $.ajax({
              url: "<?php echo base_url('musrenbang/musrenbangcam/autocomplete_kdprog'); ?>",
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
        $("#Kd_Prog").val(ui.item.id);              
      }
    }).focus(function(){            
        $(this).trigger('keydown.autocomplete');
    });

    $("#Kd_Keg_autocomplete").autocomplete({
      minLength: 0,
      source: 
      function(req, add){
          $("#Kd_Keg").val("");                            
          req.id_skpd = <?php echo $this->session->userdata('id_skpd')?>;
          req.kd_urusan = $("#Kd_Urusan").val();
          req.kd_bidang = $("#Kd_Bidang").val();
          req.kd_prog   = $("#Kd_Prog").val();
          $.ajax({
              url: "<?php echo base_url('musrenbang/musrenbangcam/autocomplete_keg'); ?>",
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
        $("#Kd_Keg").val(ui.item.id);              
      }
    }).focus(function(){            
        $(this).trigger('keydown.autocomplete');
    });
    console.log("hei");
  });
</script>

<article class="module width_full">
<header><h3>
        <?php
            if (isset($isEdit) && $isEdit){
                echo "Edit Data Musrembangcam";
            } else{
                echo "Input Data Musrembangcam";
            }
        ?>
</h3></header>

<div class="module_content">
<!-- page content -->
<table style='background-color: yellow' width="100%">
<tr >
    <td >Input Data Musrembangcam</td>
</tr>    
</table>
<hr />

<form id="input_musrembang" action="<?php echo site_url('musrembang/save_rekap_keg_kecamatan');?>" 
  method="POST" name="cek" id="cek" accept-charset="UTF-8" enctype="multipart/form-data" >
<input name='call_from' type='hidden' id="call_from" value='<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ?>'/>
<input name='id_musrembangcam' type='hidden' id="id_musrembangcam" value="<?php echo isset($id_musrembangcam) ? $id_musrembangcam : ''?>"  />   
<div>
    <table id="musrembang_input" class="fcari" width="100%">                    
    
    <tr>
        <td style="width:20%">Nama Urusan</td>
        <td>            
            <label for="Kd_Urusan_autocomplete"></label>
            <input type="text" id="Kd_Urusan_autocomplete" name="Kd_Urusan_autocomplete" class="autocomplete_field" value="<?php if(!empty($nm_urusan)){echo $nm_urusan;} ?>" />
            <input type="hidden"id="Kd_Urusan" name="Kd_Urusan" value="<?php if(!empty($urusan)){echo $urusan;} ?>"/>            
        </td>
    </tr>
    <tr>
        <td>Nama Bidang</td>
        <td> 
          <label for="Kd_Bidang_autocomplete"></label>
          <input type="text" id="Kd_Bidang_autocomplete" name="Kd_Bidang_autocomplete" class="autocomplete_field" value="<?php if(!empty($nm_bidang)){echo $nm_bidang;} ?>" />
          <input type="hidden"id="Kd_Bidang" name="Kd_Bidang" value="<?php if(!empty($bidang)){echo $bidang;} ?>"/>
        </td>
    </tr>
    <tr>
        <td>Nama Program</td>
        <td>          
          <label for="Kd_Prog_autocomplete"></label>
          <input type="text" id="Kd_Prog_autocomplete" name="Kd_Prog_autocomplete" class="autocomplete_field" value="<?php if(!empty($nm_program)){echo $nm_program;} ?>"/>
          <input type="hidden"id="Kd_Prog" name="Kd_Prog" value="<?php if(!empty($program)){echo $program;} ?>"/>
        </td>
    </tr>
    <tr>
        <td>Nama Kegiatan</td>
        <td>
          <label for="Kd_Keg_autocomplete"></label>
          <input type="text" id="Kd_Keg_autocomplete" name="Kd_Keg_autocomplete" class="autocomplete_field" value="<?php if(!empty($nm_kegiatan)){echo $nm_kegiatan;} ?>"/>
          <input type="hidden"id="Kd_Keg" name="Kd_Keg" value="<?php if(!empty($kegiatan)){echo $kegiatan;} ?>"/>            
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
  </table>
    <hr />
  </div>
</div>
<div id="list_musrembang">
</div>

<footer>
<div class="submit_link">  
      <input name="simpan" type='submit' id="simpan" value='Simpan' >
      <input type=button value=Batal onclick="window.location='<?php 
        $call_from = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        if(!empty($call_from) && strpos($call_from, 'musrembang/create_rekap_keg_kecamatan') == FALSE)
          echo $call_from;
        else 
          echo site_url('musrembang/view_rekap_keg_kecamatan');
      ?>'">
    </div> </footer>
</form>
   
</article>
<!-- end of styles article --> 