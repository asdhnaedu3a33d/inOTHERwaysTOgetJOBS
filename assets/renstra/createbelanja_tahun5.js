function listBelanja_5(clue){
    var tbl = document.getElementById("listbelanja_5");
    var row = tbl.insertRow(tbl.rows.length);
    var inIndex_5 = document.getElementById("inIndex_5").value;

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

    td0.appendChild(generateIndex_5(row.rowIndex));
    //table yang terlihat
    td0.appendChild(generateTeksIndex_5(row.rowIndex));
    tdnew0.appendChild(generateSumberDana_5(row.rowIndex))
    td1.appendChild(generateTextBound_5(row.rowIndex, "cb_jenis_belanja_5"));
    td2.appendChild(generateTextBound_5(row.rowIndex, "cb_kategori_belanja_5"));
    td3.appendChild(generateTextBound_5(row.rowIndex, "cb_subkategori_belanja_5"));
    td4.appendChild(generateTextBound_5(row.rowIndex, "cb_belanja_5"));
    td5.appendChild(generateTeksUraian_5(row.rowIndex));
    td6.appendChild(generateTeksDetUraian_5(row.rowIndex));
    td7.appendChild(generateTeksVolume_5(row.rowIndex));
    td8.appendChild(generateTeksSatuan_5(row.rowIndex));
    td9.appendChild(generateTeksNominalSatuan_5(row.rowIndex));
    tdnew1.appendChild(generateSubTotal_5(row.rowIndex));
    tdEdit.appendChild(generateTeksEdit_5(inIndex_5));
    td10.appendChild(generateTeksHapus_5(row.rowIndex));
    //table yang di-hidden
    tdnew2.appendChild(generateKodeSumberDana_5(inIndex_5));
    td11.appendChild(generateTextBox_5(inIndex_5,"r_kd_jenis_belanja_5","cb_jenis_belanja_5"));
    td12.appendChild(generateTextBox_5(inIndex_5,"r_kd_kategori_belanja_5","cb_kategori_belanja_5"));
    td13.appendChild(generateTextBox_5(inIndex_5,"r_kd_subkategori_belanja_5","cb_subkategori_belanja_5"));
    td14.appendChild(generateTextBox_5(inIndex_5,"r_kd_belanja_5","cb_belanja_5"));
    td15.appendChild(generateTextBox_5(inIndex_5,"r_uraian_5","uraian_5"));
    td16.appendChild(generateTextBox_5(inIndex_5,"r_det_uraian_5","det_uraian_5"));
    td17.appendChild(generateTextBoxNilai_5(inIndex_5,"r_volume_5","volume_5"));
    td18.appendChild(generateTextBox_5(inIndex_5,"r_satuan_5","satuan_5"));
    td19.appendChild(generateTextBoxNilai_5(inIndex_5,"r_nominal_satuan_5","nominal_satuan_5"));
    td20.appendChild(generateTotalSub_5(inIndex_5));

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
    setNominalTahun_5("tambah");
    document.getElementById("inIndex_5").value = parseInt(inIndex_5) + 1;
    $("#isEdit_5").val("0");

    if (clue=='all') {
      kosongkan_5();
    }
    else if (clue=='jns') {
      document.getElementById("cb_jenis_belanja_5").value = '';
      $("#cb_jenis_belanja_5").trigger("chosen:updated");
      document.getElementById("cb_kategori_belanja_5").value = '';
      $("#cb_kategori_belanja_5").trigger("chosen:updated");
      document.getElementById("cb_subkategori_belanja_5").value = '';
      $("#cb_subkategori_belanja_5").trigger("chosen:updated");
      document.getElementById("cb_belanja_5").value = '';
      $("#cb_belanja_5").trigger("chosen:updated");
      document.getElementById("uraian_5").value = '';
      document.getElementById("det_uraian_5").value = '';
      document.getElementById("volume_5").value = '';
      document.getElementById("satuan_5").value = '';
      document.getElementById("nominal_satuan_5").value='';
    }
    else if (clue=='kat') {
      document.getElementById("cb_kategori_belanja_5").value = '';
      $("#cb_kategori_belanja_5").trigger("chosen:updated");
      document.getElementById("cb_subkategori_belanja_5").value = '';
      $("#cb_subkategori_belanja_5").trigger("chosen:updated");
      document.getElementById("cb_belanja_5").value = '';
      $("#cb_belanja_5").trigger("chosen:updated");
      document.getElementById("uraian_5").value = '';
      document.getElementById("det_uraian_5").value = '';
      document.getElementById("volume_5").value = '';
      document.getElementById("satuan_5").value = '';
      document.getElementById("nominal_satuan_5").value='';
    }
    else if (clue=='subkat') {
      document.getElementById("cb_subkategori_belanja_5").value = '';
      $("#cb_subkategori_belanja_5").trigger("chosen:updated");
      document.getElementById("cb_belanja_5").value = '';
      $("#cb_belanja_5").trigger("chosen:updated");
      document.getElementById("uraian_5").value = '';
      document.getElementById("det_uraian_5").value = '';
      document.getElementById("volume_5").value = '';
      document.getElementById("satuan_5").value = '';
      document.getElementById("nominal_satuan_5").value='';
    }else if (clue=='belanja') {
      document.getElementById("cb_belanja_5").value = '';
      $("#cb_belanja_5").trigger("chosen:updated");
      document.getElementById("uraian_5").value = '';
      document.getElementById("det_uraian_5").value = '';
      document.getElementById("volume_5").value = '';
      document.getElementById("satuan_5").value = '';
      document.getElementById("nominal_satuan_5").value='';
    }else if (clue=='uraian') {
      document.getElementById("uraian_5").value = '';
      document.getElementById("det_uraian_5").value = '';
      document.getElementById("volume_5").value = '';
      document.getElementById("satuan_5").value = '';
      document.getElementById("nominal_satuan_5").value='';
    }else if (clue=='deturaian') {
      document.getElementById("det_uraian_5").value = '';
      document.getElementById("volume_5").value = '';
      document.getElementById("satuan_5").value = '';
      document.getElementById("nominal_satuan_5").value='';
    }


  }

  function generateTextBox_5(index,nama,isi) {
    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = nama+"["+index+"]";
    idx.id = nama+"["+index+"]";
    idx.value = document.getElementById(isi).value;
    return idx;
  }

  function generateTextBoxNilai_5(index,nama,isi) {
    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = nama+"["+index+"]";
    idx.id = nama+"["+index+"]";
    idx.value = $("#"+isi).autoNumeric('get');
    return idx;
  }

  function generateTextBound_5(index, nama) {
    var nilai = $("#"+nama+" option:selected").text();
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTotalSub_5(index) {
    var vol = $("#volume_5").autoNumeric('get');
    var nom = $("#nominal_satuan_5").autoNumeric('get');
    var hasil = vol * nom;
    var subtot = hasil;

    var idx = document.createElement("input");
    idx.type = "text";
    idx.name = "r_subtotal_5["+index+"]";
    idx.id = "r_subtotal_5["+index+"]";
    idx.value = subtot;
    return idx;
  }

  function kosongkan_5(){
    document.getElementById("kode_jenis_belanja_autocomplete_5").value = '';
    document.getElementById("kd_jenis_belanja_5").value = '';
    document.getElementById("kode_kategori_belanja_autocomplete_5").value = '';
    document.getElementById("kd_kategori_belanja_5").value = '';
    document.getElementById("kode_subkategori_belanja_autocomplete_5").value = '';
    document.getElementById("kd_subkategori_belanja_5").value = '';
    document.getElementById("kode_belanja_autocomplete_5").value = '';
    document.getElementById("kd_belanja_5").value = '';
    document.getElementById("uraian_5").value = '';
    document.getElementById("det_uraian_5").value = '';
    document.getElementById("volume_5").value = '';
    document.getElementById("satuan_5").value = '';
    document.getElementById("nominal_satuan_5").value='';
  }

  function generateIndex_5(index) {
    var idx = document.createElement("input");
    idx.type = "hidden";
    idx.name = "index_5[ ]";
    idx.id = "index_5["+index+"]";
    idx.value = index;
    return idx;
  }

  function generateTeksIndex_5(index){
    var teks = document.createTextNode(index);
    return teks;
  }

  function generateSumberDana_5(index) {
    var nilai = $("#sumberdana_5 option:selected").text();
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeSumberDana_5(index) {
    var textField = document.createElement("input");
    textField.type = "text";
    textField.name = "kd_sumber_dana_5["+index+"]";
    textField.id = "kd_sumber_dana_5["+index+"]";
    textField.value = $("#sumberdana_5").val();

    return textField;
  }

  function generateSubTotal_5(index) {
    var vol = $("#volume_5").autoNumeric('get');
    var nom = $("#nominal_satuan_5").autoNumeric('get');
    var hasil = vol * nom;
    var subtot = hasil.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    var teks = document.createTextNode(subtot);
    return teks;
  }

  function generateTeksJenisBelanja_5(index){

    var nilai = document.getElementById("kode_jenis_belanja_autocomplete_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeJenisBelanja_5(){
    var nilai = document.getElementById("kd_jenis_belanja_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeKategoriBelanja_5(){
    var nilai = document.getElementById("kd_kategori_belanja_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksKategoriBelanja_5(index){
    var nilai = document.getElementById("kode_kategori_belanja_autocomplete_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateSubKategoriBelanja(index) {
    var subkatbelanja = document.createElement("input");
    subkatbelanja.type = "hidden";
    subkatbelanja.name = "subkatbelanja_5[ ]";
    subkatbelanja.id = "subkatbelanja_5["+index+"]";
    subkatbelanja.value = document.getElementById("kd_subkategori_belanja_5").value;
    return subkatbelanja;
  }

  function generateKodeSubKategoriBelanja_5(){
    var nilai = document.getElementById("kd_subkategori_belanja_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksSubKategoriBelanja_5(index){
    var nilai = document.getElementById("kode_subkategori_belanja_autocomplete_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateBelanja(index) {
    var belanja = document.createElement("input");

    belanja.type = "hidden";
    belanja.name = "belanja_5[ ]";
    belanja.id = "belanja_5["+index+"]";
    belanja.value = document.getElementById("kd_belanja_5").value;
    return belanja;
  }

  function generateKodeBelanja_5(){
    var nilai = document.getElementById("kd_belanja_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksBelanja_5(index){
    var nilai = document.getElementById("kode_belanja_autocomplete_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksUraian_5(index){
    var nilai = document.getElementById("uraian_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateKodeUraian_5(){
    var nilai = document.getElementById("uraian_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksDetUraian_5(index){
    var nilai = document.getElementById("det_uraian_5").value;
    var teks = document.createTextNode(nilai);
    return teks;

  }

  function generateTeksVolume_5(index){
    var nilai = document.getElementById("volume_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksSatuan_5(index){
    var nilai = document.getElementById("satuan_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksNominalSatuan_5(index){
    var nilai = document.getElementById("nominal_satuan_5").value;
    var teks = document.createTextNode(nilai);
    return teks;
  }

  function generateTeksHapus_5(index){
    var teks = document.createElement("span");
    teks.setAttribute('class', 'icon-remove');
    teks.setAttribute('title', 'Hapus Belanja');
    teks.id = "hapusrow";
    teks.setAttribute('onclick','hapusrow_5(this)');
    teks.setAttribute('style','cursor:pointer;');
    teks.value = "hapus";
    return teks;
  }

  function hapusrow_5(index){
     var row = index.parentNode.parentNode;
     row.parentNode.removeChild(row);
     var tbl = document.getElementById("listbelanja_5");
     var rowLen = tbl.rows.length;
     for (var idx=1; idx<rowLen; idx++) {
       var x = tbl.rows[idx].cells;
       x[0].innerHTML = idx;
     }
     setNominalTahun_5("kurang");
  }

  function setNominalTahun_5(cond) {
    var nominalTahunTemp = $("#nominal_5").autoNumeric('get');
    if (nominalTahunTemp == "" || nominalTahunTemp == null) {
      nominalTahunTemp = 0;
    }
    if (cond == "tambah") {
      volumeTemp = $("#volume_5").autoNumeric('get');
      nominalSatuanTemp = $("#nominal_satuan_5").autoNumeric('get');
      var subTotTemp = volumeTemp * nominalSatuanTemp;
      var total = parseInt(nominalTahunTemp) + subTotTemp;
    } else if (cond == "kurang") {
      var tbl = document.getElementById("listbelanja_5");
      var rowLen = tbl.rows.length;
      var total = 0;
      for (var idx=1; idx<rowLen; idx++) {
        var x = tbl.rows[idx].cells;
        total = parseInt(total) + parseInt(x[11].innerHTML.toString().replace(/\./g , ""));
      }
    }
    document.getElementById("nominal_5").value = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function generateTeksEdit_5(index) {
    var teks = document.createElement("span");
    teks.id = "ubahrow";
    teks.setAttribute('class', 'icon-pencil');
    teks.setAttribute('title', 'Ubah Belanja');
    teks.setAttribute('onclick','ubahrow_5('+index+',this)');
    teks.setAttribute('style','cursor:pointer;');
    teks.value = "Ubah";
    return teks;
  }

  function ubahrow_5(index, ini) {
    var inEdit = $("#isEdit_5").val();

    if (inEdit != "1") {
      $("#isEdit_5").val("1");
      var jenis = $("input[name='r_kd_jenis_belanja_5["+ index +"]']").val();
      var kategori = $("input[name='r_kd_kategori_belanja_5["+ index +"]']").val();
      var sub = $("input[name='r_kd_subkategori_belanja_5["+ index +"]']").val();
      var belanja = $("input[name='r_kd_belanja_5["+ index +"]']").val();
      var volume = $("input[name='r_volume_5["+ index +"]']").val().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      var nominal = $("input[name='r_nominal_satuan_5["+ index +"]']").val().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      var sumber_dana = $("input[name='kd_sumber_dana_5["+ index +"]']").val();

      jenis_belanjanya_5("cb_jenis_belanja_5", jenis);
      kategori_belanjanya_5("cb_kategori_belanja_5", jenis, kategori);
      sub_belanjanya_5("cb_subkategori_belanja_5", jenis, kategori, sub);
      belanja_belanjanya_5("cb_belanja_5", jenis, kategori, sub, belanja);
      sumber_dananya_5("sumberdana_5", sumber_dana);
      $("#uraian_5").val($("input[name='r_uraian_5["+ index +"]']").val());
      $("#det_uraian_5").val($("input[name='r_det_uraian_5["+ index +"]']").val());
      $("#volume_5").val(volume);
      $("#satuan_5").val($("input[name='r_satuan_5["+ index +"]']").val());
      $("#nominal_satuan_5").val(nominal);
      hapusrow_5(ini);
    }else {
      alert("Anda sedang dalam mode ubah data");
    }
  }
