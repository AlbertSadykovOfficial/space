<?php 

	require_once('../templates/header.php');

	echo <<<_END
		<div class = 'main'><h3>Введите регистрационные данные:</h3>
_END;

	$error = $user = $pass = "";
	if (isset($_SESSION['user'])) destroySession();

	if (isset($_POST['user']))
	{
		$user = strtolower(sanitizeString($_POST['user']));
		$pass = sanitizeString($_POST['pass']);

		if ($user == "" || $pass == "") 
			echo 'Данные введены не во все поля';
		else
		{
				$result = queryMySQL("SELECT * FROM members WHERE user = '$user'");
				if ($result->num_rows) 
					$error = "Такое имя уже существует";
				else
				{
					queryMySQL("INSERT INTO members VALUES(NULL,'$user','$pass')");
					die("<h4>Аккаунт Создан</h4> Пожалуйста, войдите.<a href='login.php'>ВОЙТИ</a><br><br>");
				}
		}
	}

	echo <<<_END
			<form method = "POST" action = "signup.php">$error
				<span class = 'fieldname'>Username</span>
					<input type = 'text' maxlength='16' name = 'user' value = '$user'> <!-- onBlur='checkUser(this)'--->
					<span id='info'></span><br>
				<span class = 'fieldname'>Password</span>
					<input type = 'text' maxlength='16' name = 'pass' value = '$pass'>
					<br>
_END;
?>
		<span class='fieldname'>&nbsp;</span>
		<input type="submit" value='Sign up' />
	</form></div><br>
</body>
</html>
 