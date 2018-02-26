<table class="fcari" width="800px">
	<thead>
		<tr>
			<th>Aksi</th>
			<th>Tanggal</th>						
			<th>User</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Pembuatan Usulan</td>
			<td align="center"><?php echo (!empty($usulanpro->created_date))?date_format(date_create($usulanpro->created_date), 'd-m-Y H:i:s'):"-"; ?></td>
			<td align="center"><?php echo (!empty($usulanpro->created_by))?$usulanpro->created_by:""; ?></td>
		</tr>
		<tr>
			<td>Perubahan Terakhir Usulan</td>
			<td align="center"><?php echo (!empty($usulanpro->changed_date))?date_format(date_create($usulanpro->changed_date), 'd-m-Y H:i:s'):"-"; ?></td>			
			<td align="center"><?php echo (!empty($usulanpro->changed_by))?$usulanpro->changed_by:""; ?></td>
		</tr>
	</tbody>
</table>