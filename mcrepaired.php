<?php
    session_start();
    require_once "pdo.php";
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
    if( $_SESSION['role'] != '2' )
    {
        die('ACCESS DENIED');
    }
    if(isset($_POST['cancel']))
    {
        header("Location: index.php");
        return;
    }

    if(isset($_GET['mc_id']))
    {
        $mc_id = $_GET['mc_id'];
    }
    elseif (isset($_POST['mc_id']))
    {
        $mc_id = $_POST['mc_id'];
    }


    if(isset($_POST['remarks']) )
    {
        if ( strlen($_POST['remarks']) < 1)
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: mcrepaired.php');
            return;
        }
        else
        {
                
                
                $stmt = $pdo->prepare('UPDATE complaint_book SET remarks = :rem, completed = 1 WHERE machine_id = :mid AND remarks IS NULL');
                $stmt->execute(array(':rem' => $_POST['remarks'], ':mid' => $mc_id));

                $_SESSION['success'] = "Machine Repaired";
                    header('Location: home.php');
                    return;
            

        }
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
               <?php if (isset($_SESSION['id'])&&$_SESSION['role']=='0') include "navbar.php"; 
                else if(isset($_SESSION['id'])&&$_SESSION['role']=='1')  include "navbar_faculty.php";
                else include "navbar_tech.php";?>
    <div class="container" id="content">
    <div class="page-header">
    <h1>MACHINE FIXED</h1>
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

    <form method="POST" action="mcrepaired.php" class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">Remarks</span>
    <input type="text" name="remarks" required class="form-control"> </div><br/>
    
    <input type="submit" value="Done" class="btn btn-info">
    <a class ="link-no-format" href="home.php"><div class="btn btn-my">Cancel</div></a>
    <input type="hidden" name="mc_id" value="<?= $_GET['mc_id'] ?>">
    
    </form>

    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>