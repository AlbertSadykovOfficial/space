/*
Функция посылки запроса к файлу на сервере
r_method  - тип запроса: GET или POST
r_path    - путь к файлу
r_args    - аргументы вида a=1&b=2&c=3...
r_handler - функция-обработчик ответа от сервера
*/
function ajaxRequest(r_method, r_path, r_args, r_handler)
{
    //Создаём запрос
    var Request = CreateRequest();
    
    //Проверяем существование запроса еще раз
    if (!Request)
    {
        return;
    }
    
    //Назначаем пользовательский обработчик
    Request.onreadystatechange = function()
    {
        if (this.readyState == 4)  //  0 = неинициализирован, 1 = загружается, 2 = загружен, 3 = в состоянии диалога и 4 = завершен 
        {          
            if (this.status == 200)          // Код статуса HTTP, возвращенный сервером (200 = Удачно)
            {            
                if (this.responseText != null)    //Данные, возвращенные сервером в текстовом формате         
                {              
                    r_handler(Request);
                }            
                else alert("Ошибка AJAX: Данные не получены")          
            }          
             else console.log( "Ошибка AJAX: " + this.statusText)  // Текст статуса HTTP, возвращенный сервером      
        }  
    }
    
    //Проверяем, если требуется сделать GET-запрос
    if (r_method.toLowerCase() == "get" && r_args.length > 0)
    r_path += "?" + r_args;
    
    //Инициализируем соединение
    Request.open(r_method, r_path, true);
    
    if (r_method.toLowerCase() == "post")
    {
        //Устанавливаем заголовок
        Request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //Посылаем запрос
        Request.send(r_args);
    }
    else
    {
        //Посылаем нуль-запрос, т.к GET
        Request.send(null);
    }
} 


    function CreateRequest()  
    {    
        try // Браузер не относится к семейству IE?    
        {       // Да        
                var request = new XMLHttpRequest()    
        }
        catch(e1){        
            try // Это IE 6+?
            {   // Да            
                request = new ActiveXObject("Msxml2.XMLHTTP")        
            }        
            catch(e2){            
                try // Это IE 5?            
                {   // Да                
                    request = new ActiveXObject("Microsoft.XMLHTTP")            
                }            
                catch(e3) // Данный браузер не поддерживает AJAX            
                {                
                    request = false            
                }           
            }    
        }    
        return request  
    }