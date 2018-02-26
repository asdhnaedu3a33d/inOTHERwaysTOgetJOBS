

<?php 
    $total_dari_prog = 0;
    $k_total_anggaran_dari_prog = 0;
    $rp_total_anggaran_dari_prog = 0;
    $k_total_realisasi_dari_prog = 0;
    $rp_total_realisasi_dari_prog = 0;
    foreach ($urusan as $row_urusan) {
        $bidang = $this->db->query("
            select t.*,b.Nm_Bidang as nama_bidang from (
            SELECT
                pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
                SUM(keg.nominal) AS sum_nominal,
                SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
            FROM
                (SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=1) AS pro
            INNER JOIN
                (SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
            WHERE
                keg.id_skpd=".$id_skpd."
                AND keg.tahun=".$ta."
            AND keg.kd_urusan = ".$row_urusan->kd_urusan."
            GROUP BY pro.kd_urusan,kd_bidang
            ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
            ) t
            left join m_bidang b
            on t.kd_urusan = b.Kd_Urusan and t.kd_bidang = b.Kd_Bidang
        ")->result();
?>
    <tr bgcolor="#78cbfd">
        <td></td>
        <td><?php echo $row_urusan->kd_urusan; ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"><?php echo $row_urusan->nama_urusan; ?></td>
        <td colspan="26"></td>
    </tr>


<?php 
    foreach ($bidang as $row_bidang) {
        $program = $this->m_evaluasi_renja->get_data_renja_program($id_skpd,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);
?>
    <tr bgcolor="#00FF33">
        <td></td>
        <td><?php echo $row_urusan->kd_urusan; ?></td>
        <td><?php echo $row_bidang->kd_bidang; ?></td>
        <td></td>
        <td></td>
        <td colspan="2"><?php echo $row_bidang->nama_bidang; ?></td>
        <td colspan="26"></td>
    </tr>


<?php 
    $total_dari_prog += count($program);
    foreach ($program as $row_prog) {
        $ind_prog = $this->m_evaluasi_renja->get_data_renja_indikator_prog_keg($row_prog->id);
        $rowsp_prog = count($ind_prog);
        $kegiatan = $this->m_evaluasi_renja->get_data_renja_kegiatan($row_prog->id);

        foreach ($ind_prog as $key_ind_prog => $row_ind_prog) {
            $ind_renstra_prog = $this->m_evaluasi_renja->get_data_renja_indikator_from_renstra($row_ind_prog->indikator);
            $ind_cik_lalu_prog = $this->m_evaluasi_renja->get_data_renja_indikator_from_cik($row_ind_prog->indikator, $ta-1, 1);
            $ind_cik_prog = $this->m_evaluasi_renja->get_data_renja_indikator_from_cik($row_ind_prog->indikator, $ta, 1);

            // $realisasi_capaian_kinerja_prog = $ind_cik_prog->real_3+$ind_cik_prog->real_6+$ind_cik_prog->real_9+$ind_cik_prog->real_12;

            $k_realisasi_capaian_kinerja_prog = round($ind_cik_prog->real_3+$ind_cik_prog->real_6+$ind_cik_prog->real_9+$ind_cik_prog->real_12, 2);
            $rp_realisasi_capaian_kinerja_prog = $ind_cik_prog->realisasi_3+$ind_cik_prog->realisasi_6+$ind_cik_prog->realisasi_9+$ind_cik_prog->realisasi_12;
            $k_tingkat_capaian_kinerja_prog = round(($k_realisasi_capaian_kinerja_prog/$row_ind_prog->target)*100, 2);
            $rp_tingkat_capaian_kinerja_prog = round(($rp_realisasi_capaian_kinerja_prog/$row_ind_prog->nominal)*100, 2);

            if ($ind_cik_prog->kategori_indikator == 3) {
                $k_realisasi_kinerja_anggaran_prog = abs($ind_cik_lalu_prog->target + ($ind_cik_prog->real_12 - $ind_cik_prog->real_9));
                $rp_realisasi_kinerja_anggaran_prog = abs($ind_cik_lalu_prog->rencana + ($ind_cik_prog->realisasi_12 - $ind_cik_prog->realisasi_9)) ;
            }else{
                $k_realisasi_kinerja_anggaran_prog = abs($ind_cik_prog->real_12-$ind_cik_prog->real_9);
                $rp_realisasi_kinerja_anggaran_prog = abs($ind_cik_prog->realisasi_12 - $ind_cik_prog->realisasi_9);
            }

            if ($ind_cik_prog->status_indikator == 1) {
                $k_tingkat_kinerja_realisasi_prog = (($k_realisasi_kinerja_anggaran_prog/$ind_renstra_prog->target_5)*10000)/100;
            }else{
                $k_tingkat_kinerja_realisasi_prog = (($ind_renstra_prog->target_5+$k_realisasi_kinerja_anggaran_prog)+$k_realisasi_kinerja_anggaran_prog)/$k_realisasi_kinerja_anggaran_prog*100;
            }
            $rp_tingkat_kinerja_realisasi_prog = (($rp_realisasi_kinerja_anggaran_prog/$ind_renstra_prog->nominal_5)*10000)/100;
?>
    <?php if ($key_ind_prog == 0){ ?>
    <?php 
        $total_anggaran_cik_prog = 0;
        $total_realisasi_cik_prog = 0;
        foreach ($ind_prog as $key_ind_prog_2 => $row_ind_prog_2) {
            $ind_renstra_prog_2 = $this->m_evaluasi_renja->get_data_renja_indikator_from_renstra($row_ind_prog_2->indikator);
            $ind_cik_lalu_prog_2 = $this->m_evaluasi_renja->get_data_renja_indikator_from_cik($row_ind_prog_2->indikator, $ta-1, 1);
            $ind_cik_prog_2 = $this->m_evaluasi_renja->get_data_renja_indikator_from_cik($row_ind_prog_2->indikator, $ta, 1);
            $jumlah_from_cik_prog = (($ind_cik_prog_2->real_3+$ind_cik_prog_2->real_6+$ind_cik_prog_2->real_9+$ind_cik_prog_2->real_12)/$row_ind_prog_2->target)*100;

            if ($ind_cik_prog_2->kategori_indikator == 3) {
                $k_realisasi_kinerja_anggaran_prog_2 = abs($ind_cik_lalu_prog_2->target + ($ind_cik_prog_2->real_12 - $ind_cik_prog_2->real_9));
            }else{
                $k_realisasi_kinerja_anggaran_prog_2 = abs($ind_cik_prog_2->real_12-$ind_cik_prog_2->real_9);
            }

            if ($ind_cik_prog_2->status_indikator == 1) {
                $k_tingkat_kinerja_realisasi_prog_2 = (($k_realisasi_kinerja_anggaran_prog_2/$ind_renstra_prog_2->target_5)*10000)/100;
            }else{
                $k_tingkat_kinerja_realisasi_prog_2 = (($ind_renstra_prog_2->target_5+$k_realisasi_kinerja_anggaran_prog_2)+$k_realisasi_kinerja_anggaran_prog_2)/$k_realisasi_kinerja_anggaran_prog_2*100;
            }

            $total_anggaran_cik_prog += $jumlah_from_cik_prog;
            $total_realisasi_cik_prog += $k_tingkat_kinerja_realisasi_prog_2;
        }
        $k_total_anggaran_dari_prog += $total_anggaran_cik_prog/$rowsp_prog;
        $rp_total_anggaran_dari_prog += $rp_tingkat_capaian_kinerja_prog;
        $k_total_realisasi_dari_prog += $total_realisasi_cik_prog/$rowsp_prog;
        $rp_total_realisasi_dari_prog += $rp_tingkat_kinerja_realisasi_prog;
    ?>
        <tr bgcolor="#FF9933">
            <td rowspan="<?php echo $rowsp_prog; ?>"></td>
            <td rowspan="<?php echo $rowsp_prog; ?>"><?php echo $row_urusan->kd_urusan; ?></td>
            <td rowspan="<?php echo $rowsp_prog; ?>"><?php echo $row_bidang->kd_bidang; ?></td>
            <td rowspan="<?php echo $rowsp_prog; ?>"><?php echo $row_prog->kd_program; ?></td>
            <td rowspan="<?php echo $rowsp_prog; ?>"></td>
            <td rowspan="<?php echo $rowsp_prog; ?>"><?php echo $row_prog->nama_prog_or_keg; ?></td>
            <td><?php echo $row_ind_prog->indikator; ?></td>
            <td><?php echo $ind_renstra_prog->target_5.' '.$ind_renstra_prog->satuan_target; ?></td>
            <td></td>
            <td><?php echo (!empty($ind_cik_lalu_prog))?$ind_cik_lalu_prog->target.' '.$ind_cik_lalu_prog->satuan_target:'0.00 '.$ind_renstra_prog->satuan_target; ?></td>
            <td></td>
            <td><?php echo $row_ind_prog->target.' '.$row_ind_prog->satuan_target; ?></td>
            <td rowspan="<?php echo $rowsp_prog; ?>"><?php echo Formatting::currency($row_ind_prog->sum_nominal);?></td>
            <td>
                <?php echo (!empty($ind_cik_prog->real_3))?abs($ind_cik_prog->real_3).' '.$ind_cik_prog->satuan_target:'0.00 '.$row_ind_prog->satuan_target; ?>
            </td>
            <td rowspan="<?php echo $rowsp_prog; ?>">
                <?php echo (!empty($ind_cik_prog->realisasi_3))?Formatting::currency(abs($ind_cik_prog->realisasi_3)):'0'; ?>
            </td>
            <td>
                <?php echo (!empty($ind_cik_prog->real_6))?abs($ind_cik_prog->real_6-$ind_cik_prog->real_3).' '.$ind_cik_prog->satuan_target:'0.00 '.$row_ind_prog->satuan_target; ?>
            </td>
            <td rowspan="<?php echo $rowsp_prog; ?>">
                <?php echo (!empty($ind_cik_prog->realisasi_6))?Formatting::currency(abs($ind_cik_prog->realisasi_6-$ind_cik_prog->realisasi_3)):'0'; ?>
            </td>
            <td>
                <?php echo (!empty($ind_cik_prog->real_9))?abs($ind_cik_prog->real_9-$ind_cik_prog->real_6).' '.$ind_cik_prog->satuan_target:'0.00 '.$row_ind_prog->satuan_target; ?>
            </td>
            <td rowspan="<?php echo $rowsp_prog; ?>">
                <?php echo (!empty($ind_cik_prog->realisasi_9))?Formatting::currency(abs($ind_cik_prog->realisasi_9-$ind_cik_prog->realisasi_6)):'0'; ?>
            </td>
            <td>
                <?php echo (!empty($ind_cik_prog->real_12))?abs($ind_cik_prog->real_12-$ind_cik_prog->real_9).' '.$ind_cik_prog->satuan_target:'0.00 '.$row_ind_prog->satuan_target; ?>
            </td>
            <td rowspan="<?php echo $rowsp_prog; ?>">
                <?php echo (!empty($ind_cik_prog->realisasi_12))?Formatting::currency(abs($ind_cik_prog->realisasi_12-$ind_cik_prog->realisasi_9)):'0'; ?>
            </td>
            <td><?= number_format($k_realisasi_capaian_kinerja_prog, 2); ?></td>
            <td rowspan="<?php echo $rowsp_prog; ?>"><?= Formatting::currency($rp_realisasi_capaian_kinerja_prog); ?></td>
            <td><?= number_format($k_tingkat_capaian_kinerja_prog, 2); ?> %</td>
            <td rowspan="<?php echo $rowsp_prog; ?>"><?= number_format($rp_tingkat_capaian_kinerja_prog, 2); ?> %</td>
            <td><?php echo (!empty($k_realisasi_kinerja_anggaran_prog))?number_format($k_realisasi_kinerja_anggaran_prog, 2):'0.00'; ?></td>
            <td rowspan="<?php echo $rowsp_prog; ?>"><?= Formatting::currency($rp_realisasi_kinerja_anggaran_prog); ?></td>
            <td><?php echo (!empty($k_realisasi_kinerja_anggaran_prog))?number_format($k_tingkat_kinerja_realisasi_prog, 2):'0.00'; ?></td>
            <td rowspan="<?php echo $rowsp_prog; ?>"><?= number_format($rp_tingkat_kinerja_realisasi_prog, 2); ?></td>
            <td colspan="12"></td>
        </tr>
    <?php }else{ ?>
        <tr bgcolor="#FF9933">
            <td><?php echo $row_ind_prog->indikator; ?></td>
            <td><?php echo $ind_renstra_prog->target_5.' '.$ind_renstra_prog->satuan_target; ?></td>
            <td></td>
            <td><?php echo (!empty($ind_cik_lalu_prog))?$ind_cik_lalu_prog->target.' '.$ind_cik_lalu_prog->satuan_target:'0.00 '.$ind_renstra_prog->satuan_target; ?></td>
            <td></td>
            <td><?php echo $row_ind_prog->target.' '.$row_ind_prog->satuan_target; ?></td>
            <td>
                <?php echo (!empty($ind_cik_prog->real_3))?abs($ind_cik_prog->real_3).' '.$ind_cik_prog->satuan_target:'0.00 '.$row_ind_prog->satuan_target; ?>
            </td>
            <td>
                <?php echo (!empty($ind_cik_prog->real_6))?abs($ind_cik_prog->real_6-$ind_cik_prog->real_3).' '.$ind_cik_prog->satuan_target:'0.00 '.$row_ind_prog->satuan_target; ?>
            </td>
            <td>
                <?php echo (!empty($ind_cik_prog->real_9))?abs($ind_cik_prog->real_9-$ind_cik_prog->real_6).' '.$ind_cik_prog->satuan_target:'0.00 '.$row_ind_prog->satuan_target; ?>
            </td>
            <td>
                <?php echo (!empty($ind_cik_prog->real_12))?abs($ind_cik_prog->real_12-$ind_cik_prog->real_9).' '.$ind_cik_prog->satuan_target:'0.00 '.$row_ind_prog->satuan_target; ?>
            </td>
            <td><?= number_format($k_realisasi_capaian_kinerja_prog, 2); ?></td>
            <td><?= $k_tingkat_capaian_kinerja_prog; ?> %</td>
            <td><?php echo (!empty($k_realisasi_kinerja_anggaran_prog))?number_format($k_realisasi_kinerja_anggaran_prog, 2):'0.00'; ?></td>
            <td><?php echo (!empty($k_realisasi_kinerja_anggaran_prog))?number_format($k_tingkat_kinerja_realisasi_prog, 2):'0.00'; ?></td>
            <td colspan="12"></td>
        </tr>
    <?php } ?>
<?php } ?>


<?php 
    $total_dari_keg = count($kegiatan);
    $k_total_anggaran_dari_keg = 0;
    $rp_total_anggaran_dari_keg = 0;
    $k_total_realisasi_dari_keg = 0;
    $rp_total_realisasi_dari_keg = 0;
    foreach ($kegiatan as $row_keg) {
        $ind_keg = $this->m_evaluasi_renja->get_data_renja_indikator_prog_keg($row_keg->id);
        $rowsp_keg = count($ind_keg);

        foreach ($ind_keg as $key_ind_keg => $row_ind_keg) {
            $ind_renstra_keg = $this->m_evaluasi_renja->get_data_renja_indikator_from_renstra($row_ind_keg->indikator);
            $ind_cik_lalu_keg = $this->m_evaluasi_renja->get_data_renja_indikator_from_cik($row_ind_keg->indikator, $ta-1, 2);
            $ind_cik_keg = $this->m_evaluasi_renja->get_data_renja_indikator_from_cik($row_ind_keg->indikator, $ta, 2);

            $k_realisasi_capaian_kinerja_keg = round($ind_cik_keg->real_3+$ind_cik_keg->real_6+$ind_cik_keg->real_9+$ind_cik_keg->real_12, 2);
            $rp_realisasi_capaian_kinerja_keg = $ind_cik_keg->realisasi_3+$ind_cik_keg->realisasi_6+$ind_cik_keg->realisasi_9+$ind_cik_keg->realisasi_12;
            $k_tingkat_capaian_kinerja_keg = round(($k_realisasi_capaian_kinerja_keg/$row_ind_keg->target)*100, 2);
            $rp_tingkat_capaian_kinerja_keg = round(($rp_realisasi_capaian_kinerja_keg/$row_ind_keg->nominal)*100, 2);

            if ($ind_cik_keg->kategori_indikator == 3) {
                $k_realisasi_kinerja_anggaran_keg = abs($ind_cik_lalu_keg->target + ($ind_cik_keg->real_12 - $ind_cik_keg->real_9));
                $rp_realisasi_kinerja_anggaran_keg = abs($ind_cik_lalu_keg->rencana + ($ind_cik_keg->realisasi_12 - $ind_cik_keg->realisasi_9)) ;
            }else{
                $k_realisasi_kinerja_anggaran_keg = abs($ind_cik_keg->real_12-$ind_cik_keg->real_9);
                $rp_realisasi_kinerja_anggaran_keg = abs($ind_cik_keg->realisasi_12 - $ind_cik_keg->realisasi_9);
            }

            if ($ind_cik_keg->status_indikator == 1) {
                $k_tingkat_kinerja_realisasi_keg = (($k_realisasi_kinerja_anggaran_keg/$ind_renstra_keg->target_5)*10000)/100;
            }else{
                $k_tingkat_kinerja_realisasi_keg = (($ind_renstra_keg->target_5+$k_realisasi_kinerja_anggaran_keg)+$k_realisasi_kinerja_anggaran_keg)/$k_realisasi_kinerja_anggaran_keg*100;
            }
            $rp_tingkat_kinerja_realisasi_keg = (($rp_realisasi_kinerja_anggaran_keg/$ind_renstra_keg->nominal_5)*10000)/100;
?>
    <?php if ($key_ind_keg == 0){ ?>
    <?php 
        $total_anggaran_cik_keg = 0;
        $total_realisasi_cik_keg = 0;
        foreach ($ind_keg as $key_ind_keg_2 => $row_ind_keg_2) {
            $ind_renstra_keg_2 = $this->m_evaluasi_renja->get_data_renja_indikator_from_renstra($row_ind_keg_2->indikator);
            $ind_cik_lalu_keg_2 = $this->m_evaluasi_renja->get_data_renja_indikator_from_cik($row_ind_keg_2->indikator, $ta-1, 2);
            $ind_cik_keg_2 = $this->m_evaluasi_renja->get_data_renja_indikator_from_cik($row_ind_keg_2->indikator, $ta, 2);
            $jumlah_from_cik_keg = (($ind_cik_keg_2->real_3+$ind_cik_keg_2->real_6+$ind_cik_keg_2->real_9+$ind_cik_keg_2->real_12)/$row_ind_keg_2->target)*100;

            if ($ind_cik_keg_2->kategori_indikator == 3) {
                $k_realisasi_kinerja_anggaran_keg_2 = abs($ind_cik_lalu_keg_2->target + ($ind_cik_keg_2->real_12 - $ind_cik_keg_2->real_9));
            }else{
                $k_realisasi_kinerja_anggaran_keg_2 = abs($ind_cik_keg_2->real_12-$ind_cik_keg_2->real_9);
            }

            if ($ind_cik_keg_2->status_indikator == 1) {
                $k_tingkat_kinerja_realisasi_keg_2 = (($k_realisasi_kinerja_anggaran_keg_2/$ind_renstra_keg_2->target_5)*10000)/100;
            }else{
                $k_tingkat_kinerja_realisasi_keg_2 = (($ind_renstra_keg_2->target_5+$k_realisasi_kinerja_anggaran_keg_2)+$k_realisasi_kinerja_anggaran_keg_2)/$k_realisasi_kinerja_anggaran_keg_2*100;
            }

            $total_anggaran_cik_keg += $jumlah_from_cik_keg;
            $total_realisasi_cik_keg += $k_tingkat_kinerja_realisasi_keg_2;
        }
        $k_total_anggaran_dari_keg += $total_anggaran_cik_keg/$rowsp_keg;
        $rp_total_anggaran_dari_keg += $rp_tingkat_capaian_kinerja_keg;
        $k_total_realisasi_dari_keg += $total_realisasi_cik_keg/$rowsp_keg;
        $rp_total_realisasi_dari_keg += $rp_tingkat_kinerja_realisasi_keg;
    ?>
        <tr>
            <td rowspan="<?php echo $rowsp_keg; ?>"><?php echo $ind_cik_lalu_keg->rencana; ?></td>
            <td rowspan="<?php echo $rowsp_keg; ?>"><?php echo $row_urusan->kd_urusan; ?></td>
            <td rowspan="<?php echo $rowsp_keg; ?>"><?php echo $row_bidang->kd_bidang; ?></td>
            <td rowspan="<?php echo $rowsp_keg; ?>"><?php echo $row_prog->kd_program; ?></td>
            <td rowspan="<?php echo $rowsp_keg; ?>"><?php echo $row_keg->kd_kegiatan; ?></td>
            <td rowspan="<?php echo $rowsp_keg; ?>"><?php echo $row_keg->nama_prog_or_keg; ?></td>
            <td><?php echo $row_ind_keg->indikator; ?></td>
            <td><?php echo $ind_renstra_keg->target_5.' '.$ind_renstra_keg->satuan_target; ?></td>
            <td></td>
            <td><?php echo (!empty($ind_cik_lalu_keg))?$ind_cik_lalu_keg->target.' '.$ind_cik_lalu_keg->satuan_target:'0.00 '.$ind_renstra_keg->satuan_target; ?></td>
            <td></td>
            <td><?php echo $row_ind_keg->target.' '.$row_ind_keg->satuan_target; ?></td>
            <td rowspan="<?php echo $rowsp_keg; ?>"><?php echo Formatting::currency($row_ind_keg->nominal);?></td>
            <td>
                <?php echo (!empty($ind_cik_keg->real_3))?abs($ind_cik_keg->real_3).' '.$ind_cik_keg->satuan_target:'0.00 '.$row_ind_keg->satuan_target; ?>
            </td>
            <td rowspan="<?php echo $rowsp_keg; ?>">
                <?php echo (!empty($ind_cik_keg->realisasi_3))?Formatting::currency(abs($ind_cik_keg->realisasi_3)):'0'; ?>
            </td>
            <td>
                <?php echo (!empty($ind_cik_keg->real_6))?abs($ind_cik_keg->real_6-$ind_cik_keg->real_3).' '.$ind_cik_keg->satuan_target:'0.00 '.$row_ind_keg->satuan_target; ?>
            </td>
            <td rowspan="<?php echo $rowsp_keg; ?>">
                <?php echo (!empty($ind_cik_keg->realisasi_6))?Formatting::currency(abs($ind_cik_keg->realisasi_6-$ind_cik_keg->realisasi_3)):'0'; ?>
            </td>
            <td>
                <?php echo (!empty($ind_cik_keg->real_9))?abs($ind_cik_keg->real_9-$ind_cik_keg->real_6).' '.$ind_cik_keg->satuan_target:'0.00 '.$row_ind_keg->satuan_target; ?>
            </td>
            <td rowspan="<?php echo $rowsp_keg; ?>">
                <?php echo (!empty($ind_cik_keg->realisasi_9))?Formatting::currency(abs($ind_cik_keg->realisasi_9-$ind_cik_keg->realisasi_6)):'0'; ?>
            </td>
            <td>
                <?php echo (!empty($ind_cik_keg->real_12))?abs($ind_cik_keg->real_12-$ind_cik_keg->real_9).' '.$ind_cik_keg->satuan_target:'0.00 '.$row_ind_keg->satuan_target; ?>
            </td>
            <td rowspan="<?php echo $rowsp_keg; ?>">
                <?php echo (!empty($ind_cik_keg->realisasi_12))?Formatting::currency(abs($ind_cik_keg->realisasi_12-$ind_cik_keg->realisasi_9)):'0'; ?>
            </td>
            <td><?= number_format($k_realisasi_capaian_kinerja_keg, 2); ?></td>
            <td rowspan="<?php echo $rowsp_keg; ?>"><?= Formatting::currency($rp_realisasi_capaian_kinerja_keg); ?></td>
            <td><?= number_format($k_tingkat_capaian_kinerja_keg, 2); ?> %</td>
            <td rowspan="<?php echo $rowsp_keg; ?>"><?= number_format($rp_tingkat_capaian_kinerja_keg, 2); ?> %</td>
            <td><?php echo (!empty($k_realisasi_kinerja_anggaran_keg))?number_format($k_realisasi_kinerja_anggaran_keg, 2):'0.00'; ?></td>
            <td rowspan="<?php echo $rowsp_keg; ?>"><?= Formatting::currency($rp_realisasi_kinerja_anggaran_keg); ?></td>
            <td><?php echo (!empty($k_realisasi_kinerja_anggaran_keg))?number_format($k_tingkat_kinerja_realisasi_keg, 2):'0.00'; ?></td>
            <td rowspan="<?php echo $rowsp_keg; ?>"><?= number_format($rp_tingkat_kinerja_realisasi_keg, 2); ?></td>
            <td colspan="12"></td>
        </tr>
    <?php }else{ ?>
        <tr>
            <td><?php echo $row_ind_keg->indikator; ?></td>
            <td><?php echo $ind_renstra_keg->target_5.' '.$ind_renstra_keg->satuan_target; ?></td>
            <td></td>
            <td><?php echo (!empty($ind_cik_lalu_keg))?$ind_cik_lalu_keg->target.' '.$ind_cik_lalu_keg->satuan_target:'0.00 '.$ind_renstra_keg->satuan_target; ?></td>
            <td></td>
            <td><?php echo $row_ind_keg->target.' '.$row_ind_keg->satuan_target; ?></td>
            <td>
                <?php echo (!empty($ind_cik_keg->real_3))?abs($ind_cik_keg->real_3).' '.$ind_cik_keg->satuan_target:'0.00 '.$row_ind_keg->satuan_target; ?>
            </td>
            <td>
                <?php echo (!empty($ind_cik_keg->real_6))?abs($ind_cik_keg->real_6-$ind_cik_keg->real_3).' '.$ind_cik_keg->satuan_target:'0.00 '.$row_ind_keg->satuan_target; ?>
            </td>
            <td>
                <?php echo (!empty($ind_cik_keg->real_9))?abs($ind_cik_keg->real_9-$ind_cik_keg->real_6).' '.$ind_cik_keg->satuan_target:'0.00 '.$row_ind_keg->satuan_target; ?>
            </td>
            <td>
                <?php echo (!empty($ind_cik_keg->real_12))?abs($ind_cik_keg->real_12-$ind_cik_keg->real_9).' '.$ind_cik_keg->satuan_target:'0.00 '.$row_ind_keg->satuan_target; ?>
            </td>
            <td><?= number_format($k_realisasi_capaian_kinerja_keg, 2); ?></td>
            <td><?= $k_tingkat_capaian_kinerja_keg; ?> %</td>
            <td><?php echo (!empty($k_realisasi_kinerja_anggaran_keg))?number_format($k_realisasi_kinerja_anggaran_keg, 2):'0.00'; ?></td>
            <td><?php echo (!empty($k_realisasi_kinerja_anggaran_keg))?number_format($k_tingkat_kinerja_realisasi_keg, 2):'0.00'; ?></td>
            <td colspan="12"></td>
        </tr>
    <?php }} ?>
    <?php 
        $k_hasil_anggaran_seluruh_keg = round($k_total_anggaran_dari_keg/$total_dari_keg, 2);
        $rp_hasil_anggaran_seluruh_keg = round($rp_total_anggaran_dari_keg/$total_dari_keg, 2);
        $k_hasil_realisasi_seluruh_keg = round($k_total_realisasi_dari_keg/$total_dari_keg, 2);
        $rp_hasil_realisasi_seluruh_keg = round($rp_total_realisasi_dari_keg/$total_dari_keg, 2);
    ?>
<?php } ?>
<tr bgcolor="#ffff30">
    <th colspan="23">Total Rata-rata Capaian Kinerja dan Anggaran Dari Seluruh Kegiatan (%)</th>
    <th><?php echo $k_hasil_anggaran_seluruh_keg; ?></th>
    <th><?php echo $rp_hasil_anggaran_seluruh_keg; ?></th>
    <th></th>
    <th></th>
    <th><?php echo $k_hasil_realisasi_seluruh_keg; ?></th>
    <th><?php echo $rp_hasil_realisasi_seluruh_keg; ?></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
</tr>
<?php }} ?>
<?php 
    $k_hasil_anggaran_seluruh_prog = round($k_total_anggaran_dari_prog/$total_dari_prog, 2);
    $rp_hasil_anggaran_seluruh_prog = round($rp_total_anggaran_dari_prog/$total_dari_prog, 2);
    $k_hasil_realisasi_seluruh_prog = round($k_total_realisasi_dari_prog/$total_dari_prog, 2);
    $rp_hasil_realisasi_seluruh_prog = round($rp_total_realisasi_dari_prog/$total_dari_prog, 2);
?>
<?php } ?>

<tr bgcolor="#ffb770">
    <th colspan="23">Total Rata-rata Capaian Kinerja dan Anggaran Dari Seluruh Program (%)</th>
    <th><?php echo $k_hasil_anggaran_seluruh_prog; ?></th>
    <th><?php echo $rp_hasil_anggaran_seluruh_prog; ?></th>
    <th></th>
    <th></th>
    <th><?php echo $k_hasil_realisasi_seluruh_prog; ?></th>
    <th><?php echo $rp_hasil_realisasi_seluruh_prog; ?></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
</tr>
<tr bgcolor="#ffb770">
    <th colspan="23">Predikat Kinerja Dari Seluruh Program</th>
    <th>-</th>
    <th>-</th>
    <th></th>
    <th></th>
    <th>-</th>
    <th>-</th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
</tr>