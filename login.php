<?php
session_start();

include_once 'db.php';

if(isset($_SESSION['user']))
{
	echo <<<_END
	<meta http-equiv='refresh' content='0;url=index.php'>
_END;
}
else
{
	if(isset($_POST['email']) && isset($_POST['pword']) && $_POST['email']!='' && $_POST['pword']!='')
	{
		$email = mysqli_real_escape_string($db,$_POST['email']);
		$pword = mysqli_real_escape_string($db,$_POST['pword']);
		
		$q = "SELECT * FROM people WHERE email='$email' LIMIT 1";
		$r = mysqli_query($db,$q);
		
		$row = mysqli_num_rows($r);
		
		if($row>0)
		{
			$res = mysqli_fetch_assoc($r);
			
			$db_email = $res['email'];
			$db_pword = $res['pword'];
			
			if($db_pword == $pword)
			{
				$_SESSION['email'] = $email;
				$_SESSION['pword'] = $pword;
				$_SESSION['fname'] = $res['fname'];
				$_SESSION['lname'] = $res['lname'];
				$_SESSION['user'] = $res['id'];
				
				$msg = "You are Logged In now";
				echo <<<_END
				<meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
			}
			else
			{
				$msg = "Invalid Password";
				echo <<<_END
				<meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
			}
		}
		else
		{
			$msg = "Invalid Details";
			echo <<<_END
			<meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
		}
		
	}
	else
	{
		$msg = "Please enter all the fields";
		echo <<<_END
	<meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>	
_END;
	}
}

?>