<div style="width: 900px">
  <script>
  $("#input_keterangan").submit(function() {
    var url = "<?php echo site_url('musrenbangcam/add_keterangan')?>"; // the script where you handle the form input.
    $.blockUI({
      message: 'Proses perubahan status sedang dilakukan, mohon ditunggu ...',
			css: window._css,
			overlayCSS: window._ovcss
		});
    $.ajax({
       type: "POST",
       url: url,
       data: $("#input_keterangan").serialize(), // serializes the form's elements.
       success: function(msg){
         $.blockUI({
           message: msg.msg,
           timeout: 2000,
           css: window._css,
           overlayCSS: window._ovcss
         });
         dt.draw();         
       }
     });

  });
  </script>
 	<header>
 		<h3>
	       Penolakan Usulan
		</h3>
 	</header>
 	<div class="module_content">
 	  <form method="post" name='input_keterangan' id='input_keterangan' enctype="multipart/form-data" >
      <input type="hidden" name="id_musrenbang" value="<?php if(!empty($id_musrenbang)){echo $id_musrenbang;} ?>">
      <table id="input_keterangan_table" class="fcari" width="100%">
        <tr>
    		<td>Keterangan</td>
    		<td>
          <textarea  rows="4" name="keterangan" id="keterangan" placeholder="keterangan" value="" style="width:70%"> </textarea>
    		</td>
    	</tr>
       </table>
       <div class="submit_link">
  			<input type="submit" name="simpan"  id="simpan" value='Simpan'/>
	    </div>
      </form>
 	</div>
</div>
