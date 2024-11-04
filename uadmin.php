<?php
echo'<head><link rel="stylesheet" href="login.css"></head>';
include 'header.php';
//print_r($_POST);
if(!empty($_POST['edit'])){ $exp=explode("|",$_POST['moduser']); $_POST['moduser'] = $exp[0]; $_POST['userlevel'] = $exp[1]; }
if($_COOKIE['level'] != "3"){ header("Location: index.php"); }

if(!empty($_POST['moduser']) && empty($_POST['removeit']))
	{
	echo'<table align="center" width="80%" border="0"><tr><td colspan="2">';
	echo"<div align='center'><p>Change your password for:".$_POST['moduser']."<br/>or enter a new Username and fill all informations for a user creation</p>";
	echo"<div align='center'><strong>The password must have at least one uppercase letter, one lowercase";
	echo"letter, one number and a <br/>special character to choose from the following: * % - _</strong></div></p>";
	
	///////////////////////////////////////////////////////////////////FORM////////////////////////////////////////////////////////////////////////////////
	echo'<div class="login1" align="center">';
	echo'<form id="login" name="login" method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">';
	echo"<label>User: <input type=\"text\" name=\"moduser\" value=\"".$_POST['moduser']."\" /></label>";
	echo'<label> Password: <input name="password" type="password" value="" /></label></p>';
	echo'<label> Re-enter: <input name="passwordctrl" type="password" value="" /></label></p>';
	echo'<label> Level: <select name="level">';
	echo'<option selected value="'.$_POST['userlevel'].'">'.$_POST['userlevel'].'</option>';	
	echo'<option value="1">1</option>';
	echo'<option value="2">2</option>';
	echo'<option value="3">3</option>';
	echo'</select></label></p>';
	echo'<label><input type="submit" class="sub" name="Submit" value="Submit" /></label></form></div>';
	echo'</td></tr></table>';
	////////////////////////////////////////////////////////////////FORM  END//////////////////////////////////////////////////////////////////////////////
	
	if(!empty($_POST['password']) && !empty($_POST['moduser']) && !empty($_POST['passwordctrl']) && !empty($_POST['Submit']))
		{
		if($_POST['password'] != $_POST['passwordctrl']){ echo"<center>Password control not match, retry!";	}
		else
			{
			$validate = (checkpw($_POST['password']));
			if(empty($validate)){ echo"<br/><br/><br/><br/><center>the password ".$_POST['password']." is not valid</center>";	}
			else
				{
				if (strlen($_POST['password']) <= 8){echo"<br/><br/><br/><br/><center>The password ".$_POST['password']." is not valid, it must be at least 8 characters long</center>";}
				else
					{
					///////////////END PWD VALIDATION///////////////
					$pws = hash("sha256", $_POST['password']);
					$lgn = $_POST['moduser'];
					$lev = $_POST['level'];
					$check = 'SELECT * FROM "user" WHERE "username" like \''.$lgn.'\''; $rcheck = $con->query($check); $row = $rcheck->fetchArray(SQLITE3_ASSOC);
					if(!empty($row['username']))
						{
						$query = 'UPDATE "user" SET "password" = "'.$pws.'", "level" = "'.$lev.'" WHERE "username" like \''.$lgn.'\''; $con->query($query);
						}
					else
						{
						$query = 'INSERT INTO "user" ("username","password","level") VALUES ("'.$lgn.'","'.$pws.'","'.$lev.'")'; $con->query($query);
						}
					echo"<br/><br/><br/><center><label>Success your new password is set to: ".$_POST['password']." for Login: ".$_POST['moduser']."</label></center></br>";
					}
				} 
			}
		}
	}
if(!empty($_POST['removeit']) && $_POST['removeit'] == "yes")
	{
	$query = 'DELETE FROM "user" WHERE "username" like \''.$_POST['moduser'].'\''; $con->query($query);
	header("Location: account.php?pwadm=1");
	}
?>