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
        if ( strlen($_POST['mac_addr']) < 1 || strlen($_POST['lab']) < 1 || strlen($_POST['from']) < 1 )
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: posmc.php');
            return;
        }
        else
        {
            for($i=$_POST['mac_addr'];$i<=$_POST['mac_addr2'];$i++)
            {
                $stmt = $pdo->prepare('SELECT COUNT(*),machine_id FROM machine WHERE MAC_ADDR = :mac_addr');
                $stmt->execute(array(':mac_addr' => $i));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $mid=$row['machine_id'];
                if($row['COUNT(*)'] === '0')
                {
                    $_SESSION['error'] .= "Unable to place machine, ".$i." Machine does not exist";
                    continue;
                }
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM lab WHERE name = :lab');
                $stmt->execute(array(':lab' => $_POST['lab']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row['COUNT(*)'] === '0')
                {
                    $_SESSION['error'] = "This Lab does not exist<br>";
                    header('Location: posmc.php');
                    return;
                }
                $stmtn = $pdo->prepare('SELECT COUNT(*) FROM position WHERE machine_id = :mid');
                $stmtn->execute(array(':mid' => $mid));
                $row2=$stmtn->fetch(PDO::FETCH_ASSOC);
                if($row2['COUNT(*)'] === '0')
                {
                    $stmt = $pdo->prepare('SELECT * FROM machine WHERE MAC_ADDR = :mac_addr');
                    $stmt->execute(array(':mac_addr' => $i));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $mid = $row['machine_id'];
                    $stmt = $pdo->prepare('SELECT * FROM lab WHERE name = :lab');
                    $stmt->execute(array(':lab' => $_POST['lab']));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $lid = $row['lab_id'];
                    $fdate;
                    if($_POST['to'] !="1970-01-01")
                        $fdate=date('y-m-d',strtotime($_POST['to']));
                    else
                        $fdate="0000-00-00";
                    $stmt = $pdo->prepare('INSERT INTO position (machine_id, lab_id, initial_date, final_date) VALUES (:mid, :lid, :idate, :fdate)');
                        $stmt->execute(array(':mid' => $mid, ':lid' => $lid, ':idate' => date('y-m-d',strtotime($_POST['from'])), ':fdate' => $fdate));
                    $_SESSION['success'] .= $i."Machine Positioned Successfully<br>";
                }
                else
                {
                    $_SESSION['error']="Machine ".$i." is already placed<br>";
                }
            }
        }
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
    <div class="container-fluid row" id="content">
    <div class="page-header">
    <h1>POSITION MACHINE</h1>
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

    <form method="POST" action="posmc.php" class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">MACHINE No. (from)</span>
    <input type="text" name="mac_addr" required="" class="form-control" placeholder="Starting Machine ID" id="delmcf" onchange="Number('delmcf')"> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">MACHINE No. (to)</span>
    <input type="text" name="mac_addr2" required="" class="form-control" placeholder="Ending Machine ID" id="delmct" onchange="Number('delmct')"> </div><br/> 

    <div class="input-group">
    <span class="input-group-addon">LAB NAME </span>
    <select class="form-control" name="lab" required>
        <?php
            $read=$pdo->query('select name,lab_id from lab order by name');
            while($row = $read->fetch(PDO::FETCH_ASSOC))
            {
                $labname=$row['name'];
                $labid=$row['lab_id'];
                echo '<option name = $labid>';
                echo    $labname;
                echo '</option>';
            }
        ?>
    </select>
    </div><br/>

    <!--div class="input-group">
    <span class="input-group-addon">FROM </span-->
    <input type="text" name="from" hidden="" value = "<?= date('y-m-d') ?>"> <!--/div><br/-->
    
    <!--<div class="input-group" hidden>
    <span class="input-group-addon">TO (optional)</span>-->
    <input type="date" name="to"  hidden=""> <!--/div><br/-->


    <input type="submit" value="Position Machine" class="btn btn-info">
        <a class ="link-no-format" href="home.php"><div class="btn btn-my">Cancel</div></a>
    </form>

    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
