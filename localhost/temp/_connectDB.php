<?php
    class Database{
        private $Server = 'localhost';//Название сервера
        private $DBLogin = 'root';//Логин
        private $DBPass = 'root';//Пароль
        private $DBName = 'q_book';//Имя базы данных
        public $conn;

        /**
         * Подключение к базе данных
         */
        public function getConnectDB(){
            $conn = new mysqli($this->Server, $this->DBLogin, $this->DBPass, $this->DBName);
            if($conn->connect_error){
                die("Ошибка: не удалось подключиться к базе данных");
            }
            return $conn;
        }

        /**
         * Проверка токена на наличие в базе данных
         */
        public function getToken($token, $db){
            $sql = "SELECT * FROM `user` WHERE `token` = '".$token."';"; 
            if(mysqli_num_rows($db -> query($sql)) > 0){
                return true;
            }
            return false;
        }

        /**
         * Удаление токена
         */
        public function postDeleteToken($token, $db){
            $sql = "UPDATE `user` SET `token` = NULL WHERE `user`.`token` = '$token';";
            $db -> query($sql);
        }

        /**
         * Авторизация
         */
        public function postAuth($email, $pass, $db){
            $sql = "SELECT * FROM `user` WHERE `email` = '$email' AND `password` = '$pass';"; 
            if(mysqli_num_rows($db -> query($sql)) > 0){
                $token = hash('sha256', rand());
                $_SESSION['token'] = $token;
                $sql = "UPDATE `user` SET `token` = '$token' WHERE `user`.`email` = '$email';"; 
                $db -> query($sql);
                return true;
            }
            return false;
        }

       /**
        * Регистрация
        */
        public function postRegistr($email, $pass, $db){
            $sql = "SELECT * FROM `user` WHERE `email` = '$email';"; 
            if(mysqli_num_rows($db -> query($sql)) > 0){
                return false;
            }
            $sql = "INSERT INTO `user` (`id`, `email`, `password`, `token`) VALUES (NULL, '$email', '$pass', NULL);";
            $db -> query($sql);
            return true;
        }

        /**
         * Получение id пользователя изх базы данных
         */
        public function getId($token, $db){
            $sql = "SELECT * FROM `user` WHERE `token` = '$token';"; 
            if(mysqli_num_rows($res = $db -> query($sql)) > 0){
                foreach($res as $value){
                    if($value['token'] === $_SESSION['token']){
                        return $value['id'];
                    }
                }
            }
            return false;
        }

        /**
         * Получение массива избранных книг из базы данных
         */
        public function getMyFavorite($id, $db){
            $sql = "SELECT * FROM `favorite` WHERE `id_user` = $id";
            $res = $db -> query($sql);
            $arr = array(array());
            $i = 0;

            if(mysqli_num_rows($res) < 1){
                return false;
            }

            while($row = mysqli_fetch_assoc($res)){
                $arr[$i] = $row;
                $i++;
            }
            return $arr;
        }

        /**
         * Получение массива своих книг из базы данных
         */
        public function getMyList($id, $db){
            $sql = "SELECT * FROM `mylist` WHERE `id_user` = $id";
            $res = $db -> query($sql);
            $arr = array(array());
            $i = 0;

            if(mysqli_num_rows($res) < 1){
                return false;
            }

            while($row = mysqli_fetch_assoc($res)){
                $arr[$i] = $row;
                $i++;
            }
            return $arr;
        }

        /**
         * Добавление новой книги в избранные или свои книги
         */
        public function postAddList($id, $uid, $table, $db){
            $sql = "INSERT INTO `$table` (`id`, `id_user`, `uid_book`) VALUES (NULL, $id, '$uid');";
            $res = $db -> query($sql);
            return true;
        }
        
        /**
         * Удаление книги из избранных или своих книг
         */
        public function postDeleteList($id, $uid, $table, $db){
            $sql = "DELETE FROM `$table` WHERE `id_user` = $id and `uid_book` = '$uid';";
            $res = $db -> query($sql);
            return true;
        }
    }
?>