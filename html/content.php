<?php

include 'header.php';
/////////// CONTENT HERE ///////////
echo'<center>You are IN !</cente></br>';
if($_COOKIE['level'] == "1")
	{
	echo'<center>You are user</cente></br>';
	}
if($_COOKIE['level'] == "2")
	{
	echo'<center>You are operator</cente></br>';
	}	
if($_COOKIE['level'] == "3")
	{
	echo'<center>You are administrator</cente></br>';
	}		
/////////// END CONTENT ///////////		

?>
