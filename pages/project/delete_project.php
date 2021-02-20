<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SPACE</title>
    <link rel="stylesheet" href="../../css/window.css">
    <link rel="stylesheet" href="../../css/user_menu.css">
    <link rel="stylesheet" href="../../css/delete_project.css">
    <script>	
        function show_user_menu()
        {
            if(document.getElementsByClassName('user_menu')[0].classList.contains('hide'))
                document.getElementsByClassName('user_menu')[0].classList.remove('hide');
            else
                document.getElementsByClassName('user_menu')[0].classList.add('hide');
        }
    </script>
</head>
<body>
<header>
    <img  src="http://space.com/content/logo.png" onclick="show_user_menu();">
    <div class='user_menu hide'>
<?php
    session_start();

    require_once('../templates/functions.php');
    if (isset($_SESSION['user'])) 
    {
        $user 		= $_SESSION['user'];
        $user_id	=	$_SESSION['user_id'];
        $loggedin =	TRUE;
    }
    else
    {
        die('Вы не зарегистрированы');
    }

echo    "<a href='http://space.com/pages/profile/profile.php?view_id=$user_id'>Домой</a><br>".
        "<a href='http://space.com/pages/authentication/logout.php'>Выйти</a>".
    "</div>".
"</header>";

echo "<div class = 'main'>";

    if (isset($_GET['project_id']))
    {
        $project_id = $_GET['project_id'];
        $admin_id 	= queryMySQL("SELECT * FROM projects WHERE id=$project_id")->fetch_array(MYSQLI_ASSOC)['admin'];
        $user_id	= $_SESSION['user_id'];
        $project_password = queryMySQL("SELECT * FROM projects WHERE id=$project_id")->fetch_array(MYSQLI_ASSOC)['pass'];
        $project_password_by_user = $_POST['project_password'];
			
        if($user_id !== $admin_id)
        {
            die("Access Denied<br>You must have administrator Roots<br><a href='../profile/profile.php?view_id=$user_id'>BACK to profile page</a>");
        }else if ($user_id === $admin_id && $project_password === $project_password_by_user) 
        {
			// Не работает
            $pj_id = $project_id.'_';
            queryMySQL("DELETE FROM report WHERE id LIKE '$pj_id%'");
            queryMySQL("DELETE FROM list   WHERE id LIKE '$pj_id%'");

            queryMySQL("DELETE FROM report WHERE id='$pj_id'");
            queryMySQL("DELETE FROM list   WHERE id='$pj_id'");

            queryMySQL("DELETE FROM users_projects WHERE project_id = $project_id");
            queryMySQL("DELETE FROM projects  	   WHERE id 		= $project_id");

            rmdir("../../storage/project_$project_id");

            $_SESSION['executable_project_id'] 			= null;
            $_SESSION['executable_project_name'] 		= null;
            $_SESSION['executable_project_password']= null;

            die("Successful<br><a href='../profile/profile.php?view_id=$user_id'>BACK to profile page</a>");
        }
        else if($project_password_by_user != '' && $project_password != $project_password_by_user)
        {
            die("Access Denied<br><a href='../profile/profile.php?view_id=$user_id'>BACK to profile page</a>");
        }
    }
			
echo <<<_END
        <div class='window delete_window'>
            <p>Введите данные</p>
            <form method = "POST" action = "delete_project.php?project_id=$project_id">$error
                <span><img src='../../content/galaxy_icon.png'></span>
                <input type = 'text' maxlength='16' name = 'project_name' 						placeholder='REPEAT NAME'><br>
                <span><img src='../../content/pass_icon.png'></span>
                <input type = 'password' maxlength='16' name = 'project_password' 			placeholder='PASSWORD'><br>
                <span></span>
                <input type = 'password' maxlength='16' name = 'retry_project_password'	placeholder='RETRY PASSWORD'><br>
                <input class='submit_button' style='	background-color: rgb(205,37,64); color: rgb(28,35,45);' type="submit" value='DELETE' />
            </form>
        </div>
_END;
?>
        </div><br>
    </body>
    <script src="../../js/screen_adaptation.js"></script>
</html>