function l_targetAkumulasi(hasil_awal, target_1, target_2, target_3, target_4, target_5, isPositif) {
      var hasil_akhir = 0;
  if (isPositif = "0") {
    var hasil_akhir = target_5;
  }else {
        var hasil_akhir = target_5;
  }

}

function targetIncremental(hasil_awal, tr_1, tr_2, tr_3, tr_4, tr_5, isPositif) {
  var hasil_akhir = 0;
  if (isPositif == "1") {
    hasil_akhir = parseInt(hasil_awal) - parseInt(target_1) - parseInt(target_2) - parseInt(target_3) - parseInt(target_4) - parseInt(target_5);
  }else {
    hasil_akhir = parseInt(hasil_awal) + parseInt(target_1) + parseInt(target_2) + parseInt(target_3) + parseInt(target_4) + parseInt(target_5);
  }
  return hasil_akhir;
}
