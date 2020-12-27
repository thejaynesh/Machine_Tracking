<?php
    session_start();
    require_once "pdo.php";
    
    if(isset($_POST['cancel']))
    {
        header("Location: index.php");
        return;
    }
    if(!isset($_SESSION['id']))
    {
        die("ERROR 403 ACCESS DENIED");
    }
    else
    {
        $stmt=$pdo->prepare("SELECT first_name,last_name FROM member WHERE member_id = :id");
        $stmt->execute(array(":id"=>$_SESSION['id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $namex = $row['first_name'].' '.$row['last_name'];
    }

    if(isset($_POST['hardware_id']) )
    {
        if ( strlen($_POST['hardware_id']) < 1 || strlen($_POST['details']) < 1 || strlen($_POST['priority']) < 1 || strlen($_POST['name']) < 1)
        {
            $_SESSION['error'] = "All Fields are required<br>";
            die("awf");
            header('Location: device_complaint_formv2.php');
            return;
        }
        else
        {
                $stmt = $pdo->prepare('SELECT * FROM device_repair_history WHERE hardware_id = :hid AND fault IS NULL');
                $stmt->execute(array(':hid' => $_POST['hardware_id']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row === FALSE)
                {
                    $stmt = $pdo->prepare('INSERT INTO hardware_complaint_book (date_of_complaint, hardware_id, complaint_details, priority, complaint_by) VALUES (:doc, :mid, :cd, :priority, :complaint_by)');
                    $stmt->execute(array(':doc' => date('y-m-d'), ':mid' => $_POST['hardware_id'], ':cd' => $_POST['details'], ':priority' => $_POST['priority'], ':complaint_by' => $_POST['name']));
                    $_SESSION['success'] = "Complaint Registered Successfully<br>";
                    if(isset($_SESSION['id']))
                    {
                        header('Location: homev2.php');
                        return;    
                    }
                    else
                    {
                        header('Location: index.php');
                        return;   
                    }
                }
                else
                {
                    $_SESSION['success'] = "Machine is already in Repair<br>";
                    if(isset($_SESSION['id']))
                    {
                        header('Location: homev2.php');
                        return;    
                    }
                    else
                    {
                        header('Location: index.php');
                        return;   
                    }
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
            
   <center><h1>REGISTER COMPLAINT</h1></center>
   
 
    <div id="error" style="color: red; margin-left: 90px; margin-bottom: 20px;"></div>
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

    <form method="POST" action="device_complaint_formv2.php" class="register-form">
    <div class="form-row">
     <div class="form-group">
    <div class="form-input">
    <label>Select Device</label>
    <select name="hardware_id" class="form-control">
        <?php
            $qr=$pdo->query("SELECT description,name,hardware.hardware_id, description,hardware_position.lab_id,hardware_position.member_id FROM hardware JOIN hardware_position ON (hardware_position.hardware_id=hardware.hardware_id) WHERE hardware_position.final_date='0000-00-00' OR hardware_position.final_date='1970-01-01'");
            while($row=$qr->fetch(PDO::FETCH_ASSOC))
            {
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :name_id");
                $pro->execute(array(':name_id' => $row['description']));
                $name=$pdo->prepare("SELECT name from name where name_id = :name");
                $name->execute(array(":name"=>$row['name']));
                $namer=$name->fetch(PDO::FETCH_ASSOC);
                $pron = $pro->fetch(PDO::FETCH_ASSOC);
                $labname;
                if(!is_null($row['lab_id']))
                {
                    $query=$pdo->prepare("SELECT name from lab where lab_id=:labid");
                    $query->execute(array(":labid"=>$row['lab_id']));
                    $labname=$query->fetch(PDO::FETCH_ASSOC);
                    $labname=$labname['name'];
                }
                else
                    $labname="";
                $membername;
                if(!is_null($row['member_id']))
                {
                    $query=$pdo->prepare("SELECT first_name,last_name from member where member_id=:memid");
                    $query->execute(array(":memid"=>$row['member_id']));
                    $membername=$query->fetch(PDO::FETCH_ASSOC);
                    $membername=$membername['first_name'].' '.$membername['last_name'];
                }
                else
                    $membername="";

                echo "<option value=".$row['hardware_id'].">".$namer['name'].' | '.$pron['spec'].' | '.$labname.' | '.$membername."</option>";
            }
        ?>   
    </select>
     </div>
     
    <div class="form-input">
    <label>Complaint Details </label>
    <input type="text" name="details" required="" class="form-control"> </div>

    <div class="form-input">
    <label>Priority</label>
    <input type="number" name="priority" required="" placeholder="in no. of days" class="form-control" id="priority" onchange="Number('priority')"> </div>
    
    <div class="form-input">
    <label>Complaint By </label>
    <input type="text" value = '<?= $namex ?>' disabled="" required="" class="form-control" id="cname" onchange="Names('cname')"> </div>
    <input type="text" name="name" hidden="" value = '<?=$namex?>'>
    

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