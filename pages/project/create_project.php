<?php 

	require_once('../templates/header.php');
echo <<<_END
		<div class = 'main'><h3>Зполните поля:</h3>
_END;
		if (!$loggedin) 
		{
				die('Вы не зарегистрированы');
		}
		
		if (isset($_POST['project_name']) && isset($_POST['project_password'])) 
		{
			$project_name			=	stripslashes($_POST['project_name']);
			$project_password = stripslashes($_POST['project_password']);
			$user_id					= $_SESSION['user_id'];
				queryMySQL("INSERT INTO projects VALUES(NULL,'$project_name','$project_password',$user_id)");
				
				$project_id	=	queryMySQL("SELECT * FROM projects WHERE name='$project_name' AND pass='$project_password' AND admin=$user_id")->fetch_array(MYSQLI_ASSOC)['id'];

				echo("");
				queryMySQL("INSERT INTO users_projects VALUES($user_id, $project_id)");
				
				die("User id=$user_id Created Project id=$project_id ...Done <br>.<a href='../profile/profile.php?view_id=$user_id'>Home page</a><br><br>");
		}
		else
		{
			echo "Вы не заполнили поля";
		}

echo <<<_END
			<form method = "POST" action = "create_project.php">$error
				<span class = 'fieldname'>Project Name</span>
					<input type = 'text' maxlength='16' name = 'project_name' value = '$project_name'> <!-- onBlur='checkUser(this)'--->
					<span id='info'></span><br>
				<span class = 'fieldname'>Project Password</span>
					<input type = 'text' maxlength='16' name = 'project_password' value = '$project_password'>
					<br>
_END;
?>

		<span class='fieldname'>&nbsp;</span>
		<input type="submit" value='Create' />
	</form></div><br>
</body>
</html>