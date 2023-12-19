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
    if(isset($_GET['uid'])){//Проверяем наличие параметров URL
        $url = "https://www.googleapis.com/books/v1/volumes/".$_GET['uid'];//Формируем url для cURL запроса
    }
    else{
        echo "Отсутсвует параметр: uid";
        exit;
    }

    /**
     * Проверка id книги на ее существование
     */
    $bufBook = getCurlJson($url);//Получаем ответ по запросу {arr, bool}
    
    if(isset($bufBook['books'] -> error)){//Если ошибка
        echo "Книги с таким id не существует";
        exit;
    }
    
    /**
     * Формирование ответа
     */
    if($bufBook['flag']){//Если cURL запрос сработал без ошибок
        $book = array();
        $book['uid'] = $bufBook['books'] -> id;//id book
        $book['title'] = $bufBook['books'] -> volumeInfo -> title;//title
        $book['description'] = $bufBook['books'] -> volumeInfo -> description;//description
        $book['authors'] = $bufBook['books'] -> volumeInfo -> authors;//authors
        
        /**
         * Проверка наличия книги в избранных
         */
        $flag = false;//Флаг наличия книги в избранных

        $id = $database -> getId($_SESSION['token'], $db);//Получаем id пользователя по токену
        if($favorite = $database -> getMyFavorite($id, $db)){//Получаем массив всех избранных книг(arr/false)
            foreach($favorite as $value){
                if($value['uid_book'] == $book['uid']){//Если uid книги совпадает с uid в базе данных
                    $book['favorite'] = 1;
                    $flag = true;
                }
            }
        }
        
        if(!$flag){//Если книги нет в избранных
            $book['favorite'] = 0;
        }

        echo "<pre>";
        print_r($book);//Выводим результат
        echo "</pre>";
    }
    else{
        echo "Ошибка выполнения запроса<br>";
    }

?>