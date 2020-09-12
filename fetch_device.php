<?php
    session_start();
    require_once "pdo.php";
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
    if( $_SESSION['role'] != '0' )
    {
        die('ACCESS DENIED');
    }
    if(isset($_POST['cancel']))
    {
        header("Location: home.php");
        return;
    }
    if(isset($_POST['get_option']))
    {
        $stmt=$pdo->prepare("SELECT spec FROM specification JOIN name ON specification.name_id = name.name_id WHERE name.name=:name");
        $stmt->execute(array(":name"=>$_POST['get_option']));
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
                echo "<option>".$row['spec']."</option>";
        }

        
    }
?>