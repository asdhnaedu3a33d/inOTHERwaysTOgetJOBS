function listBelanja_2(clue){
    var tbl = document.getElementById("listbelanja_2");
    var row = tbl.insertRow(tbl.rows.length);
    var inIndex_2 = document.getElementById("inIndex_2").value;

    var td0 = document.createElement("td");
    var tdnew0 = document.createElement("td");
    var td1 = document.createElement("td");
    var td2 = document.createElement("td");
    var td3 = document.createElement("td");
    var td4 = document.createElement("td");
    var td5 = document.createElement("td");
    var td6 = document.createElement("td");
    var td7 = document.createElement("td");
    var td8 = document.createElement("td");
    var td9 = document.createElement("td");
    var tdnew1 = document.createElement("td");
    var td10 = document.createElement("td");
    var tdEdit = document.createElement("td");

    var tdnew2 = document.createElement("td"); tdnew2.style.display = 'none';
    var td11 = document.createElement("td");   td11.style.display = 'none';
    var td12 = document.createElement("td");   td12.style.display = 'none';
    var td13 = document.createElement("td");   td13.style.display = 'none';
    var td14 = document.createElement("td");   td14.style.display = 'none';
    var td15 = document.createElement("td");   td15.style.display = 'none';
    var td16 = document.createElement("td");   td16.style.display = 'none';
    var td17 = document.createElement("td");   td17.style.display = 'none';
    var td18 = document.createElement("td");   td18.style.display = 'none';
    var td19 = document.createElement("td");   td19.style.display = 'none';
    var td20 = document.createElement("td");   td20.style.display = 'none';


    //table yang terlihat
    td0.appendChild(generateTeksIndex_2(row.rowIndex));
    tdnew0.appendChild(generateSumberDana_2(row.rowIndex))
    td1.appendChild(generateTextBound_2(row.rowIndex, "cb_jenis_belanja_2"));
    td2.appendChild(generateTextBound_2(row.rowIndex, "cb_kategori_belanja_2"));
    td3.appendChild(generateTextBound_2(row.rowIndex, "cb_subkategori_belanja_2"));
    td4.appendChild(generateTextBound_2(row.rowIndex, "cb_belanja_2"));
    td5.appendChild(generateTeksUraian_2(row.rowIndex));
    td6.appendChild(generateTeksDetUraian_2(row.rowIndex));
    td7.appendChild(generateTeksVolume_2(row.rowIndex));
    td8.appendChild(generateTeksSatuan_2(row.rowIndex));
    td9.appendChild(generateTeksNominalSatuan_2(row.rowIndex));
    tdnew1.appendChild(generateSubTotal_2(row.rowIndex))
    tdEdit.appendChild(generateTeksEdit_2(inIndex_2));
    td10.appendChild(generateTeksHapus_2(row.rowIndex));
    //table yang di-hidden
    tdnew2.appendChild(generateKodeSumberDana_2(inIndex_2));
    td11.appendChild(generateTextBox_2(inIndex_2,"r_kd_jenis_belanja_2","cb_jenis_belanja_2"));
    td12.appendChild(generateTextBox_2(inIndex_2,"r_kd_kategori_belanja_2","cb_kategori_belanja_2"));
    td13.appendChild(generateTextBox_2(inIndex_2,"r_kd_subkategori_belanja_2","cb_subkategori_belanja_2"));
    td14.appendChild(generateTextBox_2(inIndex_2,"r_kd_belanja_2","cb_belanja_2"));
    td15.appendChild(generateTextBox_2(inIndex_2,"r_uraian_2","uraian_2"));
    td16.appendChild(generateTextBox_2(inIndex_2,"r_det_uraian_2","det_uraian_2"));
    td17.appendChild(generateTextBoxNilai_2(inIndex_2,"r_volume_2","volume_2"));
    td18.appendChild(generateTextBox_2(inIndex_2,"r_satuan_2","satuan_2"));
    td19.appendChild(generateTextBoxNilai_2(inIndex_2,"r_nominal_satuan_2","nominal_satuan_2"));
    td20.appendChild(generateTotalSub_2(inIndex_2));


    row.id = row.rowIndex;
    row.appendChild(td0);

    row.appendChild(td1);
    row.appendChild(td2);
    row.appendChild(td3);
    row.appendChild(td4);
    row.appendChild(td5);
    row.appendChild(tdnew0);
    row.appendChild(td6);
    row.appendChild(td7);
    row.appendChild(td8);
    row.appendChild(td9);
    row.appendChild(tdnew1);
    row.appendChild(tdEdit);
    row.appendChild(td10);

    row.appendChild(tdnew2);
    row.appendChild(td11);
    row.appendChild(td12);
    row.appendChild(td13);
    row.appendChild(td14);
    row.appendChild(td15);
    row.appendChild(td16);
    row.appendChild(td17);
    row.appendChild(td18);
    row.appendChild(td19);
    row.appendChild(td20);

    //mencari nominal tahunan >.<
    setNominalTahun_2("tambah");
    document.getElementById("inIndex_2").value = parseInt(inIndex_2) + 1;
    $("#isEdit_2").val("0");

    if (clue=='all') {
      kosongkan_2();
    }
    else if (clue=='jns') {
      document.getElementById("cb_jenis_belanja_2").value = '';
      $("#cb_jenis_belanja_2").trigger("chosen:updated");
      document.getElementById("cb_kategori_belanja_2").value = '';
      $("#cb_kategori_belanja_2").trigger("chosen:updated");
      document.getElementById("cb_subkategori_belanja_2").value = '';
      $("#cb_subkategori_belanja_2").trigger("chosen:updated");
      document.getElementById("cb_belanja_2").value = '';
      $("#cb_belanja_2").trigger("chosen:updated");
      document.getElementById("uraian_2").value = '';
      document.getElementById("det_uraian_2").value = '';
      document.getElementById("volume_2").value = '';
      document.getElementById("satuan_2").value = '';
      document.getElementById("nominal_satuan_2").value='';
    }
    else if (clue=='kat') {
      document.getElementById("cb_kategori_belanja_2").value = '';
      $("#cb_kategori_belanja_2").trigger("chosen:updated");
      document.getElementById("cb_subkategori_belanja_2").value = '';
      $("#cb_subkategori_belanja_2").trigger("chosen:updated");
      document.getElementById("cb_belanja_2").value = '';
      $("#cb_belanja_2").trigger("chosen:updated");
      document.getElementById("uraian_2").value = '';
      document.getElementById("det_uraian_2").value = '';
      document.getElementById("volume_2").value = '';
      document.getElementById("satuan_2").value = '';
      document.getElementById("nominal_satuan_2").value='';
    }
    else if (clue=='subkat') {
      document.getElementById("cb_subkategori_belanja_2").value = '';
      $("#cb_subkategori_belanja_2").trigger("chosen:updated");
      document.getElementById("cb_belanja_2").value = '';
      $("#cb_belanja_2").trigger("chosen:updated");
      document.getElementById("uraian_2").value = '';
      document.getElementById("det_uraian_2").value = '';
      document.getElementById("volume_2").value = '';
      document.getElementById("satuan_2").value = '';
      document.getElementById("nominal_satuan_2").value='';
    }else if (clue=='belanja') {
      document.getElementById("cb_belanja_2").value = '';
      $("#cb_belanja_2").trigger("chosen:updated");
      document.getElementById("uraian_2").value = '';
      document.getElementById("det_uraian_2").value = '';
      document.getElementById("volume_2").value = '';
      document.getElementById("satuan_2").value = '';
      document.getElementById("nominal_satuan_2").value='';
    }else if (clue=='uraian') {
      document.getElementById("uraian_2").value = '';
      document.getElementById("det_uraian_2").value = '';
      document.getElementById("volume_2").value = '';
      document.getElementById("satuan_2").value = '';
      document.getElementById("nominal_satuan_2").value='';
    }else if (clue=='deturaian') {
      document.getElementById("det_uraian_2").value = '';
      document.getElementById("volume_2").value = '';
      document.getElementById("satuan_2").value = '';
      document.getElementById("nominal_satuan_2").value='';
    }


  }

  function generateTextBox_2(index,nama,isi) {
    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = nama+"["+index+"]";
    idx.id = nama+"["+index+"]";
    idx.value = document.getElementById(isi).value;
    return idx;
  }

  function generateTextBoxNilai_2(index,nama,isi) {
    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = nama+"["+index+"]";
    idx.id = nama+"["+index+"]";
    idx.value = $("#"+isi).autoNumeric('get');
    return idx;
  }

  function generateTextBound_2(index, nama) {
    var nilai = $("#"+nama+" option:selected").text();
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTotalSub_2(index) {
    var vol = $("#volume_2").autoNumeric('get');
    var nom = $("#nominal_satuan_2").autoNumeric('get');
    var hasil = vol * nom;
    var subtot = hasil;

    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = "r_subtotal_2["+index+"]";
    idx.id = "r_subtotal_2["+index+"]";
    idx.value = subtot;
    return idx;
  }

  function kosongkan_2(){
    document.getElementById("kode_jenis_belanja_autocomplete_2").value = '';
    document.getElementById("kd_jenis_belanja_2").value = '';
    document.getElementById("kode_kategori_belanja_autocomplete_2").value = '';
    document.getElementById("kd_kategori_belanja_2").value = '';
    document.getElementById("kode_subkategori_belanja_autocomplete_2").value = '';
    document.getElementById("kd_subkategori_belanja_2").value = '';
    document.getElementById("kode_belanja_autocomplete_2").value = '';
    document.getElementById("kd_belanja_2").value = '';
    document.getElementById("uraian_2").value = '';
    document.getElementById("det_uraian_2").value = '';
    document.getElementById("volume_2").value = '';
    document.getElementById("satuan_2").value = '';
    document.getElementById("nominal_satuan_2").value='';
  }

  function generateIndex_2(index) {
    var idx = document.createElement("input");
    idx.type = "hidden";
    idx.name = "index_2[ ]";
    idx.id = "index_2["+index+"]";
    idx.value = index;
    return idx;
  }

  function generateTeksIndex_2(index){
    var teks = document.createTextNode(index);
    return teks;
  }

  function generateSumberDana_2(index) {
    var nilai = $("#sumberdana_2 option:selected").text();
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeSumberDana_2(index) {
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "kd_sumber_dana_2["+index+"]";
    textField.id = "kd_sumber_dana_2["+index+"]";
    textField.value = $("#sumberdana_2").val();

    return textField;
  }

  function generateSubTotal_2(index) {
    var vol = $("#volume_2").autoNumeric('get');
    var nom = $("#nominal_satuan_2").autoNumeric('get');
    var hasil = vol * nom;
    var subtot = hasil.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    var teks = document.createTextNode(subtot);
    return teks;
  }

  function generateTeksJenisBelanja_2(index){
    var nilai = document.getElementById("kode_jenis_belanja_autocomplete_2").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksKategoriBelanja_2(index){
    var nilai = document.getElementById("kode_kategori_belanja_autocomplete_2").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeJenisBelanja_2(index){
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "in_jenis_belanja_2["+index+"]";
    textField.id = "in_jenis_belanja_2["+index+"]";
    textField.value = document.getElementById("kd_jenis_belanja_2").value;

    return textField;
  }

  function generateKodeKategoriBelanja_2(index){
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "in_kategori_belanja_2["+index+"]";
    textField.id = "in_kategori_belanja_2["+index+"]";
    textField.value = document.getElementById("kd_jenis_belanja_2").value;
    alert(textField.value);
    return textField;
  }

  function generateKodeSubKategoriBelanja_2(index){
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "in_subkategori_belanja_2["+index+"]";
    textField.id = "in_subkategori_belanja_2["+index+"]";
    textField.value = document.getElementById("kd_subkategori_belanja_2").value;
    alert(textField.value);
    return textField;
  }

  function generateTeksSubKategoriBelanja_2(index){
    var nilai = document.getElementById("kode_subkategori_belanja_autocomplete_2").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeBelanja_2(index){
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "in_belanja_2["+index+"]";
    textField.id = "in_belanja_2["+index+"]";
    textField.value = document.getElementById("kd_belanja_2").value;
    alert("kodebelanja"+textField.value);
    return textField;
  }

  function generateTeksBelanja_2(index){
    var nilai = document.getElementById("kode_belanja_autocomplete_2").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksUraian_2(){
    var nilai = document.getElementById("uraian_2").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeUraian_2(index){
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "in_uraian_2["+index+"]";
    textField.id = "in_uraian_2["+index+"]";
    textField.value = document.getElementById("uraian_2").value;
    alert("uraian"+textField.value);
    return textField;
  }

  function generateTeksDetUraian_2(index){
    var nilai = document.getElementById("det_uraian_2").value;
    var teks = document.createTextNode(nilai);
    return teks;

  }

  function generateTeksVolume_2(index){
    var nilai = document.getElementById("volume_2").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksSatuan_2(index){
    var nilai = document.getElementById("satuan_2").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksNominalSatuan_2(index){
    var nilai = document.getElementById("nominal_satuan_2").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksHapus_2(index){
    var teks = document.createElement("span");
    teks.setAttribute('class', 'icon-remove');
    teks.setAttribute('title', 'Hapus Belanja');
    teks.id = "hapusrow";
    teks.setAttribute('onclick','hapusrow_2(this)');
    teks.setAttribute('style','cursor:pointer;');
    teks.value = "hapus";
    return teks;
  }

  function hapusrow_2(index){
     var row = index.parentNode.parentNode;
     row.parentNode.removeChild(row);
     var tbl = document.getElementById("listbelanja_2");
     var rowLen = tbl.rows.length;
     for (var idx=1; idx<rowLen; idx++) {
       var x = tbl.rows[idx].cells;
       x[0].innerHTML = idx;
     }
     setNominalTahun_2("kurang");
  }

  function setNominalTahun_2(cond) {
    var nominalTahunTemp = $("#nominal_thndpn").autoNumeric('get');
    if (nominalTahunTemp == "" || nominalTahunTemp == null) {
      nominalTahunTemp = 0;
    }
    if (cond == "tambah") {
      volumeTemp = $("#volume_2").autoNumeric('get');
      nominalSatuanTemp = $("#nominal_satuan_2").autoNumeric('get');
      var subTotTemp = volumeTemp * nominalSatuanTemp;
      var total = parseInt(nominalTahunTemp) + subTotTemp;
    } else if (cond == "kurang") {
      var tbl = document.getElementById("listbelanja_2");
      var rowLen = tbl.rows.length;
      var total = 0;
      for (var idx=1; idx<rowLen; idx++) {
        var x = tbl.rows[idx].cells;
        total = parseInt(total) + parseInt(x[11].innerHTML.toString().replace(/\./g , ""));
      }
    }
    document.getElementById("nominal_thndpn").value = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function generateTeksEdit_2(index) {
    var teks = document.createElement("span");
    teks.id = "ubahrow";
    teks.setAttribute('class', 'icon-pencil');
    teks.setAttribute('title', 'Ubah Belanja');
    teks.setAttribute('onclick','ubahrow_2('+index+',this)');
    teks.setAttribute('style','cursor:pointer;');
    teks.value = "Ubah";
    return teks;
  }

  function ubahrow_2(index, ini) {
    var inEdit = $("#isEdit_2").val();

    if (inEdit != "1") {

      $("#isEdit_2").val("1");
      var jenis = $("input[name='r_kd_jenis_belanja_2["+ index +"]']").val();
      var kategori = $("input[name='r_kd_kategori_belanja_2["+ index +"]']").val();
      var sub = $("input[name='r_kd_subkategori_belanja_2["+ index +"]']").val();
      var belanja = $("input[name='r_kd_belanja_2["+ index +"]']").val();
      var volume = $("input[name='r_volume_2["+ index +"]']").val().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      var nominal = $("input[name='r_nominal_satuan_2["+ index +"]']").val().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      var sumber_dana = $("input[name='kd_sumber_dana_2["+ index +"]']").val();

      jenis_belanjanya_2("cb_jenis_belanja_2", jenis);
      kategori_belanjanya_2("cb_kategori_belanja_2", jenis, kategori);
      sub_belanjanya_2("cb_subkategori_belanja_2", jenis, kategori, sub);
      belanja_belanjanya_2("cb_belanja_2", jenis, kategori, sub, belanja);
      sumber_dananya_2("sumberdana_2", sumber_dana);
      $("#uraian_2").val($("input[name='r_uraian_2["+ index +"]']").val());
      $("#det_uraian_2").val($("input[name='r_det_uraian_2["+ index +"]']").val());
      $("#volume_2").val(volume);
      $("#satuan_2").val($("input[name='r_satuan_2["+ index +"]']").val());
      $("#nominal_satuan_2").val(nominal);
      hapusrow_2(ini);
    }else {
      alert("Anda sedang dalam mode ubah data");
    }

  }
