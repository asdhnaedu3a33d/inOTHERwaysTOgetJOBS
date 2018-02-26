<section id="main">
        <script type="text/javascript">
            jQuery().ready(function (){
                jQuery("#daftarunit").jqGrid({
                    url:'<?php echo base_url().'index.php/jqgrid/tampil_dataunit'?>',
                    mtype : "POST",
                    datatype: "json",
                    colNames:['Nomor','Kode Unit','Nama Unit','Aksi'],
                    colModel:[
                        {name:'id_unit',index:'id_unit', width:5, align:"left",editable:false,editrules:{required:true}},
                        {name:'kode_unit',index:'kode_unit', width:5, align:"left",editable:true,editrules:{required:true}},
                        {name:'nama_unit',index:'nama_unit',  width:30, align:"left",editable:true,editrules:{required:true}},
                        {name: 'myac', width:60, fixed:true, sortable:false, search:false, align:'center', resizable: false, resize:false, formatter:'actions',
						formatoptions:{
						keys:true,editbutton: true, delbutton: true, //Key True disini adalah untuk Shorcut Tombol Enter dan Esc, Enter untuk Save, dan Escape Untuk Batal, anda juga Membuatnya jadi False jika anda tidak ingin seperti itu
						//Saya menghapus baris di bawah ini
														/*onSuccess:function(jqXHR) {
															alert(jqXHR.responseText);
															return true;
														}*/
						}}
                    ],
                    rowNum:30,
                    width: 600,
                    height: 200,
                    rowList:[10,20,30,40,50,60,70],
                    pager: '#pager',
                    sortname: 'id_unit',
                    viewrecords: true,
					sortorder: "asc",
					editurl: "<?php echo base_url().'index.php/jqgrid/crudunit'?>",
					multiselect: false,
                    caption:"Daftar Unit"
                }).navGrid('#pager',{edit:true,add:true,del:true});
            });
            

        </script>
        <article class="module width_full">                   
            <header><h3>Daftar Unit</h3></header>
            <div class="module_content">
                <table id="daftarunit"></table>
                
                <div id="pager"></div>
            </div>
        </article>
         
        
        <div class="spacer"></div>
</section>   