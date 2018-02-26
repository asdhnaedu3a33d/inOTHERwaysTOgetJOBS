function eliminationName(jenis_belanja, kategori_belanja, subkategori_belanja, kode_belanja, uraian, det_uraian, volume, satuan, nominal, sumberdana, clue, alert_id, pesan_id) {
    if (jenis_belanja == "" || jenis_belanja == null ) {
      pesanError('Jenis Belanja masih kosong / salah', alert_id, pesan_id);
    }else if (kategori_belanja == "" || kategori_belanja == null) {
      pesanError('Kategori Belanja masih kosong / salah', alert_id, pesan_id);
    }else if (subkategori_belanja == "" || subkategori_belanja == null) {
      pesanError('Sub Kategori Belanja masih kosong / salah', alert_id, pesan_id);
    }else if (kode_belanja == "" || kode_belanja == null) {
      pesanError('Belanja masih kosong / salah', alert_id, pesan_id);
    }else if (uraian == "" || uraian == null) {
      pesanError('Uraian masih kosong', alert_id, pesan_id);
    }else if (sumberdana == "" || sumberdana == null) {
      pesanError('Sumber Dana masih kosong', alert_id, pesan_id);
    }else if (det_uraian == "" || det_uraian == null) {
      pesanError('Detail Uraian masih kosong', alert_id, pesan_id);
    }else if (volume == "" || volume == null || volume < 0) {
      pesanError('Volume masih kosong / kurang dari 0', alert_id, pesan_id);
    }else if (satuan == "" || volume == null) {
      pesanError('Satuan masih kosong', alert_id, pesan_id);
    }else if (nominal == "" || nominal == null || nominal < 0) {
      pesanError('Nominal masih kosong / kurang dari 0', alert_id, pesan_id);
    }else {
      if (pesan_id == "pesan_1") {
        listBelanja_1(clue)
      }
      if (pesan_id == "pesan_2") {
        listBelanja_2(clue)
      }
      if (pesan_id == "pesan_3") {
        listBelanja_3(clue)
      }
      if (pesan_id == "pesan_4") {
        listBelanja_4(clue)
      }
      if (pesan_id == "pesan_5") {
        listBelanja_5(clue)
      }
      $(alert_id).hide();
    }
}

function pesanError(isi, idCus, idPesan) {
  $(idCus).fadeIn();
  document.getElementById(idPesan).innerHTML = isi;
}
