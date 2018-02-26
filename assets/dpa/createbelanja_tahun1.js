function listBelanja_1(clue){
    var tbl = document.getElementById("listbelanja_1");
    var row = tbl.insertRow(tbl.rows.length);
    var inIndex_1 = document.getElementById("inIndex_1").value;

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
    td0.appendChild(generateTeksIndex_1(row.rowIndex));
    tdnew0.appendChild(generateSumberDana_1(row.rowIndex))
    td1.appendChild(generateTextBound_1(row.rowIndex, "cb_jenis_belanja_1"));
    td2.appendChild(generateTextBound_1(row.rowIndex, "cb_kategori_belanja_1"));
    td3.appendChild(generateTextBound_1(row.rowIndex, "cb_subkategori_belanja_1"));
    td4.appendChild(generateTextBound_1(row.rowIndex, "cb_belanja_1"));
    td5.appendChild(generateTeksUraian_1(row.rowIndex));
    td6.appendChild(generateTeksDetUraian_1(row.rowIndex));
    td7.appendChild(generateTeksVolume_1(row.rowIndex));
    td8.appendChild(generateTeksSatuan_1(row.rowIndex));
    td9.appendChild(generateTeksNominalSatuan_1(row.rowIndex));
    tdnew1.appendChild(generateSubTotal_1(row.rowIndex))
    tdEdit.appendChild(generateTeksEdit_1(inIndex_1));
    td10.appendChild(generateTeksHapus_1(row.rowIndex));
    //table yang di-hidden
    tdnew2.appendChild(generateKodeSumberDana_1(inIndex_1));
    td11.appendChild(generateTextBox_1(inIndex_1,"r_kd_jenis_belanja_1","cb_jenis_belanja_1"));
    td12.appendChild(generateTextBox_1(inIndex_1,"r_kd_kategori_belanja_1","cb_kategori_belanja_1"));
    td13.appendChild(generateTextBox_1(inIndex_1,"r_kd_subkategori_belanja_1","cb_subkategori_belanja_1"));
    td14.appendChild(generateTextBox_1(inIndex_1,"r_kd_belanja_1","cb_belanja_1"));
    td15.appendChild(generateTextBox_1(inIndex_1,"r_uraian_1","uraian_1"));
    td16.appendChild(generateTextBox_1(inIndex_1,"r_det_uraian_1","det_uraian_1"));
    td17.appendChild(generateTextBoxNilai_1(inIndex_1,"r_volume_1","volume_1"));
    td18.appendChild(generateTextBox_1(inIndex_1,"r_satuan_1","satuan_1"));
    td19.appendChild(generateTextBoxNilai_1(inIndex_1,"r_nominal_satuan_1","nominal_satuan_1"));
    td20.appendChild(generateTotalSub_1(inIndex_1));


    row.id = row.rowIndex;
    row.appendChild(td0);
    row.appendChild(tdnew0);
    row.appendChild(td1);
    row.appendChild(td2);
    row.appendChild(td3);
    row.appendChild(td4);
    row.appendChild(td5);
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
    setNominalTahun_1("tambah");
    document.getElementById("inIndex_1").value = parseInt(inIndex_1) + 1;
    $("#isEdit_1").val("0");

    if (clue=='all') {
      kosongkan_1();
    }
    else if (clue=='jns') {
      document.getElementById("cb_jenis_belanja_1").value = '';
      $("#cb_jenis_belanja_1").trigger("chosen:updated");
      document.getElementById("cb_kategori_belanja_1").value = '';
      $("#cb_kategori_belanja_1").trigger("chosen:updated");
      document.getElementById("cb_subkategori_belanja_1").value = '';
      $("#cb_subkategori_belanja_1").trigger("chosen:updated");
      document.getElementById("cb_belanja_1").value = '';
      $("#cb_belanja_1").trigger("chosen:updated");
      document.getElementById("uraian_1").value = '';
      document.getElementById("det_uraian_1").value = '';
      document.getElementById("volume_1").value = '';
      document.getElementById("satuan_1").value = '';
      document.getElementById("nominal_satuan_1").value='';
    }
    else if (clue=='kat') {
      document.getElementById("cb_kategori_belanja_1").value = '';
      $("#cb_kategori_belanja_1").trigger("chosen:updated");
      document.getElementById("cb_subkategori_belanja_1").value = '';
      $("#cb_subkategori_belanja_1").trigger("chosen:updated");
      document.getElementById("cb_belanja_1").value = '';
      $("#cb_belanja_1").trigger("chosen:updated");
      document.getElementById("uraian_1").value = '';
      document.getElementById("det_uraian_1").value = '';
      document.getElementById("volume_1").value = '';
      document.getElementById("satuan_1").value = '';
      document.getElementById("nominal_satuan_1").value='';
    }
    else if (clue=='subkat') {
      document.getElementById("cb_subkategori_belanja_1").value = '';
      $("#cb_subkategori_belanja_1").trigger("chosen:updated");
      document.getElementById("cb_belanja_1").value = '';
      $("#cb_belanja_1").trigger("chosen:updated");
      document.getElementById("uraian_1").value = '';
      document.getElementById("det_uraian_1").value = '';
      document.getElementById("volume_1").value = '';
      document.getElementById("satuan_1").value = '';
      document.getElementById("nominal_satuan_1").value='';
    }else if (clue=='belanja') {
      document.getElementById("cb_belanja_1").value = '';
      $("#cb_belanja_1").trigger("chosen:updated");
      document.getElementById("uraian_1").value = '';
      document.getElementById("det_uraian_1").value = '';
      document.getElementById("volume_1").value = '';
      document.getElementById("satuan_1").value = '';
      document.getElementById("nominal_satuan_1").value='';
    }else if (clue=='uraian') {
      document.getElementById("uraian_1").value = '';
      document.getElementById("det_uraian_1").value = '';
      document.getElementById("volume_1").value = '';
      document.getElementById("satuan_1").value = '';
      document.getElementById("nominal_satuan_1").value='';
    }else if (clue=='deturaian') {
      document.getElementById("det_uraian_1").value = '';
      document.getElementById("volume_1").value = '';
      document.getElementById("satuan_1").value = '';
      document.getElementById("nominal_satuan_1").value='';
    }


  }

  function generateTextBox_1(index,nama,isi) {
    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = nama+"["+index+"]";
    idx.id = nama+"["+index+"]";
    idx.value = document.getElementById(isi).value;
    return idx;
  }

  function generateTextBoxNilai_1(index,nama,isi) {
    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = nama+"["+index+"]";
    idx.id = nama+"["+index+"]";
    idx.value = $("#"+isi).autoNumeric('get');
    return idx;
  }

  function generateTextBound_1(index, nama) {
    var nilai = $("#"+nama+" option:selected").text();
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTotalSub_1(index) {
    var vol = $("#volume_1").autoNumeric('get');
    var nom = $("#nominal_satuan_1").autoNumeric('get');
    var hasil = vol * nom;
    var subtot = hasil;

    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = "r_subtotal_1["+index+"]";
    idx.id = "r_subtotal_1["+index+"]";
    idx.value = subtot;
    return idx;
  }

  function kosongkan_1(){
    document.getElementById("kode_jenis_belanja_autocomplete_1").value = '';
    document.getElementById("kd_jenis_belanja_1").value = '';
    document.getElementById("kode_kategori_belanja_autocomplete_1").value = '';
    document.getElementById("kd_kategori_belanja_1").value = '';
    document.getElementById("kode_subkategori_belanja_autocomplete_1").value = '';
    document.getElementById("kd_subkategori_belanja_1").value = '';
    document.getElementById("kode_belanja_autocomplete_1").value = '';
    document.getElementById("kd_belanja_1").value = '';
    document.getElementById("uraian_1").value = '';
    document.getElementById("det_uraian_1").value = '';
    document.getElementById("volume_1").value = '';
    document.getElementById("satuan_1").value = '';
    document.getElementById("nominal_satuan_1").value='';
  }

  function generateIndex_1(index) {
    var idx = document.createElement("input");
    idx.type = "hidden";
    idx.name = "index_1[ ]";
    idx.id = "index_1["+index+"]";
    idx.value = index;
    return idx;
  }

  function generateTeksIndex_1(index){
    var teks = document.createTextNode(index);
    return teks;
  }

  function generateSumberDana_1(index) {
    var nilai = $("#sumberdana_1 option:selected").text();
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeSumberDana_1(index) {
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "kd_sumber_dana_1["+index+"]";
    textField.id = "kd_sumber_dana_1["+index+"]";
    textField.value = $("#sumberdana_1").val();

    return textField;
  }

  function generateSubTotal_1(index) {
    var vol = $("#volume_1").autoNumeric('get');
    var nom = $("#nominal_satuan_1").autoNumeric('get');
    var hasil = vol * nom;
    var subtot = hasil.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    var teks = document.createTextNode(subtot);
    return teks;
  }

  function generateTeksJenisBelanja_1(index){
    var nilai = document.getElementById("kode_jenis_belanja_autocomplete_1").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksKategoriBelanja_1(index){
    var nilai = document.getElementById("kode_kategori_belanja_autocomplete_1").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeJenisBelanja_1(index){
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "in_jenis_belanja_1["+index+"]";
    textField.id = "in_jenis_belanja_1["+index+"]";
    textField.value = document.getElementById("kd_jenis_belanja_1").value;

    return textField;
  }

  function generateKodeKategoriBelanja_1(index){
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "in_kategori_belanja_1["+index+"]";
    textField.id = "in_kategori_belanja_1["+index+"]";
    textField.value = document.getElementById("kd_jenis_belanja_1").value;
    alert(textField.value);
    return textField;
  }

  function generateKodeSubKategoriBelanja_1(index){
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "in_subkategori_belanja_1["+index+"]";
    textField.id = "in_subkategori_belanja_1["+index+"]";
    textField.value = document.getElementById("kd_subkategori_belanja_1").value;
    alert(textField.value);
    return textField;
  }

  function generateTeksSubKategoriBelanja_1(index){
    var nilai = document.getElementById("kode_subkategori_belanja_autocomplete_1").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeBelanja_1(index){
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "in_belanja_1["+index+"]";
    textField.id = "in_belanja_1["+index+"]";
    textField.value = document.getElementById("kd_belanja_1").value;
    return textField;
  }

  function generateTeksBelanja_1(index){
    var nilai = document.getElementById("kode_belanja_autocomplete_1").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksUraian_1(){
    var nilai = document.getElementById("uraian_1").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeUraian_1(index){
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "in_uraian_1["+index+"]";
    textField.id = "in_uraian_1["+index+"]";
    textField.value = document.getElementById("uraian_1").value;
    alert("uraian"+textField.value);
    return textField;
  }

  function generateTeksDetUraian_1(index){
    var nilai = document.getElementById("det_uraian_1").value;
    var teks = document.createTextNode(nilai);
    return teks;

  }

  function generateTeksVolume_1(index){
    var nilai = document.getElementById("volume_1").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksSatuan_1(index){
    var nilai = document.getElementById("satuan_1").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksNominalSatuan_1(index){
    var nilai = document.getElementById("nominal_satuan_1").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksHapus_1(index){
    var teks = document.createElement("span");
    teks.setAttribute('class', 'icon-remove');
    teks.setAttribute('title', 'Hapus Belanja');
    teks.id = "hapusrow";
    teks.setAttribute('onclick','hapusrow_1(this)');
    teks.setAttribute('style','cursor:pointer;');
    teks.value = "hapus";
    return teks;
  }

  function hapusrow_1(index){
     var row = index.parentNode.parentNode;
     row.parentNode.removeChild(row);
     var tbl = document.getElementById("listbelanja_1");
     var rowLen = tbl.rows.length;
     for (var idx=1; idx<rowLen; idx++) {
       var x = tbl.rows[idx].cells;
       x[0].innerHTML = idx;
     }
     setNominalTahun_1("kurang");
  }

  function setNominalTahun_1(cond) {
    var nominalTahunTemp = $("#total_belanja").autoNumeric('get');
    if (nominalTahunTemp == "" || nominalTahunTemp == null) {
      nominalTahunTemp = 0;
    }
    if (cond == "tambah") {
      volumeTemp = $("#volume_1").autoNumeric('get');
      nominalSatuanTemp = $("#nominal_satuan_1").autoNumeric('get');
      var subTotTemp = volumeTemp * nominalSatuanTemp;
      var total = parseInt(nominalTahunTemp) + subTotTemp;
    } else if (cond == "kurang") {
      var tbl = document.getElementById("listbelanja_1");
      var rowLen = tbl.rows.length;
      var total = 0;
      for (var idx=1; idx<rowLen; idx++) {
        var x = tbl.rows[idx].cells;
        total = parseInt(total) + parseInt(x[11].innerHTML.toString().replace(/\./g , ""));
      }
    }
    document.getElementById("total_belanja").value = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function generateTeksEdit_1(index) {
    var teks = document.createElement("span");
    teks.id = "ubahrow";
    teks.setAttribute('class', 'icon-pencil');
    teks.setAttribute('title', 'Ubah Belanja');
    teks.setAttribute('onclick','ubahrow_1('+index+',this)');
    teks.setAttribute('style','cursor:pointer;');
    teks.value = "Ubah";
    return teks;
  }

  function ubahrow_1(index, ini) {
    var inEdit = $("#isEdit_1").val();

    if (inEdit != "1") {

      $("#isEdit_1").val("1");
      var jenis = $("input[name='r_kd_jenis_belanja_1["+ index +"]']").val();
      var kategori = $("input[name='r_kd_kategori_belanja_1["+ index +"]']").val();
      var sub = $("input[name='r_kd_subkategori_belanja_1["+ index +"]']").val();
      var belanja = $("input[name='r_kd_belanja_1["+ index +"]']").val();
      var volume = $("input[name='r_volume_1["+ index +"]']").val().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      var nominal = $("input[name='r_nominal_satuan_1["+ index +"]']").val().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

      $("#sumberdana_1").val($("input[name='kd_sumber_dana_1["+ index +"]']").val());
      jenis_belanjanya_1("cb_jenis_belanja_1", jenis);
      kategori_belanjanya_1("cb_kategori_belanja_1", jenis, kategori);
      sub_belanjanya_1("cb_subkategori_belanja_1", jenis, kategori, sub);
      belanja_belanjanya_1("cb_belanja_1", jenis, kategori, sub, belanja);
      $("#uraian_1").val($("input[name='r_uraian_1["+ index +"]']").val());
      $("#det_uraian_1").val($("input[name='r_det_uraian_1["+ index +"]']").val());
      $("#volume_1").val(volume);
      $("#satuan_1").val($("input[name='r_satuan_1["+ index +"]']").val());
      $("#nominal_satuan_1").val(nominal);
      hapusrow_1(ini);
    }else {
      alert("Anda sedang dalam mode ubah data");
    }

  }
