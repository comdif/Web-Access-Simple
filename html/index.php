<head>
<link rel="stylesheet" href="login.css">
<script>
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
</script>
</head>

<?php
////////////////OPEN THE DATABASE FILE
$base = '../dbdir/mybase.sqlite';
$con = new SQLite3($base);
if(!$con)
	{
	echo $db->lastErrorMsg();
	exit(0);
	}

	////////////////HERE IS THE CONTENT

	//CHECK IF ALREDY LOGGED
	if(isset($_COOKIE['admin']) && isset($_COOKIE['user']))
		{
		header("Location: content.php");
		}

	////FORM////
	echo'<div align="center" class="login">
		<p>PLEASE LOGIN TO ACCESS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		<form id="login" name="login" method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">
		<table border="0"><tr>
		<td><label><br />Username: </label><input type="text" name="login" value=""/></td>
		<td></td></tr>
		<td style="position: relative;">
		<label> Password: </label><input name="password" type="password" id="mypwrd" value="" />
		<div class="card" onmouseover="eyeon()" onmouseout="eyeout()" style="position: absolute; top: 1.01em; left: 13.95em;"></div>
		</td></tr><tr>
		<td><label>&nbsp;</label><input type="submit" name="Submit" value="Submit" /></td>
		<td></td></tr>
		</table>
		</form></div>';

	////FORM SENT MAKE ACTION////
	if(!empty($_POST['password']) && !empty($_POST['login']))
		{
		$password = hash("sha256", $_POST['password']);
		$login = $_POST['login'];
		//CHECK IF USER/PWRD IS VALID
//-------------------To replace with litesql3 query-------------------// // controler dans la database que $password corresponde bien au user $login//

		$query = 'SELECT * FROM "user" WHERE "username" like \''.$login.'\'';
		$results = $con->query($query);
		while($row = $results->fetchArray(SQLITE3_ASSOC))
			{
			$level = $row['level'];
			$crptpass= $row['password'];
			}

		if(isset($crptpass) && !empty($crptpass) && $password == $crptpass)
			{
			echo $login." Is connected with level:".$level;
			////VERIFICATION IS OK SET COOKIES////
			setcookie('admin', 'true'); setcookie('user', $login); setcookie('level', $level); header("Location: content.php");
			}
		else
			{
			////VERIFICATION IS NOK DO NOTHING////
			echo "<br/><center><strong>Please check your login or password !</strong></center>";
			}
		}
?>
