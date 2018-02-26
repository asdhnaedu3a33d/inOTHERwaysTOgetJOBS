    <font style="color: #1C4675; font-size: 13px; font-weight: bold; text-transform: uppercase;">TRIWULAN : <?= $this->triwulan[$triwulan]["romawi"] ?></font>
    <table class="table-common tablesorter" style="width:99%" >
        <tr>
            <th rowspan="3" class="no-sort">No</th>
            <th colspan="4" rowspan="3">KODE</th>
            <th rowspan="3">Urusan/Bidang Urusan Pemerintahan Daerah dan Program / Kegiatan</th>
            <th rowspan="3">Indikator Kinerja Program (Outcome) / Kegiatan(Output)</th>
            <th colspan="2" rowspan="2">Target Renstra SKPD Pada Tahun <?= $tahun_terakhir ?></th>
            <th colspan="2" rowspan="2">Realisasi Capaian Kinerja Renstra SKPD s./d. Renja SKPD Tahun Lalu (<?= $tahun_sebelum ?>)</th>
            <th colspan="2" rowspan="2">Target Kinerja & Anggaran Renja SKPD Tahun Berjalan Yang Dievaluasi <?= $tahun ?></th>
            <th colspan="8">Realisasi Kinerja Pada Triwulan</th>
            <th colspan="2" rowspan="2">Realisasi Capaian Kinerja dan Anggaran Renja KSPD Yang Dievaluasi (<?= $tahun ?>)</th>
            <th colspan="2" rowspan="2">Tingkat Capaian Kinerja & Anggaran Renja SKPD Yang Dievaluasi (<?= $tahun ?>)</th>
            <th colspan="2" rowspan="2">Realisasi Kinerja & Anggaran Renstra SKPD s/d Tahun Berjalan (<?= $tahun ?>)</th>
            <th colspan="2" rowspan="2">Tingkat Capaian Kinerja & Realisasi Anggaran Renstra SKPD s/d Tahun <?= $tahun ?> (%)</th>
            <th rowspan="3"><i style="margin:5px" class="icon2-script_gear" title="Aksi"></i></th>
        </tr>
        <tr>
            <th colspan="2"> I </th>
            <th colspan="2"> II </th>
            <th colspan="2"> III </th>
            <th colspan="2"> IV </th>
        </tr>
        <tr>
            <th> K </th>
            <th> Rp </th>
            <th> K </th>
            <th> Rp </th>
            <th> K </th>
            <th> Rp </th>
            <th> K </th>
            <th> Rp </th>
            <th> K </th>
            <th> Rp </th>
            <th> K </th>
            <th> Rp </th>
            <th> K </th>
            <th> Rp </th>
            <th> K </th>
            <th> Rp </th>
            <th> K </th>
            <th> Rp </th>
            <th> K </th>
            <th> Rp </th>
            <th> K </th>
            <th> Rp </th>
        </tr>
<?php
$no=0;
foreach ($evaluasi_renja['evaluasi_renja_prog_keg'] as $row) {
    if (!empty($evaluasi_renja['evaluasi_renja'][$row->id])) {
    	$row_indikator = $evaluasi_renja['evaluasi_renja'][$row->id][0];
      $no++;
?>
    	<tr>
        <td align="center" rowspan="<?= $evaluasi_renja['jumlah_evaluasi_renja'][$row->id] ?>"><?= $no ?></td>
    	  <td rowspan="<?= $evaluasi_renja['jumlah_evaluasi_renja'][$row->id] ?>"><?= $row->kd_urusan ?></td>
        <td rowspan="<?= $evaluasi_renja['jumlah_evaluasi_renja'][$row->id] ?>"><?= $row->kd_bidang ?></td>
        <td rowspan="<?= $evaluasi_renja['jumlah_evaluasi_renja'][$row->id] ?>"><?= $row->kd_program ?></td>
        <td rowspan="<?= $evaluasi_renja['jumlah_evaluasi_renja'][$row->id] ?>"><?= $row->kd_kegiatan ?></td>
        <td rowspan="<?= $evaluasi_renja['jumlah_evaluasi_renja'][$row->id] ?>"><?= $row->nama_prog_or_keg ?></td>
        <td><?= $row_indikator->indikator ?></td>
        <td align="center"><?= $row_indikator->target_akhir_renstra_k." ".$row_indikator->satuan ?></td>
        <td align="right"><?= FORMATTING::currency($row->target_akhir_renstra_rp) ?></td>
        <td align="center"><?= $row_indikator->realisasi_kinerja_sebelum_k ?></td>
        <td align="right"><?= FORMATTING::currency($row->realisasi_kinerja_sebelum_rp) ?></td>
        <td align="center"><?= $row_indikator->target_anggaran_berjalan_k ?></td>
        <td align="right"><?= FORMATTING::currency($row->target_anggaran_berjalan_rp) ?></td>
<?php
    for ($i_tw=1; $i_tw <= 4; $i_tw++) {
        if (!empty($evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][$i_tw])) {
?>
        <td align="right"><?= $evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][$i_tw]->realisasi_k ?></td>
<?php
        }else{
?>
        <td></td>
<?php
        }
        if (!empty($evaluasi_renja['realisasi_evaluasi_renja_prog_keg'][$row->id][$i_tw])) {
?>
        <td align="right"><?= FORMATTING::currency($evaluasi_renja['realisasi_evaluasi_renja_prog_keg'][$row->id][$i_tw]->realisasi_rp) ?></td>
<?php
        }else{
?>
        <td></td>
<?php
        }
    }
?>
        <td align="center"><?= $row_indikator->realisasi_kinerja_berjalan_k ?></td>
        <td align="right"><?= FORMATTING::currency($row->realisasi_kinerja_berjalan_rp) ?></td>
        <td align="center"><?= $row_indikator->tingkat_capaian_k ?></td>
        <td align="right"><?= FORMATTING::currency($row->tingkat_capaian_rp) ?></td>
        <td align="center"><?= $row_indikator->realisasi_kinerja_k ?></td>
        <td align="right"><?= FORMATTING::currency($row->realisasi_kinerja_rp) ?></td>
        <td align="center"><?= $row_indikator->tingkat_capaian_total_k ?></td>
        <td align="right"><?= $row->tingkat_capaian_total_rp ?></td>
        <td align="center">
        <?php
            if ($row_indikator->status==5) {
        ?>
            <i class="icon2-tick" title="Telah di-verifikasi"></i>
        <?php
            }elseif ($row_indikator->status==4) {
        ?>
            <i class="icon2-exclamation" title="Ditolak & perlu revisi"></i>
        <?php
            }elseif ($row_indikator->status==3) {
        ?>
            <a class="icon-list veri" idev="<?= $row_indikator->id ?>" tw="<?= $row->triwulan ?>" href="javascript:void(0)"></a>
        <?php
            }
        ?>
        </td>
    	</tr>
	<?php
		if ($evaluasi_renja['jumlah_evaluasi_renja'][$row->id] > 1) {
			for ($i=1; $i < $evaluasi_renja['jumlah_evaluasi_renja'][$row->id]; $i++) {
				$row_indikator = $evaluasi_renja['evaluasi_renja'][$row->id][$i];
	?>
		  <tr>
        <td><?= $row_indikator->indikator ?></td>
        <td align="center"><?= $row_indikator->target_akhir_renstra_k." ".$row_indikator->satuan ?></td>
        <td align="right"><?= FORMATTING::currency($row->target_akhir_renstra_rp) ?></td>
        <td align="center"><?= $row_indikator->realisasi_kinerja_sebelum_k ?></td>
        <td align="right"><?= FORMATTING::currency($row->realisasi_kinerja_sebelum_rp) ?></td>
        <td align="center"><?= $row_indikator->target_anggaran_berjalan_k ?></td>
        <td align="right"><?= FORMATTING::currency($row->target_anggaran_berjalan_rp) ?></td>
<?php
    for ($i_tw=1; $i_tw <= 4; $i_tw++) {
        if (!empty($evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][$i_tw])) {
?>
        <td align="right"><?= $evaluasi_renja['realisasi_evaluasi_renja'][$row_indikator->id][$i_tw]->realisasi_k ?></td>
<?php
        }else{
?>
        <td></td>
<?php
        }
        if (!empty($evaluasi_renja['realisasi_evaluasi_renja_prog_keg'][$row->id][$i_tw])) {
?>
        <td align="right"><?= FORMATTING::currency($evaluasi_renja['realisasi_evaluasi_renja_prog_keg'][$row->id][$i_tw]->realisasi_rp) ?></td>
<?php
        }else{
?>
        <td></td>
<?php
        }
    }
?>
        <td align="center"><?= $row_indikator->realisasi_kinerja_berjalan_k ?></td>
        <td align="right"><?= FORMATTING::currency($row->realisasi_kinerja_berjalan_rp) ?></td>
        <td align="center"><?= $row_indikator->tingkat_capaian_k ?></td>
        <td align="right"><?= FORMATTING::currency($row->tingkat_capaian_rp) ?></td>
        <td align="center"><?= $row_indikator->realisasi_kinerja_k ?></td>
        <td align="right"><?= FORMATTING::currency($row->realisasi_kinerja_rp) ?></td>
        <td align="center"><?= $row_indikator->tingkat_capaian_total_k ?></td>
        <td align="right"><?= $row->tingkat_capaian_total_rp ?></td>
        <td align="center">
        <?php
            if ($row_indikator->status==5) {
        ?>
            <i class="icon2-tick" title="Telah di-verifikasi"></i>
        <?php
            }elseif ($row_indikator->status==4) {
        ?>
            <i class="icon2-exclamation" title="Ditolak & perlu revisi"></i>
        <?php
            }elseif ($row_indikator->status==3) {
        ?>
            <a class="icon-list veri" idev="<?= $row_indikator->id ?>" tw="<?= $row->triwulan ?>" href="javascript:void(0)"></a>
        <?php
            }
        ?>
        </td>
      </tr>
<?php
			}
		}
	}
}
?>
    </table>
