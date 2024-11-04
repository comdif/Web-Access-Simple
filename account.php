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
</head>
<?php
include 'header.php';
//PREVENTING TO OPEN THIS PAGE WITHOUT POST/GET
if(!isset($_GET['logout']) && !isset($_GET['pwadm'])){ header("Location: index.php"); }
//LOGOUT REQUESTED
if(!empty($_GET['logout']))
	{
	foreach($_COOKIE as $name => $value)
		{
		setcookie($name, '', 1);
		setcookie($name, '', 1, '/');
		}
	header("Location: index.php");
	}
//ACCOUNT ADMINISTRATION REQUESTED		
if(!empty($_GET['pwadm']))
	{
	echo'<table align="center" width="80%" border="0"><tr><td colspan="2">';
	///////////////////////////////////////////////////////////////////FORM////////////////////////////////////////////////////////////////////////////////
	echo"<div align='center'><p>Change your password for:".$_COOKIE['user']."</p>";                                                                     ///
	echo"<div align='center'><strong>The password must have at least one uppercase letter, one lowercase";                                              /// 
	echo"letter, one number and a <br/>special character to choose from the following: * % - _</strong></div></p>";                                     ///
	echo'<div class="login1" align="center"><form id="login" name="login" method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'?pwadm=1">';  ///
	echo"<label><input type=\"hidden\" name=\"login\" value=\"".$_COOKIE['user']."\" /></label>";
	
	echo'<table><tr><td style="position: relative;">';
	echo'<label> Password: <input name="password" type="password" id="mypwrd" value="" /></label>';
	echo'<div class="card" onmouseover="eyeon()" onmouseout="eyeout()" style="position: absolute; top: 1.09em; left: 15.7em;"></div>';
	echo'</td></tr><tr><td style="position: relative;">';
	
	echo'<label> Re-enter Password: <input name="passwordctrl" type="password"  id="mypwrd2" value="" /></label>';
	echo'<div class="card" onmouseover="eyeon2()" onmouseout="eyeout2()" style="position: absolute; top: 1.09em; left: 15.7em;"></div>';
	echo'</td></tr></table>';
		
	echo'<label><input type="submit" class="sub" name="Submit" value="Submit" /></label></form></div>';
	echo'</td></tr></table>';
	////////////////////////////////////////////////////////////////FORM  END//////////////////////////////////////////////////////////////////////////////
	
	///////////////////////////////////////////////////////////////LEVEL3 MENU/////////////////////////////////////////////////////////////////////////////
	if($_COOKIE['level'] == "3")																														///
		{
		echo'<table align="center" width="20%" border="0"><tr><td width="30%"><label>Other users administration</label><br/><br/></td></tr>';
		$mquery = 'SELECT * FROM user';
		$mresults = $con->query($mquery); 
		echo"<tr><td><form action='uadmin.php' method='post'><select name='moduser'>";
		while($mrow = $mresults->fetchArray(SQLITE3_ASSOC))
			{
			if($mrow['username'] != $_COOKIE['user'])
				{
				echo"<option value='".$mrow['username']."|".$mrow['level']."'>".$mrow['username']." &nbsp; &nbsp; Level: ".$mrow['level']."</option>'>";}
				}
		echo"</select></td></tr><tr><td>";		
		echo'<table><tr><td><label>Check to delete it</label></td><td><input type="checkbox" name="removeit" value="yes"></td></tr></table>';
		echo"</td></tr><tr><td><br/><input type='submit' class='sub' name='edit' value='Submit'/></form>";	
		}
	echo'</td></tr></table>';
	/////////////////////////////////////////////////////////////LEVEL3 MENU END///////////////////////////////////////////////////////////////////////////

	//////////////FORM CONTROL AND ACTION//////////////
	if(!empty($_POST['password']) && !empty($_POST['login']) && !empty($_POST['passwordctrl']))
		{
		if($_POST['password'] != $_POST['passwordctrl'])
			{ 
			echo"<center>Password control not match, retry!";
			}
		else
			{
			////////////////PWD VALIDATION////////////////
			$validate = (checkpw($_POST['password']));
			if(empty($validate))
				{
				echo"<center>the password ".$_POST['password']." is not valid</center>";
				}
			else
				{
				if (strlen($_POST['password']) <= 8)
					{
					echo"<center>The password ".$_POST['password']." is not valid, it must be at least 8 characters long</center>";		
					}
				else
					{
					echo"<center>The password ".$_POST['password']." is OK</center>";
					///////////////END PWD VALIDATION///////////////
					$pws = hash("sha256", $_POST['password']);
					$lgn = $_POST['login'];
					$query = 'UPDATE "user" SET "password" = "'.$pws.'" WHERE "username" like \''.$lgn.'\'';
					$con->query($query);


					echo"<center>Success your new password is set to: ".$_POST['password']." for Login: ".$_POST['login']."</center></br>";
					foreach($_COOKIE as $name => $value)
						{
						setcookie($name, '', 1);
						setcookie($name, '', 1, '/');
						}
					echo"<center><strong><a href='index.php'>Login with your new password</a></strong></center>";
					}
				} 
			}
		}
	}
?>