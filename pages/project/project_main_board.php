<style>
#tool_bar
{
		background-color: rgb(31,31,31); 
		color: white;
		text-align: center;
}	
body
{
	overflow-y: hidden;
}
#viz_content
{
	overflow-x: scroll;
	overflow-y: scroll;
	height: 90%;
	width: 100%;
	position: relative;
}

#ref_par
{
	background-color: white;
	margin: 0 auto;
	text-align: center;
	margin-left: 40%;
	z-index: 999;
}

._content
{
		position:absolute; 
		cursor:pointer;
			transition: 0s;
			background-color:rgb(282,189,53);
			border-radius: 4px;
		box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
}
._content div
{
		overflow-x: hidden;
}
#ref
{
	width:98%;
}
.my_note, .my_checklist
{
	resize: both;
	overflow-x:hidden;
}
.my_image
{
	resize: both;
}
.my_video iframe
{
	width: 100%;
	height: 100%;
}
.my_file
{
	background: none;
	width: 5%;
	box-shadow: none;
	border-radius: none;
}
.my_file image
{
	width: 100%;
}
.my_file a
{
	color:white;
	text-decoration: none;
	display: none;
}
.my_video, .my_audio
{
	height:auto;
	resize: both;
}

._content img
{
	max-width: 100%;
	max-height: 100%;
}
._content img: hover
{
	width:10%;
}
._content textarea
{
	height:auto; 
	border:none; 
	background-color:rgb(282,189,53);
	display:block;
}
._content input
{
	display:inline-block; 
	width:80%;
	background-color:rgb(282,189,53);
	border:none;
}
._content span
{
	display:inline-block;
	border:none;  
	background-color:rgb(282,189,53)
}

.my_file input
{
	width: 100%;
	color:white;
	background: none;
}
</style>

<script>
	/// !!! IMPRTANT
let drag_element = '';
	function drag_me(element)
	{
	/// !!! IMPRTANT

		drag_element = element.parentNode.parentNode.getAttribute('id');

		height_of_top = (document.getElementById('tool_bar').offsetHeight + document.getElementsByTagName('header')[0].offsetHeight);


			move_element = function()
			{
				 		scroll_x = document.getElementById('viz_content').scrollLeft;
 						scroll_y = document.getElementById('viz_content').scrollTop ;
 				 x = event.pageX - 5 + scroll_x;
 				 y = event.pageY - height_of_top - 5 + scroll_y;
 				 if (x > 0 &&  y > 0)
 				 {
					document.getElementById(drag_element).style.marginLeft = x + 'px';
					document.getElementById(drag_element).style.marginTop  = y + 'px';
					}
				//console.log(document.getElementById(drag_element).style.marginTop,event.pageY,height_of_top);
			}
			click_on_body = function ()
			{
				//document.getElementById(drag_element).dataset.x = event.pageX;
				//document.getElementById(drag_element).dataset.y = event.pageY;
				document.body.removeEventListener('mousemove', move_element, false);
				document.body.removeEventListener('mouseup', click_on_body, false);
			}

			document.body.addEventListener('mousemove',move_element); 
			document.body.addEventListener('mouseup',click_on_body); 

			// esc не отлавливается в полноэкранном режиме
			document.body.addEventListener('keydown',function(e){
				if (e.keyCode == 27) {
					document.body.removeEventListener('mouseup', click_on_body, false);
					document.body.removeEventListener('mousemove', move_element, false);
					//document.body.removeEventListener('keydown', move_element, false);
				}

			}, true);
	}
	
	function add_list(elem)
	{
		elem.insertAdjacentHTML('beforeBegin',"<input type='checkbox' style='width:5%'><input type='text' value='new' style='width:90%'>");
	}

		function add_element(type)
		{
			if(document.getElementById('set')) document.getElementById('set').remove();

			x = document.body.clientWidth/3+'px';
		  y = '50px';
		  z = DATA_ELEMENTS_ARRAY.length+1;
		  c = 'rgb(282,189,53)'; // yellow
			
			if(type != 'note' && type != 'checklist') ref_type	= document.getElementById('ref_type').value;

			if (type == 'note')
			{
				w = "230px";
		  	h = "100px";
				meta = '';

				last_note_id++;
				id = type+'_'+last_note_id;
				name = id;
			}else if (type == 'checklist') 
			{
				w = "230px";
		  	h = "100px";
				meta = '';

				last_checklist_id++;
				id = type+'_'+last_checklist_id;
				name = id;
			}else if (type == 'image')
			{
		  	w = "200px";
		  	h = "200px";
		  	if 			(ref_type == 'internet')  	meta 	= document.getElementById('ref').value;
		  	else if (ref_type == 'self') 				meta = server_project_folder+'/'+document.getElementById('ref_dir').value+document.getElementById('ref').value;//meta 	= '../../images/'+ document.getElementById('ref').value;

		  	
		  	last_image_id++;
				id = type+'_'+last_image_id;
				name = document.getElementById('ref').value;
			}else if (type == 'video')
			{
			  w = "320px";
			  h = "180px";

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
			  h = "auto";
			  if 			(ref_type == 'internet')  	meta 	= document.getElementById('ref').value;
		  	else if (ref_type == 'self') 				meta = server_project_folder+'/'+document.getElementById('ref_dir').value+document.getElementById('ref').value;
		
		  	last_audio_id++;
				id = type+'_'+last_audio_id;
				name = document.getElementById('ref').value;
			}
			else if (type == 'file')
			{
		  	w = '5%';
		  	h = 'auto';
		  	c = 'rgb(0,0,0,0)'; 
		  	if 			(ref_type == 'internet')  	meta 	= document.getElementById('ref').value;
		  	else if (ref_type == 'self')  			meta = server_project_folder+'/'+document.getElementById('ref_dir').value+document.getElementById('ref').value; //meta 	= '../../images/tmp/' + document.getElementById('ref').value;

		  	last_file_id++;
				id = type+'_'+last_file_id;

				name = document.getElementById('ref_par').children[1].children[3].value;
			}

			if (type != 'note') document.getElementById('ref_par').remove();

		 DATA_ELEMENTS_ARRAY.push([id, x, y, w, h, z, c, name, meta]);
		 console.log(DATA_ELEMENTS_ARRAY[DATA_ELEMENTS_ARRAY.length-1]);
		 build_element(DATA_ELEMENTS_ARRAY.length-1);
		} 
	function create_new_element(type,place)
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

		if (document.getElementById('ref_par') != null) document.getElementById('ref_par').remove();
		if(document.getElementById('set') != null) document.getElementById('set').remove();

		if (type == 'note')
		{
			content = "<div style='position:absolute;' id='ref_par'>"+
									"<button onclick=add_element('note')>Заметка</button>"+
									"<button onclick=add_element('checklist')>Список</button>"+
			 					"</div>";
		}else{
			content = "<div style='position:absolute;' id='ref_par'>"+
									"<div><button onclick=change_type(\'"+descr_1+"\',\'"+descr_2+"\',0)>Интернет</button>"+
									"<button onclick=change_type(\'"+descr_1+"\',\'"+descr_2+"\',1)>Сервер</button>"+
									"<button onclick=remove_element(this);>X</button></div>"+
									"<div id='ref_block'>"+
										"<p>Введите "+descr_1+" ссылку на "+descr_2+"</p>"+
										"<input type='text' id='ref_type' value='internet' style='display:none'>"+
										"<input type='text' id='ref'>"+
									"</div>"+
										"<button onclick=add_element(\'"+type+"\')>Создать</button>"+
								"</div>";
		}
		document.getElementById(place).insertAdjacentHTML('afterBegin',content);
	}

	function change_type(res,type,mode)
	{
			if (!mode)
			{
				content = "<p>Введите "+res+" ссылку на "+type+"</p>"+
									"<input type='text' id='ref_type' value='internet' style='display:none'>"+
									"<input type='text' id='ref'>";
			document.getElementById('ref_block').innerHTML = content;
			}
			else
			{
				content = "<p>Выберите файл</p>"+
									"<input type='text' id='ref_type' value='self' style='display:none'>"+
									"<input type='text' id='ref_dir' style='display:none'>"+
									"<input type='text' id='ref'>"+my_files[my_files.length-1];

			document.getElementById('ref_block').innerHTML = content;
			if 			(type == 'Фото')  open_folder(1,'image');
			else if (type == 'Видео') open_folder(2,'multimedia');
			else if (type == 'Аудио') open_folder(2,'multimedia');
			}
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

	  	first = ref.indexOf('v=')+2;												// Ищем id видео
	  	last	= ref.indexOf('&',ref.indexOf('v='));			// Проверяем, есть ли после id еще параметры?
	  	
	  	if (last == -1) 	last = ref.length - 1;						// Параметров нет - считываем все, что после "v="
	  	else 							last = last-first;										// Параметры есть - находим последний символ id видео

	  	return ref.substr(first,last);
	  }
	  else
	  {
	  	return false;
	  }
	}

	function remove_element(elem)
	{
		if (typeof(elem) == 'object')
		{		
			id = elem.parentNode.parentNode.getAttribute('id');
		}
		else if (typeof(elem) == 'string') 		
		{
			id = elem;
			if(document.getElementById('set') != null) document.getElementById('set').remove();
		}
				
		

		if (id.includes('note') || id.includes('image') || id.includes('video')  || id.includes('file') || id.includes('audio') || id.includes('checklist')) 
		{
			indx = document.getElementById(id).style.zIndex;

			if 			(id.includes('note')) {last_note_id--; type = 'note_';}
			else if (id.includes('checklist')) {last_checklist_id--; type = 'checklist_';}
			else if (id.includes('image')){last_image_id--;type = 'image_';}
			else if (id.includes('video')){last_video_id--;type = 'video_';}
			else if (id.includes('file')){last_video_id--; type = 'file_';}
			else if (id.includes('audio')){last_audio_id--; type = 'audio_';}
			DATA_ELEMENTS_ARRAY = DATA_ELEMENTS_ARRAY.filter(n => !n[0].includes(id));

			for (i = 0; i < DATA_ELEMENTS_ARRAY.length; i++)
			{
				val = DATA_ELEMENTS_ARRAY[i][0];
				// Убавлям z-index у всех элементов, у которых z-ind больше Удаляемого эл-та
				if (document.getElementById(val).style.zIndex > indx)
				{
					document.getElementById(val).style.zIndex--;
					DATA_ELEMENTS_ARRAY[i][5]	 = document.getElementById(val).style.zIndex;
				}

				// Убавляем суффикс типа элемента, если он больше удаляемого (note_2 -> note_1) 
				if (val.includes(type) && Number(val.substr(val.indexOf('_')+1))>Number(id.substr(id.indexOf('_')+1)) )
				{
					document.getElementById(val).id 				= type + String(Number(val.substr(val.indexOf('_')+1))-1);
					DATA_ELEMENTS_ARRAY[i][0] 							= type + String(Number(val.substr(val.indexOf('_')+1))-1);
				}
			}
		}

		document.getElementById(id).remove();
	}
</script>
<script>
let	last_note_id = 0;
let	last_checklist_id = 0;
let	last_image_id = 0;
let	last_video_id = 0;
let last_audio_id = 0;
let last_file_id = 0;

	function show_data()
	{
		//DATA_ELEMENTS_ARRAY = JSON.parse(board_data);
		for (i = 0 ; i < DATA_ELEMENTS_ARRAY.length; i++) {
			build_element(i);
		}
	}

/// !!! АККУРАТНО ОШШИБКИ (Т.к мы берем Высоту как высоту viz_content, а Ширину как ширину окна body)	
	function to_pixel(val,flag)
	{
		val = String(val);
		if(flag == 0)
		{
			if(val.includes('%'))
				{
					return Math.floor((val.substr(0, val.length-1)*document.body.clientWidth))/100+'px';
				}
			else 									return val;
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
	function build_element(num)
	{
		//console.log(DATA_ELEMENTS_ARRAY[num]);
		id 		= DATA_ELEMENTS_ARRAY[num][0];
		x  		= 'margin-left:'			+ to_pixel(DATA_ELEMENTS_ARRAY[num][1],0) +'; ';
		y  		= 'margin-top:' 			+ to_pixel(DATA_ELEMENTS_ARRAY[num][2],1) +'; ';
		w  		= 'width:'						+ to_pixel(DATA_ELEMENTS_ARRAY[num][3],0) +'; ';
		h  		= 'height:' 					+ to_pixel(DATA_ELEMENTS_ARRAY[num][4],1) +'; ';
		z 		=	'z-index:'					+ 				 DATA_ELEMENTS_ARRAY[num][5]		+'; ';
		c 		=	'background-color:'	+ 				 DATA_ELEMENTS_ARRAY[num][6] 		+'; ';
		name	= DATA_ELEMENTS_ARRAY[num][7];
		meta	= DATA_ELEMENTS_ARRAY[num][8];

		type = id.substr(0, id.indexOf('_'));
		content = "<div id='"+id+"'"+
									" class='_content my_"+type+"'"+
									" style='"+x+y+w+h+z+c+"'"+
									" oncontextmenu='show_settings(this);return false'>"+
									"<div>";

		if (type == 'file') 
		{
			src_image = get_src_image(meta);
			content += "<img onmousedown='drag_me(this); return false' src='"+src_image+"' style='display:block;' draggable='false' alt='"+name+"'>"+
  									"<input value='"+name+"'>";			

		} else {
			content += "<img draggable='false' style='width:7%; margin-bottom:-1%; margin-left:-1%;' src='"+server_content_folder+"/icons/pin.png' onmousedown='drag_me(this); return false;'>"+
									"<input type='text' value='"+name+"' style='"+c+"'>"+
									"<button onclick=remove_element(this); style='float:right;width:9%;'>X</button>";  		
		}
		content +=	"</div>";

		if 			(id.includes('note')) {	content += "<textarea style='width:100%;height:100%;"+c+"'>"+meta+"</textarea></div>"; last_note_id = id.substr(id.indexOf('_')+1);}
		else if (id.includes('image')){	content += "<img src='"+meta+"'></div>";	last_image_id = id.substr(id.indexOf('_')+1);}
		else if (id.includes('file')){  content += "<a href='"+meta+"' download>"+meta+"</a>"; last_file_id = id.substr(id.indexOf('_')+1);}
		else if (id.includes('video'))
		{	
			if(meta.includes('https://youtube.com/embed/'))
				content += "<iframe width='auto' height='auto' src='"+meta+"' frameborder='0' allowfullscreen data-meta="+meta+"></iframe>";
			else 																												// АККУРАТНО С poster - не работает на APPLE
				content += "<div><video style='width:100%;' preload='none' controls><source src='"+meta+"' type='video/mp4' ></video>"+
										//"<button onclick=document.getElementById('"+id+"').getElementsByTagName('video')[0].play();>Play</button>"+
		  							//"<button onclick=document.getElementById('"+id+"').getElementsByTagName('video')[0].pause();>Pause</button>"+
										"</div>";

			last_video_id = id.substr(id.indexOf('_')+1);
		}
		else if(id.includes('audio'))
		{
			content += "<div><audio style='width:100%;' preload='none' controls><source src='"+meta+"' type='audio/mp3' ></audio>"+
										//"<button onclick=document.getElementById('"+id+"').getElementsByTagName('audio')[0].play();>Play</button>"+
		  							//"<button onclick=document.getElementById('"+id+"').getElementsByTagName('audio')[0].pause();>Pause</button>"+
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
				}else if (first_index == -1 || meta.indexOf('{1,',last_index) < first_index)
				{
					first_index = meta.indexOf('{1,',last_index);
					last_index = meta.indexOf('}',first_index);
					content += "<input type='checkbox' style='width:5%' checked><input type='text' value='"+meta.substring(first_index+3,last_index)+"' style='width:90%; "+c+"'>";
				}else{
					last_index = meta.indexOf('}',first_index);
					content += "<input type='checkbox' style='width:5%'><input type='text' value='"+meta.substring(first_index+3,last_index)+"' style='width:90%; "+c+"'>";
				}
			//	console.log(last_index);
			}
			last_checklist_id = id.substr(id.indexOf('_')+1);
			content+='<button onclick=add_list(this)>Add</button></div>';
		}
			content += '</div>';	
		
		//}
		document.getElementById('viz_content').insertAdjacentHTML('beforeEnd',content);
	}

	function get_src_image(val)
	{
			if 			(val.includes('.doc'))		return  server_content_folder+'/icons/word.png';
			else if (val.includes('.xl')) 		return 	server_content_folder+'/icons/excel.png';
			else if (val.includes('.accdb'))	return 	server_content_folder+'/icons/access.png';
			else if (val.includes('.pp'))			return 	server_content_folder+'/icons/powerpoint.png';
			else if (val.includes('.vs'))			return 	server_content_folder+'/icons/visio.png';
			else 															return 	server_content_folder+'/icons/file.png';

	}

	function update_array()
	{
		for (i = 0; i < DATA_ELEMENTS_ARRAY.length; i++)
		{
			id = DATA_ELEMENTS_ARRAY[i][0];
			element = document.getElementById(id);
			DATA_ELEMENTS_ARRAY[i][1] = to_percent(element.style.marginLeft,0);
			DATA_ELEMENTS_ARRAY[i][2] = to_percent(element.style.marginTop,1);
			DATA_ELEMENTS_ARRAY[i][3] = to_percent(element.style.width,0);
			DATA_ELEMENTS_ARRAY[i][4] = to_percent(element.style.height,1);
			DATA_ELEMENTS_ARRAY[i][5] = element.style.zIndex;
			DATA_ELEMENTS_ARRAY[i][6] = element.style.backgroundColor;
			DATA_ELEMENTS_ARRAY[i][7] = element.children[0].children[1].value;

		if 			(id.includes('note')) 	DATA_ELEMENTS_ARRAY[i][8] = (element.children[1].value).split("\n").join("\\n");
		else if (id.includes('image'))	DATA_ELEMENTS_ARRAY[i][8] = element.children[1].src;
		else if (id.includes('video'))	
		{
			if (element.getElementsByTagName('iframe').length != 0) DATA_ELEMENTS_ARRAY[i][8] = element.children[1].dataset.meta;
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

		console.log(DATA_ELEMENTS_ARRAY[i]);
		}
	}

/*
	function restructure()
	{
			DATA_ELEMENTS_ARRAY = [
															['video_1', '10px','100px', '320px', '180px', '1','rgb(282,189,53)', 'Пикник', 'okmR0aUlL5E'],
															['image_1', "200px","200px", "200px", "200px",'2','rgb(282,189,53)', 'Горы', 'https://im0-tub-ru.yandex.net/i?id=277a5cfcbf1c78896e8efed5c0e87817&n=13'],
															['note_1',  "400px","50px", "200px", "100px", '3','rgb(282,189,53)', 'Надо сделать', 'Уборку,\\nДиван\\nМадам']
														];
	}
*/
	function update_data(this_id)
	{
		update_array();
	//	restructure();
		json_data = JSON.stringify(DATA_ELEMENTS_ARRAY);
		//result = JSON.parse(json_data);
			
		content = "<form method = 'POST' action = 'project.php?project_id="+this_id+"' style='position:absolute; z-index:999; background-color:white;'>"+
								"<input type='text' name='board_elements' value='"+String(json_data)+"' style='display:none'>"+
								"<input type='submit' value='Отправить'>"+
							"</form>";
	document.getElementById('viz_content').insertAdjacentHTML('afterBegin',content);
	}


</script>
<?php
		$id = $_GET['project_id'];
		if (isset($_POST['board_elements'])) 
		{
			$x = $_POST['board_elements'];
			queryMySQL("UPDATE list SET board = '$x' WHERE id = '$id'");
			$_POST['board_elements'] = null;
		}
		//
		$str = queryMySQL("SELECT board FROM list WHERE id = '$id'")->fetch_array(MYSQLI_ASSOC)['board'];

			echo "<div id='visualization'>";
//onclick='create_new_note(1)'
			echo "<div id='tool_bar'>".
						"<span style='display:inline-block' onclick=create_new_element('note','viz_content')>Note</span>   ".
						"<span style='display:inline-block' onclick=create_new_element('image','viz_content')>Photo</span>  ".
						"<span style='display:inline-block' onclick=create_new_element('video','viz_content')>Video</span>  ".
						"<span style='display:inline-block' onclick=create_new_element('audio','viz_content')>Audio</span>	".
						"<span style='display:inline-block' onclick=create_new_element('file','viz_content')>File</span>".
					"</div>";

			echo "<div id='viz_content'>";
			
			echo "<button onclick=update_data('".$id."'); style='position:fixed'>SAVE ALL</button>";
			if ($str == null || $str == '') 
			{
			
				echo "<script> let DATA_ELEMENTS_ARRAY = [];</script>";
				
			}else
			{
				echo "<script> let DATA_ELEMENTS_ARRAY = $str;</script>";
				echo "<script>show_data();</script>";
			}
			
			echo "</div>";// viz_content
			echo "</div>";// vizualization
 ?>


  <script>
  //	let scroll_x, scroll_y = 0;

	function set_parametrs(id)
	{
	//	document.getElementById(id).style.backgroundColor = document.getElementById('bkg_color').value;
		if (!id.includes('file'))
		{
				for (i = 1; i <= 7; i++) 
				{
					if (document.getElementById('color_'+i).checked) 
					{
						document.getElementById(id).style.backgroundColor = document.getElementById('color_'+i).value;
						document.getElementById(id).children[0].children[0].style.backgroundColor = document.getElementById('color_'+i).value;
						document.getElementById(id).children[0].children[1].style.backgroundColor = document.getElementById('color_'+i).value;
						document.getElementById(id).children[1].style.backgroundColor = document.getElementById('color_'+i).value;
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
			document.getElementById(id).style.width = document.getElementById('div_size').value +'%';
		}
		if (id.includes('image')) document.getElementById(id).children[1].src = document.getElementById('ref').value; 
		if (id.includes('video'))
		{  
			if (!document.getElementById(id).children[1].children[0].children[0].src.includes(server_project_folder))
			{
				meta = get_meta(document.getElementById('ref').value);
				address = 'https://youtube.com/embed/' + meta;

			  if (meta === false)
			  {	
					alert('Видео должно быть с YouTube\nПроверьте Ссылку');
					meta='';
					document.getElementById(id).style.fontSize = document.getElementById('font_size').value +'px';
		  		document.getElementById('set').remove();
			  	return 0;
			  }
			  else{
					document.getElementById(id).children[1].src = address;
					document.getElementById(id).children[1].dataset.meta = address;
				}
			}
		}

		document.getElementById(id).style.fontSize = document.getElementById('font_size').value +'px';
	  document.getElementById('set').remove();

	}
	function show_settings(element)
	{
		if(document.getElementById('set') 		!= null) document.getElementById('set').remove();
		if(document.getElementById('ref_par') != null) document.getElementById('ref_par').remove();
		content = "<div id='set' style='position:fixed; z-index:1000; margin-left:400px; background-color:white;'>"+
								"<div>Парметры<button onclick=remove_element(this); style='float:right'>X</button></div>";
				if (!element.id.includes('file')) 
				{
					content+="Color:<br>"+
								"<div class='radio_color'>"+
									"<input type='radio' name='a' id='color_1' value='rgb(282,189,53)' checked/>Y"+
									"<input type='radio' name='a' id='color_2' value='rgb(0,64,64)'/>G"+
									"<input type='radio' name='a' id='color_3' value='rgb(210,31,60)'/>R"+
									"<input type='radio' name='a' id='color_4' value='rgb(184,15,10)'/>R2"+
									"<input type='radio' name='a' id='color_5' value='rgb(191,10,48)'/>R3"+
									"<input type='radio' name='a' id='color_6' value='rgb(50, 85, 171)'/>W1"+
									"<input type='radio' name='a' id='color_7' value='rgb(255, 252, 255)'/>W2"+
								"</div>";
				}
				else
				{
					content+= "<a style='text-decoration:none; color:black;' href='"+element.children[1].innerHTML+"' download>Скачать Файл</a>"+
										"<br><button onclick=remove_element(\'"+element.id+"\')>Удалить файл с доски</button>"+	
										"<br>Размер:<br>"+
										"<input id='div_size' type='range' min='2' max='10' step='1' value='"+(element.style.width).slice(0, -1)+"'><br>";
				}
				content+="Размер Шрифта:<br>"+
								"<input id='font_size' type='range' min='8' max='24' step='2' value='"+(element.style.fontSize).slice(0, -2)+"'>"+
								"<div><button onclick=zUp(\'"+element.id+"\')>Выше</button><button onclick=zFront(\'"+element.id+"\')>Наверх</button></div>"+
								"<div><button onclick=zDown(\'"+element.id+"\')>Ниже</button><button onclick=zEnd(\'"+element.id+"\')>Вниз</button></div>";

	if(element.id.includes('image')) 
	{ 
		//if(!element.children[1].children[0].children[0].src.includes(server_project_folder))
		//if(get_meta(element.children[1].src) !== false)
		content = content + "Ссылка:<br><input id='ref' type='text' value='"+element.children[1].src+"'>";
	}
	if (element.id.includes('video')) 
	{
		if(element.querySelector('iframe') != null)
		if(get_meta(element.children[1].src) !== false)
		{
			content = content + "Ссылка:<br><input id='ref' type='text' value='"+element.children[1].src+"'>";
		}
	}

		content = content +	"<button onclick=set_parametrs(\'"+element.id+"\','"+element.children[1].src+"\')>APPLY</button>"+
							"</div>";
		document.getElementById('viz_content').insertAdjacentHTML('afterBegin',content);
	}

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
  </script>