<?php
    session_start();
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
    if( $_SESSION['id'] != '0' )
    {
        die('ACCESS DENIED');
    }
    require_once "pdo.php";
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
<html>
<head>
    <title>Maintenance/Complaint Book</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1">

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="style5.css">
</head>
<body style="margin-left: 100px; margin-right: 100px; margin-top: 100px">
            
    <div style="text-align: center">
        <b>
            <span>Medi-Caps University,Indore</span>
            <br>
            <span>Maintenance/Complaint Book</span>
        </b>
    </div>
    <br><br>
    <div class="container">
        <span class="col-xs-10">
            <b>S.No.: &nbsp</b>
            <?php
            $midq=$_GET['mc_id'];
            $stmtn3 = $pdo->prepare("SELECT complaint_book_id,Date_of_complaint FROM complaint_book where machine_id = :mid ORDER BY complaint_book_id desc ");
                $stmtn3->execute(array(':mid' => $midq));
                $rown3 = $stmtn3->fetch(PDO::FETCH_ASSOC);
               echo(htmlentities($rown3['complaint_book_id'])); 

            ?>
        </span>
        <span>
            <b>Date: &nbsp</b>
            <?php
          $midq=$_GET['mc_id'];
            $stmtn6 = $pdo->prepare("SELECT Date_of_complaint FROM complaint_book where machine_id = :mid ORDER BY complaint_book_id desc ");
              $stmtn6->execute(array(':mid' => $midq));
                $rown6 = $stmtn6->fetch(PDO::FETCH_ASSOC);
            echo(htmlentities($rown3['Date_of_complaint']));
            ?>
        </span>
    </div>
    <br><br>
    <div class="container">
        <span class="col-xs-5">
            <b>Department: &nbsp</b>
          <?php  
            $midq=$_GET['mc_id'];
                $stmtn = $pdo->prepare("SELECT lab_id FROM position where machine_id = :mid AND final_date='1970-01-01' ");
                $stmtn->execute(array(':mid' =>$midq ));
                $rown = $stmtn->fetch(PDO::FETCH_ASSOC);
                
                $stmtn1 = $pdo->prepare("SELECT name FROM lab where lab_id = :lid ");
                $stmtn1->execute(array(':lid' => $rown['lab_id']));
                $rown1 = $stmtn1->fetch(PDO::FETCH_ASSOC);

            $stmtn5 = $pdo->prepare("SELECT department FROM lab where lab_id = :lid ");
            $stmtn5->execute(array(':lid' => $rown['lab_id']));
            $rown5 = $stmtn5->fetch(PDO::FETCH_ASSOC);
                echo(htmlentities($rown5['department']));
                ?>
        </span>

        <span class="col-xs-4">
             <span> <b>Complaint By : </b><?php
            $midq=$_GET['mc_id'];
            $stmtn6 = $pdo->prepare("SELECT complaint_by FROM complaint_book where machine_id = :mid ORDER BY complaint_book_id desc ");
                $stmtn6->execute(array(':mid' => $midq));
                $rown6 = $stmtn6->fetch(PDO::FETCH_ASSOC);
                 echo(htmlentities($rown6['complaint_by']));

            ?></span>
           
            
          
           </span>



        <span>
            <b>H.O.D Name: &nbsp</b>
        </span>
    </div>
    <br>
    <?php

        
        
        $stmtcnt = $pdo->query("SELECT COUNT(*) FROM complaint_book ");
        $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

        if($row['COUNT(*)']!=='0')
        {
            $i=1;
            $stmtread = $pdo->query("SELECT * FROM complaint_book ORDER BY complaint_book_id");
            
            echo ("<table class=\"table table-striped\">
                <tr> 
                <th>Lab Name</th>
                <th>Work For</th>
                <th>Maintenance/Complaint Details</th>
                <th>Priority in Days</th>
                <th>Remarks</th>
                </tr>");

                $row = $stmtread->fetch(PDO::FETCH_ASSOC) ;
                 $midq=$_GET['mc_id'];
                $stmtn = $pdo->prepare('SELECT lab_id FROM position where machine_id = :mid AND final_date = :fdate ');
                $stmtn->execute(array(':mid' =>$midq,':fdate' => $_GET['date'] ));
                $rown = $stmtn->fetch(PDO::FETCH_ASSOC);
                
                $stmtn1 = $pdo->prepare("SELECT name FROM lab where lab_id = :lid ");
                $stmtn1->execute(array(':lid' => $rown['lab_id']));
                $rown1 = $stmtn1->fetch(PDO::FETCH_ASSOC);

                $stmtn5 = $pdo->prepare("SELECT department FROM lab where lab_id = :lid ");
                $stmtn5->execute(array(':lid' => $rown['lab_id']));
                $rown5 = $stmtn5->fetch(PDO::FETCH_ASSOC);

                $stmtn3 = $pdo->prepare("SELECT complaint_book_id FROM complaint_book where machine_id = :mid ORDER BY complaint_book_id desc ");
                $stmtn3->execute(array(':mid' => $midq));
                $rown3 = $stmtn3->fetch(PDO::FETCH_ASSOC);


                $compd = $pdo->prepare("SELECT complaint_details FROM complaint_book where complaint_book_id = :cid");
                $compd->execute(array(':cid' => $rown3['complaint_book_id']));
                $compdn = $compd->fetch(PDO::FETCH_ASSOC);


                $compb = $pdo->prepare("SELECT complaint_by FROM complaint_book where complaint_book_id = :cid");
                $compb->execute(array(':cid' => $row['complaint_book_id']));
                $compbn = $compb->fetch(PDO::FETCH_ASSOC);

                
                $comp1 = $pdo->prepare("SELECT work_for FROM complaint_book where complaint_book_id = :cid");
                $comp1->execute(array(':cid' => $row['complaint_book_id']));
                $comp1n = $comp1->fetch(PDO::FETCH_ASSOC);

                $stmtn2 = $pdo->prepare("SELECT first_name,last_name FROM member where member_id = :mid ");
                $stmtn2->execute(array(':mid' => $comp1n['work_for']));
                $rown2 = $stmtn2->fetch(PDO::FETCH_ASSOC);

                $stmtn4 = $pdo->prepare("SELECT priority FROM complaint_book where complaint_book_id = :cid ");
                $stmtn4->execute(array(':cid' => $rown3['complaint_book_id']));
                $rown4 = $stmtn4->fetch(PDO::FETCH_ASSOC);

                $stmtn6 = $pdo->prepare("SELECT Date_of_complaint FROM complaint_book where machine_id = :mid ");
                $stmtn6->execute(array(':mid' => $midq));
                $rown6 = $stmtn6->fetch(PDO::FETCH_ASSOC);

                $stmtn7 = $pdo->prepare("SELECT remarks FROM complaint_book where machine_id = :mid AND completed=1 ORDER BY Date_of_complaint DESC");
                $stmtn7->execute(array(':mid' => $midq));
                $rown7 = $stmtn7->fetch(PDO::FETCH_ASSOC);



                echo ("<tr>");
               

                echo ("<td>");
                echo(htmlentities($rown1['name']));
                echo ("</td>");
                
                echo ("<td>");
                echo($rown2['first_name']." " .$rown2['last_name']);
                echo ("</td>");
                
                echo ("<td>");
                echo($compdn['complaint_details']);
                echo ("</td>");
                
                echo ("<td>");
                 echo(htmlentities($rown4['priority']));
                echo ("</td>");
                
                echo ("<td>");
                echo(htmlentities($rown7['remarks']));

                echo("</td>");

                echo('</table>');
            
        }

    ?>
    <br><br><br><br>
    <div class="container">
        <span class="col-xs-4">
            <span>........................</span><br>
            <b>Posting</b>
        </span>

        
       
        <span class="col-xs-4">
            <span>.................</span><br>
            <b>H.O.D.</b>
        </span>
        <span>
            <span>................................................</span><br>
            <b>Ment. Supervisor<b>
        </span>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>