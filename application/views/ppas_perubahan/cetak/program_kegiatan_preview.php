<?php
	$max_col_keg=1;
?>
<thead>
<?php
	if(!empty($ta))
	{
		$tahun_rka = $ta;
	}
	else
	{
		$tahun_rka = $this->session->userdata('t_anggaran_aktif');
	}
?>
	<tr>
		<th rowspan="2" colspan="4">Kode</th>
		<th rowspan="2">Program dan Kegiatan</th>
		<th rowspan="2">Indikator Kinerja Program/Kegiatan</th>
		<th colspan="3">DPA Tahun <?php echo $tahun_rka;?></th>
        <th rowspan="2">Indikator Kinerja Program/Kegiatan</th>
		<th colspan="3">Plafon Anggaran Perubahan Tahun <?php echo $tahun_rka;?> Perubahan</th>
        <th rowspan="2">Bertambah (Berkurang)</th>
	</tr>
	<tr>
		<th >Lokasi</th>
		<th >Target Capaian Kinerja</th>
		<th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
        <th >Lokasi</th>
		<th >Target Capaian Kinerja</th>
		<th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
	</tr>
</thead>
<tbody>
<?php
	foreach ($urusan as $row_urusan) {
		$np_urusan = $row_urusan->sum_nomrka_perubahan-$row_urusan->sum_nomrka;
		echo "
		<tr bgcolor=\"#78cbfd\">
			<td>".$row_urusan->kd_urusan."</td>
			<td></td>
			<td></td>
			<td></td>
			<td colspan=\"4 \">
				<strong>".strtoupper($row_urusan->nama_urusan)."</strong>
			</td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nomrka)."</td>
			<td colspan=\"3 \"></td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nomrka_perubahan)."</td>
			<td align=\"right\">";
			echo ($np_urusan >= 0)?Formatting::currency($np_urusan) : "(".Formatting::currency(abs($np_urusan)).")";
			echo "</td></tr>";

		$bidang = $this->db->query("
			SELECT t.*,b.Nm_Bidang as nama_bidang from (
				SELECT pro.*,
				SUM(keg.nomrka) AS sum_nomrka,
				SUM(keg.nomrka_perubahan) AS sum_nomrka_perubahan
				FROM
					(SELECT a.`id`,
						b.`id` AS id_perubahan,
						a.`tahun`,
						a.`kd_urusan`,
						a.`kd_bidang`,
						a.`kd_program`,
						a.`kd_kegiatan`,
						a.`nama_prog_or_keg`,
						a.`nominal` AS nomrka,
						b.`nominal_thndpn` AS nomrka_perubahan,
						a.`id_skpd`,
						a.penanggung_jawab,
						a.lokasi,
						a.catatan,
						a.id_status,
						b.`penanggung_jawab` AS penanggung_jawab_perubahan,
						b.`lokasi` AS lokasi_perubahan ,
						b.`catatan` AS catatan_perubahan
					 FROM t_renja_prog_keg_perubahan a
					 LEFT JOIN t_ppas_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
								  AND a.`kd_bidang`=b.`kd_bidang`
								  AND a.`kd_program`=b.`kd_program`
								  AND a.`kd_kegiatan`=b.`kd_kegiatan`
								  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
								  AND a.`id_skpd`=b.`id_skpd`
							  AND a.`tahun`=b.`tahun`
					 WHERE a.is_prog_or_keg=1
					 GROUP BY a.`id`) AS pro
				INNER JOIN
					(SELECT a.`id`,
						b.`id` AS id_perubahan,
						a.`id_skpd`,
						a.`tahun`,
						a.`kd_urusan`,
						a.`kd_bidang`,
						a.`kd_program`,
						a.`kd_kegiatan`,
						a.`parent`,
						a.`nominal` AS nomrka,
						b.`nominal_thndpn` AS nomrka_perubahan,
						b.`penanggung_jawab` AS penanggung_jawab_perubahan,
						b.`lokasi` AS lokasi_perubahan ,
						b.`catatan` AS catatan_perubahan
					 FROM t_renja_prog_keg_perubahan a
					 LEFT JOIN t_ppas_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
								  AND a.`kd_bidang`=b.`kd_bidang`
								  AND a.`kd_program`=b.`kd_program`
								  AND a.`kd_kegiatan`=b.`kd_kegiatan`
								  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
								  AND a.`id_skpd`=b.`id_skpd`
							  AND a.`tahun`=b.`tahun`
					 WHERE a.is_prog_or_keg=2
					 GROUP BY a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`,a.`id`) AS keg ON keg.parent=pro.id
				WHERE
					keg.id_skpd = ".$id_skpd."
					AND keg.tahun = ".$ta."
					AND keg.kd_urusan =".$row_urusan->kd_urusan."
				GROUP BY pro.kd_bidang
				ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
			) t
			left join m_bidang b
			on t.kd_urusan = b.Kd_Urusan and t.kd_bidang = b.Kd_Bidang
		")->result();

		foreach ($bidang as $row_bidang) {
			$np_bidang = $row_bidang->sum_nomrka_perubahan-$row_bidang->sum_nomrka;
			echo "
			<tr bgcolor=\"#00FF33\">
				<td>".$row_urusan->kd_urusan."</td>
				<td>".$row_bidang->kd_bidang."</td>
				<td></td>
				<td></td>
				<td colspan=\"4 \">
					<strong>".strtoupper($row_bidang->nama_bidang)."</strong>
				</td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nomrka)."</td>
				<td colspan=\"3 \"></td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nomrka_perubahan)."</td>
				<td align=\"right\">";
			echo ($np_bidang >= 0)?Formatting::currency($np_bidang) : "(".Formatting::currency(abs($np_bidang)).")";
			echo "</td></tr>";

		$program = $this->m_ppas_perubahan->get_program_skpd_4_cetak_vdee($id_skpd,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);

	foreach ($program as $row) {
		$result = $this->m_ppas_perubahan->get_kegiatan_skpd_4_cetak($row->id_skpd, $row->tahun, $row->kd_urusan, $row->kd_bidang, $row->kd_program);
		$kegiatan = $result->result();
		$get_id_rka = $this->m_ppas_perubahan->get_id_rka($row->id_skpd, $row->tahun, $row->kd_urusan, $row->kd_bidang, $row->kd_program);
		$get_id_rka_perubahan = $this->m_ppas_perubahan->get_id_rka_perubahan($row->id_skpd, $row->tahun, $row->kd_urusan, $row->kd_bidang, $row->kd_program);
		$indikator_program = $this->m_ppas_perubahan->get_indikator_prog_keg_vdee($get_id_rka, FALSE, TRUE);
		$indikator_program_perubahan = $this->m_ppas_perubahan->get_indikator_prog_keg_perubahan($get_id_rka_perubahan, FALSE, TRUE);
		$temp = $indikator_program->result();
		$temp_perubahan = $indikator_program_perubahan->result();
		$total_temp = $indikator_program->num_rows();
		if($indikator_program->result()!=NULL){
			$temp = $indikator_program->result();
		}
		else {
			$temp = NULL;
		}

		if($indikator_program_perubahan->result()!=NULL){
			$temp_perubahan = $indikator_program_perubahan->result();
		}
		else {
			$temp_perubahan = NULL;
		}
		$total_temp = $indikator_program->num_rows();

		$col_indikator=1;
		$go_3_keg = FALSE;
		$total_for_iteration = $total_temp;
		if ($total_temp > $max_col_keg) {
			$total_temp = $max_col_keg;
			$go_3_keg = TRUE;
		}
?>
	<tr bgcolor="#FF9933">
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_urusan; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_bidang; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_program; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
		<td>
			<?php
				echo isset($temp[0]->indikator) ? $temp[0]->indikator : $temp_perubahan[0]->indikator.' (RKA Perubahan)' ;
			?>
		</td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo isset($row->lokasi) && isset($temp[0]->target) ? $row->lokasi : '-';?></td>
		<td align="center">
			<?php
				if(!empty($temp[0])){
					echo $temp[0]->target." ".$temp[0]->satuan_target;
				}
				else{
					echo "-";
				}
			?>
		</td>
		<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_for_iteration; ?>"><?php echo Formatting::currency($row->sum_nomrka);?></td>
    <td>
			<?php if(!empty($temp_perubahan[0])){
					echo $temp_perubahan[0]->indikator;
				} else {
					echo "-";
				}
			?>
		</td>
        <td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo isset($row->lokasi_perubahan) && isset($temp_perubahan[0]->target) ? $row->lokasi_perubahan : '-';?></td>
		<td align="center">
			<?php if(!empty($temp_perubahan[0])){
					echo $temp_perubahan[0]->target." ".$temp_perubahan[0]->satuan_target;
				} else {
					echo "-";
				}
			?>
		</td>
		<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_for_iteration; ?>"><?php echo Formatting::currency($row->sum_nomrka_perubahan);?></td>
		<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_for_iteration; ?>"><?php 
			$np_program = $row->sum_nomrka_perubahan-$row->sum_nomrka;
			echo ($np_program >= 0)?Formatting::currency($np_program) : "(".Formatting::currency(abs($np_program)).")";
		?></td>
	</tr>
<?php
		if ($total_for_iteration > 1) {
			for ($i=1; $i < $total_for_iteration; $i++) {
				$col_indikator++;
?>
		<tr bgcolor="#FF9933">
			<td>
				<?php
					echo $temp[$i]->indikator;
				?>
			</td>
			<td align="center">
				<?php
					echo $temp[$i]->target." ".$temp[$i]->satuan_target;
				?>
			</td>
			<td>
				<?php
					echo $temp_perubahan[$i]->indikator;
				?>
			</td>
			<td align="center">
				<?php
					echo $temp_perubahan[$i]->target." ".$temp_perubahan[$i]->satuan_target;
				?>
			</td>
		</tr>
<?php
		 	}
		 }

		foreach ($kegiatan as $row) {
			$indikator_kegiatan = $this->m_ppas_perubahan->get_indikator_prog_keg_vdee($row->id, FALSE, TRUE);
			$indikator_kegiatan_perubahan = $this->m_ppas_perubahan->get_indikator_prog_keg_perubahan($row->id_perubahan, FALSE, TRUE);
			$temp = $indikator_kegiatan->result();
			$temp_perubahan = $indikator_kegiatan_perubahan->result();
			$total_temp = $indikator_kegiatan->num_rows();

			$go_3_keg = FALSE;
			$col_indikator_keg=1;
			$total_for_iteration = $total_temp;
			if ($total_temp > $max_col_keg) {
				$total_temp = $max_col_keg;
				$go_3_keg = TRUE;
			}
?>
		<tr>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_urusan; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_bidang; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_program; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_kegiatan; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
			<td>
				<?php
					echo isset($temp[0]->indikator) ? $temp[0]->indikator : $temp_perubahan[0]->indikator.' (RKA Perubahan)' ;
				?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo isset($row->lokasi) && isset($temp[0]->target) ? $row->lokasi : '-';?></td>
			<td align="center">
				<?php if(!empty($temp[0])){
                        echo $temp[0]->target." ".$temp[0]->satuan_target;
                    } else {
                        echo "-";
                    }
                ?>			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>" align="right" ><?php echo Formatting::currency($row->nomrka);?></td>
            <td>
			<?php
				if(!empty($temp_perubahan[0])){
					echo $temp_perubahan[0]->indikator;
				}else {
						echo "-";
				}
			?>
			</td>
            <td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo isset($row->lokasi_perubahan) && isset($temp_perubahan[0]->target) ? $row->lokasi_perubahan : '-';?></td>
			<td align="center">
			<?php
				if(!empty($temp_perubahan[0])){
					echo $temp_perubahan[0]->target." ".$temp_perubahan[0]->satuan_target;
				}else {
						echo "-";
				}
			?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>" align="right" ><?php echo Formatting::currency($row->nomrka_perubahan);?></td>
			<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_for_iteration; ?>"><?php 
				$np_keg = $row->nomrka_perubahan-$row->nomrka;
				echo ($np_keg >= 0)?Formatting::currency($np_keg) : "(".Formatting::currency(abs($np_keg)).")";
			?></td>
		</tr>
<?php
			if ($total_for_iteration > 1) {
				for ($i=1; $i < $total_for_iteration; $i++) {
					$col_indikator_keg++;
?>
		<tr>
			<td>
				<?php
					echo $temp[$i]->indikator;
				?>
			</td>
			<td align="center">
				<?php
					echo $temp[$i]->target." ".$temp[$i]->satuan_target;
				?>
			</td>
			<td>
				<?php
					echo $temp_perubahan[$i]->indikator;
				?>
			</td>
			<td align="center">
				<?php
					echo $temp_perubahan[$i]->target." ".$temp_perubahan[$i]->satuan_target;
				?>
			</td>
		</tr>
<?php
				}
				}
				}
			}
		}
	}
?>
</tbody>
