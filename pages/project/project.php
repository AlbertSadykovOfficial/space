<!DOCTYPE html>
<html lang="en" style='height:100%;'>
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="../../css/config.css">
	
	<link rel="stylesheet" href="../../css/project/project_style.css">
	<link rel="stylesheet" href="../../css/project/project_list.css">
	<link rel="stylesheet" href="../../css/project/project_paper.css">
	<link rel="stylesheet" href="../../css/project/project_board.css">
</head>
<body>
			<?php 
			header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
			header('Pragma: no-cache'); // HTTP 1.0.
			header('Expires: 0'); // Proxies.
			 
				require_once('project_header.php');
				require_once('project_main.php');
echo "<main>";
				require_once('project_main_list.php');
				require_once('project_main_paper.php');
				require_once('project_main_board.php');
				
echo	"</main>";
			?>
</body>
<script>
	
// Очистка мусора (кода выводили данные нужно было юзать скрипт для присвоения значений, это порадило много ненужных тегов) 
	
	for(i=0; i < document.getElementsByTagName('body')[0].getElementsByTagName('script').length; i++)
	{
    document.getElementsByTagName('body')[0].getElementsByTagName('script')[0].remove();
   }
// 

  function upload_form(id)
	{
		form = "<form method='POST' id='xxx' action = 'project.php?project_id="+id+"' style='position:fixed; z-index:1002; text-align:center; background-color:white; width:auto; height:auto; margin-left:35%;' enctype='multipart/form-data'>"+
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
  <script src="../../DOC_JS/test/vendor/tinymce.min.js"></script>
  <script src="../../DOC_JS/test/vendor/FileSaver.js"></script>
  <script src="../../DOC_JS/dist/html-docx.js"></script>

<script src="../../js/project/variable.js"></script>
<script src="../../js/project/functions.js"></script>
<script src="../../js/project/project_list.js"></script>
<script src="../../js/project/project_paper.js"></script>
<script src="../../js/project/project_board.js"></script>
</html>