<?php
    //Создаем или возобновляем сесанс сессии     
    session_start();

    /**
     * Подключение методов
     */
    include_once("../temp/_connectDB.php");//Методы взаимодействия с базой данных
    include_once("../temp/_curlFunction.php");//Методы взаимодействия со сторонней API по средствам cURL

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

    if(!$tkn){//Если токена нет в сессии или он не совпадает с токеном в базе данных
        //Перенаправление в авторизацию
        header("Location: auth");
        exit;
    }
    
    /**
     * Проверка id книги на ее существование
     */
    $id = $database -> getId($_SESSION['token'], $db);//Получаем id пользователя по токену
    if(!$list = $database -> getMyFavorite($id, $db)){//Если у этого пользователя нет книг в избранных
        echo "Ваш список избранных книг пустой";
        exit;
    }

    /**
     * Формирование ответа
     */
    $my_list = array();
    $i = 0;

    foreach($list as $value){//Проверяем все избранные книги на совпадение
        $url = "https://www.googleapis.com/books/v1/volumes/".$value['uid_book'];//Формируем url для cURL запроса
        $bufBook = getCurlJson($url);//Получаем ответ по запросу {arr, bool}
        if($bufBook['flag']){//Если нет ошибки формируем массив
            $book = array();
            $book['uid'] = $bufBook['books'] -> id;//id book
            $book['title'] = $bufBook['books'] -> volumeInfo -> title;//title
            $book['description'] = $bufBook['books'] -> volumeInfo -> description;//description
            $book['authors'] = $bufBook['books'] -> volumeInfo -> authors;//authors
            $my_list[$i] = $book;
        }
        $i++;
    }
    echo "<pre>";
    print_r($my_list);//Выводим список избранных книг
    echo "</pre>";
?>