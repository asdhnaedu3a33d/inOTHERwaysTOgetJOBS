<?php
if (!empty($veri)) {	
    foreach ($veri as $row) {
?>
        <tr>
            <td>Mengirim <?= $row->jumlah ?> Data Evaluasi Renja <?= $row->nama_status ?> pada Triwulan <?= $row->triwulan ?>.</td>
            <td align="center"><?= $row->triwulan ?></td>
            <td align="center"><?= $row->jumlah ?></td>
            <td align="center"><a href="javascript:void(0)" tw="<?= $row->triwulan ?>" st="<?= $row->status ?>" class="send_btn icon2-email_go" title="Kirim evaluasi renja"></a></td>
        </tr>
<?php
    }
}else{
?>
	<tr>
		<td colspan="4">Tidak ada data yang dapat dikirim untuk proses verifikasi.</td>
	</tr>
<?php
}
?>                