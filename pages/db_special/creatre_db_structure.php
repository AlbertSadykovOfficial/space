<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Настройка базы данных</title>
</head>
<body>

    <h3>Setting up...</h3>

<?php 
    require_once('../templates/functions.php');

    createTable('members',
                'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user VARCHAR(16),
                pass VARCHAR(16),
                nickname VARCHAR(30),
                profession VARCHAR(100),
                company VARCHAR(100),
                location VARCHAR(100),
                href TINYTEXT,
                msg TINYTEXT,
                INDEX(user(6))'
								);
	createTable('projects',
                'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100),
                pass VARCHAR(16),
                admin INT,
                is_private TINYINT(1)' 
								);		
    createTable('list',
                'id VARCHAR(100),
                case_ VARCHAR(100),
                case_description TEXT(1000),
                executor VARCHAR(16),
                board MEDIUMTEXT,
                canvas MEDIUMTEXT,
                deadline TINYTEXT,
                INDEX(id(6))'
								);
// Поставил ограничение Отчета одного Дела на 18 страниц !!!! шрифтом 14
		createTable('report',
                "id VARCHAR(100),
                name VARCHAR(100) DEFAULT 'unknown'
                position TINYINT,
                conclusion TEXT(65536),
                INDEX(id(6))"
								);		
		createTable('users_projects',
                'user_id INT,
                project_id INT,
                is_blocked TINYINT(1)'
								);
		createTable('permission',
                'project_id INT,
                project_key VARCHAR(7)'
								);
?>

        <br>...done.
    </body>
</html>