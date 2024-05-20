<?php
session_start();
$mysqli = new mysqli('localhost', 'root', '', 'aiplus');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if (isset($_POST['login'])) {
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = md5($mysqli->real_escape_string($_POST['password']));

    $result = $mysqli->query("SELECT * FROM users WHERE username='$username' AND password='$password'");

    if ($result->num_rows == 1) {
        $_SESSION['admin'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Неправильный логин или пароль.';
    }
}

if (!isset($_SESSION['admin'])) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Вход для администратора</title>
    </head>
    <body>
    <h1>Вход для администратора</h1>
    <?php if (isset($error)) echo '<p style="color:red;">' . $error . '</p>'; ?>
    <form method="post">
        <p>
            <label for="username">Логин:</label>
            <input type="text" id="username" name="username" required>
        </p>
        <p>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
        </p>
        <p>
            <button type="submit" name="login">Войти</button>
        </p>
    </form>
    </body>
    </html>
    <?php
    exit;
}

$result = $mysqli->query("SELECT * FROM reviews ORDER BY created_at DESC");
$reviews = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Администрирование отзывов</title>
    <style>
        .review { border-bottom: 1px solid #ccc; padding: 10px 0; }
        .review img { max-width: 100px; display: block; margin-top: 10px; }
        .review.edited::after { content: ' (изменен администратором)'; color: red; }
    </style>
</head>
<body>

<h1>Администрирование отзывов</h1>
<a href="logout.php">Выйти</a>

<?php foreach ($reviews as $review): ?>
    <div class="review<?php if ($review['is_edited']) echo ' edited'; ?>">
        <p><strong><?php echo htmlspecialchars($review['name']); ?></strong> (<?php echo htmlspecialchars($review['email']); ?>)</p>
        <p><?php echo nl2br(htmlspecialchars($review['message'])); ?></p>
        <?php if ($review['image']): ?>
            <img src="uploads/<?php echo htmlspecialchars($review['image']); ?>" alt="Изображение">
        <?php endif; ?>
        <p><small><?php echo $review['created_at']; ?></small></p>
        <p>
            <?php if ($review['status'] == 'approved'): ?>
                <a href="edit_review.php?id=<?php echo $review['id']; ?>">Редактировать</a>
                <form method="post" action="moderate_review.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $review['id']; ?>">
                    <button type="submit" name="delete">Удалить</button>
                </form>
            <?php else: ?>
                <form method="post" action="moderate_review.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $review['id']; ?>">
                    <button type="submit" name="approve">Принять</button>
                    <button type="submit" name="reject">Отклонить</button>
                </form>
            <?php endif; ?>
        </p>
    </div>
<?php endforeach; ?>

</body>
</html>
