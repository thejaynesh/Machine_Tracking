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
            header('Location: device_complaint_form.php');
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
                    $_SESSION['success'] = "Machine is already in Repair<br>";
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
    <?php if (isset($_SESSION['id'])&&$_SESSION['role']=='0') include "navbar.php"; 
                else if(isset($_SESSION['id'])&&$_SESSION['role']=='1')  include "navbar_faculty.php";
                else include "navbar_tech.php";?>

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

    <form method="POST" action="device_complaint_form.php" class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">Select Device</span>
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
     </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Complaint Details </span>
    <input type="text" name="details" required="" class="form-control"> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Priority</span>
    <input type="number" name="priority" required="" placeholder="in no. of days" class="form-control" id="priority" onchange="Number('priority')"> </div><br/>
    
    <div class="input-group">
    <span class="input-group-addon">Complaint By </span>
    <input type="text" value = '<?= $namex ?>' disabled="" required="" class="form-control" id="cname" onchange="Names('cname')"> </div><br/>
    <input type="text" name="name" hidden="" value = '<?=$namex?>'>
    

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