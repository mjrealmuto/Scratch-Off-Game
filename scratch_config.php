<?php
//databased variables

include("/domain/wtmx.com/htdocs/global.php");


$id = 1;

//


$select_sg = "SELECT name, winning_image, losing_image, overlay, max_win, start_date, end_date FROM scratch_game WHERE id = " . $id;

$select_sg_rs = mysql_query( $select_sg );

$row = mysql_fetch_row( $select_sg );

$name 			= $row[0];
$winning_image 	= $row[1];
$losing_image  	= $row[2];
$overlay		= $row[3];
$max_win		= $row[4];
$start_date		= $row[5];
$end_date		= $row[6];


$rand_win = rand(0,100);


$rand_pull = rand(0,100);

if( $rand_win == $rand_pull )
{
	echo "YOU WIN!!!";
}
else
{
	echo "YOU LOSE!!!";
}