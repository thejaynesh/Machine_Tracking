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

    if(!isset($_GET['id']))
    {
        $_SESSION['error'].="No such page exsists<br>";
        header("Location:home.php");
        return;
    }
    $stmt=$pdo->prepare("SELECT COUNT(*) FROM issue_request WHERE issue_report_id = :id");
    $stmt->execute(array(":id"=>$_GET['id']));
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row['COUNT(*)']==0)
    {
        $_SESSION['error'].="No such page exsists<br>";
        header("Location:home.php");
        return;
    }
    $stmt ->execute(array(":id"=> $_GET['id']));
    $stmt=$pdo->prepare("SELECT * FROM issue_request where issue_report_id = :id");
    $stmt->execute(array(":id"=>$_GET['id']));
    $request=$stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("SELECT state FROM hardware WHERE hardware_id = :hid");
    $stmt ->execute(array(":hid"=>$_GET['dev_id']));
    $rowr=$stmt->fetch(PDO::FETCH_ASSOC);
    if($rowr['state']!=0)
    {
        $_SESSION['error']="Unable to Issue hardware ".$_GET['dev_id'].". This is either issue to someone or placed in a lab<br>".
        header("Location:home.php");
        return;
    }
    $stmt=$pdo->prepare("INSERT INTO hardware_position
        (hardware_id,member_id,initial_date,final_date)
        VALUES
        (:hid,:memberid,:idate,:fdate)
        ");
    $dat=date('y-m-d');
    $stmt->execute(array(
        ":hid" => $_GET['dev_id'],
        ":memberid" => $request['id'],
        ":idate" => $dat,
        ":fdate" => '0-0-0'
    ));
    $stmt=$pdo->prepare("UPDATE hardware SET state = '2' WHERE hardware_id = :id");
    $stmt->execute(array(":id"=>$_GET['dev_id']));
    $stmt=$pdo->prepare("DELETE FROM issue_request WHERE issue_report_id = :id");
    $stmt->execute(array(":id"=>$_GET['id']));
    $_SESSION['success'].="Hardware Issued";
    header("Location:home.php");
    return;
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
   
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>