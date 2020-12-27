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
    if(isset($_POST['mac_addr']) )
    {
        if ( strlen($_POST['mac_addr']) < 1 || strlen($_POST['lab']) < 1 || strlen($_POST['from']) < 1 )
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: posmcv2.php');
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
                    header('Location: posmcv2.php');
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
        header('Location: homev2.php');
        return;
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
                            <a class="nav-link" href="#"><?php
   echo "You are logged in as - ".$_SESSION['name']." ".$_SESSION['lname']
?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Sign Out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <br>
            
   <center><h1>POSITION MACHINE</h1></center>
   
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

    <form method="POST" action="posmcv2.php"  class="register-form" id="register-form">
    <div class="form-row">
    <div class="form-group">
    <div class="form-input">
     <label for="mc no." class="required">MACHINE No. (from)</label>
    <input type="text" name="mac_addr" required="" class="form-control" placeholder="Starting Machine ID" id="delmcf" onchange="Number('delmcf')"> </div><br/>

    <div class="form-input">
     <label for="mc no." class="required">MACHINE No. (to)</label>
    <input type="text" name="mac_addr2" required="" class="form-control" placeholder="Ending Machine ID" id="delmct" onchange="Number('delmct')"> </div><br/> 

    <div class="form-input">
     <label for="Lab" class="required">LAB NAME </label>
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


    <div class="form-submit">
        
    <input type="submit" value="Add Machine" name="add" id="Submit" class="Submit">
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