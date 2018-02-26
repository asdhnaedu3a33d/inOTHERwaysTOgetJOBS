<?php
	$max_col_keg=1;
	$tot_nominal=0; 
	$tot_nominal_thndpn=0;
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
		<th colspan="3">Rencana Tahun <?php echo $tahun_renja?></th>
		<th rowspan="2">Catatan</th>
		<th colspan="2">Perkiraan Maju Rencana Tahun <?php echo $tahun_renja+1;?></th>
	</tr>
	<tr>
		<th >Lokasi</th>
		<th >Target Capaian Kinerja</th>
		<th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
		<th >Target Capaian Kinerja</th>
		<th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
	</tr>
</thead>
<tbody>
<?php
	foreach ($urusan as $row_urusan) {
		echo "
		<tr bgcolor=\"#78cbfd\">
			<td>".$row_urusan->kd_urusan."</td>
			<td></td>
			<td></td>
			<td></td>
			<td colspan=\"4 \">
				<strong>".strtoupper($row_urusan->nama_urusan)."</strong>
			</td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nominal)."</td>
			<td colspan=\"2\"></td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nominal_thndpn)."</td>

		</tr>";

		$bidang = $this->db->query("
			select t.*,b.Nm_Bidang as nama_bidang from (
			SELECT
				pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
				SUM(keg.nominal) AS sum_nominal,
				SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
			FROM
				(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=1 AND id_skpd > 0) AS pro
			INNER JOIN
				(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=2 AND id_skpd > 0) AS keg ON keg.parent=pro.id
			WHERE
				keg.tahun=".$ta."
			AND keg.kd_urusan = ".$row_urusan->kd_urusan." AND pro.id IN (SELECT id_prog_keg FROM t_renja_indikator_prog_keg WHERE id_prog_keg = pro.id AND (target > 0 OR target_thndpn > 0))
			GROUP BY pro.kd_urusan,kd_bidang
			ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
			) t
			left join m_bidang b
			on t.kd_urusan = b.Kd_Urusan and t.kd_bidang = b.Kd_Bidang
		")->result();

		foreach ($bidang as $row_bidang) {
			echo "
			<tr bgcolor=\"#00FF33\">
				<td>".$row_urusan->kd_urusan."</td>
				<td>".$row_bidang->kd_bidang."</td>
				<td></td>
				<td></td>
				<td colspan=\"4 \">
					<strong>".strtoupper($row_bidang->nama_bidang)."</strong>
				</td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nominal)."</td>
				<td colspan=\"2\"></td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nominal_thndpn)."</td>
			</tr>";


			$skpd = $this->db->query("
			select t.*,b.nama_skpd from (
				SELECT
					pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,pro.id_skpd,
					SUM(keg.nominal) AS sum_nominal,
					SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
				FROM
					(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=1 AND id_skpd > 0) AS pro
				INNER JOIN
					(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=2 AND id_skpd > 0) AS keg ON keg.parent=pro.id
				WHERE
					keg.tahun=".$ta."
				AND keg.kd_urusan = ".$row_urusan->kd_urusan."
				AND keg.kd_bidang = ".$row_bidang->kd_bidang." 
				AND pro.id IN (SELECT id_prog_keg FROM t_renja_indikator_prog_keg WHERE id_prog_keg = pro.id AND (target > 0 OR target_thndpn > 0))
				GROUP BY pro.id_skpd
				ORDER BY id_skpd ASC
				) t
				left join m_skpd b
				on b.id_skpd = t.id_skpd
			")->result();

			foreach ($skpd as $row_skpd) {
				echo "
				<tr>
					<td colspan=\"8 \">
						<strong>".strtoupper($row_skpd->nama_skpd)."</strong>
					</td>
					<td align=\"right\">".Formatting::currency($row_skpd->sum_nominal)."</td>
					<td colspan=\"2\"></td>
					<td align=\"right\">".Formatting::currency($row_skpd->sum_nominal_thndpn)."</td>
				</tr>";
				$id_skpd = $row_skpd->id_skpd;

				$program = $this->m_renja_trx->get_program_skpd_4_cetak_v2($id_skpd,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);


				foreach ($program as $row) {
					$tot_nominal += $row->sum_nominal;
					$tot_nominal_thndpn += $row->sum_nominal_thndpn;
				    $result = $this->m_renja_trx->get_kegiatan_skpd_4_cetak($row->id);
				    $kegiatan = $result->result();
				    $indikator_program = $this->m_renja_trx->get_indikator_prog_keg($row->id, FALSE, TRUE);
				    $temp = $indikator_program->result();
				    $total_temp = $indikator_program->num_rows();

				    $col_indikator=1;
				    $go_3_keg = FALSE;
				    $total_for_iteration = $total_temp;
				    if ($total_temp > $max_col_keg) {
				        // $total_temp = $max_col_keg;
				        $go_3_keg = TRUE;
				    }
				?>
				<tr  bgcolor="#FF8000">
				    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_urusan; ?></td>
				    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_bidang; ?></td>
				    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_program; ?></td>
				    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
				    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
				    <td>
				        <?php
				            echo $temp[0]->indikator;
				        ?>
				    </td>
				    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi;?></td>
				    <td align="center">
				        <?php echo $temp[0]->target." ".$temp[0]->satuan_target;?>
				    </td>
				    <td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nominal);?></td>
				    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->catatan;?></td>
				    <td align="center">
				        <?php echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target_thndpn;?>
				    </td>
				    <td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nominal_thndpn);?></td>
				</tr>
				<?php
				    if ($total_for_iteration > 1) {
				        for ($i=1; $i < $total_for_iteration; $i++) {
				            $col_indikator++;
				?>
				    <tr bgcolor="#FF8000">
				<?php
				            if ($go_3_keg && $col_indikator > $max_col_keg) {
				?>
		<!-- 		        <td style="border-top: 0;border-bottom: 0;" ></td>
				        <td style="border-top: 0;border-bottom: 0;" ></td>
				        <td style="border-top: 0;border-bottom: 0;" ></td>
				        <td style="border-top: 0;border-bottom: 0;" ></td>
				        <td style="border-top: 0;border-bottom: 0;" ></td> -->
				<?php
				            }
				?>
				        <td>
				            <?php
				                echo $temp[$i]->indikator;
				            ?>
				        </td>
				<?php
				            if ($go_3_keg && $col_indikator > $max_col_keg) {
				?>
				        <!-- <td style="border-top: 0;border-bottom: 0;" ></td> -->
				<?php
				            }
				?>
				        <td align="center">
				            <?php
				                echo $temp[$i]->target." ".$temp[$i]->satuan_target;
				            ?>
				        </td>
				<?php
				            if ($go_3_keg && $col_indikator > $max_col_keg) {
				?>
				        <!-- <td style="border-top: 0;border-bottom: 0;" ></td>
				        <td style="border-top: 0;border-bottom: 0;" ></td> -->
				<?php
				            }
				?>
				        <td align="center">
				            <?php
				                echo $temp[$i]->target_thndpn." ".$temp[$i]->satuan_target_thndpn;
				            ?>
				        </td>
				<?php
				            if ($go_3_keg && $col_indikator > $max_col_keg) {
				?>
				        <!-- <td style="border-top: 0;border-bottom: 0;" ></td> -->
				<?php
				                }
				?>
				    </tr>
				<?php
				        }
				     }

				    foreach ($kegiatan as $row) {
				        $indikator_kegiatan = $this->m_renja_trx->get_indikator_prog_keg($row->id, FALSE, TRUE);
				        $temp = $indikator_kegiatan->result();
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
				        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_urusan; ?></td>
				        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_bidang; ?></td>
				        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_program; ?></td>
				        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
				        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
				        <td>
				        <?php
				            echo $temp[0]->indikator;
				        ?>
				        </td>
				        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi;?></td>
				        <td align="center">
				        <?php
				            echo $temp[0]->target." ".$temp[0]->satuan_target;
				        ?>
				        </td>
				        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nominal);?></td>
				        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->catatan;?></td>
				        <td align="center">
				        <?php
				            echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target_thndpn;
				        ?>
				        </td>
				        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nominal_thndpn);?></td>
				    </tr>
				<?php
				        if ($total_for_iteration > 1) {
				            for ($i=1; $i < $total_for_iteration; $i++) {
				                $col_indikator_keg++;
				?>
				    <tr>
				<?php
				                if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
				?>
				        <td style="border-top: 0;border-bottom: 0;" ></td>
				        <td style="border-top: 0;border-bottom: 0;" ></td>
				        <td style="border-top: 0;border-bottom: 0;" ></td>
				        <td style="border-top: 0;border-bottom: 0;" ></td>
				        <td style="border-top: 0;border-bottom: 0;" ></td>
				<?php
				                }
				?>
				        <td>
				            <?php
				                echo $temp[$i]->indikator;
				            ?>
				        </td>
				<?php
				                if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
				?>
				        <td style="border-top: 0;border-bottom: 0;" ></td>
				<?php
				                }
				?>
				        <td align="center">
				            <?php
				                echo $temp[$i]->target." ".$temp[$i]->satuan_target;
				            ?>
				        </td>
				<?php
				                if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
				?>
				        <td style="border-top: 0;border-bottom: 0;" ></td>
				        <td style="border-top: 0;border-bottom: 0;" ></td>
				<?php
				                }
				?>
				        <td align="center">
				            <?php
				                echo $temp[$i]->target_thndpn." ".$temp[$i]->satuan_target_thndpn;
				            ?>
				        </td>
				<?php
				                if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
				?>
				        <td style="border-top: 0;border-bottom: 0;" ></td>
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
		}
	}
?>
		<tr bgcolor="#999999">
        	<td colspan="4"></td>
			<td colspan="4">
            	<strong>
                	Total Nominal Tahun Ini
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo Formatting::currency($tot_nominal) ;?>
                <strong>
            </td>
			<td colspan="2">
            	<strong>
                	Total Nominal Tahun Depan
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo Formatting::currency($tot_nominal_thndpn); ?>
                </strong>
            </td>
		</tr>
</tbody>
