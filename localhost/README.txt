Файл со скриптом базы данных: /MySQL/q_book.sql

Параметры подключения к базе данных: /temp/_connectDB.php (строки 3-6)

-Авторизация{
    Вид URL:
    http://localhost/api/auth?email=ВашЕмаил&password=ВашПароль
    Пример URL:
    http://localhost/api/auth?email=test@tes.t&password=qwerty12345
}

-Выход из аккаунта{
    Вид URL:
    http://localhost/api/exit
}

-Регистрация{
    Вид URL: 
    http://localhost/api/registr?email=ВашЕмаил@em.ail&password=ВашПароль&confirm_password=ПовторитеПароль
    Пример URL:
    http://localhost/api/registr?email=email@em.ail&password=qwert1234&confirm_password=qwert1234
}

-Получить список книг{
    Вид URL:
    http://localhost/api/books
    Также можно использовать параметр page=номер страницы, к примеру:
    http://localhost/api/books?page=4
}

-Поиск книги{
    Вид URL: 
    http://localhost/api/books?q=строкаПоиска
    Пример URL:
    http://localhost/api/books?q=Шторм
    http://localhost/api/books?q=Ирина
    Также можно использовать параметр page=номер страницы, к примеру:
    http://localhost/api/books?q=Ирина&page=2
}

-Получить книгу{
    Вид URL:
    http://localhost/api/book?uid=idКниги
    Пример URL: 
    http://localhost/api/book?uid=fj5bDwAAQBAJ
}

-Вывести свой список книг{
    Вид URL:
    http://localhost/api/myList
}

-Добавить книгу в свой список{
    Вид URL:
    http://localhost/api/myListAdd?uid=idКниги
    Пример URL: 
    http://localhost/api/myListAdd?uid=fj5bDwAAQBAJ
}

-Удалить книгу из своего списка{
    Вид URL:
    http://localhost/api/MyListDelete?uid=idКниги
    Пример URL: 
    http://localhost/api/MyListDelete?uid=fj5bDwAAQBAJ
}

-Вывести список избранных книг{
    Вид URL:
    http://localhost/api/favorite
}

-Добавить книгу в избранное{
    Вид URL:
    http://localhost/api/favoriteAdd?uid=idКниги
    Пример URL: 
    http://localhost/api/favoriteAdd?uid=fj5bDwAAQBAJ
}

-Удалить книгу из избранных{
    Вид URL:
    http://localhost/api/favoriteDelete?uid=idКниги
    Пример URL: 
    http://localhost/api/favoriteDelete?uid=fj5bDwAAQBAJ
}