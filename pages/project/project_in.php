<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SPACE</title>
    <link rel="stylesheet" href="../../css/user_menu.css">
    <link rel="stylesheet" href="../../css/window.css">
    <link rel="stylesheet" href="../../css/create_project.css">
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
    <img  src="https://www.space.com/content/logo.png" onclick="show_user_menu();">
    <div class='user_menu hide'>


<?php 
    session_start();

    require_once('../templates/functions.php');
		
    if (isset($_SESSION['user'])) 
    {
        $user 		= $_SESSION['user'];
        $user_id	=	$_SESSION['user_id'];
        $loggedin =	true;
    }
    else
    {
        die('Вы не зарегистрированы');
    }

echo    "<a href='https://www.space.com/pages/profile/profile.php?view_id=$user_id'>Домой</a><br>".
        "<a href='https://www.space.com/pages/authentication/logout.php'>Выйти</a>".
    "</div>".
"</header>";

echo "<div class='main'>";

    if (isset($_GET['project_id'])) 
    {

        $id = stripslashes($_GET['project_id']);

        if (!queryMySQL("SELECT is_blocked FROM users_projects WHERE project_id='$id' AND user_id='$user_id'")->fetch_array(MYSQLI_ASSOC)['is_blocked']) 
        {
			    
            $name = queryMySQL("SELECT name FROM projects WHERE id='$id'")->fetch_array(MYSQLI_ASSOC)['name'];
			
            if (isset($_POST['project_password'])) 
            {
                $project_password = sanitizeString($_POST['project_password']);

                $result = queryMySQL("SELECT admin FROM projects WHERE id='$id' AND pass='$project_password'");
                
                if ($result->num_rows) 
                {
                    $_SESSION['executable_project_id'] 			= $id;
                    $_SESSION['executable_project_name'] 		= $name;
                    $_SESSION['executable_project_password']= $project_password;

                    echo 'Access Allowed...<br>';	
                    die("<a id='go_into_project' style='display:none' href='project.php?project_id=$id'>Open Project</a><script>document.getElementById('go_into_project').click();</script>");
                }else{
                    echo 'Password Incorrect<br>';
                }
            }

            if (isset($_POST['project_password_null']) || $_SESSION['executable_project_password'] == null) 
            {
                $result = queryMySQL("SELECT admin FROM projects WHERE id='$id' AND pass IS NULL");
                if ($result->num_rows) 
                {
                    $_SESSION['executable_project_id'] 			= $id;
                    $_SESSION['executable_project_name'] 		= $name;
                    $_SESSION['executable_project_password']= null;

                    echo 'Access Allowed...<br>';	
                    die("<a id='go_into_project' style='display:none' href='project.php?project_id=$id'>Open Project</a><script>document.getElementById('go_into_project').click();</script>");		
                }
            }

echo <<<_END
    <div class='window' style='margin-top:15%; margin-left:35%'>
        <p style='padding:3%'>Project $name</p>
        <form method = "POST" action = "project_in.php?project_id=$id">$error
            <span class='fieldname'><img src='../../content/pass_icon.png'></span>
            <input type = 'password' maxlength='16' name = 'project_password' value = '$project_password' placeholder='input password'>
            <input class='submit_button' type='submit' value='Login'>
        </form>
    </div><br>
_END;

        }
        else
        {

echo <<<_END
		<div class='window' style='margin-top:15%;'>
			<p style='padding:3%'>Проекта не существует</p>
			<p><a href='https://www.space.com/pages/profile/profile.php?view_id=$user_id'>Домой</a></p>
		</div><br>
_END;
        }

    }
 ?>
        </div>
    </body>
    <script src="../../js/screen_adaptation.js"></script>
</html>