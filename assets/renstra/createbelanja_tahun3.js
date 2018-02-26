function listBelanja_3(clue){
    var tbl = document.getElementById("listbelanja_3");
    var row = tbl.insertRow(tbl.rows.length);
    var inIndex_3 = document.getElementById("inIndex_3").value;

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

    td0.appendChild(generateIndex_3(row.rowIndex));
    //table yang terlihat
    td0.appendChild(generateTeksIndex_3(row.rowIndex));
    tdnew0.appendChild(generateSumberDana_3(row.rowIndex))
    td1.appendChild(generateTextBound_3(row.rowIndex, "cb_jenis_belanja_3"));
    td2.appendChild(generateTextBound_3(row.rowIndex, "cb_kategori_belanja_3"));
    td3.appendChild(generateTextBound_3(row.rowIndex, "cb_subkategori_belanja_3"));
    td4.appendChild(generateTextBound_3(row.rowIndex, "cb_belanja_3"));
    td5.appendChild(generateTeksUraian_3(row.rowIndex));
    td6.appendChild(generateTeksDetUraian_3(row.rowIndex));
    td7.appendChild(generateTeksVolume_3(row.rowIndex));
    td8.appendChild(generateTeksSatuan_3(row.rowIndex));
    td9.appendChild(generateTeksNominalSatuan_3(row.rowIndex));
    tdnew1.appendChild(generateSubTotal_3(row.rowIndex));
    tdEdit.appendChild(generateTeksEdit_3(inIndex_3));
    td10.appendChild(generateTeksHapus_3(row.rowIndex));
    //table yang di-hidden
    tdnew2.appendChild(generateKodeSumberDana_3(inIndex_3));
    td11.appendChild(generateTextBox_3(inIndex_3,"r_kd_jenis_belanja_3","cb_jenis_belanja_3"));
    td12.appendChild(generateTextBox_3(inIndex_3,"r_kd_kategori_belanja_3","cb_kategori_belanja_3"));
    td13.appendChild(generateTextBox_3(inIndex_3,"r_kd_subkategori_belanja_3","cb_subkategori_belanja_3"));
    td14.appendChild(generateTextBox_3(inIndex_3,"r_kd_belanja_3","cb_belanja_3"));
    td15.appendChild(generateTextBox_3(inIndex_3,"r_uraian_3","uraian_3"));
    td16.appendChild(generateTextBox_3(inIndex_3,"r_det_uraian_3","det_uraian_3"));
    td17.appendChild(generateTextBoxNilai_3(inIndex_3,"r_volume_3","volume_3"));
    td18.appendChild(generateTextBox_3(inIndex_3,"r_satuan_3","satuan_3"));
    td19.appendChild(generateTextBoxNilai_3(inIndex_3,"r_nominal_satuan_3","nominal_satuan_3"));
    td20.appendChild(generateTotalSub_3(inIndex_3));

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
    setNominalTahun_3("tambah");
    document.getElementById("inIndex_3").value = parseInt(inIndex_3) + 1;
    $("#isEdit_3").val("0");

    if (clue=='all') {
      kosongkan_3();
    }
    else if (clue=='jns') {
      document.getElementById("cb_jenis_belanja_3").value = '';
      $("#cb_jenis_belanja_3").trigger("chosen:updated");
      document.getElementById("cb_kategori_belanja_3").value = '';
      $("#cb_kategori_belanja_3").trigger("chosen:updated");
      document.getElementById("cb_subkategori_belanja_3").value = '';
      $("#cb_subkategori_belanja_3").trigger("chosen:updated");
      document.getElementById("cb_belanja_3").value = '';
      $("#cb_belanja_3").trigger("chosen:updated");
      document.getElementById("uraian_3").value = '';
      document.getElementById("det_uraian_3").value = '';
      document.getElementById("volume_3").value = '';
      document.getElementById("satuan_3").value = '';
      document.getElementById("nominal_satuan_3").value='';
    }
    else if (clue=='kat') {
      document.getElementById("cb_kategori_belanja_3").value = '';
      $("#cb_kategori_belanja_3").trigger("chosen:updated");
      document.getElementById("cb_subkategori_belanja_3").value = '';
      $("#cb_subkategori_belanja_3").trigger("chosen:updated");
      document.getElementById("cb_belanja_3").value = '';
      $("#cb_belanja_3").trigger("chosen:updated");
      document.getElementById("uraian_3").value = '';
      document.getElementById("det_uraian_3").value = '';
      document.getElementById("volume_3").value = '';
      document.getElementById("satuan_3").value = '';
      document.getElementById("nominal_satuan_3").value='';
    }
    else if (clue=='subkat') {
      document.getElementById("cb_subkategori_belanja_3").value = '';
      $("#cb_subkategori_belanja_3").trigger("chosen:updated");
      document.getElementById("cb_belanja_3").value = '';
      $("#cb_belanja_3").trigger("chosen:updated");
      document.getElementById("uraian_3").value = '';
      document.getElementById("det_uraian_3").value = '';
      document.getElementById("volume_3").value = '';
      document.getElementById("satuan_3").value = '';
      document.getElementById("nominal_satuan_3").value='';
    }else if (clue=='belanja') {
      document.getElementById("cb_belanja_3").value = '';
      $("#cb_belanja_3").trigger("chosen:updated");
      document.getElementById("uraian_3").value = '';
      document.getElementById("det_uraian_3").value = '';
      document.getElementById("volume_3").value = '';
      document.getElementById("satuan_3").value = '';
      document.getElementById("nominal_satuan_3").value='';
    }else if (clue=='uraian') {
      document.getElementById("uraian_3").value = '';
      document.getElementById("det_uraian_3").value = '';
      document.getElementById("volume_3").value = '';
      document.getElementById("satuan_3").value = '';
      document.getElementById("nominal_satuan_3").value='';
    }else if (clue=='deturaian') {
      document.getElementById("det_uraian_3").value = '';
      document.getElementById("volume_3").value = '';
      document.getElementById("satuan_3").value = '';
      document.getElementById("nominal_satuan_3").value='';
    }


  }

  function generateTextBox_3(index,nama,isi) {
    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = nama+"["+index+"]";
    idx.id = nama+"["+index+"]";
    idx.value = document.getElementById(isi).value;
    return idx;
  }

  function generateTextBoxNilai_3(index,nama,isi) {
    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = nama+"["+index+"]";
    idx.id = nama+"["+index+"]";
    idx.value = $("#"+isi).autoNumeric('get');
    return idx;
  }

  function generateTextBound_3(index, nama) {
    var nilai = $("#"+nama+" option:selected").text();
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTotalSub_3(index) {
    var vol = $("#volume_3").autoNumeric('get');
    var nom = $("#nominal_satuan_3").autoNumeric('get');
    var hasil = vol * nom;
    var subtot = hasil;

    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = "r_subtotal_3["+index+"]";
    idx.id = "r_subtotal_3["+index+"]";
    idx.value = subtot;
    return idx;
  }

  function kosongkan_3(){
    document.getElementById("kode_jenis_belanja_autocomplete_3").value = '';
    document.getElementById("kd_jenis_belanja_3").value = '';
    document.getElementById("kode_kategori_belanja_autocomplete_3").value = '';
    document.getElementById("kd_kategori_belanja_3").value = '';
    document.getElementById("kode_subkategori_belanja_autocomplete_3").value = '';
    document.getElementById("kd_subkategori_belanja_3").value = '';
    document.getElementById("kode_belanja_autocomplete_3").value = '';
    document.getElementById("kd_belanja_3").value = '';
    document.getElementById("uraian_3").value = '';
    document.getElementById("det_uraian_3").value = '';
    document.getElementById("volume_3").value = '';
    document.getElementById("satuan_3").value = '';
    document.getElementById("nominal_satuan_3").value='';
  }

  function generateIndex_3(index) {
    var idx = document.createElement("input");
    idx.type = "hidden";
    idx.name = "index_3[ ]";
    idx.id = "index_3["+index+"]";
    idx.value = index;
    return idx;
  }

  function generateTeksIndex_3(index){
    var teks = document.createTextNode(index);
    return teks;
  }

  function generateSumberDana_3(index) {
    var nilai = $("#sumberdana_3 option:selected").text();
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeSumberDana_3(index) {
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "kd_sumber_dana_3["+index+"]";
    textField.id = "kd_sumber_dana_3["+index+"]";
    textField.value = $("#sumberdana_3").val();

    return textField;
  }

  function generateSubTotal_3(index) {
    var vol = $("#volume_3").autoNumeric('get');
    var nom = $("#nominal_satuan_3").autoNumeric('get');
    var hasil = vol * nom;
    var subtot = hasil.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    var teks = document.createTextNode(subtot);
    return teks;
  }

  function generateTeksJenisBelanja_3(index){

    var nilai = document.getElementById("kode_jenis_belanja_autocomplete_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeJenisBelanja_3(){
    var nilai = document.getElementById("kd_jenis_belanja_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeKategoriBelanja_3(){
    var nilai = document.getElementById("kd_kategori_belanja_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksKategoriBelanja_3(index){
    var nilai = document.getElementById("kode_kategori_belanja_autocomplete_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateSubKategoriBelanja(index) {
    var subkatbelanja = document.createElement("input");
    subkatbelanja.type = "hidden";
    subkatbelanja.name = "subkatbelanja_3[ ]";
    subkatbelanja.id = "subkatbelanja_3["+index+"]";
    subkatbelanja.value = document.getElementById("kd_subkategori_belanja_3").value;
    return subkatbelanja;
  }

  function generateKodeSubKategoriBelanja_3(){
    var nilai = document.getElementById("kd_subkategori_belanja_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksSubKategoriBelanja_3(index){
    var nilai = document.getElementById("kode_subkategori_belanja_autocomplete_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateBelanja(index) {
    var belanja = document.createElement("input");

    belanja.type = "hidden";
    belanja.name = "belanja_3[ ]";
    belanja.id = "belanja_3["+index+"]";
    belanja.value = document.getElementById("kd_belanja_3").value;
    return belanja;
  }

  function generateKodeBelanja_3(){
    var nilai = document.getElementById("kd_belanja_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksBelanja_3(index){
    var nilai = document.getElementById("kode_belanja_autocomplete_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksUraian_3(index){
    var nilai = document.getElementById("uraian_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeUraian_3(){
    var nilai = document.getElementById("uraian_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksDetUraian_3(index){
    var nilai = document.getElementById("det_uraian_3").value;
    var teks = document.createTextNode(nilai);
    return teks;

  }

  function generateTeksVolume_3(index){
    var nilai = document.getElementById("volume_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksSatuan_3(index){
    var nilai = document.getElementById("satuan_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksNominalSatuan_3(index){
    var nilai = document.getElementById("nominal_satuan_3").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksHapus_3(index){
    var teks = document.createElement("span");
    teks.setAttribute('class', 'icon-remove');
    teks.setAttribute('title', 'Hapus Belanja');
    teks.id = "hapusrow";
    teks.setAttribute('onclick','hapusrow_3(this)');
    teks.setAttribute('style','cursor:pointer;');
    teks.value = "hapus";
    return teks;
  }

  function hapusrow_3(index){
     var row = index.parentNode.parentNode;
     row.parentNode.removeChild(row);
     var tbl = document.getElementById("listbelanja_3");
     var rowLen = tbl.rows.length;
     for (var idx=1; idx<rowLen; idx++) {
       var x = tbl.rows[idx].cells;
       x[0].innerHTML = idx;
     }
     setNominalTahun_3("kurang");
  }

  function setNominalTahun_3(cond) {
    var nominalTahunTemp = $("#nominal_3").autoNumeric('get');
    if (nominalTahunTemp == "" || nominalTahunTemp == null) {
      nominalTahunTemp = 0;
    }
    if (cond == "tambah") {
      volumeTemp = $("#volume_3").autoNumeric('get');
      nominalSatuanTemp = $("#nominal_satuan_3").autoNumeric('get');
      var subTotTemp = volumeTemp * nominalSatuanTemp;
      var total = parseInt(nominalTahunTemp) + subTotTemp;
    } else if (cond == "kurang") {
      var tbl = document.getElementById("listbelanja_3");
      var rowLen = tbl.rows.length;
      var total = 0;
      for (var idx=1; idx<rowLen; idx++) {
        var x = tbl.rows[idx].cells;
        total = parseInt(total) + parseInt(x[11].innerHTML.toString().replace(/\./g , ""));
      }
    }
    document.getElementById("nominal_3").value = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function generateTeksEdit_3(index) {
    var teks = document.createElement("span");
    teks.id = "ubahrow";
    teks.setAttribute('class', 'icon-pencil');
    teks.setAttribute('title', 'Ubah Belanja');
    teks.setAttribute('onclick','ubahrow_3('+index+',this)');
    teks.setAttribute('style','cursor:pointer;');
    teks.value = "Ubah";
    return teks;
  }

  function ubahrow_3(index, ini) {
    var inEdit = $("#isEdit_3").val();

    if (inEdit != "1") {
      $("#isEdit_3").val("1");
      var jenis = $("input[name='r_kd_jenis_belanja_3["+ index +"]']").val();
      var kategori = $("input[name='r_kd_kategori_belanja_3["+ index +"]']").val();
      var sub = $("input[name='r_kd_subkategori_belanja_3["+ index +"]']").val();
      var belanja = $("input[name='r_kd_belanja_3["+ index +"]']").val();
      var volume = $("input[name='r_volume_3["+ index +"]']").val().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      var nominal = $("input[name='r_nominal_satuan_3["+ index +"]']").val().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      var sumber_dana = $("input[name='kd_sumber_dana_3["+ index +"]']").val();

      jenis_belanjanya_3("cb_jenis_belanja_3", jenis);
      kategori_belanjanya_3("cb_kategori_belanja_3", jenis, kategori);
      sub_belanjanya_3("cb_subkategori_belanja_3", jenis, kategori, sub);
      belanja_belanjanya_3("cb_belanja_3", jenis, kategori, sub, belanja);
      sumber_dananya_3("sumberdana_3", sumber_dana);
      $("#uraian_3").val($("input[name='r_uraian_3["+ index +"]']").val());
      $("#det_uraian_3").val($("input[name='r_det_uraian_3["+ index +"]']").val());
      $("#volume_3").val(volume);
      $("#satuan_3").val($("input[name='r_satuan_3["+ index +"]']").val());
      $("#nominal_satuan_3").val(nominal);
      hapusrow_3(ini);
    }else {
      alert("Anda sedang dalam mode ubah data");
    }
  }

  function copyrow_3() {
    var tbl_1 = document.getElementById("listbelanja_3");
    var tbl_2 = document.getElementById("listbelanja_4");

    var rowLen = tbl_1.rows.length;

    for (var idx=1; idx<rowLen; idx++) {
      var x = tbl_1.rows[idx];
      var row = tbl_2.insertRow(tbl_2.rows.length);
      var inIndex_4 = document.getElementById("inIndex_4").value;
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

      td0.appendChild(copy_textnode(inIndex_4));
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
      tdEdit.appendChild(generateTeksEdit_4(inIndex_4));
      tdHapus.appendChild(generateTeksHapus_4(inIndex_4));

      td12.appendChild(copy_inputnode(inIndex_4, "kd_sumber_dana_4", x.cells[14].getElementsByTagName('input')[0].value));
      td13.appendChild(copy_inputnode(inIndex_4, "r_kd_jenis_belanja_4", x.cells[15].getElementsByTagName('input')[0].value));
      td14.appendChild(copy_inputnode(inIndex_4, "r_kd_kategori_belanja_4", x.cells[16].getElementsByTagName('input')[0].value));
      td15.appendChild(copy_inputnode(inIndex_4, "r_kd_subkategori_belanja_4", x.cells[17].getElementsByTagName('input')[0].value));
      td16.appendChild(copy_inputnode(inIndex_4, "r_kd_belanja_4", x.cells[18].getElementsByTagName('input')[0].value));
      td17.appendChild(copy_inputnode(inIndex_4, "r_uraian_4", x.cells[19].getElementsByTagName('input')[0].value));
      td18.appendChild(copy_inputnode(inIndex_4, "r_det_uraian_4", x.cells[20].getElementsByTagName('input')[0].value));
      td19.appendChild(copy_inputnode(inIndex_4, "r_volume_4", x.cells[21].getElementsByTagName('input')[0].value));
      td20.appendChild(copy_inputnode(inIndex_4, "r_satuan_4", x.cells[22].getElementsByTagName('input')[0].value));
      td21.appendChild(copy_inputnode(inIndex_4, "r_nominal_satuan_4", x.cells[23].getElementsByTagName('input')[0].value));
      td22.appendChild(copy_inputnode(inIndex_4, "r_subtotal_4", x.cells[24].getElementsByTagName('input')[0].value));


      row.id = inIndex_4;
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

      document.getElementById("inIndex_4").value = parseInt(inIndex_4) + 1;
      setNominalTahun_4("kurang");
    }
  }
