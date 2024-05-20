<?php
$mysqli = new mysqli('localhost', 'root', '', 'aiplus');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$name = $mysqli->real_escape_string($_POST['name']);
$email = $mysqli->real_escape_string($_POST['email']);
$message = $mysqli->real_escape_string($_POST['message']);
$image = '';

if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $allowed_types = ['image/jpeg', 'image/gif', 'image/png'];
    $max_size = 1048576; // 1MB

    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        die('Недопустимый формат изображения.');
    }

    if ($_FILES['image']['size'] > $max_size) {
        die('Файл слишком большой.');
    }

    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $image = time() . '_' . basename($_FILES['image']['name']);
    $target_file = $upload_dir . $image;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        die('Ошибка при загрузке файла.');
    }
}

$query = "INSERT INTO reviews (name, email, message, image) VALUES ('$name', '$email', '$message', '$image')";

if ($mysqli->query($query)) {
    echo 'Отзыв успешно отправлен на модерацию.';
} else {
    echo 'Ошибка: ' . $mysqli->error;
}

$mysqli->close();
?>
