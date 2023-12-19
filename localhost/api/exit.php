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
        $tkn = $database -> postDeleteToken($_SESSION['token'], $db);//Удаляем токен в базе данных
        echo "Вы вышли";
        session_destroy();//Очищаем сессию
    }
    else{
        echo "Вы не авторизованны";
    }
?>
