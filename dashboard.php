<?php
    session_start();
    require_once "pdo.php";
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
?>
<html>
<head>
    <title>Machine Tracking</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1">
    <link rel="stylesheet" type="text/css" href="style5.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
</head>
<body>
        <div class="wrapper">
     <?php include "navbar.php" ;?>
           <div class="container-fluid row" id="content">

        <div class="page-header">
        <h1>TO-DO...</h1>
        </div>

        <?php
            if ( isset($_SESSION['success']))
            {
                echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
                unset($_SESSION['success']);
            }
            if ( isset($_SESSION['error']))
            {
                echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
                unset($_SESSION['error']);
            }

            if($_SESSION['id']=='0')
            {
                $stmtcnt = $pdo->query("SELECT COUNT(*) FROM complaint_book JOIN repair_history ON complaint_book.machine_id = repair_history.machine_id WHERE complaint_book.remarks IS NOT NULL AND repair_history.fault IS NULL");
                $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

                if($row['COUNT(*)']!=='0')
                {
                    echo "<h2>Repaired Machines</h2>";
                    $i=1;
                    $stmtread = $pdo->query("SELECT * FROM complaint_book JOIN repair_history ON complaint_book.machine_id = repair_history.machine_id WHERE complaint_book.remarks IS NOT NULL AND repair_history.fault IS NULL");
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Date of Complaint</th><th>MAC_ADDR</th><th>Complaint Details</th><th>Priority</th><th>Complaint By</th><th>Remarks</th><th>Action</th> </tr>");
                    while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
                    {
                        $stmtr = $pdo->prepare("SELECT MAC_ADDR FROM machine WHERE machine_id = :mid ");
                        $stmtr->execute(array(':mid' => $row['machine_id']));
                        $rowr = $stmtr->fetch(PDO::FETCH_ASSOC);
                        echo ("<tr>");
                        echo ("<td>");
                        echo($i);
                        echo("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['Date_of_complaint']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($rowr['MAC_ADDR']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['complaint_details']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['priority']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['complaint_by']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['remarks']));
                        echo ("</td>");
                        echo ("<td>");
                        echo('<a class="link-black" href="fromrepairmc.php?mc_id='.$rowr['MAC_ADDR'].'">'. 'Mark Completed' . '</a>');
                        echo ("</td>");
                        
                        $i++;
                    }
                    echo('</table>');
                }


                $stmtcnt = $pdo->query("SELECT COUNT(*) FROM complaint_book WHERE work_for IS NULL ");
                $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

                if($row['COUNT(*)']!=='0')
                {
                    echo "<h2>Repair Requests</h2>";
                    $i=1;
                    $stmtread = $pdo->query("SELECT * FROM complaint_book WHERE work_for IS NULL");
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Date of Complaint</th><th>MAC_ADDR</th><th>Complaint Details</th><th>Priority</th><th>Complaint By</th><th>Action</th> </tr>");
                    while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
                    {
                        $stmtr = $pdo->prepare("SELECT MAC_ADDR FROM machine WHERE machine_id = :mid ");
                        $stmtr->execute(array(':mid' => $row['machine_id']));
                        $rowr = $stmtr->fetch(PDO::FETCH_ASSOC);
                        echo ("<tr>");
                        echo ("<td>");
                        echo($i);
                        echo("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['Date_of_complaint']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($rowr['MAC_ADDR']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['complaint_details']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['priority']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['complaint_by']));
                        echo ("</td>");
                        echo ("<td>");
                        echo('<a class="link-black" href="gorepairmc.php?mc_id='.$rowr['MAC_ADDR'].'">'. 'Assign Job' . '</a>');

                        echo ("</td>");
                        
                        $i++;
                    }
                    echo('</table>');
                }

                $stmtcnt = $pdo->query("SELECT COUNT(*) FROM transfer_request");
                $flag=0;
                while($row = $stmtcnt->fetch(PDO::FETCH_ASSOC))
                {
                    $stmtcnt2 = $pdo->prepare("SELECT * FROM system_transfer_report where trid = :trid");
                    $stmtcnt2->execute(array(':trid' => $row['transfer_request_id']));
                    $row2 = $stmtcnt2->fetch(PDO::FETCH_ASSOC);
                    if($row2!== FALSE)
                    {
                        $flag++;
                    }
                }
                if($flag!=0)
                {
                    echo "<h2>Transfer Requests</h2>";
                    $i=1;
                    $stmtread = $pdo->query("SELECT * FROM transfer_request");
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Date of Request</th><th>Name</th><th>Department</th><th>Purpose</th><th>Processor</th><th>Ram</th><th>HDD</th><th>OS</th><th>Quantity</th><th>Action</th> </tr>");
                    while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
                    {
                        $stmtread2 = $pdo->prepare("SELECT * FROM system_transfer_report where trid = :trid");
                        $stmtread2->execute(array(':trid' => $row['transfer_request_id']));
                        $row2 = $stmtread2->fetch(PDO::FETCH_ASSOC);
                        if($row2=== FALSE)
                        {
                            echo ("<tr>");
                        echo ("<td>");
                        echo($i);
                        echo("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['date_of_request']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['name']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['department']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['purpose']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['processor']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['ram']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['hdd']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['os']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['quantity']));
                        echo ("</td>");
                        echo ("<td>");
                        echo('<a class="link-black" href="servicerpt.php?id='.$row['transfer_request_id'].'">'. 'Generate Report' . '</a>');
                        echo ("</td>");
                        
                        $i++;    
                        }
                        
                    }
                    echo('</table>');
                }
            }
            else
            {
                $stmtcnt = $pdo->query("SELECT COUNT(*) FROM complaint_book WHERE remarks IS NULL AND work_for = ".$_SESSION['id']."");
                $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

               if($row['COUNT(*)']!=='0')
                {
                    echo "<h2>Repair Jobs</h2>";
                    $i=1;
                    $stmtread = $pdo->query("SELECT * FROM complaint_book WHERE remarks IS NULL");
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Date of Complaint</th><th>MAC_ADDR</th><th>Complaint Details</th><th>Priority</th><th>Action</th> </tr>");
                    while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
                    {
                        $stmtr = $pdo->prepare("SELECT MAC_ADDR FROM machine WHERE machine_id = :mid ");
                        $stmtr->execute(array(':mid' => $row['machine_id']));
                        $rowr = $stmtr->fetch(PDO::FETCH_ASSOC);
                        echo ("<tr>");
                        echo ("<td>");
                        echo($i);
                        echo("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['Date_of_complaint']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($rowr['MAC_ADDR']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['complaint_details']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['priority']));
                        echo ("</td>");
                        echo ("<td>");
                        echo('<a class="link-black "href="mcrepaired.php?mc_id='.$row['machine_id'].'">'. 'Job Done' . '</a>');
                        echo ("</td>");
                        
                        $i++;
                    }
                    echo('</table>');
                }

                /*$stmtcnt = $pdo->query("SELECT COUNT(*) FROM transfer_request");
                $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

                if($row['COUNT(*)']!=='0')
                {
                    echo "<h2>Transfer Requests</h2>";
                    $i=1;
                    $stmtread = $pdo->query("SELECT * FROM transfer_request");
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Date of Request</th><th>Name</th><th>Department</th><th>Purpose</th><th>Processor</th><th>Ram</th><th>HDD</th><th>OS</th><th>Quantity</th><th>Action</th> </tr>");
                    while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
                    {
                        $stmtread2 = $pdo->prepare("SELECT * FROM system_transfer_report where trid = :trid");
                        $stmtread2->execute(array(':trid' => $row['transfer_request_id']));
                        $row2 = $stmtread2->fetch(PDO::FETCH_ASSOC);
                        if($row2=== FALSE)
                        {
                            echo ("<tr>");
                        echo ("<td>");
                        echo($i);
                        echo("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['date_of_request']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['name']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['department']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['purpose']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['processor']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['ram']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['hdd']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['os']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['quantity']));
                        echo ("</td>");
                        echo ("<td>");
                        echo('<a href="servicerpt.php?id='.$row['transfer_request_id'].'">'. 'Generate Report' . '</a>');
                        echo ("</td>");
                        
                        $i++;    
                        }
                        
                    }
                    echo('</table>');
                }*/
            }
        ?>

        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
