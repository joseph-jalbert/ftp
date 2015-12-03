/* -------
*  2014 Fractl
*  http://frac.tl/
---------- */

$(document).ready(function(){

	var selectTimes = '<select name="calls" id="" class="calls"><option value="0">1-2</option><option value="1">3-5</option><option value="2">6-10</option><option value="3">11+</option></select>';
	var selectDays = '<select name="long" id="" class="long"><option value="0">1 week</option><option value="1">1 month</option><option value="2">6 months</option><option value="3">1 year</option><option value="4">2 years</option><option value="5">3 years</option><option value="6">4 years</option></select>';
	var inputResult = '<input type="text" class="result" value="$2,250" disabled>';

	$('#select-times').html(selectTimes);
	$('#select-days').html(selectDays);
	$('#input-result').html(inputResult);

	$('.slider').bxSlider({
		pager: false
	});

	$('.calls, .long').change(function(){

		var calls = $(".calls option:selected").val();
		var hlong = $(".long option:selected").val();

        //1-2 

		if (calls == 0 && hlong == 0)
		{
		   result = "2,250.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 0 && hlong == 1)
		{
		   result = "9,742.50";
		   $('.result').val('$' + result);
		}
		else if (calls == 0 && hlong == 2)
		{
		   result = "58,455.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 0 && hlong == 3)
		{
		   result = "117,000.00";
		   $('.result').val('$' + result);
		}

		else if (calls == 0 && hlong == 4)
		{
		   result = "234,000.00";
		   $('.result').val('$' + result);
		}

		else if (calls == 0 && hlong == 5)
		{
		   result = "351,000.00";
		   $('.result').val('$' + result);
		}

		else if (calls == 0 && hlong == 6)
		{
		   result = "468,000.00";
		   $('.result').val('$' + result);
		}

		//3-5

		if (calls == 1 && hlong == 0)
		{
		   result = "6,000.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 1 && hlong == 1)
		{
		   result = "25,980.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 1 && hlong == 2)
		{
		   result = "155,880.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 1 && hlong == 3)
		{
		   result = "312,000.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 1 && hlong == 4)
		{
		   result = "624,000.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 1 && hlong == 5)
		{
		   result = "936,000.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 1 && hlong == 6)
		{
		   result = "1,248,000.00";
		   $('.result').val('$' + result);
		}
		

		//6-10

		if (calls == 2 && hlong == 0)
		{
		   result = "12,000.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 2 && hlong == 1)
		{
		   result = "51,960.00";
		   $('.result').val('$' + result);	
		}
		else if (calls == 2 && hlong == 2)
		{
		   result = "311,760.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 2 && hlong == 3)
		{
		   result = "624,000.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 2 && hlong == 4)
		{
		   result = "1,248,000.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 2 && hlong == 5)
		{
		   result = "1,872,000.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 2 && hlong == 6)
		{
		   result = "2,496,000.00";
		   $('.result').val('$' + result);
		}
		
		//11+ 1y	

		if (calls == 3 && hlong == 0)
		{
		   result = "16,500.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 3 && hlong == 1)
		{
		   result = "71,445.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 3 && hlong == 2)
		{
		   result = "428,670.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 3 && hlong == 3)
		{
		   result = "858,000.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 3 && hlong == 4)
		{
		   result = "1,716,000.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 3 && hlong == 5)
		{
		   result = "2,574,000.00";
		   $('.result').val('$' + result);
		}
		else if (calls == 3 && hlong == 6)
		{
		   result = "3,432,000.00";
		   $('.result').val('$' + result);
		}

	});

});