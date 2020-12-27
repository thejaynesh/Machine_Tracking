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
        header("Location: index.php");
        return;
    }

    if(isset($_GET['mc_id']))
    {
        $mc_id = $_GET['mc_id'];
       
        if ( strlen($_GET['mac_addr']) < 1 )
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: scrapmc.php');
            return;
        }
       
        else
        {
            $stmtread = $pdo->prepare("SELECT * FROM machine where MAC_ADDR = :xyz");
            $stmtread->execute(array(":xyz" => $_GET['mac_addr']));
            $row = $stmtread->fetch(PDO::FETCH_ASSOC);
            if ( $row === false )
            {
                $_SESSION['error'] = 'Could not load machine details<br>';
                header( 'Location: scrapmc.php' ) ;
                return;
            }
            else
            {

                $processor_no=$row['processor'];
                $ram_no=$row['ram'];
                $memory_no=$row['memory'];
                $monitor_no=$row['monitor'];
                $keyboard_no=$row['keyboard'];
                $mouse_no=$row['mouse'];
            }
        }
    }
       
    else if (isset($_POST['mc_id']))
    {
        $mc_id = $_POST['mc_id'];

        
        {
            $stmtread = $pdo->prepare("SELECT * FROM machine where MAC_ADDR = :xyz");
            $stmtread->execute(array(":xyz" => $_GET['mac_addr']));
            $row = $stmtread->fetch(PDO::FETCH_ASSOC);
            if ( $row === false )
            {
                $_SESSION['error'] = 'Could not load machine details<br>';
                header( 'Location: scrapmc.php' ) ;
                return;
            }
            else
            {

                $processor_no=$row['processor'];
                $ram_no=$row['ram'];
                $memory_no=$row['memory'];
                $monitor_no=$row['monitor'];
                $keyboard_no=$row['keyboard'];
                $mouse_no=$row['mouse'];
            }
        }
    }

    if(isset($_POST['submit']) )
    {
        
        {
            

            if(!isset($_POST['processor']))
            {
                $stmt = $pdo->prepare('UPDATE hardware SET state = -1 WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $processor_no));
            }
            else
            {

                $stmt = $pdo->prepare('UPDATE hardware SET state = 0 WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $processor_no));
            }
            
            if(!isset($_POST['ram']))
            {
                $stmt = $pdo->prepare('UPDATE hardware SET state = -1 WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $ram_no));
            }

            else
            {

                $stmt = $pdo->prepare('UPDATE hardware SET state = 0 WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $ram_no));
            }
            
            
            if(!isset($_POST['harddisk']))
            {
                $stmt = $pdo->prepare('UPDATE hardware SET state = -1 WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $memory_no));
            }

            else
            {

                $stmt = $pdo->prepare('UPDATE hardware SET state = 0 WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $memory_no));
            }
            
            
            if(!isset($_POST['monitor']))
            {
                $stmt = $pdo->prepare('UPDATE hardware SET state = -1 WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $monitor_no));
            }

            else
            {

                $stmt = $pdo->prepare('UPDATE hardware SET state = 0 WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $monitor_no));
            }
            
            if(!isset($_POST['keyboard']))
            {
                $stmt = $pdo->prepare('UPDATE hardware SET state = -1 WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $keyboard_no));
            }

           else
            {

                $stmt = $pdo->prepare('UPDATE hardware SET state = 0 WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $keyboard_no));
            }
            
            if(!isset($_POST['mouse']))
            {
                $stmt = $pdo->prepare('UPDATE hardware SET state = -1 WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $mouse_no));
            }

            else
            {

                $stmt = $pdo->prepare('UPDATE hardware SET state = 0 WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $mouse_no));
            }
            
            $stmt1 = $pdo->prepare('UPDATE machine SET state = "SCRAPPED" WHERE MAC_ADDR = :Mid');
            $stmt1->execute(array(':Mid' => $mc_id));
            


            }

            $_SESSION['success'] = "Machine Scrapped Successfully";
            header('Location: home.php');
            return;
        
        
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
    <h1>SELECT PARTS TO BE SAVED</h1>
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

    <form method="POST"  class="col-xs-5">

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
    <input type="hidden" name="mc_id" value="<?= $_GET['mc_id'] ?>">
    <div class="form-submit">
        
        <input type="submit" value="Add Machine" name="add" id="Submit" class="Submit">
        <input type="reset" value="Reset" class="submit" id="reset" name="reset" />
            </div>>
    
    
    </form>

    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
