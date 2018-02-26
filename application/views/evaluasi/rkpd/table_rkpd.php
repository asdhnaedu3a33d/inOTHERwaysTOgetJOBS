<font <?= $css_header_tw ?>>TRIWULAN : <?= $this->triwulan[$triwulan]["romawi"] ?></font>
<table <?= $css_table ?>>
      <tr>
          <th height="80px" width="10px" rowspan="3" class="no-sort">No</th>
          <th width="10px" colspan="4" rowspan="3">KODE</th>
          <th width="50px" rowspan="3">Urusan/Bidang Urusan Pemerintahan Daerah dan Program / Kegiatan</th>
          <th width="50px" rowspan="3">Indikator Kinerja Program (Outcome) / Kegiatan(Output)</th>
          <th colspan="2" rowspan="2">Target Renstra SKPD Pada Tahun <?= $tahun_terakhir ?></th>
          <th colspan="2" rowspan="2">Realisasi Capaian Kinerja Renstra SKPD s./d. Renja SKPD Tahun Lalu (<?= $tahun_sebelum ?>)</th>
          <th colspan="2" rowspan="2">Target Kinerja & Anggaran Renja SKPD Tahun Berjalan Yang Dievaluasi <?= $tahun ?></th>
          <th colspan="8">Realisasi Kinerja Pada Triwulan</th>
          <th colspan="2" rowspan="2">Realisasi Capaian Kinerja dan Anggaran Renja KSPD Yang Dievaluasi (<?= $tahun ?>)</th>
          <th colspan="2" rowspan="2">Tingkat Capaian Kinerja & Anggaran Renja SKPD Yang Dievaluasi (<?= $tahun ?>)</th>
          <th colspan="2" rowspan="2">Realisasi Kinerja & Anggaran Renstra SKPD s/d Tahun Berjalan (<?= $tahun ?>)</th>
          <th colspan="2" rowspan="2">Tingkat Capaian Kinerja & Realisasi Anggaran Renstra SKPD s/d Tahun <?= $tahun ?> (%)</th>
          <th width="10px" rowspan="3">Unit SKPD Penanggung Jawab</th>
          <th width="10px" rowspan="2" colspan="3">Ket</th>
      </tr>
      <tr>
          <th colspan="2">I</th>
          <th colspan="2">II</th>
          <th colspan="2">III</th>
          <th colspan="2">IV </th>
      </tr>
      <tr>
          <th>K</th>
          <th>Rp</th>
          <th>K</th>
          <th>Rp</th>
          <th>K</th>
          <th>Rp</th>
          <th>K</th>
          <th>Rp</th>
          <th>K</th>
          <th>Rp</th>
          <th>K</th>
          <th>Rp</th>
          <th>K</th>
          <th>Rp</th>
          <th>K</th>
          <th>Rp</th>
          <th>K</th>
          <th>Rp</th>
          <th>K</th>
          <th>Rp</th>
          <th>K</th>
          <th>Rp</th>
          <th>5t</th>
          <th>1t</th>
          <th>R</th>
      </tr>
<?php
$no=0;
$tot_tingkat_capaian_k = 0;
$tot_tingkat_capaian_rp = 0;
$tot_tingkat_capaian_total_k = 0;
$tot_tingkat_capaian_total_rp = 0;
$tot_count_k = 0;
$tot_count_rp = 0;
foreach ($evaluasi_rkpd['kode_urusan'] as $row_urusan) {
?>
  <tr>
    <td align="center"></td>
    <td><?= $row_urusan->kd_urusan ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td><?= $row_urusan->urusan ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
<?php
  if (!empty($evaluasi_rkpd['kode_bidang'][$row_urusan->kd_urusan])) {
    foreach ($evaluasi_rkpd['kode_bidang'][$row_urusan->kd_urusan] as $row_bidang) {
      $var_tingkat_capaian_k[1] = 0;
      $var_tingkat_capaian_rp[1] = 0;
      $var_tingkat_capaian_total_k[1] = 0;
      $var_tingkat_capaian_total_rp[1] = 0;
      $count_k[1] = 0;
      $count_rp[1] = 0;

      $var_tingkat_capaian_k[2] = 0;
      $var_tingkat_capaian_rp[2] = 0;
      $var_tingkat_capaian_total_k[2] = 0;
      $var_tingkat_capaian_total_rp[2] = 0;
      $count_k[2] = 0;
      $count_rp[2] = 0;
    ?>
      <tr>
        <td align="center"></td>
        <td><?= $row_bidang->kd_urusan ?></td>
        <td><?= $row_bidang->kd_bidang ?></td>
        <td></td>
        <td></td>
        <td><?= $row_bidang->bidang ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    <?php

      $skpd_result = $this->m_evaluasi_rkpd->get_rkpd_skpd($tahun, $triwulan, $row_urusan->kd_urusan, $row_bidang->kd_bidang);
      foreach ($skpd_result as $row_skpd) {
      ?>
        <tr>
          <td align="center"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><?= $row_skpd->nama_skpd ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      <?php
        $prog_keg_result = $this->m_evaluasi_rkpd->get_rkpd_prog_keg($tahun, $triwulan, $row_urusan->kd_urusan, $row_bidang->kd_bidang, $row_skpd->id_skpd);
        foreach ($prog_keg_result['evaluasi_prog_keg'] as $key => $row) {
          if (!empty($prog_keg_result['evaluasi'][$row->id])) {
            $row_indikator = $prog_keg_result['evaluasi'][$row->id][0];
            $no++;

            $var_tingkat_capaian_k[$row->is_prog_or_keg] += $row_indikator->tingkat_capaian_k;
            $var_tingkat_capaian_rp[$row->is_prog_or_keg] += $row->tingkat_capaian_rp;
            $var_tingkat_capaian_total_k[$row->is_prog_or_keg] += $row_indikator->tingkat_capaian_total_k;
            $var_tingkat_capaian_total_rp[$row->is_prog_or_keg] += $row->tingkat_capaian_total_rp;
            $count_k[$row->is_prog_or_keg]++;
            $count_rp[$row->is_prog_or_keg]++;
          ?>
            <tr>
              <td align="center" rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>"><?= $no ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>"><?= $row->kd_urusan ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>"><?= $row->kd_bidang ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>"><?= $row->kd_program ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>"><?= $row->kd_kegiatan ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>"><?= $row->nama_prog_or_keg ?></td>
              <td><?= $row_indikator->indikator ?></td>
              <td align="center"><?= $row_indikator->target_akhir_renstra_k." ".$row_indikator->satuan ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>" align="right"><?= FORMATTING::currency($row->target_akhir_renstra_rp) ?></td>
              <td align="center"><?= $row_indikator->realisasi_kinerja_sebelum_k ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>" align="right"><?= FORMATTING::currency($row->realisasi_kinerja_sebelum_rp) ?></td>
              <td align="center"><?= $row_indikator->target_anggaran_berjalan_k ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>" align="right"><?= FORMATTING::currency($row->target_anggaran_berjalan_rp) ?></td>
          <?php
          for ($i_tw=1; $i_tw <= 4; $i_tw++) {
              if (!empty($prog_keg_result['realisasi_evaluasi'][$row_indikator->id][$i_tw])) {
          ?>
              <td align="right"><?= $prog_keg_result['realisasi_evaluasi'][$row_indikator->id][$i_tw]->realisasi_k ?></td>
          <?php
              }else{
          ?>
              <td></td>
          <?php
              }
              if (!empty($prog_keg_result['realisasi_evaluasi_prog_keg'][$row->id][$i_tw])) {
          ?>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>" align="right"><?= FORMATTING::currency($prog_keg_result['realisasi_evaluasi_prog_keg'][$row->id][$i_tw]->realisasi_rp) ?></td>
          <?php
              }else{
          ?>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>"></td>
          <?php
              }
          }
          ?>
              <td align="center"><?= $row_indikator->realisasi_kinerja_berjalan_k ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>" align="right"><?= FORMATTING::currency($row->realisasi_kinerja_berjalan_rp) ?></td>
              <td align="center"><?= $row_indikator->tingkat_capaian_k ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>" align="right"><?= FORMATTING::currency($row->tingkat_capaian_rp) ?></td>
              <td align="center"><?= $row_indikator->realisasi_kinerja_k ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>" align="right"><?= FORMATTING::currency($row->realisasi_kinerja_rp) ?></td>
              <td align="center"><?= $row_indikator->tingkat_capaian_total_k ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>" align="center"><?= $row->tingkat_capaian_total_rp ?></td>
              <td rowspan="<?= $prog_keg_result['jumlah_evaluasi'][$row->id] ?>" align="left"><?= $row->penanggung_jawab ?></td>
              <td align="center"><?= $row_indikator->status_5t ?></td>
              <td align="center"><?= $row_indikator->status_1t ?></td>
              <td align="center"><?= $row_indikator->status_r ?></td>
            </tr>
          <?php
            if ($prog_keg_result['jumlah_evaluasi'][$row->id] > 1) {
              for ($i=1; $i < $prog_keg_result['jumlah_evaluasi'][$row->id]; $i++) {
                $row_indikator = $prog_keg_result['evaluasi'][$row->id][$i];

                $var_tingkat_capaian_k[$row->is_prog_or_keg] += $row_indikator->tingkat_capaian_k;
                $var_tingkat_capaian_total_k[$row->is_prog_or_keg] += $row_indikator->tingkat_capaian_total_k;
                $count_k[$row->is_prog_or_keg]++;
            ?>
              <tr>
                <td><?= $row_indikator->indikator ?></td>
                <td align="center"><?= $row_indikator->target_akhir_renstra_k." ".$row_indikator->satuan ?></td>
                <td align="center"><?= $row_indikator->realisasi_kinerja_sebelum_k ?></td>
                <td align="center"><?= $row_indikator->target_anggaran_berjalan_k ?></td>
            <?php
                for ($i_tw=1; $i_tw <= 4; $i_tw++) {
                  if (!empty($prog_keg_result['realisasi_evaluasi'][$row_indikator->id][$i_tw])) {
            ?>
                  <td align="right"><?= $prog_keg_result['realisasi_evaluasi'][$row_indikator->id][$i_tw]->realisasi_k ?></td>
            <?php
                  }else{
            ?>
                  <td></td>
            <?php
                  }
                }
            ?>
                <td align="center"><?= $row_indikator->realisasi_kinerja_berjalan_k ?></td>
                <td align="center"><?= $row_indikator->tingkat_capaian_k ?></td>
                <td align="center"><?= $row_indikator->realisasi_kinerja_k ?></td>
                <td align="center"><?= $row_indikator->tingkat_capaian_total_k ?></td>
                <td align="center"><?= $row_indikator->status_5t ?></td>
                <td align="center"><?= $row_indikator->status_1t ?></td>
                <td align="center"><?= $row_indikator->status_r ?></td>
              </tr>
            <?php
              }
            }
          }

          if (empty($prog_keg_result['evaluasi_prog_keg'][$key+1]) || (!empty($prog_keg_result['evaluasi_prog_keg'][$key+1]) && $prog_keg_result['evaluasi_prog_keg'][$key+1]->is_prog_or_keg==1)) {
        ?>
              <tr>
                <td align="right" colspan="23">Rata-rata Capaian Kinerja (%)</td>
                <td align="center"><?= $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_k[2], $count_k[2]) ?></td>
                <td align="center"><?= $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_rp[2], $count_rp[2]) ?></td>
                <td></td>
                <td></td>
                <td align="center"><?= $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_total_k[2], $count_k[2]) ?></td>
                <td align="center"><?= $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_total_rp[2], $count_rp[2]) ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td align="right" colspan="23">Predikat Kinerja</td>
                <td align="center"><?= $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_k[2], $count_k[2]) ?></td>
                <td align="center"><?= $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_rp[2], $count_rp[2]) ?></td>
                <td></td>
                <td></td>
                <td align="center"><?= $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_total_k[2], $count_k[2]) ?></td>
                <td align="center"><?= $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_total_rp[2], $count_rp[2]) ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
        <?php
            $var_tingkat_capaian_k[2] = 0;
            $var_tingkat_capaian_rp[2] = 0;
            $var_tingkat_capaian_total_k[2] = 0;
            $var_tingkat_capaian_total_rp[2] = 0;
            $count_k[2] = 0;
            $count_rp[2] = 0;
          }
        }
      }
        ?>
              <tr>
                <td align="right" colspan="23">Total Rata-rata Capaian Kinerja dan Anggaran Dari Seluruh Program Dalam Bidang Urusan (%)</td>
                <td align="center"><?= $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_k[1], $count_k[1]) ?></td>
                <td align="center"><?= $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_rp[1], $count_rp[1]) ?></td>
                <td></td>
                <td></td>
                <td align="center"><?= $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_total_k[1], $count_k[1]) ?></td>
                <td align="center"><?= $this->m_evaluasi_renja->hitung_capaian_lap($var_tingkat_capaian_total_rp[1], $count_rp[1]) ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td align="right" colspan="23">Predikat Kinerja Dari Seluruh Program Dalam Bidang Urusan</td>
                <td align="center"><?= $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_k[1], $count_k[1]) ?></td>
                <td align="center"><?= $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_rp[1], $count_rp[1]) ?></td>
                <td></td>
                <td></td>
                <td align="center"><?= $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_total_k[1], $count_k[1]) ?></td>
                <td align="center"><?= $this->m_evaluasi_renja->predikat_capaian_lap($var_tingkat_capaian_total_rp[1], $count_rp[1]) ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
        <?php
        $tot_tingkat_capaian_k += $var_tingkat_capaian_k[1];
        $tot_tingkat_capaian_rp += $var_tingkat_capaian_rp[1];
        $tot_tingkat_capaian_total_k += $var_tingkat_capaian_total_k[1];
        $tot_tingkat_capaian_total_rp += $var_tingkat_capaian_total_rp[1];
        $tot_count_k += $count_k[1];
        $tot_count_rp += $count_rp[1];
    }
  }
}
?>
                <tr>
                  <th align="right" colspan="23">Total Rata-rata Capaian Kinerja dan Anggaran Dari Seluruh Program (%)</th>
                  <th><?= $this->m_evaluasi_renja->hitung_capaian_lap($tot_tingkat_capaian_k, $tot_count_k) ?></th>
                  <th><?= $this->m_evaluasi_renja->hitung_capaian_lap($tot_tingkat_capaian_rp, $tot_count_rp) ?></th>
                  <th></th>
                  <th></th>
                  <th><?= $this->m_evaluasi_renja->hitung_capaian_lap($tot_tingkat_capaian_total_k, $tot_count_k) ?></th>
                  <th><?= $this->m_evaluasi_renja->hitung_capaian_lap($tot_tingkat_capaian_total_rp, $tot_count_rp) ?></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
                <tr>
                  <th align="right" colspan="23">Predikat Kinerja Dari Seluruh Program</th>
                  <th><?= $this->m_evaluasi_renja->predikat_capaian_lap($tot_tingkat_capaian_k, $tot_count_k) ?></th>
                  <th><?= $this->m_evaluasi_renja->predikat_capaian_lap($tot_tingkat_capaian_rp, $tot_count_rp) ?></th>
                  <th></th>
                  <th></th>
                  <th><?= $this->m_evaluasi_renja->predikat_capaian_lap($tot_tingkat_capaian_total_k, $tot_count_k) ?></th>
                  <th><?= $this->m_evaluasi_renja->predikat_capaian_lap($tot_tingkat_capaian_total_rp, $tot_count_rp) ?></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
</table>
