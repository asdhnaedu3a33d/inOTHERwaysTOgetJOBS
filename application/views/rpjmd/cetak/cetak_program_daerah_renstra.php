
	<h1> Tabel Program Daerah Renstra </h1>
	<table border="1"  style="border-collapse: collapse"  width="100%" >
	<thead>
		<tr>
			<th > NO</th>
			<th >Program Daerah</th>
			<th >Kode Program</th>
			<th >Program</th>
			<th >Indikator</th>
			<th >SKPD Penanggungjawab</th>
		</tr>

	</thead>
	<tbody>

	<?php  foreach ($program as $rowprogram ) {
		$countdaerah=0;
		$indikator = $this->m_rpjmd_trx->get_indikator_program_skpd_by_program_daerah($rowprogram->id);

		$program_skpd = $this->m_rpjmd_trx->get_program_skpd_by_program_daerah($rowprogram->id);
		$skpd_penanggung = $this->m_rpjmd_trx->get_skpd_penanggung_by_program_daerah($rowprogram->id);
		$skpd_="";

		foreach ($skpd_penanggung as $skpd_pj ) {
			if ($skpd_=="") {
				$skpd_=$skpd_pj->nama_skpd;
			}else {
				$skpd_ =$skpd_.'<br>'.$skpd_pj->nama_skpd;
			}
		}
		$rowspanprogramdaerah = count($indikator);
		$rowspanindikator = count($indikator);
		$no=1;
	 $countskpd=0;
	 foreach ($program_skpd as $rowprogramskpd ) {
		 $countskpd=0;
		 //ambil indikator per program SKPD
		 $indikator_program_skpd = $this->m_rpjmd_trx->get_indikator_program_skpd_by_program_skpd($rowprogramskpd->id);
		 $rowspanprogramskpd = count($indikator_program_skpd);
	 ?>
		  <?php foreach ($indikator_program_skpd as $indikator ) { ?>
				<tr>
					<?php
					if ($countdaerah==0) {
					?>
						<td rowspan="<?php echo $rowspanprogramdaerah ; ?>"><?php echo $no ;?></td>
				 		<td rowspan="<?php echo $rowspanprogramdaerah ; ?>"><?php echo $rowprogram->nama_prog ;?></td>
					<?php
					}
					?>
					<?php
					if ($countskpd==0) {
					?>
						<td align="center" rowspan="<?php echo $rowspanprogramskpd ; ?>"><?php echo "0".$rowprogramskpd->kd_urusan.".".$rowprogramskpd->kd_bidang.".".$rowprogramskpd->kd_program ;?></td>
						<td rowspan="<?php echo $rowspanprogramskpd ; ?>"><?php echo  $rowprogramskpd->nama_prog_or_keg ;?></td>
					<?php
					$countskpd++;}
					?>
			    <td><?php echo $indikator->indikator ;?></td>
					<?php
					if ($countdaerah==0) {
					?>
					<td rowspan="<?php echo $rowspanprogramdaerah ; ?>"><?php echo $skpd_ ;?></td>
					<?php
					$countdaerah++;}
					?>

				</tr>
<?php   }
 $no++;}
$no++;} ?>
</tbody>
</table>
