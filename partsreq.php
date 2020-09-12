<?php
    session_start();
    require_once "pdo.php";
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
    if( $_SESSION['role'] == '0' )
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


    if(isset($_POST['submit']) )
    {
        if ( isset($_POST['processor']) || isset($_POST['ram']) || isset($_POST['harddisk']) || isset($_POST['keyboard']) || isset($_POST['mouse']) || isset($_POST['monitor']))
        {
            
            $stmtch = $pdo->prepare("SELECT * from temp where machine_id = :mid");
            $stmtch->execute(array(':mid'=> $mc_id));
            $rowch = $stmtch->fetch(PDO::FETCH_ASSOC);
            if($rowch == false)
            {
                $stmti = $pdo->prepare("INSERT INTO temp (machine_id, completed) VALUES (:mid, 0)");
                $stmti->execute(array(':mid' => $mc_id));

            }

            if(isset($_POST['processor']))
            {
                $stmt = $pdo->prepare('UPDATE complaint_book SET processor = 1 WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }
            else
            {   
                $stmt = $pdo->prepare('UPDATE temp SET processor = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));

                $stmt = $pdo->prepare('UPDATE complaint_book SET processor = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            if(isset($_POST['ram']))
            {
                $stmt = $pdo->prepare('UPDATE complaint_book SET ram = 1 WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            else
            {   
                $stmt = $pdo->prepare('UPDATE temp SET ram = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
                $stmt = $pdo->prepare('UPDATE complaint_book SET ram = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            if(isset($_POST['harddisk']))
            {
                $stmt = $pdo->prepare('UPDATE complaint_book SET harddisk = 1 WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            else
            {   
                $stmt = $pdo->prepare('UPDATE temp SET harddisk = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
                $stmt = $pdo->prepare('UPDATE complaint_book SET harddisk = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            if(isset($_POST['monitor']))
            {
                $stmt = $pdo->prepare('UPDATE complaint_book SET monitor = 1 WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            else
            {   
                $stmt = $pdo->prepare('UPDATE temp SET monitor = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
                $stmt = $pdo->prepare('UPDATE complaint_book SET monitor = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            if(isset($_POST['keyboard']))
            {
                $stmt = $pdo->prepare('UPDATE complaint_book SET keyboard = 1 WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            else
            {   
                $stmt = $pdo->prepare('UPDATE temp SET keyboard = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
                $stmt = $pdo->prepare('UPDATE complaint_book SET keyboard = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            if(isset($_POST['mouse']))
            {
                $stmt = $pdo->prepare('UPDATE complaint_book SET mouse = 1 WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }


            else
            {   
                $stmt = $pdo->prepare('UPDATE temp SET mouse = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
                $stmt = $pdo->prepare('UPDATE complaint_book SET mouse = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            $stmtd = $pdo->prepare("UPDATE complaint_book SET DOPR = date('y-m-d') WHERE machine_id = :mid");
            $stmtd->execute(array(':mid' => $mc_id));

            $stmtch = $pdo->prepare("SELECT * from temp where machine_id = :mid");
            $stmtch->execute(array(':mid'=> $mc_id));
            $rowch = $stmtch->fetch(PDO::FETCH_ASSOC);
            if($rowch == false)
            {
                $stmti = $pdo->prepare("INSERT INTO temp (machine_id, completed) VALUES (:mid, 0)");
                $stmti->execute(array(':mid' => $mc_id));

            }
            else
            {
                $stmtd = $pdo->prepare("UPDATE temp SET completed = 0 WHERE machine_id = :mid");
                $stmtd->execute(array(':mid' => $mc_id));
                
            }

            $_SESSION['success'] = "Request Sent";
            header('Location: home.php');
            return;
        }
        else
        {
            $_SESSION['error'] = "At least one option should be selected";
            header('Location: partsreq.php');
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

    <form method="POST" action="partsreq.php" class="col-xs-5">

    <div class="checkbox">
    <label><input type="checkbox" name="processor" value="processor">Processor</label>
    </div>
    <div class="checkbox">
    <label><input type="checkbox" name="ram" value="ram">Ram</label>
    </div>
    <div class="checkbox">
    <label><input type="checkbox" name="harddisk" value="harddisk">Harddisk</label>
    </div>
    <div class="checkbox">
    <label><input type="checkbox" name="monitor" value="monitor">Monitor</label>
    </div>
    <div class="checkbox">
    <label><input type="checkbox" name="mouse" value="mouse">Mouse</label>
    </div>
    <div class="checkbox">
    <label><input type="checkbox" name="keyboard" value="keyboard">Keyboard</label>
    </div>

    <input type="submit" name="submit" value="Submit" class="btn btn-info">
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
