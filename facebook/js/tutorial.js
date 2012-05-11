
$(function() {
	$('.error').hide();
	$('input.text-input').css({backgroundColor:"#FFFFFF"});
	$('input.text-input').focus(function()
	{
		$(this).css({backgroundColor:"#FFDDAA"});
	});
	$('input.text-input').blur(function()
	{
		$(this).css({backgroundColor:"#FFFFFF"});
	});
	$(".button").click(function() 
	{
		$('.error').hide();	
		var dataString="username="+$("input#userid").val();
		dataString +="&pwd="+$("input#pwd").val();
		dataString +="&upLoadAll=";
//		$.each($("input, select, textarea"), function(i,v) 
		$.each($("input"), function(i,v) 
		{
			var theTag = v.tagName;
			var theElement = $(v);
			var theValue = theElement.val();
			if( theElement.attr('name') != "userid"
				&& theElement.attr('name') != "submit"
				&& theElement.attr('name') != "pwd"
				) // skip the 'new' fields
				{
					dataString += theElement.attr('name')+ ","+theValue+"::";
				}
		});
		$.ajax(
		{
			type: "POST",
			url: "bin/upload.php",
			data: dataString,
			success: function() 
			{
				$('#contact_form').html("<div id='message'></div>");
				$('#message').html("<h2>Data Submitted!</h2>")
				.append("<p>Login to use more Features.</p>")
				.hide()
				.fadeIn(1500, function() 
				{
					$('#message').append("<img id='checkmark' src='images/check.png' />");
				});
			}
		});
		return false;
	});
});
runOnLoad(function()
{
  $("input#name").select().focus();
});
