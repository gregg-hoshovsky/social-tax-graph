<!DOCTYPE html>
<html lang="en">
<head>

	<link rel="shortcut icon" href="/images/favicon.ico" />
	<link rel="icon" href="/images/favicon.ico" type="image/x-icon" />
	<meta charset="utf-8">
	<title>Adding new items</title>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	<script src="http://jqueryui.com/external/jquery.bgiframe-2.1.2.js"></script>
	<script src="http://code.jquery.com/ui/1.8.19/jquery-ui.min.js" type="text/javascript"></script>
	<script src="http://jquery-ui.googlecode.com/svn/tags/latest/external/jquery.bgiframe-2.1.2.js" type="text/javascript"></script>
	<link rel="stylesheet" href="http://jqueryui.com/demos/demos.css">
	<style>
		body { font-size: 62.5%; }
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
	</style>
	<script>
//https://simple-window-3181.herokuapp.com/bin/uploadnew.php?username=1455691200&pwd=1455691200&upLoadNew=2,healthcare,1::

	var dataString="username=<?php echo $_GET['user_id']?>&pwd=<?php echo $_GET['user_id']?>&upLoadNew=";
	$(function() {

		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var name = $( "#name" ),
			percent = $( "#percent" ),
			allFields = $( [] ).add( name ).add( percent ),
			tips = $( ".validateTips" );
		var list = $( "#list" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}
		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			buttons: {
				"Add Your choice": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkLength( name, "username", 3, 16 );
					bValid = bValid && checkLength( percent, "percent", 1,2 );

					bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/percent_address_validation/
					bValid = bValid && checkRegexp( percent,  /^([0-9])+$/i, "0>x<100" );
					

					if ( bValid ) {
					dataString+= list.val()+","+name.val() +","+percent.val() +"::";
						$( "#users tbody" ).append( "<tr>" +
							"<td>" + name.val() + "</td>" + 
							"<td>" + percent.val() + "</td>" + 
							"<td>" + $('#list option:selected').text() + "</td>" +
						"</tr>" ); 
						$( this ).dialog( "close" );
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#create-user" )
			.button()
			.click(function() {
				$( "#dialog-form" ).dialog( "open" );
			});
		$( "#upload" )
			.button()
			.click(function() {
		//s	alert("datastring "+dataString);
		$.ajax(
		{
			type: "POST",
			url: "/bin/uploadnew.php",
			data: dataString,
			success: function() 
			{
				$('#contact_form').html("<div id='message'></div>");
				$('#message').html("<h2>Data Submitted!"+dataString+"</h2>")
				.append("<p>Login to use more Features.</p>")
				.hide()
				.fadeIn(1500, function() 
				{
					$('#message').append("<img id='checkmark' src='images/check.png' />");
				});
			}
		});
		window.location = '/';
		return false;
			});
	});
	</script>
</head>
<body>

<div class="demo">

<div id="dialog-form" title="Create new Item">
	<p class="validateTips">All form fields are required.</p>

	<form>
	<fieldset>
			<label for="list">Choose One</label>
		<select id='list'>
			<option value='1'>Defense</option>
			<option value='2' selected>Welfare</option>
		</select>
		<label for="name">Name</label>
		<input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
		<label for="percent">Percent</label>
		<input type="text" name="percent" id="percent" value="" class="text ui-widget-content ui-corner-all" />

	</fieldset>
	</form>
</div>

<div id="contact_form">
<div id="users-contain" class="ui-widget">
	<h1>New categories</h1>
	<table id="users" class="ui-widget ui-widget-content">
		<thead>
			<tr class="ui-widget-header ">
				<th>Name</th>
				<th>Percent</th>
				<th>Category</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
</div>
<button id="create-user">Create new item</button><button id="upload">Upload</button>

</div><!-- End demo -->
</div><!-- End contact_form -->



<div class="demo-description">
</div><!-- End demo-description -->
<a href="/" > Return </a>
</body>
</html>
