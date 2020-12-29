<?php
    session_start();
    require_once "pdo.php";
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
    if( $_SESSION['role'] == '0' )
    {
        die('ACCESS DENIED');
    }
    if(isset($_POST['cancel']))
    {
        header("Location: index.php");
        return;
    }

    if(isset($_GET['mc_id']))
    {
        $mc_id = $_GET['mc_id'];
    }
    elseif (isset($_POST['mc_id']))
    {
        $mc_id = $_POST['mc_id'];
    }


    if(isset($_POST['mc_id']) )
    {
        if ( isset($_POST['processor']) || isset($_POST['ram']) || isset($_POST['harddisk']) || isset($_POST['keyboard']) || isset($_POST['mouse']) || isset($_POST['monitor']))
        {
            
            $stmtch = $pdo->prepare("SELECT * from temp where machine_id = :mid");
            $stmtch->execute(array(':mid'=> $mc_id));
            $rowch = $stmtch->fetch(PDO::FETCH_ASSOC);
            if($rowch == false)
            {
                $stmti = $pdo->prepare("INSERT INTO temp (machine_id, completed) VALUES (:mid, 0)");
                $stmti->execute(array(':mid' => $mc_id));

            }

            if(isset($_POST['processor']))
            {
                $stmt = $pdo->prepare('UPDATE complaint_book SET processor = 1 WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }
            else
            {   
                $stmt = $pdo->prepare('UPDATE temp SET processor = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));

                $stmt = $pdo->prepare('UPDATE complaint_book SET processor = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            if(isset($_POST['ram']))
            {
                $stmt = $pdo->prepare('UPDATE complaint_book SET ram = 1 WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            else
            {   
                $stmt = $pdo->prepare('UPDATE temp SET ram = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
                $stmt = $pdo->prepare('UPDATE complaint_book SET ram = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            if(isset($_POST['harddisk']))
            {
                $stmt = $pdo->prepare('UPDATE complaint_book SET harddisk = 1 WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            else
            {   
                $stmt = $pdo->prepare('UPDATE temp SET harddisk = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
                $stmt = $pdo->prepare('UPDATE complaint_book SET harddisk = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            if(isset($_POST['monitor']))
            {
                $stmt = $pdo->prepare('UPDATE complaint_book SET monitor = 1 WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            else
            {   
                $stmt = $pdo->prepare('UPDATE temp SET monitor = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
                $stmt = $pdo->prepare('UPDATE complaint_book SET monitor = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            if(isset($_POST['keyboard']))
            {
                $stmt = $pdo->prepare('UPDATE complaint_book SET keyboard = 1 WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            else
            {   
                $stmt = $pdo->prepare('UPDATE temp SET keyboard = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
                $stmt = $pdo->prepare('UPDATE complaint_book SET keyboard = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }

            if(isset($_POST['mouse']))
            {
                $stmt = $pdo->prepare('UPDATE complaint_book SET mouse = 1 WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }


            else
            {   
                $stmt = $pdo->prepare('UPDATE temp SET mouse = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
                $stmt = $pdo->prepare('UPDATE complaint_book SET mouse = NULL WHERE machine_id = :mid');
                $stmt->execute(array(':mid' => $mc_id));
            }
            $todaydate = date('y-m-d');
            $stmtd = $pdo->prepare("UPDATE complaint_book SET DOPR = '$todaydate' WHERE machine_id = :mid");
            $stmtd->execute(array(':mid' => $mc_id));

            $stmtch = $pdo->prepare("SELECT * from temp where machine_id = :mid");
            $stmtch->execute(array(':mid'=> $mc_id));
            $rowch = $stmtch->fetch(PDO::FETCH_ASSOC);
            if($rowch == false)
            {
                $stmti = $pdo->prepare("INSERT INTO temp (machine_id, completed) VALUES (:mid, 0)");
                $stmti->execute(array(':mid' => $mc_id));

            }
            else
            {
                $stmtd = $pdo->prepare("UPDATE temp SET completed = 0 WHERE machine_id = :mid");
                $stmtd->execute(array(':mid' => $mc_id));
                
            }

            $_SESSION['success'] = "Request Sent";
            header('Location: homev2.php');
            return;
        }
        else
        {
            $_SESSION['error'] = "At least one option should be selected";
            header('Location: partsreqv2.php');
            return;
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
            
   <center><h1>MACHINE FIXED</h1></center>

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

    <form method="POST" action="partsreqv2.php" class="register-form">

    <div class="checkbox">
    <label><input type="checkbox" name="processor" value="processor">Processor</label>
    </div>
    <div class="checkbox">
    <label><input type="checkbox" name="ram" value="ram">Ram</label>
    </div>
    <div class="checkbox">
    <label><input type="checkbox" name="harddisk" value="harddisk">Harddisk</label>
    </div>
    <div class="checkbox">
    <label><input type="checkbox" name="monitor" value="monitor">Monitor</label>
    </div>
    <div class="checkbox">
    <label><input type="checkbox" name="mouse" value="mouse">Mouse</label>
    </div>
    <div class="checkbox">
    <label><input type="checkbox" name="keyboard" value="keyboard">Keyboard</label>
    </div>

    <div class="form-submit">
        
        <input type="submit" value="Req. Parts" name="add" id="Submit" class="Submit">
        <input type="reset" value="Reset" class="submit" id="reset" name="reset" />
            </div>
    <input type="hidden" name="mc_id" value="<?= $mc_id ?>">
    
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