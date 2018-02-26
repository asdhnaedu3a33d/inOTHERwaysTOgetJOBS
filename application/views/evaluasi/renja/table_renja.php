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
foreach ($renja['renja'] as $row) {
    ############ Indikator Renja ############
    if (!empty($renja['indikator'][$row->id])) {
      ############ Indikator Pertama ############
    	$row_indikator = $renja['indikator'][$row->id][0];
?>
    	<tr>
        	<td rowspan="<?= $renja['jumlah_indikator'][$row->id] ?>"><?= $row->kd_urusan ?></td>
            <td rowspan="<?= $renja['jumlah_indikator'][$row->id] ?>"><?= $row->kd_bidang ?></td>
            <td rowspan="<?= $renja['jumlah_indikator'][$row->id] ?>"><?= $row->kd_program ?></td>
            <td rowspan="<?= $renja['jumlah_indikator'][$row->id] ?>"><?= $row->kd_kegiatan ?></td>
            <td rowspan="<?= $renja['jumlah_indikator'][$row->id] ?>"><?= $row->nama_prog_or_keg ?></td>
            <td>
            	<?= @$row_indikator->indikator ?> <i>(<?= @$row_indikator->target." ".@$row_indikator->satuan ?>)</i> <?= (!empty($row_indikator->tipe_ind==2))?'**':'' ?>
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
            	if (empty($row_indikator->$tw_obj) && $periode[$i_tw]->active==1) {
            ?>
            	<a href="javascript:void(0)" class="icon-pencil <?= (!empty($row_indikator->tipe_ind==2))?'edit-evaluasi-cik':'edit-evaluasi' ?>" idr="<?= $row_indikator->id_prog_keg ?>" idi="<?= @$row_indikator->id ?>" tw="<?= $i_tw ?>" title="Evaluasi Triwulan <?= $i_tw ?>"/>
            <?php
            	}elseif(!empty($row_indikator->$tw_obj)) {
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
    ############ Bila Terdapat Indikator Lebih Dari 1 ############
		if ($renja['jumlah_indikator'][$row->id] > 1) {
			for ($i=1; $i < $renja['jumlah_indikator'][$row->id]; $i++) {
				$row_indikator = $renja['indikator'][$row->id][$i];
	?>
		<tr>
    		<td>
    			<?= @$row_indikator->indikator ?> <i>(<?= @$row_indikator->target." ".@$row_indikator->satuan ?>)</i> <?= (!empty($row_indikator->tipe_ind==2))?'**':'' ?>
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
            	if (empty($row_indikator->$tw_obj) && $periode[$i_tw]->active==1) {
            ?>
            	<a href="javascript:void(0)" class="icon-pencil <?= (!empty($row_indikator->tipe_ind==2))?'edit-evaluasi-cik':'edit-evaluasi' ?>" idr="<?= $row_indikator->id_prog_keg ?>" idi="<?= @$row_indikator->id ?>" tw="<?= $i_tw ?>" title="Evaluasi Triwulan <?= $i_tw ?>"/>
            <?php
            	}elseif(!empty($row_indikator->$tw_obj)) {
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

  ############ Indikator CIK tp tidak ada di Renja ############
  if ($row->is_prog_or_keg == 1 && !empty($cik['keg_row'][$row->kd_urusan][$row->kd_bidang][$row->kd_program])) {
    $keg_cik = $cik['keg_row'][$row->kd_urusan][$row->kd_bidang][$row->kd_program];
    foreach ($keg_cik as $row_keg_cik) {
      $number = $row_keg_cik['number'];
      $row = $row_keg_cik['value'];
      if (!empty($cik['indikator'][$row->id])) {
          ############ Indikator Pertama ############
          $row_indikator = $cik['indikator'][$row->id][0];
    ?>
          <tr>
                <td rowspan="<?= $cik['jumlah_indikator'][$row->id] ?>"><?= $row->kd_urusan ?></td>
                <td rowspan="<?= $cik['jumlah_indikator'][$row->id] ?>"><?= $row->kd_bidang ?></td>
                <td rowspan="<?= $cik['jumlah_indikator'][$row->id] ?>"><?= $row->kd_program ?></td>
                <td rowspan="<?= $cik['jumlah_indikator'][$row->id] ?>"><?= $row->kd_kegiatan ?></td>
                <td rowspan="<?= $cik['jumlah_indikator'][$row->id] ?>"><?= $row->nama_prog_or_keg ?> **</td>
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
                  if (empty($row_indikator->$tw_obj) && $periode[$i_tw]->active==1) {
                ?>
                  <a href="javascript:void(0)" class="icon-pencil edit-evaluasi-cik" idr="<?= $row->id ?>" idi="<?= @$row_indikator->id ?>" tw="<?= $i_tw ?>" title="Evaluasi Triwulan <?= $i_tw ?>"/>
                <?php
                  }elseif(!empty($row_indikator->$tw_obj)) {
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
        ############ Bila Terdapat Indikator CIK Lebih Dari 1 ############
        if ($cik['jumlah_indikator'][$row->id] > 1) {
          for ($i=1; $i < $cik['jumlah_indikator'][$row->id]; $i++) {
            $row_indikator = $cik['indikator'][$row->id][$i];
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
                  if (empty($row_indikator->$tw_obj) && $periode[$i_tw]->active==1) {
                ?>
                  <a href="javascript:void(0)" class="icon-pencil edit-evaluasi-cik" idr="<?= $row->id ?>" idi="<?= @$row_indikator->id ?>" tw="<?= $i_tw ?>" title="Evaluasi Triwulan <?= $i_tw ?>"/>
                <?php
                  }elseif(!empty($row_indikator->$tw_obj)) {
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
      unset($cik['cik'][$number]);
    }
  }
}

############ CIK PROGRAM DAN KEGIATAN ############
foreach ($cik['cik'] as $row) {
    if (!empty($cik['indikator'][$row->id])) {
    	$row_indikator = $cik['indikator'][$row->id][0];
?>
    	<tr>
        	  <td rowspan="<?= $cik['jumlah_indikator'][$row->id] ?>"><?= $row->kd_urusan ?></td>
            <td rowspan="<?= $cik['jumlah_indikator'][$row->id] ?>"><?= $row->kd_bidang ?></td>
            <td rowspan="<?= $cik['jumlah_indikator'][$row->id] ?>"><?= $row->kd_program ?></td>
            <td rowspan="<?= $cik['jumlah_indikator'][$row->id] ?>"><?= $row->kd_kegiatan ?></td>
            <td rowspan="<?= $cik['jumlah_indikator'][$row->id] ?>"><?= $row->nama_prog_or_keg ?> **</td>
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
            	if (empty($row_indikator->$tw_obj) && $periode[$i_tw]->active==1) {
            ?>
            	<a href="javascript:void(0)" class="icon-pencil edit-evaluasi-cik" idr="<?= $row->id ?>" idi="<?= @$row_indikator->id ?>" tw="<?= $i_tw ?>" title="Evaluasi Triwulan <?= $i_tw ?>"/>
            <?php
            	}elseif(!empty($row_indikator->$tw_obj)) {
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
		if ($cik['jumlah_indikator'][$row->id] > 1) {
			for ($i=1; $i < $cik['jumlah_indikator'][$row->id]; $i++) {
				$row_indikator = $cik['indikator'][$row->id][$i];
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
            	if (empty($row_indikator->$tw_obj) && $periode[$i_tw]->active==1) {
            ?>
            	<a href="javascript:void(0)" class="icon-pencil edit-evaluasi-cik" idr="<?= $row->id ?>" idi="<?= @$row_indikator->id ?>" tw="<?= $i_tw ?>" title="Evaluasi Triwulan <?= $i_tw ?>"/>
            <?php
            	}elseif(!empty($row_indikator->$tw_obj)) {
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
?>
    </table>
