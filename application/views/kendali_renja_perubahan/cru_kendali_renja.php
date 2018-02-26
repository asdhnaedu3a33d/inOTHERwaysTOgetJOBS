<script type="text/javascript">
	$('form#program').validate({
		rules: {				
			kesesuaian : {
				required : true,			
			},
			hasil_kendali : {
				required : true,			
			},
			tindak_lanjut : {
				required : true,			
			},
			hasil_tl : {
				required : true,			
			}
		},
		ignore: ":hidden:not(select)"
	});	
	
	$("#simpan").click(function(){
		
	    var valid = $("form#program").valid();
	    if (valid) {
	    	$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
	    	
	    	$.ajax({
				type: "POST",
				url: $("form#program").attr("action"),
				data: $("form#program").serialize(),
				dataType: "json",
				success: function(msg){
					if (msg.success==1) {														
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});							
						$.facebox.close();
						location.reload();						
						
					};						
				}
			});
	    };
	});

</script>

<div style="width: 900px">
	<header>
		<h3 style="padding:20px">Tambah Data  Kendali Renja</h3>
	</header>
	<div class="module_content">
    <form action="<?php echo site_url('kendali_perubahan/save_kendali_renja');?>" method="POST" name="program" id="program" 
     accept-charset="UTF-8" enctype="multipart/form-data" >
    <input type="hidden" name="id_skpd" value="<?php echo $this->session->userdata("id_skpd"); ?>" />
    <input type="hidden" name="tahun" value="<?php echo $this->m_settings->get_tahun_anggaran(); ?>" /> 
    <input type="hidden" name="id" value="<?php if(!empty($id_kendali)){echo $id_kendali;} ?>" />
    <table class="fcari" width="99.8%">
        <tbody>
            <tr>
                <td width="20%">Kesesuaian</td>
                <td width="80%"><input type="radio" name="kesesuaian" value="1"/>Ya, Sesuai<br />
                                <input type="radio" name="kesesuaian" value="2"/>Tidak Sesuai
                </td>
            </tr>					
            <tr>
                <td>Hasil Pengendalian</td>
                <td>
                <textarea class="common" name="hasil_kendali" rows="4">
                <?php echo (!empty($kendali->hasil_kendali))? $kendali->hasil_kendali:''; ?></textarea>
                </td>
            </tr>
            <tr>
                <td>Tindak Lanjut</td>
                <td>
                <textarea class="common" name="tindak_lanjut" rows="4">
                <?php echo (!empty($kendali->tindak_lanjut))? $kendali->tindak_lanjut:''; ?></textarea>
                </td>
            </tr>
            <tr>
                <td>Hasil Tindak Lanjut</td>
                <td>
                <textarea class="common" name="hasil_tl" rows="4">
                <?php echo (!empty($kendali->hasil_tl))? $kendali->hasil_tl:''; ?></textarea>
                </td>
            </tr>						
        </tbody>
    </table>
	</form>
	</div>
	<footer>
		<div class="submit_link">    			
  			<input id="simpan" type="button" value="Simpan">
		</div> 
	</footer>
</div>
