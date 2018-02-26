/* ================================================================ 
This copyright notice must be untouched at all times.
Stay Put!
Copyright (c) 2009 Stu Nicholls - stunicholls.com - all rights reserved.
=================================================================== */
onload = function() {


startPos = $("#secondary_bar").position().top;
divHeight = $("#secondary_bar").outerHeight();
$("#secondary_bar").css("height", divHeight + "px")

$(window).scroll(function (e) {
scrTop = $(window).scrollTop();


	if ((startPos-5) < scrTop) {
		if ($.browser.msie && $.browser.version <= 6 ) {
		topPos = startPos + (scrTop - startPos) +5;
		$("#secondary_bar")	.css("position", "absolute")
						.css("top", topPos +"px")
						.css('zIndex', '500')
		}
		else {
		$("#secondary_bar")	.css("position", "fixed")
						.css("top", "0px")
						.css("zIndex", "500")
		}
	}
	else {
		$("#secondary_bar")	.css("position", "static")
	}

    });

}
