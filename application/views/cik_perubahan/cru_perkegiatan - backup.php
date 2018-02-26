					<tr>
						<td id="indikator_frame_kegiatan">
							<div style="width: 100%; margin-top: 10px;">
								<div style="width: 100%;">
									<textarea class="common indikator_val" name="indikator_kinerja[]" style="width:99%" readonly="readonly"></textarea>
							  </div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr style="width:100%">
											<td>Target Rencana</td>
                                            <td><input style="width: 100%;" type="text" class="target" name="target[]" value="11" readonly="readonly"></td>
                                            <td  bgcolor="#00FF33">Target Realisasi</td>
                                            <td  bgcolor="#00FF33"><input style="width: 100%;" type="text" class="real" name="real_[]" value="12"></td>
                                        </tr>
									</table>
								</div>
							</div>
							<div style="width: 100%; margin-top: 10px;">
								<div style="width: 100%;">
									<textarea class="common indikator_val" name="indikator_kinerja[]" style="width:99%" readonly="readonly"></textarea>
							  </div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr style="width:100%">
											<td>Target Rencana</td>
                                            <td><input style="width: 100%;" type="text" class="target" name="target[]" value="21" readonly="readonly"></td>
                                            <td  bgcolor="#00FF33">Target Realisasi</td>
                                            <td  bgcolor="#00FF33"><input style="width: 100%;" type="text" class="real" name="real_[]" value="32"></td>
                                        </tr>
									</table>
								</div>
							</div>
							<div style="width: 100%; margin-top: 10px;">
								<div style="width: 100%;">
									<textarea class="common indikator_val" name="indikator_kinerja[]" style="width:99%" readonly="readonly"></textarea>
							  </div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr style="width:100%">
											<td>Target Rencana</td>
                                            <td><input style="width: 100%;" type="text" class="target" name="target[]" value="40" readonly="readonly"></td>
                                            <td  bgcolor="#00FF33">Target Realisasi</td>
                                            <td  bgcolor="#00FF33"><input style="width: 100%;" type="text" class="real" name="real_[]" value="65"></td>
                                        </tr>
									</table>
								</div>
							</div>
						</td>
					</tr>					
					<tr style="background-color: white;">
						<td></td>
						<td colspan="2"><hr></td>
					</tr>
                    <tr>
                    	<td>&nbsp;&nbsp;Capaian IK</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;
                        <input id="tot_kinerja" type="text" name="capaian_" value="" readonly="readonly"/> %</td>
                    </tr>					
					<tr>
						<td>&nbsp;&nbsp;Rencana Anggaran</td>
						<td>Rp. <input type="text" name="rencana" value="" readonly="readonly"/>
                        </td>
					</tr>
                    <tr>
						<td bgcolor="#00FF33">&nbsp;&nbsp;Realisasi Anggaran </td>
						<td bgcolor="#00FF33">Rp. <input type="text" name="realisasi_" value=""/>
                        </td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>*** Silahkan inputkan data CIK Perubahan yang sesuai di dalam kolom berwarna hijau</em></p>
	<footer>
		<div class="submit_link">    			
  			<input id="simpan" type="button" value="Simpan">
		</div> 
	</footer>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(".real").keyup(function(){
			hitung_cik();
		});

		function hitung_cik()
		{		
			var real, target, kinerja, tot_kinerja=0, count=0;
			$(".target").each(function(){
				count++;
				target = $(this).val();
				real = $(this).parent().parent().find(".real").val();
				kinerja = Math.round((real/target)*10000)/100;
				tot_kinerja+=kinerja;				
			});

			tot_kinerja = Math.round((tot_kinerja/count)*100)/100;
			$("#tot_kinerja").val(tot_kinerja);			
		}

		hitung_cik();
	});
</script>