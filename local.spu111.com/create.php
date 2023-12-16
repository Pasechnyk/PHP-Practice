<?php global $pdo;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $imageTmpName = $_FILES['image']['tmp_name']; //Темпова назва файлу, коли він передаєтсья на сервер
    $dir="/images/";
    $image_name = uniqid().".jpg";
    //шлях для збереження файлу
    $destination = $_SERVER["DOCUMENT_ROOT"].$dir.$image_name;
    // Move the uploaded file to the destination
    move_uploaded_file($imageTmpName, $destination);
    include($_SERVER["DOCUMENT_ROOT"] . "/config/connection_database.php");
    // SQL query to insert data
    $sql = "INSERT INTO categories (name, image, description) VALUES (:name, :image, :description)";
    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);
    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':image', $image_name);
    $stmt->bindParam(':description', $description);

    // Execute the statement
    $stmt->execute();
    header("Location: /");
    exit;
}
?>

<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Головна сторінка</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/site.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="/js/script.js"></script>
</head>
<body>

<?php include($_SERVER["DOCUMENT_ROOT"] . "/_header.php"); ?>

<main>
    <div class="container">
        <h1 class="text-center">Додати категорію</h1>
        <form class="offset-md-3 col-md-6" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Назва</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Фото</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>

            <!-- Image preview -->
            <div class="mb-3">
                <label for="preview-image" class="form-label">Попередній перегляд:</label>
                <img id="preview-image" class="img-thumbnail" alt="Preview Image" style="cursor: pointer;">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Опис</label>
                <textarea rows="5" class="form-control" id="description" name="description"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Додати</button>
        </form>
    </div>
</main>

<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>