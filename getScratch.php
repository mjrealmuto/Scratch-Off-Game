<?php
//databased variables

include("/domain/wtmx.com/htdocs/global.php");

$hour 	= date('H');
$min	= date('i');
$day	= date('d');
$dt 	= date('Y-m-d');

$winning_num = rand(0,100);

//$winning_num = 1;

$winning_count = 0;

$id = $_GET['id'];

$select_sg = "SELECT name, winning_image, losing_image, overlay, max_win, start_date, end_date, winning_times, win_interval FROM scratch_game WHERE id = " . $id;

$select_sg_rs = mysql_query( $select_sg );

$row = mysql_fetch_row( $select_sg_rs );

$name 			= $row[0];
$winning_image 	= $row[1];
$loosing_image  = $row[2];
$overlay		= $row[3];
$max_win		= $row[4];
$start_date		= $row[5];
$end_date		= $row[6];
$wt				= $row[7];
$wi				= $row[8];

list($int_num, $int_time) = explode("x", $wi);

if( isset( $_GET['code'] ) )
{
	$code = "T_" . $_GET['code'];
	
	$winners = explode("|",$wt);
	
	$new_winners = "";
	
	if( count( $winners ) == 1 )
	{
		$update_winners = "UPDATE scratch_game SET winning_times = '' WHERE id = " . $id;
		
		$update_winners_rs = mysql_query( $update_winners );
	}
	else
	{
		
		foreach( $winners as $winner )
		{
			list($date, $time, $data) = explode(" ", $winner);
			
			if( $data != $code )		
			{	
				
				if( empty($new_winners) )
				{
					$new_winners = $date . " " . $time . " " . $data;
				}
				else
				{
					$new_winners .= "|" . $date . " " . $time . " " . $data;
				}
			}
		}
		
		$update_winners = "UPDATE scratch_game SET winning_times = '" . $new_winners . "' WHERE id = " . $id;
		
		$update_winners_rs = mysql_query( $update_winners );
	}
	
	$rand_pull = rand(0,100);
					
	$rand_pull = 1;
	
	if( $winning_num == $rand_pull )
	{
		$win_date = date('Y-m-d H:i:s');
		
		$ts = time( );
		
		$insert_winner = "UPDATE scratch_game SET winning_times  = '" . $new_winners . "|" . $win_date . " T_" . $ts . "' WHERE id = " . $id;	
		
		$insert_winner_rs = mysql_query( $insert_winner );
		
		echo $name . "|" . $winning_image . "|" . $overlay . "|1|" . $ts;	
	}
	else
	{
		echo $name . "|" . $loosing_image . "|" . $overlay . "|0";
	}
}
else
{
	if( empty( $wt ) )
	{
		$winner_count = 0;
	}
	else
	{
		$winners = explode("|",$wt);
	
		$winner_count	= count($winners);
	}
	
	
	
	if( $winner_count == $max_win )
	{
		echo $name . "|" . $loosing_image . "|" . $overlay . "|0";
	}
	else
	{
		if( $winner_count > 0 )
		{
			$roll_the_dice = FALSE;
			
			$winners_per_interval = 0;
			
			foreach( $winners as $winner )
			{
				list($date, $time) = explode(" ", $winner);
				
				list($h, $m, $d ) = explode(":", $time);
				
				switch( $int_time )
				{
					case "h":
						if( ( $date == $dt ) && ( $h == $hour ) )
						{
							$winners_per_interval++;
						}
					break;
					case "m":
						if( ( $date == $dt ) && ( $m == $min ) )
						{
							$winners_per_interval++;
						}
					break;
					case "d":
						if( ( $date == $dt ) && ( $d == $day ) )
						{
							$winners_per_interval++;
						}
					break;
				}
			}
			
			if( $winners_per_interval == $int_num )
			{
				echo $name . "|" . $loosing_image . "|" . $overlay . "|0";	
			}
			else
			{
				$rand_pull = rand(0,100);
				
				//$rand_pull = 1;
				
				if( $winning_num == $rand_pull )
				{
					$win_date = date('Y-m-d H:i:s');
					
					$ts = time( );
					
					echo $name . "|" . $winning_image . "|" . $overlay . "|1|" . $ts;
					
					$insert_winner = "UPDATE scratch_game SET winning_times  = '" . $wt . "|" . $win_date . " T_" . $ts . "' WHERE id = " . $id;	
					
					$insert_winner_rs = mysql_query( $insert_winner );
				}
				else
				{
					echo $name . "|" . $loosing_image . "|" . $overlay . "|0";
				}
			}
		}
		else
		{
			$rand_pull = rand(0,100);
			//$rand_pull = 1;
					
			if( $winning_num == $rand_pull )
			{
				$win_date = date('Y-m-d H:i:s');
				
				$ts = time( );
				
				echo $name . "|" . $winning_image . "|" . $overlay . "|1|" . $ts;
				
				$insert_winner = "UPDATE scratch_game SET winning_times  = '" . $win_date . " T_" . $ts . "' WHERE id = " . $id;
				
				$insert_winner_rs = mysql_query( $insert_winner );
			}
			else
			{
				echo $name . "|" . $loosing_image . "|" . $overlay . "|0";
			}		
		}
	}

}






