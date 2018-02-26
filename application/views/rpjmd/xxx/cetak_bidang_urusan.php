<?php
	$i=0;
	foreach ($misi as $row) {
		$tujuan = $this->m_rpjmd_trx->get_tujuan_rpjmd_4_cetak_final($row->id);
		$i++;
?>
<div class="new_page">
	<font class="title">
<?php
	echo "Misi ".$i.". : ".$row->misi;
?>
	</font>
	<table <?php echo $class_table; ?> style="width: 99%">
		<thead>
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Tujuan</th>
				<th rowspan="2">Sasaran</th>
				<th rowspan="2">Strategi</th>
				<th rowspan="2">Kebijakan</th>
				<th rowspan="2">Indikator Kerja (Outcome)</th>
				<th colspan="2">Capaian Kinerja</th>
				<th rowspan="2">Program Pembangunan Daerah</th>
				<th rowspan="2">Bidang Urusan</th>
				<th rowspan="2">SKPD Penanggungjawab</th>
			</tr>
			<tr>
				<th>Kondisi Awal</th>
				<th>Kondisi Akhir</th>
			</tr>
		</thead>
		<tbody>
<?php
		$j=0;
		$tujuan_print = TRUE;
		foreach ($tujuan as $row1) {
			$sasaran = $this->m_rpjmd_trx->get_sasaran_rpjmd_4_cetak_final($row1->id);
			$j++;
			$sasaran_print = TRUE;
			foreach ($sasaran as $row2) {
				$indikator = $this->m_rpjmd_trx->get_indikator_rpjmd_4_cetak_final($row2->id);
				$kebijakan = $this->m_rpjmd_trx->get_kebijakan_sasaran($row2->id);
				$indikator_print=TRUE;
				foreach ($indikator as $row3) {
					$program = $this->m_rpjmd_trx->get_program_rpjmd_4_cetak_final($row3->id_sasaran, $row3->id);
					foreach ($program as $row4) {
?>
			<tr>
<?php
					if ($tujuan_print) {
?>
				<td rowspan="<?php echo $row1->span; ?>"><?php echo $j; ?></td>
				<td rowspan="<?php echo $row1->span; ?>"><?php echo $row1->tujuan; ?></td>
<?php
					}

					if ($sasaran_print) {
?>
				<td rowspan="<?php echo $row2->span; ?>"><?php echo $row2->sasaran; ?></td>
				<td rowspan="<?php echo $row2->span; ?>"><?php echo $row2->strategi; ?></td>
				<td rowspan="<?php echo $row2->span; ?>">
<?php
				$index_kebijakan = 0;
				foreach ($kebijakan as $row_kebijakan) {
					$index_kebijakan++;
					echo $index_kebijakan.". ".$row_kebijakan->kebijakan."<BR>";
				}
?>
				</td>
<?php
					}

					if ($indikator_print) {
?>
				<td rowspan="<?php echo $row3->span; ?>"><?php echo $row3->indikator; ?></td>
				<td align="center" rowspan="<?php echo $row3->span; ?>"><?php echo $row3->kondisi_awal." ".$row3->nama_value; ?></td>
				<td align="center" rowspan="<?php echo $row3->span; ?>"><?php echo $row3->kondisi_akhir." ".$row3->nama_value; ?></td>
<?php
					}
?>
				<td><?php echo $row4->nama_prog_or_keg; ?></td>
				<td><?php echo $row4->nm_bidang; ?></td>
				<td><?php echo $row4->nama_skpd; ?></td>
			</tr>
<?php

					$tujuan_print=FALSE;
					$sasaran_print=FALSE;
					$indikator_print=FALSE;
					}
					$indikator_print=TRUE;
				}
				$sasaran_print=TRUE;
			}
			$tujuan_print=TRUE;
		}
?>
		</tbody>
	</table>
</div>
<?php
	}

	$i=0;
	foreach ($bidang_urusans as $row) {
		$i++;
		$temp['bidang_urusan'] = $this->m_rpjmd_trx->get_bidang_urusan_4_cetak_final($row->Kd_Urusan, $row->Kd_Bidang);
		$table = $this->load->view("rpjmd/cetak/cetak", $temp, TRUE);
?>
<div class="new_page scroll form-group">
	<font class="title">
<?php
	echo $i.". ".$row->Nm_Urusan." ".$row->Nm_Bidang;
?>
	</font>
	<table <?php echo $class_table; ?> style="width: 99%">
<?php
	echo $table;
?>
	</table>
</div>
<?php
	}
?>
