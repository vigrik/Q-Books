<?php
    /**
     * cURL запрос
     */
    function getCurlJson($url){
        $flag = true;//Флаг наличия ошибок

        $ch = curl_init();//Инициализация cURL сеанса

        /**
         * Установка опций запроса
         */
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);//Выполняем запрос

        if(curl_errno($ch)) {//проверяем наличие ошибок
            $flag = false;
        }

        curl_close($ch);//Закрываем cURL сеанс

        $arr = json_decode($res, false);//Декодируем JSON результат cURL запроса в object

        return array('books' => $arr, 'flag' => $flag);//Возвращаем наш объект и наличие ошибок
    }
?>