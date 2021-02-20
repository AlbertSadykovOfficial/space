<?php    
            session_start();

            $user_id            = $_SESSION['user_id'];
            $project_name       = $_SESSION['executable_project_name'];
            $project_id         = $_SESSION['executable_project_id'];
            $project_id_get     = $_GET['project_id'];

            $domain = 'http://space.com';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/user_menu.css">
    <link rel="stylesheet" href="http://space.com/css/config.css">
    <link rel="stylesheet" href="http://space.com/css/style.css">
    <link rel="stylesheet" href="http://space.com/css/project_settings.css">

<?php 
    if (isset($_COOKIE['theme']))
    echo "<link id='".$_COOKIE['theme']."_theme_css' rel = 'stylesheet' type = 'text/css' href = '../../css/light/project_settings_".$_COOKIE['theme'].".css'>"
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
    <header>
        <img  src="http://space.com/content/logo.png" onclick="show_user_menu();">
        <div class='user_menu hide'>

<?php 	 
            require_once("../templates/functions.php");

            echo 		"<a href='$domain/pages/profile/profile.php?view_id=$user_id'>Домой</a><br>".
                    "<a href='$domain/pages/project/project.php?project_id=$project_id'>Проект</a><br>".
                    "<a href='$domain/pages/authentication/logout.php'>Выйти</a><br>".
        "</div>".
    "</header>";

    $admin = queryMySQL("SELECT admin FROM projects WHERE id=$project_id AND admin=$user_id")->num_rows;

    if ($project_id !== $project_id_get) 
    {
        die("Access Denied!<br>You are not logged in to this project<br>Please, back to profile and Log in to the project<br><a href='$domain/pages/profile/profile.php?view_id=$user_id'>Back to profile</a>");
    }

    if ($admin) 
    {
        if (isset($_POST['changeProjectName'])) 
        {
            if ($_POST['changeProjectName'] != '') 
            {
                $project_id 		= $_GET['project_id'];
                $project_name 	= $_POST['changeProjectName'];
                changeThisProjectName($project_id,$project_name);
                $_POST['changeProjectName'] = null; 
                $_SESSION['executable_project_name'] = $project_name;
            }else
                echo "<script>alert('Поле Изменения Имени Пустое, Имя Не изменено')</script>";
            }

            if (isset($_POST['changeProjectPass1'])) 
            {
                if ($_POST['changeProjectPass1'] != '') 
                {
                    $project_id 	= $_GET['project_id'];
                    $project_pass1 	= $_POST['changeProjectPass1'];
                    $project_pass2 	= $_POST['changeProjectPass2'];
                    
                    if ($project_pass1 == $project_pass2) 
                    {
                        changeThisProjectPassword($project_id,$project_pass1);
                    } echo "<script>alert('Пароли не совпадают')</script>";
                }else echo "<script>alert('Поле Изменения Пароля Пустое, Пароль Не Изменен')</script>";
            }

            if (isset($_POST['delete_user_from_project']) && $_POST['delete_user_from_project'] != '') 
            {
                echo $_POST['delete_user_from_project']." $user_id";
                if ($_POST['delete_user_from_project'] == $user_id) 
                    echo "Вы не можете исключить себя";
                else
                    echo '';
                   # deleteUserFromProject($project_id, $_POST['delete_user_from_project']);
            }

            if (isset($_POST['exclude'])) 
            {
                deleteUsersFromProject($project_id,$user_id,$_POST['exclude'],'exclude');
            }
            if (isset($_POST['lock'])) 
            {
                deleteUsersFromProject($project_id,$user_id,$_POST['lock'],'lock');
            }
            if (isset($_POST['unlock']))
            {
                UnlockUserInTheProject($project_id, $_POST['unlock']);
            }

            if (isset($_POST['create_permission']))
            {
                $permission = $_POST['create_permission'];
                queryMySQL("INSERT INTO permission VALUES($project_id_get,'$permission')");
            }
            if (isset($_POST['delete_permission']))
            {
								queryMySQL("DELETE FROM permission WHERE project_id='$project_id_get'");
            }

            if (isset($_POST['create_permission']) || $key = queryMySQL("SELECT project_key FROM permission WHERE project_id='$project_id_get'")) 
            {
                if ($key->num_rows != 0)
                {
                    $key = $key->fetch_array(MYSQLI_ASSOC)['project_key'];

                    $permission =	"<div id='permission' style='background-color:rgb(255,186,53)'>".
                                    "<span style='color:black;'>Ключ:<br>$key</span><br>".
                                    "<button class='add_button' style='background-color:red' onclick='delete_permission($project_id_get)'>Закртыть Доступ</button>".
                                  "</div>";
                }
                else if (isset($_POST['create_permission']))
                {
								
                    $permission =	"<div id='permission' style='background-color:rgb(255,186,53)'>".
                                    "<span style='color:black;'>Ключ:<br>$permission</span><br>".
                                    "<button class='add_button' style='background-color:red' onclick='delete_permission($project_id_get)'>Закртыть Доступ</button>".
                                  "</div>";
                }
                else
                {
                    $permission =	"<div id='permission'>".
                                    "<button class='add_button' onclick='create_permission($project_id_get)'>Открыть Доступ</button>".
                                "</div>";
                }
            }

            echo "<div class='main'>".
                    "<div class='main_info'>".
                        "<p>MY PROJECT:</p>".
                        "<p>Name: $project_name</p>".
										
                        "<div id='change_this_project_name_id'>". 
                        "<button class='add_button' onclick='change_this_project_name($project_id_get)'>Изменить Название</button>".
                    "</div>".

                    "<div id='change_this_project_pass_id'>". 
                        "<button class='add_button' onclick='change_this_project_pass($project_id_get)'>Изменить Пароль</button>".
                    "</div>".

                    $permission.

                    "<div id='delete_this_project_id'>". 
                        "<a  href='delete_project.php?project_id=$project_id_get'><button class='delete_button'>УДАЛИТЬ</button></a>".
                    "</div>".

                "</div>";
								 

            $all_users = queryMySQL("SELECT user,id FROM members WHERE id IN (SELECT user_id FROM users_projects WHERE project_id=$project_id AND is_blocked = false)");
									$how = $all_users->num_rows;
            
            echo "<p>Все участники ($how):</p>";
            echo "<div class='participants'><ul>";

                for ($i=1; $i <= $how; $i++) 
                { 
                    $data = $all_users->fetch_array(MYSQLI_ASSOC);
                    $user_name = $data['user'];
                    $user_id	 = $data['id'];
                    echo "<li oncontextmenu=\"menu($user_id,'$user_name'); return false\" alt='$user_id'>$user_name (#$i)</li>";
                }

            echo "<ul></div>";

									
            $blocked = queryMySQL("SELECT user,id FROM members WHERE id IN (SELECT user_id FROM users_projects WHERE project_id=$project_id AND is_blocked = true)");
            $how = $blocked->num_rows;
            echo "<div class='participants blocked'><ul>";

            for ($i=1; $i <= $how; $i++) 
            { 
                $data = $blocked->fetch_array(MYSQLI_ASSOC);
                $user_name = $data['user'];
                $user_id	 = $data['id'];
                echo "<li oncontextmenu=\"unlock($user_id,'$user_name'); return false\" alt='$user_id' style='background-color:red'>$user_name (#$i)</li>";
            }

            echo "<ul></div>";
        echo "</div>";
        }
?>
</body>
<script type="text/javascript">

    function create_permission(project_id)
    {
        key = String(Math.floor(1000*Math.random()))+' '+String(Math.floor(1000*Math.random()));
        output = "<form method='POST' action='project_settings.php?project_id="+project_id+"' style='display:none'>"+
                    "<input type='text' name='create_permission' value='"+key+"'>"+
                    "<input id='create_permission_button' type='submit'>"+
                "</form>";
        document.getElementsByTagName('body')[0].insertAdjacentHTML('afterBegin',output);
        document.getElementById('create_permission_button').click();
    }

    function delete_permission(project_id)
    {
        output = "<form method='POST' action='project_settings.php?project_id="+project_id+"' style='display:none'>"+
                    "<input type='text' name='delete_permission' value='all'>"+
                    "<input id='delete_permission_button' type='submit'>"+
                "</form>";

        document.getElementsByTagName('body')[0].insertAdjacentHTML('afterBegin',output);
        document.getElementById('delete_permission_button').click();
    }

    function change_this_project_name(project_id)
    {
        output = "<form method='POST' action='project_settings.php?project_id="+project_id+"'>"+
                    "Название:<br>"+
                    "<input type='text' name='changeProjectName' 	placeholder='New Name'><br>"+
                    "<button class='add_button' type='submit'>Применить</button>"+
                "</form>";
        document.getElementById('change_this_project_name_id').innerHTML = output;			
    }
    
    function change_this_project_pass(project_id)
    {
        output = "<form method='POST' action='project_settings.php?project_id="+project_id+"'>"+
                    "Новый Пароль:<br>"+
                    "<input type='password' name='changeProjectPass1' placeholder='New Password'><br>"+
                    "<input type='password' name='changeProjectPass2' placeholder='Repeat New Password'><br>"+
                    "<button class='add_button' type='submit'>Применить</button>"+
                "</form>";
        document.getElementById('change_this_project_pass_id').innerHTML = output;			
    }

    function delete_user(project_id)
    {
        output ="<form method='POST' action='project_settings.php?project_id="+project_id+"'>"+
                    "#<input type='text' name='delete_user_from_project' placeholder='user id' style='width:60px;'>"+
                    "<input type='submit' value='удалить'>"+
                "</form>";
        document.getElementById('delete_user').innerHTML = output;
    }
    
    function menu(id,name)
    {
        if (document.getElementsByClassName('menu')[0] != null)
        {
            document.getElementsByClassName('menu')[0].remove();
        }

        content = "<div class='menu'>"+
                    "<button class='close_button' onclick=remove_from_remove_panel(this)><img src='http://space.com/content/close.png' alt='HIDE'></button>"+
                    "<span>#</span><input type='text' value='"+id+"'>"+
                    "<span>("+name+")</span>"+
                    "<button class='delete_button' onclick=\"push_to_exclude("+id+",'"+name+"')\">Исключить</button>"+
                    "<button class='delete_button' onclick=\"push_to_lock("+id+",'"+name+"')\" style='background-color:rgb(255,0,41)'>Заблокировать</button>"+
                  "</div>";
        document.getElementsByClassName('main')[0].insertAdjacentHTML('afterBegin',content);
        document.getElementsByClassName('menu')[0].style = 'margin-left:'+event.pageX+'px; '+'margin-top:'+event.pageY+'px';
    }

    function unlock(id,name)
    {
        if (document.getElementsByClassName('menu')[0] != null)
        {
            document.getElementsByClassName('menu')[0].remove();
        }

        content = "<div class='menu'>"+
                    "<button class='close_button' onclick=remove_from_remove_panel(this)><img src='http://space.com/content/close.png' alt='HIDE'></button>"+
                    "<span>("+name+")</span>"+
                    "<button class='delete_button' onclick=\"push_to_unclock("+id+")\" style='background-color:rgb(0,255,41)'>Вернуть в проект</button>"+
                "</div>";
        document.getElementsByClassName('main')[0].insertAdjacentHTML('afterBegin',content);
        document.getElementsByClassName('menu')[0].style = 'margin-left:'+event.pageX+'px; '+'margin-top:'+event.pageY+'px';
    }

    function push_to_exclude(id,name)
    {
        create_remove_panel();

        if(document.getElementById('remove_'+id) == null)
        {
            content = '<div id=\'remove_'+id+'\'>'+
                        '<span>'+name+'</span>'+
                        "<input type='text' name=exclude[] value=" + id + " hidden>"+
                        "<button class='close_button' onclick=remove_from_remove_panel(this)><img src='http://space.com/content/delete_icon.png' alt='HIDE'></button>"+
                      '</div>';
            document.getElementsByClassName('exclude')[0].children[1].insertAdjacentHTML('beforeEnd',content);
        }else
        {
            alert('Элемент уже содержится в стеке удаления');
        }
        document.getElementsByClassName('menu')[0].remove();
    }

    function push_to_lock(id,name)
    {
        create_remove_panel();

        if(document.getElementById('remove_'+id) == null)
        {
            content = '<div id=\'remove_'+id+'\'>'+
                        '<span>'+name+'</span>'+
                        '<input type=text name=lock[] value='+id+' hidden>'+
                        "<button class='close_button' onclick=remove_from_remove_panel(this)><img src='http://space.com/content/delete_icon.png' alt='HIDE'></button>"+
                        '</div>';
            document.getElementsByClassName('lock')[0].children[1].insertAdjacentHTML('beforeEnd',content);
        }
        else
        {
            alert('Элемент уже содержится в стеке удаления');
        }
        document.getElementsByClassName('menu')[0].remove();
    }
	</script>

	<script>
    function push_to_unclock(id,name)
    {
        content =	"<form method='POST' action='project_settings.php"+location.search+"' style='display:none'>"+
                    '<input type=text name=unlock value='+id+' hidden>'+
                    "<button id='unlock' style='width:40%;'></button>"+
                "</form>";
        document.getElementsByClassName('main')[0].insertAdjacentHTML('afterBegin',content);
        document.getElementById('unlock').click();
    }
    
    function create_remove_panel()
    {
        if (document.getElementsByClassName('remove_panel')[0] == null)
        {
            content = "<div class='remove_panel'>"+
                        "<span>Stack</span>"+
                        "<form method='POST' action='project_settings.php"+location.search+"'>"+
                            
                            "<div class='exclude'>"+
                                "<p>Исключить</p>"+
                                "<div>"+
                                "</div>"+
                            "</div>"+

                            "<div class='lock'>"+
                                "<p>Заблокировать</p>"+
                                "<div>"+
                                "</div>"+
                            "</div>"+

                            "<button type='submit' class='delete_button' style='width:40%;'>ПРИМЕНИТЬ</button>"+
                        "</form>"+
                    "</div>";

            document.getElementsByClassName('main')[0].insertAdjacentHTML('afterBegin',content);
        }
        else if(document.getElementsByClassName('remove_panel')[0].classList.contains('hide'))
        {
            document.getElementsByClassName('remove_panel')[0].classList.remove('hide');
        }
        document.getElementsByClassName('main_info')[0].classList.add('hide');
    }

    function remove_from_remove_panel(element)
    {
        element.parentNode.remove();
        if(document.getElementsByClassName('exclude')[0].children[1].children.length == 0 && document.getElementsByClassName('lock')[0].children[1].children.length == 0)
        {
            document.getElementsByClassName('remove_panel')[0].classList.add('hide');
            document.getElementsByClassName('main_info')[0].classList.remove('hide');
        }
    }
</script>
</html>