<?php 
	session_start();

	echo "<!DOCTYPE html>\n<html><head>";
	require_once('functions.php');

	$userstr = ' (Guest)';

	if (isset($_SESSION['user'])) 
	{
		$user 		= $_SESSION['user'];
		$loggedin =	TRUE;
		$userstr	=	" ($user)"; 
	}
	else $loggedin	= FALSE;

	echo "<title>$appname$userstr</title><link rel = 'stylesheet'".
				"href = '../../css/style.css' type = 'text/css'>".
				"</head><body>";//.
		//		"<div class = 'appname'>$appname$userstr</div>";

/* 
	Ошибка:
		Смотря с какого файла вызывавть header, он может выйти не в ту папку и 
		не найти logout.php
*/
	if($loggedin)
	{
		echo 	"You are loggedin,$userstr".
					"<div><a href='../authentication/logout.php'>Выйти из аккаунта</a></div>";
	}else
	{
			echo 	("Вы должны быть зарегистрированы".
						" для просмотра.<br><br>");
						
	}

 ?>