<?php 
	session_start();
	require_once("../templates/functions.php");
	$user_id 					= $_SESSION['user_id'];
	$project_name 		= $_SESSION['executable_project_name'];
	$project_id   		= $_SESSION['executable_project_id'];
	
	$project_id_get == '';
	for ($i=0; $i < count($project_id) ; $i++) { 
		$project_id_get	+= (string)$_GET['project_id'][$i]; 	
	}

	if ($project_id != $project_id_get) 
	{
			die("Access Denied!<br>You are not logged in to this project<br>Please, back to profile and Log in to the project<br><a href='../profile/profile.php?view_id=$user_id'>Back to profile</a>");
	}

	if(!(queryMySQL("SELECT * FROM users_projects WHERE user_id=$user_id AND project_id=$project_id")->num_rows))
	{
		$header_output = "$project_name <form style='display:inline-block;' method = 'POST' action = 'join_the_project.php'><input type='submit' name='project_num' value='JOIN_#$project_id'></form>";
	}
	else $header_output = $project_name; 

////////////	
$AVAILABLE_FILE_TYPES = [
													 'image/',
													 'audio/',
													 'text/plain',
													 'application/',
													 'video/',
													 'model/'
													];
$success = false;

  if (isset($_FILES['my_file']['name']))
  {
   if ($_FILES['my_file']['error'] === UPLOAD_ERR_OK)
   {
			$data_type =  $_FILES['my_file']['type'];
		   
		  for ($i=0; $i < count($AVAILABLE_FILE_TYPES); $i++)
		  {
		   	if (strpos($data_type,$AVAILABLE_FILE_TYPES[$i]) !== false) 
		   	{

		   		$success = true;
		   		break;
		   	}
	   	}
	  
		  if ($success) 
		  {
		  	$destiation_dir ='../../images/tmp/' . $_FILES['my_file']['name']; // директория для размещения файла
				if (move_uploaded_file($_FILES['my_file']['tmp_name'], $destiation_dir))  //перемещение в желаемую директорию
				$file_upload_info = "'".'Файл успешно загружен'."'"; //оповещаем пользователя об успешной загрузке файла
				else
				$file_upload_info = "'".'Файл Не удалось загрузить'."'"; 
		  }
		  else
		  {
		  	$file_upload_info = "'".'Файл не соответсвует доступному типу'."'";
		  }
   }
   else
   {
   	$file_upload_info = "'Technical_ERROR:".$_FILES['my_file']['error']."'";
   }
  }else{ $file_upload_info = false; }


  $current_dir = '../../images/tmp/';
  $dir = opendir($current_dir);

  //echo "<p>Каталог загрузки: $current_dir</p>";
  //echo '<p>Содержимое каталога:</p><ul>';
  $content = "<ul>";
  while ($file = readdir($dir))
  {
  	if ($file !== '.' && $file !== '..')
  	{
  		$content = $content."<li onclick=document.getElementById('ref').value='$file'; >$file</li>";
  	}
   
  }
  $content = $content.'</ul>';
  closedir($dir);
  echo "<script>let my_files = \"".$content."\";</script>";

//////////////
	echo "<header>".
	"<span style='text-align: left;'><a href='project.php?project_id=$project_id' style='text-decoration:none; color:white;'>$header_output</a></span>".
	"<span style='text-align: center;'><a href='project_settings.php?project_id=$project_id'>Settings</a></span>".
	"<span style='text-align: right;'><a href='../profile/profile.php?view_id=$user_id'>User info</a></span>".
	"</header>";
	

	if ($file_upload_info !== false) 
	{
		echo "<script>alert($file_upload_info);</script>";
	}
	
?>


<script>
	
	function upload_form(id)
	{
		form = "<form method='POST'  action = 'project.php?project_id="+id+"' style='position:fixed; z-index:1002; text-align:center; background-color:white; width:auto; height:auto; margin-left:35%;' id='xxx' enctype='multipart/form-data'>"+
						"<button onclick=getElementById('xxx').remove() >CLOSE</button>"+
						"<h3>Загрузите файлы</h3>"+
						"<input type='hidden' name='MAX_FILE_SIZE' value='104857600'>"+
						"		File:<br><input type='file' name='my_file' size='14'><br>"+
						"		<input type='submit' value='Load'>"+
						"</form>";

		document.getElementById('paper').insertAdjacentHTML('afterBegin',form);
	}
</script>