<?php  
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel = 'stylesheet' type = 'text/css' href = '../../css/config.css'>
    <link rel = 'stylesheet' type = 'text/css' href = '../../css/window.css'>
    <link rel = 'stylesheet' type = 'text/css' href = '../../css/user_menu.css'>
    <link rel = 'stylesheet' type = 'text/css' href = '../../css/profile.css'>

<?php 
    if (isset($_COOKIE['theme']))
    echo "<link id='".$_COOKIE['theme']."_theme_css' rel = 'stylesheet' type = 'text/css' href = '../../css/light/profile_".$_COOKIE['theme'].".css'>"
?>

</head>
<body>
<header oncontextmenu="change_theme('profile'); return false;">
    <img src="http://space.com/content/logo.png" onclick="show_user_menu();">
    <div class='user_menu hide'>
        <a href="http://space.com/pages/authentication/logout.php">Выйти</a>
    </div>
</header>
<?php  
#    session_start();
    require_once('../templates/functions.php');
    if (isset($_SESSION['user'])) 
    {			
        $user 		= $_SESSION['user'];
        $user_id 	= $_SESSION['user_id'];
        $loggedin   = true;
        $userstr	= " ($user)"; 
        $server_content_folder = 'http://space.com/content';
        echo "<script>let server_content_folder = '$server_content_folder';</script>";
    }
    else 
    {
        die("Вы не зарегистрированы<br><a href='../authentication/login.php'>Войти в аккаунт</a>");
    }
?>
<?php
    if (isset($_POST['change_info_btn']) && $_POST['id_change'] == $user_id) 
    {
        $nickname 	= $_POST['nickname'];
        $profession = $_POST['profession'];
        $company 	= $_POST['company'];
        $location 	= $_POST['location'];
        $href 		= $_POST['href'];
        queryMySQL("UPDATE members SET
										nickname	='$nickname',
										profession  ='$profession',
										company		='$company',
										location	='$location',
										href		='$href' 
										WHERE id ='$user_id'");
    }
?>

<?php
    if (isset($_FILES['user_photo']['name']))
    {
        $saveto = "$user_id.jpg";
        move_uploaded_file($_FILES['user_photo']['tmp_name'], $saveto);
        $typeok = TRUE;

        switch ($_FILES['user_photo']['type']) 
        {
            case 'image/jpeg':
            case 'image/pjpeg':
            $src = imagecreatefromjpeg($saveto); 
            break;

            case 'image/png': $src = imagecreatefrompng($saveto); 
            break;

            case 'image/gif': $src = imagecreatefromgif($saveto); 
            break;

            default:
							$typeok = FALSE;
            break;
        }

        if ($typeok) 
        {
            list($w, $h) = getimagesize($saveto);

            $max = 500;
            $tw = $w;
            $th = $h;

            if ($w > $h && $max < $w) 
            {
                $th = ($max / $w) * $h;
                $tw = $max;
            }
            elseif ($h > $w && $max < $w)
						{
						//	$tw = ($max / $h) * $w;
							//$th = $max;
                $th = ($max / $w) * $h;
                $tw = $max;
            }
            elseif ($max < $w) 
            {
                $tw = $th = $max	;
            }

            $tmp = imagecreatetruecolor($tw, $th);			// созданине новой пустой картинки
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
            imageconvolution($tmp, array(array(-1,-1,-1), array(-1, 16, -1), array(-1, -1, -1)), 8, 0);	// Повышение резкости
            imagejpeg($tmp, '../../storage/user_photo/'.$saveto);
            imagedestroy($tmp);
            imagedestroy($src);
        }

			// Внутренний редирект при загрузке фото
       # header("Location: http://{$_SERVER['SERVER_NAME']}{$_SERVER['SCRIPT_NAME']}?view_id=$user_id&$rnd");
        #exit;
    }
	
?>
<?php 
    echo "<div class='main'>";
    echo "<div class='main_indent'></div>";

    if (isset($_GET['view_id']))
    {
        $view_id = sanitizeString($_GET['view_id']);
    }
    else
    {
        $view_id = $user_id;
        $_GET['view_id'] = $view_id;
    }

    $user_data = queryMySQL("SELECT * FROM members WHERE id = '$view_id'")->fetch_array(MYSQLI_ASSOC);
			
    showProfile($user_data,$server_content_folder);
    showMyProjects($user_data['id'],$server_content_folder);
 ?>
 <?php
    function showProfile($user,$server_content_folder)
    {
        echo "<div class='user_block'> <div class='photo' oncontextmenu='change_profile_photo(); return false'>";
        if (file_exists("../../storage/user_photo/".$user['id'].".jpg")) //$user
			// Не забудь поправить png на jpg
					echo "<img src = 'http://space.com/storage/user_photo/".$user['id'].".jpg' align = 'middle'>";
        else 
					echo "<img src = '$server_content_folder/standart_user_icon.png' align = 'middle'>";
        echo "</div>";

        echo "<div 	class='user_info_string _id'><span>id #</span>".				$user['id'].'</div>'.
                "<textarea class='user_info_string msg' onchange=upload_msg(this,'".$user['id']."')>".	$user['msg'].'</textarea>'.
                "<div 			class='user_info_string'><img src='$server_content_folder/login_icon.png'><span>".	$user['user'].'</span></div>'.
                "<div 			class='user_info_string' data-column='nickname'><img src='$server_content_folder/login_icon.png'><span>".	$user['nickname'].'</span></div>'.
                "<div 			class='user_info_string' data-column='profession'><img src='$server_content_folder/speciality.png'><span>".	$user['profession'].'</span></div>'.
                "<div 			class='user_info_string' data-column='company'><img src='$server_content_folder/company_icon.png'><span>".$user['company'].'</span></div>'.
                "<div 			class='user_info_string' data-column='location'><img src='$server_content_folder/city_icon.png'><span>".		$user['location'].'</span></div>'.
                "<div 			class='user_info_string' data-column='href'><img src='$server_content_folder/link_icon.png'><span><a href='".		$user['href']."'>My Site (click)</a></span></div>";

        echo "<button class='add_button' onclick=change_user_info(".$user['id'].") style='width:60%'>Изменить</button>";
        echo '</div>'; // user_info
		
			//$result = queryMySQL("SELECT * FROM users_projects WHERE user_id = '$user'");

/*			if ($result->num_rows) 
			{
				$row = $result->fetch_array(MYSQLI_ASSOC);
				echo stripslashes($row['text']).
									"<br style = 'clear : left;'><br>";
			}
*/			
    }

    function showMyProjects($user_id,$server_content_folder)
    {
        $result = queryMySQL("SELECT project_id FROM users_projects WHERE user_id = '$user_id' AND is_blocked = false");
        $num = $result->num_rows;
        echo "<div class='projects_block'>".
                "<div class='my_projects'><p>My Projects ($num)</p>".
                    "<div class='search'>".
                        "<img src='$server_content_folder/search_icon.png'>".
                        "<input type='text' onchange=search_project(this.value,'my')>".
                    "</div>".
								
                    "<div class='projects'>";
                    for ($i=1; $i <= $num ; $i++) 
                    { 
                        $row = $result->fetch_array(MYSQLI_ASSOC);
										
                        $out = $row['project_id'];
                        $out = queryMySQL("SELECT * FROM projects WHERE id = '$out'")->fetch_array(MYSQLI_ASSOC)['name'];
                        if ($out != NULL)
                        { 
                            $pid = $row['project_id'];
                            if ($pid == $_SESSION['executable_project_id'])
                            {
                                $class = 'executable';
                            }else $class = '';
											
                            echo "<div class='project_row $class'><p>$out</p>".
                                    "<div>".
                                        "<img src='$server_content_folder/enter_icon.png' onclick=enter_to_project('$pid')>".
                                        "<a href='../project/delete_project.php?project_id=$pid'>".
                                            "<img src='$server_content_folder/delete_icon.png'>".
                                        "</a>".
                                    "</div></div>";
                        }
                    }
        echo "</div><button class='add_button' onclick='create_project_form()'>New (+)</button></div>";//<a href='../project/create_project.php'>Создать проект</a>        

        echo "<div class='not_my_projects'><p>Not My Projects</p>".
                "<div class='search'>".
                    "<img src='$server_content_folder/search_icon.png'>".
                    "<input type='text' onchange=search_project(this.value,'not_my')>".
                "</div>".
                "<div class='projects'></div>".
                    "<button class='add_button' onclick='find_project()'>OTHER</button>".
                "</div></div>";
    }
 ?>
    </div>
</body>
<script src='../../js/change_theme.js'></script>	
<script src='../../js/profile.js'></script>	
<script src='../../js/screen_adaptation.js'></script>
<script src='../../js/AJAX/ajaxRequest.js'></script>
<script src='../../js/AJAX/findProject.js'></script>
<script src='../../js/AJAX/upload_msg.js'></script>
</html>