    function upload_msg(element,id)
    { 
        args = 'id=' + id + '&' + 'msg=' +element.value;
        //Отправляем запроc
        ajaxRequest("POST", 'http://space.com/js/AJAX/change_msg.php', args, function(){});
    }