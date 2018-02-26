;(function($){
/**
 * jqGrid Indonesia Translation by Praba Sabtika, putu@praba.web.id, http:// praba.web.id
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/
$.jgrid = $.jgrid || {};
$.extend($.jgrid,{
	defaults : {
		recordtext: "Lihat {0} - {1} of {2}",
		emptyrecords: "Tidak ada data untuk dilihat",
		loadtext: "Memuat...",
		pgtext : "Halaman {0} dari {1}"
	},
	search : {
		caption: "Mencari...",
		Find: "Cari",
		Reset: "Reset",
		odata : ['sama dengan', 'tidak sama dengan', 'lebih kecil', 'lebih kecil sama dengan','lebih besar','lebih besar sama dengan', 'dimulai dengan','tidak dimulai dengan','ada dalam','tidak ada dalam','berakhir dengan','tidak berakhir dengan','berisi','tidak berisi'],
		groupOps: [	{ op: "AND", text: "semua" },	{ op: "OR",  text: "sebagian" }	],
		matchText: " cocok",
		rulesText: " aturan"
	},
	edit : {
		addCaption: "Tambah Data",
		editCaption: "Edit Data",
		bSubmit: "Proses",
		bCancel: "Batal",
		bClose: "Tutup",
		saveData: "Data telah berubah! Simpan perubahan?",
		bYes : "Ya",
		bNo : "Tidak",
		bExit : "Batal",
		msg: {
			required:"Kolom dibutuhkan",
			number:"Mohon, masukan angka yang benar",
			minValue:"Nilai harus lebih besar dari atau sama dengan ",
			maxValue:"Nilai harus lebih kecil dari atau sama dengan",
			email: "bukan tulisan email yang benar",
			integer: "Mohon, masukan nilai bilangan yang benar",
			date: "Mohon, masukan nilai tanggal yang benar",
			url: "bukan URL yang benar. Awalan diperlukan ('http://' atau 'https://')",
			nodefined : " tidak didefinisikan!",
			novalue : " nilai balik dibutuhkan!",
			customarray : "Fungsi kustom harus kembali array!",
			customfcheck : "Fungsi kustom harus hadir dalam pemeriksaan kasus kustom!"

		}
	},
	view : {
		caption: "Tampilkan data",
		bClose: "Tutup"
	},
	del : {
		caption: "Hapus",
		msg: "Hapus data terpilih?",
		bSubmit: "Hapus",
		bCancel: "Batal"
	},
	nav : {
		edittext: "",
		edittitle: "Edit baris terpilih",
		addtext:"",
		addtitle: "Tambah baris baru",
		deltext: "",
		deltitle: "Hapus baris terpilih",
		searchtext: "",
		searchtitle: "Cari data",
		refreshtext: "",
		refreshtitle: "Segarkan Grid",
		alertcap: "Peringatan",
		alerttext: "Tolong pilih satu baris",
		viewtext: "",
		viewtitle: "tampilkan data terpilih"
	},
	col : {
		caption: "Pilih kolom",
		bSubmit: "Ok",
		bCancel: "Batal"
	},
	errors : {
		errcap : "Kesalahan",
		nourl : "Tidak ada url",
		norecords: "Tidak ada data untuk diproses",
		model : "Panjang colNames <> colModel!"
	},
	formatter : {
		integer : {thousandsSeparator: " ", defaultValue: '0'},
		number : {decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, defaultValue: '0.00'},
		currency : {decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, prefix: "", suffix:"", defaultValue: '0.00'},
		date : {
			dayNames:   [
				"Mgg", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab",
				"Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"
			],
			monthNames: [
				"Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Des",
				"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "Desember"
			],
			AmPm : ["am","pm","AM","PM"],
			S: function (j) {return j < 11 || j > 13 ? ['st', 'nd', 'rd', 'th'][Math.min((j - 1) % 10, 3)] : 'th'},
			srcformat: 'Y-m-d',
			newformat: 'd/m/Y',
			masks : {
				ISO8601Long:"Y-m-d H:i:s",
				ISO8601Short:"Y-m-d",
				ShortDate: "n/j/Y",
				LongDate: "l, F d, Y",
				FullDateTime: "l, F d, Y g:i:s A",
				MonthDay: "F d",
				ShortTime: "g:i A",
				LongTime: "g:i:s A",
				SortableDateTime: "Y-m-d\\TH:i:s",
				UniversalSortableDateTime: "Y-m-d H:i:sO",
				YearMonth: "F, Y"
			},
			reformatAfterEdit : false
		},
		baseLinkUrl: '',
		showAction: '',
		target: '',
		checkbox : {disabled:true},
		idName : 'id'
	}
});
})(jQuery);