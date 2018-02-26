function eliminationName(lokasi, uraian_kegiatan, jenis_belanja, kategori_belanja, subkategori_belanja, kode_belanja, uraian, det_uraian, volume, satuan, nominal, sumberdana, clue, alert_id, pesan_id) {
    if (lokasi == "" || lokasi == null) {
      pesanError('Lokasi masih kosong', alert_id, pesan_id);
      return false;
    }else if (jenis_belanja == "" || jenis_belanja == null ) {
      pesanError('Kelompok Belanja masih kosong / salah', alert_id, pesan_id);
      return false;
    }else if (kategori_belanja == "" || kategori_belanja == null) {
      pesanError('Jenis Belanja masih kosong / salah', alert_id, pesan_id);
      return false;
    }else if (subkategori_belanja == "" || subkategori_belanja == null) {
      pesanError('Obyek Belanja masih kosong / salah', alert_id, pesan_id);
      return false;
    }else if (kode_belanja == "" || kode_belanja == null) {
      pesanError('Rincian Obyek masih kosong / salah', alert_id, pesan_id);
      return false;
    }else if (uraian == "" || uraian == null) {
      pesanError('Rincian Belanja masih kosong', alert_id, pesan_id);
      return false;
    }else if (sumberdana == "" || sumberdana == null) {
      pesanError('Sumber Dana masih kosong', alert_id, pesan_id);
      return false;
    }else if (det_uraian == "" || det_uraian == null) {
      pesanError('Sub Rincian masih kosong', alert_id, pesan_id);
      return false;
    }else if (volume == "" || volume == null || volume < 1) {
      pesanError('Volume masih kosong / 0', alert_id, pesan_id);
      return false;
    }else if (satuan == "" || volume == null) {
      pesanError('Satuan masih kosong', alert_id, pesan_id);
      return false;
    }else if (nominal == "" || nominal == null || nominal < 1) {
      pesanError('Nominal masih kosong / 0', alert_id, pesan_id);
      return false;
    }else {
      $(alert_id).hide();
      return true;
    }
}

function pesanError(isi, idCus, idPesan) {
  $(idCus).fadeIn();
  document.getElementById(idPesan).innerHTML = isi;
}
