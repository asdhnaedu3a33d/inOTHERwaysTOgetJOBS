<div class="shortcuts">
	<?php if($id_laporan == 1) {?>
        <a href="<?php echo site_url('guest/report?report=renstra'); ?>" class="shortcut"><i class="shortcut-icon icon-list-alt"></i><span class="shortcut-label">Renstra</span></a>
        <a href="<?php echo site_url('guest/report?report=rpjmd'); ?>" class="shortcut"><i class="shortcut-icon icon-th"></i><span class="shortcut-label">RPJMD</span></a>
	<?php }
    elseif($id_laporan == 2) {?>
        <a href="<?php echo site_url('guest/report?report=renja'); ?>" class="shortcut"><i class="shortcut-icon icon-th-list"></i><span class="shortcut-label">Renja</span></a>
        <a href="<?php echo site_url('guest/report?report=renja_perubahan'); ?>" class="shortcut"><i class="shortcut-icon icon-th-list"></i><span class="shortcut-label">Renja Perubahan</span></a>
        <a href="<?php echo site_url('guest/report?report=rkpd'); ?>" class="shortcut"><i class="shortcut-icon icon-list-alt"></i><span class="shortcut-label">RKPD</span></a>
    <?php } 
    elseif($id_laporan == 3) {?>
    	<a href="<?php echo site_url('guest/report?report=rka'); ?>" class="shortcut"><i class="shortcut-icon icon-list"></i><span class="shortcut-label">RKA</span></a>
        <a href="<?php echo site_url('guest/report?report=rka_perubahan'); ?>" class="shortcut"><i class="shortcut-icon icon-list"></i><span class="shortcut-label">RKA Perubahan</span></a>
        <a href="<?php echo site_url('guest/report?report=dpa'); ?>" class="shortcut"><i class="shortcut-icon icon-file"></i><span class="shortcut-label">DPA</span></a>
        <a href="<?php echo site_url('guest/report?report=dpa_perubahan'); ?>" class="shortcut"><i class="shortcut-icon icon-file"></i><span class="shortcut-label">DPA Perubahan</span></a>
	<?php } 
	elseif($id_laporan == 4) {?>
    	<a href="<?php echo site_url('guest/report?report=musrenbangdes'); ?>" class="shortcut"><i class="shortcut-icon icon-th-large"></i><span class="shortcut-label">Rekap. Desa</span></a>
        <a href="<?php echo site_url('guest/report?report=rekap_kecamatan'); ?>" class="shortcut"><i class="shortcut-icon icon-briefcase"></i><span class="shortcut-label">Rekap. Kecamatan</span></a>
        <a href="<?php echo site_url('guest/report?report=rekap_skpd'); ?>" class="shortcut"><i class="shortcut-icon icon-tasks"></i><span class="shortcut-label">Rekap. SKPD</span></a>
        <a href="<?php echo site_url('guest/report?report=pokir'); ?>" class="shortcut"><i class="shortcut-icon icon-tasks"></i><span class="shortcut-label">Rekap. Pokir DPR</span></a>
        <a href="<?php echo site_url('guest/report?report=temuwirasa'); ?>" class="shortcut"><i class="shortcut-icon icon-tasks"></i><span class="shortcut-label">Rekap. Temuwirasa</span></a>
    <?php } 
	elseif($id_laporan == 5) {?>
    	<a href="<?php echo site_url('guest/report?report=kendali_belanja'); ?>" class="shortcut"><i class="shortcut-icon icon-align-left"></i><span class="shortcut-label">Kendali Belanja</span></a>
    <?php } 
	elseif($id_laporan == 6) {?>
    	<a href="<?php echo site_url('guest/report?report=cik'); ?>" class="shortcut"><i class="shortcut-icon icon-book"></i><span class="shortcut-label">CIK</span></a>
        <a href="<?php echo site_url('guest/report?report=cik_perubahan'); ?>" class="shortcut"><i class="shortcut-icon icon-book"></i><span class="shortcut-label">CIK Perubahan</span></a>
        <a href="<?php echo site_url('guest/report?report=evaluasi_renja'); ?>" class="shortcut"><i class="shortcut-icon icon-folder-close"></i><span class="shortcut-label">Evaluasi Renja</span></a>
        <a href="<?php echo site_url('guest/report?report=evaluasi_rkpd'); ?>" class="shortcut"><i class="shortcut-icon icon-folder-open"></i><span class="shortcut-label">Evaluasi RKPD</span></a>
    <?php } ?>
</div>