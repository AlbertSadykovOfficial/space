<?php 
    session_start();

    echo "<!DOCTYPE html>\n<html><head> ";
    require_once('functions.php');

    $userstr = ' (Guest)';

    if (isset($_SESSION['user'])) 
    {
        $user 		= $_SESSION['user'];
        $loggedin =	true;
        $userstr	=	" ($user)"; 
    }
    else $loggedin	= false;

    echo "<title>$appname</title><link rel = 'stylesheet' ".
				"href = '../../css/style.css' type = 'text/css'>".
				"</head><body>";//.

    if($loggedin)
    {
        echo 	"<header>".
					"<span style='float:right;'><a href='../authentication/logout.php'>Выйти из аккаунта</a></span></header>";
    }
 ?>