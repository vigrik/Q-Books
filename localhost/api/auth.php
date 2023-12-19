<?php
    //Создаем или возобновляем сесанс сессии
    session_start();

    //Подключение методов взаимодействия с базой данных
    include_once("../temp/_connectDB.php");

    /**
     * Подключение к базе данных
     */
    $database = new Database();
    $db = $database -> getConnectDB();

    /**
     * Проверка токена
     */
    $tkn = false;//Флаг токена

    if(isset($_SESSION['token'])) {//Если токен есть в сессии
        $tkn = $database -> getToken($_SESSION['token'], $db);//Проверка токена на совпадение (true/false)
    }

    if($tkn){//Если токен есть в сессии и он совпадает с токеном в базе данных
        echo "Вы уже авторизованы";
        exit;
    }

    /**
     * Авторизация
     */
    if (isset($_GET['email']) and isset($_GET['password'])) {//Проверяем наличие параметров URL
        if($database -> postAuth($_GET['email'], $_GET['password'], $db)){//Выполняем запрос авторизации (true/false)
            echo "Вы успешно авторизовались";
        }
        else{
            echo "Ошибка авторизации";
        }
	} 
    else{
        echo "Отсутствуют параметры: email or password";
    }
?>