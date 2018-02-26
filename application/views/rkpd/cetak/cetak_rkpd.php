<?php 
	if(!empty($ta))
	{
		$tahun_anggaran = $ta;
	}
	else
	{
		$tahun_anggaran = $this->session->userdata('t_anggaran_aktif');
	}
?>
<thead>
	<tr>
		<th rowspan="2" colspan="4">Kode</th>
		<th rowspan="2">Program dan Kegiatan</th>
		<th rowspan="2">Indikator Kinerja Program/Kegiatan</th>
		<th colspan="3">Rencana Tahun <?php echo $tahun_anggaran?></th>
		<th rowspan="2">Catatan</th>
        <th rowspan="2">Keterangan</th>
	</tr>
	<tr>				
		<th >Lokasi</th>
		<th >Target Capaian Kinerja</th>
		<th >Kebutuhan Dana/Pagu Indikatif</th>	
	</tr>
</thead>
<tbody>
</tbody>