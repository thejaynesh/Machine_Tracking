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
    <h1>MACHINES</h1>
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
        //echo('<p><a href="logout.php">Logout</a></p>');
        $stmtcnt = $pdo->query("SELECT COUNT(*) FROM repair_history WHERE final_date='0000-00-00'");
        $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

        if($row['COUNT(*)']!=='0')
        {
            $i=1;
            $stmtread = $pdo->query("SELECT * FROM repair_history where final_date = '0000-00-00' ");
            echo ("<table class=\"table table-striped\">
                <tr> <th>S.no.</th><th>MAC ADDRESS</th><th>Date Sent</th><th>Work For</th><th>No. of days passed</th></tr>");
            while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
            {
                $mid=$row['machine_id'];
                $read2= $pdo -> query("SELECT * FROM machine where machine_id='$mid'");
                $read3= $pdo -> query("SELECT * FROM complaint_book where machine_id='$mid' AND remarks IS NULL");
                $row3 = $read3->fetch(PDO::FETCH_ASSOC);
                $memid = $row3['work_for'];
                $read4= $pdo -> query("SELECT * FROM member where member_id='$memid'");
                $row4 = $read4->fetch(PDO::FETCH_ASSOC);
                while($row2 = $read2->fetch(PDO::FETCH_ASSOC))
                {
                    echo ("<tr>");
                    echo ("<td>");
                    echo($i);
                    echo("</td>");
                    echo ("<td>");
                    echo(htmlentities($row2['MAC_ADDR']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($row['initial_date']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($row4['first_name']));
                    echo" ";
                    echo(htmlentities($row4['last_name']));
                    echo ("</td>");
                    echo ("<td>");
                    echo((abs(strtotime(date("Y-m-d")) - strtotime($row['initial_date'])))/(24*3600));
                    echo ("</td>");
                    
                    $i++;
                }
            }
            echo('</table>');
        }
    ?>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>