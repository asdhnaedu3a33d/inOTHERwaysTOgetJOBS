<style type="text/css">
table.fcari tr:nth-child(odd) {
  background-color: #EEE;
}

table.fcari tr:nth-child(even) {
  background-color: #FFF;
}
</style>

<script type="text/javascript">
    function search() {
      var postData = new Object();

      if($('#ck_desa').is(':checked')) postData['param_desa'] = $('#param_desa').val();

      $.post('<?php echo site_url('musrenbang/musrenbangcam/search_rekap_keg_kecamatan')?>',
            postData, function(data) {
              catch_expired_session2(data);

              $('#view_rekap_keg_kecamatan').html(data);
            });
    }

    $(function() {
      //$('#search').click(function() {        
        search();
      //  return false;
      //});

      //$('#search').trigger('click');
    });
</script>

<article class="module width_full">
<header>
  <h3>Rekapitulasi Kegiatan Kecamatan</h3>
</header>             
 <div class="module_content">      

<hr />
<form 
<?php if(!$this->session->userdata('id_kecamatan')){
    echo 'style="display: none;"';
}    
?>
>
<table class='fcari' width='100%'>   
  <tr>
    
    <td width='150px'>
      <input type='checkbox' id='ck_desa' name='ck_desa' for='param_desa'>&nbsp;
      Desa</td>
    <td>
        <select name="param_desa" id="param_desa">
          <?php foreach($nama_desa as $nk){
            echo "<option value='{$nk->id_desa}'>{$nk->nama_desa}</option>";
          }
          ?>
        </select>
    </td>     
  </tr>    
  <tr>
    <td></td>
    <td><input type="submit" name="search" id="search" value="Tampilkan" />      
    </td>      
  </tr>    
</table>
</form>
<div style='float:right'><a href="<?php echo site_url('musrenbang/musrenbangcam/create_rekap_keg_kecamatan')?>">Tambah Data Musrembangcam</a></div><br>
<div id="view_rekap_keg_kecamatan">
</div>
</article>