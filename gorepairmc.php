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
    if(isset($_POST['mac_addr']) )
    {
        if ( strlen($_POST['mac_addr']) < 1 )
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: gorepairmc.php');
            return;
        }
        else
        {
            $_POST['date']=date('y-m-d',strtotime($_POST['date']));
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM machine WHERE MAC_ADDR = :mac_addr');
            $stmt->execute(array(':mac_addr' => $_POST['mac_addr']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row['COUNT(*)'] !== '0')
            {
                $stmt = $pdo->prepare('SELECT * FROM machine WHERE MAC_ADDR = :mac_addr');
                $stmt->execute(array(':mac_addr' => $_POST['mac_addr']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $mid = $row['machine_id'];

                $stmt = $pdo->prepare('SELECT COUNT(*) FROM repair_history WHERE machine_id = :mid AND final_date = "0000-00-00"');
                $stmt->execute(array(':mid' => $mid));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row['COUNT(*)'] !== '0')
                {
                    $_SESSION['error'] = "Machine already in Repair<br>";
                    header('Location: gorepairmc.php');
                    return;
                }

                $stmtn = $pdo->prepare('SELECT * FROM complaint_book WHERE work_for IS NULL AND machine_id = :mid');
                $stmtn->execute(array(':mid' => $mid));
                $rown = $stmtn->fetch(PDO::FETCH_ASSOC);

                 $stmt = $pdo->prepare('UPDATE machine SET state = "INACTIVE" WHERE machine_id = :mid');
                    $stmt->execute(array(':mid' => $mid));

                $stmt = $pdo->prepare('UPDATE position SET final_date = :fdate WHERE machine_id = :mid AND final_date = "1970-01-01"');
                    $stmt->execute(array(':mid' => $mid, ':fdate' => $_POST['date']));

                $stmt = $pdo->prepare('INSERT INTO repair_history (machine_id, initial_date, final_date, complaint_book_id) VALUES (:mid, :idate, "1970-01-01", :cbid)');
                    $stmt->execute(array(':mid' => $mid, ':idate' => $_POST['date'], ':cbid' => $rown['complaint_book_id']));

                $stmt = $pdo->prepare('UPDATE complaint_book SET work_for = :wf WHERE machine_id = :mid AND work_for IS NULL');
                $stmt->execute(array(':mid' => $mid, ':wf' => $_POST['work_for']));

                $wf=$_POST['work_for'];
                $date=$_POST['date'];
                $_SESSION['success'] = "Machine sent to Repair Successfully<br>";
               // header("Location:printcomp.php?mc_id=$mid&wf=$wf&date=$date");
                echo("<script>
         window.open('printcomp.php?mc_id=$mid&wf=$wf&date=$date', '_blank'); 
</script>");
        echo("<script>window.open('home.php','_self')</script>");
            }
            else
            {
                $_SESSION['error'] = "Machine does not Exists<br>";
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
    <h1>REPAIR MACHINE</h1>
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

    <form method="POST" action="gorepairmc.php" class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">MAC ADDRESS </span>    
    <input type="text" value="<?= $mac_addr ?>" class="form-control" disabled>
    <input type="text" name="mac_addr" hidden value="<?= $mac_addr ?>" >
    </div><br/>

    <input type="text" name="date" hidden="" value = '<?= date('y-m-d') ?>'>


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