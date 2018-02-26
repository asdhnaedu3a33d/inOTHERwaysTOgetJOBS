<?php

class Cleanshit extends CI_Controller {

  public function __construct()
	{
		$this->CI =& get_instance();
		parent::__construct();
    if (!empty($this->session->userdata("db_aktif"))) {
        $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
    }
	}

	function index()
	{
    ini_set("memory_limit", "-1");
    set_time_limit(0);

		$skpds = $this->db->query("SELECT id_skpd, nama_skpd FROM m_skpd;")->result();
    $i_skpd=1;
    foreach ($skpds as $skpd) {
      echo "<div style='display: table-row;'>";
      echo $i_skpd++.". ";
      echo "<a onclick=\"window.open('". base_url('cleanshit/do_cleanshit/'.$skpd->id_skpd) ."')\" href='#'>";
      echo $skpd->nama_skpd;
      echo "</a>";
      echo "</div>";
    }
	}

  function do_cleanshit($id_skpd=NULL)
  {
    if (empty($id_skpd)) {
      redirect("cleanshit");
    }

    $skpd = $this->db->query("SELECT id_skpd, nama_skpd FROM m_skpd WHERE id_skpd=$id_skpd;")->row();
    $this->load->library('Export_excel');
    $this->export_excel->create_header(array(
                              $skpd->nama_skpd,
                              NULL,
                              NULL,
                              NULL,
                              NULL,
                              NULL,
                              NULL,
                              NULL,
                              NULL
                            )
                          );
    $this->export_excel->merge_cell($this->export_excel->get_cell_name(1).$this->export_excel->get_last_row(), $this->export_excel->get_cell_name(8).$this->export_excel->get_last_row());

    #################################################### Renja ####################################################
    $this->export_excel->set_row(array(
                              "Renja"
                            )
                          );
    $this->export_excel->set_row(array(
                              "ID",
                              "Urus.",
                              "Bid.",
                              "Prog.",
                              "Keg.",
                              "Nama",
                              "Indikator Renja",
                              "Status",
                              "Indikator Pada Renstra (Jika Diubah)"
                            )
                          );

    $this->db->query("UPDATE
                        t_renja_indikator_prog_keg
                      INNER JOIN
                        t_renja_prog_keg ON t_renja_indikator_prog_keg.id_prog_keg=t_renja_prog_keg.id
                      LEFT JOIN
                        t_renstra_prog_keg ON t_renja_prog_keg.id_renstra=t_renstra_prog_keg.id
                      LEFT JOIN
                        t_renstra_indikator_prog_keg ON t_renstra_indikator_prog_keg.id_prog_keg=t_renstra_prog_keg.id
                      SET
                        t_renja_indikator_prog_keg.id_indikator_renstra=t_renstra_indikator_prog_keg.id
                      WHERE
                        t_renja_prog_keg.id_skpd=$skpd->id_skpd
                        AND tahun=2016
                        AND t_renja_indikator_prog_keg.indikator=t_renstra_indikator_prog_keg.indikator;");

    $indikators = $this->db->query("SELECT kd_urusan, kd_bidang, kd_program, kd_kegiatan, nama_prog_or_keg, t_renja_indikator_prog_keg.* FROM t_renja_indikator_prog_keg INNER JOIN t_renja_prog_keg ON t_renja_indikator_prog_keg.id_prog_keg=t_renja_prog_keg.id WHERE t_renja_prog_keg.id_skpd=$skpd->id_skpd AND tahun=2016 AND t_renja_indikator_prog_keg.id_indikator_renstra IS NULL;")->result();
    foreach ($indikators as $indikator) {
        $this->export_excel->set_row(array(
                                      $indikator->id,
                                      $indikator->kd_urusan,
                                      $indikator->kd_bidang,
                                      $indikator->kd_program,
                                      $indikator->kd_kegiatan,
                                      $indikator->nama_prog_or_keg,
                                      $indikator->indikator,
                                      "",
                                      ""
                                    ));
    }

    #################################################### RKA ####################################################
    $this->export_excel->set_row(array(
                              NULL
                            )
                          );
    $this->export_excel->set_row(array(
                              "RKA"
                            )
                          );
    $this->export_excel->set_row(array(
                              "ID",
                              "Urus.",
                              "Bid.",
                              "Prog.",
                              "Keg.",
                              "Nama",
                              "Indikator RKA",
                              "Status",
                              "Indikator Pada Renja (Jika Diubah)"
                            )
                          );

    $this->db->query("UPDATE
                        tx_rka_indikator_prog_keg
                      INNER JOIN
                        tx_rka_prog_keg ON tx_rka_indikator_prog_keg.id_prog_keg=tx_rka_prog_keg.id
                      LEFT JOIN
                        t_renja_prog_keg ON tx_rka_prog_keg.id_renja=t_renja_prog_keg.id
                      LEFT JOIN
                        t_renja_indikator_prog_keg ON t_renja_indikator_prog_keg.id_prog_keg=t_renja_prog_keg.id
                      SET
                        tx_rka_indikator_prog_keg.id_indikator_renja=t_renja_indikator_prog_keg.id
                      WHERE
                        tx_rka_prog_keg.id_skpd=$skpd->id_skpd
                        AND tx_rka_prog_keg.tahun=2016
                        AND tx_rka_indikator_prog_keg.indikator=t_renja_indikator_prog_keg.indikator;");

    $indikators = $this->db->query("SELECT kd_urusan, kd_bidang, kd_program, kd_kegiatan, nama_prog_or_keg, tx_rka_indikator_prog_keg.* FROM tx_rka_indikator_prog_keg INNER JOIN tx_rka_prog_keg ON tx_rka_indikator_prog_keg.id_prog_keg=tx_rka_prog_keg.id WHERE tx_rka_prog_keg.id_skpd=$skpd->id_skpd AND tahun=2016 AND tx_rka_indikator_prog_keg.id_indikator_renja IS NULL;")->result();
    foreach ($indikators as $indikator) {
        $this->export_excel->set_row(array(
                                      $indikator->id,
                                      $indikator->kd_urusan,
                                      $indikator->kd_bidang,
                                      $indikator->kd_program,
                                      $indikator->kd_kegiatan,
                                      $indikator->nama_prog_or_keg,
                                      $indikator->indikator,
                                      "",
                                      ""
                                    ));
    }

    #################################################### DPA ####################################################
    $this->export_excel->set_row(array(
                              NULL
                            )
                          );
    $this->export_excel->set_row(array(
                              "DPA"
                            )
                          );
    $this->export_excel->set_row(array(
                              "ID",
                              "Urus.",
                              "Bid.",
                              "Prog.",
                              "Keg.",
                              "Nama",
                              "Indikator DPA",
                              "Status",
                              "Indikator Pada RKA (Jika Diubah)"
                            )
                          );

    $this->db->query("UPDATE
                        tx_dpa_indikator_prog_keg
                      INNER JOIN
                        tx_dpa_prog_keg ON tx_dpa_indikator_prog_keg.id_prog_keg=tx_dpa_prog_keg.id
                      LEFT JOIN
                        tx_rka_prog_keg ON tx_dpa_prog_keg.id_rka=tx_rka_prog_keg.id
                      LEFT JOIN
                        tx_rka_indikator_prog_keg ON tx_rka_indikator_prog_keg.id_prog_keg=tx_rka_prog_keg.id
                      SET
                        tx_dpa_indikator_prog_keg.id_indikator_rka=tx_rka_indikator_prog_keg.id
                      WHERE
                        tx_dpa_prog_keg.id_skpd=$skpd->id_skpd
                        AND tx_dpa_prog_keg.tahun=2016
                        AND tx_dpa_indikator_prog_keg.indikator=tx_rka_indikator_prog_keg.indikator;");

    $indikators = $this->db->query("SELECT kd_urusan, kd_bidang, kd_program, kd_kegiatan, nama_prog_or_keg, tx_dpa_indikator_prog_keg.* FROM tx_dpa_indikator_prog_keg INNER JOIN tx_dpa_prog_keg ON tx_dpa_indikator_prog_keg.id_prog_keg=tx_dpa_prog_keg.id WHERE tx_dpa_prog_keg.id_skpd=$skpd->id_skpd AND tahun=2016 AND tx_dpa_indikator_prog_keg.id_indikator_rka IS NULL;")->result();
    foreach ($indikators as $indikator) {
        $this->export_excel->set_row(array(
                                      $indikator->id,
                                      $indikator->kd_urusan,
                                      $indikator->kd_bidang,
                                      $indikator->kd_program,
                                      $indikator->kd_kegiatan,
                                      $indikator->nama_prog_or_keg,
                                      $indikator->indikator,
                                      "",
                                      ""
                                    ));
    }

    #################################################### CIK ####################################################
    $this->export_excel->set_row(array(
                              NULL
                            )
                          );
    $this->export_excel->set_row(array(
                              "CIK"
                            )
                          );
    $this->export_excel->set_row(array(
                              "ID",
                              "Urus.",
                              "Bid.",
                              "Prog.",
                              "Keg.",
                              "Nama",
                              "Indikator CIK",
                              "Status",
                              "Indikator Pada DPA (Jika Diubah)"
                            )
                          );

    $this->db->query("UPDATE
                        tx_cik_indikator_prog_keg
                      INNER JOIN
                        tx_cik_prog_keg ON tx_cik_indikator_prog_keg.id_prog_keg=tx_cik_prog_keg.id
                      LEFT JOIN
                        tx_dpa_prog_keg ON tx_cik_prog_keg.id_dpa=tx_dpa_prog_keg.id
                      LEFT JOIN
                        tx_dpa_indikator_prog_keg ON tx_dpa_indikator_prog_keg.id_prog_keg=tx_dpa_prog_keg.id
                      SET
                        tx_cik_indikator_prog_keg.id_indikator_dpa=tx_dpa_indikator_prog_keg.id
                      WHERE
                        tx_cik_prog_keg.id_skpd=$skpd->id_skpd
                        AND tx_cik_prog_keg.tahun=2016
                        AND tx_cik_indikator_prog_keg.indikator=tx_dpa_indikator_prog_keg.indikator;");

    $indikators = $this->db->query("SELECT kd_urusan, kd_bidang, kd_program, kd_kegiatan, nama_prog_or_keg, tx_cik_indikator_prog_keg.* FROM tx_cik_indikator_prog_keg INNER JOIN tx_cik_prog_keg ON tx_cik_indikator_prog_keg.id_prog_keg=tx_cik_prog_keg.id WHERE tx_cik_prog_keg.id_skpd=$skpd->id_skpd AND tahun=2016 AND tx_cik_indikator_prog_keg.id_indikator_dpa IS NULL;")->result();
    foreach ($indikators as $indikator) {
        $this->export_excel->set_row(array(
                                      $indikator->id,
                                      $indikator->kd_urusan,
                                      $indikator->kd_bidang,
                                      $indikator->kd_program,
                                      $indikator->kd_kegiatan,
                                      $indikator->nama_prog_or_keg,
                                      $indikator->indikator,
                                      "",
                                      ""
                                    ));
    }

    #########################################################################
    $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(1))->setAutoSize(true);
    $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(2))->setAutoSize(true);
    $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(3))->setAutoSize(true);
    $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(4))->setAutoSize(true);
    $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(5))->setAutoSize(true);
    $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(6))->setWidth(55);
    $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(7))->setWidth(55);
    $this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(9))->setWidth(55);

    $this->export_excel->getActiveSheet()->getStyle($this->export_excel->get_cell_name(6). "1:". $this->export_excel->get_cell_name(7) . $this->export_excel->get_last_row())->getAlignment()->setWrapText(true);

    $this->export_excel->filename = $skpd->nama_skpd."_";
    $this->export_excel->execute();
  }

}
?>
