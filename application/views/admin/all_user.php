<script type="text/javascript">

            jQuery().ready(function (){
                jQuery("#tabel").jqGrid({
                    url:'<?php echo base_url().'jqgrid/tampil_datauser' ?>',
                    mtype : "POST",
                    datatype: "json",
                    colNames:['Uraian','Volume','Satuan','Harga Satuan','Jumlah'],
                    colModel:[
                         {name:'id',index:'id', width:3, align:"left",editable:false,editrules:{required:true}},
                        {name:'username',index:'username', width:10, align:"left"},
                        {name:'password',index:'password', width:15, align:"left",editable:false,editrules:{required:true}},
                        {name:'user_nama',index:'user_nama', width:15, align:"left",editable:false,editrules:{required:true}},
                        {name:'user_active',index:'user_active', width:5, align:"left",editable:false,editrules:{required:true}},
                        
                    ],
                    rowNum:20,
                    width: 900,
                    height: 150,
                    rowList:[10,20,30,40,50,60,70],
                    pager: '#pager',
                    sortname: 'id_user',
                    viewrecords: true,
                    sortorder: "asc",
                    editurl: "<?php echo base_url().'jqgrid/crud'?>",
                    multiselect: false,
                    caption:"Daftar Semua User"
                }).navGrid('#pager');
            });    
            </script>  

        
            <table id="tabel"></table>
                
            <div id="pager"></div>
