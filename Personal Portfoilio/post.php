<?php require_once("Back\Db.php"); ?>
<?php require_once("Back\Functions.php"); ?>
<?php require_once("Back\Sessions.php"); ?>
<?php
    if(isset($_POST["Publish"])){
        $Title = $_POST["Title"];
        $Picture = $_FILES["Image"]["name"];
        $Target = "Upload/".basename($_FILES["Image"]["name"]);
        $Description = $_POST["description"];
        $Admin = "Dev";

        if(empty($Title)){
            $_SESSION["ErrorMessage"] = "Title can't be empty";
            redirect_to("Post.php");
        } elseif (empty($Description)) {
            $_SESSION["ErrorMessage"] = "Description can't be empty";
            redirect_to("Post.php");
        } elseif (strlen($Title)<5) {
            $_SESSION["ErrorMessage"] = "Title can't be less than 5 characters";
            redirect_to("Post.php");
        } elseif (strlen($Title)>49) {
            $_SESSION["ErrorMessage"] = "Title can't be greater than 50 characters";
            redirect_to("Post.php");
        } elseif (strlen($Description)>999) {
            $_SESSION["ErrorMessage"] = "Description can't be greater than 5 characters";
            redirect_to("Post.php");
        }

        global $conn;
        $sql = "INSERT INTO pp_post(title, image, text) VALUES(:title, :image, :post)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':title', $Title);
        $stmt->bindValue(':image', $Picture);
        $stmt->bindValue(':post', $Description);

        move_uploaded_file($_FILES["Image"]["tmp_name"], $Target);

        $Execute = $stmt->execute();
        if($Execute){
            redirect_to("index.php");
        }else{
            $_SESSION["ErrorMessage"] = "Unable to add post";
            redirect_to("post.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>
    <link rel="stylesheet" href="Post-style.css">
</head>

<body>
    <h1 class="Header">Form for New Post on Portfolio</h1>
    <section>
        <div class="error" style="color: white; width: max-content; background-color: brown; margin: auto; font-size: 2rem;">
        <?php
            echo ErrorMessage();
        ?>
        </div>
        <div>
            <div>

                <form class="InputForm" action="Post.php" method="post" enctype="multipart/form-data">
                    <div>
                        <div class="heading">
                            <h1>Add New Posts</h1>
                        </div>

                        <div>
                            <div class="form-group">
                                <label for="title"><span class="FieldInfo"> Post Title: </span></label>
                                <br>
                                <input type="text" name="Title" id="title" placeholder="Enter title of Post">
                            </div>

                            <div class="form-group">
                                <label for="Image"> Select Image: </label>
                                <div class="">
                                    <input type="file" name="Image" id="Image">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description"><span class=""> Post Description: </span></label>
                                <textarea name="description" id="description" rows="8" cols="80"></textarea>
                            </div>

                            <div class="form-group">
                                <div>
                                    <button name="Publish" class="Publish">Publish</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>

</html>