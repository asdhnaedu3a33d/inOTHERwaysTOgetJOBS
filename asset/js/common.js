$(document).ready(function(){
	//transition effects between pages
	$("body").css("display", "none");
	$("body").fadeIn(1000);

	$("a.transition").click(function(event){
        event.preventDefault();
        linkLocation = this.href;
        $("body").fadeOut(1000, redirectPage);
    });

    function redirectPage() {
        window.location = linkLocation;
    }
});



//Digunakan untuk komponen autoNumeric
var numOptions 			= {aSep: '.', aDec: ',', vMin: '-999999999999.99', vMax: '999999999999.99', mDec: 0};
var numOptionsPositive 	= {aSep: '.', aDec: ',', vMin: '0.00', vMax: '999999999999.99', mDec: 0};
var numOptionsPersen 	= {aSep: '.', aDec: ',', vMin: '0', vMax: '100', mDec: 0, aSign: '%', pSign: 's', mDec:1};
var numOptionsNotRound	= {aSep: '.', aDec: ',', vMin: '-999999999999.99', vMax: '999999999999.99', mDec: 2};

var _css   = { padding: '10px', border: '2px solid #000', backgroundColor:'#ddd' };
var _ovcss = { backgroundColor: '#000', opacity:  0.1, cursor: 'default' };

var _csserr   = { padding: '10px', border: '2px solid #000', backgroundColor:'red', color:'#ffffff'};
var _ovcsserr = { backgroundColor: 'red', opacity:  0.1, cursor: 'default' };

function loader() {
	return '<center><div class="loader" style="width: 24px; height: 24px"></div></center>';
}

function print_doc(url, title, orientation) {
	var win = popup( url, title, orientation);

	//if(confirm("Lanjutkan Proses Pencetakan ?\n Pencetakan bisa dilanjutkan kemudian dengan menekan tombol Ctrl+P")) {
		win.print();
	//}
	//alert('Tekan Ctrl+P untuk Mencetak');
}

function popup(url, title, orientation) {

	var _width = 800; _height = 600;
	var _title = "no title";

	if(orientation !== undefined && orientation == "landscape") {
		_width = 1024;
	}

	if(title != undefined) {
		_title = title;
	}

	var strWindowFeatures = "toolbar = no, menubar = no, location = no, scrollbars = yes, " +
							"resizable = yes, width = " + _width + ", height = " + _height;

	var win = window.open(url, _title, strWindowFeatures);

	return win;
}

function days_between(date1, date2) {

    // The number of milliseconds in one day
    var ONE_DAY = 1000 * 60 * 60 * 24

    // Convert both dates to milliseconds
    var date1_ms = date1.getTime()
    var date2_ms = date2.getTime()

    // Calculate the difference in milliseconds
    var difference_ms = Math.abs(date1_ms - date2_ms)

    // Convert back to days and return
    return Math.round(difference_ms/ONE_DAY)

}

function autoNumericOff(selector) {
	$(selector).val($(selector).autoNumeric('get'));
}

function autoNumericOn(selector) {
	$(selector).autoNumeric('set', $(selector).val());
}

function toggle(selector)
{
	$('#'+selector).fadeToggle('fast');
}

function prepare_form()
{
    $( function() {
    	$('.number').autoNumeric(window.numOptionsPositive);
    	$('.currency').autoNumeric(window.numOptions);
    	$('.percentage').autoNumeric(window.numOptionsPersen);

        $( ".date").datepicker({
            dateFormat:"yy-mm-dd",
            showOtherMonths: true,
      		selectOtherMonths: true,
      		showWeek: true,
      		firstDay: 1,
        });
    });
}

function catch_expired_session(response)
{
	if(response.errno == 403) {
		window.location = response.message;
	}
}

function catch_expired_session2(data)
{
	try {
		var response = $.parseJSON(data);

		if(response.errno == 403) {
			window.location = response.message;
		}
	} catch(e) {
		return;
	}
}

function parseJSON(data) {
	try {
		var response = $.parseJSON(data);

		if(response.errno == 403) {
			window.location = response.message;
		}

		return response;
	} catch(e) {
		return data;
	}
}

function init_combo(arCombo) {
	//request isian combobox ke server
	$.each(arCombo, function () {
		var obj = this;

		if (typeof this.fn === 'undefined') {
			this.fn = function(data) {
				obj.combo.html(data);
			}
		}
		$.post(this.url, this.postData, this.fn);
	});
}

/**
 * var arCombo = new Array(
 		$("#cmb0"),$("#cmb1"),$("#cmb2"),$("#cmb3")
 	);
	level : 0 - parent, 1 - child, 2 - child of child dst
 */
function populate_combo(arCombo, level, url, postData){
	//ambil kode referensi
	var parentCode = arCombo[level].val();

	//show loading text
    arCombo[level+1].empty();
    arCombo[level+1].append("<option value=''> Loading ... </option>");

	//request isian combobox ke server
	$.ajax({
			type: "POST",
			url : url,
			data: postData,
			success: function(msg){
				//set isian combo
				//alert(msg);
				arCombo[level+1].html(msg);
			}
	});

	//reset semua child nya
	for (var i = level + 2; i < arCombo.length; i++) {
        arCombo[i].empty();
        arCombo[i].append("<option value=''> Pilih ... </option>");
	}
}


function populate_comboX(context) {
	//cek parameter minimal terdiri dari 4 element
	//arCombo, level, url, postData
	if (!context.hasOwnProperty('arCombo') ||
		!context.hasOwnProperty('level') ||
		!context.hasOwnProperty('url') ||
		!context.hasOwnProperty('postData')) {
		alert('required parameter not fulfilled');
		return;
	}

	//show loading text
    context.arCombo[level+1].empty();
    context.arCombo[level+1].append("<option value=''> Loading ... </option>");

	//request isian combobox ke server
	$.ajax({
			type: "POST",
			url : url,
			data: postData,
			success: function(msg){
				//set isian combo
				//alert(msg);
				context.arCombo[level+1].html(msg);
			}
	});

	//reset semua child nya
	for (var i = context.level + 2; i < context.arCombo.length; i++) {
        context.arCombo[i].empty();
        context.arCombo[i].append("<option value=''> Pilih ... </option>");
	}
}


//usage: append_combo('#comboId', value, label, true);
function append_combo(objId, value, label, isSelected) {
    var selected = '';

    if (isSelected) {
        selected = 'selected';
    }
    var option = "<option value='" + value + "' " + selected + ">" +
                    label + "</option>";

    var obj = $(objId + ' option[value="' + value + '"]');

    if ($.trim(obj.text()) == '') {
        $(objId).append(option);
    }
}


function import_honor_from_file(fh, first_row, separator, callback) {

	if (!(window.File && window.FileReader && window.FileList && window.Blob)) {
		alert('The File APIs are not fully supported by your browser.');
		return;
	}

	if(first_row <= 0 || !fh.files || !fh.files[0]) return;

    var reader = new FileReader();
    reader.onload = function (event) {
        var output 	= event.target.result;
        output 		= output.split("\n");
        var hasil	= '';
        var rows 	= [];
        var counter = 0;
            output.forEach( function(row) {
                if( counter < first_row - 1)
                	counter++;

                row = row.split(separator);
                cols= {};
                var counter2 = 0;
                row.forEach( function(col) {
                    cols[counter2] = col;
                    counter2++;
                });

                rows[counter] = cols;
                counter++;
            });

            //masukan ke row
            callback(rows);
    };//end onload()

    reader.readAsText(fh.files[0]);
    //end if html5 filelist support
}
