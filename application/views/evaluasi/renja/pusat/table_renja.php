  <table class="table-common tablesorter" style="width:99%" >
		<tr>
			<th colspan="4">KODE</th>
			<th>PROGRAM DAN KEGIATAN</th>
			<th>INDIKATOR KINERJA PROGRAM / KEGIATAN</th>
			<th colspan="2">TW1</th>
      <th colspan="2">TW2</th>
      <th colspan="2">TW3</th>
      <th colspan="2">TW4</th>
		</tr>
<?php
foreach ($skpd as $row_skpd) {
  $renja = $this->m_evaluasi_renja->get_renja_all($tahun, $row_skpd->id_skpd);
  ?>
  <tr style="background-color: #FF9933 !important;">
      <td colspan="14"><strong><?= $row_skpd->nama_skpd ?></strong></td>
  </tr>
  <?php
  foreach ($renja['renja'] as $row) {
      if (!empty($renja['indikator'][$row->id])) {
      	$row_indikator = $renja['indikator'][$row->id][0];
  ?>
      	<tr>
          	<td rowspan="<?= $renja['jumlah_indikator'][$row->id] ?>"><?= $row->kd_urusan ?></td>
              <td rowspan="<?= $renja['jumlah_indikator'][$row->id] ?>"><?= $row->kd_bidang ?></td>
              <td rowspan="<?= $renja['jumlah_indikator'][$row->id] ?>"><?= $row->kd_program ?></td>
              <td rowspan="<?= $renja['jumlah_indikator'][$row->id] ?>"><?= $row->kd_kegiatan ?></td>
              <td rowspan="<?= $renja['jumlah_indikator'][$row->id] ?>"><?= $row->nama_prog_or_keg ?></td>
              <td>
              	<?= @$row_indikator->indikator ?> <i>(<?= @$row_indikator->target." ".@$row_indikator->satuan ?>)</i>
              </td>
      <?php
  		for ($i_tw=1; $i_tw <= 4; $i_tw++) {
  			$tw_obj = 'tw_'.$i_tw;
              $status_obj = 'status_'.$i_tw;
              if (!empty($row_indikator->$status_obj)) {
  	?>
              <td align="center">
              <?php
                  switch ($row_indikator->$status_obj) {
                      case 1:
              ?>
                  <i class="icon2-page_add" title="Baru"></i>
              <?php
                          break;

                      case 2:
              ?>
                  <i class="icon2-page_edit" title="Telah diperbaiki / dikoreksi"></i>
              <?php
                          break;

                      case 3:
              ?>
                  <i class="icon2-time" title="Menunggu verifikasi"></i>
              <?php
                          break;

                      case 4:
              ?>
                  <i class="icon2-exclamation" title="Ditolak & perlu revisi"></i>
              <?php
                          break;

                      case 5:
              ?>
                  <i class="icon2-tick" title="Telah di-verifikasi"></i>
              <?php
                          break;
                  }
              ?>
              </td>
      <?php
              }
      ?>
  			<td align="center" colspan="<?= (empty($row_indikator->$status_obj)?2:1) ?>">
              <?php
              	if(!empty($row_indikator->$tw_obj)) {
              ?>
              	<a href="javascript:void(0)" class="icon-search lihat-evaluasi" idev="<?= $row_indikator->$tw_obj ?>" tw="<?= $i_tw ?>" title="Lihat Evaluasi Triwulan <?= $i_tw ?>"/>
              <?php
              	}else{
              ?>
              	<a href="javascript:void(0)" class="icon-minus" title="Evaluasi Belum Tersedia"/>
              <?php
              	}
              ?>
              </td>
  	<?php
  		}
  	?>
      	</tr>
  	<?php
  		if ($renja['jumlah_indikator'][$row->id] > 1) {
  			for ($i=1; $i < $renja['jumlah_indikator'][$row->id]; $i++) {
  				$row_indikator = $renja['indikator'][$row->id][$i];
  	?>
  		<tr>
      		<td>
      			<?= @$row_indikator->indikator ?> <i>(<?= @$row_indikator->target." ".@$row_indikator->satuan ?>)</i>
              </td>
  	<?php
  		for ($i_tw=1; $i_tw <= 4; $i_tw++) {
  			$tw_obj = 'tw_'.$i_tw;
              $status_obj = 'status_'.$i_tw;
              if (!empty($row_indikator->$status_obj)) {
      ?>
              <td align="center">
              <?php
                  switch ($row_indikator->$status_obj) {
                      case 1:
              ?>
                  <i class="icon2-page_add" title="Baru"></i>
              <?php
                          break;

                      case 2:
              ?>
                  <i class="icon2-page_edit" title="Telah diperbaiki / dikoreksi"></i>
              <?php
                          break;

                      case 3:
              ?>
                  <i class="icon2-time" title="Menunggu verifikasi"></i>
              <?php
                          break;

                      case 4:
              ?>
                  <i class="icon2-exclamation" title="Ditolak & perlu revisi"></i>
              <?php
                          break;

                      case 5:
              ?>
                  <i class="icon2-tick" title="Telah di-verifikasi"></i>
              <?php
                          break;
                  }
              ?>
              </td>
      <?php
              }
      ?>
  			<td align="center" colspan="<?= (empty($row_indikator->$status_obj)?2:1) ?>">
              <?php
              	if(!empty($row_indikator->$tw_obj)) {
              ?>
              	<a href="javascript:void(0)" class="icon-search lihat-evaluasi" idev="<?= $row_indikator->$tw_obj ?>" tw="<?= $i_tw ?>" title="Lihat Evaluasi Triwulan <?= $i_tw ?>"/>
              <?php
              	}else{
              ?>
              	<a href="javascript:void(0)" class="icon-minus" title="Periode pada triwulan ini belum aktif"/>
              <?php
              	}
              ?>
              </td>
  	<?php
  		}
  	?>
          </tr>
  	<?php
  			}
  		}
  	}
  }
}
?>
    </table>
