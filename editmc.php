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

    if(isset($_GET['mac_addr']) )
    {
        if ( strlen($_GET['mac_addr']) < 1 )
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: upgrademc.php');
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
                header( 'Location: upgrademc.php' ) ;
                return;
            }

            $processor = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
            $processor->execute(array(':hid' => $row['processor']));
            $processorn = $processor->fetch(PDO::FETCH_ASSOC);

            $ram = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
            $ram->execute(array(':hid' => $row['ram']));
            $ramn = $ram->fetch(PDO::FETCH_ASSOC);

            $memory = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
            $memory->execute(array(':hid' => $row['memory']));
            $memoryn = $memory->fetch(PDO::FETCH_ASSOC);

            $keyboard = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
            $keyboard->execute(array(':hid' => $row['keyboard']));
            $keyboardn = $keyboard->fetch(PDO::FETCH_ASSOC);

            $mouse = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
            $mouse->execute(array(':hid' => $row['mouse']));
            $mousen = $mouse->fetch(PDO::FETCH_ASSOC);

            $monitor = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
            $monitor->execute(array(':hid' => $row['monitor']));
            $monitorn = $monitor->fetch(PDO::FETCH_ASSOC);


            $mac_addr = htmlentities($row['MAC_ADDR']);
            $processor = htmlentities($processorn['description']);
            $ram = htmlentities($ramn['description']);
            $memory = htmlentities($memoryn['description']);
            $price = htmlentities($row['price']);
            $dop = htmlentities($row['DOP']);
            $os = htmlentities($row['os']);
            $keyboard = htmlentities($keyboardn['description']);
            $mouse = htmlentities($mousen['description']);
            $monitor = htmlentities($monitorn['description']);
        }
    }

    if(isset($_POST['processor']))
    {
        if ( strlen($_POST['processor']) < 1 || strlen($_POST['ram']) < 1 || strlen($_POST['memory']) < 1)
        {
            $_SESSION['error'] = "All Fields are required";
            header('Location: upgrademc.php');
            return;
        }
        else
        {
            $stmtreadu = $pdo->prepare("SELECT * FROM machine where MAC_ADDR = :xyz");
            $stmtreadu->execute(array(":xyz" => $_POST['macaddr']));
            $row = $stmtreadu->fetch(PDO::FETCH_ASSOC);

            $stmtu = $pdo->prepare('UPDATE hardware SET state = 0 where hardware_id = :hid');
            $stmtu->execute(array(':hid' => $row['processor']));
            //$_SESSION['success']=$row['processor'];
            $stmtu = $pdo->prepare('UPDATE hardware SET state = 0 where hardware_id = :hid');
            $stmtu->execute(array(':hid' => $row['ram']));

            $stmtu = $pdo->prepare('UPDATE hardware SET state = 0 where hardware_id = :hid');
            $stmtu->execute(array(':hid' => $row['memory']));

            /*$stmtu = $pdo->prepare('UPDATE hardware SET state = 0 where hardware_id = :hid');
            $stmtu->execute(array(':hid' => $row['monitor']));

            $stmtu = $pdo->prepare('UPDATE hardware SET state = 0 where hardware_id = :hid');
            $stmtu->execute(array(':hid' => $row['mouse']));

            $stmtu = $pdo->prepare('UPDATE hardware SET state = 0 where hardware_id = :hid');
            $stmtu->execute(array(':hid' => $row['keyboard']));*/

            $stmt = $pdo->prepare('UPDATE machine SET
            processor = :p, ram = :ram, memory = :mem
            WHERE MAC_ADDR = :ma');
            $stmt->execute(array(
            ':p' => $_POST['processor'],
            ':ram' => $_POST['ram'],
            ':mem' => $_POST['memory'],
            ':ma' => $_POST['macaddr']
            
            ));

            $stmtu = $pdo->prepare('UPDATE hardware SET state = 1 where hardware_id = :hid');
            $stmtu->execute(array(':hid' => $_POST['processor']));

            $stmtu = $pdo->prepare('UPDATE hardware SET state = 1 where hardware_id = :hid');
            $stmtu->execute(array(':hid' => $_POST['ram']));

            $stmtu = $pdo->prepare('UPDATE hardware SET state = 1 where hardware_id = :hid');
            $stmtu->execute(array(':hid' => $_POST['memory']));

 /*           $stmtu = $pdo->prepare('UPDATE hardware SET state = 1 where hardware_id = :hid');
            $stmtu->execute(array(':hid' => $_POST['monitor']));

            $stmtu = $pdo->prepare('UPDATE hardware SET state = 1 where hardware_id = :hid');
            $stmtu->execute(array(':hid' => $_POST['mouse']));

            $stmtu = $pdo->prepare('UPDATE hardware SET state = 1 where hardware_id = :hid');
            $stmtu->execute(array(':hid' => $_POST['keyboard']));
*/
             $stmtug = $pdo->prepare('INSERT INTO upgrade_history (machine_id, processori, rami, memoryi, processorf, ramf, memoryf, dateofupgrade) VALUES (:mid, :pi, :ri, :mi, :pf, :rf, :mf, :d)');
            $stmtug->execute(array(
                ':mid' => $row['machine_id'],
             ':pi' => $row['processor'], 
             ':ri' => $row['ram'], 
             ':mi' => $row['memory'],
             ':pf' => $_POST['processor'],
                ':rf' => $_POST['ram'],
                ':mf' => $_POST['memory'],
                ':d' => date('y-m-d')
                ));

            $_SESSION['success']="Machine Upgraded Sucessfully<br>";
            header("Location: home.php");
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
    <div class="container-fluid row" id="content">

    <div class="page-header">
    <h1>UPGRADE MACHINE</h1>
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

    <form method="POST" action="editmc.php" class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">MAC ADDRESS </span>
    <input type="text" value="<?= $mac_addr ?>" class="form-control" disabled> </div><br/>
    <input type="hidden" name="macaddr" value="<?= $mac_addr ?>" class="form-control" >

    <!--<div class="input-group">
    <span class="input-group-addon">Processor </span>
    <input type="text" name="processor" required="" value="<?= $processor ?>" class="form-control"> </div><br/> -->

    <div class="input-group">
    <span class="input-group-addon">Processor</span>
    <select name=processor class="form-control" required="">
        <?php
            
            $qr=$pdo->query("SELECT * from name where name = 'processor'");
            $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
            $processoriddb=$rowtmp['name_id'];

                
            echo '<option value = '.$row['processor'].' selected >';
            
            $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $pro->execute(array(':spec_id' => $processor));
                $pron = $pro->fetch(PDO::FETCH_ASSOC);
                echo($pron['spec']);
            
            echo '</option>';

            $qr=$pdo->query("SELECT * from hardware WHERE name = $processoriddb AND state = '0'");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value = '.$rowx['hardware_id'].'>';
                
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :desc");
                $pro->execute(array(':desc' => $rowx['description']));
                $pron = $pro->fetch(PDO::FETCH_ASSOC);

                echo ($pron['spec']);
                echo '</option>';
            }
         ?>
    </select>
    </div><br/>

    <!--<div class="input-group">
    <span class="input-group-addon">RAM </span>
    <input type="text" name="ram" required="" value="<?= $ram ?>" class="form-control"> </div><br/>-->

    <div class="input-group">
    <span class="input-group-addon">Ram</span>
    <select name=ram class="form-control" required="">
        <?php
            $qr=$pdo->query("SELECT * from name where name = 'ram'");
            $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
            $ramiddb=$rowtmp['name_id'];

            echo '<option value = '.$row['ram'].' selected >';

            $ramq = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $ramq->execute(array(':spec_id' => $ram));
                $ramn = $ramq->fetch(PDO::FETCH_ASSOC);
                echo($ramn['spec']);


            //echo ($ram);
            echo '</option>';
            $qr=$pdo->query("SELECT * from hardware WHERE name = $ramiddb AND state = '0'");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value = '.$rowx['hardware_id'].'>';
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :desc");
                $pro->execute(array(':desc' => $rowx['description']));
                $pron = $pro->fetch(PDO::FETCH_ASSOC);

                echo ($pron['spec']);
                //echo ($rowx['description']);
                echo '</option>';
            }
         ?>
    </select>
    </div><br/>

    <!--<div class="input-group">
    <span class="input-group-addon">Storage </span>
    <input type="text" name="memory" required="" value="<?= $memory ?>" class="form-control"> </div><br/>-->

    <div class="input-group">
    <span class="input-group-addon">Storage</span>
    <select name=memory class="form-control" required="">
        <?php
            $qr=$pdo->query("SELECT * from name where name = 'harddisk'");
            $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
            $memoryiddb=$rowtmp['name_id'];

            echo '<option value = '.$row['memory'].' selected >';

            $mem = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $mem->execute(array(':spec_id' => $memory));
                $memn = $mem->fetch(PDO::FETCH_ASSOC);
                echo($memn['spec']);


            //echo ($memory);
            echo '</option>';
            $qr=$pdo->query("SELECT * from hardware WHERE name = $memoryiddb AND state = '0'");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value = '.$rowx['hardware_id'].'>';
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :desc");
                $pro->execute(array(':desc' => $rowx['description']));
                $pron = $pro->fetch(PDO::FETCH_ASSOC);

                echo ($pron['spec']);
                //echo ($rowx['description']);
                echo '</option>';
            }
         ?>
    </select>
    </div><br/>

    <div class="input-group">
    <span class="input-group-addon">OS </span>
    <!--<input type="text" name="os" value="<?= $os ?>" class="form-control"> </div><br/>-->
    <select name="os" class="form-control" required="">
        <?php
            $os=$pdo->prepare("SELECT os from machine where MAC_ADDR= :ma");
            $os->execute(array(':ma'=>$_POST['mac_addr']));
            $rowos=$os->fetch(PDO::FETCH_ASSOC);
        ?>
        <option value="windows" <?php if($rowos['os']=="windows") echo " selected "?>>Windows</option>
        <option value="linux" <?php if($rowos['os']=="linux") echo " selected "?>>Linux</option>
        <option value="osx" <?php if($rowos['os']=="osx") echo " selected "?>>OS X</option>
    </select></div><br>
    <!--<div class="input-group">
    <span class="input-group-addon">Keyboard </span>
    <input type="text" name="keyboard" required="" value="<?= $keyboard ?>" class="form-control"> </div><br/>-->

    <div class="input-group">
    <span class="input-group-addon">Keyboard </span>
    <select name=keyboard class="form-control" disabled>
        <?php
            $qr=$pdo->query("SELECT * from name where name = 'keyboard'");
            $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
            $keyboardiddb=$rowtmp['name_id'];

            echo '<option value = '.$row['keyboard'].' selected >';
            $key = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $key->execute(array(':spec_id' => $keyboard));
                $keyn = $key->fetch(PDO::FETCH_ASSOC);
                echo($keyn['spec']);


            //echo ($keyboard);

            echo '</option>';
            $qr=$pdo->query("SELECT * from hardware WHERE name = $keyboardiddb AND state = '0'");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value = '.$rowx['hardware_id'].'>';
                echo ($rowx['description']);
                echo '</option>';
            }
         ?>
    </select>
    </div><br/>

    <!--<div class="input-group">
    <span class="input-group-addon">Mouse </span>
    <input type="text" name="mouse" required="" value="<?= $mouse ?>" class="form-control"> </div><br/>-->

    <div class="input-group">
    <span class="input-group-addon">Mouse</span>
    <select name=mouse class="form-control" disabled>
        <?php
            $qr=$pdo->query("SELECT * from name where name = 'mouse'");
            $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
            $mouseiddb=$rowtmp['name_id'];

            echo '<option value = '.$row['mouse'].' selected >';

            $mou = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $mou->execute(array(':spec_id' => $mouse));
                $moun = $mou->fetch(PDO::FETCH_ASSOC);
                echo($moun['spec']);


            //echo ($mouse);
            echo '</option>';
            $qr=$pdo->query("SELECT * from hardware WHERE name = $mouseiddb AND state = '0'");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value = '.$rowx['hardware_id'].'>';
                echo ($rowx['description']);
                echo '</option>';
            }
         ?>
    </select>
    </div><br/>

    <!--<div class="input-group">
    <span class="input-group-addon">Monitor </span>
    <input type="text" name="os" required="" value="<?= $monitor ?>" class="form-control"> </div><br/>-->

    <div class="input-group">
    <span class="input-group-addon">Monitor</span>
    <select name=monitor class="form-control" disabled>
        <?php
            $qr=$pdo->query("SELECT * from name where name = 'monitor'");
            $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
            $monitoriddb=$rowtmp['name_id'];

            echo '<option value = '.$row['monitor'].' selected >';
            $mem = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $mem->execute(array(':spec_id' => $monitor));
                $memn = $mem->fetch(PDO::FETCH_ASSOC);
                echo($memn['spec']);
           // echo ($monitor);
            echo '</option>';
            $qr=$pdo->query("SELECT * from hardware WHERE name = $monitoriddb AND state = '0'");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value = '.$rowx['hardware_id'].'>';
                echo ($rowx['description']);
                echo '</option>';
            }
         ?>
    </select>
    </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Price of Purchase </span>
    <input type="text" name="price" value="<?= $price ?>"" class="form-control" disabled> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Date of Purchase</span>
    <input type="date" name="dop" value="<?= $dop ?>" class="form-control" disabled> </div><br/>


    <input type="submit" value="Upgrade Machine" class="btn btn-info">
    <a class ="link-no-format" href="home.php"><div class="btn btn-my">Cancel</div></a>
    </form>

    </div>
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript"src="script.js"></script>
</body>
</html>