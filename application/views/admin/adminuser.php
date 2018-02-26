<section id="main">
        <article class="module width_full">                   
            <header><h3>Daftar User</h3></header>
            
            <div class="module_content">
            <table>
            <tr><td>Jenis User</td><td>:</td><td> <?php echo form_dropdown("id_tes",$jenis_user,"","id='id_tes'") ?></td></tr> 
            </table>
            <br>
            
            <div id="cek" style="width: 900px";>
            <table id="tabel"></table>
            <div id="pager"></div>
            </div>
            
            
            </div>
        </article>
         
        
        <div class="spacer"></div>
        <script type="text/javascript">
          $("#id_tes").change(function(){
                var selectValues = $("#id_tes").val();
                
                    var id_tes = {id_tes:$("#id_tes").val()};
                    $.ajax({
                            type: "POST",
                            url : "<?php echo site_url('admin/get_user_tabel')?>",
                            data: id_tes,
                            success: function(msg){
                                $('#cek').html(msg);
                            }
                      });
               
        });
       </script> 
</section> 
