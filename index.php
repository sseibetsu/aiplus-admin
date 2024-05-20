<?php
$mysqli = new mysqli('localhost', 'root', '', 'aiplus');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$order_by = 'created_at DESC';
if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    $order = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'ASC' : 'DESC';

    switch ($sort) {
        case 'name':
            $order_by = "name $order";
            break;
        case 'email':
            $order_by = "email $order";
            break;
        case 'date':
            $order_by = "created_at $order";
            break;
    }
}

$result = $mysqli->query("SELECT * FROM reviews WHERE status='approved' ORDER BY $order_by");
$reviews = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отзывы</title>
    <style>
        .review { border-bottom: 1px solid #ccc; padding: 10px 0; }
        .review img { max-width: 100px; display: block; margin-top: 10px; }
        .review.edited::after { content: ' (изменен администратором)'; color: red; }
    </style>
</head>
<body>

<h1>Отзывы</h1>
<div>
    <a href="?sort=date&order=desc">Сортировать по дате (по убыванию)</a> |
    <a href="?sort=date&order=asc">Сортировать по дате (по возрастанию)</a> |
    <a href="?sort=name&order=asc">Сортировать по имени (по возрастанию)</a> |
    <a href="?sort=name&order=desc">Сортировать по имени (по убыванию)</a> |
    <a href="?sort=email&order=asc">Сортировать по email (по возрастанию)</a> |
    <a href="?sort=email&order=desc">Сортировать по email (по убыванию)</a>
</div>

<?php foreach ($reviews as $review): ?>
    <div class="review<?php if ($review['is_edited']) echo ' edited'; ?>">
        <p><strong><?php echo htmlspecialchars($review['name']); ?></strong> (<?php echo htmlspecialchars($review['email']); ?>)</p>
        <p><?php echo nl2br(htmlspecialchars($review['message'])); ?></p>
        <?php if ($review['image']): ?>
            <img src="uploads/<?php echo htmlspecialchars($review['image']); ?>" alt="Изображение">
        <?php endif; ?>
        <p><small><?php echo $review['created_at']; ?></small></p>
    </div>
<?php endforeach; ?>

<h2>Оставить отзыв</h2>
<form id="feedbackForm" method="post" enctype="multipart/form-data">
    <p>
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" required>
    </p>
    <p>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
    </p>
    <p>
        <label for="message">Сообщение:</label>
        <textarea id="message" name="message" required></textarea>
    </p>
    <p>
        <label for="image">Картинка:</label>
        <input type="file" id="image" name="image" accept="image/*">
    </p>
    <p>
        <button type="submit">Отправить</button>
    </p>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#feedbackForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: 'submit_review.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response);
                    location.reload();
                }
            });
        });
    });
</script>
</body>
</html>
