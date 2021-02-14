    function upload_msg(element,id)
    { 
        args = 'id=' + id + '&' + 'msg=' +element.value;
        //Отправляем запроc
        ajaxRequest("POST", 'https://www.space.com/js/AJAX/change_msg.php', args, function(){});
    }