
<script type="text/javascript">

if( typeof helper == 'undefined' ) {
  var helper = { } ;
}
helper.arr = {
    multisort: function(arr, columns, order_by) {
        if(typeof columns == 'undefined') {
            columns = []
            for(x=0;x<arr[0].length;x++) {
                columns.push(x);
            }
        }
        if(typeof order_by == 'undefined') {
            order_by = []
            for(x=0;x<arr[0].length;x++) {
                order_by.push('ASC');
            }
        }
        function multisort_recursive(a,b,columns,order_by,index) {
            var direction = order_by[index] == 'DESC' ? 1 : 0;
            var is_numeric = !isNaN(+a[columns[index]] - +b[columns[index]]);
            var x = is_numeric ? +a[columns[index]] : a[columns[index]].toLowerCase();
            var y = is_numeric ? +b[columns[index]] : b[columns[index]].toLowerCase();
            if(x < y) {
                    return direction == 0 ? -1 : 1;
            }
            if(x == y)  {
                return columns.length-1 > index ? multisort_recursive(a,b,columns,order_by,index+1) : 0;
            }
            return direction == 0 ? 1 : -1;
        }
        return arr.sort(function (a,b) {
            return multisort_recursive(a,b,columns,order_by,0);
        });
    }
};

function clearTable(){
  var rowCount = listbelanja.rows.length;
	for (var i = rowCount - 1; i > 0; i--) {
	    listbelanja.deleteRow(i);
	}
};
function generateArrayToTable(arrayData){
 for (var i =1; i <= arrayData.length;i++){
 	var tbl = document.getElementById("listbelanja");
			var row = tbl.insertRow(tbl.rows.length);
			var td0 = document.createElement("td");

			var td1 = document.createElement("td");
			var td2 = document.createElement("td");
			var td3 = document.createElement("td");
			var td4 = document.createElement("td");
			var td5 = document.createElement("td");
			var td6 = document.createElement("td");

			var td7 = document.createElement("td");
			var td8 = document.createElement("td");
			var td9 = document.createElement("td");
			var td10 = document.createElement("td");

			var td11 = document.createElement("td");
			td11.style.display = 'none';
			var td12 = document.createElement("td");
			td12.style.display = 'none';
			var td13 = document.createElement("td");
			td13.style.display = 'none';
			var td14 = document.createElement("td");
			td14.style.display = 'none';
			var td15 = document.createElement("td");
			td15.style.display = 'none';


			td0.appendChild(generateIndex(i));
			td0.appendChild(generateTeksIndex(i));

			//td1.appendChild(generateJenisBelanja(i));
			td1.appendChild(generateTeksJenisBelanja(i));

			//td2.appendChild(generateKategoriBelanja(i));
			td2.appendChild(generateTeksKategoriBelanja(i));

			//td3.appendChild(generateSubKategoriBelanja(row.rowIndex));
			td3.appendChild(generateTeksSubKategoriBelanja(i));

			//td4.appendChild(generateBelanja(row.rowIndex));
			td4.appendChild(generateTeksBelanja(i));

			//td5.appendChild(generateUraian(row.rowIndex));
			td5.appendChild(generateTeksUraian(i));

			//td6.appendChild(generateDetUraian(row.rowIndex));
			td6.appendChild(generateTeksDetUraian(i));

			//td7.appendChild(generateVolume(row.rowIndex));
			td7.appendChild(generateTeksVolume(i));

			//td8.appendChild(generateSatuan(row.rowIndex));
			td8.appendChild(generateTeksSatuan(i));

			//td9.appendChild(generateNominal(row.rowIndex));
			td9.appendChild(generateTeksNominal(i));

			td10.appendChild(generateTeksHapus(i));

			td11.appendChild(generateKodeJenisBelanja(i));
			td12.appendChild(generateKodeKategoriBelanja(i));
			td13.appendChild(generateKodeSubKategoriBelanja(i));
			td14.appendChild(generateKodeBelanja(i));
			td15.appendChild(generateKodeUraian(i));


			row.id = i;
			row.appendChild(td0);
			row.appendChild(td1);
			row.appendChild(td2);
			row.appendChild(td3);
			row.appendChild(td4);
			row.appendChild(td5);
			row.appendChild(td6);
			row.appendChild(td7);
			row.appendChild(td8);
			row.appendChild(td9);
			row.appendChild(td10);
			row.appendChild(td11);
			row.appendChild(td12);
			row.appendChild(td13);
			row.appendChild(td14);
			row.appendChild(td15);

 };
};
function generateTableToArray(){
    var array = [];
    var headers = [];
    $('#listbelanja th').each(function(index, item) {
        headers[index] = $(item).html();
    });
    $('#listbelanja tr').has('td').each(function() {
        var arrayItem = {};
        $('td', $(this)).each(function(index, item) {
            arrayItem[headers[index]] = $(item).html();
        });
        array.push(arrayItem);
    });
    //console.log(array);
    return array;
};



function reorderTable(){
  arrayTable = generateTableToArray();
  clearTable();
   rankingarr = helper.arr.multisort(arrayTable, ['1','2','3','4','5'], ['ASC','ASC','ASC','ASC','ASC']);
  if(rankingarr.length > 0){
	//console.log(rankingarr):
 	//console.log(rankingarr[0]["Jenis Belanja"])
 	//console.log(rankingarr.length)

	};
  //document.getElementById('whereToPrint').innerHTML = JSON.stringify(arrayTable, null, 4);
 //console.log(rankingarr[0]["Item Description"]);
 //console.log(rankingarr[1]["Item Description"]);

 generateArrayToTable(rankingarr);
};


	function listBelanja(clue){
			var tbl = document.getElementById("listbelanja");
			var row = tbl.insertRow(tbl.rows.length);
			var td0 = document.createElement("td");

			var td1 = document.createElement("td");
			var td2 = document.createElement("td");
			var td3 = document.createElement("td");
			var td4 = document.createElement("td");
			var td5 = document.createElement("td");
			var td6 = document.createElement("td");

			var td7 = document.createElement("td");
			var td8 = document.createElement("td");
			var td9 = document.createElement("td");
			var td10 = document.createElement("td");

			var td11 = document.createElement("td");
			td11.style.display = 'none';
			var td12 = document.createElement("td");
			td12.style.display = 'none';
			var td13 = document.createElement("td");
			td13.style.display = 'none';
			var td14 = document.createElement("td");
			td14.style.display = 'none';
			var td15 = document.createElement("td");
			td15.style.display = 'none';


			td0.appendChild(generateIndex(row.rowIndex));
			td0.appendChild(generateTeksIndex(row.rowIndex));

			//td1.appendChild(generateJenisBelanja(row.rowIndex));
			td1.appendChild(generateTeksJenisBelanja(row.rowIndex));

			//td2.appendChild(generateKategoriBelanja(row.rowIndex));
			td2.appendChild(generateTeksKategoriBelanja(row.rowIndex));

			//td3.appendChild(generateSubKategoriBelanja(row.rowIndex));
			td3.appendChild(generateTeksSubKategoriBelanja(row.rowIndex));

			//td4.appendChild(generateBelanja(row.rowIndex));
			td4.appendChild(generateTeksBelanja(row.rowIndex));

			//td5.appendChild(generateUraian(row.rowIndex));
			td5.appendChild(generateTeksUraian(row.rowIndex));

			//td6.appendChild(generateDetUraian(row.rowIndex));
			td6.appendChild(generateTeksDetUraian(row.rowIndex));

			//td7.appendChild(generateVolume(row.rowIndex));
			td7.appendChild(generateTeksVolume(row.rowIndex));

			//td8.appendChild(generateSatuan(row.rowIndex));
			td8.appendChild(generateTeksSatuan(row.rowIndex));

			//td9.appendChild(generateNominal(row.rowIndex));
			td9.appendChild(generateTeksNominal(row.rowIndex));

			td10.appendChild(generateTeksHapus(row.rowIndex));

			td11.appendChild(generateKodeJenisBelanja(row.rowIndex));
			td12.appendChild(generateKodeKategoriBelanja(row.rowIndex));
			td13.appendChild(generateKodeSubKategoriBelanja(row.rowIndex));
			td14.appendChild(generateKodeBelanja(row.rowIndex));
			td15.appendChild(generateKodeUraian(row.rowIndex));


			row.id = row.rowIndex;
			row.appendChild(td0);
			row.appendChild(td1);
			row.appendChild(td2);
			row.appendChild(td3);
			row.appendChild(td4);
			row.appendChild(td5);
			row.appendChild(td6);
			row.appendChild(td7);
			row.appendChild(td8);
			row.appendChild(td9);
			row.appendChild(td10);
			row.appendChild(td11);
			row.appendChild(td12);
			row.appendChild(td13);
			row.appendChild(td14);
			row.appendChild(td15);

			reorderTable();
			if (clue=='all') {
				kosongkan();

			}
			else if (clue=='jns') {
				document.getElementById("kode_jenis_belanja_autocomplete").value = '';
				document.getElementById("kd_jenis_belanja").value = '';
				document.getElementById("volume").value = '';
				document.getElementById("satuan").value = '';
				document.getElementById("nominal").value='';
			}
			else if (clue=='kat') {
				document.getElementById("kode_kategori_belanja_autocomplete").value = '';
				document.getElementById("kd_kategori_belanja").value = '';
				document.getElementById("volume").value = '';
				document.getElementById("satuan").value = '';
				document.getElementById("nominal").value='';
			}
			else if (clue=='subkat') {
				document.getElementById("kode_subkategori_belanja_autocomplete").value = '';
				document.getElementById("kd_subkategori_belanja").value = '';
				document.getElementById("volume").value = '';
				document.getElementById("satuan").value = '';
				document.getElementById("nominal").value='';
			}else if (clue=='belanja') {
				document.getElementById("kode_belanja_autocomplete").value = '';
				document.getElementById("kd_belanja").value = '';
				document.getElementById("volume").value = '';
				document.getElementById("satuan").value = '';
				document.getElementById("nominal").value='';
			}else if (clue=='uraian') {
				document.getElementById("uraian").value = '';
				document.getElementById("volume").value = '';
				document.getElementById("satuan").value = '';
				document.getElementById("nominal").value='';
			}else if (clue=='deturaian') {
				document.getElementById("det_uraian").value = '';
				document.getElementById("volume").value = '';
				document.getElementById("satuan").value = '';
				document.getElementById("nominal").value='';
			}
		}

		function kosongkan(){
			document.getElementById("kode_jenis_belanja_autocomplete").value = '';
			document.getElementById("kd_jenis_belanja").value = '';
			document.getElementById("kode_kategori_belanja_autocomplete").value = '';
			document.getElementById("kd_kategori_belanja").value = '';
			document.getElementById("kode_subkategori_belanja_autocomplete").value = '';
			document.getElementById("kd_subkategori_belanja").value = '';
			document.getElementById("kode_belanja_autocomplete").value = '';
			document.getElementById("kd_belanja").value = '';
			document.getElementById("uraian").value = '';
			document.getElementById("det_uraian").value = '';
			document.getElementById("volume").value = '';
			document.getElementById("satuan").value = '';
			document.getElementById("nominal").value='';
		}

		function generateIndex(index) {
			var idx = document.createElement("input");

			idx.type = "hidden";
			idx.name = "index[ ]";
			idx.id = "index["+index+"]";
			idx.value = index;
			return idx;
		}

		function generateTeksIndex(index){
		var teks = document.createTextNode(index);
			return teks;
		}

		function generateJenisBelanja(index) {
			var jnsbelanja = document.createElement("input");

			jnsbelanja.type = "hidden";
			jnsbelanja.name = "jnsbelanja[ ]";


			if(index>rankingarr.length){

				jnsbelanja.id = "jnsbelanja["+index+"]";
				jnsbelanja.value = document.getElementById("kd_jenis_belanja").value;

			}else{
				//index=index+1;
				jnsbelanja.id = "jnsbelanja["+index+"]";
				jnsbelanja.value = rankingarr[index-1]["1"];
			//alert(rankingarr[index-1]["1"]);
			};
			return jnsbelanja;
		}

		function generateTeksJenisBelanja(index){

			if(index>rankingarr.length){
				var nilai = document.getElementById("kode_jenis_belanja_autocomplete").value;
			}else{
				var nilai = rankingarr[index-1]["Jenis Belanja"];
			};
			var teks = document.createTextNode(nilai);
			return teks;
		}
		function generateKodeJenisBelanja(){
			var nilai = document.getElementById("kd_jenis_belanja").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}
		function generateKategoriBelanja(index) {
			var katbelanja = document.createElement("input");

			katbelanja.type = "hidden";
			katbelanja.name = "katbelanja[ ]";

			if(index>rankingarr.length){
				katbelanja.id = "katbelanja["+index+"]";
				katbelanja.value = document.getElementById("kd_kategori_belanja").value;
			}else{
				//index=index+1;
				katbelanja.id = "katbelanja["+index+"]";
				katbelanja.value = rankingarr[index-1]["2"];
			//alert(rankingarr[index-1]["1"]);
			};
			return katbelanja;
		}

		function generateKodeKategoriBelanja(){
			var nilai = document.getElementById("kd_kategori_belanja").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}
		function generateTeksKategoriBelanja(index){
			if(index>rankingarr.length){
				var nilai = document.getElementById("kode_kategori_belanja_autocomplete").value;
			}else{
				var nilai = rankingarr[index-1]["Kategori Belanja"];
			};
			var teks = document.createTextNode(nilai);
			return teks;
		}

		function generateSubKategoriBelanja(index) {
			var subkatbelanja = document.createElement("input");

			subkatbelanja.type = "hidden";
			subkatbelanja.name = "subkatbelanja[ ]";
			subkatbelanja.id = "subkatbelanja["+index+"]";
			subkatbelanja.value = document.getElementById("kd_subkategori_belanja").value;
			return subkatbelanja;
		}
		function generateKodeSubKategoriBelanja(){
			var nilai = document.getElementById("kd_subkategori_belanja").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}
		function generateTeksSubKategoriBelanja(index){
			if(index>rankingarr.length){
				var nilai = document.getElementById("kode_subkategori_belanja_autocomplete").value;
			}else{
				var nilai = rankingarr[index-1]["Sub Kategori Belanja"];
			};
			var teks = document.createTextNode(nilai);
			return teks;
		}

		function generateBelanja(index) {
			var belanja = document.createElement("input");

			belanja.type = "hidden";
			belanja.name = "belanja[ ]";
			belanja.id = "belanja["+index+"]";
			belanja.value = document.getElementById("kd_belanja").value;
			return belanja;
		}
		function generateKodeBelanja(){
			var nilai = document.getElementById("kd_belanja").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}
		function generateTeksBelanja(index){
			if(index>rankingarr.length){
				var nilai = document.getElementById("kode_belanja_autocomplete").value;
			}else{
				var nilai = rankingarr[index-1]["Belanja"];
			};
			var teks = document.createTextNode(nilai);
			return teks;
		}

		function generateUraian(index) {
			var uraian = document.createElement("input");

			uraian.type = "hidden";
			uraian.name = "uraian[ ]";
			uraian.id = "uraian["+index+"]";
			uraian.value = document.getElementById("uraian").value;
			return uraian;
		}

		function generateTeksUraian(index){
			if(index>rankingarr.length){
				var nilai = document.getElementById("uraian").value;
			}else{
				var nilai = rankingarr[index-1]["Uraian"];
			};
			var teks = document.createTextNode(nilai);
			return teks;
		}
		function generateKodeUraian(){
			var nilai = document.getElementById("uraian").value;
			var teks = document.createTextNode(nilai);
			return teks;
		}
		function generateDetUraian(index){
			var deturaian = document.createElement("input");

			deturaian.type = "hidden";
			deturaian.name = "deturaian[ ]";
			deturaian.id = "deturaian["+index+"]";
			deturaian.value = document.getElementById("det_uraian").value;
			return deturaian;
		}

		function generateTeksDetUraian(index){
			if(index>rankingarr.length){
				var nilai = document.getElementById("det_uraian").value;
			}else{
				var nilai = rankingarr[index-1]["Detil"];
			};
			var teks = document.createTextNode(nilai);
			return teks;

		}

		function generateVolume(index) {
			var volume = document.createElement("input");

			volume.type = "hidden";
			volume.name = "volume[ ]";
			volume.id = "volume["+index+"]";
			volume.value = document.getElementById("volume").value;
			return volume;
		}

		function generateTeksVolume(index){
			if(index>rankingarr.length){
				var nilai = document.getElementById("volume").value;
			}else{
				var nilai = rankingarr[index-1]["Volume"];
			};
			var teks = document.createTextNode(nilai);
			return teks;
		}

		function generateSatuan(index) {
			var satuan = document.createElement("input");

			satuan.type = "hidden";
			satuan.name = "satuan[ ]";
			satuan.id = "satuan["+index+"]";
			satuan.value = document.getElementById("satuan").value;
			return satuan;
		}

		function generateTeksSatuan(index){
			if(index>rankingarr.length){
				var nilai = document.getElementById("satuan").value;
			}else{
				var nilai = rankingarr[index-1]["Satuan"];
			};
			var teks = document.createTextNode(nilai);
			return teks;
		}

		function generateNominal(index) {
			var nominal = document.createElement("input");

			nominal.type = "hidden";
			nominal.name = "nominal[ ]";
			nominal.id = "nominal["+index+"]";
			nominal.value = document.getElementById("nominal").value;
			return nominal;
		}

		function generateTeksNominal(index){
			if(index>rankingarr.length){
				var nilai = document.getElementById("nominal").value;
			}else{
				var nilai = rankingarr[index-1]["Nominal"];
			};

			var teks = document.createTextNode(nilai);
			return teks;
		}

		function generateTeksHapus(index){

			var teks = document.createElement("span");
			var x = document.createTextNode("Hapus");
			teks.appendChild(x);
			teks.id = "hapusrow";
			teks.setAttribute('onclick','hapusrow(' +index+ ')');
			teks.setAttribute('style','cursor:pointer;');
			teks.value = "hapus";
			return teks;
		}

		function hapusrow(){
			var tbl = document.getElementById("listbelanja");
			var table = document.createElement("table");
				bufferRow(table);
				deleteAll();
				reIndex(table);
		}

		function bufferRow(table) {
			var tbl = document.getElementById("listbelanja");
			var rowLen = tbl.rows.length;

			for (var idx=1;idx<rowLen;idx++) {
				var row = tbl.rows[idx];
				var cell = row.cells[1];
				var node = cell.lastChild;

				if (node.checked == false) {
					var rowNew = table.insertRow(table.rows.length);
					var td0 = document.createElement("td");
					var td1 = document.createElement("td");
					var td2 = document.createElement("td");
					var td3 = document.createElement("td");
					var td4 = document.createElement("td");
					var td5 = document.createElement("td");
					var td6 = document.createElement("td");
					var td7 = document.createElement("td");
					var td8 = document.createElement("td");
					var td9 = document.createElement("td");

					td0.appendChild(row.cells[0].lastChild);
					td1.appendChild(row.cells[1].lastChild);
					td2.appendChild(row.cells[2].lastChild);
					td3.appendChild(row.cells[3].firstChild);
					td3.appendChild(row.cells[3].lastChild);
					td4.appendChild(row.cells[4].lastChild);
					rowNew.appendChild(td0);
					rowNew.appendChild(td1);
					rowNew.appendChild(td2);
					rowNew.appendChild(td3);
					rowNew.appendChild(td4);
				}
			}
		}

		function hapusrow(index){
			 var row = document.getElementById(index);
			 row.parentNode.removeChild(row);
		}

	$(document).ready(function(){
		window.rankingarr=[] ;
		$('#nominal').autoNumeric(numOptions);

		jQuery.validator.addMethod("kode_autocomplete", function(value, element, params){
		    if ($("input[name="+ params +"]").val()=="") {
		    	return false;
		    }else{
		    	return true;
		    }
		}, "Data tidak valid/belum di pilih, mohon pilih data setelah melakukan pencarian pada kolom ini.");
		<?php if(@$id_groups!='6'){
		?>

		$("#nama_dewan").hide();
		<?php
		}
		?>
		$("form#belanja_renstra").validate({
			rules: {
			  kode_urusan_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_urusan"
			  },
			  kode_bidang_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_bidang"
			  },
			   kode_jenis_belanja_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_jenis_belanja"
			  },
			   kode_kategori_belanja_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_kategori_belanja"
			  },
			   kode_subkategori_belanja_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_subkategori_belanja"
			  },
			   kode_belanja_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_belanja"
			  },
			  kode_kegiatan_autocomplete : {
			  	required : true,
			  	kode_autocomplete : "kd_keg"
			  },
			  jenis_pekerjaan : "required",
			  volume : {
			  	required : true,
			  	number: true
			  },
			  satuan : "required",
			  lokasi : "required"
			}
	    });

		$("#simpan").click(function(){
		    var valid = $("form#belanja_renstra").valid();
		    if (valid) {
		    	$("#nominal").val($("#nominal").autoNumeric('get'));
		    	$("form#belanja_renstra").submit();
		    };
		});

		$("#kode_bidang_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_bidang",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_bidang").val("");
	          var kdurusan = $("#kd_urusan").val();
	          var s = $("#kode_bidang_autocomplete").val();

	          console.log(kdurusan);
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdbidang'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"kd_urusan": kdurusan,"term" : s},
	              success:
	              function(data){
	                add(data);

	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_bidang").val(ui.item.id);
			//console.log($("#id_groups").val());

	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });

		$("#kode_program_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_prog",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_prog").val("");
	          var kdurusan = $("#kd_urusan").val();
	          var kdbidang = $("#kd_bidang").val();
	          var s = $("#kode_program_autocomplete").val();


	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdprog'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"kd_urusan": kdurusan,"kd_bidang": kdbidang,"term" : s},
	              success:
	              function(data){
	                add(data);

	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_prog").val(ui.item.id);
			//console.log($("#id_groups").val());
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });


		$("#kode_kegiatan_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_keg",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_keg").val("");
	          var kdurusan = $("#kd_urusan").val();
	          var kdbidang = $("#kd_bidang").val();
	          var kdprog = $("#kd_prog").val();
	          var s = $("#kode_kegiatan_autocomplete").val();

	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_keg'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"kd_urusan": kdurusan,"kd_bidang": kdbidang,"kd_prog":kdprog,"term" : s},
	              success:
	              function(data){
	                add(data);

	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_keg").val(ui.item.id);
			//console.log($("#id_groups").val());
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });





		$("#kode_jenis_belanja_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_jenis_belanja",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_jenis_belanja").val("");
	          var s = $("#kode_jenis_belanja_autocomplete").val();

	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdjenisbelanja'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"term" : s},
	              success:
	              function(data){
	                add(data);

	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_jenis_belanja").val(ui.item.id);
			//console.log($("#id_groups").val());

	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });


		$("#kode_kategori_belanja_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_kategori_belanja",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_kategori_belanja").val("");
	          var kdjenis = $("#kd_jenis_belanja").val();
	          var s = $("#kode_kategori_belanja_autocomplete").val();


	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdkategoribelanja'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"kd_jenis_belanja": kdjenis,"term" : s},
	              success:
	              function(data){
	                add(data);

	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_kategori_belanja").val(ui.item.id);
			//console.log($("#id_groups").val());

	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });

		$("#kode_subkategori_belanja_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_subkategori_belanja",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_subkategori_belanja").val("");
	          var kdjenis= $("#kd_jenis_belanja").val();
	          var kdkategori = $("#kd_kategori_belanja").val();
	          var s = $("#kode_subkategori_belanja_autocomplete").val();


	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdsubkategoribelanja'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"kd_jenis_belanja": kdjenis,"kd_kategori_belanja": kdkategori,"term" : s},
	              success:
	              function(data){
	                add(data);

	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_subkategori_belanja").val(ui.item.id);
			//console.log($("#id_groups").val());

	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });

		$("#kode_belanja_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_belanja",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_belanja").val("");
	          var kdjenis= $("#kd_jenis_belanja").val();
	          var kdkategori = $("#kd_kategori_belanja").val();
	          var kdsubkategori = $("#kd_subkategori_belanja").val();

	          var s = $("#kode_belanja_autocomplete").val();


	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdkodebelanja'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: {"kd_jenis_belanja": kdjenis,"kd_kategori_belanja": kdkategori,"kd_subkategori_belanja":kdsubkategori,"term" : s},
	              success:
	              function(data){
	                add(data);

	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_belanja").val(ui.item.id);
			//console.log($("#id_groups").val());

	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });


		$("#kode_urusan_autocomplete").autocomplete({
        appendTo: "#autocomplete_element_urusan",
	      minLength: 0,
	      source:
	      function(req, add){
	          $("#kd_urusan").val("");
	          $.ajax({
	              url: "<?php echo base_url('common/autocomplete_kdurusan'); ?>",
	              dataType: 'json',
	              type: 'POST',
	              data: req,
	              success:
	              function(data){
	                add(data);
	              },
	          });
	      },
	      select:
	      function(event, ui) {
	        $("#kd_urusan").val(ui.item.id);
	      }
	    }).focus(function(){
	        $(this).trigger('keydown.autocomplete');
	    });
	});
</script>



<article class="module width_full">
 	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit Data Belanja Renstra";

			} else{
			    echo "Input Data Belanja Renstra";
			}
		?>
		</h3>
 	</header>

 	<div class="module_content">
 		<form action="<?php echo site_url('belanja_renstra/save');?>" method="POST" name="belanja_renstra" id="belanja_renstra" accept-charset="UTF-8" enctype="multipart/form-data" >

 			<input type="hidden" name="id_belanja_renstra"  id='id_belanja_renstra' value="<?php if(!empty($id_belanja_renstra)){echo $id_belanja_renstra;} ?>" />
 			<table class="fcari" width="100%">
 				<tbody>
 					<tr>
              <td width="20%">Jenis Belanja</td>
            	<td width="80%">
              		<input type="text" id="kode_jenis_belanja_autocomplete" name="kode_jenis_belanja_autocomplete" class="common" value="<?php if(!empty($nm_jenis_belanja)){echo $nm_jenis_belanja;} ?>"/>
  		            <input type="hidden" id="kd_jenis_belanja" name="kd_jenis_belanja" value="<?php if(!empty($kode_jenis_belanja)){echo $kode_jenis_belanja;} ?>" />
        	        <div id="autocomplete_element_jenis_belanja" class="autocomplete_element"></div>
              </td>
          </tr>
          <tr>
            	<td>Kategori Belanja</td>
            	<td>
                	<input type="text" id="kode_kategori_belanja_autocomplete" name="kode_kategori_belanja_autocomplete" class="common" value="<?php if(!empty($nm_kategori_belanja)){echo $nm_kategori_belanja;} ?>"/>
                  <input type="hidden" id="kd_kategori_belanja" name="kd_kategori_belanja" value="<?php if(!empty($kode_kategori_belanja)){echo $kode_kategori_belanja;} ?>"/>
            	    <div id="autocomplete_element_kategori_belanja" class="autocomplete_element"></div>
              </td>
          </tr>
          <tr>
          	<td>Sub Kategori Belanja</td>
          	<td>
          	     <input type="text" id="kode_subkategori_belanja_autocomplete" name="kode_subkategori_belanja_autocomplete" class="common" value="<?php if(!empty($nm_subkategori_belanja)){echo $nm_subkategori_belanja;} ?>"/>
	               <input type="hidden" id="kd_subkategori_belanja" name="kd_subkategori_belanja" value="<?php if(!empty($kode_subkategori_belanja)){echo $kode_subkategori_belanja;} ?>" />
      	         <div id="autocomplete_element_subkategori_belanja" class="autocomplete_element"></div>
            </td>
          </tr>
          <tr >
          	<td>Belanja</td>
          	<td>
          		   <input type="text" id="kode_belanja_autocomplete" name="kode_belanja_autocomplete" class="common" value="<?php if(!empty($nm_belanja)){echo $nm_belanja;} ?>"/>
            	   <input type="hidden" id="kd_belanja" name="kd_belanja" value="<?php if(!empty($kode_belanja)){echo $kode_belanja;} ?>" />
                 <div id="autocomplete_element_belanja" class="autocomplete_element"></div>
            </td>
          </tr>
					<tr>
          	<td>Uraian</td>
          	<td>
                  <input type="text" id="uraian" name="uraian" class="common" value="<?php if(!empty($uraian)){echo $uraian;} ?>" />
            </td>
          </tr>
          <tr>
          	<td>Detail Uraian</td>
          	<td>
                  <input type="text" id="det_uraian" name="det_uraian" class="common" value="<?php if(!empty($deturaian)){echo $deturaian;} ?>" />
            </td>
          </tr>
          <tr>
						<td>Volume</td>
						<td><input class="common" type="text" name="volume" id="volume" value="<?php if(!empty($volume)){echo $volume;} ?>"/></td>
					</tr>
					<tr>
          	<td>Satuan</td>
          	<td>
          		<input type="text" id="satuan" name="satuan" class="common" value="<?php if(!empty($satuan)){echo $satuan;} ?>" />
            </td>
          </tr>
          <tr>
						<td>Nominal</td>
						<td><input class="common" type="text" name="nominal" id="nominal"value="<?php if(!empty($nominal)){echo $nominal;} ?>"/></td>
					</tr>
 				</tbody>
 			</table>

 	</div>
 	<footer>
		<div class="submit_link">

			<input type='button' id="tambahjnsbelanja" onclick="listBelanja(clue='jns');" style="cursor:pointer;" value='Tambah Jenis Belanja'>
			<input type='button'  id="tambahkatbelanja" onclick="listBelanja(clue='kat');" style="cursor:pointer;" value='Tambah Kategori Belanja'>
			<input type='button'  id="tambahsubkatbelanja" onclick="listBelanja(clue='subkat');" style="cursor:pointer;" value='Tambah Sub Kategori Belanja'>
			<input type='button'  id="tambahbelanja" onclick="listBelanja(clue='belanja');" style="cursor:pointer;" value='Tambah Belanja'>
			<input type='button'  id="tambahuraian" onclick="listBelanja(clue='uraian');" style="cursor:pointer;" value='Tambah Uraian'>
			<input type='button'  id="tambahdeturaian" onclick="listBelanja(clue='deturaian');" style="cursor:pointer;" value='Tambah Detil Uraian'>
			<input type='button'  id="tambah" onclick="listBelanja(clue='all');" style="cursor:pointer;" value='Tambah'>

		</div>
		<table id="listbelanja">
			<tr>
				<th>No</th>
				<th>Jenis Belanja</th>
				<th>Kategori Belanja</th>
				<th>Sub Kategori Belanja</th>
				<th>Belanja</th>
				<th>Uraian</th>
				<th>Detil</th>

				<th>Volume</th>
				<th>Satuan</th>
				<th>Nominal</th>
				<th>Action</th>

				<th style="display:none;">1</th>
				<th style="display:none;">2</th>
				<th style="display:none;">3</th>
				<th style="display:none;">4</th>
				<th style="display:none;">5</th>
			</tr>

		</table>
<p id ="whereToPrint"></p>
		<div class="submit_link">
		<input type='button' id="simpan" name="simpan" value='Simpan' />
  		<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('belanja_renstra'); ?>'" />
  	</div>
	</footer>
	</form>
</article>
