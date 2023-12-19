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

    /**
     * Проверяем наличие книги в списке своих книг
     */
    $flag = false;//Флаг налоичия книги

    $id = $database -> getId($_SESSION['token'], $db);//Получаем id пользователя по токену
    if($myList = $database -> getMyList($id, $db)){//Получаем массив всех своих книг
        foreach($myList as $value){
            if($value['uid_book'] == $_GET['uid']){//Если uid книги совпадает с uid в базе данных
                $flag = true;
            }
        }
    }

    if(!$flag){//Если книги нет в списке своих книг
        echo "Книги с введенным id нету в вашем списке";
        exit;
    }

    /**
     * Удаляем книгу из списка своих книг
     */
    if($database -> postDeleteList($id, $_GET['uid'], 'myList', $db)){//Удаляет из базы данных
        echo "Книга успешно удалена из ивашего списка";
    }
    else{
        echo "Ошибка выполнения mysqli запроса";
    }
?>