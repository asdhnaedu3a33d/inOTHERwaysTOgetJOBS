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

      //if($('#ck_desa').is(':checked')) postData['param_desa'] = $('#param_desa').val();

      $.post('<?php echo site_url('musrenbang/musrenbangdes/search_rekap_keg_desa')?>',
            postData, function(data) {
              catch_expired_session2(data);

              $('#view_rekap_keg_desa').html(data);
            });
    }

    $(function() {
      $('#search').click(function() {        
        search();
        return false;
      });

      $('#search').trigger('click');
    });
</script>

<article class="module width_full">
<header>
  <h3>Rekapitulasi Kegiatan Desa/Kelurahan</h3>
</header>             
 <div class="module_content">      

<hr />
<form>
<table class='fcari' width='100%'>
  <tr style="display: none;">
    <td></td>
    <td><input type="submit" name="search" id="search" value="Tampilkan" />      
    </td>      
  </tr>    
</table>
<div style='float:right'><a href="<?php echo site_url('musrenbang/musrenbangdes/create_rekap_keg_desa')?>">Tambah Data Musrembangdes</a></div><br>
</form>
<hr>
<div id="view_rekap_keg_desa">
</div>
</article>