<?php

include("/domain/wp.wtmx.com/htdocs/wp-load.php");


if( $_SERVER['REQUEST_METHOD'] == "POST" )
{
	$id 		= $_POST['id'];
	$code 		= $_POST['code'];
	$first_name = $_POST['firstname'];
	$last_name  = $_POST['lastname'];
	$email 		= $_POST['email'];
	$address	= $_POST['address'];
	$city 		= $_POST['city'];
	$state		= $_POST['state'];
	$zip		= $_POST['zip'];
	
	
	$select_winners_query = "select winning_times FROM scratch_game WHERE id = " . $id;
	
	$select_winners_rs = mysql_query( $select_winners_query );
	
	$row = mysql_fetch_row( $select_winners_rs );
	
	$new_winners = str_replace("T_" . $code, urlencode($first_name) . "^" . urlencode($last_name) . "^" . urlencode($email) . "^" . urlencode($address) . "^" . urlencode($city) . "^" . urlencode($state) . "^" . urlencode($zip), $row[0]);
	
	$update_winners = "UPDATE scratch_game SET winning_times = '" . $new_winners . "' WHERE id = " . $id;
	
	$update_winners_rs = mysql_query($update_winners);
	
	if( $update_winners_rs )
	{
		echo "1";
	}
	else
	{
		echo $update_winners;
		echo mysql_error( ) . "<br />";
		echo "0";
	}
}
