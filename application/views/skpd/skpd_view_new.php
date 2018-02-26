<section class="content">
<div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><strong>SKPD</strong> </h3>
          <a style="float:right"  href="<?php echo base_url('master_skpd/skpd_add');?>" class="btn btn-primary">
            <i class="glyphicon glyphicon-saved"></i>
            Tambah SKPD
          </a>
        </div>
    <!-- /.box-header -->
        <div class="box-body">
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr >
                                     <th style="display :none;">Bid Koor</th>
                                        <th style="display :none;">Bid Koor</th>
                                        <th >Kode SKPD</th>
                                        <th >Nama SKPD</th>
                                        <th>NO Telepon</th>
                                        <th>Nama Kepala SKPD</th>
                                         <th>Pilihan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach($data as $u){

                                    ?>
                                    <tr  class='odd gradeX context'>
                                       <td style="display :none;" class="skpd"><?php echo $u->id_skpd ?></td>
                                        <td style="display :none;"><?php echo $u->id_bidkoor ?></td>
                                        <td><?php echo $u->kode_skpd?></td>
                                        <td><?php echo $u->nama_skpd?></td>
                                        <td><?php echo $u->telp_skpd?></td> 
                                         <td><?php echo $u->kaskpd_nama?></td>
                                         <td align="center">
                                         <a class="btn btn-warning btn-sm" title="Ubah SKPD"   href="<?php echo base_url('master_skpd/skpd_edit/'.$u->id_skpd); ?>">  <span class="fa fa-fw fa-edit" ></span> </a>


                                        </td>






                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
          </div>



        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->

</section><!-- /.content -->

<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
