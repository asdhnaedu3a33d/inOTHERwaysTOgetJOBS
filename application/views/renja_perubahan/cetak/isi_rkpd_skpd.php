<?php
	$max_col_keg=1;
	$total_nomrenja = 0;
	$total_nomrenja_perubahan = 0;
	$p=0;
	$k=0;
?>
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
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nomrenja,2)."</td>
			<td colspan=\"3\"></td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nomrenja_perubahan,2)."</td>
			<td colspan=\"2\"></td>
		</tr>";
		$total_nomrenja += $row_urusan->sum_nomrenja;
		$total_nomrenja_perubahan += $row_urusan->sum_nomrenja_perubahan;

		$bidang = $this->db->query("
			SELECT r.*,b.Nm_Bidang AS nama_bidang FROM (
			SELECT
			r.tahun,
			r.id_skpd,
			r.kd_urusan,
			r.kd_bidang,
			SUM(r.nomrenja) AS sum_nomrenja,
			SUM(r.nomrenja_perubahan) AS sum_nomrenja_perubahan
			FROM (
			SELECT
			k.*,
			IF(r.nama_prog_or_keg='',r.nama_prog_or_keg,rp.nama_prog_or_keg) AS nama_prog_or_keg,
			r.id,
			r.penanggung_jawab,
			r.lokasi,
			r.catatan,
			r.id_status,
			r.`nominal` AS nomrenja,
			rp.id_renja,
			rp.`penanggung_jawab` AS penanggung_jawab_perubahan,
			rp.`lokasi` AS lokasi_perubahan ,
			rp.`catatan` AS catatan_perubahan,
			rp.`keterangan` AS keterangan_perubahan,
			rp.`nominal` AS nomrenja_perubahan
			FROM (
			SELECT tahun,kd_urusan,kd_bidang,kd_program,kd_kegiatan,id_skpd FROM t_renja_prog_keg WHERE tahun = '".$ta."' AND kd_kegiatan IS NOT NULL
			UNION
			SELECT tahun,kd_urusan,kd_bidang,kd_program,kd_kegiatan,id_skpd FROM t_renja_prog_keg_perubahan WHERE tahun = '".$ta."' AND kd_kegiatan IS NOT NULL
			) k
			LEFT JOIN t_renja_prog_keg r
			ON k.tahun = r.tahun
			AND k.kd_urusan = r.kd_urusan
			AND k.kd_bidang = r.kd_bidang
			AND k.kd_program = r.kd_program
			AND k.kd_kegiatan = r.kd_kegiatan
			AND k.id_skpd = r.id_skpd
			LEFT JOIN t_renja_prog_keg_perubahan AS rp
			ON k.tahun = rp.tahun
			AND k.kd_urusan = rp.kd_urusan
			AND k.kd_bidang = rp.kd_bidang
			AND k.kd_program = rp.kd_program
			AND k.kd_kegiatan = rp.kd_kegiatan
			AND k.id_skpd = rp.id_skpd
			) r
			WHERE id_skpd = '".$id_skpd."'
			AND kd_urusan = '".$row_urusan->kd_urusan."'
			GROUP BY kd_bidang
			ORDER BY kd_urusan ASC,kd_bidang ASC
			) r
			LEFT JOIN m_bidang b
			ON r.kd_urusan = b.Kd_Urusan AND r.kd_bidang = b.Kd_Bidang
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
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nomrenja,2)."</td>
				<td colspan=\"3\"></td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nomrenja_perubahan,2)."</td>
				<td colspan=\"2\"></td>
			</tr>";

			$program = $this->m_renja_trx_perubahan->get_program_skpd_4_cetak($id_skpd,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);


			foreach ($program as $row) {
				$result = $this->m_renja_trx_perubahan->get_kegiatan_skpd_4_cetak($row->id_skpd,$ta,$row->kd_urusan,$row->kd_bidang,$row->kd_program);
				$kegiatan = $result->result();
				$get_id_renja = $this->m_renja_trx_perubahan->get_id_renja($row->id_skpd, $row->tahun, $row->kd_urusan, $row->kd_bidang, $row->kd_program);
				$get_id_renja_perubahan = $this->m_renja_trx_perubahan->get_id_renja_perubahan($row->id_skpd, $row->tahun, $row->kd_urusan, $row->kd_bidang, $row->kd_program);
				$indikator_program = $this->m_renja_trx_perubahan->get_indikator_prog_keg($get_id_renja, FALSE, TRUE);
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
				$p++;

				$col_indikator=1;
				$go_3_keg = FALSE;
				$total_for_iteration = $total_temp;
				if ($total_temp > $max_col_keg) {
					$total_temp = $max_col_keg;
					$go_3_keg = TRUE;
				}
		?>
			<tr bgcolor="#FF9933">
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_urusan; ?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_bidang; ?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_program; ?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
                <td>
                    <?php
                        echo isset($temp[0]->indikator) ? $temp[0]->indikator : $temp_perubahan[0]->indikator.' (Renja Perubahan)' ;
                    ?>
                </td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi;?></td>
                <td align="center">
                    <?php 
                        if($temp!=NULL){
                            echo $temp[0]->target." ".$temp[0]->satuan_target;
                        }
                        else{
                            echo "-";
                        }
                    ?>
                </td>
                <td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nomrenja,2);?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->catatan;?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi_perubahan;?></td>
                <td align="center">
                    <?php
                        if($temp_perubahan!=NULL){ 
                            echo $temp_perubahan[0]->target." ".$temp_perubahan[0]->satuan_target;
                        }
                        else{
                            echo "-";
                        }
                    ?>
                </td>
                <td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nomrenja_perubahan,2);?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->catatan_perubahan;?></td>
                <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->keterangan_perubahan;?></td>
            </tr>
        <?php
                if ($total_for_iteration > 1) {
                    for ($i=1; $i < $total_for_iteration; $i++) {
                        $col_indikator++;
        ?>
                <tr bgcolor="#FF9933">
        <?php
                        if ($go_3_keg && $col_indikator > $max_col_keg) {
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
                        if ($go_3_keg && $col_indikator > $max_col_keg) {
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
                        if ($go_3_keg && $col_indikator > $max_col_keg) {
        ?>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
        <?php
                        }
        ?>
                    <td align="center">
                        <?php
                            echo $temp_perubahan[$i]->target." ".$temp_perubahan[$i]->satuan_target;
                        ?>
                    </td>
        <?php
                        if ($go_3_keg && $col_indikator > $max_col_keg) {
        ?>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
        <?php
                            }
        ?>
                </tr>
		<?php
				 	}
				 }

				foreach ($kegiatan as $row) {
					$indikator_kegiatan = $this->m_renja_trx->get_indikator_prog_keg($row->id, FALSE, TRUE);
					$indikator_kegiatan_perubahan = $this->m_renja_trx_perubahan->get_indikator_prog_keg_perubahan($row->id_perubahan, FALSE, TRUE);
					$temp = $indikator_kegiatan->result();
					$temp_perubahan = $indikator_kegiatan_perubahan->result();
					$total_temp = $indikator_kegiatan->num_rows();
					$k++;

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
                        echo isset($temp[0]->indikator) ? $temp[0]->indikator : $temp_perubahan[0]->indikator.' (Renja Perubahan)' ;
                    ?>
                    </td>
                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi;?></td>
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
                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nomrenja,2);?></td>
                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->catatan;?></td>
                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi_perubahan;?></td>
                    <td align="center">
                    <?php
                        if(isset($temp_perubahan[0]->target)){
                            echo $temp_perubahan[0]->target." ".$temp_perubahan[0]->satuan_target;
                        }
                        else{
                            echo '-';
                        }
                    ?>
                    </td>
                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nomrenja_perubahan,2);?></td>
                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->catatan_perubahan;?></td>
                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->keterangan_perubahan;?></td>
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
                            echo isset($temp[$i]->indikator) ? $temp[$i]->indikator : $temp_perubahan[$i]->indikator.' (Renja Perubahan)' ;
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
                            
                            if(isset($temp[$i]->target)){
                                echo $temp[$i]->target." ".$temp[$i]->satuan_target;
                            }
                            else{
                                echo '-';
                            }
                        ?>
                    </td>
        <?php
                            if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
        ?>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
        <?php
                            }
        ?>
                    <td align="center">
                        <?php
                            
                            if(isset($temp_perubahan[$i]->target)){
                                echo $temp_perubahan[$i]->target." ".$temp_perubahan[$i]->satuan_target;
                            }
                            else{
                                echo '-';
                            }
                        ?>
                    </td>
        <?php
                            if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
        ?>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
                    <td style="border-top: 0;border-bottom: 0;" ></td>
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
?>
	<tr bgcolor="#999999">
        	<td colspan="4"></td>
			<td colspan="4">
            	<strong>
                	Jumlah Program
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo $p." Program" ;?>
                <strong>
            </td>
			<td colspan="3">
            	<strong>
                	Jumlah Kegiatan
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo $k." Kegiatan"; ?>
                </strong>
            </td>
            <td colspan="2"></td>
		</tr>
        
	<tr bgcolor="#999999">
        	<td colspan="4"></td>
			<td colspan="4">
            	<strong>
                	Total Nominal Renja
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo Formatting::currency($total_nomrenja,2) ;?>
                <strong>
            </td>
			<td colspan="3">
            	<strong>
                	Total Nominal Renja Perubahan
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo Formatting::currency($total_nomrenja_perubahan,2); ?>
                </strong>
            </td>
            <td colspan="2"></td>
		</tr>