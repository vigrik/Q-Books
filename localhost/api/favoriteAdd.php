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
    if(!isset($_GET['uid'])){//Проверяем наличие параметров URL
        echo "Отсутствует параметр: uid";
        exit;
    }

    $url = "https://www.googleapis.com/books/v1/volumes/".$_GET['uid'];//Формируем URl для cURL запроса
    $bufBook = getCurlJson($url);//Получаем ответ по запросу {arr, bool}
    if(isset($bufBook['books'] -> error)){//Если ошибка
        echo "Книги с таким id не существует";
        exit;
    }

    /**
     * Проверяем наличие книги в избранных
     */
    $flag = false;//Флаг налоичия книги

    $id = $database -> getId($_SESSION['token'], $db);//Получаем id пользователя по токену
    if($favorite = $database -> getMyFavorite($id, $db)){//Получаем массив всех избранных книг
        foreach($favorite as $value){
            if($value['uid_book'] == $_GET['uid']){//Если uid книги совпадает с uid в базе данных
                $flag = true;
            }
        }
    }

    if($flag){//Если книга уже есть в избранных книгах
        echo "Книга с введенным id уже в избранном";
        exit;
    }

    /**
     * Добавляем книгу в писок своих книг
     */
    if($database -> postAddList($id, $_GET['uid'], 'favorite', $db)){//Добавляем в базу данных
        echo "Книга успешно добавлена в избранное";
    }
    else{
        echo "Ошибка выполнения mysqli запроса";
    }
?>