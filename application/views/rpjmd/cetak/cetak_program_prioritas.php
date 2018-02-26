<div align="center">Tabel Indikasi Program Prioritas</div>
<table border="1" style="border-collapse: collapse"  width="100%">
	<thead>
		<tr>
			<th rowspan="3" colspan="3">KODE</th>
			<th rowspan="3">BIDANG URUSAN PEMERINTAH DAERAH</th>
			<th rowspan="3">INDIKATOR KINERJA PROGRAM (OUTCOME)</th>
			<th rowspan="3">KONDISI KINERJA PADA AWAL RPJMD</th>
			<th colspan="10">CAPAIAN KINERJA PROGRAM DAN KERANGKA PENDANAAN</th>
			<th rowspan="2" colspan="2">KONDISI KINERJA PADA AKHIR PERIODE RPJMD</th>
			<th rowspan="3">SKPD PENANGGUNG JAWAB</th>
		</tr>
		<tr>
			<th colspan="2"><?php echo $th_anggaran[0]->tahun_anggaran; ?> </th>
			<th colspan="2"><?php echo $th_anggaran[1]->tahun_anggaran; ?> </th>
			<th colspan="2"><?php echo $th_anggaran[2]->tahun_anggaran; ?> </th>
			<th colspan="2"><?php echo $th_anggaran[3]->tahun_anggaran; ?> </th>
			<th colspan="2"><?php echo $th_anggaran[4]->tahun_anggaran; ?> </th>
		</tr>
		<tr>
			<th>TARGET</th>
			<th>RP.</th>
			<th>TARGET</th>
			<th>RP.</th>
			<th>TARGET</th>
			<th>RP.</th>
			<th>TARGET</th>
			<th>RP.</th>
			<th>TARGET</th>
			<th>RP.</th>
			<th>TARGET</th>
			<th>RP.</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$u_1 = 0;$u_2 = 0;$u_3 = 0;$u_4 = 0;$u_5 = 0;
			foreach ($rpjmd as $row) {
			$u_1 += $row->nom1;
			$u_2 += $row->nom2;
			$u_3 += $row->nom3;
			$u_4 += $row->nom4;
			$u_5 += $row->nom5;
			$bidang = $this->m_rpjmd_trx->get_bidang_urusan($row->kd);
		?>
			<tr>
				<td><b><?php echo $row->kd; ?></b></td>
				<td></td>
				<td></td>
				<td colspan="3"><b style="text-transform: uppercase;"><?php echo $row->nama; ?></b></td>
				<td></td>
				<td align="right"><b><?php echo Formatting::currency($row->nom1); ?></b></td>
				<td></td>
				<td align="right"><b><?php echo Formatting::currency($row->nom2); ?></b></td>
				<td></td>
				<td align="right"><b><?php echo Formatting::currency($row->nom3); ?></b></td>
				<td></td>
				<td align="right"><b><?php echo Formatting::currency($row->nom4); ?></b></td>
				<td></td>
				<td align="right"><b><?php echo Formatting::currency($row->nom5); ?></b></td>
				<td></td>
				<td align="right"><b><?php echo Formatting::currency(floatval($row->nom1)+floatval($row->nom2)+floatval($row->nom3)+floatval($row->nom4)+floatval($row->nom5)); ?></b></td>
				<td></td>
			</tr>
			<?php
				foreach ($bidang as $row2) {
				$skpd = $this->m_rpjmd_trx->get_skpd_bidang_urusan($row->kd, $row2->kd);
			?>
				<tr>
					<td><b><?php echo $row->kd; ?></b></td>
					<td><b><?php echo $row2->kd; ?></b></td>
					<td></td>
					<td colspan="3"><b style="text-transform: uppercase;"><?php echo $row2->nama; ?></b></td>
					<td></td>
					<td align="right"><b><?php echo Formatting::currency($row2->nom1); ?></b></td>
					<td></td>
					<td align="right"><b><?php echo Formatting::currency($row2->nom2); ?></b></td>
					<td></td>
					<td align="right"><b><?php echo Formatting::currency($row2->nom3); ?></b></td>
					<td></td>
					<td align="right"><b><?php echo Formatting::currency($row2->nom4); ?></b></td>
					<td></td>
					<td align="right"><b><?php echo Formatting::currency($row2->nom5); ?></b></td>
					<td></td>
					<td align="right"><b><?php echo Formatting::currency(floatval($row2->nom1)+floatval($row2->nom2)+floatval($row2->nom3)+floatval($row2->nom4)+floatval($row2->nom5)); ?></b></td>
					<td></td>
				</tr>
				<?php
					foreach ($skpd as $row3) {
					$kd_skpd = explode('.', $row3->kode_skpd);
					$program = $this->m_rpjmd_trx->get_program_skpd($row->kd, $row2->kd, $row3->kd);
				?>
				<tr>
					<td><b><?php echo $kd_skpd[0]; ?></b></td>
					<td><b><?php echo $kd_skpd[1]; ?></b></td>
					<td><b><?php echo $kd_skpd[2]; ?></b></td>
					<td colspan="3"><b style="text-transform: uppercase;"><?php echo $row3->nama; ?></b></td>
					<td></td>
					<td align="right"><b><?php echo Formatting::currency($row3->nom1); ?></b></td>
					<td></td>
					<td align="right"><b><?php echo Formatting::currency($row3->nom2); ?></b></td>
					<td></td>
					<td align="right"><b><?php echo Formatting::currency($row3->nom3); ?></b></td>
					<td></td>
					<td align="right"><b><?php echo Formatting::currency($row3->nom4); ?></b></td>
					<td></td>
					<td align="right"><b><?php echo Formatting::currency($row3->nom5); ?></b></td>
					<td></td>
					<td align="right"><b><?php echo Formatting::currency(floatval($row3->nom1)+floatval($row3->nom2)+floatval($row3->nom3)+floatval($row3->nom4)+floatval($row3->nom5)); ?></b></td>
					<td></td>
				</tr>
				<?php
					foreach ($program as $row4) {
						$counter = 1;
						$indikator = $this->m_rpjmd_trx->get_indikator_program_skpd($row4->id);
						$rowindikator = count($indikator);
						foreach ($indikator as $row5) {
				?>
					<?php if ($counter == 1) { ?>
						<tr>
							<td rowspan="<?php echo $rowindikator; ?>"><?php echo $row->kd; ?></td>
							<td rowspan="<?php echo $rowindikator; ?>"><?php echo $row2->kd; ?></td>
							<td rowspan="<?php echo $rowindikator; ?>"><?php echo $row4->kd_program; ?></td>
							<td rowspan="<?php echo $rowindikator; ?>"><?php echo $row4->nama_prog_or_keg; ?></td>
							<td ><?php echo $row5->indikator; ?></td>
							<td align="center"><?php echo $row5->kondisi_awal." ".$row5->satuan_target; ?></td>
							<td align="center"><?php echo $row5->target_1; ?></td>
							<td rowspan="<?php echo $rowindikator; ?>" align="right"><?php echo Formatting::currency($row4->nom1); ?></td>
							<td align="center"><?php echo $row5->target_2; ?></td>
							<td rowspan="<?php echo $rowindikator; ?>" align="right"><?php echo Formatting::currency($row4->nom2); ?></td>
							<td align="center"><?php echo $row5->target_3; ?></td>
							<td rowspan="<?php echo $rowindikator; ?>" align="right"><?php echo Formatting::currency($row4->nom3); ?></td>
							<td align="center"><?php echo $row5->target_4; ?></td>
							<td rowspan="<?php echo $rowindikator; ?>" align="right"><?php echo Formatting::currency($row4->nom4); ?></td>
							<td align="center"><?php echo $row5->target_5; ?></td>
							<td rowspan="<?php echo $rowindikator; ?>" align="right"><?php echo Formatting::currency($row4->nom5); ?></td>
							<td align="center"><?php echo $row5->target_kondisi_akhir; ?></td>
							<td rowspan="<?php echo $rowindikator; ?>" align="right"><?php echo Formatting::currency(floatval($row4->nom1)+floatval($row4->nom2)+floatval($row4->nom3)+floatval($row4->nom4)+floatval($row4->nom5)); ?></td>
							<td rowspan="<?php echo $rowindikator; ?>"><?php echo $row3->nama; ?></td>
						</tr>
					<?php $counter++;}else{ ?>
						<tr>
							<td ><?php echo $row5->indikator; ?></td>
							<td align="center"><?php echo $row5->kondisi_awal." ".$row5->satuan_target; ?></td>
							<td align="center"><?php echo $row5->target_1; ?></td>
							<td align="center"><?php echo $row5->target_2; ?></td>
							<td align="center"><?php echo $row5->target_3; ?></td>
							<td align="center"><?php echo $row5->target_4; ?></td>
							<td align="center"><?php echo $row5->target_5; ?></td>
							<td align="center"><?php echo $row5->target_kondisi_akhir; ?></td>
						</tr>
					<?php } ?>
		<?php }}}}} ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6"><b>TOTAL </b></td>
			<td></td>
			<td><?php echo Formatting::currency($u_1); ?></td>
			<td></td>
			<td><?php echo Formatting::currency($u_2); ?></td>
			<td></td>
			<td><?php echo Formatting::currency($u_3); ?></td>
			<td></td>
			<td><?php echo Formatting::currency($u_4); ?></td>
			<td></td>
			<td><?php echo Formatting::currency($u_5); ?></td>
			<td></td>
			<td><?php echo Formatting::currency(floatval($u_1)+floatval($u_2)+floatval($u_3)+floatval($u_4)+floatval($u_5)); ?></td>
			<td></td>
		</tr>
	</tfoot>

</table>
