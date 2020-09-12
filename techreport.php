<?php
    session_start();
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
    if( $_SESSION['role'] != '0' )
    {
        die('ACCESS DENIED');
    }
    require_once "pdo.php";

    //Selecting id of procssors,ram and memory from name table

?>
<html>
<head>
    <title>Machine Tracking</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1">

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="style5.css">
</head>
<body>
            <div class="wrapper">
     <?php if (isset($_SESSION['id'])&&$_SESSION['role']=='0') include "navbar.php"; 
                else if(isset($_SESSION['id'])&&$_SESSION['role']=='1')  include "navbar_faculty.php";
                else include "navbar_tech.php";?>
         <div class="container-fluid row" id="content">

    <div class="page-header">
        <form method="POST" class="form-inline">
            <label id="tech">Select Technician</label>
                <select class="form-control" id="tech" name="tech">
                    <?php

                    //This query will select all distinct(description) and hardware_id from hardware table and name will be equal to processor number selected in line 13

                        $qr=$pdo->query("SELECT * from member WHERE role='2'");
                        while($row=$qr->fetch(PDO::FETCH_ASSOC))
                        {
                            echo "<option value =". $row['member_id'].">". $row['first_name'].' '.$row['last_name'].'/'.$row['id']."</option>   ";
                        }
                    ?>
                </select>
            <input class="btn btn-my"type="submit" name="submit">
        </form>
    <h1>Work Done</h1>
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

        if(isset($_POST['tech']))
        {
            echo "<h2>Work on Machines</h2>";
            $stmt=$pdo->prepare("SELECT * FROM complaint_book WHERE work_for = :tech");
            $stmt->execute(array(":tech"=>$_POST['tech']));
            echo"<table class=\"table table-striped\">";
            echo "<tr>
            <th>Sr. No. </th>
            <th>Date of Complaint</th>
            <th>Mac ID</th>
            <th>Complaint Details</th>
            <th>Prirority(No. of Days)</th>
            <th>Remarks</th>
            <th>Complaint By</th>
            <th>Parts Requested</th>
            <th>Completed<th>
            ";
            echo "</tr>";
            $i=1;
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                echo "<tr>";
                $stmtmc = $pdo->prepare("SELECT MAC_ADDR FROM machine WHERE machine_id = :mid");
                $stmtmc->execute(array(":mid"=>$row['machine_id']));
                $macaddr= $stmtmc->fetch(PDO::FETCH_ASSOC);
                $macaddr = $macaddr['MAC_ADDR'];
                echo "<td>";
                    echo $i;
                echo "</td>";
                echo "<td>";
                    echo $row['Date_of_complaint'];
                echo "</td>";
                echo "<td>";
                    echo $macaddr;
                echo "</td>";
                echo "<td>";
                    echo $row['complaint_details'];
                echo "</td>";
                echo "<td>";
                    echo $row['priority'];
                echo "</td>";
                echo "<td>";
                    echo $row['remarks'];
                echo "</td>";
                echo "<td>";
                    echo $row['complaint_by'];
                echo "</td>";
                echo "<td>";
                    if(!is_null($row['processor'])||!is_null($row['ram'])||!is_null($row['harddisk'])||!is_null($row['mouse'])||!is_null($row['keyboard'])||!is_null($row['monitor']))
                        echo "YES";
                    else  
                        echo "NO";
                echo "</td>";
                echo "<td>";
                    if($row['completed'] == '1')
                        echo "YES";
                    else
                        echo "NO";
                echo "</td>";

                echo "</tr>";
            }
            echo "</table>";
            echo "<h2>Work on Hardwares</h2>";
            $stmt=$pdo->prepare("SELECT * FROM hardware_complaint_book WHERE work_for = :tech");
            $stmt->execute(array(":tech"=>$_POST['tech']));
            echo"<table class=\"table table-striped\">";
            echo "<tr>
            <th>Sr. No. </th>
            <th>Date of Complaint</th>
            <th>Hardware Name</th>
            <th>Complaint Details</th>
            <th>Prirority(No. of Days)</th>
            <th>Remarks</th>
            <th>Complaint By</th>
            <th>Completed<th>
            ";
            echo "</tr>";
            $i=1;
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                echo "<tr>";
                $stmtmc = $pdo->prepare("SELECT name.name FROM name JOIN hardware ON hardware.hardware_id = :hid AND name.name_id = hardware.name");
                $stmtmc->execute(array(":hid"=>$row['hardware_id']));
                $macaddr= $stmtmc->fetch(PDO::FETCH_ASSOC);
                $macaddr = $macaddr['name'];
                echo "<td>";
                    echo $i;
                echo "</td>";
                echo "<td>";
                    echo $row['date_of_complaint'];
                echo "</td>";
                echo "<td>";
                    echo $macaddr;
                echo "</td>";
                echo "<td>";
                    echo $row['complaint_details'];
                echo "</td>";
                echo "<td>";
                    echo $row['priority'];
                echo "</td>";
                echo "<td>";
                    echo $row['remarks'];
                echo "</td>";
                echo "<td>";
                    echo $row['complaint_by'];
                echo "</td>";
                echo "<td>";
                    if($row['completed'] == '1')
                        echo "YES";
                    else
                        echo "NO";
                echo "</td>";

                echo "</tr>";
            }
                echo "</table>";
        }
    ?>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstdap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>