<?php 
    require_once('../templates/header.php');
    echo "<link rel = 'stylesheet' type = 'text/css' href = '../../css/config.css'>";
    echo "<link rel = 'stylesheet' type = 'text/css' href = '../../css/authentication_dark.css'>";
    echo "<link rel = 'stylesheet' type = 'text/css' href = '../../css/window.css'>";
    echo "<div class='main'><div><div style='width:5%; margin:0 auto;'><img src='../../content/logo.png' style='width:100%;'></div><div class='window' style='margin:0 auto;'><div><p>LOGIN</p></div>";
//<h3>Пожауйста, введите свои данные</h3> или <a href='signup.php'>ЗАРЕГИСТРИРУЙТЕСЬ</a>
    $error = $user = $pass ="";

    if (isset($_POST['user'])) 
    {
        $user = strtolower(sanitizeString($_POST['user']));
        $pass = sanitizeString($_POST['pass']);

        if ($user =='' || $pass == '') 
        {
            $error = 'Не все поля заполнены<br>';
        }
        else
        {
            $result = queryMySQL("SELECT user,pass 
                                    FROM members 
                                    WHERE user='$user' AND pass='$pass'");
	
            if ($result->num_rows == 0) 
            {
                $error = "<span class='error'>Username/Password не верны</span><br>";
            }
            else
            {
	 				
                $_SESSION['user_id']   = queryMySQL("SELECT id 
                                                        FROM members 
                                                        WHERE user='$user' AND pass='$pass'")->fetch_array(MYSQLI_ASSOC)['id'];
                $_SESSION['user']      = $user;
                $_SESSION['user_pass'] = $pass;

                $user_id = $_SESSION['user_id'];
                echo    "<br>Вы вошли. Ваш id = $user_id <br>".
                        "Пожалуйтса".
                        "<a id='href' href='../profile/profile.php?view_id=$user_id'>"."(Нажмите ЗДЕСЬ)</a>,".
                        "чтобы продолжить.<br><br>";
                echo    "<script>document.getElementById('href').click();</script>";
            }
        }
	 }

echo <<<_END
        <form method='POST' action='login.php'>$error  
            <span class='fieldname'><img src='../../content/login_icon.png'></span><input type='text' maxlength='16' name='user' value='$user' placeholder='LOGIN'><br>  
            <span class='fieldname'><img src='../../content/pass_icon.png'></span><input type='password' maxlength='16' name='pass' value='$pass' placeholder='PASSWORD'><br>
_END;
 ?>
 	<!--	<span class = 'fildname'>&nbsp;</span>-->
        <input class='submit_button' type='submit' value='Enter'></form></div><div></div>
    </body>
    <script src="../../js/screen_adaptation.js"></script>
</html>