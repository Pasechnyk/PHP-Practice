<?php
global $pdo;
include($_SERVER["DOCUMENT_ROOT"] . "/config/connection_database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Show form for update
    $categoryId = isset($_POST['id']) ? $_POST['id'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';

    // Check for a new image
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $dir = "/images/";
        $image_name = uniqid() . ".jpg";
        $destination = $_SERVER["DOCUMENT_ROOT"] . $dir . $image_name;
        move_uploaded_file($imageTmpName, $destination);

        // Update with new image
        $stmt = $pdo->prepare("UPDATE categories SET name = :name, image = :image, description = :description WHERE id = :id");
        $stmt->bindParam(':image', $image_name);
    } else {
        // Update without image
        $stmt = $pdo->prepare("UPDATE categories SET name = :name, description = :description WHERE id = :id");
    }

    // Binding
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $categoryId);

    if ($stmt->execute()) {
        // Redirect to index page
        header("Location: /");
        exit;
    } else {
        echo "Error updating category!";
        var_dump($stmt->errorInfo()); // Show error details
    }
} else {
    // Display the form with values
    $categoryId = isset($_GET['id']) ? $_GET['id'] : null;
    if (!$categoryId) {
        header("Location: /");
        exit;
    }

    // Fetch the category details
    $stmt = $pdo->prepare("SELECT id, name, description, image FROM categories WHERE id = :id");
    $stmt->bindParam(':id', $categoryId);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$categoryId) {
        header("Location: /");
        exit;
    }
}
?>

<!DOCTYPE html>
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
        <h1 class="text-center">Редагувати категорію</h1>
        <form class="offset-md-2 col-md-6" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Назва</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $category['name']; ?>">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Фото</label>
                <input type="file" class="form-control" id="image" name="image">
                <img src="/images/<?php echo $category['image']; ?>" alt="Current Image" width="100">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Опис</label>
                <textarea rows="5" class="form-control" id="description" name="description"><?php echo $category['description']; ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Зберегти</button>
        </form>
    </div>
</main>

<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>
