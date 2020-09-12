<?php
    session_start();
    require_once "pdo.php";
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
    if( $_SESSION['id'] != '0' )
    {
        die('ACCESS DENIED');
    }
    if(isset($_POST['cancel']))
    {
        header("Location: home.php");
        return;
    }


    if ( isset($_POST['delete']) )
    {
        $stmt = $pdo->query("SHOW TABLES");
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
           $sql = "DELETE FROM ".$row['Tables_in_computers']." WHERE 1";
            echo $sql;
            $stmt = $pdo->query($sql);
        }
        $_SESSION['success'] = 'Data Reset';
       // header( 'Location: home.php' ) ;
        //return;
    }
    
?>
<html>
<head>
    <title>Machine Tracking</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1">

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="style5.css">
    <style>
        .input-group-addon {
        min-width:150px;
        text-align:left;
    }
    </style>
</head>
<body>
    <div class="wrapper">
    <?php include "navbar.php" ;?>
      <div class="container-fluid row" id="content">
        <div class="page-header">
    <h1>CONFIRM RESET</h1>
    </div>
    <?php
        if ( isset($_SESSION['error']) )
        {
            echo('<p style="color: red;">'.$_SESSION['error']."</p>\n");
            unset($_SESSION['error']);
        }
        if ( isset($_SESSION['success']))
        {
            echo('<p style="color: green;">'.$_SESSION['success']."</p>\n");
            unset($_SESSION['success']);
        }
    ?>

    <form method="post" class="col-xs-5">
    <p>This will delete all the data. Are you sure you want to continue?</p>
    <input type="hidden" name="id"
    value="<?= $id?>"
    />
    <input type="submit" name="delete" value="Delete" class="btn btn-info">
    <input type="submit" name="cancel" value="Cancel" class="btn btn-info">
    <input type="hidden" name="id" value="<?= $_POST['id'] ?>">
    </p>
    </form>

    </div>
    </div>
    <script type="text/javascript" src="script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>