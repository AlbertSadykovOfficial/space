/// !!! IMPORTANT
let drag_element = '';

let	last_note_id = 0;
let	last_checklist_id = 0;
let	last_image_id = 0;
let	last_video_id = 0;
let last_audio_id = 0;
let last_file_id  = 0;
let last_canvas_id= 0;

let has_something_changed = false;

GROUP = [];
/*

		В конце всего скрипта мы вызываем функцию: 
		show_data();  

		Для отображения Всех элементов доски после загрузки страницы
*/

/*


*/

transfer = 0;
TEMPL = '';
    function drag_start(ev)
    {		
        if (transfer == 0) 
        {
            handleDrop = function (e)
            {
            //console.log(e.dataTransfer.effectAllowed, e.dataTransfer.types, e.dataTransfer.getData('text/plain'));
                if (e.dataTransfer.types[1] !== 'Files') 
                {
                    write_drag(e.dataTransfer.getData('text/plain'));
                } 
                else
                {
                    alert('FILE');
                }

                transfer = 0;
                document.getElementById('drop_area').removeEventListener('drop',handleDrop,false);
            }

            document.getElementById('drop_area').classList.remove('hide');
            document.getElementById('drop_area').addEventListener('drop',handleDrop,false);
            document.body.addEventListener('keydown',function(e)
            {
                if (e.keyCode == 27)	document.body.removeEventListener('drop',handleDrop,false);
            }, true);

            transfer = 1;
        }
    }

    function stop_drag()
    {
        document.getElementById('drop_area').classList.add('hide');
    }

    function write_drag(val)
    {
        type = detect_type(val);
        if (type == 'note')
        {
            id = create_new_element(type,'viz_content');
            document.getElementById('add_element_menu').children[0].click();
            document.getElementById(id).children[1].value = val;
        }
        else
        {
            id = create_new_element(type,'viz_content');
            document.getElementById('ref').value = val;
            document.getElementById('add_element_menu').getElementsByClassName('add_button')[0].click();
            }
        }

    function detect_type(info)
    {
        if(info.includes('.jpg') || info.includes('.jpeg') || info.includes('.png') || info.includes('https://yandex.ru/images/'))	
            return 'image';

        if(get_meta(info) && !info.includes('video_'))				
            return 'video';

        if(info.includes('.mp3'))	
            return 'audio';
					
            return 'note';
    }


 /*

			Перемещение Элементов на доске

 */
	
    function drag_me(element)
    {
        has_something_changed = true;
		/// !!! IMPORTANT
        drag_element = element.parentNode.parentNode.getAttribute('id');
        X1 = to_number(document.getElementById(drag_element).style.marginLeft);
        X2 = X1 + to_number(document.getElementById(drag_element).style.width);
        Y1 = to_number(document.getElementById(drag_element).style.marginTop);
        Y2 = Y1 + to_number(document.getElementById(drag_element).style.height);//.slice(0,-2);
        z = Number(document.getElementById(drag_element).style.zIndex);
        console.log(drag_element);
        update_board_array();
        if (drag_element.includes('canvas'))
        {
            GROUP = [];
            for (i=0; i < DATA_ELEMENTS_ARRAY.length; i++)
            {
                if (to_number(DATA_ELEMENTS_ARRAY[i][1],0) > X1 && to_number(DATA_ELEMENTS_ARRAY[i][1],0) < X2
                  &&to_number(DATA_ELEMENTS_ARRAY[i][2],1) > Y1 && to_number(DATA_ELEMENTS_ARRAY[i][2],1) < Y2
                  && Number(DATA_ELEMENTS_ARRAY[i][5]) > z)
                {
                    console.log(DATA_ELEMENTS_ARRAY[i][0],to_number(DATA_ELEMENTS_ARRAY[i][1],0) - X1, to_number(DATA_ELEMENTS_ARRAY[i][2],1) - Y1);
                    GROUP.push([DATA_ELEMENTS_ARRAY[i][0],to_number(DATA_ELEMENTS_ARRAY[i][1],0) - X1, to_number(DATA_ELEMENTS_ARRAY[i][2],1) - Y1]);
                }
            }
        }
			//console.log(GROUP);
        document.getElementById(drag_element).classList.add('drag_target');
        document.getElementById('tool_bar').classList.add('hide');
        document.getElementById('board_map').classList.add('hide');
        document.getElementsByClassName('project_switch_panel')[0].classList.add('hide');
        document.getElementById('viz_content').style.overflowX = 'hidden';
        document.getElementById('viz_content').style.overflowY = 'hidden';

        height_of_top = document.getElementsByTagName('header')[0].offsetHeight; //document.getElementById('tool_bar').offsetHeight + 

        move_element = function()
        {
            scroll_x = document.getElementById('viz_content').scrollLeft;
            scroll_y = document.getElementById('viz_content').scrollTop ;
            x = event.pageX - 10 + scroll_x;
            y = event.pageY - height_of_top - 5 + scroll_y;
            
            if (x > 0 &&  y > 0)
            {
                document.getElementById(drag_element).style.marginLeft = x + 'px';
                document.getElementById(drag_element).style.marginTop  = y + 'px';
                
                for (i = 0; i < GROUP.length; i++)
                {
                    document.getElementById(GROUP[i][0]).style.marginLeft = x + GROUP[i][1] + 'px';
                    document.getElementById(GROUP[i][0]).style.marginTop  = y + GROUP[i][2] + 'px';
                }
            }
        }
				
        click_on_body = function ()
        {
            document.getElementsByClassName('slide_pannel')[0].classList.add('hide');
            document.getElementById(drag_element).classList.remove('drag_target');
            document.getElementById('tool_bar').classList.remove('hide');
            document.getElementById('board_map').classList.remove('hide');
            document.getElementsByClassName('project_switch_panel')[0].classList.remove('hide');

            document.getElementById('viz_content').style.overflowX = 'scroll';
            document.getElementById('viz_content').style.overflowY = 'scroll';
            document.body.removeEventListener('mousemove', move_element, false);
            document.body.removeEventListener('mouseup', click_on_body, false);

            GROUP = [];
        }

        document.body.addEventListener('mousemove',move_element); 
        document.body.addEventListener('mouseup',click_on_body); 

        document.getElementsByClassName('slide_pannel')[0].classList.remove('hide');

				// esc не отлавливается в полноэкранном режиме
        document.body.addEventListener('keydown',function(e)
        {
            if (e.keyCode == 27) 
            {
                document.body.removeEventListener('mouseup', click_on_body, false);
                document.body.removeEventListener('mousemove', move_element, false);
						//document.body.removeEventListener('keydown', move_element, false);
            }
				}, true);
    }

    function expand(direction)
    {
        element = document.getElementById('board_div').style;
        document.getElementById('slide_to_right').classList.remove('new_right_pannel');
        document.getElementById('slide_to_bottom').classList.remove('new_bottom_pannel');
        //position = document.getElementById('viz_content').scrollLeft; 
			
        if (direction == 'to_right')
        {
            position = Number(to_percent(document.getElementsByClassName('drag_target')[0].style.marginLeft,0).slice(0,-1))+20;
            
            if (Number(element.width.slice(0,-1)) < position)
            {
                element.width =  Number(element.width.slice(0,-1))+100+'%';
            }
					
            position = Number(to_percent(document.getElementsByClassName('drag_target')[0].style.marginLeft,0).slice(0,-1))+20+100;
            
            if (Number(element.width.slice(0,-1)) < position)
            {
                document.getElementById('slide_to_right').classList.add('new_right_pannel');
            }

            document.getElementById('viz_content').scrollLeft += Number(to_pixel('100%',0).slice(0,-2));
        }

        if (direction == 'to_bottom')
        {
            position = Number(to_percent(document.getElementsByClassName('drag_target')[0].style.marginTop,1).slice(0,-1))+20;

            if (element.height.slice(0,-1) < position)
            {
                element.height =  Number(element.height.slice(0,-1))+100+'%';
            }

            position = Number(to_percent(document.getElementsByClassName('drag_target')[0].style.marginTop,1).slice(0,-1))+20+100;

            if (Number(element.height.slice(0,-1)) < position)
            {
                document.getElementById('slide_to_bottom').classList.add('new_bottom_pannel');
            }

            document.getElementById('viz_content').scrollTop += to_pixel('100%',1).slice(0,-2);
        }

        if (direction == 'to_top') 
        {
            document.getElementById('viz_content').scrollTop -= to_pixel('100%',1).slice(0,-2);
        }

        if (direction == 'to_left') 
        {
            document.getElementById('viz_content').scrollLeft -= to_pixel('100%',0).slice(0,-2);
        }
    }

    function jump()
    {

        jump_to = function()
        {
            left_x = document.getElementById('board_map').offsetLeft;//Number(document.getElementById('board_map').style.marginLeft.slice(0,-1))*w/100;
            width = document.getElementById('board_map').offsetWidth;
            top_y = document.getElementById('board_map').offsetTop;
            height = document.getElementById('board_map').offsetHeight;

            // Расчет кординат прыжка
            // X =(event.pageX-left_x) - Находим координату Х относительно левого края, делая попраку на отступ панели от него
            // X/width - Находим положения курсора относительно ширины Панели (Пример: курсор относительно панели по оси X (300/1200 = 1/4))
            // Зная относительное положение курсора (1/4) и ширину нашей доски(board_width), можно найти куда прыгать: board_width*(1/4)
            jump_x = (event.pageX-left_x)/width;
            jump_y = (event.pageY-top_y)/height;

            // Задаем положения скролла по высчитанным координатам
            // Делаем поправку на скролл:
            // Вычитаем из Ширины доски 100%, как-бы отставая на значение 1 доски (особенности св-ва scroll)
            document.getElementById('viz_content').scrollLeft= jump_x * (to_pixel(document.getElementById('board_div').style.width,0).slice(0,-2)-to_pixel('100%',0).slice(0,-2));
            document.getElementById('viz_content').scrollTop = jump_y * (to_pixel(document.getElementById('board_div').style.height,1).slice(0,-2)-to_pixel('100%',1).slice(0,-2));
        }

        stop_jump = function ()
        {
            document.body.removeEventListener('mousemove', jump_to, false);
            document.body.removeEventListener('mouseup', stop_jump, false);
        }

        document.body.addEventListener('mousemove',jump_to); 
        document.body.addEventListener('mouseup',stop_jump); 
    }

/*

		Вспомогательные функции 
		Конвертация % в ПИКСЕЛИ
		Конвертация ПИКСЕЛЕЙ в %
		Получение Ссылки на иконку к переданному типу файла на сервере
		Поиск Мета информации YouTube Видео

*/

/// !!! АККУРАТНО ОШИБКИ (Т.к мы берем Высоту как высоту viz_content, а Ширину как ширину окна body)	
    function to_pixel(val,flag)
    {
        val = String(val);

        if (flag == 0)
        {
            if(val.includes('%'))
            {
                 return Math.floor((val.substr(0, val.length-1)*document.body.clientWidth))/100+'px';
            }
            else return val;
        }
        else
        {
            if(val.includes('%')) return Math.floor((val.substr(0, val.length-1)*document.getElementById('viz_content').offsetHeight))/100+'px';
            else 									return val;
        }
    }

    function to_percent(val,flag)
    {
        val = String(val);
			
        if(flag == 0)
        {		
            if(val.includes('px')) return Math.floor((100*val.substr(0, val.length-2)/document.body.clientWidth)) +'%';
            else 									 return val;
        }
        else
        {
            if(val.includes('px')) return Math.floor((100*val.substr(0, val.length-2)/document.getElementById('viz_content').offsetHeight)) +'%';
            else 									 return val;
        }
    }

    function to_number(val,flag)
    {
        val = String(val);

        if(flag == 0)
        {
            if(val.includes('%'))
            {
                 return Number(Math.floor((val.substr(0, val.length-1)*document.body.clientWidth))/100);
            }
            else return Number(val.slice(0,-2));
        }
        else
        {
            if(val.includes('%')) return Number(Math.floor((val.substr(0, val.length-1)*document.getElementById('viz_content').offsetHeight))/100);
            else 									return Number(val.slice(0,-2));
        }
    }
		
    function get_src_image(val)
    {
        if 		(val.includes('.doc'))	return  server_content_folder+'/icons/word.png';
        else if (val.includes('.xl')) 		return 	server_content_folder+'/icons/excel.png';
        else if (val.includes('.accdb'))	return 	server_content_folder+'/icons/access.png';
        else if (val.includes('.pp'))		return 	server_content_folder+'/icons/powerpoint.png';
        else if (val.includes('.vs'))		return 	server_content_folder+'/icons/visio.png';
        else 							    return 	server_content_folder+'/icons/file.png';
    }

    function get_meta(ref)
    {
        if (ref.includes('https://youtube.com/embed/'))
        {
            return ref.substr(26);
        }
        else if(ref.includes('https://youtu.be/')) 
        {
            return ref.substr(17);
        }
        else if(ref.includes('https://www.youtube.com/'))
        {

            first = ref.indexOf('v=')+2;										// Ищем id видео
            last	= ref.indexOf('&',ref.indexOf('v='));			// Проверяем, есть ли после id еще параметры?
		  	
            if (last == -1) 	last = ref.length - 1;				// Параметров нет - считываем все, что после "v="
            else 							last = last-first;						// Параметры есть - находим последний символ id видео

            return ref.substr(first,last);
        }
        else
        {
            return false;
        }
    }

/*

			Добавление Элемента в Массив Элементов

*/

    function add_element(type) 
    {
        if(document.getElementById('set')) document.getElementById('set').remove();

        x = document.body.clientWidth/3+'px';
        y = '50px';
        z = DATA_ELEMENTS_ARRAY.length+1;
        c = 'rgb(255,189,53)'; // yellow
			
        if(type != 'note' && type != 'checklist' && type != 'canvas') ref_type	= document.getElementById('ref_type').value;

        if (type == 'note')
        {
            w = "250px";
            k = 0.25; //h = "100px";
            meta = '';

            last_note_id++;
            id = type+'_'+last_note_id;
            name = id;
        }else if (type == 'checklist') 
        {
            w = "230px";
            k = 'auto';//h = "auto";
            meta = '';

            last_checklist_id++;
            id = type+'_'+last_checklist_id;
            name = id;
        } else if (type == 'canvas')
        {
            w = "800px";
            k = 0.5;//h = "450px";
            meta = '';

            last_canvas_id++;
            id= type+'_'+last_canvas_id;
            name = id;
        }else if (type == 'image')
        {
            w = "200px";
            k = 1;//h = "200px";
            if 			(ref_type == 'internet')  	meta 	= document.getElementById('ref').value;
            else if (ref_type == 'self') 				meta = server_project_folder+'/'+document.getElementById('ref_dir').value+document.getElementById('ref').value;//meta 	= '../../images/'+ document.getElementById('ref').value;
		  	
            last_image_id++;
            id = type+'_'+last_image_id;
            name = document.getElementById('ref').value;
        }else if (type == 'video')
        {
            w = "320px";
            k = 0.6;//h = "180px";

            if (ref_type == 'internet')
            {
                meta = get_meta((document.getElementById('ref').value).trim());
                
                if (meta === false)
                {
                    alert('Видео должно быть с YouTube\nПроверьте Ссылку');
                    last_video_id--;
                    return 0;
                }
                meta = "https://youtube.com/embed/" + meta;
            }
            else if (ref_type == 'self')  
            {
                meta = server_project_folder+'/'+document.getElementById('ref_dir').value+document.getElementById('ref').value;
            //meta = '../../images/tmp/' + document.getElementById('ref').value;
            }

            last_video_id++;
            id = type+'_'+last_video_id;

				    name = document.getElementById('ref').value;
        }else if(type == 'audio')
        {
            w = "250px";
            k = 'auto';//h = "auto";
            if 			(ref_type == 'internet')  	meta = document.getElementById('ref').value;
            else if (ref_type == 'self') 			meta = server_project_folder+'/'+document.getElementById('ref_dir').value+document.getElementById('ref').value;
		
            last_audio_id++;
            id = type+'_'+last_audio_id;
            name = document.getElementById('ref').value;
        }
        else if (type == 'file')
        {
            w = '5%';
            k = 'auto';
            c = 'rgb(0,0,0,0)'; 
            if 			(ref_type == 'internet')  	meta 	= document.getElementById('ref').value;
            else if (ref_type == 'self')  			meta = server_project_folder+'/'+document.getElementById('ref_dir').value+document.getElementById('ref').value; //meta 	= '../../images/tmp/' + document.getElementById('ref').value;

            last_file_id++;
            id = type+'_'+last_file_id;

            name = document.getElementById('menu_references').children[2].value;
        }

        document.getElementById('add_element_menu').remove();

        has_something_changed = true;
        DATA_ELEMENTS_ARRAY.push([id, x, y, w, k, z, c, name, meta, '']);
        console.log(DATA_ELEMENTS_ARRAY[DATA_ELEMENTS_ARRAY.length-1]);
        build_element(DATA_ELEMENTS_ARRAY.length-1);
        return id;
    } 

/*

			Окно добавлении элемента

*/

    function create_new_element(type, place)
    {
        if (type == 'image'){
            descr_1 = 'Интернет';
            descr_2 = 'Фото';
        }else if (type == 'file'){
            descr_1 = 'Интернет';
            descr_2 = 'Файл';
        }else if (type == 'video'){
            descr_1 = 'YouTube';
            descr_2 = 'Видео';
        }else if (type == 'audio'){
            descr_1 = 'Интернет';
            descr_2 = 'Аудио'; 
        }

        if (document.getElementById('add_element_menu') != null) document.getElementById('add_element_menu').remove();
        if (document.getElementById('set') != null) document.getElementById('set').remove();

        if (type == 'note')
        {
            content = "<div id='add_element_menu' class='add_element_menu menu_controls'>"+
                        "<button onclick=add_element('note')>Заметка</button>"+
                        "<button onclick=add_element('checklist')>Список</button>"+
                        "<button onclick=add_element('canvas')>Холст</button>"+
                    "</div>";
        }else{
            content = "<div id='add_element_menu' class='add_element_menu' >"+
                        "<span>CREATE ELEMENT</span><button class='close_button' onclick=\"document.getElementById('add_element_menu').remove()\"><img src='"+server_content_folder+"/close.png'></button>"+
                        "<div class='menu_controls' >"+
                            "<button onclick=change_type(\'"+descr_1+"\',\'"+descr_2+"\',0)>Интернет</button>"+
                            "<button onclick=change_type(\'"+descr_1+"\',\'"+descr_2+"\',1)>Сервер</button>"+
                        "</div>"+
                        "<div id='menu_references'>"+
                            "<p>Введите "+descr_1+" ссылку на "+descr_2+"</p>"+
                            "<input type='text' id='ref_type' value='internet' style='display:none'>"+
                            "<input type='text' id='ref'>"+
                        "</div>"+
                        "<button class='add_button' style='width:70%; margin-left:0;' onclick=add_element(\'"+type+"\')>Создать</button>"+
                      "</div>";
        }
        document.getElementById(place).insertAdjacentHTML('beforeBegin',content);
    }

// Изменить ресурс Загрузки элемента  (Интернет/Сервер)
    function change_type(res,type,mode)
    {
        if (!mode)
        {
            document.getElementsByClassName('menu_controls')[0].children[0].style.backgroundColor = 'rgb(22,22,22)';
            document.getElementsByClassName('menu_controls')[0].children[1].style.backgroundColor = 'rgba(0,0,0,0)';
            if (res == 'YouTube') res = '<span style=color:red>YouTube</span>';
            content = "<p>Введите "+res+" ссылку на "+type+"</p>"+
                        "<input type='text' id='ref_type' value='internet' style='display:none'>"+
                        "<input type='text' id='ref'>";
            document.getElementById('menu_references').innerHTML = content;
        }
        else
        {
            document.getElementsByClassName('menu_controls')[0].children[1].style.backgroundColor = 'rgb(22,22,22)';
            document.getElementsByClassName('menu_controls')[0].children[0].style.backgroundColor = 'rgba(0,0,0,0)';
            content = "<p>Выберите файл</p>"+
                        "<input type='text' id='ref_type' value='self' style='display:none'>"+
                        "<input type='text' id='ref_dir' style='display:none'>"+
                        "<input type='text' id='ref'>"+my_files[my_files.length-1];

            document.getElementById('menu_references').innerHTML = content;
            if 		(type == 'Фото')  open_folder(1,'image');
            else if (type == 'Видео') open_folder(2,'multimedia');
            else if (type == 'Аудио') open_folder(2,'multimedia');
        }
    }

/*

			Обновить Данные Массива Элементов

*/

    function update_board_array()
    {
        for (i = 0; i < DATA_ELEMENTS_ARRAY.length; i++)
        {
            id = DATA_ELEMENTS_ARRAY[i][0];
            element = document.getElementById(id);
            DATA_ELEMENTS_ARRAY[i][1] = to_percent(element.style.marginLeft,0);
            DATA_ELEMENTS_ARRAY[i][2] = to_percent(element.style.marginTop,1);
            DATA_ELEMENTS_ARRAY[i][3] = to_percent(element.style.width,0);
            if (element.style.height == 'auto') 
                DATA_ELEMENTS_ARRAY[i][4] = 'auto';
            else 
                DATA_ELEMENTS_ARRAY[i][4] = to_number(element.style.height,1)/to_number(element.style.width,0);
//				console.log(DATA_ELEMENTS_ARRAY[i][4]);
            DATA_ELEMENTS_ARRAY[i][5] = element.style.zIndex;
            DATA_ELEMENTS_ARRAY[i][6] = element.style.backgroundColor;
            DATA_ELEMENTS_ARRAY[i][7] = element.children[0].children[1].value;

            if 			(id.includes('note')) 	DATA_ELEMENTS_ARRAY[i][8] = (element.children[1].value).split("\n").join("\\n");
            else if (id.includes('image'))	DATA_ELEMENTS_ARRAY[i][8] = element.children[1].src;
            else if (id.includes('video'))	
            {
                if (element.getElementsByTagName('iframe').length != 0) 
                DATA_ELEMENTS_ARRAY[i][8] = element.children[1].dataset.meta;
            }else if (id.includes('checklist'))
            {
                let checklist='';
                for (j = 0; j < parseInt(element.children[1].children.length/2); j++)
                {
                    if (element.children[1].children[j*2].checked)
                    {
                        checklist += '{1,';
                    }else checklist += '{0,';

                    checklist += element.children[1].children[j*2+1].value.split("}").join(")") +'}';
                }
                DATA_ELEMENTS_ARRAY[i][8] = checklist;
            }

            if (element.classList.contains('hide'))
				    DATA_ELEMENTS_ARRAY[i][9] = 'hide';
            else
            DATA_ELEMENTS_ARRAY[i][9] = '';

            DATA_ELEMENTS_ARRAY[i][10] = element.dataset.deadline;
//			console.log(DATA_ELEMENTS_ARRAY[i]);
        }
    }

/*

			Обновить Данные Элементов 
			Подготовить JSON массив для синхронизации с сервером

*/
		
    function board_synchronization(this_id)
    {
        if (has_something_changed)
        {
            update_board_array();
            delete_useless_elems(); // project_board_canvas.js

            json_board  = String(JSON.stringify(DATA_ELEMENTS_ARRAY)).replace('&','(!AMP!)');
            json_canvas = String(JSON.stringify(CANVAS_ELEMENTS));
            console.log(this_id, json_board, json_canvas);
					
            args = 'project_id=' + this_id + '&' + "data_board=" + json_board + "&" + "data_cnvs=" + json_canvas;
            ajaxRequest("POST", 'http://space.com/js/AJAX/board_synchronization.php', args, function(){});
				
            has_something_changed = false;
        }
    }

/*

			Создать Элемент из Массива Элементов

*/	
		// Добавить Чек-Элемент в Список Дел

    function add_list(elem)
    {
        has_something_changed = true;

        color = elem.parentNode.parentNode.style.backgroundColor;
        elem.parentNode.parentNode.style.height = 'auto';
        elem.insertAdjacentHTML('beforeBegin',"<input type='checkbox' style='width:5%; background-color:"+color+"'><input type='text' value='new' style='width:90%; background-color:"+color+"''>");
    }

    function build_element(num)
    {
			//console.log(DATA_ELEMENTS_ARRAY[num]);
        id 		= DATA_ELEMENTS_ARRAY[num][0];
        x  		= 'margin-left:'			+ to_pixel(DATA_ELEMENTS_ARRAY[num][1],0) +'; ';
        y  		= 'margin-top:' 			+ to_pixel(DATA_ELEMENTS_ARRAY[num][2],1) +'; ';
        w  		= 'width:'						+ to_pixel(DATA_ELEMENTS_ARRAY[num][3],0) +'; ';
        if (DATA_ELEMENTS_ARRAY[num][4] == 'auto')
        h  		= 'height:auto; ';
        else
        h  		= 'height:' 					+ DATA_ELEMENTS_ARRAY[num][4]*
                                    to_number(to_pixel(DATA_ELEMENTS_ARRAY[num][3],0),0)+'px; ';
        z 		=	'z-index:'					+ 				 DATA_ELEMENTS_ARRAY[num][5]		+'; ';
        c 		=	'background-color:'	+ 				 DATA_ELEMENTS_ARRAY[num][6] 		+'; ';
        name	= DATA_ELEMENTS_ARRAY[num][7];
        meta	= DATA_ELEMENTS_ARRAY[num][8];   meta = meta.replace('(!AMP!)','&');
        cls 	= DATA_ELEMENTS_ARRAY[num][9];
        ddlin = DATA_ELEMENTS_ARRAY[num][10]


        type = id.substr(0, id.indexOf('_'));
        content = "<div id='"+id+"'"+
                    " class='_content my_"+type+"'"+
                    " style='"+x+y+w+h+z+c+"'"+
                    " data-deadline='"+ddlin+"'>"+
                    "<div oncontextmenu='show_settings(this);return false' >";

        if (type == 'file') 
        {
            src_image = get_src_image(meta);
            content += "<img onmousedown='drag_me(this); return false'"+"src='"+src_image+"' style='display:block;' draggable='false' alt='"+name+"'>"+
	  									    "<input value='"+name+"'>";			

        } else {
            content += "<img draggable='false'"+
                            "style='width:7%; max-width:30px; margin-bottom:-1%; margin-left:-1%;'"+
                            "src='"+server_content_folder+"/icons/pin.png'"+
                            "onmousedown='drag_me(this); return false;'>"+

                        "<input type='text' value='"+name+"' style='"+c+"'>"+
                        "<button onclick=remove_element(this)><img src='"+server_content_folder+"/close.png'></button>"+
                        "<button onclick=minimize_element('"+id+"')><img src='"+server_content_folder+"/minimize.png'></button>"; 
										//										//"<button onclick=remove_element(this); style='float:right;width:9%;'>X</button>" 		
        }
        content +=	"</div>";

        if 			(id.includes('note')) {	content += "<textarea style='width:100%;height:100%;"+c+"'>"+meta+"</textarea></div>"; last_note_id = id.substr(id.indexOf('_')+1);}
        else if (id.includes('image')){	content += "<img src='"+meta+"' alt='!!! ОЙ,Что-то Не так. Проверьте Интернет или правильность ссылки !!!'></div>";	last_image_id = id.substr(id.indexOf('_')+1);}
        else if (id.includes('file')){  content += "<a href='"+meta+"' download>"+meta+"</a>"; last_file_id = id.substr(id.indexOf('_')+1);}
        else if (id.includes('video'))
        {	
            if(meta.includes('https://youtube.com/embed/'))
                content += "<iframe width='auto' height='auto' src='"+meta+"' frameborder='0' allowfullscreen data-meta="+meta+"></iframe>";
            else 																												// АККУРАТНО С poster - не работает на APPLE
                content += "<div>"+
                                "<video style='width:100%;' preload='none' controls>"+
                                    "<source src='"+meta+"' type='video/mp4' >"+
                                "</video>"+
                            "</div>";

            last_video_id = id.substr(id.indexOf('_')+1);
        }
        else if(id.includes('audio'))
        {
            content += "<div>"+
                            "<audio style='width:100%;' preload='none' controls><source src='"+meta+"' type='audio/mp3' >"+
                            "</audio>"+
                        "</div>";
            last_audio_id = id.substr(id.indexOf('_')+1);
        }else if(id.includes('checklist'))
        {
            content+='<div>';
            first_index = 0;
            last_index = 0;
            
            while (last_index < meta.length)
            {
                first_index = meta.indexOf('{0,',last_index);
                if (first_index == -1 && meta.indexOf('{1,',last_index) == -1) 
                {
                    break;
                }else if (first_index == -1 || (meta.indexOf('{1,',last_index) != -1 && meta.indexOf('{1,',last_index) < first_index))
                {
                    first_index = meta.indexOf('{1,',last_index);
                    last_index = meta.indexOf('}',first_index);
                    content += "<input class='checkbox' type='checkbox' style='width:5%' checked><input type='text' value='"+meta.substring(first_index+3,last_index)+"' style='width:90%; "+c+"'>";
                }else{
                    last_index = meta.indexOf('}',first_index);
                    content += "<input class='checkbox' type='checkbox' style='width:5%' ><input type='text' value='"+meta.substring(first_index+3,last_index)+"' style='width:90%; "+c+"'>";
                }
					//console.log(last_index);
            }
            last_checklist_id = id.substr(id.indexOf('_')+1);
            content+='<button onclick=add_list(this) style=\'width: 100%; max-width:100%\'>+</button></div>';
        }
        else if (id.includes('canvas'))
        {	//width='320' height='240'
            last_canvas_id = id.substr(id.indexOf('_')+1);
            content += "<canvas width=1200px height=700px onmousedown=draw(this.parentNode.id) ondblclick=draw_text(this.parentNode.id) oncontextmenu=\"choose_type(this.parentNode.id); return false\"></canvas>";
        }
				
        content += '</div>';	
        document.getElementById('board_div').insertAdjacentHTML('beforeEnd',content);
/// Если у элеента присутстыует класс hide, то свернуть его
        if (cls == 'hide') minimize_element(id);
    }

/*

			Отобразить Все Элементы Доски этого раздела проекта

*/

    function show_data()
    {
        width  = 100;
        height = 100;
        //DATA_ELEMENTS_ARRAY = JSON.parse(board_data);
        for (i = 0 ; i < DATA_ELEMENTS_ARRAY.length; i++) 
        {
            if (Number(DATA_ELEMENTS_ARRAY[i][1].slice(0,-1)) > width)  width  = Number(DATA_ELEMENTS_ARRAY[i][1].slice(0,-1));
            if (Number(DATA_ELEMENTS_ARRAY[i][2].slice(0,-1)) > height) height = Number(DATA_ELEMENTS_ARRAY[i][2].slice(0,-1));
            build_element(i);
        }
        document.getElementById('board_div').style.width  = (Math.floor(width/100)+1)*100 + '%';
        document.getElementById('board_div').style.height = (Math.floor(height/100)+1)*100 +'%';
    }

/*

			Удалить Элемент с Доски и из Массива Элементов 

*/

    function remove_element(elem)
    {
        has_something_changed = true;

        if (typeof(elem) == 'object')
        {		
            id = elem.parentNode.parentNode.getAttribute('id');
        }
        else if (typeof(elem) == 'string') 		
        {
            id = elem;
            if(document.getElementById('set') != null) document.getElementById('set').remove();
        }
		
        indx = document.getElementById(id).style.zIndex;
        console.log(id, indx);

		if (id.includes('minimized_apps')) 
        document.getElementById(id).classList.add('hide');
        else
        document.getElementById(id).remove();	

        if (id.includes('note') || id.includes('image') || id.includes('video')  || id.includes('file') || id.includes('audio') || id.includes('checklist') || id.includes('canvas')) 
        {

            if 		(id.includes('note')){			last_note_id--; 			type = 'note_';}
            else if (id.includes('checklist')){     last_checklist_id--;        type = 'checklist_';}
            else if (id.includes('image')){			last_image_id--;			type = 'image_';}
            else if (id.includes('video')){			last_video_id--;			type = 'video_';}
            else if (id.includes('file')){			last_file_id--; 			type = 'file_';}
            else if (id.includes('audio')){			last_audio_id--; 			type = 'audio_';}
            else if (id.includes('canvas')){		last_canvas_id--; 		type = 'canvas_'; 
			CANVAS_ELEMENTS = CANVAS_ELEMENTS.filter(n => !n[0].includes(id));
																				}

            DATA_ELEMENTS_ARRAY = DATA_ELEMENTS_ARRAY.filter(n => !n[0].includes(id));


            for (i = 0; i < DATA_ELEMENTS_ARRAY.length; i++)
            {
                val = DATA_ELEMENTS_ARRAY[i][0];
                    // Убавлям z-index у всех элементов, у которых z-ind больше Удаляемого эл-та
                    if (Number(document.getElementById(val).style.zIndex) > Number(indx))
                    {
                        console.log(val,document.getElementById(val).style.zIndex, DATA_ELEMENTS_ARRAY[i][5]);
                        document.getElementById(val).style.zIndex = Number(document.getElementById(val).style.zIndex) - 1;
                        DATA_ELEMENTS_ARRAY[i][5]	 = document.getElementById(val).style.zIndex;
                    }
            }

            for (i = 0; i < DATA_ELEMENTS_ARRAY.length; i++)
            {
                val = DATA_ELEMENTS_ARRAY[i][0];
                    // Убавляем суффикс типа элемента, если он больше удаляемого (note_2 -> note_1) 
                    if (val.includes(type) && Number(val.substr(val.indexOf('_')+1))>Number(id.substr(id.indexOf('_')+1)) )
                    {
                        document.getElementById(val).id = type + String(Number(val.substr(val.indexOf('_')+1))-1);
                        DATA_ELEMENTS_ARRAY[i][0] 		= type + String(Number(val.substr(val.indexOf('_')+1))-1);
                    }
            }
        }
        console.log(id);

    }

/*

			Свернуть/Развернуть элемент

*/
    function minimized_apps_panel()
    {
        if (document.getElementById('minimized_apps').classList.contains('hide')) 
        {	
        document.getElementById('minimized_apps').classList.remove('hide');
        }else{
        document.getElementById('minimized_apps').classList.add('hide');
        }
    }

    function minimize_element(element_id, group='self')
    {
        // Если уже спрятан, то не прятать, иначе если пятать группу, он продублируется.
        if (document.getElementById(element_id).classList.contains('hide')) return false;

        has_something_changed = true;
        element = document.getElementById(element_id);

        element.classList.add('hide');

        // Если это canvas-(элемент группирования), указать это в Мета (Пример : 'group')
        // Если элемент свернут через элемент группирования (canvas), указываем id группы и прячем. (Пример : 'canvas_2')
        // Если элемент свернули напрямую, указываем, что он - одиночка. (Пример : 'self')
        if (element_id.includes('canvas')) 
            content = '<span style=\'cursor:pointer\' onclick=\"expand_element(this,\''+element_id+'\')\" data-meta=\'group\'>'+element.children[0].children[1].value+'</span>';
        else if (group != 'self')
            content = '<span style=\'cursor:pointer; display:none;\' onclick=\"expand_element(this,\''+element_id+'\')\" data-meta='+group+'>'+element.children[0].children[1].value+'</span>';
        else
            content = '<span style=\'cursor:pointer\' onclick=\"expand_element(this,\''+element_id+'\')\" data-meta='+group+'>'+element.children[0].children[1].value+'</span>';
			
        document.getElementById('minimized_apps').insertAdjacentHTML('beforeEnd',content);

        if (element_id.includes('canvas')) minimize_group(element_id);
    }

    function minimize_group(element_id)
    {
        X1 = to_number(document.getElementById(element_id).style.marginLeft);//.slice(0,-2);
        X2 = X1 + to_number(document.getElementById(element_id).style.width);//.slice(0,-2)
        Y1 = to_number(document.getElementById(element_id).style.marginTop);//.slice(0,-2);
        Y2 = Y1 + to_number(document.getElementById(element_id).style.height);//.slice(0,-2);
        z = Number(document.getElementById(element_id).style.zIndex);

        for (i=0; i < DATA_ELEMENTS_ARRAY.length; i++)
        {
            if (DATA_ELEMENTS_ARRAY[i][0] != element_id 
              && to_number(DATA_ELEMENTS_ARRAY[i][1],0) > X1 && to_number(DATA_ELEMENTS_ARRAY[i][1],0) < X2
              && to_number(DATA_ELEMENTS_ARRAY[i][2],1) > Y1 && to_number(DATA_ELEMENTS_ARRAY[i][2],1) < Y2
              && Number(DATA_ELEMENTS_ARRAY[i][5]) > z)
            {
                minimize_element(DATA_ELEMENTS_ARRAY[i][0],element_id);
            }
        }
    }

    function expand_element(stack,element_id)
    {
        has_something_changed = true;
        document.getElementById(element_id).classList.remove('hide');

        if (stack.dataset.meta == 'group')
        expand_group(element_id);
			
        stack.remove();
    }

    function expand_group(element_id)
    {
        elements = document.getElementById('minimized_apps');

        for (i=elements.children.length-1;i>=0;i--)
        {
            if (elements.children[i].dataset.meta == element_id)
            {
                elements.children[i].click();
            }
        }
    }

/*

			Окно Настройки Параметров Элемента

*/

    function show_settings(element)
    {
        element = document.getElementById(element.parentNode.id);

        if(document.getElementById('set') 		!= null) document.getElementById('set').remove();
        if(document.getElementById('add_element_menu') != null) document.getElementById('add_element_menu').remove();
        content = "<div id='set'>"+
                    "<div><span>Парметры</span><button class='close_button' onclick=remove_element(this); style='float:right'><img src='"+server_content_folder+"/close.png'></button></div>";
			
        content+="<span>Deadline:</span>"+
                    "<div id='deadline_info'>"+
                        "<input type='date' value='"+(element.dataset.deadline).substr(0,10)+"'>"+
                        "<input type='time' value='"+(element.dataset.deadline).substr(-5)+"'></span>"+
                    "</div>";
        if (!element.id.includes('file')) 
        {
            content+="<span>Color:</span><br>"+
                    "<div class='radio_color'>"+
                        "<div class='form_radio_group-item' style='background-color:rgb(234,226,183)'>"+
                        "<input type='radio' name='a' id='color_1' value='rgb(234,226,183)'/>"+
										    "<div></div>"+
                    "</div>"+

                    "<div class='form_radio_group-item' style='background-color:rgb(214,40,40)'>"+
                        "<input type='radio' name='a' id='color_2' value='rgb(214,40,40)'/>"+
                        "<div></div>"+
                    "</div>"+

                    "<div class='form_radio_group-item' style='background-color:rgb(255,186,53)'>"+
                        "<input type='radio' name='a' id='color_3' value='rgb(255,186,53)' checked/>"+
                        "<div></div>"+
                    "</div>"+

                    "<div class='form_radio_group-item' style='background-color:rgb(19,111,99)'>"+
                        "<input type='radio' name='a' id='color_4' value='rgb(19,111,99)'/>"+
                        "<div></div>"+
                    "</div>"+
                    
                    "<div class='form_radio_group-item' style='background-color:rgb(67,170,139)'>"+
                        "<input type='radio' name='a' id='color_5' value='rgb(67,170,139)'/>"+
                        "<div></div>"+
                    "</div>"+

                    "<div class='form_radio_group-item' style='background-color:rgb(51,101,138)'>"+
                        "<input type='radio' name='a' id='color_6' value='rgb(51,101,138)'/>"+
                        "<div></div>"+
                    "</div>"+

                    "<div class='form_radio_group-item' style='background-color:rgb(0, 48, 73)'>"+
                        "<input type='radio' name='a' id='color_7' value='rgb(0, 48, 73)'/>"+
                        "<div></div>"+
                    "</div>"+

								"</div>";
        }
        else
        {
            content+= "<button style='background-color:rgb(255,186,53);'><a href='"+element.children[1].innerHTML+"' download>Скачать Файл</a></button>"+
                      "<button style='background-color:rgb(255,186,53);color:rgb(33,33,33)' onclick=minimize_element('"+element.id+"')>Свернуть</button>"+
                      "<button style='background-color:rgb(205,37,64);color:rgb(33,33,33)' onclick=remove_element(\'"+element.id+"\')>Убрать с доски</button>"+	
                      "<br><span>Размер:</span><br>"+
                      "<input id='div_size' type='range' min='2' max='10' step='1' value='"+(element.style.width).slice(0, -1)+"'><br>";
        }
				
        content+= "<span>Размер Шрифта:</span><br>"+
                  "<input id='font_size' type='range' min='8' max='24' step='2' value='"+(element.style.fontSize).slice(0, -2)+"'>"+
                  "<button onclick=zFront(\'"+element.id+"\')>Наверх</button><button onclick=zUp(\'"+element.id+"\')>Выше</button>"+
                  "<button onclick=zDown(\'"+element.id+"\')>Ниже</button><button onclick=zEnd(\'"+element.id+"\')>Вниз</button>";

        if(element.id.includes('image')) 
        { 
            //if(!element.children[1].children[0].children[0].src.includes(server_project_folder))
            //if(get_meta(element.children[1].src) !== false)
            content = content + "<span>Ссылка:</span><br><input id='ref' type='text' value='"+element.children[1].src+"'>";
        }

        if (element.id.includes('video')) 
        {
            if(element.querySelector('iframe') != null)
            if(get_meta(element.children[1].src) !== false)
            {
                content = content + "<span>Ссылка:</span><br><input id='ref' type='text' value='"+element.children[1].src+"'>";
            }
        }

        content = content +	"<button class='add_button' style='width:70%;' onclick=set_parametrs(\'"+element.id+"\','"+element.children[1].src+"\')>APPLY</button>"+
										"</div>";
        document.getElementById('viz_content').insertAdjacentHTML('afterBegin',content);
    }


/*

			Установка Параметров Элемента, введенных в Окне Настройки

*/


    function set_parametrs(id)
    {
        has_something_changed = true;

        element = document.getElementById(id);

        element.dataset.deadline = document.getElementById('deadline_info').children[0].value+' '+
																 document.getElementById('deadline_info').children[1].value;
        if (!id.includes('file'))
        {
            for (i = 1; i <= 7; i++) 
            {
                if (document.getElementById('color_'+i).checked) 
                {
                    element.style.backgroundColor = document.getElementById('color_'+i).value;
                    element.children[0].children[0].style.backgroundColor = document.getElementById('color_'+i).value;
                    element.children[0].children[1].style.backgroundColor = document.getElementById('color_'+i).value;
                    element.children[1].style.backgroundColor = document.getElementById('color_'+i).value;
                    
                    if(id.includes('checklist'))
                    {
                        elements = document.getElementById(id).children[1].getElementsByTagName('input');
                        for (j=0; j < elements.length; j++)
                        {
                            elements[j].style.backgroundColor =document.getElementById('color_'+i).value;
                        }
                    }
                    break;
                }
            }
        }
        else
        {
            element.style.width = document.getElementById('div_size').value +'%';
        }
        if (id.includes('image')) element.children[1].src = document.getElementById('ref').value; 
        if (id.includes('video'))
        {  
            if (element.children[1].children[0] == null) 
            {
            //if (!document.getElementById(id).children[1].children[0].children[0].src.includes(server_project_folder))
            //{
                meta = get_meta(document.getElementById('ref').value);
						    address = 'https://youtube.com/embed/' + meta;

                if (meta === false)
                {	
                    alert('Видео должно быть с YouTube\nПроверьте Ссылку');
                    meta='';
                    element.style.fontSize = document.getElementById('font_size').value +'px';
                    document.getElementById('set').remove();
                    return 0;
                }
                else{
                    element.children[1].src = address;
                    element.children[1].dataset.meta = address;
                }
					//}
            }
        }

        element.style.fontSize = document.getElementById('font_size').value +'px';
        document.getElementById('set').remove();
    }

/*

			Изменение Глубины положения объекта

*/

    function zUp(id)
    {
        elemIndx = document.getElementById(id).style.zIndex;
        for (var item of document.getElementsByClassName('_content'))
        {
            if (elemIndx == Number(item.style.zIndex) - 1)
            {
                document.getElementById(id).style.zIndex = item.style.zIndex ;
                item.style.zIndex = Number(item.style.zIndex) - 1;
                return 1;
            }
        }
    }

    function zDown(id)
    {
        elemIndx = document.getElementById(id).style.zIndex;
        for (var item of document.getElementsByClassName('_content'))
        {
            if (elemIndx == Number(item.style.zIndex) + 1)
            {
                document.getElementById(id).style.zIndex = item.style.zIndex ;
                item.style.zIndex = Number(item.style.zIndex) + 1;
                return 1;
            }
        }
    }

    function zFront(id)
    {
        elemIndx = document.getElementById(id).style.zIndex;
        for (var item of document.getElementsByClassName('_content'))
        {
            if (item.style.zIndex > elemIndx)
            item.style.zIndex--;
        }
        document.getElementById(id).style.zIndex = DATA_ELEMENTS_ARRAY.length;
    }

    function zEnd(id)
    {
        elemIndx = document.getElementById(id).style.zIndex;
        for (var item of document.getElementsByClassName('_content'))
        {
            if (item.style.zIndex < elemIndx)
            item.style.zIndex++;
        }
        document.getElementById(id).style.zIndex = 1;	
    }


// Выводим все Элементы на доске при загруке страницы
    show_data();
    draw_canvas();

/*
			Функция Реструктурирования данных

*/

/*
	function restructure()
	{
			DATA_ELEMENTS_ARRAY = [
															['video_1', '10%','10%', '32%', '18%', '1','rgb(282,189,53)', 'Пикник', 'okmR0aUlL5E'],
															['image_1', "20%","20%", "20%", "20%",'2','rgb(282,189,53)', 'Горы', 'https://im0-tub-ru.yandex.net/i?id=277a5cfcbf1c78896e8efed5c0e87817&n=13'],
															['note_1',  "40%","50%", "20%", "10%", '3','rgb(282,189,53)', 'Надо сделать', 'Уборку,\\nДиван\\nМадам']
														];
	}
*/
 