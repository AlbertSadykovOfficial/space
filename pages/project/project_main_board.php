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
.my_note
{
	resize: both;
	overflow-x:hidden;
}
.my_image
{
	resize: both;
}
.my_video
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

._content img
{
	max-width: 100%;
	max-height: 100%;
}
._content img hover
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
	width:auto;
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
	}
	
		function add_element(type)
		{
			if(document.getElementById('set')) document.getElementById('set').remove();

			x = document.body.clientWidth/3+'px';
		  y = '50px';
		  z = DATA_ELEMENTS_ARRAY.length+1;
		  c = 'rgb(282,189,53)'; // yellow
			
			if(type != 'note') ref_type	= document.getElementById('ref_type').value;

			if (type == 'note')
			{
				w = "230px";
		  	h = "100px";
				meta = '';

				last_note_id++;
				id = type+'_'+last_note_id;
			}else if (type == 'image')
			{
		  	w = "200px";
		  	h = "200px";
		  	if 			(ref_type == 'internet')  	meta 	= document.getElementById('ref').value;
		  	else if (ref_type == 'self') 				meta 	= '../../images/' + document.getElementById('ref').value;

		  	last_image_id++;
				id = type+'_'+last_image_id;
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
				  	meta = '../../images/tmp/' + document.getElementById('ref').value;
			  	}

			  last_video_id++;
				id = type+'_'+last_video_id;
			}else if (type == 'file')
			{
		  	w = '5%';
		  	h = 'auto';
		  	c = 'rgb(0,0,0,0)'; 
		  	if 			(ref_type == 'internet')  	meta 	= document.getElementById('ref').value;
		  	else if (ref_type == 'self')  			meta 	= '../../images/tmp/' + document.getElementById('ref').value;

		  	last_file_id++;
				id = type+'_'+last_file_id;

				type = document.getElementById('ref_par').children[1].children[2].value;
			}

			if (type != 'note') document.getElementById('ref_par').remove();

		 DATA_ELEMENTS_ARRAY.push([id, x, y, w, h, z, c, type, meta]);
		 console.log(DATA_ELEMENTS_ARRAY[DATA_ELEMENTS_ARRAY.length-1]);
		 build_element(DATA_ELEMENTS_ARRAY.length-1);
		} 
	function create_new_element(type)
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
		}

		if (document.getElementById('ref_par') != null) document.getElementById('ref_par').remove();
		if(document.getElementById('set') != null) document.getElementById('set').remove();

		content = "<div style='position:absolute;' id='ref_par'>"+
							"<div><button onclick=change_type(\'"+descr_1+"\',\'"+descr_2+"\',0)>Интернет</button><button onclick=change_type(\'"+descr_1+"\',\'"+descr_2+"\',1)>Сервер</button><button onclick=remove_element(this);>X</button></div>"+
							"<div id='ref_block'>"+
								"<p>Введите "+descr_1+" ссылку на "+descr_2+"</p>"+
								"<input type='text' id='ref_type' value='internet' style='display:none'>"+
								"<input type='text' id='ref'>"+
							"</div>"+
								"<button onclick=add_element(\'"+type+"\')>Создать</button>"+
							"</div>";
		document.getElementById('viz_content').insertAdjacentHTML('afterBegin',content);
	}

	function change_type(res,content,mode)
	{
			if (!mode)
			{
				content = "<p>Введите "+res+" ссылку на "+content+"</p>"+
									"<input type='text' id='ref_type' value='internet' style='display:none'>"+
									"<input type='text' id='ref'>";
			}
			else
			{
				content = "<p>Выберите файл</p>"+
									"<input type='text' id='ref_type' value='self' style='display:none'>"+
									"<input type='text' id='ref'>"+my_files;
			}

			document.getElementById('ref_block').innerHTML = content;
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
				
		

		if (id.includes('note') || id.includes('image') || id.includes('video')  || id.includes('file')) 
		{
			indx = document.getElementById(id).style.zIndex;

			if 			(id.includes('note')) {last_note_id--; type = 'note_';}
			else if (id.includes('image')){last_image_id--;type = 'image_';}
			else if (id.includes('video')){last_video_id--;type = 'video_';}
			else if (id.includes('file')){last_video_id--; type = 'file_';}
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
let	last_image_id = 0;
let	last_video_id = 0;
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
		x  		= 'margin-left:'			+ to_pixel(DATA_ELEMENTS_ARRAY[num][1],0) +'; '; console.log(DATA_ELEMENTS_ARRAY[num][1]);
		y  		= 'margin-top:' 			+ to_pixel(DATA_ELEMENTS_ARRAY[num][2],1) +'; ';console.log(DATA_ELEMENTS_ARRAY[num][2]);
		w  		= 'width:'						+ to_pixel(DATA_ELEMENTS_ARRAY[num][3],0) +'; ';
		h  		= 'height:' 					+ to_pixel(DATA_ELEMENTS_ARRAY[num][4],1) +'; ';
		z 		=	'z-index:'					+ to_pixel(DATA_ELEMENTS_ARRAY[num][5]) +'; ';
		c 		=	'background-color:'	+ to_pixel(DATA_ELEMENTS_ARRAY[num][6]) +'; ';
		name	= DATA_ELEMENTS_ARRAY[num][7];
		meta	= DATA_ELEMENTS_ARRAY[num][8];

		type = id.substr(0, id.indexOf('_'));
		content = "<div id='"+id+"' class='_content my_"+type+"' style='"+x+y+w+h+z+c+"' oncontextmenu='show_settings(this);return false'>"+
								"<div>";
		if (type != 'file') 
		{	//style='"+c+"'  <span  > </span>
			content += "<img draggable='false' style='width:7%; margin-bottom:-1%; margin-left:-1%;' src='../../images/icons/pin.png' onmousedown='drag_me(this); return false;'>"+
									"<input type='text' value='"+name+"' style='"+c+"'>"+
									"<button onclick=remove_element(this); style='float:right;width:9%;'>X</button>";
		}
		else
		{

			if (meta.includes('.doc'))				image = '../../images/icons/word.png';
			else if (meta.includes('.xl')) 		image = '../../images/icons/excel.png';
			else if (meta.includes('.accdb'))	image = '../../images/icons/access.png';
			else if (meta.includes('.pp'))		image = '../../images/icons/powerpoint.png';
			else if (meta.includes('.vs'))		image = '../../images/icons/visio.png';
			else 															image = '../../images/icons/file.png';

			content += "<img onmousedown='drag_me(this); return false' src='"+image+"' style='display:block;' draggable='false' alt='"+name+"'>"+
  									"<input value='"+name+"'>";
		}
		content +=	"</div>";
		if 			(id.includes('note')) {	content += "<textarea style='width:100%;height:100%;"+c+"'>"+meta+"</textarea></div>"; last_note_id = id.substr(id.indexOf('_')+1);}
		else if (id.includes('image')){	content += "<img src='"+meta+"'></div>";	last_image_id = id.substr(id.indexOf('_')+1);}
		else if (id.includes('file')){  content += "<a href='"+meta+"' download>"+meta+"</a>"; last_file_id = id.substr(id.indexOf('_')+1);}
		else if (id.includes('video')){
			content += "<iframe width='auto' height='auto' src='"+meta+"' frameborder='0' allowfullscreen data-meta="+meta+"></iframe></div>";	last_video_id = id.substr(id.indexOf('_')+1);
		}
									
		document.getElementById('viz_content').insertAdjacentHTML('beforeEnd',content);
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

		if 			(id.includes('note')) 	DATA_ELEMENTS_ARRAY[i][8] = (document.getElementById(id).children[1].value).split("\n").join("\\n");
		else if (id.includes('image'))	DATA_ELEMENTS_ARRAY[i][8] = document.getElementById(id).children[1].src;
		else if (id.includes('video'))	DATA_ELEMENTS_ARRAY[i][8] = document.getElementById(id).children[1].dataset.meta;

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
		if ($str == null || $str == '') 
		{
			$str = [];
		}
		echo "<script> let DATA_ELEMENTS_ARRAY = $str;</script>";

			echo "<div id='visualization'>";
//onclick='create_new_note(1)'
			echo "<div id='tool_bar'>".
						"<span style='display:inline-block' onclick=add_element('note')>Note</span>".
						"<span style='display:inline-block' onclick=create_new_element('image')>   Photo</span>".
						"<span style='display:inline-block' onclick=create_new_element('video')>  Video</span>".
						"<span style='display:inline-block' onclick=create_new_element('file')>   File</span>".
					"</div>";

			echo "<div id='viz_content'>";
			
			echo "<button onclick=update_data('".$id."'); style='position:fixed'>SAVE ALL</button>";
			echo "<script>show_data();</script>";
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
					content+="<a style='text-decoration:none; color:black;' href='"+element.children[1].innerHTML+"' download>Скачать Файл</a>"+
										"<br><button onclick=remove_element(\'"+element.id+"\')>Удалить файл с доски</button>"+
										"<br>Размер:<br>"+
										"<input id='div_size' type='range' min='2' max='10' step='1' value='"+(element.style.width).slice(0, -1)+"'><br>";
				}
				content+="Размер Шрифта:<br>"+
								"<input id='font_size' type='range' min='8' max='24' step='2' value='"+(element.style.fontSize).slice(0, -2)+"'>"+
								"<div><button onclick=zUp(\'"+element.id+"\')>Выше</button><button onclick=zFront(\'"+element.id+"\')>Наверх</button></div>"+
								"<div><button onclick=zDown(\'"+element.id+"\')>Ниже</button><button onclick=zEnd(\'"+element.id+"\')>Вниз</button></div>";
	if(!element.id.includes('note') && !element.id.includes('file')) 
	{ 
		content = content + "Ссылка:<br><input id='ref' type='text' value='"+element.children[1].src+"'>"
	}

		content = content +	"<button onclick=set_parametrs(\'"+element.id+"\')>APPLY</button>"+
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