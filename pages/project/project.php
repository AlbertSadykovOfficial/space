<?php
    header("Expires: Thu, 19 Feb 1998 13:24:18 GMT"); 
    header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); 
    header("Cache-Control: no-cache, must-revalidate"); 
    header("Cache-Control: post-check = 0, pre-check = 0"); 
    header("Cache-Control: max-age = 0"); 
    header("Pragma: no-cache");
    header('Expires: 0'); // Proxies.
    session_start();

    $domain = 'http://space.com';
?>
<!DOCTYPE html>
<html lang="en" style='height:100%;'>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/config.css">
	
    <link rel="stylesheet" href="../../css/user_menu.css">
<!--	<link rel="stylesheet" href="../../css/project/checkbox.css">-->
    <link rel="stylesheet" href="../../css/project/project_style.css">
    <link rel="stylesheet" href="../../css/project/project_list.css">
    <link rel="stylesheet" href="../../css/project/project_paper.css">
    <link rel="stylesheet" href="../../css/project/project_board.css">
<?php 
    if (isset($_COOKIE['theme']))
        echo "<link id='".$_COOKIE['theme']."_theme_css' rel = 'stylesheet' type = 'text/css' href = '../../css/light/project_".$_COOKIE['theme'].".css'>"
?>

    <script>	
        function show_user_menu()
        {
            if(document.getElementsByClassName('user_menu')[0].classList.contains('hide'))
                document.getElementsByClassName('user_menu')[0].classList.remove('hide');
            else
                document.getElementsByClassName('user_menu')[0].classList.add('hide');
        }
    </script>
</head>
<body>

<?php 
    require_once('project_header.php');
    require_once('project_main.php');
echo    "<main>";
    require_once('project_main_list.php');
    require_once('project_main_paper.php');
    require_once('project_main_board.php');
				
echo	"</main>";
?>

</body>
<script>
	
// Очистка мусора (кода выводили данные нужно было юзать скрипт для присвоения значений, это порадило много ненужных тегов) 
	
    how_scripts = document.getElementsByTagName('body')[0].getElementsByTagName('script').length;
    for(i=0; i < how_scripts; i++)
    {
        document.getElementsByTagName('body')[0].getElementsByTagName('script')[0].remove();
    }
// 

    function upload_form(id)
    { //background-color:white; width:auto; height:auto; 
        form = "<form method='POST' id='file_upload_form' action = 'project.php?project_id="+id+"' style='position:fixed; z-index:1002; text-align:center; margin-left:35%; margin-top:10%;' enctype='multipart/form-data'>"+
                    "<div class='menu'>"+
                        "<span>Загрузка файлов</span><button class='close_button' onclick=getElementById('file_upload_form').remove() ><img src='"+server_content_folder+"/close.png'></button>"+
                        "<input type='hidden' name='MAX_FILE_SIZE' value='104857600'>"+
                    "		Files:<br>"+
                        "<input type='file' name='my_files[]' multiple>"+
                        "<button class='add_button' style='margin-left:0 !important;' type='submit'>LOAD</button>"+
                    "</div>"+
                "</form>";

        document.getElementById('paper').insertAdjacentHTML('afterBegin',form);
    }
</script>
<script src="../../js/change_theme.js"></script>
<script src="../../js/screen_adaptation.js"></script>

<script src="../../DOC_JS/test/vendor/tinymce.min.js"></script>
<script src="../../DOC_JS/test/vendor/FileSaver.js"></script>
<script src="../../DOC_JS/dist/html-docx.js"></script>

<script src="../../js/project/switcher.js"></script>
<script src="../../js/project/project_list.js"></script>
<script src="../../js/project/project_paper.js"></script>
<script src="../../js/project/project_board_canvas.js"></script>
<script src="../../js/project/project_board.js"></script>


<!--<script src='../../js/project/calendar.js'></script>-->
<script src='../../js/AJAX/ajaxRequest.js'></script>

<!--
<script src='../../js/project/hyphenation.js'></script>
<script src='../../js/project/handwrite.js'></script>
-->
<script src='../../js/project/to_photo.js'></script>
</html>