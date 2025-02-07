<?php
//print_r($_COOKIE); 
echo'<head>';
echo'<link rel="stylesheet" href="header.css">';
?><script>
function eyeon()
	{
	var x = document.getElementById("mypwrd");
	if (x.type === "password")
		{ x.type = "text"; }
	else
		{ x.type = "password"; }
	}
function eyeout()
	{
	var x = document.getElementById("mypwrd");
	if (x.type === "text")
		{ x.type = "password"; }
	else
		{ x.type = "text"; }
	}
function eyeon2()
	{
	var x = document.getElementById("mypwrd2");
	if (x.type === "password")
		{ x.type = "text"; }
	else
		{ x.type = "password"; }
	}
function eyeout2()
	{
	var x = document.getElementById("mypwrd2");
	if (x.type === "text")
		{ x.type = "password"; }
	else
		{ x.type = "text"; }
	}
</script>
<?php
echo'</head>';
function checkpw($str){ 
		return 
		preg_match('/[a-z]/', $str)
		&& preg_match('/[A-Z]/', $str)
		&& preg_match('/[%*_\-]/', $str) 
		&& preg_match('/[0-9]/', $str); 
		} 
if(!isset($_COOKIE['admin']) && !isset($_COOKIE['user'])){ header("Location: index.php"); }
else
	{
	////////////////OPEN THE DATABASE FILE
	$base = '../dbdir/mybase.sqlite';
	$con = new SQLite3($base);
	if(!$con)
		{
		echo $db->lastErrorMsg(); exit(0);
		}
echo'<div class="mybody">';
	/////////// HEADER HERE ///////////
	echo'<p><a href="content.php"><font style="font-size: 10px;color: white;">
	Home</a> &nbsp; &nbsp; &nbsp; <a href="account.php?pwadm=1">My Account</a> &nbsp; &nbsp; &nbsp; <a href="account.php?logout=1">Logout</font></a></p>';
	/////////// END HEADER ///////////
	echo'</div><br/>';	
	}	
?>
