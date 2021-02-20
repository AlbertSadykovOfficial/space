function findProjectByName(name, target)
{
    //Создаем функцию обработчик
    var Handler = function(Request)
    {
        document.getElementById(target).innerHTML = Request.responseText;
    } 
    
    name = 'project_name=' + name;
    //Отправляем запроc
    ajaxRequest("GET", 'http://space.com/js/AJAX/get_projects.php', name, Handler);
    //ajaxRequest("POST", 'get_projects.php', name, Handler); 
}

function findProjectByKey(name, target)
{
    //Создаем функцию обработчик
    var Handler = function(Request)
    {
        document.getElementById(target).innerHTML = Request.responseText;
    } 
    
    name = 'project_key=' + name;
    //Отправляем запроc
    ajaxRequest("GET", 'http://space.com/js/AJAX/get_projects.php', name, Handler);
    //ajaxRequest("POST", 'get_projects.php', name, Handler); 
} 