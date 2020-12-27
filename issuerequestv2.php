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

    $salt='new_ton56*';

    if(isset($_POST['id']) )
    {
        if ( strlen($_POST['id']) < 1 || strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 || strlen($_POST['c_pass']) < 1|| strlen($_POST['contact_no']) < 1)
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: add_memberv2.php');
            return;
        }
        else
        {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM member WHERE id = :id');
            $stmt->execute(array(':id' => $_POST['id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row['COUNT(*)'] !== '0')
            {
                $_SESSION['error'] = "This ID already exists<br>";
                header('Location: add_memberv2.php');
                return;
            }

            if($_POST['pass'] === $_POST['c_pass'])
            {
                if(strlen($_POST['pass'])<8)
                {
                    $_SESSION['error'] = "Password must be atleast 8 character long<br>";
                    header('Location: add_memberv2.php');
                    return;
                }
                else
                {
                    $check = hash('md5', $salt.$_POST['pass']);
                    $stmt = $pdo->prepare('INSERT INTO member (id, first_name, last_name, email, pass_word ,role,contact_no) VALUES (:id, :fn, :ln, :em, :pw,"2",:cn)');
                    $stmt->execute(array(':id' => $_POST['id'], ':fn' => $_POST['first_name'], ':ln' => $_POST['last_name'], ':em' => $_POST['email'], ':pw' => $check,':cn' => $_POST['contact_no']));

                    $_SESSION['success'] = "Member Added Successfully<br>";
                    header('Location: homev2.php');
                    return;
                }
            }
            else
            {
                $_SESSION['error'] = "Passwords do not match<br>";
                header('Location: add_memberv2.php');
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
            
   <center><h1>ADD NEW MEMBER</h1></center>
   
    
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

    <form method="POST"  class="register-form">
    <div class="form-row">
    <div class="form-group">
    <div class="form-input">
    <label>Select Hardware</label>
    <select name="hardwarename" class="form-control">
        <?php
            $stmt=$pdo->query("SELECT description from hardware WHERE state =0");
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                echo "<option>".$row['description']."</option>"

        ?>
    </select>
    </div>

    <div class="form-input">
    <label>First Name</label>
    <input type="text" name="first_name" required="" class="form-control" id="fname" onchange="Names('fname')"> </div>

    <div class="form-input">
    <label>Last Name</label>
    <input type="text" name="last_name" required="" class="form-control" id="lname" onchange="Names('lname')"> </div>

    <div class="form-input">
    <label>Email</label>
    <input type="email" name="email" required="" class="form-control"> </div>

    <div class="form-input">
      <label>Contact No.</label>
      <input type="text" name="contact_no" class="form-control" required placeholder="Enter a valid Contact number"> </div>

      <div class="form-input">
    <label>Password</label>
    <input type="password" name="pass" required="" class="form-control"> </div>

    <div class="form-input">
    <label>Confirm Password</label>
    <input type="password" required="" name="c_pass" class="form-control"> </div>
    
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