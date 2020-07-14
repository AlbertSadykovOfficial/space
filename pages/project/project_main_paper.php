  <!--<script src="http://tinymce.cachefly.net/4.1/tinymce.min.js"></script> -->

  <script src="../../DOC_JS/test/vendor/tinymce.min.js"></script>
  <script src="../../DOC_JS/test/vendor/FileSaver.js"></script>
  <script src="../../DOC_JS/dist/html-docx.js"></script>
  <script type="text/javascript">
  
  function rebuild_deleted_divs($arr_len)
  {
  	let previous_div, div_content;
   if (document.getElementById('content_ifr').contentWindow.document.getElementById('look'))
   {
  	 x = document.getElementById('content_ifr').contentWindow.document.getElementById('look');
  	 if (x.childNodes.length != $arr_len) 
  	 {
  	 	x = document.getElementById('content_ifr').contentWindow.document;
		  	 	for (i=1; i<=$arr_len; i++)
		  	 	{
		  	 		if(x.getElementById(position[i-1]) == null)
		  	 		{
		  	 			previous_div = x.getElementById(position[i-2]);
		  	 			div_content ="<div id='"+position[i-1]+"' name='conclusion' title='Элемент: "+position[i-1]+"'><p>IF_YOU_WANT_TO__DELETE__THIS_PART DELETE_ id="+position[i-1]+" FROM__YOUR__LIST</p></div>";
		  	 			if(previous_div == null)
		  	 			{
		  	 				document.getElementById('content_ifr').contentWindow.document.getElementById('look').innerHTML = div_content + document.getElementById('content_ifr').contentWindow.document.getElementById('look').innerHTML;
		  	 			}else{
			  	 			previous_div.insertAdjacentHTML('afterend',div_content);
			  	 		}
			  	 			alert('Удалили элемент' + position[i-1]);
			  	 	}
		  	 	}//for
		  
  	 }
  	}//look
  	else
  	{
  	 	if ($arr_len == 1) 
  	 	{
  	 		inside_body = document.getElementById('content_ifr').contentWindow.document.body;
  	 			if (inside_body.innerHTML == '') 
  	 				div_content = "IF_YOU_WANT_TO__DELETE__THIS_PART DELETE_ id="+position[0]+" FROM__YOUR__LIST";
  	 			else
  	 				div_content = inside_body.innerHTML;

  	 		div_content ="<div contentEditable id=\"look\"><div id='"+position[0]+"' name='conclusion' title='Элемент: "+position[0]+"'><p>"+div_content+"</p></div>";
  	 	}else{
  	 		div_content = "<div contentEditable id=\"look\">";
  			for (i=1; i<=$arr_len; i++)
  	 		{
  	 			div_content += "<div id='"+position[i-1]+"' name='conclusion' title='Элемент: "+position[i-1]+"'><p></p><p>IF_YOU_WANT_TO__DELETE__THIS_PART DELETE_ id="+position[i-1]+" FROM__YOUR__LIST</p><p></p></div>";
  	 		}
  	 	}
  	 	document.getElementById('content_ifr').contentWindow.document.body.innerHTML = div_content + "</div>";
  	}
  }

  function upload_values($arr_len){
  		// Воссоздаем все удаленные div в нужном порядке.
      document.getElementById('content_ifr').contentWindow.document.body.innerHTML = document.getElementById('paper_content_span').innerHTML;
  		rebuild_deleted_divs($arr_len);

  		let pos_nums = '';
  		form =  "<form method='POST' action='project.php?project_id="+task_id+"' style='text-align: center;'>";
///////
  	 	if ($arr_len == 1) 
  	 	{
  			if(document.getElementById('content_ifr').contentWindow.document.getElementById(position[0]) == null)
  				save_this = document.getElementById('content_ifr').contentWindow.document.body.innerHTML;
  			else
  				save_this = document.getElementById('content_ifr').contentWindow.document.getElementById(position[0]).innerHTML;

  			form += "<textarea style='display:none' name=\""+position[0]+"\">"+save_this+"</textarea>\n";
  			pos_nums = position[0];
  		}else{
///////
    		for (let i = 1; i <= $arr_len; i++) 
    		{
    			save_this = document.getElementById('content_ifr').contentWindow.document.getElementById(position[i-1]).innerHTML;

    			if (save_this.length != conclusion_len[i-1]) 
    			{
    				//console.log(i, conclusion_len[i-1], save_this.length);
    				form += "<textarea style='display:none' name=\""+position[i-1]+"\">"+save_this+"</textarea>\n";
    				pos_nums += (position[i-1].toString()+',');
    			}
    		}
      pos_nums.slice(-1);
  	 }
  		
  		form +="Вы измените элементы с id:<br>"+
  					"<input type='text' name='conclusion' value='"+pos_nums+"'>\n"+
  					"<input type='submit' value='Upload'>\n"+
			  		"</form>";
			if (pos_nums == '') 
			{
				form = 'Вы ничего не изменили';
			}
			if (document.getElementsByClassName('description_window')[0] == null){
			document.getElementsByTagName('main')[0].insertAdjacentHTML('afterBegin',"<div class='description_window'><div id='here_please'></div></div>"); 	
			}
  		document.getElementById('here_please').innerHTML = 
	  		"<button onclick=\"(function(){document.getElementsByClassName('description_window')[0].remove(); document.getElementById('paper').style.filter = '';})();\">X</button>"
	  		+form;
  		document.getElementById('here_please').style.height = 'auto';
  		document.getElementById('paper').style.filter = 'blur(10px)';
  	}
  </script>

  <script>
  	show_bar = 1;
  	function change_bar_mode()
  	{
  		show_bar = !show_bar;
  		if (show_bar) 
  		{
  			document.getElementById('bar').style.width = '20%';
	  		document.getElementById('bar').style.height = '100%';
	  		//document.getElementById('bar').childNodes[0].style.display = 'none';
  		}else
  		{
	  		document.getElementById('bar').style.width = 	'4%';
	  		document.getElementById('bar').style.height = '4%';
	  		//document.getElementById('bar').childNodes[0].style.display = 'none';
  		}

  	}
  </script>

  <script>
     function change_div(id){
        document.getElementsByClassName('menu')[0].remove();
        document.getElementById('content_maker').style.display = 'block';
        document.getElementById('content_maker').children[0].children[0].dataset.id = id;
        document.getElementById('content_ifr').contentWindow.document.body.innerHTML = document.getElementById(id).children[0].innerHTML;
      }
      function hide_this(element)
      {
          document.getElementById(element.parentNode.parentNode.getAttribute('id')).style.display = 'none';
      }
      function apply(element){
        document.getElementById('content_maker').style.display = 'none';
        document.getElementById(element.dataset.id).children[0].innerHTML = document.getElementById('content_ifr').contentWindow.document.body.innerHTML;
      }
      function menu(id)
      {
        content = "<div class='menu'>"+
                  "<button onclick=\"document.getElementsByClassName(\'menu\')[0].remove()\">X</button>"+
                    "<ul>"+
                      "<li onclick=change_div(\'"+id+"\');>Изменить</li>"+
                      "<li onclick=show_files(\'"+id+"\');>Файлы</li>"+
                    "</ul>"+
                  "</div>";
        document.getElementById('paper').insertAdjacentHTML('afterBegin',content);
        document.getElementsByClassName('menu')[0].style = 'position:absolute; background-color: gray; margin-left:'+event.pageX+'px; '+'margin-top:'+event.pageY+'px';
      }
      function show_files(id)
      {
        content = 'Файлы элемента:'+
                  '<button onclick=\"document.getElementsByClassName(\'menu\')[0].remove()\">X</button>'+
                  '<div></div>';
        data = document.getElementById(id).getElementsByTagName('datatag');
       for (i=0; i<data.length; i++)
       {
          content+= "<div>"+
                        "<img src='"+get_src_image(data[i].children[1].innerHTML)+"' style='width:7%'>"+data[i].children[0].innerHTML+
                        "<a href='"+data[i].children[1].innerHTML+"'>Скачать</a>"+
                        "<button onclick='remove_file(this,"+id+","+i+");'>Удалить</button>"+
                    "</div>";
       }
       content += '<button onclick=add_file('+id+')>Добавить</button>';
        document.getElementsByClassName('menu')[0].innerHTML = content;
      }
      function add_file(id)
      {
        create_new_element('file','paper');
        document.getElementById('ref_par').children[2].remove();
        document.getElementById('ref_par').insertAdjacentHTML('beforeEnd',"<button onclick='create_href("+id+")'>ADD</button>");
      }
      function create_href(id)
      {
        ref_type = document.getElementById('ref_type').value;
        name = document.getElementById('ref').value;
        if      (ref_type == 'internet')    meta  = name
        else if (ref_type == 'self')        meta  = server_project_folder+'/' + document.getElementById('ref_dir').value + name;

        document.getElementById(id).insertAdjacentHTML('beforeEnd',"<datatag style='display:none'><dataname>"+name+"</dataname><datahref>"+meta+'</datahref></datatag>');
        document.getElementsByClassName('menu')[0].children[1].insertAdjacentHTML('afterBegin',"<div><img src='"+get_src_image(name)+"' style='width:7%'>"+document.getElementById('ref').value+"<a href='"+meta+"' download>Cкачать</a></div>");
        document.getElementById('ref_par').remove();
      }

      function remove_file(element,id,num)
      {
        element.parentNode.remove();
        document.getElementById(id).getElementsByTagName('datatag')[num].remove();
      }

  </script>

  <style>
    .hide
    {
      display: none;
    }
  </style>
  <script>
    
    function change_paper_mode(element)
    {
      mode_now = Number(element.dataset.mode);
      
      if (mode_now == 0) 
      {
        element.parentNode.children[1].dataset.pos = 1;
        element.dataset.mode = '1';
        element.innerHTML =  "Посмотреть весь отчет";
        element.parentNode.children[1].classList.remove('hide');

        for (i = 0; i < document.getElementsByClassName('paper_content_div').length; i++)
            document.getElementsByClassName('paper_content_div')[i].classList.add('hide');

         document.getElementsByClassName('paper_content_div')[0].classList.remove('hide');

      }
      else
      {
         element.dataset.mode = '0';
         element.innerHTML =  "По элементно";
         element.parentNode.children[1].classList.add('hide');

         for (i = 0; i < document.getElementsByClassName('paper_content_div').length; i++)
            document.getElementsByClassName('paper_content_div')[i].classList.remove('hide');
      }
    }

    function prev_page(element)
    {
      if (element.dataset.pos > 1)
      {
        document.getElementById(element.dataset.pos).classList.add('hide');
        element.dataset.pos = Number(element.dataset.pos) - 1;

        document.getElementById(element.dataset.pos).classList.remove('hide');
      }
    }
    function next_page(element)
    {

      if (element.dataset.pos < document.getElementsByClassName('paper_content_div').length)
      {
        document.getElementById(element.dataset.pos).classList.add('hide');
        element.dataset.pos = Number(element.dataset.pos) + 1;

         document.getElementById(element.dataset.pos).classList.remove('hide');
      }
    }    

  </script>
<?php

// header("Access-Control-Allow-Origin: *"); /////////////////////
		// Массив, который будет хранить данные о номере последнего элемента группы (для изменения порядка)
		$position_array = [];
		
		echo "<div id='paper' style='overflow-y:scroll;'>";

    $task_id = $_GET['project_id'];
	  $task_list = queryMySQL("SELECT * FROM report WHERE id LIKE '$task_id%' ORDER BY position");
		$task_list_length = $task_list->num_rows;

    echo "<div id='content_maker' style='display:none; position:fixed; width:60%; margin-left: 20%; margin-top:5%; overflow-x:hidden;'>".
            "<div style='background-color: #f0f0f0; border: 0.3px solid rgba(0,0,0,0.2)'>".
              "Редактирование Элемента".
              "<button onclick=apply(this)>Apply</button><button style='float:right' onclick=hide_this(this)>X</button>".
            "</div>".
            "<textarea  id='content' cols='60' rows='10'>\n</textarea>\n".
          "</div>";

    echo "<div>".
              "<span style='display:block; margin-left:20%; margin-top: 1%; text-align:center; background-color:white; width:60%;'>".
                "<button onclick='change_paper_mode(this)' data-mode='0'>По элементно</button>".
                "<div class='hide'>".
                  "<button onclick='prev_page(this.parentNode)'><<Назад</button>".
                  "<button onclick='next_page(this.parentNode)'>Вперед>></button>".
                "</div>".
              "</span>".
        "</div>";      

		echo "<span id='paper_content_span' style='display:inline-block;  width:60%; height: auto; margin-bottom: 100px; margin-left: 20%; background-color : whitesmoke; box-shadow: 0 14px 28px rgba(0,0,0,0.6), 0 10px 10px rgba(0,0,0,0.6); margin-top:1%'>\n";

    //<!--<form method='POST' action='project.php?project_id=$task_id'>-->";
		
		$conclusions_len = [];
		echo "<div  id=\"look\" style='font-size:20px; padding-top:50px; padding-left:80px; padding-right:50px; padding-bottom:70px;'>"; // 
			if ($task_list_length) 
			{
				$task_id = $_GET['project_id'].'_'; /// (1_2)->(1_2_)
				$task_len= strlen($task_id);
				for ($i=1; $i <= $task_list_length; $i++) 
				{ 
					$task = $task_list->fetch_array(MYSQLI_ASSOC);
					$output = str_replace("\"", "'", $task['conclusion']);
					$id_now = $task['position'];
          $elem_id =  $task['id'];
         
          if ($i == 1) 
          { 
            $last_id = $id_now;
            $prev_group_id = substr($elem_id,0,5);
           // echo "<script>document.getElementById('_$prev_group_id').dataset.startpos =$id_now;</script>"; 
          }else
          {
            if ($prev_group_id !== substr($elem_id,0,5)) 
            { 
            //echo "<script>document.getElementById('_$prev_group_id').dataset.finishpos =".($id_now-1).";</script>"; 
              echo "<script>document.getElementById('_$prev_group_id').dataset.poslen = ".($id_now-$last_id).";</script>"; 
              $prev_group_id = substr($elem_id,0,5);
              $last_id = $id_now;
            }
          }



					$conclusions_len[] = strlen($output);
          //style='border-top: 1px solid transparent; border-image: linear-gradient(to right, black 0%, transparent 50%, transparent  100%); border-image-slice: 1;'
					echo "<div id='$id_now' data-parent='$prev_group_id' class='paper_content_div' title='Элемент: $id_now' oncontextmenu=\"menu('$id_now'); return false; \">$output</div>\n"; //
					array_push($position_array,$id_now);
				}
      echo "<script>document.getElementById('_$prev_group_id').dataset.poslen =".($id_now-$last_id).";</script>";
		echo "</div>";
				$pos_arr_len = count($position_array)-1;
				$last_number_of_this_list = $position_array[$pos_arr_len];
				$a =  $position_array[0];
			}else
			{
				$pos_arr_len = 0;
				$a = 0;
				$last_number_of_this_list = 0;
			}
     // echo "<script>console.log("+$task_list_length+","+$output+","+$id_now+");</script>";

		echo "<!--</form>-->\n</span><br>\n";//</div><input type='submit' value='Upload'>\n
// Заполняем массив длины каждого conclusion		
		$task_id = $_GET['project_id'];
		echo "<script> let conclusion_len = [];\n position = []; task_id = '$task_id';\n";
		for ($i=0; $i < $task_list_length ; $i++) 
    { 
			echo "conclusion_len.push(".$conclusions_len[$i].");\n".
					 "position.push(".$position_array[$i].");";
		}
		echo "</script>\n";

// Очистка мусора (кода выводили данные нужно было юзать скрипт для присвоения значений, это порадило много ненужных тегов) 
    echo '<script>'.
            ' elements = document.getElementById(\'look\').getElementsByTagName(\'script\').length;'.
          'for(i=0; i < elements; i++){'.
          'document.getElementById(\'look\').getElementsByTagName(\'script\')[0].remove();'.
          '}</script>';
/*
				if ($task_list_length == 1) 
				{
         
					echo "<script>if(document.getElementById('content_ifr') != null){ ".
                "document.getElementById('content_ifr').contentWindow.document.body = \"$output\";\n".
                //"}else{ console.log(typeof document.getElementById('content_ifr').contentWindow.document.body);\n".
                "}</script>\n";
				}
*/
echo "<script>".
		//	"document.getElementById('firstNum').value = $a;".
			"let lastNum = $last_number_of_this_list;".
			"function set_num_input(x,b){". 
				"if(x==1){".
					"if(b == 0) n = 1; else n = $last_number_of_this_list;".
					"document.getElementById('last_elem_num_input').value = n;".
				"alert($last_number_of_this_list);}".
			"}".
			"</script><script>\n".
			"function set_delete_range(a){\n".
	//		"document.getElementById('delete_range').value = ('".$a."_".$last_number_of_this_list."');\n". // ;
			"}".
			"set_delete_range(1);".
			"</script>\n"	;

/* YЕ СРАБОТАЕТ, Я ВЫНЕС В ОТДЕЛЬНУЮ ОКНО УДАЛЕНИЕ
echo "<script>";
			for($i = 0; $i <= $pos_arr_len; $i++)
			{
				$last_number_of_this_list = $position_array[$i];
				echo "document.getElementById('_".(string)($i+1)."').dataset.position = ". $last_number_of_this_list ." ;";
				echo "document.getElementById('_".(string)($i+1)." pos').value = ". $last_number_of_this_list.' ;';
			} 
			$last_number_of_this_list = $position_array[$pos_arr_len];
echo	"</script>"
*/
?>	

</div> <!-- div paper -->

    <div id='bar' style="position: absolute; height:100%; width:20%; background-color: gray; float:left; z-index: 1001;">
    	<button onclick="change_bar_mode();">X</button>
    	<?php 
    		echo "<div style='text-align:center;'>\n".
    				 	"<button style='width:100px;' onclick='upload_values($task_list_length)'>Upload</button>\n".
    				 "</div>\n";
    	 ?>
			<div style="text-align: center;">
	
				<div class="page-orientation">
				    <span>Page orientation:</span><br>
				    <label><input type="radio" name="orientation" value="portrait" checked>Portrait</label>
				    <label><input type="radio" name="orientation" value="landscape">Landscape</label>
				  </div>
				  <button id="convert">EXPORT TO DOC</button>
				</div>
				
				<div class='list_master_small'>

				<p style="text-align: center;"> Level Structure:</p>
				<?php
				echo "$list_masters"; 
        echo "<button onclick='upload_form($project_id)'>Upload files</button>";
        ?>
				</div>
				<style>
					.list_master_small div
					{
						margin-left: 5%;
						width: 90%;
					}
					.list_master_small img
					{
						float: right	
					}
				</style>			  
				<div id="download-area"></div>
		</div>
	<script>
    tinymce.init({
      selector: '#content',
      plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen fullpage",
        "insertdatetime media table contextmenu paste"
      ],
      toolbar: "insertfile undo redo | styleselect | bold italic | " +
        "alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | " +
        "link image"
    });
    document.getElementById('convert').addEventListener('click', function(e) {
      e.preventDefault();
      convertImagesToBase64()
      // for demo purposes only we are using below workaround with getDoc() and manual
      // HTML string preparation instead of simple calling the .getContent(). Becasue
      // .getContent() returns HTML string of the original document and not a modified
      // one whereas getDoc() returns realtime document - exactly what we need.
      var contentDocument = tinymce.get('content').getDoc();
      var content = '<!DOCTYPE html>' + contentDocument.documentElement.outerHTML;
      var orientation = document.querySelector('.page-orientation input:checked').value;
      var converted = htmlDocx.asBlob(content, {orientation: orientation});

      saveAs(converted, 'UNIVERSE.docx');

      var link = document.createElement('a');
      link.href = URL.createObjectURL(converted);
      link.download = 'document.docx';
      link.appendChild(
        document.createTextNode('Click here if your download has not started automatically'));
      var downloadArea = document.getElementById('download-area');
      downloadArea.innerHTML = '';
      downloadArea.appendChild(link);
    });

    function convertImagesToBase64 () {
      contentDocument = tinymce.get('content').getDoc();
      var regularImages = contentDocument.querySelectorAll("img");
      var canvas = document.createElement('canvas');
      var ctx = canvas.getContext('2d');
      [].forEach.call(regularImages, function (imgElement) {
        // preparing canvas for drawing
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        canvas.width = imgElement.width;
        canvas.height = imgElement.height;

        ctx.drawImage(imgElement, 0, 0);
        // by default toDataURL() produces png image, but you can also export to jpeg
        // checkout function's documentation for more details
        var dataURL = canvas.toDataURL();
        imgElement.setAttribute('src', dataURL);
      })
      canvas.remove();
    }
  </script>