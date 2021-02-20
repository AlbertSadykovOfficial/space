		
    function change_profile_photo(id)
    {
        form = "<form method='POST' id='file_upload_form' action='' style='position:fixed; z-index:1002; text-align:center; margin-left:35%; margin-top:10%;' enctype='multipart/form-data'>"+
                "<div class='menu'>"+
                "<span>Выберите Фото</span><button class='close_button' onclick=getElementById('file_upload_form').remove() ><img src='"+server_content_folder+"/close.png'></button>"+
                "<input type='hidden' name='MAX_FILE_SIZE' value='104857600'>"+
                "<br>"+
                "<input type='file' name='user_photo'>"+
                "<button class='add_button' style='margin-left:0 !important;' type='submit'>LOAD</button>"+
                "</div>"+
                "</form>";

        document.getElementsByTagName('body')[0].insertAdjacentHTML('afterBegin',form);
    }	
		
    function enter_to_project(id,have_pass = 1)
    {
        if (document.getElementsByClassName('enter_window').length > 0)
            document.getElementsByClassName('enter_window')[0].remove();

        content = "<div class='window profile_window enter_window' style='z-index: 2000;'>"+
 									"<span>PROJECT</span><button class='close_button' onclick=\"document.getElementsByClassName(\'window\')[0].remove()\"><img src='"+server_content_folder+"/close.png'></button>"+
 									"<form method = 'POST' action='../project/project_in.php?project_id="+id+"'>";
        if (have_pass == 1)
        {
            content =	 content + "<span class='fieldname'><img src='../../content/pass_icon.png'></span>"+
				"<input type = 'password' maxlength='16' name='project_password' placeholder='PASSWORD'><br>";
        } else {
            content =	 content + "<span style='width:auto'>Свободный Доступ</span><input type='text' name='project_password_null' style='display:none' value=null><br>";
        }
        content =	 content + "<input type='submit' class='submit_button' value='Enter'>"+
 								'</form></div>';
        document.getElementsByTagName('body')[0].insertAdjacentHTML('afterBegin',content);
    }

    function show_user_menu()
    {
        if(document.getElementsByClassName('user_menu')[0].classList.contains('hide'))
            document.getElementsByClassName('user_menu')[0].classList.remove('hide');
        else
            document.getElementsByClassName('user_menu')[0].classList.add('hide');
    }	
	
    function change_user_info(id)
    {
        if (document.getElementsByClassName('user_info_window').length > 0)
            document.getElementsByClassName('user_info_window')[0].remove();
        elements = document.getElementsByClassName('user_info_string');
        content = "<div class='window user_info_window' style='z-index: 2000;'>"+
                    "<span>Info</span><button class='close_button' onclick=\"document.getElementsByClassName(\'window\')[0].remove()\"><img src='"+server_content_folder+"/close.png'></button>"+
                    "<form method = 'POST' action>";
        for (let i = 3; i < elements.length; i++)
        {
            content += "<input type=text"+
                            "  name='" + 			elements[i].dataset.column +
                            "' placeholder='"+elements[i].dataset.column;
            if (elements[i].dataset.column == 'href')
                content +=		"' value='"+			elements[i].children[1].children[0].getAttribute('href')+"'>";
            else
                content +=		"' value='"+			elements[i].children[1].innerHTML+"'>";
        }

        content =	 content + "<input type='text' name='id_change' value="+id+" style='display:none'>";
        content =	 content + "<input type='submit' name='change_info_btn' class='submit_button' value='Enter'>"+
 								'</form></div>';
        document.getElementsByTagName('body')[0].insertAdjacentHTML('afterBegin',content);
    }

    function search_project(value,type)
    {
        value = value.toLowerCase();
        if (type == 'my')
        {
            projects = document.getElementsByClassName('projects')[0];
        }
        else
        {
            projects = document.getElementsByClassName('projects')[1];
        }

        for (i=0; i < projects.children.length; i++)
        {
            projects.children[i].classList.remove('hide');
        }

        for (i=0; i < projects.children.length; i++)
        { 
            if (!(projects.children[i].children[0].innerHTML.toLowerCase()).includes(value))
            {
                projects.children[i].classList.add('hide');
            }
        }
    }

    function create_project_form()
    {
        content = "<div class='window profile_window'>"+

							 			"<span>Info</span>"+
							 			"<button class='close_button' onclick=\"document.getElementsByClassName(\'window\')[0].remove()\">"+
							 			"<img src='"+server_content_folder+"/close.png'>"+
							 			"</button>"+

		 								"<form method = 'POST' action='../project/create_project.php'>"+
											"<span class='fieldname'><img src='../../content/galaxy_icon.png'></span><input type = 'text' maxlength='16' name = 'project_name' 						placeholder='NAME'><br> "+
											"<span class='fieldname'><img src='../../content/pass_icon.png'></span>"+"SAVE MODE<br>"+
											"<div class=menu_controls id=''>"+
													"<button type='button' onclick=mode(1)>no password</button>"+
													"<button type='button' onclick=mode(2)>password</button>"+
													"<button type='button' onclick=mode(3)>only key</button>"+
									 		"</div>"+
									 		"<div class='pass_div'>"+
									 			"<input type = 'password' maxlength='16' name = 'project_password' placeholder='PASSWORD'><br>"+
												"<input type = 'password' maxlength='16' name = 'retry_project_password' placeholder='RETRY PASSWORD'><br>"+
											"</div>"+
											"<textarea name = 'about_project' placeholder='Write Something about your Project'></textarea>"+
		 									"<input class='submit_button' type='submit' value='CREATE'>"+
	 									'</form></div>';
        document.getElementsByTagName('body')[0].insertAdjacentHTML('afterBegin',content);
    }

    function mode(type)
    {
        if (type==1) 
        {
            document.getElementsByClassName('pass_div')[0].innerHTML = "Проект будет без пароля <input type='text' name='project_password_null' value=true style=display:none>'";
        }
        else if (type==2)
        {
            document.getElementsByClassName('pass_div')[0].innerHTML = 
            "<input type = 'password' maxlength='16' name = 'project_password' 			placeholder='PASSWORD'><br>"+
            "<input type = 'password' maxlength='16' name = 'retry_project_password'	placeholder='RETRY PASSWORD'><br>";
        }
        else if (type==3)
        {
            document.getElementsByClassName('pass_div')[0].innerHTML = 
            "<input type = 'text' maxlength='4'      name = 'only_key_access' value='true' style='display:none'>"+
            "<input type = 'password' maxlength='16' name = 'project_password' 			placeholder='PASSWORD'><br>"+
            "<input type = 'password' maxlength='16' name = 'retry_project_password'	placeholder='RETRY PASSWORD'><br>";
        }
    }

    function find_project()
    {
        content = "<div class='window profile_window' style='height:450px'>"+
                    "<span>FIND</span>"+
                    "<button class='close_button' onclick=\"document.getElementsByClassName(\'window\')[0].remove()\">"+
                    "<img src='"+server_content_folder+"/close.png'>"+
                    "</button>"+
		    						"<div class='menu_controls' >"+
                        "<button onclick=change_type(0)>Ключ</button>"+
                        "<button onclick=change_type(1)>Поиск</button>"+
                    "</div>"+
                    "<div id='menu_references'>"+
                    "</div>"+
                    "<div id='result_block' style='height:50%; overflow-y:scroll'>"+
                    "</div>"
                   '</div>';

        document.getElementsByTagName('body')[0].insertAdjacentHTML('afterBegin',content);
        change_type(0);
    }

    function find_in_db()
    { 
        if (document.getElementById('ref_type').value == 'search')
            findProjectByName(document.getElementById('ref').value,'result_block');
        else if (document.getElementById('ref_type').value == 'key')
            findProjectByKey(document.getElementById('ref').value,'result_block');
    }

    function change_type(mode)
    {
        if (!mode)
        {
            document.getElementsByClassName('menu_controls')[0].children[0].style.backgroundColor = 'black';
            document.getElementsByClassName('menu_controls')[0].children[1].style.backgroundColor = 'rgba(0,0,0,0)';
            content = "<p>Введите ключ к проекту</p>"+
                        "<input type='text' id='ref_type' value='key' style='display:none'>"+
                        "<div class='search' style='padding:0'>"+
                            "<img src='https://www.space.com/content/search_icon.png'><input type='text' id='ref' onchange='find_in_db()'>"+
                        "</div>";
										//"<input type='text' id='ref'>";
            document.getElementById('menu_references').innerHTML = content;
        }
        else
        {
            document.getElementsByClassName('menu_controls')[0].children[1].style.backgroundColor = 'black';
            document.getElementsByClassName('menu_controls')[0].children[0].style.backgroundColor = 'rgba(0,0,0,0)';
            content = "<p>НАЙТИ</p>"+
                        "<input type='text' id='ref_type' value='search' style='display:none'>"+
                        "<div class='search' style='padding:0'>"+
                            "<img src='https://www.space.com/content/search_icon.png' style='width:8%'><input type='text' id='ref' onchange='find_in_db()'>"+
                        "</div>"

            document.getElementById('menu_references').innerHTML = content;
        }
    }