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
     * Проверка параметров URL
     */
    $page = 0;//Номер страницы (по умолчанию 0)

    if(isset($_GET['page'])){//Если номер страницы есть в параметрах URL
        $page = isset($_GET['page']);//Меняем номер страницы
    }
    if(isset($_GET['q'])){//Если параметр поиска (q) есть в параметрах URL
        $url = "https://www.googleapis.com/books/v1/volumes?q={".$_GET['q']."}&maxResults=40&startIndex=".$page * 40;//Выводим результаты поиска, но только одну страницу
    }
    else{
        $url = "https://www.googleapis.com/books/v1/volumes?q={%D1%81%D1%82%D1%80%D0%BE%D0%BA%D0%B0}&maxResults=40&startIndex=".$page * 40;//Выводим все, но только одну страницу
    }

    /**
     * Получение списка книг
     */
    $bufBooks = getCurlJson($url);//Получаем ответ по запросу {arr, bool}

    if(isset($bufBooks['books'] -> error)){//Если ошибка
        echo "По вашему запросу ничего не найдено";
        exit;
    }

    if($bufBooks['flag']){//Если cURL запрос сработал без ошибок
        $booksAll = $bufBooks['books'] -> items;
        $books = array(array());
        $i = 0;

        foreach($booksAll as $value){
            $books[$i]['uid'] = $value -> id;//id book
            $books[$i]['title'] = $value -> volumeInfo -> title;//title
            $books[$i]['authors'] = $value -> volumeInfo -> authors;//authors
            $books[$i]['description'] = $value -> volumeInfo -> description;//description
            $i++;
        }
        unset($books[0]);//Удаляем пустой элемент (да да костыль{ понять и простить :) })

        echo "<pre>";
        print_r($books);//Выводим результат
        echo "</pre>";
    }
    else{
        echo "Ошибка выполнения запроса<br>";
    }

?>