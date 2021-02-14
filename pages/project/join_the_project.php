<?php 
    require_once('../templates/header.php');
    
    if(isset($_POST['project_num']))
    {
        $user_id = $_SESSION['user_id'];

        $joined_to_this_project = $_POST['project_num'];
        $joined_to_this_project = (int)substr($joined_to_this_project,6);
			
        if($joined_to_this_project != null)
            queryMySQL("INSERT INTO users_projects VALUES($user_id, $joined_to_this_project,false)");
			
        echo "User id=$user_id joined the Project id=$joined_to_this_project<br><a id='go_into_project' href='project.php?project_id=$joined_to_this_project'>Go Into Project</a> <script>document.getElementById('go_into_project').click();</script>";
    }
?>