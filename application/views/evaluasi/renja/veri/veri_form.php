<style type="text/css">
    form{
        color: black;
    }
    .radio-btn{
        width: 97%;
        padding: 10px;
    }
    .radio-btn textarea, .radio-btn .error{
        margin-left: 25px;
        width: 500px;
        float: left;
    }
    .radio-btn textarea{
        height: 100px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $("form#veri_evaluasi_renja").validate({
            rules: {
              ket : "required"
            }
        });

        $("#simpan").click(function(){
            var valid = $("form#veri_evaluasi_renja").valid();
            if (valid) {
                $.blockUI({
                    css: window._css,
                    overlayCSS: window._ovcss
                });

                $.ajax({
                    type: "POST",
                    url: $("form#veri_evaluasi_renja").attr("action"),
                    data: $("form#veri_evaluasi_renja").serialize(),
                    dataType: "json",
                    success: function(msg){
                        if (msg.success==1) {
                            $.blockUI({
                                message: msg.msg,
                                timeout: 2000,
                                css: window._css,
                                overlayCSS: window._ovcss
                            });
                            reload_table();
                            $.facebox.close();
                        };
                    }
                });
            };
        });

        $("#keluar").click(function(){
            $.facebox.close();
        });

        $("input[name=veri]").click(function(){
            $("#simpan").attr("disabled", false);
            if($(this).val()=="0"){
                $("#ket").attr("disabled", false);
            }else{
                $("#ket").val("");
                $("#ket").attr("disabled", true);
            }
        });
    });
</script>
<div style="width: 900px">
	<header>
		<h3>Verifikasi Evaluasi Renja</h3>
	</header>
    <div class="module_content">
    	<table class="fcari" width="99%">
        	<tbody>
            	<tr>
                    <td width="20%">Periode</td>
                    <td width="80%"><?= $periode ?></td>
                </tr>
                <tr>
                    <td width="20%">Tahun</td>
                    <td width="80%"><?= $tahun ?></td>
                </tr>
                <tr>
                    <td width="20%">SKPD</td>
                    <td width="80%"><?= $skpd->nama_skpd ?></td>
                </tr>
                <tr>
                    <td>Kode</td>
                    <td width="80%">
                        <?= $data['evaluasirenja']->kd_urusan.". ".$data['evaluasirenja']->kd_bidang.". ".$data['evaluasirenja']->kd_program ?><?= (!empty($data['evaluasirenja']->kd_kegiatan)?". ".$data['evaluasirenja']->kd_kegiatan.".":'') ?>
                    </td>
                </tr>
                <tr>
                    <td>Kegiatan</td>
                    <td width="80%">
                        <?= $data['evaluasirenja']->nama_prog_or_keg ?>
                    </td>
                </tr>
                <tr>
                    <td>Indikator</td>
                    <td width="80%">
                    <?php
                        $no_indikator=0;
                        foreach ($data['indikator'] as $row_indikator) {
                            $no_indikator++;
                            if ($row_indikator->id == $id_evaluasi_renja) {
                                $satuan=$row_indikator->satuan_target;
                    ?>
                            <strong>
                    <?php
                            }
                    ?>
                            <?= $no_indikator.". ".$row_indikator->indikator ?> <i>(<?= $row_indikator->target_indikator." ".$row_indikator->satuan_target ?>)</i><BR>
                    <?php
                            if ($row_indikator->id == $id_evaluasi_renja) {
                    ?>
                            </strong>
                    <?php
                            }
                        }
                    ?>
                    </td>
                </tr>
            </tbody>
        </table>
		<table class="table-common" width="99%">
        	<tbody>
            <tr>
            	<th colspan="2" width="33%">Target Renstra SKPD Pada Tahun <?= $tahun_terakhir ?> </th>
                <th colspan="2" width="33%">Realisasi Capaian Kinerja Renstra SKPD s./d. Renja SKPD Tahun Lalu (<?= $tahun_sebelum ?>) </th>
                <th colspan="2" width="33%">Target Kinerja & Anggaran Renja SKPD Tahun Berjalan Yang Dievaluasi <?= $tahun ?> </th>
            </tr>
            <tr>
            	<td align="center">K</td>
                <td align="center">Rp.</td>
                <td align="center">K</td>
                <td align="center">Rp.</td>
                <td align="center">K</td>
                <td align="center">Rp.</td>
            </tr>
            <tr>
            	<td align="center"><?= $data['evaluasirenja']->target_akhir_renstra_k." ".$data['evaluasirenja']->satuan ?></td>
                <td align="center"><?= FORMATTING::currency($data['evaluasirenja']->target_akhir_renstra_rp) ?></td>
                <td align="center"><?= $data['evaluasirenja']->realisasi_kinerja_sebelum_k." ".$data['evaluasirenja']->satuan ?></td>
                <td align="center"><?= FORMATTING::currency($data['evaluasirenja']->realisasi_kinerja_sebelum_rp) ?></td>
                <td align="center"><?= $data['evaluasirenja']->target_anggaran_berjalan_k." ".$data['evaluasirenja']->satuan ?></td>
                <td align="center"><?= FORMATTING::currency($data['evaluasirenja']->target_anggaran_berjalan_rp) ?></td>
            </tr>
            </tbody>
        </table>
        <table class="table-common" width="99%">
        	<thead>
            	<tr>
                	<th colspan="8" align="center">Realisasi Kinerja Pada Triwulan</th>
                    <th colspan="2" rowspan="2" width="20%">Realisasi Capaian Kinerja dan Anggaran Renja KSPD Yang Dievaluasi (<?= $tahun ?>)</th>
                </tr>
                <tr>
                    <th colspan="2" width="20%">TW 1</th>
                    <th colspan="2" width="20%">TW 2</th>
                    <th colspan="2" width="20%">TW 3</th>
                    <th colspan="2" width="20%">TW 4</th>
                </tr>
            </thead>
        	<tbody>
                <tr>
                    <td align="center">K</td>
                    <td align="center">Rp.</td>
                    <td align="center">K</td>
                    <td align="center">Rp.</td>
                    <td align="center">K</td>
                    <td align="center">Rp.</td>
                    <td align="center">K</td>
                    <td align="center">Rp.</td>
                    <td align="center">K</td>
                    <td align="center">Rp.</td>
                </tr>
                <tr>
                  <?php
                      for ($i_tw=1; $i_tw <= 4; $i_tw++) {
                          if (!empty($evaluasi_renja['realisasi'][$i_tw])) {
                  ?>
                          <td align="center"><?= $evaluasi_renja['realisasi'][$i_tw]->realisasi_k ?></td>
                  <?php
                          }else{
                  ?>
                          <td></td>
                  <?php
                          }
                          if (!empty($evaluasi_renja['realisasi_prog_keg'][$i_tw])) {
                  ?>
                          <td align="center"><?= FORMATTING::currency($evaluasi_renja['realisasi_prog_keg'][$i_tw]->realisasi_rp) ?></td>
                  <?php
                          }else{
                  ?>
                          <td></td>
                  <?php
                          }
                      }
                  ?>
                    <td align="center"><?= $data['evaluasirenja']->realisasi_kinerja_berjalan_k ?></td>
                    <td align="center"><?= FORMATTING::currency($data['evaluasirenja']->realisasi_kinerja_berjalan_rp) ?></td>
                </tr>
            </tbody>
        </table>
        <table class="fcari" width="99%">
        	<tbody>
            	<tr>
                	<td width="70%" rowspan="2">Tingkat Capaian Kinerja & Anggaran Renja SKPD Yang Dievaluasi (<?= $tahun ?>)</td>
                    <td width="5%">K</td>
                    <td width="35%"><?= $data['evaluasirenja']->tingkat_capaian_k ?></td>

                 </tr>
                 <tr>
                	<td>Rp. </td>
                    <td><?= $data['evaluasirenja']->tingkat_capaian_rp ?></td>
                </tr>
                <tr>
                    <td rowspan="2">Realisasi Kinerja & Anggaran Renstra SKPD s/d Tahun Berjalan (<?= $tahun ?>)</td>
                    <td>K</td>
                    <td><?= $data['evaluasirenja']->realisasi_kinerja_k ?></td>

                 </tr>
                 <tr>
                    <td>Rp. </td>
                    <td><?= FORMATTING::currency($data['evaluasirenja']->realisasi_kinerja_rp) ?></td>
                </tr>
                <tr>
                	<td rowspan="2">Tingkat Capaian Kinerja & Realisasi Anggaran Renstra SKPD s/d Tahun <?= $tahun ?></td>
                    <td>K</td>
                    <td><?= $data['evaluasirenja']->tingkat_capaian_total_k." %" ?></td>

                 </tr>
                 <tr>
                	<td>Rp. </td>
                    <td><?= $data['evaluasirenja']->tingkat_capaian_total_rp." %" ?></td>
                </tr>
            </tbody>
        </table>
        <form id="veri_evaluasi_renja" name="veri_evaluasi_renja" method="POST" accept-charset="UTF-8" action="<?php echo site_url('evaluasi_renja/save_veri'); ?>">
            <input type="hidden" name="id" value="<?php echo $id_evaluasi_renja; ?>">
            <div class="radio">
                <label><input type="radio" name="veri" value="1"> Disetujui</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="veri" value="0"> Tidak Disetujui</label>
            </div>
            <div class="form-group" style="padding-top: 0px">
                <textarea class="form-control" disabled id="ket" name="ket"></textarea>
            </div>
        </form>
    </div>
	<footer>
        <div class="submit_link">
            <input disabled type='button' id="simpan" name="simpan" value='Simpan' />
            <input type='button' id="keluar" name="keluar" value='Keluar' />
        </div>
    </footer>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#edit").click(function(){
            //var idr = $(this).parent().parent().attr("id-r");

            prepare_facebox();
            $.blockUI({
                css: window._css,
                overlayCSS: window._ovcss
            });
            $.ajax({
                type: "POST",
                url: '<?php echo site_url("evaluasi_renja/cru_evaluasi_renja"); ?>',
                dataType: "json",
                data: {idr:$(this).attr("idr"), idi: $(this).attr("idi"), tw: $(this).attr("tw")},
                success: function(msg){
                    catch_expired_session2(msg);
                    $.facebox(msg.html);
                    $.unblockUI();
                }
            });
        });
    });
</script>
