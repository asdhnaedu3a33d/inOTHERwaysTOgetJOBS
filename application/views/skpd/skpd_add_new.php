<section class="content">
    <div class="row">
        <div class="col-md-12">
             <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><strong>Tambah SKPD</strong></h3>
                </div>
                <div class="box-body">
                    <form method="post"  id="Simpan" action="<?php echo base_url('master_skpd/skpd_addDB');?>" >
                   
                    <div class="form-group">
                      <label style="font-weight:normal;">Bid. Koordinasi</label>
                      <select class="form-control select2" style="width: 100%;" name="IdKoor">
                      <?php
                          foreach($bidkoor as $u){
                      ?>
                        <option value="<?php echo $u->id_bidkoor; ?>" ><?php echo $u->nama_koor; ?></option>
                        <?php } ?>
                      </select>
                    </div>

                    <div class="form-group" id="BidangUrusan">
                      <label style="font-weight:normal;">Bidang</label>
                     <select id="IdBidangUrusan" class="form-control select2" style="width: 100%;" name="IdBidangUrusan[]" multiple="multiple">
                        <?php
                          foreach($bidang as $u1){
                      ?>
                        <option value="<?php echo $u1->id; ?>" ><?php echo $u1->nama; ?></option>
                        <?php } ?>
                      </select>
                      </select>
                    </div>
                    
                   
                    <div class="form-group">
                            <label style="font-weight:normal;">Kode SKPD</label>
                            <input type="text" name="KodeSkpd" id="KodeSkpd"   class="form-control"  placeholder="">
                    </div>
                    <div class="form-group">
                            <label style="font-weight:normal;">Kode Pos</label>
                            <input type="text" name="KodePos" id="KodePos"   class="form-control"  placeholder="">
                    </div>
                    <div class="form-group">
                            <label style="font-weight:normal;">Nama SKPD</label>
                            <input type="text" name="NamaSkpd" id="NamaSkpd"   class="form-control"  placeholder="">
                    </div>
                    <div class="form-group">
                            <label style="font-weight:normal;">Alamat SKPD</label>
                            <input type="text" name="AlamatSkpd" id="AlamatSkpd"   class="form-control"  placeholder="">
                    </div>
                    <div class="form-group">
                            <label style="font-weight:normal;">No Tlp SKPD</label>
                            <input type="text" name="TelptSkpd" id="TelptSkpd"   class="form-control"  placeholder="">
                    </div>
                    <div class="form-group">
                            <label style="font-weight:normal;">Fax SKPD</label>
                            <input type="text" name="FaxSkpd" id="FaxSkpd"   class="form-control"  placeholder="">
                    </div>
                    <div class="form-group">
                            <label style="font-weight:normal;">Nama Kepala SKPD</label>
                            <input type="text" name="KepalaSkpd" id="KepalaSkpd"   class="form-control"  placeholder="">
                    </div>
                    <div class="form-group">
                            <label style="font-weight:normal;">NIP Kepala SKPD</label>
                            <input type="text" name="NipSkpd" id="NipSkpd"   class="form-control"  placeholder="">
                    </div>
                    <div class="form-group">
                            <label style="font-weight:normal;">Pangkat /Golongan</label>
                            <input type="text" name="PangkatGolongan" id="PangkatGolongan"   class="form-control"  placeholder="">
                    </div>

                   <div class="form-group">
                      <button type="submit" value="Validate" class="btn btn-primary"><i class='glyphicon glyphicon-ok'></i> Simpan</button>
                    </div>
                    </form>
                </div>
               </div>
         </div>
    </div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>

<script type="text/javascript">
   $(document).on('change', '#IdUrusan', function(){
  var id = $('#IdUrusan').val();
        $.ajax({
            type: "POST",
            dataType:'html',
           url: '<?php echo site_url("master_skpd/select_bidang_byurusan");?>',
            data: {idurusan: id},
            success: function(data){
                 $('#BidangUrusan').html(data);  
                 $(".select2").select2();
            }
          });
    });
</script>

<script>
  $(function () {
    $(".select2").select2();

  });
  </script>


  <style type="text/css">
 .select2-container--default .select2-selection--multiple .select2-selection__choice {
       background-color: #3c8dbc;
    border-color: #367fa9;
    border-radius: 4px;
    cursor: default;
    float: left;
    margin-right: 5px;
    margin-top: 5px;
    padding: 0 5px;
}
.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    box-sizing: border-box;
    list-style: none;
    /* margin: 0; */
    padding: 0 5px;
    width: 100%;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    /* line-height: 28px; */
}


</style>


</section>


