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


function is_office_format($type)
{
		if (strpos($type,'msword') !== false || strpos($type,'vnd.openxmlformats-officedocument.') !== false || strpos($type,'vnd.ms-word.') !== false ) return true;
		else if (strpos($type,'msexcel') !== false || strpos($type,'vnd.ms-excel.') !== false) return true;
		else if (strpos($type,'mspowerpoint') !== false || strpos($type,'vnd.ms-powerpoint.') !== false) return true;
		else if (strpos($type,'msaccess') !== false) return true;
		else if (strpos($type,'vnd.ms-office.calx') !== false || strpos($type,'x-winhelp') !== false || strpos($type,'msproject') !== false || strpos($type,'vnd.ms-officetheme') !== false || strpos($type,'mswrite') !== false) return true;
		else return false;
}
////////////
$AVAILABLE_FILE_TYPES = [
													 ['image/','image/'],
													 ['audio/', 'multimedia/'],
													 ['text/plain','another/'],
													 ['application/','another/'],
													 ['video/','multimedia/'],
													 ['model/','another/']
													];
$success = false;

  if (isset($_FILES['my_files']['name']))
  {
	  foreach ($_FILES['my_files']['error'] as $key => $error)
	  {
	  	$success = false;
	  	if ($error === UPLOAD_ERR_OK)
	   	{
				$data_type =  $_FILES['my_files']['type'][$key];
			   
			  for ($i=0; $i < count($AVAILABLE_FILE_TYPES); $i++)
			  {
			   	if (strpos($data_type,$AVAILABLE_FILE_TYPES[$i][0]) !== false) 
			   	{
			   		$fdr = $AVAILABLE_FILE_TYPES[$i][1];
			   		if(is_office_format($data_type)) $fdr = 'office/';

			   		$success = true;
			   		break;
			   	}
		   	}
		  
			  if ($success) 
			  {
			  	//$destiation_dir = 'https://www.space.com/storage/project_'.$project_id.'/'. $_FILES['my_files']['name'][$key]; // директория для размещения файла'
			  	echo "<script>console.log('".$_FILES['my_files']['name'][$key].",".$_FILES['my_files']['tmp_name'][$key].",".$_FILES['my_files']['type'][$key]."');</script>";
					$destiation_dir = '../../storage/project_'.$project_id.'/'.$fdr.iconv('utf-8','windows-1251',$_FILES['my_files']['name'][$key]);
					if (move_uploaded_file($_FILES['my_files']['tmp_name'][$key], $destiation_dir))  //перемещение в желаемую директорию
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
	   		$file_upload_info = "'Technical_ERROR:".$_FILES['my_files']['error'][$key]."'";
	   	}
	 	}
  }else{ $file_upload_info = false; }


  $server_project_folder = 'https://www.space.com/storage/project_'.$project_id;
  $server_content_folder = '../../content';
  echo "<script>server_project_folder = '$server_project_folder'; server_content_folder = '$server_content_folder'</script>";
  $current_dir = '../../storage/project_'.$project_id;
  $dir = opendir($current_dir);

  //echo "<p>Каталог загрузки: $current_dir</p>";
  //echo '<p>Содержимое каталога:</p><ul>';
echo "<script>let my_files = [];</script>";
  $content = "<ul>";
  $count = 0;
  while ($folder = readdir($dir))
  {
  	if ($folder !== '.' && $folder !== '..')
  	{
  		$file_list = "<ul id='$folder'>";
  		$tmp_dir = opendir($current_dir.'/'.$folder);
  		while ($file = readdir($tmp_dir))
  		{
  			if ($file !== '.' && $file !== '..')
  			{
  				$file_list = $file_list."<li onclick=document.getElementById('ref').value=this.innerHTML>$file</li>";
  			}
  		}
  		closedir($tmp_dir);
  		$file_list = $file_list."</ul>";
  		 echo "<script>my_files.push(\"".$file_list."\");</script>";
  		$content = $content."<li onclick=open_folder($count,'$folder')>$folder</li>";
  		$count++;
  	}
  }
  $content = $content.'</ul>';
  closedir($dir);
  echo "<script>my_files.push(\"".$content."\");</script>";

  echo "<script>".
  			"function open_folder(i,dir){".
  			"document.getElementById('ref_dir').value = dir+'/';".
  			"document.getElementById('ref_block').querySelector('ul').remove();".
  			"document.getElementById('ref_block').insertAdjacentHTML('beforeEnd',my_files[i]);".
 			 "}</script>";
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
						"		Files:<br>"+
						"<input type='file' name='my_files[]' multiple><br>"+
						"		<input type='submit' value='Load'>"+
						"</form>";

		document.getElementById('paper').insertAdjacentHTML('afterBegin',form);
	}
</script>