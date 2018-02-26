<!--==============================  -->
<script language="javascript">
  
  prepare_facebox();

  function delete_musrembangcam(id) {
    console.log('delete',id);

    if(confirm('Apakah anda yakin untuk menghapus data musrembang ini?')) {

      $.blockUI({
                  message: 'Proses penghapusan sedang dilakukan, mohon ditunggu ...',
                  css: window._css,
                  overlayCSS: window._ovcss
      });      

      $.post("<?php echo site_url('musrenbang/musrenbangcam/delete_musrenbangcam')?>", {id_musrembangcam: id})
      .success(function(data) {
        var result = parseJSON(data);
        
        //alert(result.message);
        if(!result.message)
          result.message = 'Terjadi kesalahan sistem, mohon diulang kembali';

        $.blockUI({
          message: result.message,
          timeout: 2000,
          css: window._css,
          overlayCSS: window._ovcss
        });

        $('#search').trigger('click');
      });
    }
  }

  function edit_musrenbangcam(id) {
    console.log('edit',id);    
    window.location = '<?php echo site_url('musrenbang/musrenbangcam/edit_musrenbangcam')?>/' + id;
  }

  $(function() {
    $('#tsearch').tablesorter(
        {headers: {
          0:{sorter:false},
          //7:{sorter:false},
          8:{sorter:false}
        } 
    });
  
  var table = $('#tsearch');
    table.find('tr.detail').hide();
    
    table.find('tr.accordion').click( function() {
      $(this).next().toggle();
            if($(this).next().is(':visible')){
                $(this).css({ 'background-color': 'lightgrey', 'font-weight' : 'bold'}); 
        //ambil kwitansinya
        var td = $(this).next().find("td").next();
        td.html('<center> Loading data ... </center>');
        $.ajax({
          type:   "POST",
          url:  "<?php echo site_url('musrenbang/musrenbangcam/ajax_get_musrembangcam')?>",
          data: {id_musrembang: $(this).next().attr("id_musrembang")},
          success: function (msg) {
            td.html(msg);                 
          }
        });               
            } else {
                $(this).css({ 'background-color': 'white', 'font-weight' : 'normal'});
            }
    }); 
  });

</script>

<style type="text/css">
.header {
  text-indent: 0;
  text-align: left;
}

</style>
<table class='table-common tablesorter' style='width: 99.9%' id='tsearch'>  
  <thead>
  <tr>
  	<th >No</th>
  <!--  <th >Kode Urusan</th>
  	<th >Kode Bidang</th>
    <th >Kode Program</th>
    <th >Kode Kegiatan</th> -->
    <th colspan="4">Kode</th>
    <th >Jenis Pekerjaan</th>
    <th >Volume</th>
    <th >Satuan</th>
    <th >Jumlah Dana</th>
    <th >Nama Desa</th>
    <th >Nama Kecamatan</th>
    <th >Action</th>    
  </tr>
  </thead>
  <tbody>
  <?php if(!empty($rows)) {
	$i=0;
	foreach($rows as $row) : $i++;
  ?>
    <tr class="accordion">
        <td align="center"><?php echo $i;?></td>
        <td ><?php echo $row->Kd_Urusan; ?></td>
        <td ><?php echo $row->Kd_Bidang; ?></td>
        <td ><?php echo $row->Kd_Prog; ?></td>
        <td ><?php echo $row->Kd_Keg; ?></td>
        <td ><?php echo $row->jenis_pekerjaan; ?> </td>
        <td ><?php echo $row->volume; ?> </td>
        <td ><?php echo $row->satuan; ?> </td>
        <td align="right"><?php echo Formatting::currency($row->jumlah_dana); ?> </td>
        <td ><?php echo $row->nama_desa; ?> </td>
        <td ><?php echo $row->nama_kecamatan; ?> </td>
        <td align="center">            
              <a href="javascript:void(0)" onclick="delete_musrenbangcam(<?php echo $row->id_musrembang;?>)" class='icon2-delete' 
              title='hapus musrembangcam'/>
              <a href="javascript:void(0)" onclick="edit_musrenbangcam(<?php echo $row->id_musrembang;?>)" class='icon2-page_white_edit' 
              title='edit musrembangcam'/>
	       </td>         
    </tr>
    <tr class='detail' id="<?php echo $row->id_musrembang?>">
        <td></td>
        <td colspan="11" style="padding: 0px; margin: 0px;"></td> 
    </tr>    
  <?php endforeach;?>  
  </tbody>
</table>
<br />
<?php } else { ?>
  <tr>
    <td align='center' colspan="12" style="padding-bottom: 15px; padding-top: 15px;">Data Kosong</td>
  </tr>
</table>
<br/> 
<?php } ?> 