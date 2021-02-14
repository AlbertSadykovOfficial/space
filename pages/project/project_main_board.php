<?php
    $id = $_GET['project_id'];
    if (isset($_POST['board_elements'])) 
    {
        $x = $_POST['board_elements'];
        queryMySQL("UPDATE list SET board = '$x' WHERE id = '$id'");
        $_POST['board_elements'] = null;
    }

    if (isset($_POST['canvas_elements'])) 
    {
        $x = $_POST['canvas_elements'];
        queryMySQL("UPDATE list SET canvas = '$x' WHERE id = '$id'");
        $_POST['canvas_elements'] = null;
    }

    $result = queryMySQL("SELECT board, canvas FROM list WHERE id = '$id'")->fetch_array(MYSQLI_ASSOC);
    $board  = $result['board'];
    $canvas = $result['canvas'];

    echo "<div id='visualization'>".
            "<div id='minimized_apps' class='add_element_menu hide'><span>Свернутые Элементы</span><button class='close_button' onclick='minimized_apps_panel();'><img src='$server_content_folder/close.png'></button></div>".

                "<div id='tool_bar' class='hide'>".
                    "<span style='display:inline-block' onclick=create_new_element('note','viz_content')><img src='$server_content_folder/tool_note.png'></span>".
                    "<span style='display:inline-block' onclick=create_new_element('image','viz_content')><img src='$server_content_folder/tool_photo.png'></span>".
                    "<span style='display:inline-block' onclick=create_new_element('video','viz_content')><img src='$server_content_folder/tool_video.png'></span>".
                    "<span style='display:inline-block' onclick=create_new_element('audio','viz_content')><img src='$server_content_folder/tool_music.png'></span>".
                    "<span style='display:inline-block' onclick=create_new_element('file','viz_content')><img src='$server_content_folder/tool_file.png'></span>".
	                    "<span style='display:inline-block' onclick=minimized_apps_panel()><img src='$server_content_folder/minimize.png'></span>".
                "</div>".

                "<div id='board_map' class='hide' onmousedown=jump()></div>".

                "<div id='viz_content'>".
                    "<div class='slide_pannel hide'>".
                        "<span id='slide_to_left'   onmouseover=expand('to_left')></span>".
                        "<span id='slide_to_right'  onmouseover=expand('to_right')><p style='display:none'>NEW</p></span>".
                        "<span id='slide_to_top'	  onmouseover=expand('to_top')></span>".
                        "<span id='slide_to_bottom' onmouseover=expand('to_bottom')><p style='display:none'>NEW</p></span>".
                "</div>".
                
                "<button id='update_board_btn' onclick=board_synchronization('".$id."'); style='display:none'></button>";

    echo "<div id='board_div' style='width:100%; height:100%;' ondragover='drag_start(event); return false;' ondrop='stop_drag(); return false;'>";
    
//onchange=console.log(this.value)//
    echo "<div id='drop_area' class='is_drag hide'></div>";
    if ($board == null || $board == '') echo "<script> let DATA_ELEMENTS_ARRAY = [];</script>";
    else 																echo "<script> let DATA_ELEMENTS_ARRAY = $board;</script>";

    if ($canvas == null || $canvas == '') echo "<script> let CANVAS_ELEMENTS = [];</script>";
    else 																	echo "<script> let CANVAS_ELEMENTS = $canvas;</script>";
			
        echo "</div>";
    echo 	"</div>".// viz_content
    "</div>";// vizualization
 ?>