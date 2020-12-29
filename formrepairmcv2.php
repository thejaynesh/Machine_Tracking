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
        header("Location: homev2.php");
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
            header('Location: formrepairmcv2.php?mc_id='.$_GET['mc_id']);
            return;
        }
        else
        {
            $_POST['date']=date('y-m-d',strtotime($_POST['date']));
            $stmt = $pdo->prepare('SELECT * FROM machine WHERE MAC_ADDR = :mac_addr');
            $stmt->execute(array(':mac_addr' => $_POST['mac_addr']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row == FALSE)
            {
                $_SESSION['error'] = "Invalid MAC ADDRESS";
                header('Location: formrepairmcv2.php?mc_id='.$_GET['mc_id']);
                return;
            }
            $mid = $row['machine_id'];
 
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM repair_history WHERE machine_id = :mid AND fault IS NULL');
            $stmt->execute(array(':mid' => $mid));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row['COUNT(*)'] != '0')
            {

                 $stmt = $pdo->prepare('UPDATE machine SET state = "ACTIVE" WHERE machine_id = :mid');
                    $stmt->execute(array(':mid' => $mid));

                $stmt = $pdo->prepare('UPDATE repair_history SET final_date = :fdate, fault = :fault, cost = :cost WHERE machine_id = :mid AND final_date = "1970-01-01"');
                    $stmt->execute(array(':mid' => $mid, ':fdate' => date('y-m-d'), ':fault' => $_POST['fault'], ':cost' => $_POST['cost']));

                $stmtn = $pdo->prepare('SELECT * from position WHERE machine_id = :mid ORDER BY position_id DESC');
                $stmtn->execute(array(':mid' => $mid));
                $rown=$stmtn->fetch(PDO::FETCH_ASSOC);
                if($rown != FALSE)
                {
                    $lid=$rown['lab_id'];

                    $stmt = $pdo->prepare('INSERT INTO position (machine_id, lab_id, initial_date, final_date) VALUES (:mid, :lid, :idate, :fdate)');
                            $stmt->execute(array(':mid' => $mid, ':lid' => $lid, ':idate' => $_POST['date'], ':fdate' => '1970-01-01'));
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
                // $stmtdelete = $pdo->prepare("DELETE FROM  where machine_id = :xyz");
                // $stmtdelete->execute(array(":xyz" => $mid));

                $_SESSION['success'] = "Machine returned from Repair Successfully<br>";
               // header("Location: printcomprem.php?mc_id=$mid&date=$date");
                echo("<script>
         window.open('printcompremv2.php?mc_id=$mid&date=$_POST[date]', '_blank'); 
</script>");
        echo("<script>window.open('homev2.php','_self')</script>");
               // return;
            }
            else
            {
                $_SESSION['error'] = "Machine does not Exist in Repair House<br>";
                    header('Location: formrepairmcv2.php');
                    return;
            }

        }
    }
?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>DigiTrack</title>
 
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="style3.css">
    <link rel="stylesheet" href="css/form-style.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">

    <!-- Font Awesome JS --> 
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <style>

th, td {
  padding: 15px;
  text-align:center;
}
td:hover{
    background-color:#c394ff;
    text-decoration:underline;
}
</style>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <?php if (isset($_SESSION['id'])&&$_SESSION['role']=='0') include "navnew.php"; 
                else if(isset($_SESSION['id'])&&$_SESSION['role']=='1')  include "navbar_faculty.php";
                else include "navbar_tech.php";?>
           <div class="container-fluid row" id="content">

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Menu</span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                        
                        <li class="nav-item">
                            <a class="nav-link" href="#"><?php echo "You are logged in as - ".$_SESSION['name']." ".$_SESSION['lname'] ?></a>
                        </li>
                        <li class="nav-item">
                                <a class="nav-link" href="logout.php">Sign Out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <br>
            
   <center><h1>MACHINE REPAIRED</h1></center>
   
    
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
            header('Location: homev2.php');
            return;
        }
    ?>

    <form method="POST" action="formrepairmcv2.php" class="register-form">
    <div class="form-row">
    <div class="form-group">
    <div class="form-input">
    <label>MAC ADDRESS </label>    
    <input type="text" value="<?php echo $_GET['mc_id']; ?>" disabled class="form-control">
    <input type="hidden" name="mac_addr" value="<?php echo $_GET['mc_id']; ?>" class="form-control">
    </div><br/>
    <input type="text" name="date" value="<?php echo date("Y-m-d") ?>"hidden >
    <div class="form-input">
    <label>FAULT </label>
    <input type="text" name="fault" required="" class="form-control" id="fault"> </div><br/>

    <div class="form-input">
    <label>COST OF REPAIR </label>
    <input type="text" name="cost" required="" class="form-control" id="cost" onchange="Number('cost')"> </div><br/>

    <div class="form-submit">
        
    <input type="submit" value="Submit" name="add" id="Submit" class="Submit">
    <input type="reset" value="Reset" class="submit" id="reset" name="reset" />
        </div>
    </form>

   </div>
    </div>

    <div class="overlay"></div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- jQuery Custom Scroller CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#sidebar").mCustomScrollbar({
                theme: "minimal"
            });

            $('#dismiss, .overlay').on('click', function () {
                $('#sidebar').removeClass('active');
                $('.overlay').removeClass('active');
            });

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').addClass('active');
                $('.overlay').addClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
            });
        });
    </script>
</body>

</html>