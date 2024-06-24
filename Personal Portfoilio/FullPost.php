<?php require_once("Back\Db.php"); ?>
<?php require_once("Back\Functions.php"); ?>
<?php require_once("Back\Sessions.php"); ?>
<?php
$SearchPost = $_GET["id"];
if (isset($_POST["delete"])) {
    global $conn;
    $sql = "DELETE FROM pp_post WHERE id='$SearchPost'";

    $Execute = $conn->query($sql);
    if ($Execute) {
        $_SESSION["SuccessMessage"] = "Post Successfully Deleted";
        redirect_to("index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="FullCard.css">
    <title>View Full Post</title>
</head>

<body>
    <h1 class="header">Achievements of Devadyumna Vijay Kumar</h1>
    <?php
    global $conn;
    $sql = "SELECT * FROM pp_post WHERE id='$SearchPost'";
    $stmt = $conn->query($sql);

    while ($DataRows = $stmt->fetch()) {
        $id = $DataRows["id"];
        $title = $DataRows["title"];
        $image = $DataRows["image"];
        $description = $DataRows["text"];
    ?>

        <div class="FullCard">
            <img src="Upload/<?php echo $image; ?>" alt="">
            <h1><?php echo $title; ?></h1>
            <hr>
            <p><?php echo $description; ?></p>
        </div>
    <?php } ?>

    <form action="FullPost.php?id=<?php echo $id; ?>" method="post">
        <button class="delete" name="delete" type="submit">Delete</button>
    </form>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>