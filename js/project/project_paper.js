let show_bar = 1;
/*

				Показать / Спрятать Меню
		
*/
  	function change_bar_mode()
  	{
  		show_bar = !show_bar;
  		if (show_bar) 
  		{
  			document.getElementById('bar').style.width = '20%';
	  		document.getElementById('bar').style.height = '100%';
  		}else
  		{
	  		document.getElementById('bar').style.width = 	'4%';
	  		document.getElementById('bar').style.height = '4%';
  		}

  	}

/*

					Смена Типа отобранеия отчета + Их переключение
*/

    
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

/*

					Восстановление удаленных ячеек
					(А может не надо? Они же уже не удаляются)
none
*/

  
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

		  	 		div_content ="<div id=\"look\"><div id='"+position[0]+"' name='conclusion' title='Элемент: "+position[0]+"'><p>"+div_content+"</p></div>";
		  	 	}else{
		  	 		div_content = "<div id=\"look\">";
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

  	 	if ($arr_len == 1) 
  	 	{
  			if(document.getElementById('content_ifr').contentWindow.document.getElementById(position[0]) == null)
  				save_this = document.getElementById('content_ifr').contentWindow.document.body.innerHTML;
  			else
  				save_this = document.getElementById('content_ifr').contentWindow.document.getElementById(position[0]).innerHTML;

  			form += "<textarea class='hide' name=\""+position[0]+"\">"+save_this+"</textarea>\n";
  			pos_nums = position[0];
  		}else{
    		for (let i = 1; i <= $arr_len; i++) 
    		{
    			save_this = document.getElementById('content_ifr').contentWindow.document.getElementById(position[i-1]).innerHTML;

    			if (save_this.length != conclusion_len[i-1]) 
    			{
    				//console.log(i, conclusion_len[i-1], save_this.length);
    				form += "<textarea class='hide' name=\""+position[i-1]+"\">"+save_this+"</textarea>\n";
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

			if (document.getElementsByClassName('description_window')[0] == null)
			{
				document.getElementsByTagName('main')[0].insertAdjacentHTML('afterBegin',"<div class='description_window'><div id='here_please'></div></div>"); 	
			}
  		
  		document.getElementById('here_please').innerHTML = 
	  		"<button onclick=\"(function(){document.getElementsByClassName('description_window')[0].remove(); document.getElementById('paper').style.filter = '';})();\">X</button>"
	  		+form;

  		document.getElementById('here_please').style.height = 'auto';
  		document.getElementById('paper').style.filter = 'blur(10px)';
	  }

 /*

			Файлы, прикрепленные к проектам + меню
		
*/

		function change_div(id){
      document.getElementsByClassName('menu')[0].remove();
      document.getElementById('content_maker').classList.remove('hide');
      document.getElementById('content_maker').children[0].children[0].dataset.id = id;
      document.getElementById('content_ifr').contentWindow.document.body.innerHTML = document.getElementById(id).children[0].innerHTML;
    }

    function hide_this(element)
    {
        document.getElementById(element.parentNode.parentNode.getAttribute('id')).classList.add('hide');
    }

    function apply(element){
      document.getElementById('content_maker').classList.add('hide');
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

      document.getElementById(id).insertAdjacentHTML('beforeEnd',"<datatag class='hide' ><dataname>"+name+"</dataname><datahref>"+meta+'</datahref></datatag>');
      document.getElementsByClassName('menu')[0].children[1].insertAdjacentHTML('afterBegin',"<div><img src='"+get_src_image(name)+"' style='width:7%'>"+document.getElementById('ref').value+"<a href='"+meta+"' download>Cкачать</a></div>");
      document.getElementById('ref_par').remove();
    }

    function remove_file(element,id,num)
    {
      element.parentNode.remove();
      document.getElementById(id).getElementsByTagName('datatag')[num].remove();
    }

 /*

			TINY MCE  и Экспорт содержимого в Word
		
*/
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