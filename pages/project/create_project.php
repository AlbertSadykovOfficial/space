<?php
    session_start();
?>
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
    <img  src="http://space.com/content/logo.png" onclick="show_user_menu();">
    <div class='user_menu hide'>

<?php 
    require_once('../templates/functions.php');
    if (isset($_SESSION['user'])) 
    {
        $user 	  = $_SESSION['user'];
        $user_id  = $_SESSION['user_id'];
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
			
    if (isset($_POST['project_name']) && $_POST['project_name'] != '') 
    {

        $project_name	= stripslashes($_POST['project_name']);
        $about_project	= stripslashes($_POST['about_project']);
        $user_id		= $_SESSION['user_id'];

        if(isset($_POST['project_password']) && $_POST['project_password'] != '')
        {
            $project_password 		= stripslashes($_POST['project_password']);
            $retry_project_password	= stripslashes($_POST['retry_project_password']);
							
            if (isset($_POST['only_key_access']) && $_POST['only_key_access'] == 'true') 
                $is_private = 1;
            else
                $is_private = 0; 
							

            if ($project_password === $retry_project_password) 
            {
                queryMySQL("INSERT INTO projects VALUES(NULL,'$project_name','$project_password',$user_id, $is_private)");
								
                $project_id	=	queryMySQL("SELECT * FROM projects WHERE name='$project_name' AND pass='$project_password' AND admin=$user_id")->fetch_array(MYSQLI_ASSOC)['id'];
                queryMySQL("INSERT INTO list VALUES('$project_id','$project_name','$about_project','$user',NULL,'[]', NULL)");
                queryMySQL("INSERT INTO report VALUES('$project_id', 1, '<div>$about_project</div>','$project_name')");
                queryMySQL("INSERT INTO users_projects VALUES($user_id, $project_id,0)");

                mkdir("../../storage/project_$project_id", 0770);
                mkdir("../../storage/project_$project_id/another", 0770);
                mkdir("../../storage/project_$project_id/image", 0770);
                mkdir("../../storage/project_$project_id/multimedia", 0770);
                mkdir("../../storage/project_$project_id/office", 0770);
								
                die("User id=$user_id Created Project id=$project_id ...Done <br>.<a href='../profile/profile.php?view_id=$user_id'>Home page</a><br><br>");
            }
            else
            {
                $error = "Введенные пароли не совпали";
            }
        } 
        else if (isset($_POST['no_pass']) && $_POST['no_pass'] == 'true')
        {
						
            queryMySQL("INSERT INTO projects VALUES(NULL,'$project_name', NULL, $user_id, 0)");
            $project_id	=	queryMySQL("SELECT * FROM projects WHERE name='$project_name' AND admin=$user_id AND pass IS NULL")->fetch_array(MYSQLI_ASSOC)['id'];
            queryMySQL("INSERT INTO list VALUES('$project_id','$project_name','$about_project','$user',NULL,'[]', NULL)");
            queryMySQL("INSERT INTO report VALUES('$project_id', 1, '<div>$about_project</div>','$project_name')");
            queryMySQL("INSERT INTO users_projects VALUES($user_id, $project_id,0)");
						
            mkdir("../../storage/project_$project_id", 0770);
            mkdir("../../storage/project_$project_id/another", 0770);
            mkdir("../../storage/project_$project_id/image", 0770);
            mkdir("../../storage/project_$project_id/multimedia", 0770);
            mkdir("../../storage/project_$project_id/office", 0770);

            die("User id=$user_id Created Project id=$project_id ...Done <br>.<a href='../profile/profile.php?view_id=$user_id'>Home page</a><br><br>");
        }

    }
    else
    {
        $error = "Заполните все поля";
    }

echo <<<_END
        <div class='window' style='margin:0 auto; margin-top:5%;'>
            <p>NEW PROJECT</p>
            <form method = 'POST' action='../project/create_project.php'>$error<br>
                <span class='fieldname'><img src='../../content/galaxy_icon.png'></span><input type = 'text' maxlength='16' name = 'project_name' 						placeholder='NAME'><br>
                <span class='fieldname'><img src='../../content/pass_icon.png'></span>Access Mode<br>
                <div class=menu_controls id=''>
                    <button type='button' onclick=mode(1)>no password</button>
                    <button type='button' onclick=mode(2)>password</button>
                    <button type='button' onclick=mode(3)>only key</button>
                </div>
                <div class='pass_div'>
                    <input type = 'password' maxlength='16' name = 'project_password' 				placeholder='PASSWORD'><br>
                    <input type = 'password' maxlength='16' name = 'retry_project_password'	placeholder='RETRY PASSWORD'><br>
                </div>
                <textarea name = 'about_project' placeholder='Write Something about your Project'></textarea><br>
                <input class='submit_button' type='submit' value='CREATE'>
            </form>
        </div>
_END;
?>
    </div>
</body>
<script>
    function mode(type)
    {
        if (type == 1) 
        {
            document.getElementsByClassName('pass_div')[0].innerHTML = 'Проект будет без пароля <input type=text name=no_pass value=true style=display:none>';
        }
        else if (type == 2)
        {
            document.getElementsByClassName('pass_div')[0].innerHTML = 
            "<input type = 'password' maxlength='16' name = 'project_password' 				placeholder='PASSWORD'><br>"+
            "<input type = 'password' maxlength='16' name = 'retry_project_password'	placeholder='RETRY PASSWORD'><br>";
        }
        else if (type == 3)
        {
            document.getElementsByClassName('pass_div')[0].innerHTML = 
            "<input type = 'text' maxlength='4' name = 'only_key_access' value='true' style='display:none'>"+
            "<input type = 'password' maxlength='16' name = 'project_password' 				placeholder='PASSWORD'><br>"+
            "<input type = 'password' maxlength='16' name = 'retry_project_password'	placeholder='RETRY PASSWORD'><br>";
        }
    }
</script>
<script src="../../js/screen_adaptation.js"></script>
</html>