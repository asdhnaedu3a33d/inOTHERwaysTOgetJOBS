$(window).load(function() {
	//alert("test");
	$("#caption_login2").animate({
		left: 184
	}, {
		duration: 500,
		step: function( now, fx ){
		  $( ".block:gt(0)" ).css( "left", now );
		}
	});
	$("#caption_login.a1").animate({
		left: 0
	}, {
		duration: 800,
		step: function( now, fx ){
		  $( ".block:gt(0)" ).css( "left", now );
		}
	});
	$("#caption_login.a2").animate({
		left: 0
	}, {
		duration: 1000,
		step: function( now, fx ){
		  $( ".block:gt(0)" ).css( "left", now );
		}
	});
	
	$("#login_form input").click(function(){
  		$("#form_result").slideUp(200);
	  	//setTimeout(function(){
			//$("body").css("overflow","auto");			
	  	//},1000);
	});
	
});

$(document).ready(function() {
		$('#login_form').submit(function() {
		$("#form_result").css({'background' : '#777', 'border-bottom-color' : '#566'});
		$("#form_result").text("validasi user...");
		$("#form_result").slideDown("fast");
		
		var target = $("input.target").val();
		
		//$('#loading2').show();
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
			
			var data_ = JSON.parse(data);

			var pesan = data_.errno;

				if(pesan=='0'){
					$("#form_result").css({'background' : '#57bcda', 'border-bottom-color' : '#37849a'});
					$("#form_result").text("login sukses");
					document.location=data_.message;
					
				}else if(pesan=='1')
				{
					$("#form_result").css({'background' : '#da4f49', 'border-bottom-color' : '#bd362f'});
					$('#form_result').text("password & username tidak cocok");
					$('input[type=text]').val("");
					$('input[type=password]').val("");
				}
                
			}
		})
		return false;
	});
});
