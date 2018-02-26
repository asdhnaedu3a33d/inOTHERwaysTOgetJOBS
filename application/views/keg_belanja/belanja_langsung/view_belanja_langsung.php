<script type="text/javascript">
    var dt;
    $(document).ready(function(){
        dt = $("#belanja_langsung").DataTable({
          "processing": true,
            "serverSide": true,
            "aoColumnDefs": [
                  {
                      "bSortable": false,
                      "aTargets": ["no-sort"]
                  }
              ],
              "ajax": {
                "url": "<?php echo $url_load_data; ?>",
                "type": "POST"
            }
        });

        $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
          if (e.keyCode == 13) {
              dt.search( this.value ).draw();
          }
      } );
    });

    function edit_belanja(id){
      window.location = '<?php echo $url_edit_data;?>/' + id;
    }

    function delete_belanja(id){
      if (confirm('Apakah anda yakin untuk menghapus data belanja_langsung ini?')) {
        $.blockUI({
          message: 'Proses penghapusan sedang dilakukan, mohon ditunggu ...',
          css: window._css,
          overlayCSS: window._ovcss
        });

        $.ajax({
          type: "POST",
          url: '<?php echo $url_delete_data; ?>',
          data: {id: id},
          dataType: "json",
          success: function(msg){
            catch_expired_session2(msg);
                      console.log(msg);
            if (msg.success==1) {
                //reload table
                          dt.draw();
              $.blockUI({
                message: msg.msg,
                timeout: 2000,
                css: window._css,
                overlayCSS: window._ovcss
              });
            };
          }
        });
      };
    }
</script>
<article class="module widh_full">
  <header>
    <h3> TABEL DATA BELANJA LANGSUNG </h3>
  </header>
  <div class="module_content"; style="overflow:auto">
    <div style='float:right'>
      <a href="<?php echo $url_add_data ?>"><input style="margin: 3px 10px 0 10px; float: right;" type="button" value="Tambah Data Belanja Langsung" /></a>
    </div><br>
  <table id="belanja_langsung" class="table-common tablesorter" style="width:100%">
    <thead>
      <tr>
        <th class="no-sort" rowspan="2">No</th>
        <th rowspan="2">Jenis Belanja Langsung</th>
        <th colspan="2">Realisasi</th>
        <th colspan="3">Proyeksi</th>
        <th rowspan="2">Action</th>
      </tr>
      <?php
        $ta=$this->m_settings->get_tahun_anggaran();
        $tahun_n1=$ta;
        $tahun_n2= $ta+1;
        $tahun_n3= $ta+2;
        $tahun_n4= $ta+3;
        $tahun_n5= $ta+4;
      ?>
      <tr>
        <th align="center">Tahun <?php echo $tahun_n1;?></th>
        <th align="center">Tahun <?php echo $tahun_n2;?></th>
        <th align="center">Tahun <?php echo $tahun_n3;?></th>
        <th align="center">Tahun <?php echo $tahun_n4;?></th>
        <th align="center">Tahun <?php echo $tahun_n5;?></th>
      </tr>
    </thead>
    <tbody>

    </tbody>
</thead>
  </table>

</div>
</article>
