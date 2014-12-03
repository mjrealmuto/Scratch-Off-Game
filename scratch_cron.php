<?php

$dbIP = "localhost";
$dblink = mysql_connect($dbIP, "jbuti", "mysqljeff");
$db = mysql_select_db("wtmx", $dblink);

echo "writing query...\n";
$find_scratches_query = "SELECT id, winning_times FROM scratch_game";

echo "attempting connection ....\n";
$find_scratches_rs = mysql_query( $find_scratches_query );


echo "check error ....\n";
if( ! $find_scratches_rs )
{
	echo "hi" . mysql_error();
}
echo "after";
$update_field = FALSE;
$winners = "";
while( $row = mysql_fetch_assoc( $find_scratches_rs ) )
{
	
	$id 	= $row['id'];
	
	$times 	= $row['winning_times']; 
	
	if( ! empty( $times ) )
	{
		$tmp = explode("|", $times);

		//if( count( $tmp ) > 1 )
		//{
			foreach( $tmp as $t )
			{
				$tmp1 = explode(" ", $t);

				if( preg_match("/^T_/", $tmp1[2]) )
				{
					$t1 = StrToTime ( date('Y-m-d H:i:s') );
					$t2 = StrToTime ( $tmp1[0] . ' ' . $tmp1[1] );
					$diff = $t1 - $t2;
					$hours = $diff / ( 60 * 60 );

					
					if( $hours > 1 )
					{
						continue;
					}
					else
					{
						if( empty( $winners ) )
						{
							$winners = $t;
						}
						else
						{
							$winners .= "|" . $t;
						}
					}
				}
				else
				{
					if( empty( $winners ) )
					{
						$winners = $t;
					}
					else
					{
						$winners .= "|" . $t;
					}
				}
			}
		/*}
		else
		{
			
			
		}*/
		
 	}	
 	
 	$update = "UPDATE scratch_game SET winning_times = '" . $winners . "' WHERE id = " . $id;
 	
 	$rs = mysql_query($update);
 	
 	if( ! $rs )
 	{
	 	echo mysql_error( ) . "\n";
	 	die;
	 	
 	}
}

