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
    if(isset($_GET['h_id']))
    {
        $hid=$_GET['h_id'];
    }
    if(isset($_POST['hardware_id']) )
    {
        if ( strlen($_POST['hardware_id']) < 1 )
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: gorepairmc.php?h_id='.$_GET['h_id']);
            return;
        }
        else
        {

            $_POST['date']=date('y-m-d',strtotime($_POST['date']));
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM hardware WHERE hardware_id = :hid');
            $stmt->execute(array(':hid' => $_POST['hardware_id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row['COUNT(*)'] !== '0')
            {
                $stmt = $pdo->prepare('SELECT * FROM hardware WHERE hardware_id = :hid');
                $stmt->execute(array(':hid' => $_POST['hardware_id']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $stmt = $pdo->prepare('SELECT COUNT(*) FROM device_repair_history WHERE hardware_id = :mid AND final_date = "0000-00-00"');
                $stmt->execute(array(':mid' => $hid));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row['COUNT(*)'] !== '0')
                {
                    $_SESSION['error'] = "Machine already in Repair";
                    header('Location: gorepairmc.php'.$_GET['h_id']);
                    return;
                }


                 $stmt = $pdo->prepare('UPDATE hardware SET state = 3 WHERE hardware_id = :mid');
                    $stmt->execute(array(':mid' => $hid));

                $stmt = $pdo->prepare('UPDATE hardware_position SET final_date = :fdate WHERE hardware_id = :mid AND final_date = "000-00-00"');
                    $stmt->execute(array(':mid' => $hid, ':fdate' => date('y-m-d')));

                $stmt = $pdo->prepare('INSERT INTO device_repair_history (hardware_id, initial_date, final_date) VALUES (:hid, :idate, "0000-00-00")');
                    $stmt->execute(array(':hid' => $hid, ':idate' => date('y-m-d')));

                $stmt = $pdo->prepare('UPDATE hardware_complaint_book SET work_for = :wf WHERE hardware_id = :hid AND work_for IS NULL');
                $stmt->execute(array(':hid' => $hid, ':wf' => $_POST['work_for']));

                $_SESSION['success'] = "Hardware sent to Repair Successfully<br>";
                header('Location: home.php');
                return;
            }
            else
            {
                $_SESSION['error'] = "Hardware does not Exists<br>";
                    header('Location: gorepairmc.php');
                    return;
            }

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

       <div class="container-fluid row" id="content">

    <div class="page-header">
    <h1>REPAIR HARDWARE</h1>
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

    <form method="POST" class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">Hardware ID </span>    
    <input type="text" disabled required="" value="<?= $hid ?>" class="form-control">
    </div><br/>
    <input type="text" name="hardware_id" hidden="" required="" value="<?= $hid ?>">
    <!--<div class="input-group">
    <span class="input-group-addon">DATE</span>
    <input type="date" name="date" required="" class="form-control" required> </div><br/>
    -->

    <div class="input-group">
    <span class="input-group-addon">Work For</span>
    <select name=work_for class="form-control" required="">
        <?php
            $qr=$pdo->query("SELECT * from member WHERE role = 2");
            while($row=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value = '.$row['member_id'].'>';
                echo ($row['first_name'] . " " . $row['last_name']);
                echo '</option>';
            }
         ?>
    </select>
    </div><br/>
    <input type="submit" value="Repair Machine" class="btn btn-info">
          <a class ="link-no-format" href="home.php"><div class="btn btn-my">Cancel</div></a>
    </form>

    </div>
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript"src="script.js"></script>
</body>
</html>