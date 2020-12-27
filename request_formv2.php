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
        die("Please Login First");
    }
    else
    {
        $stmt=$pdo->prepare("SELECT first_name,last_name FROM member WHERE member_id = :id");
        $stmt->execute(array(":id"=>$_SESSION['id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $name = $row['first_name'].' '.$row['last_name'];
    }
    if(isset($_POST['name']) )
    {
        if ( strlen($_POST['name']) < 1 || strlen($_POST['department']) < 1 || strlen($_POST['purpose']) < 1|| strlen($_POST['quantity']) < 1)
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: request_formv2.php');
            return;
        }
        else
        {
                 
                
                $date=date('y-m-d');
                $stmt = $pdo->prepare('INSERT INTO transfer_request(date_of_request, name, department, purpose, processor, ram, hdd, os, quantity) VALUES (:dat, :name, :department, :purpose, :processor, :ram, :hdd, :os, :quantity)');
                    $stmt->execute(array(':dat' => date('y-m-d'), ':name' => $_POST['name'], ':department' => $_POST['department'], ':purpose' => $_POST['purpose'], ':processor' => $_POST['processor'], ':ram' => $_POST['ram'], ':hdd' => $_POST['hdd'], ':os' => $_POST['os'], ':quantity' => $_POST['quantity']));

                    $trid=$pdo->lastInsertId();
                    
                $_SESSION['success'] = "Request Sent Successfully<br>";
                    /*if(isset($_SESSION['id']))
                        header("Location:homev2.php");
                    else
                        header('Location: index.php');*/
                   // header("location:printform_requestv2.php?trid=$trid");
                    //return;
                         echo("<script>
         window.open('printrequest_formv2.php?trid=$trid', '_blank'); 
</script>");
        echo("<script>window.open('homev2.php','_self')</script>");
            

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
            
   <center><h1>REQUEST COMPUTERS</h1></center>
   
    
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

    <form method="POST" action="request_formv2.php" class="register-form">
    <div class="form-row">
    <div class="form-group">
    <div class="form-input">
    <label>Name </label>
    <input type="text" disabled="" required="" class="form-control" value="<?=$name?>" id="rname" onchange="Names('rname')"> </div>
    <input type="text" name="name" hidden value="<?=$name?>">
    
    <div class="form-input">
    <label>Department </label>
    <input type="text" name="department" required="" class="form-control" placeholder="Department Name" id="deprt" onchange="Names('deprt')"> </div>

    <div class="form-input">
    <label>Purpose</label>
    <input type="text" name="purpose" required="" class="form-control" id="purp" onchange="Purpose('purp')"> </div>

    <p>Required Specifications</p>
    <div class="form-input">
    <label>Processor</label>
    <select name=processor class="form-control" required="">
        <option value="NULL">Any</option>
        <?php
            
            $qr=$pdo->query("SELECT * from name where name = 'processor'");
            $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
            $processoriddb=$rowtmp['name_id'];

            $qr=$pdo->query("SELECT DISTINCT description from hardware WHERE name = $processoriddb");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option>';
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $pro->execute(array(':spec_id' => $rowx['description']));
                $pron = $pro->fetch(PDO::FETCH_ASSOC);
                echo($pron['spec']);
                
                echo '</option>';
            }
         ?>
    </select>
    </div>

    <div class="form-input">
    <label>Ram</label>
    <select name=ram class="form-control" required="">
        <option value="NULL">Any</option>
        <?php
            $qr=$pdo->query("SELECT * from name where name = 'ram'");
            $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
            $ramiddb=$rowtmp['name_id'];

            $qr=$pdo->query("SELECT DISTINCT description from hardware WHERE name = $ramiddb");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option>';
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $pro->execute(array(':spec_id' => $rowx['description']));
                $pron = $pro->fetch(PDO::FETCH_ASSOC);
                echo($pron['spec']);
                
                echo '</option>';
            }
         ?>
    </select>
    </div>

    <div class="form-input">
    <label>Storage</label>
    <select name=hdd class="form-control" required="">
        <option value="NULL">Any</option>
        <?php
            $qr=$pdo->query("SELECT * from name where name = 'harddisk'");
            $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
            $memoryiddb=$rowtmp['name_id'];

            $qr=$pdo->query("SELECT DISTINCT description from hardware WHERE name = $memoryiddb");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option>';
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $pro->execute(array(':spec_id' => $rowx['description']));
                $pron = $pro->fetch(PDO::FETCH_ASSOC);
                echo($pron['spec']);
                echo '</option>';
            }
         ?>
    </select>
    </div>

    <div class="form-input">
    <label>OS </label>
    <select id="drop-other" name="os" class="form-control" onchange="Device();" required="">
       <option value="NULL">Any</option>
       <option value="windows">Windows</option>
       <option value="linux">Linux</option>
       <option value="osx">OS X</option>
   </select>
     </div>
    
     <div class="form-input">
    <label>Quantity</label>
    <input type="number" name="quantity" required class="form-control" placeholder="No. of Computers"> </div>
    
    

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