<?php 
		require_once('../templates/header.php');
		
		if (!$loggedin) 
		{
			die('ИЗЫДИ, незарегестрированный');
		}
		
echo "<div class='main'><h3>Вход в комнату</h3>";

		if (isset($_GET['project_id'])) 
		{

			$id = stripslashes($_GET['project_id']);
			
			$name = queryMySQL("SELECT * FROM projects WHERE id='$id'")->fetch_array(MYSQLI_ASSOC)['name'];
			echo "<br> Проект $name <br>";
		
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
						echo '<br>Password Incorrect<br>';
					}
				}
		
		}
echo <<<_END
			<form method = "POST" action = "project_in.php?project_id=$id">$error
				<span class = 'fieldname'>Project Password</span>
					<input type = 'text' maxlength='16' name = 'project_password' value = '$project_password'>
					<br>
_END;
 ?>

  		<span class = 'fildname'>&nbsp;</span>
 		<input type='submit' value='Login'>
 	</form><br></div>
 </body>
</html>