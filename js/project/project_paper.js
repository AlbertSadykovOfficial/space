let show_bar = 1;
/*

				Показать / Спрятать Меню
		
*/
    function change_bar_mode()
    {
        show_bar = !show_bar;
        if (show_bar) 
        {
            document.getElementById('target').classList.remove('hide');
            document.getElementById('show_structure_btn').classList.remove('hide');
            document.getElementById('bar').style.height = '100%';
            document.getElementById('bar').style.width = '20%';
            document.getElementsByClassName('bar_button')[0].style.width = '8%';
            document.getElementsByClassName('bar_button')[0].style.backgroundColor = 'none';
            document.getElementById('bar').style.backgroundColor = 'rgb(33,33,33)'; 
        }else
        {
            document.getElementById('structure_pannel').classList.remove('hide');
            show_strucutre();

            document.getElementById('target').classList.add('hide');
            document.getElementById('show_structure_btn').classList.add('hide');
            document.getElementById('bar').style.height = 'auto';
            document.getElementById('bar').style.width = 'auto';
            document.getElementsByClassName('bar_button')[0].style.width = '2%';
            document.getElementsByClassName('bar_button')[0].style.backgroundColor = 'rgb(39,39,39)';
            document.getElementById('bar').style.backgroundColor = 'rgba(0,0,0,0)';
        }
    }

/*
      
      Показать структуру

*/
    function goto(id)
    {
        if(document.getElementsByClassName('page_navigator')[0].getElementsByTagName('button')[0].dataset.mode == '1')
        {
            document.getElementsByClassName('page_navigator')[0].getElementsByTagName('button')[0].click();
        }
        document.getElementById('paper').scrollTo(0,document.getElementById(id).offsetTop-20);
    }

    function show_strucutre()
    {
        if (document.getElementById('structure_pannel').classList.contains('hide')) 
        {
            document.getElementById('structure_pannel').classList.remove('hide');
            document.getElementById('show_structure_btn').children[1].classList.remove('hide');
            document.getElementById('target').classList.add('hide');

            elements = document.getElementsByClassName('paper_content_div');
            
            if (document.getElementsByClassName('menu').length != 0)
            {
                document.getElementsByClassName('menu')[0].remove();
            }

            content = '<div class=\'menu structure_menu\'>'+
                  "<span>Найдено:</span>";
            for (i=0; i<elements.length;i++)
            {
                content += '<div onclick=goto('+elements[i].getAttribute('id')+')>'+elements[i].dataset.name+'</div>';
            }
            
            content += '</div>';
            document.getElementById('structure_pannel').innerHTML = content;
        }else{
            document.getElementById('structure_pannel').classList.add('hide');
            document.getElementById('show_structure_btn').children[1].classList.add('hide');
            document.getElementById('target').classList.remove('hide');
        }
    }

    function find_by_name()
    {
        request = document.getElementById('show_structure_btn').getElementsByTagName('input')[0].value.toLowerCase();
        elements = document.getElementsByClassName('paper_content_div');
      
        content = '<div class=\'menu structure_menu\'>'+ //menu
                "<span>Найдено:</span>";
        for (i=0; i<elements.length;i++)
        {
            if ((elements[i].dataset.name).toLowerCase().includes(request))
            content += '<div onclick=goto('+elements[i].getAttribute('id')+')>'+elements[i].dataset.name+'</div>';
        }
        content += '</div>';
        document.getElementById('structure_pannel').innerHTML = content;
    }

/*

					Смена Типа отображения отчета + Их переключение
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

    function upload_values($arr_len)
    {
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

    function change_content(id)
    {
        document.getElementsByClassName('menu')[0].remove();
        document.getElementById('content_maker').classList.remove('hide');
        document.getElementById('content_maker').children[0].children[0].dataset.id = id;
        document.getElementById('content_ifr').contentWindow.document.body.innerHTML = document.getElementById(id).children[0].innerHTML;
        set_content_maker_style();
    }

    function hide_this(element)
    {
        document.getElementById(element.parentNode.parentNode.getAttribute('id')).classList.add('hide');
    }

    function apply(element)
    {
        id      = document.getElementById(element.dataset.id).dataset.id;
        document.getElementById('content_maker').classList.add('hide');

        console.log(document.getElementById(element.dataset.id).innerHTML.indexOf('<div>'));
        if (document.getElementById(element.dataset.id).innerHTML.indexOf('<div>') == 0)
            document.getElementById(element.dataset.id).children[0].innerHTML = document.getElementById('content_ifr').contentWindow.document.body.innerHTML;
        else
        {
            document.getElementsByTagName('body')[0].insertAdjacentHTML('afterBegin',"<div id='TECHNICAL_DIV'></div>");

            len = document.getElementById(element.dataset.id).getElementsByTagName('datatag').length;
            for (i = 0; i < len; i++)
            {
                  document.getElementById('TECHNICAL_DIV').append(document.getElementById(element.dataset.id).getElementsByTagName('datatag')[0]);
            }
            document.getElementById(element.dataset.id).innerHTML = 
            '<div>'+
                document.getElementById('content_ifr').contentWindow.document.body.innerHTML+
            '</div>'+
            document.getElementById('TECHNICAL_DIV').innerHTML;
        
            document.getElementById('TECHNICAL_DIV').remove();
        }
        //console.log((document.getElementById(element.dataset.id).innerHTML).replaceAll("'",'(!1qts!)').replaceAll('"','(!2qts!)').replaceAll('&nbsp',''));
        args = 'element_id=' + id + '&' + "data_conclusion="+(document.getElementById(element.dataset.id).innerHTML).replaceAll("'",'(!1qts!)').replaceAll('"','(!2qts!)').replaceAll('&nbsp',' ');
        ajaxRequest("POST", 'https://www.space.com/js/AJAX/paper_synchronization.php', args, function(){});
    }

    function menu(id)
    {
        content = "<div class='menu'>"+
                "<span></span><button class='close_button' onclick=\"document.getElementsByClassName(\'menu\')[0].remove()\"><img src='"+server_content_folder+"/close.png'></button>"+
                    "<ul>"+
                        "<li onclick=change_content(\'"+id+"\');>Изменить</li>"+
                        "<li onclick=show_files(\'"+id+"\');>Файлы</li>"+
                    "</ul>"+
                "</div>";
        document.getElementById('paper').insertAdjacentHTML('afterBegin',content);
        document.getElementsByClassName('menu')[0].style = 'margin-left:'+event.pageX+'px; '+'margin-top:'+event.pageY+'px';
    }   

    function show_files(id)
    {
        content = '<span>Файлы элемента:</span>'+
                "<button class='close_button' onclick=\"document.getElementsByClassName(\'menu\')[0].remove()\"><img src='"+server_content_folder+"/close.png'></button>"+
                '<div></div>';
        data = document.getElementById(id).getElementsByTagName('datatag');
        
        for (i=0; i<data.length; i++)
        {
            content+= "<div>"+
                          "<a href='"+data[i].children[1].innerHTML+"' download>"+data[i].children[0].innerHTML+"</a>"+
                          "<button onclick='remove_file(this,"+id+","+i+");'><img src='"+server_content_folder+"/delete_icon.png'></button>"+
                        "</div>";
        }

        content += "<button class='add_button' onclick=add_file("+id+")>+ ADD NEW</button>";
        document.getElementsByClassName('menu')[0].innerHTML = content;
        document.getElementsByClassName('menu')[0].classList.add('file_manager');
    }

    function add_file(id)
    { 
        create_new_element('file','paper');
        document.getElementById('add_element_menu').getElementsByClassName('add_button')[0].remove();
        document.getElementById('add_element_menu').insertAdjacentHTML('beforeEnd',"<button class='add_button' onclick=\"create_href('"+id+"')\">ADD</button>");
    }

    function create_href(id)
    {
        ref_type = document.getElementById('ref_type').value;
        name = document.getElementById('ref').value;
        if      (ref_type == 'internet')    meta  = name
        else if (ref_type == 'self')        meta  = server_project_folder + '/' + document.getElementById('ref_dir').value + name;

        document.getElementById(id).insertAdjacentHTML('beforeEnd',"<datatag class='hide'><dataname>"+name+"</dataname><datahref>"+meta+'</datahref></datatag>');
        document.getElementsByClassName('menu')[0].
                    getElementsByClassName('add_button')[0].
                    insertAdjacentHTML('beforeBegin',
                    "<div>"+
                        "<a href='"+meta+"' download>"+ document.getElementById('ref').value + "</a>"+
                        "<button onclick='remove_file(this,"+id+","+i+");'><img src='"+server_content_folder+"/delete_icon.png'></button>"+
                    "</div>");
        document.getElementById('add_element_menu').remove();
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

    document.getElementById('convert').addEventListener('click', function(e) 
    {
  /// -----
        document.getElementById('content_ifr').contentWindow.document.body.innerHTML = document.getElementById('paper_content_span').innerHTML;
        elems = document.getElementById('content_ifr').contentWindow.document.body.getElementsByTagName('datatag');
      
      // Плюсуем i потму что после каждой итерации elems.length уменьшается на 1, а i увеличивается
        for (i = 0; i < elems.length+i; i++)
        {
          elems[0].remove();
      }
  /// --- 
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

    function convertImagesToBase64 () 
    {
        contentDocument = tinymce.get('content').getDoc();
        var regularImages = contentDocument.querySelectorAll("img");
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        [].forEach.call(regularImages, function (imgElement) 
        {
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

    function set_content_maker_style()
    {
        document.getElementById('mceu_33').style.height = '100vh';
        document.getElementById('content_ifr').style.height = '100%';
        document.getElementById('mceu_34').innerHTML = 'Ой, что-то пошло не так, откройте и закройте консоль разработчика (обычно F12)';
    }
    function fix_content_maker()
    {
        document.getElementById('mceu_33').style.height = '0vh';
        document.getElementById('content_ifr').style.height = '100%';
        set_content_maker_style();
    }

    var evt = new KeyboardEvent('keydown', {'keyCode':122, 'which':122});
    document.dispatchEvent(evt);