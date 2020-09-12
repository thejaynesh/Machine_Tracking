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
    if(isset($_POST['mac_addr']) )
    {
        if ( strlen($_POST['mac_addr']) < 1 || strlen($_POST['details']) < 1 || strlen($_POST['priority']) < 1 || strlen($_POST['name']) < 1)
        {
            $_SESSION['error'] = "All Fields are required";
            header('Location: complaint_form.php');
            return;
        }
        else
        {
                $stmt = $pdo->prepare('SELECT * FROM machine WHERE MAC_ADDR = :mac_addr');
                $stmt->execute(array(':mac_addr' => $_POST['mac_addr']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row=== FALSE)
                {
                    $_SESSION['error'] = "Invalid MAC ADDRESS";
                    header('Location: complaint_form.php');
                    return;
                }
                $mid = $row['machine_id'];
                
                $stmt = $pdo->prepare('SELECT * FROM complaint_book WHERE machine_id = :mid AND completed IS NULL');
                $stmt->execute(array(':mid' => $mid));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $stmt2 = $pdo->prepare('SELECT * FROM machine WHERE machine_id = :mid AND state = "INACTIVE"');
                $stmt2->execute(array(':mid' => $mid));
                $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

                if($row === FALSE && $row2 === FALSE)
                {
                    $_POST['dop']=date('y-m-d',strtotime($_POST['dop']));
                $stmt = $pdo->prepare('INSERT INTO complaint_book (date_of_complaint, machine_id, complaint_details, priority, complaint_by) VALUES (:doc, :mid, :cd, :priority, :complaint_by)');
                    $stmt->execute(array(':doc' => date('y-m-d'), ':mid' => $mid, ':cd' => $_POST['details'], ':priority' => $_POST['priority'], ':complaint_by' => $_POST['name']));
                $_SESSION['success'] = "Complaint Registered Successfully";
                    if(isset($_SESSION['id']))
                    {
                        header('Location: home.php');
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
                    $_SESSION['success'] = "Complaint for this machine already exists";
                    if(isset($_SESSION['id']))
                    {
                        header('Location: home.php');
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
                <?php if (isset($_SESSION['id'])&&$_SESSION['role']=='0') include "navbar.php"; else if(isset($_SESSION['id'])&&$_SESSION['role']=='1')  include "navbar_faculty.php"; else include "navbar_tech.php"?>

    <div class="container" id="content">
    <div class="page-header">
    <h1>REGISTER COMPLAINT</h1>
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

    <form method="POST" action="complaint_form.php" class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">MACHINE No. </span>
    <input type="text" name="mac_addr" required="" class="form-control" id="mac_addr" onchange="Number('mac_addr')" placeholder="Computer No. (only integers)"> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Complaint Details </span>
    <input type="text" name="details" required="" class="form-control"> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Priority</span>
    <input type="number" name="priority" required="" placeholder="in no. of days" class="form-control" id="priority"> </div><br/>
    
    <div class="input-group">
    <span class="input-group-addon">Complaint By </span>
    <input type="text" value = '<?= $name ?>' disabled="" required="" class="form-control" id="cname" onchange="Names('cname')"> </div><br/>
    <input type="text" name="name" hidden="" value = '<?=$name?>'>

    <input type="submit" value="Register Complaint" class="btn btn-info">
    <a class ="link-no-format" href="home.php"><div class="btn btn-my">Cancel</div></a>
    </form>

    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>