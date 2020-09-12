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
         <?php if ($_SESSION['id']=='0') include "navbar.php"; else include "navbar_index.php" ;?>
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
                    echo "<h2>Machine Repair Requests</h2>";
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
                        echo('<a class="link-black" href="gorepairmc.php?mc_id='.$rowr['MAC_ADDR'].'">'. 'Assign Job' . '</a>' . ' / ' . '<a class="link-black" href="deleterr.php?cb_id='.$row['complaint_book_id'].'">'. 'Delete' . '</a>');

                        echo ("</td>");
                        
                        $i++;
                    }
                    echo('</table>');
                }
                //Repaired Hardware
                $stmtcnt = $pdo->query("SELECT COUNT(*) FROM hardware_complaint_book JOIN device_repair_history ON hardware_complaint_book.hardware_id = device_repair_history.hardware_id WHERE hardware_complaint_book.remarks IS NOT NULL AND device_repair_history.fault IS NULL");
                $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);
                    
                if($row['COUNT(*)']!=='0')
                {
                    echo "<h2>Repaired Hardware</h2>";
                    $i=1;
                    $stmtread = $pdo->query("SELECT * FROM hardware_complaint_book JOIN device_repair_history ON hardware_complaint_book.hardware_id = device_repair_history.hardware_id WHERE hardware_complaint_book.remarks IS NOT NULL AND device_repair_history.fault IS NULL");
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Date of Complaint</th><th>Hardware ID</th><th>description</th><th>Complaint Details</th><th>Priority</th><th>Complaint By</th><th>Remarks</th><th>Action</th> </tr>");
                    while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
                    {

                    $stmt=$pdo->prepare("SELECT description,name from hardware WHERE hardware_id =:hid");
                    $stmt->execute(array(":hid"=>$row['hardware_id']));
                    $rowdes=$stmt->fetch(PDO::FETCH_ASSOC);
                    $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :name_id");
                    $pro->execute(array(':name_id' => $rowdes['description']));
                    $name=$pdo->prepare("SELECT name from name where name_id = :name");
                    $name->execute(array(":name"=>$rowdes['name']));
                    $namer=$name->fetch(PDO::FETCH_ASSOC);
                    $pron = $pro->fetch(PDO::FETCH_ASSOC);
                        echo ("<tr>");
                        echo ("<td>");
                        echo($i);
                        echo("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['date_of_complaint']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['hardware_id']));
                        echo ("</td>");
                        echo "<td>".$namer['name'].' '.$pron['spec']."</td>";
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
                        echo('<a class="link-black" href="fromrepairhardware.php?hid='.$row['hardware_id'].'">'. 'Mark Completed' . '</a>');

                        echo ("</td>");
                        
                        $i++;
                    }
                    echo('</table>');
                }
                
                //Hardare Repair Request start
                $stmtcnt = $pdo->query("SELECT COUNT(*) FROM hardware_complaint_book WHERE work_for IS NULL ");
                $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

                if($row['COUNT(*)']!=='0')
                {
                    echo "<h2>Hardware Repair Requests</h2>";
                    $i=1;
                    $stmtread = $pdo->query("SELECT * FROM hardware_complaint_book WHERE work_for IS NULL");
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Date of Complaint</th><th>Hardware ID<th>description</th></th><th>Complaint Details</th><th>Priority</th><th>Complaint By</th><th>Location</th><th>Action</th> </tr>");
                    while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
                    {
                        $location;
                        $stmt=$pdo->prepare("SELECT * FROM hardware_position WHERE hardware_id = :hid");
                        $stmt->execute(array(":hid"=>$row['hardware_id']));
                        $rowr=$stmt->fetch(PDO::FETCH_ASSOC);
                        if(!is_null($rowr['lab_id']))
                        {
                            $stmt=$pdo->prepare("SELECT name from lab WHERE lab_id = :lab");
                            $stmt->execute(array(":lab"=>$rowr['lab_id']));
                            $rowr2=$stmt->fetch(PDO::FETCH_ASSOC);
                            $location="Lab ".$rowr2['name'];
                        }
                        else
                        {
                         
                            $stmt=$pdo->prepare("SELECT first_name,last_name from member WHERE id = :id");
                            $stmt->execute(array(":id"=>$rowr['member_id']));
                            $rowr2=$stmt->fetch(PDO::FETCH_ASSOC);
                            $location="Meber ".$rowr2['first_name']." ".$rowr2['last_name'];
                        }
                    $stmt=$pdo->prepare("SELECT description,name from hardware WHERE hardware_id =:hid");
                    $stmt->execute(array(":hid"=>$row['hardware_id']));
                    $rowdes=$stmt->fetch(PDO::FETCH_ASSOC);
                    $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :name_id");
                    $pro->execute(array(':name_id' => $rowdes['description']));
                    $name=$pdo->prepare("SELECT name from name where name_id = :name");
                    $name->execute(array(":name"=>$rowdes['name']));
                    $namer=$name->fetch(PDO::FETCH_ASSOC);
                    $pron = $pro->fetch(PDO::FETCH_ASSOC);
                        echo ("<tr>");
                        echo ("<td>");
                        echo($i);
                        echo("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['date_of_complaint']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($rowr['hardware_id']));
                        echo ("</td>");
                        echo "<td>".$namer['name'].' '.$pron['spec']."</td>";
                        echo ("<td>");
                        echo(htmlentities($row['complaint_details']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['priority']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['complaint_by']));
                        echo ("</td>");
                        echo "<td>";
                        echo (htmlentities($location));
                        echo "</td>";
                        echo ("<td>");
                        echo('<a class="link-black" href="gorepairhardware.php?h_id='.$rowr['hardware_id'].'">'. 'Assign Job' . '</a>' . ' / ' . '<a class="link-black" href="deletehc.php?cb_id='.$row['hardware_complaint_book_id'].'">'. 'Delete' . '</a>');

                        echo ("</td>");
                        
                        $i++;
                    }
                    echo('</table>');
                }
                //Hardware Request ends

                $stmtcnt = $pdo->query("SELECT *,COUNT(*) FROM transfer_request");
                $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);
                $flag=0;
                while($row = $stmtcnt->fetch(PDO::FETCH_ASSOC))
                {
                    $stmtcnt2 = $pdo->prepare("SELECT COUNT(*) FROM system_transfer_report where trid =:trid");
                    $stmtcnt2->execute(array(':trid' => $row['transfer_request_id']));
                    $row2 = $stmtcnt->fetch(PDO::FETCH_ASSOC);
                    if($row2==True)
                    {
                        $flag++;    
                    }
                }
                if($flag==0)
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
                        echo('<a class="link-black" href="servicerpt.php?id='.$row['transfer_request_id'].'">'. 'Generate Report' . '</a>' . ' / ' . '<a class="link-black" href="deletetr.php?tr_id='.$row['transfer_request_id'].'">'. 'Delete' . '</a>');
                        echo ("</td>");
                        
                        $i++;    
                        }
                        
                    }
                    echo('</table>');
                }

                $stmt=$pdo->query("SELECT COUNT(*) FROM issue_request");
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                if($row['COUNT(*)']>0)
                {
                    echo "<h2>Issue Requests</h2>";
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Name</th><th>Requested Hardware</th><th>Purpose</th><th>Date Of Request</th><th>Action</th></tr>");
                    $stmt=$pdo->query("SELECT * FROM issue_request");
                    $i=1;
                    while($row2=$stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        echo "<tr>";
                            echo "<td>".$i++."</td>";
                            echo "<td>";
                                $stmtname=$pdo->prepare("SELECT first_name,last_name FROM member where id = :id");
                                $stmtname->execute(array(":id"=>$_SESSION['id']));
                                $name=$stmtname->fetch(PDO::FETCH_ASSOC);
                                echo $name['first_name'].' '.$name['last_name'];
                            echo "</td>";
                            echo "<td>";
                                $stmtname=$pdo->prepare("SELECT description from hardware where hardware_id = :name");
                                $stmtname->execute(array(":name"=>$row2['name_of_hardware']));
                                $name=$stmtname->fetch(PDO::FETCH_ASSOC);
                                echo $name['description'];
                            echo "</td>";
                            echo "<td>";
                                echo $row2['purpose'];
                            echo "</td>";
                            echo "<td>";
                                echo $row2['date_of_request'];
                            echo "</td>";
                            echo "<td>";
                                echo "<a class='link-black' href='issue_hardware.php?id=".$row2['issue_report_id']."'>Issue</a>/
                                <a class='link-red' href='delete_issue_request.php?id=".$row2['issue_report_id']."'>Delete</a>";
                            echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }

                $stmtcnt = $pdo->query("SELECT COUNT(*) FROM complaint_book WHERE completed IS NULL AND (processor IS NOT NULL OR ram IS NOT NULL OR harddisk IS NOT NULL OR monitor IS NOT NULL OR keyboard IS NOT NULL OR mouse IS NOT NULL)");
                $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

                if($row['COUNT(*)']!=='0')
                {
                    echo "<h2>Part Requests</h2>";
                    $i=1;
                    $stmtread = $pdo->query("SELECT * FROM complaint_book WHERE completed IS NULL AND (processor IS NOT NULL OR ram IS NOT NULL OR harddisk IS NOT NULL OR monitor IS NOT NULL OR keyboard IS NOT NULL OR mouse IS NOT NULL)");
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Date of Request</th><th>MAC_ADDR</th><th>Work For</th><th>Processor</th><th>Ram</th><th>Hard Disk</th><th>Monitor</th><th>Keyboard</th><th>Mouse</th><th>Action</th> </tr>");
                    while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
                    {
                        $stmtr = $pdo->prepare("SELECT MAC_ADDR FROM machine WHERE machine_id = :mid ");
                        $stmtr->execute(array(':mid' => $row['machine_id']));
                        $rowr = $stmtr->fetch(PDO::FETCH_ASSOC);

                        $stmtt = $pdo->prepare("SELECT * FROM temp WHERE machine_id = :mid ");
                        $stmtt->execute(array(':mid' => $row['machine_id']));
                        $rowt = $stmtt->fetch(PDO::FETCH_ASSOC);
                        if($rowt == false)
                        {
                            echo ("<tr>");
                            echo ("<td>");
                            echo($i);
                            echo("</td>");
                            echo ("<td>");
                            echo(htmlentities($row['DOPR']));
                            echo ("</td>");
                            echo ("<td>");
                            echo(htmlentities($rowr['MAC_ADDR']));
                            echo ("</td>");

                            $stmtwf = $pdo->prepare("SELECT * FROM member WHERE member_id = :mid ");
                            $stmtwf->execute(array(':mid' => $row['work_for']));
                            $rowwf = $stmtwf->fetch(PDO::FETCH_ASSOC);

                            echo ("<td>");
                            echo(htmlentities($rowwf['first_name']));
                            echo " ";
                            echo(htmlentities($rowwf['last_name']));
                            echo ("</td>");
                            echo ("<td>");
                            if($row['processor'] == NULL)
                            {
                                echo("NO");
                            }
                            else
                            {
                                echo("YES");
                            }
                            echo ("</td>");
                            echo ("<td>");
                            if($row['ram'] == NULL)
                            {
                                echo("NO");
                            }
                            else
                            {
                                echo("YES");
                            }
                            echo ("</td>");
                            echo ("<td>");
                            if($row['harddisk'] == NULL)
                            {
                                echo("NO");
                            }
                            else
                            {
                                echo("YES");
                            }
                            echo ("</td>");
                            echo ("<td>");
                            if($row['monitor'] == NULL)
                            {
                                echo("NO");
                            }
                            else
                            {
                                echo("YES");
                            }
                            echo ("</td>");
                            echo ("<td>");
                            if($row['keyboard'] == NULL)
                            {
                                echo("NO");
                            }
                            else
                            {
                                echo("YES");
                            }
                            echo ("</td>");
                            echo ("<td>");
                            if($row['mouse'] == NULL)
                            {
                                echo("NO");
                            }
                            else
                            {
                                echo("YES");
                            }
                            echo ("</td>");
                            echo ("<td>");
                            echo('<a class="link-black" href="issue_parts.php?cb_id='.$row['complaint_book_id'].'&mc_id='.$row['machine_id'].'">'. 'Issue Parts' . '</a>' . ' / ' . '<a class="link-black" href="deny_parts.php?cb_id='.$row['complaint_book_id'].'">'. 'Deny' . '</a>');

                            echo ("</td>");
                        }
                        
                        
                        $i++;
                    }
                    echo('</table>');
                }
            }

            else if($_SESSION['id']=='1')
            {


                $stmt=$pdo->query("SELECT COUNT(*) FROM hardware_position WHERE final_date='0000-00-00' OR  final_date='1990-00-00'");
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                if($row['COUNT(*)']>0)
                {
                    echo "<h2>Issued Devices</h2>";
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Member ID</th><th>Name</th><th>Hardware Issued</th>
                        <th>Hardware Desc.</th><th>Date</th><th>Hardware Company</th></tr>");
                    $stmt=$pdo->query("SELECT * FROM hardware_position");
                    $i=1;
                    while($row2=$stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        echo "<tr>";
                            echo "<td>".$i++."</td>";
                            echo "<td>";
                                $stmtname=$pdo->prepare("SELECT * FROM hardware_position where final_date = '0000-00-00' OR final_date = '1990-00-00'");
                                $stmtname->execute(array());
                                $name=$stmtname->fetch(PDO::FETCH_ASSOC);

                                $stmtname=$pdo->prepare("SELECT id FROM member where member_id=:mid");
                                $stmtname->execute(array(':mid'=>$name['member_id']));
                                $name=$stmtname->fetch(PDO::FETCH_ASSOC);


                                //echo $name['member_id'];
                            echo "</td>";
                            echo "<td>";
                                $stmtname1=$pdo->prepare("SELECT first_name,last_name FROM member where member_id=:mid");
                                $stmtname1->execute(array(':mid'=>$name['member_id']));
                                $name1=$stmtname1->fetch(PDO::FETCH_ASSOC);
                                echo $name1['first_name'].' '.$name1['last_name'];
                            echo "</td>";

                            echo "<td>";
                                $stmtname2=$pdo->prepare("SELECT name,description,company FROM hardware where hardware_id=:hid");
                                $stmtname2->execute(array(':hid'=>$name['hardware_id']));
                                $name2=$stmtname2->fetch(PDO::FETCH_ASSOC);
                                echo $name2['name'];
                            echo "</td>";

                            echo "<td>";

                                $stmtname3=$pdo->prepare("SELECT spec FROM specification where name_id=:nid");
                                $stmtname3->execute(array(':nid'=>$name2['description']));
                                $name3=$stmtname3->fetch(PDO::FETCH_ASSOC);

                                echo $name3['spec'];

                            echo "</td>";

                            echo "<td>";

                                echo $name['initial_date'];

                            echo "</td>";

                            $stmtname4=$pdo->prepare("SELECT name FROM company where company_id=:cid");
                                $stmtname4->execute(array(':cid'=>$name2['company']));
                                $name4=$stmtname4->fetch(PDO::FETCH_ASSOC);
                                echo $name4['name'];

                            echo "<td>";


                                echo "<a class='link-black' href='issue_hardware.php?id=".$row2['issue_report_id']."'>Issue</a>/
                                <a class='link-red' href='delete_issue_request.php?id=".$row2['issue_report_id']."'>Delete</a>";
                            echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
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

                        $stmtc = $pdo->prepare("SELECT * FROM complaint_book WHERE machine_id = :mid AND (processor = 1 OR ram = 1 OR harddisk = 1 OR mouse = 1 OR monitor = 1 OR keyboard = 1) ");
                        $stmtc->execute(array(':mid' => $row['machine_id']));
                        $rowc = $stmtc->fetch(PDO::FETCH_ASSOC);

                        $stmtc2 = $pdo->prepare("SELECT * FROM temp WHERE machine_id = :mid ");
                        $stmtc2->execute(array(':mid' => $row['machine_id']));
                        $rowc2 = $stmtc2->fetch(PDO::FETCH_ASSOC);

                        if($rowc == false)
                        {
                            echo('<a class="link-black "href="mcrepaired.php?mc_id='.$row['machine_id'].'">'. 'Job Done' . '/</a>');
                            echo('<a class="link-black "href="partsreq.php?mc_id='.$row['machine_id'].'">'. 'Parts Required' . '</a>');
                            echo ("</td>");                            
                        }
                        else if($rowc2 == false)
                        {
                            echo('<a class="link-black "href="partsreq.php?mc_id='.$row['machine_id'].'">'. 'Parts Required' . '</a>');
                            echo ("</td>");    
                        }
                        else
                        {
                            echo('<a class="link-black "href="mcrepaired.php?mc_id='.$row['machine_id'].'">'. 'Job Done' . '/</a>');
                            echo('<a class="link-black "href="partsreq.php?mc_id='.$row['machine_id'].'">'. 'Parts Required' . '</a>');
                            echo ("</td>");
    
                        }
                        
                        $i++;
                    }
                    echo('</table>');
                }
                //Hardware compaint book begins
                $stmtcnt = $pdo->query("SELECT COUNT(*) FROM hardware_complaint_book WHERE remarks IS NULL AND work_for = ".$_SESSION['id']."");
                $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);
               if($row['COUNT(*)']!=='0')
                {
                    echo "<h2>Repair Jobs</h2>";
                    $i=1;
                    $stmtread = $pdo->query("SELECT * FROM hardware_complaint_book WHERE remarks IS NULL AND work_for = ".$_SESSION['id']."");
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Date of Complaint</th><th>Hardware ID</th><th>Description</th><th>Complaint Details</th><th>Priority</th><th>Action</th> </tr>");
                    while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
                    {

                        $stmt=$pdo->prepare("SELECT description,name from hardware WHERE hardware_id =:hid");
                        $stmt->execute(array(":hid"=>$row['hardware_id']));
                        $rowdes=$stmt->fetch(PDO::FETCH_ASSOC);
                        $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :name_id");
                        $pro->execute(array(':name_id' => $rowdes['description']));
                        $name=$pdo->prepare("SELECT name from name where name_id = :name");
                        $name->execute(array(":name"=>$rowdes['name']));
                        $namer=$name->fetch(PDO::FETCH_ASSOC);
                        $pron = $pro->fetch(PDO::FETCH_ASSOC);
                        echo ("<tr>");
                        echo ("<td>");
                        echo($i);
                        echo("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['date_of_complaint']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['hardware_id']));
                        echo ("</td>");
                        echo "<td>".$namer['name'].' '.$pron['spec']."</td>";
                        echo ("<td>");
                        echo(htmlentities($row['complaint_details']));
                        echo ("</td>");
                        echo ("<td>");
                        echo(htmlentities($row['priority']));
                        echo ("</td>");
                        echo ("<td>");
                        echo('<a class="link-black "href="hardwarerepaired.php?hid='.$row['hardware_complaint_book_id'].'">'. 'Job Done' . '</a>');
                        echo ("</td>");                            
                        $i++;
                    }
                    echo('</table>');
                }
            }
        ?>

        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
