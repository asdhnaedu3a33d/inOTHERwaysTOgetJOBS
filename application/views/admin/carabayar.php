<section id="main">
    <script type="text/javascript">

            jQuery().ready(function (){
                jQuery("#daftaruser").jqGrid({
                    url:'<?php echo base_url().'jqgrid/tampil_carabayar' ?>',
                    mtype : "POST",
                    datatype: "json",
                    colNames:['nomor','kode carabayar','carabayar','Aksi'],
                    colModel:[
                         {name:'id',index:'id', width:5, align:"left",editable:true,editrules:{required:true}},
                        {name:'kode_carabayar',index:'kode_carabayar', width:10, align:"left",editable:true,editrules:{required:true}},
                        {name:'carabayar',index:'carabayar', width:15, align:"left",editable:true,editrules:{required:true}},
                        {name: 'myac', width:70, fixed:true, sortable:false, search:false, align:'center', resizable: true, resize:true, formatter:'actions',
                        formatoptions:{
                        keys:true,editbutton: true, delbutton: true, //Key True disini adalah untuk Shorcut Tombol Enter dan Esc, Enter untuk Save, dan Escape Untuk Batal, anda juga Membuatnya jadi False jika anda tidak ingin seperti itu
                        //Saya menghapus baris di bawah ini
                                                        /*onSuccess:function(jqXHR) {
                                                            alert(jqXHR.responseText);
                                                            return true;
                                                        }*/
                        }}
                    ],
                    rowNum:20,
                    width: 500,
                    height: 150,
                    rowList:[10,20,30,40,50,60,70],
                    pager: '#pager',
                    sortname: 'id_carabayar',
                    viewrecords: true,
                    sortorder: "asc",
                    editurl: "<?php echo base_url().'jqgrid/crudcarabayar'?>",
                    multiselect: false,
                    caption:"Daftar Carabayar"
                }).navGrid('#pager',{edit:true,add:true,del:true} );
            });    
            </script>  

        <article class="module width_full">                   
            <header><h3>Daftar Cara Bayar</h3></header>
            <div class="module_content">
                <table id="daftaruser"></table>
                
                <div id="pager"></div>
            </div>
        </article>
         
        
        <div class="spacer"></div>
</section>   