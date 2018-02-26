<div style="width: 900px">
	<header>
		<h3>Evaluasi Renja</h3>
	</header>
    <div class="module_content">
        <?php
            if (!empty($evaluasi_detail) && $evaluasi_detail->status==4) {
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
                        <?= $data['prog_keg']->kd_urusan.". ".$data['prog_keg']->kd_bidang.". ".$data['prog_keg']->kd_program.". ".$data['prog_keg']->kd_kegiatan ?> (<strong><?= $source ?></strong>)
                    </td>
                </tr>
                <tr>
                    <td>Kegiatan</td>
                    <td width="80%">
                        <?= $data['prog_keg']->nama_prog_or_keg ?>
                    </td>
                </tr>
                <tr>
                    <td>Indikator</td>
                    <td width="80%">
                    <?php
                        $no_indikator=0;
                        foreach ($data['indikator'] as $row_indikator) {
                            $no_indikator++;
                            if ($row_indikator->id == $id_indikator) {
                                $satuan=$row_indikator->satuan_target;
                                $nama_indikator=$row_indikator->indikator;
                                $target_indikator=$row_indikator->target;
                    ?>
                            <strong>
                    <?php
                            }
                    ?>
                            <?= $no_indikator.". ".$row_indikator->indikator ?> <i>(<?= $row_indikator->target." ".$row_indikator->satuan ?>)</i><BR>
                    <?php
                            if ($row_indikator->id == $id_indikator) {
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
            	<td align="center"><?= (!empty($indikator_renstra->target_kondisi_akhir)?$indikator_renstra->target_kondisi_akhir." ".$indikator_renstra->satuan:'-') ?></td>
                <td align="center"><?php $total_renstra=@$renstra->nominal_1+@$renstra->nominal_2+@$renstra->nominal_3+@$renstra->nominal_4+@$renstra->nominal_5; echo FORMATTING::currency($total_renstra) ?></td>
                <td align="center"><?= $realisasi_capaian_tahun_lalu['target'] ?></td>
                <td align="center"><?= FORMATTING::currency($realisasi_capaian_tahun_lalu['nominal']) ?></td>
                <td align="center"><?= (!empty($indikator_renstra->$kolom_now['target'])?$indikator_renstra->$kolom_now['target']." ".$indikator_renstra->satuan:'-') ?></td>
                <td align="center"><?= FORMATTING::currency(@$renstra->$kolom_now['nominal']) ?></td>
            </tr>
            </tbody>
        </table>
        <table class="table-common" width="99%">
        	<thead>
            	<tr>
                	<th colspan="8" align="center">Realisasi Kinerja Pada Triwulan</th>
                    <th colspan="2" rowspan="2" width="20%">Realisasi Capaian Kinerja dan Anggaran Renja SKPD Yang Dievaluasi (<?= $tahun ?>)</th>
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
                    <td align="center"><?= ($tw>=1?$cik['1']['target']:'-') ?></td>
                    <td align="center"><?= ($tw>=1?FORMATTING::currency($cik['1']['nominal']):'-') ?></td>
                    <td align="center"><?= ($tw>=2?$cik['2']['target']:'-') ?></td>
                    <td align="center"><?= ($tw>=2?FORMATTING::currency($cik['2']['nominal']):'-') ?></td>
                    <td align="center"><?= ($tw>=3?$cik['3']['target']:'-') ?></td>
                    <td align="center"><?= ($tw>=3?FORMATTING::currency($cik['3']['nominal']):'-') ?></td>
                    <td align="center"><?= ($tw>=4?$cik['4']['target']:'-') ?></td>
                    <td align="center"><?= ($tw>=4?FORMATTING::currency($cik['4']['nominal']):'-') ?></td>
                    <td align="center"><?= $cik['ak']['target'] ?></td>
                    <td align="center"><?= FORMATTING::currency($cik['ak']['nominal']) ?></td>
                </tr>
            </tbody>
        </table>
        <form id="evaluasirenja">
            <input type="hidden" name="kd_urusan" value="<?= $data['prog_keg']->kd_urusan ?>" />
            <input type="hidden" name="kd_bidang" value="<?= $data['prog_keg']->kd_bidang ?>" />
            <input type="hidden" name="kd_program" value="<?= $data['prog_keg']->kd_program ?>" />
          <?php
            if (!empty($data['prog_keg']->kd_kegiatan)) {
          ?>
            <input type="hidden" name="kd_kegiatan" value="<?= $data['prog_keg']->kd_kegiatan ?>" />
          <?php
            }
          ?>
            <input type="hidden" name="<?= $source_id_prog_keg ?>" value="<?= $data['prog_keg']->id ?>" />
            <input type="hidden" name="nama_prog_or_keg" value="<?= $data['prog_keg']->nama_prog_or_keg ?>" />
            <input type="hidden" name="id_skpd" value="<?= $data['prog_keg']->id_skpd ?>" />
            <input type="hidden" name="tahun" value="<?= $data['prog_keg']->tahun ?>" />
            <input type="hidden" name="is_prog_or_keg" value="<?= $data['prog_keg']->is_prog_or_keg ?>" />

            <input type="hidden" name="target_akhir_renstra_k" value="<?= $indikator_renstra->target_kondisi_akhir ?>" />
            <input type="hidden" name="target_akhir_renstra_rp" value="<?= $total_renstra ?>" />
            <input type="hidden" name="realisasi_kinerja_sebelum_k" value="<?= $realisasi_capaian_tahun_lalu['target'] ?>" />
            <input type="hidden" name="realisasi_kinerja_sebelum_rp" value="<?= $realisasi_capaian_tahun_lalu['nominal'] ?>" />
            <input type="hidden" name="target_anggaran_berjalan_k" value="<?= $indikator_renstra->$kolom_now['target'] ?>" />
            <input type="hidden" name="target_anggaran_berjalan_rp" value="<?= @$renstra->$kolom_now['nominal'] ?>" />

        <?php
          for ($i=1; $i <= $tw; $i++) {
        ?>
            <input type="hidden" name="realisasi_k[<?= $i ?>]" value="<?= $cik[$i]['target'] ?>" />
            <input type="hidden" name="realisasi_rp[<?= $i ?>]" value="<?= $cik[$i]['nominal'] ?>" />
        <?php
          }
        ?>
            <input type="hidden" name="realisasi_kinerja_berjalan_k" value="<?= $cik['ak']['target'] ?>" />
            <input type="hidden" name="realisasi_kinerja_berjalan_rp" value="<?= $cik['ak']['nominal'] ?>" />

            <input type="hidden" name="satuan" value="<?= @$satuan ?>" />
            <input type="hidden" name="tahun" value="<?= $tahun ?>" />
            <input type="hidden" name="<?= $source_id ?>" value="<?= $id_indikator ?>" />
            <input type="hidden" name="triwulan_berjalan" value="<?= $tw ?>" />
            <input type="hidden" name="indikator" value="<?= $nama_indikator ?>" />
            <input type="hidden" name="target_indikator" value="<?= $target_indikator ?>" />
						<input type="hidden" name="penanggung_jawab" value="<?= @$renstra->penanggung_jawab ?>" />

            <input type="hidden" name="status_5t" value="<?= $status_5t ?>" />
            <input type="hidden" name="status_1t" value="<?= $status_1t ?>" />
            <input type="hidden" name="status_r" value="<?= $status_r ?>" />
        <?php
          if (!empty($evaluasi_detail->id_evaluasi_renja)) {
        ?>
            <input type="hidden" name="id_evaluasi_renja" value="<?= $evaluasi_detail->id_evaluasi_renja ?>" />
            <input type="hidden" name="id_evaluasi_renja_prog_keg" value="<?= $evaluasi_detail->id_evaluasi_renja_prog_keg ?>" />
        <?php
          }
        ?>
            <table class="fcari" width="99%">
            	  <tbody>
                	   <tr>
                        <td align="center" width="15%" rowspan="3">Rupiah</td>
                    	  <td width="60%">Tingkat Capaian Anggaran Renja SKPD Yang Dievaluasi (<?= $tahun ?>) (%)</td>
                        <td><input readonly type="text" class="common" name="tingkat_capaian_rp" value="<?php $pembagi_capaian_rp=(@$renstra->$kolom_now['nominal']!=0?$renstra->$kolom_now['nominal']:1); echo round(($cik['ak']['nominal']/$pembagi_capaian_rp)*100, 2) ?>" /></td>
                    </tr>
                    <tr>
                        <td>Realisasi Anggaran Renstra SKPD s/d Tahun Berjalan (<?= $tahun ?>)</td>
                        <td><input readonly type="text" class="common" name="realisasi_kinerja_rp" value="<?php $realisasi_berjalan_rp = $cik['ak']['nominal']+$realisasi_capaian_tahun_lalu['nominal']; echo $realisasi_berjalan_rp ?>" /></td>
                    </tr>
                    <tr>
                        <td>Tingkat Capaian Realisasi Anggaran Renstra SKPD s/d Tahun <?= $tahun ?> (%)</td>
                        <td><input readonly type="text" class="common" name="tingkat_capaian_total_rp" value="<?php $pembagi_capaian_total_rp=($total_renstra!=0?$total_renstra:1); echo round(($realisasi_berjalan_rp/$pembagi_capaian_total_rp)*100, 2) ?>" /></td>
                    </tr>
                </tbody>
            </table>
            <table class="fcari" width="99%">
            	  <tbody>
                	   <tr>
                        <td align="center" width="15%" rowspan="3">Kinerja</td>
                    	  <td width="60%">Tingkat Capaian Kinerja Renja SKPD Yang Dievaluasi (<?= $tahun ?>) (%)</td>
                        <td bgcolor="#00FF33" width="35%"><input type="text" class="common" name="tingkat_capaian_k" value="<?= @$evaluasi_detail->tingkat_capaian_k ?>"></td>
                     </tr>
                    <tr>
                        <td>Realisasi Kinerja Renstra SKPD s/d Tahun Berjalan (<?= $tahun ?>)</td>
                        <td><input readonly type="text" class="common" name="realisasi_kinerja_k" value="<?php $realisasi_berjalan_k = $cik['ak']['target']+$realisasi_capaian_tahun_lalu['target']; echo $realisasi_berjalan_k ?>"></td>
                     </tr>
                    <tr>
                        <td>Tingkat Capaian Kinerja Anggaran Renstra SKPD s/d Tahun <?= $tahun ?> (%)</td>
                        <td bgcolor="#00FF33"><input type="text" class="common" name="tingkat_capaian_total_k" value="<?= @$evaluasi_detail->tingkat_capaian_total_k ?>"></td>
                     </tr>
                </tbody>
            </table>
        </form>
    </div>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>*** Silahkan inputkan data Evaluasi Renja yang sesuai di dalam kolom berwarna hijau</em></p>
	<footer>
		<div class="submit_link">
  			<input id="simpan" type="button" value="Simpan">
		</div>
	</footer>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('input[name=tingkat_capaian_rp]').autoNumeric(numOptionsNotRound);
        $('input[name=realisasi_kinerja_rp]').autoNumeric(numOptionsNotRound);
        $('input[name=tingkat_capaian_total_rp]').autoNumeric(numOptionsNotRound);
        $('input[name=tingkat_capaian_k]').autoNumeric(numOptionsNotRound);
        $('input[name=realisasi_kinerja_k]').autoNumeric(numOptionsNotRound);
        $('input[name=tingkat_capaian_total_k]').autoNumeric(numOptionsNotRound);

        $('form#evaluasirenja').validate({
            rules: {
                tingkat_capaian_rp : "required",
                realisasi_kinerja_rp : "required",
                tingkat_capaian_total_rp : "required",
                tingkat_capaian_k : "required",
                realisasi_kinerja_k : "required",
                tingkat_capaian_total_k : "required",
            }
        });

        $("#simpan").click(function(){
            var valid = $("form#evaluasirenja").valid();
            if (valid) {
                $('input[name=tingkat_capaian_rp]').val($('input[name=tingkat_capaian_rp]').autoNumeric('get'));
                $('input[name=realisasi_kinerja_rp]').val($('input[name=realisasi_kinerja_rp]').autoNumeric('get'));
                $('input[name=tingkat_capaian_total_rp]').val($('input[name=tingkat_capaian_total_rp]').autoNumeric('get'));
                $('input[name=tingkat_capaian_k]').val($('input[name=tingkat_capaian_k]').autoNumeric('get'));
                $('input[name=realisasi_kinerja_k]').val($('input[name=realisasi_kinerja_k]').autoNumeric('get'));
                $('input[name=tingkat_capaian_total_k]').val($('input[name=tingkat_capaian_total_k]').autoNumeric('get'));

                $.blockUI({
                    css: window._css,
                    overlayCSS: window._ovcss
                });

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('evaluasi_renja/save') ?>",
                    data: $("form#evaluasirenja").serialize(),
                    dataType: "json",
                    success: function(msg){
                        if (msg.success==1) {
                            $.blockUI({
                                message: msg.msg,
                                timeout: 2000,
                                css: window._css,
                                overlayCSS: window._ovcss
                            });
                            $.facebox.close();
                            reload_table();
                        };
                    }
                });
            }
        });
    });
</script>
