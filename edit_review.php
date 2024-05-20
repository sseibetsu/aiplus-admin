<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'aiplus');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $mysqli->real_escape_string($_POST['name']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $message = $mysqli->real_escape_string($_POST['message']);

    $mysqli->query("UPDATE reviews SET name='$name', email='$email', message='$message', is_edited=1, updated_at=NOW() WHERE id=$id");

    header('Location: admin.php');
    exit;
}

$result = $mysqli->query("SELECT * FROM reviews WHERE id=$id");
$review = $result->fetch_assoc();

if (!$review) {
    die('Отзыв не найден.');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование отзыва</title>
</head>
<body>

<h1>Редактирование отзыва</h1>
<form method="post">
    <p>
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($review['name']); ?>" required>
    </p>
    <p>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($review['email']); ?>" required>
    </p>
    <p>
        <label for="message">Сообщение:</label>
        <textarea id="message" name="message" required><?php echo htmlspecialchars($review['message']); ?></textarea>
    </p>
    <p>
        <button type="submit">Сохранить</button>
    </p>
</form>

<a href="admin.php">Назад</a>

</body>
</html>
