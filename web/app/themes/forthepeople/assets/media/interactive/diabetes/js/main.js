jQuery(function($){

var dropdom =  $('.drop span.runnumber');
var skulldom =  $('.skull span.runnumber');
//var angeldom =  $('.angel span.runnumber');
var legdom =  $('.leg span.runnumber');
var eyedom =  $('.eye span.runnumber');
var babydom =  $('.baby span.runnumber');
var childdom =  $('.child span.runnumber');
var mediandom =  $('.median span.runnumber');
var outdom =  $('.out span.runnumber');
var persondom =  $('.person span.runnumber');
var totaldom =  $('.total span.runnumber');
var footerdom = $('footer span.runnumber');

var dropstart = 21246350;
var skullstart = 231404;
//var angelstart = 11492000;
var legstart =  65700;
var eyestart =  9450000;
var babystart = 11492000;
var childstart = 119783;  
var medianstart= 7900;
var outstart =  350;
var personstart = 0;
var totalstart = 0; 
var footerstart = 0;


var percent = 0;
percent = percent + 90;

var val;
//sliders

$('.bxslider').bxSlider({
  	infiniteLoop: true,
  	controls: false,
  	auto: true,
  	pager: true,
  	pagerCustom: '#bx-pager',
  	pause: 7000
  	//speed: 1000
});

$('.causesslider').bxSlider({
  	infiniteLoop: true,
  	captions: true,
  	pager: false,
    auto: true
});

$('.stateslider').bxSlider({
    infiniteLoop: true,
    captions: true,
    pager: true,
    auto: true,
    pagerCustom: '.states'
});


$('.states').bxSlider({
  	infiniteLoop: true,
  	minSlides: 5,
  	maxSlides: 5,
  	slideWidth: 155,
	slideMargin: 20,
	pager: false
});



function slidecircle(){

	if(percent == 90){
		data(),
		eye();
		percent = 0.039;
	}else if(percent == 0.039){
		data(),
		amputation();
		percent = 65;
	}else if(percent == 65){
		data(),
		heart();
		percent = 60;
	}else if(percent == 60){
		data(),
		hypertension();
		percent = 40;
	}else if(percent == 40){
		data(),
		kidney();
		percent = 18;
	}else if(percent == 18){
		data(),
		pregnancy();
		percent = 50;
	}else if(percent == 50){
		data(),
		depression();
		percent = 10;
	}else if(percent == 10){
		data(),
		birth();	
		percent = 20;
	}else if(percent == 20){
		data(),
		abortions();
		percent = 90;
	}

}

slidecircle();
setInterval(slidecircle, 5000);


function eye(){
	$("#percentcircle").empty().removeData().attr("data-fgcolor", "#f6921e").css("color", "#f6921e");
	$('#percentcircle').circliful();
}

function amputation(){
	$("#percentcircle").empty().removeData().attr("data-fgcolor", "#00adee").css("color", "#00adee");
	$('#percentcircle').circliful();	
}

function heart(){
	$("#percentcircle").empty().removeData().attr("data-fgcolor", "#00a69c").css("color", "#00a69c");
	$('#percentcircle').circliful();
}

function hypertension(){
	$("#percentcircle").empty().removeData().attr("data-fgcolor", "#009345").css("color", "#009345");
	$('#percentcircle').circliful();
}

function kidney(){
	$("#percentcircle").empty().removeData().attr("data-fgcolor", "#af8169").css("color", "#af8169");
	$('#percentcircle').circliful();
}

function pregnancy(){
	$("#percentcircle").empty().removeData().attr("data-fgcolor", "#be1e2d").css("color", "#be1e2d");
	$('#percentcircle').circliful();
}

function depression(){
	$("#percentcircle").empty().removeData().attr("data-fgcolor", "#c3996b").css("color", "#c3996b");
	$('#percentcircle').circliful();
}

function birth(){
	$("#percentcircle").empty().removeData().attr("data-fgcolor", "#c02d90").css("color", "#c02d90");
	$('#percentcircle').circliful();
}

function abortions(){
	$("#percentcircle").empty().removeData().attr("data-fgcolor", "#1b75bb").css("color", "#1b75bb");
	$('#percentcircle').circliful();
}


function data(){
	$("#percentcircle").remove();
	$(".per").append("<div id=\"percentcircle\"></div>");
	$("#percentcircle").empty().removeData().attr("data-dimension", "222");
	$("#percentcircle").empty().removeData().attr("data-info", "my text");
	$("#percentcircle").empty().removeData().attr("data-width", "30");
	$("#percentcircle").empty().removeData().attr("data-fontsize", "56");
	$("#percentcircle").empty().removeData().attr("data-bgcolor", "#BBBDBF");
	$("#percentcircle").attr("data-text",  percent.toString()+"%");
	$("#percentcircle").attr("data-percent",  percent.toString());
}

$('.percent li').bind('click',function(){
	var icon = $(this).attr('class');
	
	switch (icon){
		case 'c1': 
		percent = 90;
		data();
		eye();
		percent = 0.039;
		break;
		case 'c2': 
		percent = 0.039;
		data();
		amputation();
		percent = 65;
		break;
		case 'c3': 
		percent = 65;
		data();
		heart();
		percent = 60;
		break;
		case 'c4': 
		percent = 60;
		data();
		hypertension();
		percent = 40;
		break;
		case 'c5': 
		percent = 40;
		data();
		kidney();
		percent = 18;
		break;
		case 'c6': 
		percent = 18;
		data();
		pregnancy();
		percent = 50;
		break;
		case 'c7': 
		percent = 50;
		data();
		depression();
		percent = 10;
		break;
		case 'c8': 
		percent = 10;
		data();
		birth();	
		percent = 20;
		break;
		case 'c9': 
		percent = 20;
		data();
		abortions();
		percent = 90;
		break;
	}

});

//Hover description


$('.c1').hover(function(){
	    $('.c1hover').stop( true, true ).fadeIn();
	},function(){
	    $('.c1hover').hide();
});

$('.c2').hover(function(){
	    $('.c2hover').stop( true, true ).fadeIn();
	},function(){
	    $('.c2hover').hide();
});

$('.c3').hover(function(){
	    $('.c3hover').stop( true, true ).fadeIn();
	},function(){
	    $('.c3hover').hide();
});

$('.c4').hover(function(){
	    $('.c4hover').stop( true, true ).fadeIn();
	},function(){
	    $('.c4hover').hide();
});

$('.c5').hover(function(){
	    $('.c5hover').stop( true, true ).fadeIn();
	},function(){
	    $('.c5hover').hide();
});

$('.c6').hover(function(){
	    $('.c6hover').stop( true, true ).fadeIn();
	},function(){
	    $('.c6hover').hide();
});

$('.c7').hover(function(){
	    $('.c7hover').stop( true, true ).fadeIn();
	},function(){
	    $('.c7hover').hide();
});

$('.c8').hover(function(){
	    $('.c8hover').stop( true, true ).fadeIn();
	},function(){
	    $('.c8hover').hide();
});

$('.c9').hover(function(){
	    $('.c9hover').stop( true, true ).fadeIn();
	},function(){
	    $('.c9hover').hide();
});


	






// Animate the element's value from x to y:
  $({someValue: 10000000}).animate({someValue: 21000000}, {
      duration: 2000,
      easing:'swing', // can be anything
      step: function() { // called on every step
          // Update the element's text with rounded-up value:
          $('.runnumber').html(commaSeparateNumber(Math.round(this.someValue)));
      },
      complete: function(){
      		dropdom.html(commaSeparateNumber(dropstart));
			skulldom.html(commaSeparateNumber(skullstart));
			//angeldom.html(commaSeparateNumber(angelstart));
			legdom.html(commaSeparateNumber(legstart));
			eyedom.html(commaSeparateNumber(eyestart));
			babydom.html(commaSeparateNumber(babystart));
			childdom.html(commaSeparateNumber(childstart));
			mediandom.html(commaSeparateNumber(medianstart));
			outdom.html(commaSeparateNumber(outstart));
			persondom.html(commaSeparateNumber(personstart));
			totaldom.html(commaSeparateNumber(totalstart));
			footerdom.html(commaSeparateNumber(footerstart));
      	  	obj.Start();
      }
  });

  // Cost:
  $({xyz: 0}).animate({xyz: 245000000000}, {
      duration: 10000,
      easing:'swing', // can be anything
      step: function(now) { // called on every step
          // Update the element's text with rounded-up value:
          $('.costrunner').html(commaSeparateNumber("$ "+Math.round(now)));
      }
  });

// Declaring class "Timer"
	var Timer = function(){		
		// Property: Frequency of elapse event of the timer in millisecond
		this.Interval = 1000;
		
		// Property: Whether the timer is enable or not
		this.Enable = new Boolean(false);
		
		// Event: Timer tick
		this.Tick;
		
		// Member variable: Hold interval id of the timer
		var timerId = 0;
		
		// Member variable: Hold instance of this class
		var thisObject;
		
		// Function: Start the timer
		this.Start = function(){
			this.Enable = new Boolean(true);
	
			thisObject = this;
			if (thisObject.Enable){
				thisObject.timerId = setInterval(
				function(){
					thisObject.Tick(); 
				}, thisObject.Interval);

				thisObject.timerId = setInterval(
				function(){
					thisObject.Tick2(); 
				}, thisObject.Interval2);

				/*thisObject.timerId = setInterval(
				function(){
					thisObject.Tick3(); 
				}, thisObject.Interval3);*/

				thisObject.timerId = setInterval(
				function(){
					thisObject.Tick4(); 
				}, thisObject.Interval4);

				thisObject.timerId = setInterval(
				function(){
					thisObject.Tick5(); 
				}, thisObject.Interval5);

				thisObject.timerId = setInterval(
				function(){
					thisObject.Tick6(); 
				}, thisObject.Interval6);

				thisObject.timerId = setInterval(
				function(){
					thisObject.Tick7(); 
				}, thisObject.Interval7);

				thisObject.timerId = setInterval(
				function(){
					thisObject.Tick8(); 
				}, thisObject.Interval8);

				thisObject.timerId = setInterval(
				function(){
					thisObject.Tick9(); 
				}, thisObject.Interval9);

				thisObject.timerId = setInterval(
				function(){
					thisObject.Tick10(); 
				}, thisObject.Interval10);

				thisObject.timerId = setInterval(
				function(){
					thisObject.Tick11(); 
				}, thisObject.Interval11);

				thisObject.timerId = setInterval(
				function(){
					thisObject.Tick12(); 
				}, thisObject.Interval12);

			}
		};
		
		// Function: Stops the timer
		/*this.Stop = function()
		{			
			thisObject.Enable = new Boolean(false);
			clearInterval(thisObject.timerId);
		};*/
	
	};
	
	var obj = new Timer();
	obj.Interval = 17000;
	obj.Interval2 = 60000;
	//obj.Interval3 = 60000;
	obj.Interval4 = 3600000;
	obj.Interval5 = 17000;
	obj.Interval6 = 60000;
	obj.Interval7 = 420000;
	obj.Interval8 = 2000;
	obj.Interval9 = 2000;
	obj.Interval10 = 2000;
	obj.Interval11 = 2000;
	obj.Interval12 = 17000;
	obj.Tick = timer_tick;
	obj.Tick2 = timer_tick2;
	//obj.Tick3 = timer_tick3;
	obj.Tick4 = timer_tick4;
	obj.Tick5 = timer_tick5;
	obj.Tick6 = timer_tick6;
	obj.Tick7 = timer_tick7;
	obj.Tick8 = timer_tick8;
	obj.Tick9 = timer_tick9;
	obj.Tick10 = timer_tick10;
	obj.Tick11 = timer_tick11;
	obj.Tick12 = timer_tick12;

	function timer_tick(){
		dropstart  = dropstart + 1;
		dropdom.html(commaSeparateNumber(Math.round(dropstart)));
	}

	function timer_tick2(){
		skullstart = skullstart + 2;
		skulldom.html(commaSeparateNumber(Math.round(skullstart)));
	}

	function timer_tick3(){
		//angelstart = angelstart + 20;
		//angeldom.html(commaSeparateNumber(Math.round(angelstart)));
	}

	function timer_tick4(){
		legstart = legstart + 8;
		legdom.html(commaSeparateNumber(Math.round(legstart)));
	}

	function timer_tick5(){
		eyestart = eyestart + 1;
		eyedom.html(commaSeparateNumber(Math.round(eyestart)));
	}

	function timer_tick6(){
		babystart = babystart + 20;
		babydom.html(commaSeparateNumber(Math.round(babystart)));
	}

	function timer_tick7(){
		childstart = childstart + 1;
		childdom.html(commaSeparateNumber(Math.round(childstart)));
	}

	function timer_tick8(){
		medianstart = medianstart + 1;
		mediandom.html(commaSeparateNumber(Math.round(medianstart)));
	}

	function timer_tick9(){
		outstart = outstart + 1;
		outdom.html(commaSeparateNumber(Math.round(outstart)));
	}

	function timer_tick10(){
		personstart = personstart + 1;
		persondom.html(commaSeparateNumber(Math.round(personstart)));
	}

	function timer_tick11(){
		totalstart = totalstart + 1;
		totaldom.html(commaSeparateNumber(Math.round(totalstart)));
	}

	function timer_tick12(){
		footerstart = footerstart + 1;
		footerdom.html(commaSeparateNumber(Math.round(footerstart)));
	}


 function commaSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }
    return val;
  }


});