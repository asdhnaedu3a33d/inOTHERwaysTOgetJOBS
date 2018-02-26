<?php

class Constant {

    //Konstanta sifat pembayaran
    const JB_UP                         = 'UP';
    const JB_TUP                        = 'TUP';
    const JB_GUP                        = 'GUP';
    const JB_LS                         = 'LS';
    const JB_NHL                        = 'NHL';
	const JB_TUP_NHL                    = 'TUP_NHL';
	const JB_GUP_NHL                    = 'GUP_NHL';
    const JB_SAH                        = 'SAH';
    const JB_ALL                        = '%';

    //Konstanta jenis referensi Unit
    const JR_ID                         = 1;
    const JR_CD                         = 2;

    //Konstanta Kode Applikasi di LOV
    const KAP_SATUAN                    = 1;
    const KAP_KW_TYPE                   = 2;
    const KAP_STATUS_MHS                = 3;
    const KAP_LADIK_LAIN                = 4;
    const KAP_SKEMA_BAYAR               = 5;
    const KAP_STATUS_BUKTI_AKUNTANSI    = 6;
    const JENIS_LADIK                   = 7;
    const KAP_SEWA                      = 8;
    const KAP_INVESTASI                 = 9;
    const KAP_KEWENANGAN_PELAKSANA      = 10;
    const KAP_CARA_BAYAR                = 11;
    const KAP_SIFAT_BAYAR               = 12;
    const KAP_JENIS_PENERIMAAN          = 13;
    const JENIS_BANK_KAS                = 14;
    const KAP_JENIS_BUKTI               = 15;
    const KAP_SURAT_SETOR               = 16;
	const KAP_SPTJM              		= 17;
    const KAP_JENIS_HONOR               = 18;
    const KAP_HONOR_JABATAN_PANITIA     = 19;
    const KAP_HONOR_JABATAN_PENGUJI     = 20;
    

    //Konstanta Jenis SPJ
    const JSPJ_KW                       = 1;
    const JSPJ_SPTB                     = 2;
    const JSPJ_SPP                      = 3;
    const JSPJ_SPM                      = 4;
    const JSPJ_SP2D                     = 5;

    //konstanta jenis MOU
    const MOU_LADIK                     = 'LADIK';
    const MOU_LADIK_LAIN                = 'LADIK_LAIN';
    const MOU_HIBAH_TERENCANA           = 'HIBAH_TERENCANA';
    const MOU_PPPMAS                    = 'PPPMAS';
    const MOU_HIBAH_TAKTER              = 'HIBAH_TAKTER';
    const MOU_UNIT_USAHA                = 'UNIT_USAHA';
    const MOU_ASET                      = 'ASET';
    const MOU_ASET_RUTIN                = 'ASET_RUTIN';
    const MOU_INVESTASI                 = 'INVESTASI';
    const MOU_JASA_GIRO                 = 'JASA_GIRO';


    //jenis list
    const WITH_ALL                      = 1;
    const WITHOUT_ALL                   = 2;
    const WITHOUT_PILIH                 = 3;

    //jenis bukti
    const BUK_BKM                       = 'BKM';
    const BUK_BKK                       = 'BKK';
    const BUK_BM                        = 'BM';

    //jenis Bank Kas
    const BK_KAS_MASUK                  = 'KM';
    const BK_KAS_KELUAR                 = 'KK';
    const BK_BANK_MASUK                 = 'BM';
    const BK_BANK_KELUAR                = 'BK';
    const BK_MEMORIAL                   = 'MM';

    //Jenis Surat Setor
    const SS_SS                         = 'SS';
    const SS_SSBP                       = 'SSBP';
    const SS_SSPB                       = 'SSPB';

    const SPJSU_BARU                    = 1;
    const SPJSU_VERI                    = 2;
    const SPJSU_REVISI                  = 3;
    const SPJSU_KWOK                    = 4;
    const SPJSU_VERIDOK                 = 5;
    const SPJSU_VALID                   = 6;
    const SPJSU_KWVALID                 = 7;
    const SPJSU_DICAIRKAN               = 8;

    const KWITANSI_BARU                 = 0;
    const KWITANSI_VERI1                = 1;
    const KWITANSI_REVISI               = 2;
    const KWITANSI_VERI3                = 3;
    const KWITANSI_OK                   = 18;
    const KWITANSI_VERI_DOK             = 19;
    const KWITANSI_TDKLKP               = 20;
    const KWITANSI_VALID                = 21;
    const KWITANSI_KWVALID              = 22;
    const KWITANSI_DICAIRKAN            = 23;

    const SPTB_BARU                     = 31;
    const SPTB_OTORPPK                  = 32;
    const SPTB_SDHSPP                   = 33;

    const SPP_BARU                      = 41;
    const SPP_OTORPPK                   = 42;
    const SPP_SDHSPM                    = 43;

    const SPM_BARU                      = 51;
    const SPM_OTORDKN                   = 52;
    const SPM_DIAJUKAN                  = 53;
    const SPM_DOKTDKLKP                 = 54;
    const SPM_LENGKAP                   = 55;
    const SPM_VERIBLU                   = 56;
    const SPM_VERIDM                    = 57;
    const SPM_VERIKEU                   = 58;
    const SPM_SDHSP2D                   = 59;

    const SP2D_BARU                     = 71;
    const SP2D_OTORPR2                  = 72;
    const SP2D_DICAIRKAN                = 73;

    const SPJU_BARU                     = 1;
    const SPJU_DIAJUKAN                 = 2;
    const SPJU_LENGKAP                  = 3;
    const SPJU_SDNGVERI                 = 4;
    const SPJU_OK                       = 5;
    const SPJU_SDHSP2D                  = 6;
    const SPJU_SP2DOK                   = 7;
    const SPJU_DICAIRKAN                = 8;

    const USULANSU_BARU                 = 1;
    const USULANSU_VERI                 = 2;
    const USULANSU_REVISI               = 3;
    const USULANSU_OK                   = 4;
    const USULANSU_OTORSU               = 5;
    const USULANSU_SDHSPM               = 6;
    const USULANSU_DICAIRKAN            = 7;

    const USULANITM_BARU                = 1;
    const USULANITM_VERI                = 2;
    const USULANITM_REVISI              = 3;
    const USULANITM_SDHREVISI           = 4;
    const USULANITM_OK                  = 5;
    
    const JH_PANITIA_TETAP              = 1;
    const JH_MENGAJAR                   = 2;
    const JH_NASKAH_KOREKSI             = 3;
    const JH_PEMBIMBING_TA              = 4;
    const JH_PENGUJI_TA                 = 5;
    const JH_PENGAWAS                   = 6;
    const JH_ADHOC                      = 7;
    const JH_PEMBIMBING_AKAD            = 8;

    const CSV_SEPARATOR = '\t';

}
?>
