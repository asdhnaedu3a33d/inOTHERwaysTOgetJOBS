function listBelanja_4(clue){
    var tbl = document.getElementById("listbelanja_4");
    var row = tbl.insertRow(tbl.rows.length);
    var inIndex_4 = document.getElementById("inIndex_4").value;

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

    td0.appendChild(generateIndex_4(row.rowIndex));
    //table yang terlihat
    td0.appendChild(generateTeksIndex_4(row.rowIndex));
    tdnew0.appendChild(generateSumberDana_4(row.rowIndex))
    td1.appendChild(generateTextBound_4(row.rowIndex, "cb_jenis_belanja_4"));
    td2.appendChild(generateTextBound_4(row.rowIndex, "cb_kategori_belanja_4"));
    td3.appendChild(generateTextBound_4(row.rowIndex, "cb_subkategori_belanja_4"));
    td4.appendChild(generateTextBound_4(row.rowIndex, "cb_belanja_4"));
    td5.appendChild(generateTeksUraian_4(row.rowIndex));
    td6.appendChild(generateTeksDetUraian_4(row.rowIndex));
    td7.appendChild(generateTeksVolume_4(row.rowIndex));
    td8.appendChild(generateTeksSatuan_4(row.rowIndex));
    td9.appendChild(generateTeksNominalSatuan_4(row.rowIndex));
    tdnew1.appendChild(generateSubTotal_4(row.rowIndex));
    tdEdit.appendChild(generateTeksEdit_4(inIndex_4));
    td10.appendChild(generateTeksHapus_4(row.rowIndex));
    //table yang di-hidden
    tdnew2.appendChild(generateKodeSumberDana_4(inIndex_4));
    td11.appendChild(generateTextBox_4(inIndex_4,"r_kd_jenis_belanja_4","cb_jenis_belanja_4"));
    td12.appendChild(generateTextBox_4(inIndex_4,"r_kd_kategori_belanja_4","cb_kategori_belanja_4"));
    td13.appendChild(generateTextBox_4(inIndex_4,"r_kd_subkategori_belanja_4","cb_subkategori_belanja_4"));
    td14.appendChild(generateTextBox_4(inIndex_4,"r_kd_belanja_4","cb_belanja_4"));
    td15.appendChild(generateTextBox_4(inIndex_4,"r_uraian_4","uraian_4"));
    td16.appendChild(generateTextBox_4(inIndex_4,"r_det_uraian_4","det_uraian_4"));
    td17.appendChild(generateTextBoxNilai_4(inIndex_4,"r_volume_4","volume_4"));
    td18.appendChild(generateTextBox_4(inIndex_4,"r_satuan_4","satuan_4"));
    td19.appendChild(generateTextBoxNilai_4(inIndex_4,"r_nominal_satuan_4","nominal_satuan_4"));
    td20.appendChild(generateTotalSub_4(inIndex_4));

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
    setNominalTahun_4("tambah");
    document.getElementById("inIndex_4").value = parseInt(inIndex_4) + 1;
    $("#isEdit_4").val("0");

    if (clue=='all') {
      kosongkan_4();
    }
    else if (clue=='jns') {
      document.getElementById("cb_jenis_belanja_4").value = '';
      $("#cb_jenis_belanja_4").trigger("chosen:updated");
      document.getElementById("cb_kategori_belanja_4").value = '';
      $("#cb_kategori_belanja_4").trigger("chosen:updated");
      document.getElementById("cb_subkategori_belanja_4").value = '';
      $("#cb_subkategori_belanja_4").trigger("chosen:updated");
      document.getElementById("cb_belanja_4").value = '';
      $("#cb_belanja_4").trigger("chosen:updated");
      document.getElementById("uraian_4").value = '';
      document.getElementById("det_uraian_4").value = '';
      document.getElementById("volume_4").value = '';
      document.getElementById("satuan_4").value = '';
      document.getElementById("nominal_satuan_4").value='';
    }
    else if (clue=='kat') {
      document.getElementById("cb_kategori_belanja_4").value = '';
      $("#cb_kategori_belanja_4").trigger("chosen:updated");
      document.getElementById("cb_subkategori_belanja_4").value = '';
      $("#cb_subkategori_belanja_4").trigger("chosen:updated");
      document.getElementById("cb_belanja_4").value = '';
      $("#cb_belanja_4").trigger("chosen:updated");
      document.getElementById("uraian_4").value = '';
      document.getElementById("det_uraian_4").value = '';
      document.getElementById("volume_4").value = '';
      document.getElementById("satuan_4").value = '';
      document.getElementById("nominal_satuan_4").value='';
    }
    else if (clue=='subkat') {
      document.getElementById("cb_subkategori_belanja_4").value = '';
      $("#cb_subkategori_belanja_4").trigger("chosen:updated");
      document.getElementById("cb_belanja_4").value = '';
      $("#cb_belanja_4").trigger("chosen:updated");
      document.getElementById("uraian_4").value = '';
      document.getElementById("det_uraian_4").value = '';
      document.getElementById("volume_4").value = '';
      document.getElementById("satuan_4").value = '';
      document.getElementById("nominal_satuan_4").value='';
    }else if (clue=='belanja') {
      document.getElementById("cb_belanja_4").value = '';
      $("#cb_belanja_4").trigger("chosen:updated");
      document.getElementById("uraian_4").value = '';
      document.getElementById("det_uraian_4").value = '';
      document.getElementById("volume_4").value = '';
      document.getElementById("satuan_4").value = '';
      document.getElementById("nominal_satuan_4").value='';
    }else if (clue=='uraian') {
      document.getElementById("uraian_4").value = '';
      document.getElementById("det_uraian_4").value = '';
      document.getElementById("volume_4").value = '';
      document.getElementById("satuan_4").value = '';
      document.getElementById("nominal_satuan_4").value='';
    }else if (clue=='deturaian') {
      document.getElementById("det_uraian_4").value = '';
      document.getElementById("volume_4").value = '';
      document.getElementById("satuan_4").value = '';
      document.getElementById("nominal_satuan_4").value='';
    }


  }

  function generateTextBox_4(index,nama,isi) {
    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = nama+"["+index+"]";
    idx.id = nama+"["+index+"]";
    idx.value = document.getElementById(isi).value;
    return idx;
  }

  function generateTextBoxNilai_4(index,nama,isi) {
    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = nama+"["+index+"]";
    idx.id = nama+"["+index+"]";
    idx.value = $("#"+isi).autoNumeric('get');
    return idx;
  }

  function generateTextBound_4(index, nama) {
    var nilai = $("#"+nama+" option:selected").text();
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTotalSub_4(index) {
    var vol = $("#volume_4").autoNumeric('get');
    var nom = $("#nominal_satuan_4").autoNumeric('get');
    var hasil = vol * nom;
    var subtot = hasil;

    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = "r_subtotal_4["+index+"]";
    idx.id = "r_subtotal_4["+index+"]";
    idx.value = subtot;
    return idx;
  }

  function kosongkan_4(){
    document.getElementById("kode_jenis_belanja_autocomplete_4").value = '';
    document.getElementById("kd_jenis_belanja_4").value = '';
    document.getElementById("kode_kategori_belanja_autocomplete_4").value = '';
    document.getElementById("kd_kategori_belanja_4").value = '';
    document.getElementById("kode_subkategori_belanja_autocomplete_4").value = '';
    document.getElementById("kd_subkategori_belanja_4").value = '';
    document.getElementById("kode_belanja_autocomplete_4").value = '';
    document.getElementById("kd_belanja_4").value = '';
    document.getElementById("uraian_4").value = '';
    document.getElementById("det_uraian_4").value = '';
    document.getElementById("volume_4").value = '';
    document.getElementById("satuan_4").value = '';
    document.getElementById("nominal_satuan_4").value='';
  }

  function generateIndex_4(index) {
    var idx = document.createElement("input");
    idx.type = "hidden";
    idx.name = "index_4[ ]";
    idx.id = "index_4["+index+"]";
    idx.value = index;
    return idx;
  }

  function generateTeksIndex_4(index){
    var teks = document.createTextNode(index);
    return teks;
  }

  function generateSumberDana_4(index) {
    var nilai = $("#sumberdana_4 option:selected").text();
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeSumberDana_4(index) {
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "kd_sumber_dana_4["+index+"]";
    textField.id = "kd_sumber_dana_4["+index+"]";
    textField.value = $("#sumberdana_4").val();

    return textField;
  }

  function generateSubTotal_4(index) {
    var vol = $("#volume_4").autoNumeric('get');
    var nom = $("#nominal_satuan_4").autoNumeric('get');
    var hasil = vol * nom;
    var subtot = hasil.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    var teks = document.createTextNode(subtot);
    return teks;
  }

  function generateTeksJenisBelanja_4(index){

    var nilai = document.getElementById("kode_jenis_belanja_autocomplete_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeJenisBelanja_4(){
    var nilai = document.getElementById("kd_jenis_belanja_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeKategoriBelanja_4(){
    var nilai = document.getElementById("kd_kategori_belanja_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksKategoriBelanja_4(index){
    var nilai = document.getElementById("kode_kategori_belanja_autocomplete_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateSubKategoriBelanja(index) {
    var subkatbelanja = document.createElement("input");
    subkatbelanja.type = "hidden";
    subkatbelanja.name = "subkatbelanja_4[ ]";
    subkatbelanja.id = "subkatbelanja_4["+index+"]";
    subkatbelanja.value = document.getElementById("kd_subkategori_belanja_4").value;
    return subkatbelanja;
  }

  function generateKodeSubKategoriBelanja_4(){
    var nilai = document.getElementById("kd_subkategori_belanja_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksSubKategoriBelanja_4(index){
    var nilai = document.getElementById("kode_subkategori_belanja_autocomplete_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateBelanja(index) {
    var belanja = document.createElement("input");

    belanja.type = "hidden";
    belanja.name = "belanja_4[ ]";
    belanja.id = "belanja_4["+index+"]";
    belanja.value = document.getElementById("kd_belanja_4").value;
    return belanja;
  }

  function generateKodeBelanja_4(){
    var nilai = document.getElementById("kd_belanja_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksBelanja_4(index){
    var nilai = document.getElementById("kode_belanja_autocomplete_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksUraian_4(index){
    var nilai = document.getElementById("uraian_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeUraian_4(){
    var nilai = document.getElementById("uraian_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksDetUraian_4(index){
    var nilai = document.getElementById("det_uraian_4").value;
    var teks = document.createTextNode(nilai);
    return teks;

  }

  function generateTeksVolume_4(index){
    var nilai = document.getElementById("volume_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksSatuan_4(index){
    var nilai = document.getElementById("satuan_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksNominalSatuan_4(index){
    var nilai = document.getElementById("nominal_satuan_4").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksHapus_4(index){
    var teks = document.createElement("span");
    teks.setAttribute('class', 'icon-remove');
    teks.setAttribute('title', 'Hapus Belanja');
    teks.id = "hapusrow";
    teks.setAttribute('onclick','hapusrow_4(this)');
    teks.setAttribute('style','cursor:pointer;');
    teks.value = "hapus";
    return teks;
  }

  function hapusrow_4(index){
     var row = index.parentNode.parentNode;
     row.parentNode.removeChild(row);
     var tbl = document.getElementById("listbelanja_4");
     var rowLen = tbl.rows.length;
     for (var idx=1; idx<rowLen; idx++) {
       var x = tbl.rows[idx].cells;
       x[0].innerHTML = idx;
     }
     setNominalTahun_4("kurang");
  }

  function setNominalTahun_4(cond) {
    var nominalTahunTemp = $("#nominal_4").autoNumeric('get');
    if (nominalTahunTemp == "" || nominalTahunTemp == null) {
      nominalTahunTemp = 0;
    }
    if (cond == "tambah") {
      volumeTemp = $("#volume_4").autoNumeric('get');
      nominalSatuanTemp = $("#nominal_satuan_4").autoNumeric('get');
      var subTotTemp = volumeTemp * nominalSatuanTemp;
      var total = parseInt(nominalTahunTemp) + subTotTemp;
    } else if (cond == "kurang") {
      var tbl = document.getElementById("listbelanja_4");
      var rowLen = tbl.rows.length;
      var total = 0;
      for (var idx=1; idx<rowLen; idx++) {
        var x = tbl.rows[idx].cells;
        total = parseInt(total) + parseInt(x[11].innerHTML.toString().replace(/\./g , ""));
      }
    }
    document.getElementById("nominal_4").value = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function generateTeksEdit_4(index) {
    var teks = document.createElement("span");
    teks.id = "ubahrow";
    teks.setAttribute('class', 'icon-pencil');
    teks.setAttribute('title', 'Ubah Belanja');
    teks.setAttribute('onclick','ubahrow_4('+index+',this)');
    teks.setAttribute('style','cursor:pointer;');
    teks.value = "Ubah";
    return teks;
  }

  function ubahrow_4(index, ini) {
    var inEdit = $("#isEdit_4").val();

    if (inEdit != "1") {
      $("#isEdit_4").val("1");
      var jenis = $("input[name='r_kd_jenis_belanja_4["+ index +"]']").val();
      var kategori = $("input[name='r_kd_kategori_belanja_4["+ index +"]']").val();
      var sub = $("input[name='r_kd_subkategori_belanja_4["+ index +"]']").val();
      var belanja = $("input[name='r_kd_belanja_4["+ index +"]']").val();
      var volume = $("input[name='r_volume_4["+ index +"]']").val().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      var nominal = $("input[name='r_nominal_satuan_4["+ index +"]']").val().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      var sumber_dana = $("input[name='kd_sumber_dana_4["+ index +"]']").val();

      jenis_belanjanya_4("cb_jenis_belanja_4", jenis);
      kategori_belanjanya_4("cb_kategori_belanja_4", jenis, kategori);
      sub_belanjanya_4("cb_subkategori_belanja_4", jenis, kategori, sub);
      belanja_belanjanya_4("cb_belanja_4", jenis, kategori, sub, belanja);
      sumber_dananya_4("sumberdana_4", sumber_dana);
      $("#uraian_4").val($("input[name='r_uraian_4["+ index +"]']").val());
      $("#det_uraian_4").val($("input[name='r_det_uraian_4["+ index +"]']").val());
      $("#volume_4").val(volume);
      $("#satuan_4").val($("input[name='r_satuan_4["+ index +"]']").val());
      $("#nominal_satuan_4").val(nominal);
      hapusrow_4(ini);
    }else {
      alert("Anda sedang dalam mode ubah data");
    }
  }

  function copyrow_4() {
    var tbl_1 = document.getElementById("listbelanja_4");
    var tbl_2 = document.getElementById("listbelanja_5");

    var rowLen = tbl_1.rows.length;

    for (var idx=1; idx<rowLen; idx++) {
      var x = tbl_1.rows[idx];
      var row = tbl_2.insertRow(tbl_2.rows.length);
      var inIndex_5 = document.getElementById("inIndex_5").value;
      //row.id = row.rowIndex;

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
      var tdEdit = document.createElement("td");
      var tdHapus = document.createElement("td");

      var td12 = document.createElement("td");   td12.style.display = 'none';
      var td13 = document.createElement("td");   td13.style.display = 'none';
      var td14 = document.createElement("td");   td14.style.display = 'none';
      var td15 = document.createElement("td");   td15.style.display = 'none';
      var td16 = document.createElement("td");   td16.style.display = 'none';
      var td17 = document.createElement("td");   td17.style.display = 'none';
      var td18 = document.createElement("td");   td18.style.display = 'none';
      var td19 = document.createElement("td");   td19.style.display = 'none';
      var td20 = document.createElement("td");   td20.style.display = 'none';
      var td21 = document.createElement("td");   td21.style.display = 'none';
      var td22 = document.createElement("td");   td22.style.display = 'none';

      td0.appendChild(copy_textnode(inIndex_5));
      td1.appendChild(copy_textnode(x.cells[1].innerHTML));
      td2.appendChild(copy_textnode(x.cells[2].innerHTML));
      td3.appendChild(copy_textnode(x.cells[3].innerHTML));
      td4.appendChild(copy_textnode(x.cells[4].innerHTML));
      td5.appendChild(copy_textnode(x.cells[5].innerHTML));
      td6.appendChild(copy_textnode(x.cells[6].innerHTML));
      td7.appendChild(copy_textnode(x.cells[7].innerHTML));
      td8.appendChild(copy_textnode(x.cells[8].innerHTML));
      td9.appendChild(copy_textnode(x.cells[9].innerHTML));
      td10.appendChild(copy_textnode(x.cells[10].innerHTML));
      td11.appendChild(copy_textnode(x.cells[11].innerHTML));
      tdEdit.appendChild(generateTeksEdit_5(inIndex_5));
      tdHapus.appendChild(generateTeksHapus_5(inIndex_5));

      td12.appendChild(copy_inputnode(inIndex_5, "kd_sumber_dana_5", x.cells[14].getElementsByTagName('input')[0].value));
      td13.appendChild(copy_inputnode(inIndex_5, "r_kd_jenis_belanja_5", x.cells[15].getElementsByTagName('input')[0].value));
      td14.appendChild(copy_inputnode(inIndex_5, "r_kd_kategori_belanja_5", x.cells[16].getElementsByTagName('input')[0].value));
      td15.appendChild(copy_inputnode(inIndex_5, "r_kd_subkategori_belanja_5", x.cells[17].getElementsByTagName('input')[0].value));
      td16.appendChild(copy_inputnode(inIndex_5, "r_kd_belanja_5", x.cells[18].getElementsByTagName('input')[0].value));
      td17.appendChild(copy_inputnode(inIndex_5, "r_uraian_5", x.cells[19].getElementsByTagName('input')[0].value));
      td18.appendChild(copy_inputnode(inIndex_5, "r_det_uraian_5", x.cells[20].getElementsByTagName('input')[0].value));
      td19.appendChild(copy_inputnode(inIndex_5, "r_volume_5", x.cells[21].getElementsByTagName('input')[0].value));
      td20.appendChild(copy_inputnode(inIndex_5, "r_satuan_5", x.cells[22].getElementsByTagName('input')[0].value));
      td21.appendChild(copy_inputnode(inIndex_5, "r_nominal_satuan_5", x.cells[23].getElementsByTagName('input')[0].value));
      td22.appendChild(copy_inputnode(inIndex_5, "r_subtotal_5", x.cells[24].getElementsByTagName('input')[0].value));


      row.id = inIndex_5;
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
      row.appendChild(tdEdit);
      row.appendChild(tdHapus);

      row.appendChild(td12);
      row.appendChild(td13);
      row.appendChild(td14);
      row.appendChild(td15);
      row.appendChild(td16);
      row.appendChild(td17);
      row.appendChild(td18);
      row.appendChild(td19);
      row.appendChild(td20);
      row.appendChild(td21);
      row.appendChild(td22);

      document.getElementById("inIndex_5").value = parseInt(inIndex_5) + 1;
      setNominalTahun_5("kurang");
    }
  }
