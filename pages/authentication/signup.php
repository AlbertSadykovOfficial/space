<?php 

    require_once('../templates/header.php');
    $server_content_folder = 'http://space.com/content';

    echo    "<link rel = 'stylesheet' type = 'text/css' href = '../../css/config.css'>";
    echo    "<link rel = 'stylesheet' type = 'text/css' href = '../../css/authentication_dark.css'>";
    echo    "<link rel = 'stylesheet' type = 'text/css' href = '../../css/window.css'>";

echo <<<_END
	    <div class = 'main'>
        <div>
            <div style='width:5%; margin:0 auto;'>
                <img src='../../content/logo.png' style='width:100%;'>
            </div>
            <div class='window' style='margin:0 auto;'>
            <div>
            <p>SIGN UP</p>
        </div>
_END;

    $error = $user = $pass = "";
    if (isset($_SESSION['user'])) destroySession();

    if (isset($_POST['user']))
    {
        $user 			= strtolower(sanitizeString($_POST['user']));
        $pass 			= sanitizeString($_POST['pass']);
        $retry_pass = sanitizeString($_POST['retry_pass']);
        $nickname 	= sanitizeString($_POST['nickname']);
        $profession = sanitizeString($_POST['profession']);
        $company 		= sanitizeString($_POST['company']);
        $location 	= sanitizeString($_POST['location']);
        $href 			= sanitizeString($_POST['href']);

        if ($user == "" || $pass == "") 
            echo    'Данные введены не во все поля';
        else
        {
			
            $result = queryMySQL("SELECT * FROM members WHERE user = '$user'");
            if ($result->num_rows) 
                $error = 'Такое имя уже существует<br>';
            else
            {
                if ($pass === $retry_pass)
                {
                    queryMySQL("INSERT INTO members VALUES(NULL,'$user','$pass','$nickname','$profession','$company','$location','$href', NULL)");
                    die("<h4>Аккаунт Создан</h4> Пожалуйста, <a id='href' href='login.php'>(войдите)</a>. <script>document.getElementById('href').click();</script><br><br>");
                }else
                {
                    $error = 'Пароли не совпадают<br>';
                }
				
            }
        }
    }

echo <<<_END
        <form method = "POST" action = "signup.php">$error
            <span class='fieldname'><img src='$server_content_folder/login_icon.png'></span><input type='text' maxlength='16' name='user' value='$user' placeholder='LOGIN'><br>
            <span class='fieldname'><img src='$server_content_folder/pass_icon.png'></span><input type='password' maxlength='16' name='pass' placeholder='PASSWORD'><br>
            <span class='fieldname'></span><input type='password' maxlength='16' name='retry_pass' placeholder='RETRY PASSWORD'><br>
            <span class='fieldname'><img src='$server_content_folder/login_icon.png'></span><input type='text' maxlength='30' name='nickname' placeholder='Nickname'><br>
            <span class='fieldname'><img src='$server_content_folder/speciality.png'></span><input type='text' maxlength='100' name='profession' placeholder='Profession'><br>
            <span class='fieldname'><img src='$server_content_folder/company_icon.png'></span><input type='text' maxlength='100' name='company' placeholder='Company'><br>
            <span class='fieldname'><img src='$server_content_folder/city_icon.png'></span><input type='text' maxlength='100' name='location' placeholder='Location'><br>
            <span class='fieldname'><img src='$server_content_folder/link_icon.png'></span><input type='text' maxlength='16' name='href' placeholder='Your Site (link)'><br>
_END;
?>
          <input class='submit_button' type='submit' value='Enter'>
        </form>
      </div>
    </div>
  </div>
</body>
<script src="../../js/screen_adaptation.js"></script>
</html>
 