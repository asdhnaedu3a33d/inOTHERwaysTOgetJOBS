
	<h1> Tabel Program Daerah, Strategi, Kebijakan </h1>
	<table border="1"  style="border-collapse: collapse"  width="100%" >
	<thead>
		<tr>
			<th >No</th>
			<th >Program Daerah</th>
			<th >Strategi</th>
			<th >Kebijakan</th>
			<th >Urusan</th>
			<th >SKPD</th>
		</tr>

	</thead>
	<tbody>
		<?php $no=1; foreach ($dataprogram as $rowprogram ) {
			$countprogram=0;
			$datakebijakan_by_prog_daerah=$this->m_rpjmd_trx->get_kebijakan_by_program_daerah($rowprogram->id);
			$rowspanprogram = count($datakebijakan_by_prog_daerah);
			$datastrategi = $this->m_rpjmd_trx->get_strategi_by_program_daerah($rowprogram->id);

			$urusan = $this->m_rpjmd_trx->get_all_urusan($rowprogram->id);
			$urusan_="";

			foreach ($urusan as $urusan_bid ) {
				if ($urusan_=="") {
					$urusan_=$urusan_bid->Nm_Bidang;
				}else {
					$urusan_ =$urusan_.'<br>'.$urusan_bid->Nm_Bidang;
				}
			}

			$skpd_penanggung = $this->m_rpjmd_trx->get_skpd_penanggung_by_program_daerah($rowprogram->id);
			$skpd_="";

			foreach ($skpd_penanggung as $skpd_pj ) {
				if ($skpd_=="") {
					$skpd_=$skpd_pj->nama_skpd;
				}else {
					$skpd_ =$skpd_.'<br>'.$skpd_pj->nama_skpd;
				}
			}
			//
			// $datastrategi =  $this->m_rpjmd_trx->get_strategi_sasaran_cetak($rowprogram->id_sasaran);
			// $kodeprogram = $rowprogram->id_rpjmd.'.'.$rowprogram->id_tujuan.'.'.$rowprogram->id_sasaran.'.'.$rowprogram->id;
			// $totalstrategibysasaran =  $this->m_rpjmd_trx->get_total_kebijakan_strategi_cetak($rowprogram->id_sasaran)->jumlah;
			// $totalstrategi = 0;//count($datastrategi); ?>

			<?php foreach ($datastrategi as $rowstrategi) {
				$datakebijakan =  $this->m_rpjmd_trx->get_kebijakan_by_strategi($rowstrategi->id);
				$rowspanstrategi = count($datakebijakan);
				$countstrategi=0;
				foreach  ($datakebijakan as $rowskebijakan) {
					?>
					<tr>
						<?php if ($countprogram==0) { ?>
							<td rowspan="<?php echo $rowspanprogram; ?>"></td>
							<td align="left" style="border-left: 0;"rowspan="<?php echo $rowspanprogram; ?>"><?php echo $rowprogram->nama_prog;?> </td>..
						<?php } ?>
						<?php if ($countstrategi==0) { ?>
							<td rowspan="<?php echo $rowspanstrategi; ?>"><?php echo $rowstrategi->strategi ;?></td>
						<?php } ?>
							<td ><?php echo $rowskebijakan->kebijakan ;?></td>
						<?php if ($countprogram==0) { ?>
							<td rowspan="<?php echo $rowspanprogram; ?>"><?php echo $urusan_ ;?></td>
							<td rowspan="<?php echo $rowspanprogram; ?>"><?php echo $skpd_ ;?></td>
						<?php } ?>
					</tr>
					<?php $countprogram++;$countstrategi++;} ?>
				<?php } ?>
			<?php $no++;} ?>
</tbody>
	</table>
