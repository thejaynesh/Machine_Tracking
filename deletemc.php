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
    if(isset($_POST['mac_addr']) )
    {
        if ( strlen($_POST['mac_addr']) < 1 )
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: deletemc.php');
            return;
        }
        else
        {
            $flag=0;
            $to;
            if(empty($_POST['mac_addr2']))
                $to=$_POST['mac_addr'];
            else
                $to=$_POST['mac_addr2'];
            for($i=$_POST['mac_addr'];$i<=$to;$i++)
            {
                $stmt = $pdo->prepare('SELECT *,COUNT(*) FROM machine WHERE MAC_ADDR = :mac_addr');
                $stmt->execute(array(':mac_addr' => $i));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row['COUNT(*)'] !== '0')
                {
                    $stmtd = $pdo->prepare('DELETE FROM hardware WHERE hardware_id = :hid');
                    $stmtd->execute(array(':hid' => $row['processor']));
                    $stmtd = $pdo->prepare('DELETE FROM hardware WHERE hardware_id = :hid');
                    $stmtd->execute(array(':hid' => $row['ram']));
                    $stmtd = $pdo->prepare('DELETE FROM hardware WHERE hardware_id = :hid');
                    $stmtd->execute(array(':hid' => $row['memory']));
                    $stmtd = $pdo->prepare('DELETE FROM hardware WHERE hardware_id = :hid');
                    $stmtd->execute(array(':hid' => $row['monitor']));
                    $stmtd = $pdo->prepare('DELETE FROM hardware WHERE hardware_id = :hid');
                    $stmtd->execute(array(':hid' => $row['keyboard']));
                    $stmtd = $pdo->prepare('DELETE FROM hardware WHERE hardware_id = :hid');
                    $stmtd->execute(array(':hid' => $row['mouse']));


                     $stmt = $pdo->prepare('DELETE FROM machine WHERE mac_addr = :mac_addr');
                        $stmt->execute(array(':mac_addr' => $i));
                    $_SESSION['success'].="Machine".$i." deleted Successfully<br>";
                }
                else
                {
                    $_SESSION['error'] = "Other Machines does not Exists<br>";
                    $flag++;
                }
            }
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
            <!-- Sidebar Holder -->
       <?php if (isset($_SESSION['id'])&&$_SESSION['role']=='0') include "navbar.php"; 
                else if(isset($_SESSION['id'])&&$_SESSION['role']=='1')  include "navbar_faculty.php";
                else include "navbar_tech.php";?>
   <div class="container-fluid row" id="content">

    <div class="page-header">
    <h1>DELETE MACHINE in range</h1>
    </div>
    <div id="error" style="color: red; margin-left: 90px; margin-bottom: 20px;">
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

    <form method="POST" action="deletemc.php" class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">MACHINE No. (from)</span>
    <input type="text" name="mac_addr" class="form-control" required="" placeholder="Starting machine id" id="mcs" onchange="Number('mcs')"> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">MACHINE No. (to)</span>
    <input type="text" name="mac_addr2" class="form-control" placeholder="Ending machine id" id="mce" onchange="Number('mce')"> </div><br/>


    <input type="submit" value="Delete Machine" class="btn btn-info">
    <a class ="link-no-format" href="home.php"><div class="btn btn-my">Cancel</div></a>
    </form>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
