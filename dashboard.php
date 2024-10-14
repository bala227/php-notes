<?php

    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: index.php"); 
        exit();
    }
    $curruser = $_SESSION['username'];

    $conn = mysqli_connect('localhost','root','','auth');

    $sq = "SELECT * FROM usernotes WHERE username = '$curruser'";
    
    $res = $conn->query($sq);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Document</title>
</head>
<style>
    body{
        font-family: "Poppins",sans-serif;
        background-image: url("dashimg.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        width: 100%;
        height: 100vh;
    }
    h1{
        text-align: center;
    }
    .nav{
        display: flex;
        justify-content: space-between;
        padding: 10px 50px;
    }
    .nav h2 span{
        text-transform: uppercase;
        font-weight: bold;
        color: red;
        letter-spacing: 0.2cap;
    }
    .nav a{
        font-size: 18px;
        background-color: red;
        height: 30px;
        padding: 12px 30px;
        text-decoration: none;
        color: white;
        border-radius: 45px;
        cursor: pointer;
        box-shadow: rgba(14, 30, 37, 0.12) 0px 2px 4px 0px, rgba(14, 30, 37, 0.32) 0px 2px 16px 0px;
    }
    .container{ 
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        margin-top: 50px;
    }
    .container input{
        font-size: 15px;
        border: none;
        padding: 10px;
        width: 500px;
        border-radius: 20px;
        box-shadow: rgba(14, 30, 37, 0.12) 0px 2px 4px 0px, rgba(14, 30, 37, 0.32) 0px 2px 16px 0px;
    }
    .container input:focus{
        outline: none;
    }
    .container label{
        font-size: 18px;
    }
    .plus{
        cursor: pointer;
        font-size: 30px;
        background-color: transparent;
        border: none;
        border-radius: 100px;
        color: green;
    }
    .note{
        margin-left: 200px;
        margin-top: 50px;
        width: 550px;
    }
    .note form{
        font-size: 18px;
        background-color:aliceblue;
        padding: 10px 25px;
        border-radius: 20px;
        list-style: none;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 15px;
        box-shadow: rgba(14, 30, 37, 0.12) 0px 2px 4px 0px, rgba(14, 30, 37, 0.32) 0px 2px 16px 0px;
    }
    .notecontain{
        display: inline-block;
    }
    .delete{
        color: red;
        cursor: pointer;
        border: none;
        font-size: 17px;
        background-color: transparent;
    }
</style>
<body>
    <div class="nav">
    <h2>Welcome , <span><?php echo htmlspecialchars($curruser);?></span></h2>
    <a href="logout.php">Logout</a>
    </div>
    <form class="container" action="" method="post">
        <label>Add Notes :</label>
        <input type="text" name="notes">
        <button name="submit" type="submit" class="plus"><i class="fa-solid fa-circle-plus"></i></button>
    </form>
    <div class="note">
            <?php
                while( $row = $res->fetch_assoc()){
                    $noteid = $row['notes'];
                    echo '<div class="notecontain"> 
                            <form method="post" action="">
                                <input type="hidden" name="noteid" value="' . $noteid . '">'
                                . $row['notes'] .
                                '<button class="delete" type="submit" name="delete">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div><br>';
                }
            ?>
    </div>
</body>
</html>

<?php
    if(isset($_POST['submit'])){
        $notes = $_POST['notes'];

        $conn = mysqli_connect('localhost','root','','auth');

        $check = "SELECT * FROM usernotes WHERE username = '$curruser' AND notes = '$notes'";
        $result = $conn->query($check);

        if($result->num_rows <= 0){
            $sql = "INSERT INTO usernotes (username,notes) VALUES ('$curruser','$notes')";
            $conn->query($sql);
        }
        $conn->close();
        
    }

    if(isset($_POST["delete"])){
        $noteid = $_POST['noteid'];
        $conn = mysqli_connect('localhost','root','','auth');

        $check = "SELECT * FROM usernotes WHERE username = '$curruser' AND notes = '$noteid'";  
        $result = $conn->query($check);

        if( $result->num_rows == 1){

            $sql = "DELETE FROM usernotes WHERE username = '$curruser' AND notes = '$noteid'";
            $conn->query($sql);
            
        }
        $conn->close();
    }
?>