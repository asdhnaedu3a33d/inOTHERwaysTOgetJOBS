<?php $pagu = 0; $sisa_pagu = 0; 
?>
<table>
  <tr>
    <td width="10px"><b>Indikator</b></td>
    <td>
      <table>
        <?php if (!empty($indikator_program_rpjmd->result())){
			$pagu = $program_rpjmd->pagu_rpjmd;
			$sisa_pagu = $pagu - $pagu_sisa->sisa;
          foreach ($indikator_program_rpjmd->result() as $row) {
          ?>
          <tr>
            <td colspan="7"><?php echo $row->indikator ?></td>
          </tr>
          <tr>
            <td><b>Satuan</b></td>
            <td colspan="6"><?php echo $row->satuan_target ?></td>
          </tr>
          <tr>
            <td><b>Kategori Indikator</b></td>
            <td colspan="3"><?php echo $row->status_nya ?></td>
            <td colspan="3"><?php echo $row->kategori_nya ?></td>
          </tr>
          <tr>
            <th>Kondisi Awal</th>
            <th>Target 1</th>
            <th>Target 2</th>
            <th>Target 3</th>
            <th>Target 4</th>
            <th>Target 5</th>
            <th>Kondisi Akhir</th>
          </tr>
          <tr>
            <td><?php echo $row->kondisi_awal ?></td>
            <td><?php echo $row->target_1 ?></td>
            <td><?php echo $row->target_2 ?></td>
            <td><?php echo $row->target_3 ?></td>
            <td><?php echo $row->target_4 ?></td>
            <td><?php echo $row->target_5 ?></td>
            <td><?php echo $row->kondisi_akhir ?></td>
          </tr>
          <tr>
            <td colspan="7"  style="height:1px;"></td>
          </tr>
        <?php }}else{echo "Tidak ada data.";} ?>
      </table>
    </td>
  </tr>
</table>Pagu Indikatif : Rp. <?php echo Formatting::currency($pagu); ?> <BR> Sisa Pagu Indikatif : Rp. <?php echo Formatting::currency($sisa_pagu); ?>
