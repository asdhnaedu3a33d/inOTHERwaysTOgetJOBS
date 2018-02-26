<?php
	if(!empty($ta))
	{
		$tahun_usulan = $ta;
	}
	else
	{
		$tahun_usulan = $this->session->userdata('t_anggaran_aktif');
	}
?>
<thead>
	<tr>
		<th colspan="4">Kode</th>
		<th >Program dan Kegiatan</th>
		<th >Jenis Pekerjaan</th>
		<th >Volume</th>
		<th >Satuan</th>
		<th >Jumlah Dana (Rp.)</th>
        <th >Nama Desa</th>
        <th >SKPD Penanggungjawab</th>
        <th >Asal Usulan</th>
        <th >Nama Dewan</th>
	</tr>
</thead>
<tbody>
<?php
	foreach ($urusan as $row_urusan) {
		if($row_urusan->nama_urusan!=NULL){
			$nama_urusan = $row_urusan->nama_urusan;
		}
		else{
			$nama_urusan = "Kode Urusan Belum Ditentukan";
		}
?>
		<tr bgcolor="#78cbfd">
			<td><?php echo $row_urusan->kd_urusan;?></td>
			<td></td>
			<td></td>
			<td></td>
			<td colspan="4">
				<strong><?php echo strtoupper($nama_urusan); ?></strong>
			</td>
			<td align="right"><?php echo Formatting::currency($row_urusan->sum_jumlah_dana); ?></td>
			<td colspan="4"></td>

		</tr>
<?php
		if($row_urusan->kd_urusan!=0){
			$kode_urusan = $row_urusan->kd_urusan;
		}
		else{
			$kode_urusan = 0;
		}
		
		
		$bidang = $this->m_usulan_terakomodir->get_bidang_usulan($tahun_usulan,$kode_urusan);
		foreach ($bidang as $row_bidang) {
			if($row_bidang->nama_bidang!=NULL){
			$nama_bidang = $row_bidang->nama_bidang;
			}
			else{
				$nama_bidang = "Kode Bidang Belum Ditentukan";
			}
?>
			<tr bgcolor="#00FF33">
				<td><?php echo $row_urusan->kd_urusan; ?></td>
				<td><?php echo $row_bidang->kd_bidang; ?></td>
				<td></td>
				<td></td>
				<td colspan="4">
					<strong><?php echo strtoupper($nama_bidang); ?></strong>
				</td>
				<td align="right"><?php echo Formatting::currency($row_bidang->sum_jumlah_dana); ?></td>
				<td colspan="4"></td>
			</tr>
<?php
			if($row_bidang->kd_bidang!=0){
			$kode_bidang = $row_bidang->kd_bidang;
			}
			else{
				$kode_bidang = 0;
			}
			$program = $this->m_usulan_terakomodir->get_program_usulan($tahun_usulan,$kode_urusan,$kode_bidang);
			foreach ($program as $row_program){
				if($row_program->nama_program!=NULL){
					$nama_program = $row_program->nama_program;
				}
				else{
					$nama_program = "Kode Program Belum Ditentukan";
				}
?>
				<tr bgcolor="#FF8000">
                    <td><?php echo $row_urusan->kd_urusan; ?></td>
                    <td><?php echo $row_bidang->kd_bidang; ?></td>
                    <td><?php echo $row_program->kd_program; ?></td>
                    <td></td>
                    <td colspan="4">
                        <strong><?php echo strtoupper($nama_program); ?></strong>
                    </td>
                    <td align="right"><?php echo Formatting::currency($row_program->sum_jumlah_dana); ?></td>
                    <td colspan="4"></td>
                </tr>
<?php
				if($row_program->kd_program!=0){
				$kode_program = $row_program->kd_program;
				}
				else{
					$kode_program = 0;
				}
				$kegiatan = $this->m_usulan_terakomodir->get_kegiatan_usulan($tahun_usulan,$kode_urusan,$kode_bidang,$kode_program);
				foreach ($kegiatan as $row_kegiatan){
					if($row_kegiatan->nama_kegiatan!=NULL){
						$nama_kegiatan = $row_kegiatan->nama_kegiatan;
					}
					else{
						$nama_kegiatan = "Kode Kegiatan Belum Ditentukan";
		}
?>
					<tr >
                        <td><?php echo $row_urusan->kd_urusan; ?></td>
                        <td><?php echo $row_bidang->kd_bidang; ?></td>
                        <td><?php echo $row_program->kd_program; ?></td>
                        <td><?php echo $row_kegiatan->kd_kegiatan; ?></td>
                        <td><?php echo $nama_kegiatan; ?></td>
                        <td><?php echo $row_kegiatan->jenis_pekerjaan; ?></td>
                        <td><?php echo $row_kegiatan->volume; ?></td>
                        <td><?php echo $row_kegiatan->satuan; ?></td>
                        <td align="right"><?php echo Formatting::currency($row_kegiatan->jumlah_dana); ?></td>
                        <td><?php echo $row_kegiatan->nama_desa; ?></td>
                        <td><?php echo $row_kegiatan->nama_skpd; ?></td>
                        <td><?php echo $row_kegiatan->asal_usulan; ?></td>
                        <td><?php echo $row_kegiatan->nama_dewan; ?></td>
                    </tr>
<?php
				}
			}
		}
	}
?>
</tbody>