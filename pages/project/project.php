<!DOCTYPE html>
<html lang="en" style='height:100%;'>
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="../../css/config.css">
	<link rel="stylesheet" href="../../css/project/project_style.css">
</head>
<body style='height:100%'>
			<?php 
			header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
			header('Pragma: no-cache'); // HTTP 1.0.
			header('Expires: 0'); // Proxies.
			 
				require_once('project_header.php');
				require_once('project_main.php');
echo "<main style='text-alight: center; margin:auto; height:100%;'>";
				require_once('project_main_list.php');
				//echo "	<div id='short_list'		>1</div>";
				require_once('project_main_paper.php');
				require_once('project_main_board.php');
				
echo	"</main>";
			?>

</body>
<script src="../../js/project/variable.js"></script>
<script src="../../js/project/functions.js"></script>
<script src="../../js/project/project_main_list.js"></script>
</html>