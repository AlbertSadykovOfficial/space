<?php
	require_once('../templates/header.php');
echo <<<_END
		<div class = 'main'><h3>Процесс удаления проекта</h3>
_END;
		if (!$loggedin) 
		{
			die('You are Unregistred');
		}

		if (isset($_GET['project_id']))
		{
				$project_id = $_GET['project_id'];
				$admin_id 	= queryMySQL("SELECT * FROM projects WHERE id=$project_id")->fetch_array(MYSQLI_ASSOC)['admin'];
				$user_id		= $_SESSION['user_id'];
				$project_password = queryMySQL("SELECT * FROM projects WHERE id=$project_id")->fetch_array(MYSQLI_ASSOC)['pass'];
				$project_password_by_user = $_POST['project_password'];
			
					if($user_id !== $admin_id)
					{
						die("Access Denied<br>You must have administrator Roots<br><a href='../profile/profile.php?view_id=$user_id'>BACK to profile page</a>");
					}
					
					if ($user_id === $admin_id && $project_password === $project_password_by_user) 
					{
						// Не работает
							$pj_id = $project_id.'_';
							queryMySQL("DELETE FROM report WHERE id LIKE '$pj_id%'");
							queryMySQL("DELETE FROM list 	 WHERE id LIKE '$pj_id%'");

							queryMySQL("DELETE FROM users_projects WHERE project_id = $project_id");
							queryMySQL("DELETE FROM projects  		 WHERE id 				= $project_id");

							$_SESSION['executable_project_id'] 			= null;
							$_SESSION['executable_project_name'] 		= null;
							$_SESSION['executable_project_password']= null;

							die("Successful<br><a href='../profile/profile.php?view_id=$user_id'>BACK to profile page</a>");
					}
					else if($project_password_by_user!='' && $project_password != $project_password_by_user){
						die("Access Denied<br><a href='../profile/profile.php?view_id=$user_id'>BACK to profile page</a>");
					}
		
		}
			
echo <<<_END
			<form method = "POST" action = "delete_project.php?project_id=$project_id">$error
				<span class = 'fieldname'>Input Password: </span>
					<input type = 'text' maxlength='16' name = 'project_password'>
					<br>
					<span class='fieldname'>&nbsp;</span>
		<input type="submit" value='Delete' />
	</form>
_END;

?>
		</div><br>
</body>
</html>