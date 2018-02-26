<?php
	$max_col_keg=1;
?>
<?php
	if(!empty($ta))
	{
		$tahun_renja = $ta;
	}
	else
	{
		$tahun_renja = $this->session->userdata('t_anggaran_aktif');
	}
?>
<thead>
	<tr>
		<th rowspan="2" colspan="4">Kode</th>
		<th rowspan="2">Program dan Kegiatan</th>
		<th rowspan="2">Indikator Kinerja Program/Kegiatan</th>
		<th colspan="3">DPA Tahun <?php echo $tahun_renja?></th>
		<!-- <th rowspan="2">Catatan</th> -->
		<th colspan="3">Renja Tahun <?php echo $tahun_renja;?> Perubahan</th>
        <!-- <th rowspan="2">Keterangan Perubahan</th> -->
        <th rowspan="2">Bertambah (Berkurang)</th>
        <th rowspan="2">Catatan</th>
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
		$np_urusan = $row_urusan->sum_nomrenja_perubahan-$row_urusan->sum_nomrenja;
		echo "
		<tr bgcolor=\"#78cbfd\">
			<td>".$row_urusan->kd_urusan."</td>
			<td></td>
			<td></td>
			<td></td>
			<td colspan=\"4 \">
				<strong>".strtoupper($row_urusan->nama_urusan)."</strong>
			</td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nomrenja)."</td>
			<td colspan=\"2\"></td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nomrenja_perubahan)."</td>
			<td align=\"right\">";
			echo ($np_urusan >= 0)?Formatting::currency($np_urusan) : "(".Formatting::currency(abs($np_urusan)).")";
			echo "</td>
			<td colspan=\"1\"></td>
			</tr>";

		$bidang = $this->db->query("
		select r.*,b.Nm_Bidang as nama_bidang from (
			select
			r.tahun,
			r.id_skpd,
			r.kd_urusan,
			r.kd_bidang,
			sum(r.nomrenja) AS sum_nomrenja,
			sum(r.nomrenja_perubahan) AS sum_nomrenja_perubahan
			from (
			select
			k.*,
			if(r.nama_prog_or_keg='',r.nama_prog_or_keg,rp.nama_prog_or_keg) as nama_prog_or_keg,
			r.id,
			r.penanggung_jawab,
			r.lokasi,
			r.catatan_1,
			r.id_status,
			r.nominal_1+r.nominal_2+r.nominal_3+r.nominal_4+r.nominal_5+r.nominal_6+r.nominal_7+r.nominal_8+r.nominal_9+r.nominal_10+r.nominal_11+r.nominal_12 AS nomrenja,
			rp.id_renja,
			rp.`penanggung_jawab` AS penanggung_jawab_perubahan,
			rp.`lokasi` AS lokasi_perubahan ,
			rp.`catatan` AS catatan_perubahan,
			rp.`keterangan` AS keterangan_perubahan,
			rp.`nominal_thndpn` AS nomrenja_perubahan
			from (
			select tahun,kd_urusan,kd_bidang,kd_program,kd_kegiatan,id_skpd from tx_dpa_prog_keg where id_skpd = '".$id_skpd."' and tahun = '".$ta."' and kd_kegiatan is not null
			union
			select tahun,kd_urusan,kd_bidang,kd_program,kd_kegiatan,id_skpd from t_renja_prog_keg_perubahan where id_skpd = '".$id_skpd."' and tahun = '".$ta."' and kd_kegiatan is not null
			) k
			left join tx_dpa_prog_keg r
			on k.tahun = r.tahun
			and k.kd_urusan = r.kd_urusan
			and k.kd_bidang = r.kd_bidang
			and k.kd_program = r.kd_program
			and k.kd_kegiatan = r.kd_kegiatan
			and k.id_skpd = r.id_skpd
			left join t_renja_prog_keg_perubahan as rp
			on k.tahun = rp.tahun
			and k.kd_urusan = rp.kd_urusan
			and k.kd_bidang = rp.kd_bidang
			and k.kd_program = rp.kd_program
			and k.kd_kegiatan = rp.kd_kegiatan
			and k.id_skpd = rp.id_skpd
			) r
			where kd_urusan = '".$row_urusan->kd_urusan."'
			GROUP BY kd_bidang
			order by kd_urusan asc,kd_bidang asc
			) r
			left join m_bidang b
			on r.kd_urusan = b.Kd_Urusan and r.kd_bidang = b.Kd_Bidang
		")->result();

		foreach ($bidang as $row_bidang) {
			$np_bidang = $row_bidang->sum_nomrenja_perubahan-$row_bidang->sum_nomrenja;
			echo "
			<tr bgcolor=\"#00FF33\">
				<td>".$row_urusan->kd_urusan."</td>
				<td>".$row_bidang->kd_bidang."</td>
				<td></td>
				<td></td>
				<td colspan=\"4 \">
					<strong>".strtoupper($row_bidang->nama_bidang)."</strong>
				</td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nomrenja)."</td>
				<td colspan=\"2\"></td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nomrenja_perubahan)."</td>
				<td align=\"right\">";
				echo ($np_bidang >= 0)?Formatting::currency($np_bidang) : "(".Formatting::currency(abs($np_bidang)).")";
				echo "</td>
				<td colspan=\"1\"></td>
			</tr>";

		$program = $this->m_renja_trx_perubahan->get_program_skpd_4_cetak_vdee($id_skpd,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);

	foreach ($program as $row) {
		$result = $this->m_renja_trx_perubahan->get_kegiatan_skpd_4_cetak($row->id_skpd,$ta,$row->kd_urusan,$row->kd_bidang,$row->kd_program);
		
		$kegiatan = $result->result();
		$get_id_renja = $this->m_renja_trx_perubahan->get_id_renja($row->id_skpd, $row->tahun, $row->kd_urusan, $row->kd_bidang, $row->kd_program);
		$get_id_renja_perubahan = $this->m_renja_trx_perubahan->get_id_renja_perubahan($row->id_skpd, $row->tahun, $row->kd_urusan, $row->kd_bidang, $row->kd_program);
		$indikator_program = $this->m_renja_trx_perubahan->get_indikator_prog_keg_vdee($get_id_renja, FALSE, TRUE);
		$indikator_program_perubahan = $this->m_renja_trx_perubahan->get_indikator_prog_keg_perubahan($get_id_renja_perubahan, FALSE, TRUE);
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
		//var_dump($indikator_program->result());

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
				echo isset($temp[0]->indikator) ? $temp[0]->indikator : $temp_perubahan[0]->indikator.' (Renja Perubahan)' ;
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
		<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_for_iteration; ?>"><?php echo Formatting::currency($row->sum_nomrenja);?></td>
		<!-- <td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"></td> -->
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo isset($row->lokasi_perubahan) && isset($temp_perubahan[0]->target) ? $row->lokasi_perubahan : '-';?></td>
		<td align="center">
			<?php
				if(!empty($temp_perubahan[0])){
					echo $temp_perubahan[0]->target." ".$temp_perubahan[0]->satuan_target;
				}
				else{
					echo "-";
				}
			?>
		</td>
		<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_for_iteration; ?>"><?php echo Formatting::currency($row->sum_nomrenja_perubahan);?></td>
        <!-- <td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"></td> -->
        <td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>" align="right"><?php 
			$np_program = $row->sum_nomrenja_perubahan-$row->sum_nomrenja;
			echo ($np_program >= 0)?Formatting::currency($np_program) : "(".Formatting::currency(abs($np_program)).")";
		?></td>
        <td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->keterangan_perubahan;?></td>
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
			<td align="center">
				<?php
					if(!empty($temp_perubahan[$i])){
						echo $temp_perubahan[$i]->target." ".$temp_perubahan[$i]->satuan_target;
					}
					else{
						echo "-";
					}
				?>
			</td>
		</tr>
<?php
		 	}
		 }

		foreach ($kegiatan as $row) {
			$indikator_kegiatan = $this->m_renja_trx_perubahan->get_indikator_prog_keg_vdee($row->id, FALSE, TRUE);
			$indikator_kegiatan_perubahan = $this->m_renja_trx_perubahan->get_indikator_prog_keg_perubahan($row->id_perubahan, FALSE, TRUE);
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
				echo isset($temp[0]->indikator) ? $temp[0]->indikator : $temp_perubahan[0]->indikator.' (Renja Perubahan)' ;
			?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo isset($row->lokasi) && isset($temp[0]->target) ? $row->lokasi : '-';?></td>
			<td align="center">
			<?php
				if(isset($temp[0]->target)){
					echo $temp[0]->target." ".$temp[0]->satuan_target;
				}
				else{
					echo '-';
				}

			?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>" align="right" ><?php echo Formatting::currency($row->nomrenja);?></td>
			<!-- <td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->catatan;?></td> -->
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo isset($row->lokasi_perubahan) && isset($temp_perubahan[0]->target) ? $row->lokasi_perubahan : '-';?></td>
            <td align="center">
			<?php
				if(!empty($temp_perubahan[0]->target)){
					echo $temp_perubahan[0]->target." ".$temp_perubahan[0]->satuan_target;
				}
				else{
					echo '-';
				}
			?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>" align="right" ><?php echo Formatting::currency($row->nomrenja_perubahan);?></td>
            <!-- <td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->catatan_perubahan;?></td> -->
            <td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>" align="right"><?php 
			$np_keg = $row->nomrenja_perubahan-$row->nomrenja;
			echo ($np_keg >= 0)?Formatting::currency($np_keg) : "(".Formatting::currency(abs($np_keg)).")";
		?></td>
            <td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->keterangan_perubahan;?></td>
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
			<td align="center">
				<?php
				if(!empty($temp_perubahan[$i]->target)){
					echo $temp_perubahan[$i]->target." ".$temp_perubahan[$i]->satuan_target;
				}
				else{
					echo '-';
				}
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
