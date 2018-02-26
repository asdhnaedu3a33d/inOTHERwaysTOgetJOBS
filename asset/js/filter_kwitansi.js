/**
 * var arCombo = new Array(
 		$("#cmb0"),$("#cmb1"),$("#cmb2"),$("#cmb3") 
 	);
	level : 0 - parent, 1 - child, 2 - child of child dst 
 */
function populate_combo(arCombo, level, url, postData){
	//ambil kode referensi
	var parentCode = arCombo[level].val();
 
	//request isian combobox ke server
	$.ajax({
			type: "POST",
			url : url,
			data: postData,
			success: function(msg){
				//set isian combo
				//alert(msg);
				arCombo[level + 1].html(msg);
			}
	});
	
	//reset semua child nya
	for (var i = level + 2; i < arCombo.length; i++) {
		arCombo[i].html("<option value=''> Pilih ... </option>")
	}
}
