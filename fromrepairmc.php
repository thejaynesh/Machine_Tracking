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
    if(isset($_GET['mc_id']))
    {
        $mac_addr=$_GET['mc_id'];
    }
    if(isset($_POST['mac_addr']))
    {
        if ( strlen($_POST['mac_addr']) < 1 || strlen($_POST['fault']) < 1 || strlen($_POST['cost']) < 1 )
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: fromrepairmc.php?mc_id='.$_GET['mc_id']);
            return;
        }
        else
        {
            $_POST['date']=date('y-m-d',strtotime($_POST['date']));
            $stmt = $pdo->prepare('SELECT * FROM machine WHERE MAC_ADDR = :mac_addr');
            $stmt->execute(array(':mac_addr' => $_POST['mac_addr']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row === FALSE)
            {
                $_SESSION['error'] = "Invalid MAC ADDRESS";
                header('Location: fromrepairmc.php?mc_id='.$_GET['mc_id']);
                return;
            }
            $mid = $row['machine_id'];
 
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM repair_history WHERE machine_id = :mid AND fault IS NULL');
            $stmt->execute(array(':mid' => $mid));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row['COUNT(*)'] !== '0')
            {

                 $stmt = $pdo->prepare('UPDATE machine SET state = "ACTIVE" WHERE machine_id = :mid');
                    $stmt->execute(array(':mid' => $mid));

                $stmt = $pdo->prepare('UPDATE repair_history SET final_date = :fdate, fault = :fault, cost = :cost WHERE machine_id = :mid AND final_date = "0000-00-00"');
                    $stmt->execute(array(':mid' => $mid, ':fdate' => date('y-m-d'), ':fault' => $_POST['fault'], ':cost' => $_POST['cost']));

                $stmtn = $pdo->prepare('SELECT * from position WHERE machine_id = :mid ORDER BY position_id DESC');
                $stmtn->execute(array(':mid' => $mid));
                $rown=$stmtn->fetch(PDO::FETCH_ASSOC);
                if($rown != FALSE)
                {
                    $lid=$rown['lab_id'];

                    $stmt = $pdo->prepare('INSERT INTO position (machine_id, lab_id, initial_date, final_date) VALUES (:mid, :lid, :idate, :fdate)');
                            $stmt->execute(array(':mid' => $mid, ':lid' => $lid, ':idate' => $_POST['date'], ':fdate' => 1970-01-01));
                }

                $stmtc = $pdo->prepare('SELECT * FROM temp WHERE machine_id = :mid');
                $stmtc->execute(array(':mid' => $mid));
                $c = $stmtc->fetch(PDO::FETCH_ASSOC);

                if($c != false)
                {
                    $stmtreadu = $pdo->prepare("SELECT * FROM machine where machine_id = :xyz");
                    $stmtreadu->execute(array(":xyz" => $mid));
                    $row = $stmtreadu->fetch(PDO::FETCH_ASSOC);

                    //$_SESSION['success']=$row['processor'];

                    if(!is_null($c['processor']))
                    {
                        $stmtu = $pdo->prepare('UPDATE hardware SET state = -1 where hardware_id = :hid');
                        $stmtu->execute(array(':hid' => $row['processor']));

                        $stmt = $pdo->prepare('UPDATE machine SET processor = :p WHERE machine_id = :ma');
                        $stmt->execute(array( ':p' => $c['processor'], ':ma' => $mid));
                    }

                    if(!is_null($c['ram']))
                    {
                        $stmtu = $pdo->prepare('UPDATE hardware SET state = -1 where hardware_id = :hid');
                        $stmtu->execute(array(':hid' => $row['ram']));
                        
                        $stmt = $pdo->prepare('UPDATE machine SET ram = :p WHERE machine_id = :ma');
                        $stmt->execute(array( ':p' => $c['ram'], ':ma' => $mid));
                    }

                    if(!is_null($c['harddisk']))
                    {
                        $stmtu = $pdo->prepare('UPDATE hardware SET state = -1 where hardware_id = :hid');
                        $stmtu->execute(array(':hid' => $row['memory']));

                        $stmt = $pdo->prepare('UPDATE machine SET memory = :p WHERE machine_id = :ma');
                        $stmt->execute(array( ':p' => $c['harddisk'], ':ma' => $mid));
                    }

                    if(!is_null($c['keyboard']))
                    {
                        $stmtu = $pdo->prepare('UPDATE hardware SET state = -1 where hardware_id = :hid');
                        $stmtu->execute(array(':hid' => $row['keyboard']));

                        $stmt = $pdo->prepare('UPDATE machine SET keyboard = :p WHERE machine_id = :ma');
                        $stmt->execute(array( ':p' => $c['keyboard'], ':ma' => $mid));
                    }

                    if(!is_null($c['mouse']))
                    {
                        $stmtu = $pdo->prepare('UPDATE hardware SET state = -1 where hardware_id = :hid');
                        $stmtu->execute(array(':hid' => $row['mouse']));

                        $stmt = $pdo->prepare('UPDATE machine SET mouse = :p WHERE machine_id = :ma');
                        $stmt->execute(array( ':p' => $c['mouse'], ':ma' => $mid));
                    }

                    if(!is_null($c['monitor']))
                    {
                        $stmtu = $pdo->prepare('UPDATE hardware SET state = -1 where hardware_id = :hid');
                        $stmtu->execute(array(':hid' => $row['monitor']));

                        $stmt = $pdo->prepare('UPDATE machine SET monitor = :p WHERE machine_id = :ma');
                        $stmt->execute(array( ':p' => $c['monitor'], ':ma' => $mid));
                    }

                    /*$stmtug = $pdo->prepare('INSERT INTO upgrade_history (machine_id, processori, rami, memoryi, processorf, ramf, memoryf, dateofupgrade) VALUES (:mid, :pi, :ri, :mi, :pf, :rf, :mf, :d)');
                    $stmtug->execute(array(
                        ':mid' => $mc_id,
                     ':pi' => $row['processor'], 
                     ':ri' => $row['ram'], 
                     ':mi' => $row['memory'],
                     ':pf' => $c['processor'],
                        ':rf' => $c['ram'],
                        ':mf' => $c['memory'],
                        ':d' => date('y-m-d')
                        ));*/

                    $stmtug = $pdo->prepare('INSERT INTO upgrade_history (machine_id, processori, rami, memoryi, dateofupgrade) VALUES (:mid, :pi, :ri, :mi, :d)');
                    $stmtug->execute(array(
                        ':mid' => $mid,
                     ':pi' => $row['processor'], 
                     ':ri' => $row['ram'], 
                     ':mi' => $row['memory'],
                       ':d' => date('y-m-d')
                        ));
                    $date=date('y-m-d');

                    $last_id = $pdo->lastInsertId();

                    if(!is_null($c['processor']))
                    {
                        $stmtu = $pdo->prepare('UPDATE upgrade_history SET processorf = :pf where upgrade_history_id = :uhid');
                        $stmtu->execute(array(':pf' => $c['processor'], ':uhid' => $last_id));
                    }
                    else
                    {
                        $stmtu = $pdo->prepare('UPDATE upgrade_history SET processorf = :pf where upgrade_history_id = :uhid');
                        $stmtu->execute(array(':pf' => $row['processor'], ':uhid' => $last_id));

                    }

                    if(!is_null($c['ram']))
                    {
                        $stmtu = $pdo->prepare('UPDATE upgrade_history SET ramf = :rf where upgrade_history_id = :uhid');
                        $stmtu->execute(array(':rf' => $c['ram'], ':uhid' => $last_id));
                    }
                    else
                    {
                        $stmtu = $pdo->prepare('UPDATE upgrade_history SET ramf = :rf where upgrade_history_id = :uhid');
                        $stmtu->execute(array(':rf' => $row['ram'], ':uhid' => $last_id));

                    }

                    if(!is_null($c['harddisk']))
                    {
                        $stmtu = $pdo->prepare('UPDATE upgrade_history SET memoryf = :mf where upgrade_history_id = :uhid');
                        $stmtu->execute(array(':mf' => $c['harddisk'], ':uhid' => $last_id));
                    }
                    else
                    {
                        $stmtu = $pdo->prepare('UPDATE upgrade_history SET memoryf = :mf where upgrade_history_id = :uhid');
                        $stmtu->execute(array(':mf' => $row['memory'], ':uhid' => $last_id));

                    }

                    $stmtdelete = $pdo->prepare("DELETE FROM temp where machine_id = :xyz");
                    $stmtdelete->execute(array(":xyz" => $mid));


                }

                $_SESSION['success'] = "Machine returned from Repair Successfully<br>";
               // header("Location: printcomprem.php?mc_id=$mid&date=$date");
                echo("<script>
         window.open('printcomprem.php?mc_id=$mid&date=$date', '_blank'); 
</script>");
        echo("<script>window.open('home.php','_self')</script>");
               // return;
            }
            else
            {
                $_SESSION['error'] = "Machine does not Exist in Repair House<br>";
                    header('Location: fromrepairmc.php');
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
    <div class="container-fluid row" id="container">
    <div class="page-header">
    <h1>MACHINE REPAIRED</h1>
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

         $checkinactive=$pdo ->query("SELECT count(*) from machine where state='INACTIVE'");
        $rowcheck=$checkinactive->fetch(PDO::FETCH_ASSOC);
        if($rowcheck['count(*)']==0)
        {
            $_SESSION['error']="None of the machines are sent for repairing<br>";
            header('Location: home.php');
            return;
        }
    ?>

    <form method="POST" action="fromrepairmc.php" class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">MAC ADDRESS </span>    
    <input type="text" value="<?= $mac_addr ?>" disabled class="form-control">
    <input type="hidden" name="mac_addr" value="<?= $mac_addr ?>" class="form-control">
    </div><br/>
    <input type="text" name="date" hidden >
    <div class="input-group">
    <span class="input-group-addon">FAULT </span>
    <input type="text" name="fault" required="" class="form-control" id="fault"> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">COST OF REPAIR </span>
    <input type="text" name="cost" required="" class="form-control" id="cost" onchange="Number('cost')"> </div><br/>

    <input type="submit" value="Place Machine" class="btn btn-info">
        <a class ="link-no-format" href="home.php"><div class="btn btn-my">Cancel</div></a>
    </form>

    </div>
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript"src="script.js"></script>
</body>
</html>