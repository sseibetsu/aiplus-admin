# aiplus-admin
Панель админа с Базой Данных(БД) для отзывов.
Данный код был написал для решения "Тестового Задания".

Для проверки работоспособности созданного мною кода я использовал две программы - XAMPP и phpMyAdmin.
В файл htdocs,  находящийся в директории XAMPP, я добавил все файлы, содержащие код. Далее я включил модули Apache и MySQL. Открыл [phpMyAdmin](http://localhost/phpmyadmin/), создал БД "aiplus" с импортом моего кода aiplus.sql. Далее я перешёл на страницу [localhost](http://localhost/index.php). После создания отзыва, я перехожу на страницу администрирования - http://localhost/admin.php. После "разрешения" на отзыв, его можно будет увидеть на главной странице. Если отзыв имеет фотографию, то она будет сохраняться в папке "uploads". Если админ решит отклонить отзыв, то он удаляется, а если примет, то действия изменятся из "принять" и "отклонить" в "редактировать" и "удалить".
В силу того, что задание тестовое, я ограничился дизайнингом страницы и сделал её голой, базовой.
На выполнение было потрачено 1.5-2 часа.
