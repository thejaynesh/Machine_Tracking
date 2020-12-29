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
    if(isset($_GET['h_id']))
    {
        $hid=$_GET['h_id'];
    }
    if(isset($_POST['hardware_id']) )
    {
        if ( strlen($_POST['hardware_id']) < 1 )
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: gorepairmcv2.php?h_id='.$_GET['h_id']);
            return;
        }
        else
        {

            $_POST['date'] = date('y-m-d',strtotime($_POST['date']));
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
                    header('Location: gorepairmcv2.php'.$_GET['h_id']);
                    return;
                }


                 $stmt = $pdo->prepare('UPDATE hardware SET state = 3 WHERE hardware_id = :mid');
                    $stmt->execute(array(':mid' => $hid));

                $stmt = $pdo->prepare('UPDATE hardware_position SET final_date = :fdate WHERE hardware_id = :mid AND final_date = "1970-01-01"');
                    $stmt->execute(array(':mid' => $hid, ':fdate' => date('y-m-d')));

                $stmt = $pdo->prepare('INSERT INTO device_repair_history (hardware_id, initial_date, final_date) VALUES (:hid, :idate, "1970-01-01")');
                    $stmt->execute(array(':hid' => $hid, ':idate' => date('y-m-d')));

                $stmt = $pdo->prepare('UPDATE hardware_complaint_book SET work_for = :wf WHERE hardware_id = :hid AND work_for IS NULL');
                $stmt->execute(array(':hid' => $hid, ':wf' => $_POST['work_for']));

                $_SESSION['success'] = "Hardware sent to Repair Successfully<br>";
                header('Location: homev2.php');
                return;
            }
            else
            {
                $_SESSION['error'] = "Hardware does not Exists<br>";
                    header('Location: gorepairmcv2.php');
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
                            <a class="nav-link" href="#"><?php echo $_SESSION['name']." ".$_SESSION['lname'] ?></a>
                        </li>
                        <li class="nav-item">
                                <a class="nav-link" href="logout.php">Sign Out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <br>
            
   <center><h1>REPAIR HARDWARE</h1></center>

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

    <form method="POST" class="register-form">

    <div class="form-row">
    <div class="form-group">
    <div class="form-input">
    <label>Hardware ID </label>    
    <input type="text" disabled required="" value="<?= $hid ?>" class="form-control">
    </div>
    <input type="text" name="hardware_id" hidden="" required="" value="<?= $hid ?>">
    <!--<div class="input-group">
    <span class="input-group-addon">DATE</span>
    <input type="date" name="date" required="" class="form-control" required> </div><br/>
    -->

    <div class="form-input">
    <label>Work For</label>
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
    </div>
    <div class="form-submit">
        
        <input type="submit" value="Asign" name="add" id="Submit" class="Submit">
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