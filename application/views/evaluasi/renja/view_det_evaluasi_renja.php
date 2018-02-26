<div style="width: 900px">
	<header>
		<h3>Evaluasi Renja</h3>
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
                    <td>Kode (Evaluasi Renja)</td>
                    <td width="80%">
                        <?= $data['evaluasirenja']->kd_urusan.". ".$data['evaluasirenja']->kd_bidang.". ".$data['evaluasirenja']->kd_program.". ".$data['evaluasirenja']->kd_kegiatan."." ?>
                    </td>
                </tr>
                <tr>
                    <td>Kegiatan (Evaluasi Renja)</td>
                    <td width="80%">
                        <?= $data['evaluasirenja']->nama_prog_or_keg ?>
                    </td>
                </tr>
                <tr>
                    <td>Indikator (Evaluasi Renja)</td>
                    <td width="80%">
                    <?php
                        $no_indikator=0;
                        foreach ($data['indikator'] as $row_indikator) {
                            $no_indikator++;
                            if ($row_indikator->id_indikator_prog_keg == $data['evaluasirenja']->id_indikator_prog_keg && $row_indikator->id_indikator_prog_keg_cik == $data['evaluasirenja']->id_indikator_prog_keg_cik) {
                                $satuan=$row_indikator->satuan_target;
                    ?>
                            <strong>
                    <?php
                            }
                    ?>
                            <?= $no_indikator.". ".$row_indikator->indikator ?> <i>(<?= $row_indikator->target_indikator." ".$row_indikator->satuan_target ?>)</i><BR>
                    <?php
                            if ($row_indikator->id_indikator_prog_keg == $data['evaluasirenja']->id_indikator_prog_keg && $row_indikator->id_indikator_prog_keg_cik == $data['evaluasirenja']->id_indikator_prog_keg_cik) {
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
                  for ($i=1; $i <= 4; $i++) {
                    if (!empty($data['realisasi'][$i]->realisasi_k) && !empty($data['realisasi_prog_keg'][$i]->realisasi_rp)) {
                      ### Realisasi ada dimana triwulan biasa evaluasi = triwulan realisasi ###
                ?>
                    <td align="center"><?= $data['realisasi'][$i]->realisasi_k." ".$data['evaluasirenja']->satuan ?></td>
                    <td align="center"><?= FORMATTING::currency($data['realisasi_prog_keg'][$i]->realisasi_rp) ?></td>
                <?php
                    }else{
                ?>
                    <td align="center">-</td>
                    <td align="center">-</td>
                <?php
                    }
                ?>

                <?php
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
                    <td align="center" width="15%" rowspan="3">Rupiah</td>
                    <td width="60%">Tingkat Capaian Kinerja & Anggaran Renja SKPD Yang Dievaluasi (<?= $tahun ?>)</td>
                    <td width="35%"><?= $data['evaluasirenja']->tingkat_capaian_rp." %" ?></td>
                </tr>
                <tr>
                    <td>Realisasi Kinerja & Anggaran Renstra SKPD s/d Tahun Berjalan (<?= $tahun ?>)</td>
                    <td><?= FORMATTING::currency($data['evaluasirenja']->realisasi_kinerja_rp) ?></td>
                </tr>
                <tr>
                    <td>Tingkat Capaian Kinerja & Realisasi Anggaran Renstra SKPD s/d Tahun <?= $tahun ?></td>
                    <td><?= $data['evaluasirenja']->tingkat_capaian_total_rp." %" ?></td>
                </tr>
            </tbody>
        </table>
        <table class="fcari" width="99%">
            <tbody>
                 <tr>
                    <td align="center" width="15%" rowspan="3">Kinerja</td>
                    <td width="60%">Tingkat Capaian Kinerja & Anggaran Renja SKPD Yang Dievaluasi (<?= $tahun ?>)</td>
                    <td width="35%"><?= $data['evaluasirenja']->tingkat_capaian_k." %" ?></td>
                 </tr>
                <tr>
                    <td>Realisasi Kinerja & Anggaran Renstra SKPD s/d Tahun Berjalan (<?= $tahun ?>)</td>
                    <td><?= $data['evaluasirenja']->realisasi_kinerja_k ?></td>
                 </tr>
                <tr>
                    <td>Tingkat Capaian Kinerja & Realisasi Anggaran Renstra SKPD s/d Tahun <?= $tahun ?></td>
                    <td><?= $data['evaluasirenja']->tingkat_capaian_total_k." %" ?></td>
                 </tr>
            </tbody>
        </table>
    <?php
        if ($data['evaluasirenja']->status==4) {
    ?>
        <table class="table-common" width="99%">
            <thead>
                <tr>
                    <th colspan="3" align="center" style="color: red">Keterangan Verifikasi Ditolak</th>
                </tr>
                <tr>
                    <th width="10px">No.</th>
                    <th>Keterangan</th>
                    <th width="20%">Tanggal</th>
                </tr>
            </thead>
            <tbody>
        <?php
            $no=0;
            foreach ($ket_revisi as $key => $value) {
                $no++;
        ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $value->ket ?></td>
                    <td><?= FORMATTING::dateINA($value->cru_date, TRUE, "Y-m-d H:i:s") ?></td>
                </tr>
        <?php
            }
        ?>
            </tbody>
        </table>
    <?php
        }
    ?>
    </div>
	<footer>
		<div class="submit_link">
        <?php
            if (!$pusat && !empty($periode_er[$data['evaluasirenja']->triwulan]->active == 1) && $data['evaluasirenja']->status != 3 && $data['evaluasirenja']->status != 5) {
        ?>
            <input id="<?= (!empty($data['evaluasirenja']->id_renja)?'edit':'edit-cik') ?>" type="button" value="Ubah Evaluasi" idr="<?= (!empty($data['evaluasirenja']->id_renja)?$data['evaluasirenja']->id_renja:$data['evaluasirenja']->id_cik) ?>" idi="<?= (!empty($data['evaluasirenja']->id_indikator_prog_keg)?$data['evaluasirenja']->id_indikator_prog_keg:$data['evaluasirenja']->id_indikator_prog_keg_cik) ?>" tw="<?= $triwulan ?>">
        <?php
            }
        ?>
		</div>
	</footer>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#edit").click(function(){
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

        $("#edit-cik").click(function(){
            prepare_facebox();
            $.blockUI({
                css: window._css,
                overlayCSS: window._ovcss
            });
            $.ajax({
                type: "POST",
                url: '<?php echo site_url("evaluasi_renja/cru_evaluasi_cik"); ?>',
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
