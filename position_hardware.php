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

    $salt='new_ton56*';

    if(isset($_POST['id']) )
    {
        if ( strlen($_POST['id']) < 1 || strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 || strlen($_POST['c_pass']) < 1|| strlen($_POST['contact_no']) < 1)
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: add_member.php');
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
                header('Location: add_member.php');
                return;
            }

            if($_POST['pass'] === $_POST['c_pass'])
            {
                if(strlen($_POST['pass'])<8)
                {
                    $_SESSION['error'] = "Password must be atleast 8 character long<br>";
                    header('Location: add_member.php');
                    return;
                }
                else
                {
                    $check = hash('md5', $salt.$_POST['pass']);
                    $stmt = $pdo->prepare('INSERT INTO member (id, first_name, last_name, email, pass_word ,role,contact_no) VALUES (:id, :fn, :ln, :em, :pw,"2",:cn)');
                    $stmt->execute(array(':id' => $_POST['id'], ':fn' => $_POST['first_name'], ':ln' => $_POST['last_name'], ':em' => $_POST['email'], ':pw' => $check,':cn' => $_POST['contact_no']));

                    $_SESSION['success'] = "Member Added Successfully<br>";
                    header('Location: home.php');
                    return;
                }
            }
            else
            {
                $_SESSION['error'] = "Passwords do not match<br>";
                header('Location: add_member.php');
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
    <h1>ADD NEW MEMBER</h1>
    </div>
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

    <form method="POST"  class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">Select Hardware</span>
    <select name="hardwarename" class="form-control">
        <?php
            $stmt=$pdo->query("SELECT description from hardware WHERE state =0");
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                echo "<option>".$row['description']."</option>"

        ?>
    </select>
    </div>
    <br/>

    <div class="input-group">
    <span class="input-group-addon">First Name</span>
    <input type="text" name="first_name" required="" class="form-control" id="fname" onchange="Names('fname')"> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Last Name</span>
    <input type="text" name="last_name" required="" class="form-control" id="lname" onchange="Names('lname')"> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Email</span>
    <input type="email" name="email" required="" class="form-control"> </div><br/>

    <div class="input-group">
      <span class="input-group-addon">Contact No.</span>
      <input type="text" name="contact_no" class="form-control" required placeholder="Enter a valid Contact number"> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Password</span>
    <input type="password" name="pass" required="" class="form-control"> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Confirm Password</span>
    <input type="password" required="" name="c_pass" class="form-control"> </div><br/>
    
    <input type="submit" value="Sign Up" class="btn btn-info">
    <a class ="link-no-format" href="home.php"><div class="btn btn-my">Cancel</div></a>
    </form>

    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>