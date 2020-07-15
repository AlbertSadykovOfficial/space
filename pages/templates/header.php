<?php 
	session_start();

	echo "<!DOCTYPE html>\n<html><head> ";
	require_once('functions.php');

	$userstr = ' (Guest)';

	if (isset($_SESSION['user'])) 
	{
		$user 		= $_SESSION['user'];
		$loggedin =	TRUE;
		$userstr	=	" ($user)"; 
	}
	else $loggedin	= FALSE;

	echo "<title>$appname</title><link rel = 'stylesheet' ".
				"href = '../../css/style.css' type = 'text/css'>".
				"</head><body>";//.
echo <<<_END
 	<script> 
 		function enter_to_project(id)
 		{
 			content = "<div style='position:fixed; background-color:white; margin-left: 40%; width:20%;'><form method = 'POST' action='../project/project_in.php?project_id="+id+"'>"+
									"<span class = 'fieldname'>Project Password</span>"+
									"<input type = 'text' maxlength='16' name = 'project_password'>"+
									"<br><span class = 'fildname'>&nbsp;</span>"+
 									"<input type='submit' value='Login'>"+
 								'</form></div>';
 			document.getElementsByTagName('body')[0].insertAdjacentHTML('afterBegin',content);
 		}

 	</script>
_END;

	if($loggedin)
	{
				echo 	"<header>You are loggedin,$userstr".
					"<span style='float:right;'><a href='../authentication/logout.php'>Выйти из аккаунта</a></span></header>";
	}
	else
	{
		echo 	("Вы должны быть зарегистрированы".
						" для просмотра.<br><br>");
	}

 ?>