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
._content img
{
	max-width: 100%;
	max-height: 100%;
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
	
function create_new_note()
{
	if(document.getElementById('set')) document.getElementById('set').remove();
	//console.log(DATA_ELEMENTS_ARRAY);
	  x = document.body.clientWidth/3+'px';
  	y = '0px';
  	w = "230px";
  	h = "100px";
  	z = DATA_ELEMENTS_ARRAY.length+1;
  	c = 'rgb(282,189,53)'; // yellow
	last_note_id++;
	style = "margin-left:"+x+"; margin-top:"+y+"; width:"+w+"; height:"+h+"; z-index: "+z+"; background-color:"+c+";";

	content = "<div id='note_"+last_note_id+"' style='"+style+"' class='_content my_note' oncontextmenu='show_settings(this);return false'>"+
							"<div>"+
								"<span  onmousedown=drag_me(this)>X</span>"+
								"<input type='text' value='заметка' style='background-color:"+c+"'>"+
								"<button onclick=remove_element(this); style='float:right;width:9%;'>C</button>"+
							"</div>"+
								"<textarea style='width:100%; height:100%; background-color:"+c+";'></textarea>"+
						"</div>";
		DATA_ELEMENTS_ARRAY.push(['note_'+last_note_id, x, y, w, h, z, c,'note','']);
 // console.log(DATA_ELEMENTS_ARRAY);
	document.getElementById('viz_content').insertAdjacentHTML('beforeEnd',content);
}

function change_file_type(type)
{
		if (!type)
		{
			content = "<p>Введите Интернет ссылку на фото</p>"+
								"<input type='text' id='ref_type' value='internet' style='display:none'>"+
								"<input type='text' id='ref'>";
			document.getElementById('file_ref_block').innerHTML = content;
		}
		else
		{
			content = "<p>Выберите файл</p>"+
								"<input type='text' id='ref_type' value='self' style='display:none'>"+
								"<input type='text' id='ref'>"+my_files;
			document.getElementById('file_ref_block').innerHTML = content;
		}
}
function create_new_file_div()
{

  ref_type	= document.getElementById('ref_type').value;
  if (ref_type == 'internet')  	address 	= document.getElementById('ref').value;
  else if (ref_type == 'self')  address 	= '../../images/tmp/' + document.getElementById('ref').value;
 

  content = "<div><img src='../../images/settings_icon.png' style='display:inline-block'>"+document.getElementById('ref').value+"</div>";
   document.getElementById('ref_par').remove();
  document.getElementById('viz_content').insertAdjacentHTML('beforeEnd',content);

}
function create_new_file()
{
	if(document.getElementById('set')) document.getElementById('set').remove();
	content = "<div id='ref_par' style='position:absolute;' >"+
							"<button onclick='change_file_type(0)'>Интернет</button><button onclick='change_file_type(1)'>Свое</button>"+
							"<div id='file_ref_block'>"+
								"<p>Выберите файл</p>"+
								"<input type='text' id='ref_type' value='self' style='display:none'>"+
								"<input type='text' id='ref'>"+my_files+
							"</div>"+
							"<button onclick='create_new_file_div()'>Создать</button>"+
						"</div>";
	document.getElementById('viz_content').insertAdjacentHTML('beforeEnd',content);
}

function change_image_type(type)
{
		if (!type)
		{
			content = "<p>Введите Интернет ссылку на фото</p>"+
								"<input type='text' id='ref_type' value='internet' style='display:none'>"+
								"<input type='text' id='ref'>";
			document.getElementById('image_ref_block').innerHTML = content;
		}
		else
		{
			content = "<p>Выберите файл</p>"+
								"<input type='text' id='ref_type' value='self' style='display:none'>"+
								"<input type='text' id='ref'>"+my_files;
			document.getElementById('image_ref_block').innerHTML = content;
		}
}

function create_new_image()
{
	if(document.getElementById('set')) document.getElementById('set').remove();
	content = "<div id='ref_par' style='position:absolute;' >"+
							"<button onclick='change_image_type(0)'>Интернет</button><button onclick='change_image_type(1)'>Свое</button>"+
							"<div id='image_ref_block'>"+
							"<p>Введите Интернет ссылку на фото</p>"+
							"<input type='text' id='ref_type' value='internet' style='display:none'>"+
							"<input type='text' id='ref'>"+
							"</div>"+
							"<button onclick='create_new_image_div()'>Создать</button>"+
						"</div>";
	document.getElementById('viz_content').insertAdjacentHTML('beforeEnd',content);
}
function create_new_image_div()
{
	last_image_id++;
	  x = document.body.clientWidth/3+'px';
  	y = '50px';
  	w = "200px";
  	h = "200px";
  	z = DATA_ELEMENTS_ARRAY.length+1;
  	c = 'rgb(282,189,53)'; // yellow

 
  ref_type	= document.getElementById('ref_type').value;
  if (ref_type == 'internet')  	address 	= document.getElementById('ref').value;
  else if (ref_type == 'self')  address 	= '../../images/' + document.getElementById('ref').value;
  document.getElementById('ref_par').remove();
	
	

	style = "margin-left:"+x+"; margin-top:"+y+"; width:"+w+"; height:"+h+"; z-index: "+z+"; background-color:"+c+";";

	content = "<div id='image_"+last_image_id+"' class='_content my_image' oncontextmenu='show_settings(this);return false' style='"+style+"'>"+
							"<div>"+
								"<span  onmousedown=drag_me(this) 			style='background-color:"+c+"'>X</span>"+
								"<input type='text' value='Картинка' 		style='background-color:"+c+"' >"+
								"<button onclick=remove_element(this); 	style='float:right;width:9%;'>C</button>"+
							 "</div>"+
								"<img src='"+address+"'>"+
						"</div>";
	
	DATA_ELEMENTS_ARRAY.push(['image_'+last_image_id, x, y, w, h, z, c, 'image', address]);
	//console.log(DATA_ELEMENTS_ARRAY);
	document.getElementById('viz_content').insertAdjacentHTML('beforeEnd',content);

}

function change_video_type(type)
{
		if (!type)
		{
			content = "<p>Введите YouTube ссылку на видео</p>"+
								"<input type='text' id='ref_type' value='internet' style='display:none'>"+
								"<input type='text' id='ref'>";
			document.getElementById('video_ref_block').innerHTML = content;
		}
		else
		{
			content = "<p>Выберите Видео</p>"+
								"<input type='text' id='ref_type' value='self' style='display:none'>"+
								"<input type='text' id='ref'>"+my_files;
			document.getElementById('video_ref_block').innerHTML = content;
		}
}

function create_new_video()
{
	if(document.getElementById('set')) document.getElementById('set').remove();
	content = "<div style='position:absolute;' id='ref_par'>"+
						"<button onclick='change_video_type(0)'>Интернет</button><button onclick='change_video_type(1)'>Свое</button>"+
						"<div id='video_ref_block'>"+
							"<p>Введите YouTube ссылку на видео</p>"+
							"<input type='text' id='ref_type' value='internet' style='display:none'>"+
							"<input type='text' id='ref'>"+
						"</div>"+
							"<button onclick='create_new_video_div()'>Создать</button>"+
						"</div>";
	document.getElementById('viz_content').insertAdjacentHTML('beforeEnd',content);
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

	function create_new_video_div()
	{
	  last_video_id++;
	    x = document.body.clientWidth/3+'px';
	  	y = '50px';
	  	w = "320px";
	  	h = "180px";
	  	z = DATA_ELEMENTS_ARRAY.length+1;
	  	c = 'rgb(282,189,53)'; // yellow

	  address = (document.getElementById('ref').value).trim();
	  ref_type	= document.getElementById('ref_type').value;
  	if (ref_type == 'internet')
  	{
	  		address 	= (document.getElementById('ref').value).trim;
	  		meta = get_meta(address);
	  		if (meta === false)
			  {	
					alert('Видео должно быть с YouTube\nПроверьте Ссылку');
			  	last_video_id--;
			  	return 0;
			  }

			  address = 'https://youtube.com/embed/' + meta;
  	}
  	else if (ref_type == 'self')  
  	{
	  		meta = document.getElementById('ref').value;
	  		address 	= '../../images/tmp/' + meta;
  	}

	  document.getElementById('ref_par').remove();
		
	  DATA_ELEMENTS_ARRAY.push(['video_'+last_video_id, x, y, w, h, z, c,'video', meta]);

	  style = "margin-left:"+x+"; margin-top:"+y+"; width:"+w+"; height:"+h+"; z-index: "+z+"; background-color:"+c+";";

		content = "<div id='video_"+last_video_id+"' class='_content my_video' style='"+style+"' oncontextmenu='show_settings(this);return false'>"+
								"<div>"+
									"<span  onmousedown=drag_me(this) style='width:10%; background-color:"+c+";'>X</span>"+
									"<input id='video_"+last_video_id+"_name' type='text' value='Видео' style='width:80%; background-color:"+c+";'>"+
									"<button onclick=remove_element(this); style='float:right;width:9%;'>C</button>"+
								 "</div>"+
								 "<iframe width='auto' height='auto' src='"+address+"' frameborder='0' allowfullscreen data-meta="+meta+"></iframe>"+
							"</div>";


		document.getElementById('viz_content').insertAdjacentHTML('beforeEnd',content);
	}

	function remove_element(elem)
	{
		
		id = elem.parentNode.parentNode.getAttribute('id');
		if (id.includes('note') || id.includes('image') || id.includes('video')) 
		{
			indx = document.getElementById(id).style.zIndex;

			if 			(id.includes('note')) {last_note_id--; type = 'note_';}
			else if (id.includes('image')){last_image_id--;type = 'image_';}
			else if (id.includes('video')){last_video_id--;type = 'video_';}
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
let	last_note_id = 1;
let	last_image_id = 1;
let	last_video_id = 1;
	function show_data()
	{
		//DATA_ELEMENTS_ARRAY = JSON.parse(board_data);
		for (i = 0 ; i < DATA_ELEMENTS_ARRAY.length; i++) {
			build_element(i);
		}
	}
	function build_element(num)
	{
		//console.log(DATA_ELEMENTS_ARRAY[num]);
		id 		= DATA_ELEMENTS_ARRAY[num][0];
		x  		= 'margin-left:'			+ DATA_ELEMENTS_ARRAY[num][1] +'; ';
		y  		= 'margin-top:' 			+ DATA_ELEMENTS_ARRAY[num][2] +'; ';
		w  		= 'width:'						+ DATA_ELEMENTS_ARRAY[num][3] +'; ';
		h  		= 'height:' 					+ DATA_ELEMENTS_ARRAY[num][4] +'; ';
		z 		=	'z-index:'					+ DATA_ELEMENTS_ARRAY[num][5] +'; ';
		c 		=	'background-color:'	+ DATA_ELEMENTS_ARRAY[num][6] +'; ';
		name	= DATA_ELEMENTS_ARRAY[num][7];
		meta	= DATA_ELEMENTS_ARRAY[num][8];

		type = id.substr(0, id.indexOf('_'));
		content = "<div id='"+id+"' class='_content my_"+type+"' style='"+x+y+w+h+z+c+"' oncontextmenu='show_settings(this);return false'>"+
								"<div>"+
									"<span  onmousedown=drag_me(this) style='"+c+"'>X</span>"+
									"<input type='text' value='"+name+"' style='"+c+"'>"+
									"<button onclick=remove_element(this); style='float:right;width:9%;'>C</button>"+
								 "</div>";
		if 			(id.includes('note')) {	content += "<textarea style='width:100%;height:100%;"+c+"'>"+meta+"</textarea></div>"; last_note_id = id.substr(id.indexOf('_')+1);}
		else if (id.includes('image')){	content += "<img src='"+meta+"'></div>";	last_image_id = id.substr(id.indexOf('_')+1);}
		else if (id.includes('video')){	content += "<iframe width='auto' height='auto' src='https://youtube.com/embed/"+meta+"' frameborder='0' allowfullscreen data-meta="+meta+"></iframe></div>";	last_video_id = id.substr(id.indexOf('_')+1);}
									
		document.getElementById('viz_content').insertAdjacentHTML('beforeEnd',content);

	}


	function update_array()
	{
		for (i = 0; i < DATA_ELEMENTS_ARRAY.length; i++)
		{
			id = DATA_ELEMENTS_ARRAY[i][0];
			element = document.getElementById(id);
			DATA_ELEMENTS_ARRAY[i][1] = element.style.marginLeft;
			DATA_ELEMENTS_ARRAY[i][2] = element.style.marginTop;
			DATA_ELEMENTS_ARRAY[i][3] = element.style.width;
			DATA_ELEMENTS_ARRAY[i][4] = element.style.height;
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
		echo "<script> let DATA_ELEMENTS_ARRAY = $str;</script>";

			echo "<div id='visualization'>";

			echo "<div id='tool_bar'><span style='display:inline-block'></span><span style='display:inline-block' onclick='create_new_note(1)'>Note  </span>  <span style='display:inline-block' onclick='create_new_image(1)' >Photo</span>  <span style='display:inline-block' onclick='create_new_video(1)'>  Video</span><span style='display:inline-block' onclick='create_new_file(1)'>  File</span></div>";

			echo "<div id='viz_content'>";
			
			echo "<button onclick=update_data('".$id."'); style='position:fixed'>SAVE ALL</button>";
			echo "<script>show_data();</script>";
			echo "</div>";// viz_content
			echo "</div>";// vizualization
 ?>


  <script>
  	let scroll_x, scroll_y = 0;
  //document.getElementById('viz_content').addEventListener('scroll',scroll_viz_content); 
 function scroll_viz_content ()
 {

 }
	function set_parametrs(id)
	{
	//	document.getElementById(id).style.backgroundColor = document.getElementById('bkg_color').value;
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
				document.getElementById(id).children[1].dataset.meta = meta;
			}
		}

		document.getElementById(id).style.fontSize = document.getElementById('font_size').value +'px';
	  document.getElementById('set').remove();

	}
	function show_settings(element)
	{

		if(document.getElementById('ref_par')) document.getElementById('ref_par').remove();
		content = "<div id='set' style='position:fixed; z-index:1000; margin-left:400px; background-color:white;'>"+
								"<div>Парметры<button onclick=remove_element(this); style='float:right'>X</button></div>"+
								"Color:<br>"+
								"<div class='radio_color'>"+
									"<input type='radio' name='a' id='color_1' value='rgb(282,189,53)' checked/>Y"+
									"<input type='radio' name='a' id='color_2' value='rgb(0,64,64)'/>G"+
									"<input type='radio' name='a' id='color_3' value='rgb(210,31,60)'/>R"+
									"<input type='radio' name='a' id='color_4' value='rgb(184,15,10)'/>R2"+
									"<input type='radio' name='a' id='color_5' value='rgb(191,10,48)'/>R3"+
									"<input type='radio' name='a' id='color_6' value='rgb(50, 85, 171)'/>W1"+
									"<input type='radio' name='a' id='color_7' value='rgb(255, 252, 255)'/>W2"+

								"</div>"+"Размер Шрифта:<br>"+
								"<input id='font_size' type='range' min='8' max='24' step='2' value='16'>"+
								"<div><button onclick=zUp(\'"+element.id+"\')>Выше</button><button onclick=zFront(\'"+element.id+"\')>Наверх</button></div>"+
								"<div><button onclick=zDown(\'"+element.id+"\')>Ниже</button><button onclick=zEnd(\'"+element.id+"\')>Вниз</button></div>";
if(!element.id.includes('note')) { content = content + "Ссылка:<br><input id='ref' type='text' value='"+element.children[1].src+"'>"}
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