<?php

if( empty( $canvasWidth ) )
{
	$canvasWidth = "500px";
}

if( empty( $canvasHeight ) )
{
	$canvasHeight = "500px";
}


if( ! empty( $scratch_id ) )
{
?>

	<script type='text/javascript' src='/sharedcode/scratch/js/scratcher2.js'></script>
	
	<br />
	<canvas id='scratcher' width='<?php echo $canvasWidth; ?>' height='<?php echo $canvasHeight; ?>' style='border:2px solid #000;'></canvas>
	<br />
	<div id='message'></div>
	<form name='scratch' id='scratch'>
	<table>
		<tr>
			<td style='vertical-align: middle;'><label for='firstname'>First Name</label></td>
			<td><input type='text' name='firstname' class='input' size='30'/></td>
		</tr>
		<tr>
			<td style='vertical-align: middle;'><label for='lastname'>Last Name</label></td>
			<td><input type='text' name='lastname' class='input' size='30'/></td>
		</tr>
		<tr>
			<td style='vertical-align: middle;'><label for='email'>E-Mail</label></td>
			<td><input type='text' name='email' class='input' size='30'/></td>
		</tr>
		<tr>
			<td style='vertical-align: middle;'><label for='address'>Address</label></td>
			<td><input type='text' name='address' class='input' size='50'/></td>
		</tr>
		<tr>
			<td style='vertical-align: middle;'><label for='city'>City</label></td>
			<td>
				<input type='text' name='city' class='input' />
				&nbsp;
				<label for='state'>State</label>
				<input type='text' name='state' class='input' size='2'/>
				&nbsp;
				<label for='zip'>Zip</label>
				<input type='text' name='zip' class='input' size='5'/>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<input type='submit' name='Enter' value='Submit' />
			</td>
		</tr>
	</form>
	<script>
	
		var scratcher;
		
		var scratched = 0;
		
		var percentage = 0;
		
		var code = "";
		
		var timeout;
		
		var winner = 0;
	
		$(function( ){			
		
			getScratcher( );
			
			$("form[name=scratch] input").each( function( ){
				
				$(this).attr('disabled','disabled');
				
			});
			
			$("form[name=scratch]").on("submit", function( e ){
				
				e.preventDefault( );
				
				var firstname 	= $("input[name=firstname]").val( );
				var lastname  	= $("input[name=lastname]").val( );
				var email		= $("input[name=email]").val( );
				var address		= $("input[name=address]").val( );
				var city		= $("input[name=city]").val( );
				var state		= $("input[name=state]").val( );
				var zip			= $("input[name=zip]").val( );
				
				query_string = "id=<?php echo $scratch_id; ?>&code=" + code + "&firstname=" + firstname + "&lastname=" + lastname + "&email=" + email + "&address=" + address + "&city=" + city + "&state=" + state + "&zip=" + zip;
				
				$.ajax({
					beforeSend: function( ){
						$("input[name=Enter]").val("Entering...");
					},
					afterSend: function( ){
						$("input[name=Enter]").val("Enter");
					},
					type : "POST",
					url : "postScratch.php",
					data : query_string,
					dateType : "text",
					success : function( data ){
						
						if ( data == 1 )
						{
							$("input[name=firstname]").val("");
							$("input[name=lastname]").val("");
							$("input[name=email]").val("");							
							$("input[name=address]").val("");
							$("input[name=city]").val("");
							$("input[name=state]").val("");
							$("input[name=zip]").val("");
																					
							$("#message").html("Thank you for your entry.").css("color","green");
						}
						else
						{
							$("#message").html("Your entry could not be uploaded.").css("color","red");
						}
					},
					error: function( ){
						
					}
				});
				
			});
		});
	
		function getPercentage( e )
		{
			percentage = (this.fullAmount(10) * 100 )|0;
			
			if( percentage > 50 )
			{
				if( scratched == 0 && winner == 1)
				{
					$("form[name=scratch] input").each( function( ){
						$(this).removeAttr('disabled');
					});
					
					scratched = 1;
				}
			}
		}
		
		function getScratcher( )
		{
		
			qs = "";
		
			if( code == "" )
			{
				qs = "id=<?php echo $scratch_id; ?>";
			}
			else
			{
				qs = "id=<?php echo $scratch_id; ?>&code=" + code;
				console.log(qs);
			}
		
			$.ajax({
				type : "GET",
				url  : "getScratch.php?" + qs,
				success : function( data )
				{
					var scratch_vars = data.split("|");
					
					scratch_name = scratch_vars[0];
					btm_img		 = scratch_vars[1];
					overlay		 = scratch_vars[2];
					num			 = scratch_vars[3];
					
					if( scratch_vars.length == 5 )
					{
						code = scratch_vars[4];
						winner = 1;
						console.log(code);
					}
					
					scratcher = new Scratcher('scratcher');
					
					scratcher.setImages('/test/images/scratch/' + btm_img ,'/test/images/scratch/' + overlay);	
					
					scratcher.addEventListener('scratch', getPercentage);
					
					timeout = setTimeout(getScratcher,300000);
				}
			});
		}
	
	</script>
	<style>
		.input
		{
			
			border: 0;
			padding: 10px;
			font-size: .9em;
			font-family: Arial, sans-serif;
			color: #aaa;
			border: solid 1px #ccc;
			margin: 0 0 10px;
			
		}
	
		input:focus
		{
			border: solid 1px #eea32a;
		}
	
	</style>
<?php
}
?>