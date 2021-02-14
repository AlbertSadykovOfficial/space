function ReadFile(filename, container)
{
    //Создаем функцию обработчик
    var Handler = function(Request)
    {
        document.getElementById('container').innerHTML = Request.responseText;
    }
    
    //Отправляем запроc
    ajaxRequest("GET", filename, "maxim", Handler);
    ajaxRequest("POST", filename, "1_5", Handler); 
} 
