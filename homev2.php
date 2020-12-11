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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>DigiTrack</title>
 
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="style3.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

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

                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Menu</span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto"> 
                        <li class="nav-item">
                                <a class="nav-link" href="logout.php"> Home |</a>
                            </li>  
                            <li class="nav-item">
                            <a class="nav-link" href="#"><?php
   echo "HI - ".$_SESSION['name']." ".$_SESSION['lname']
?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Signout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <?php
            if ( isset($_SESSION['success']))
            {
                echo('<p style="color: green;">'.$_SESSION['success']."</p>");
                unset($_SESSION['success']);
            }
            if ( isset($_SESSION['error']))
            {
                echo('<p style="color: red;">'.$_SESSION['error']."</p>\n");
                unset($_SESSION['error']);
            }

            if($_SESSION['role']=='0')
            {
                $stmtcnt = $pdo->query("SELECT COUNT(*) FROM complaint_book JOIN repair_history ON complaint_book.machine_id = repair_history.machine_id WHERE complaint_book.remarks IS NOT NULL AND repair_history.fault IS NULL");
                $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

                if($row['COUNT(*)']!=='0')
                {
                    echo "<h2>Repaired Machines</h2>";
                    $i=1;
                    $stmtread = $pdo->query("SELECT * FROM complaint_book JOIN repair_history ON complaint_book.complaint_book_id = repair_history.complaint_book_id WHERE complaint_book.remarks IS NOT NULL AND repair_history.fault IS NULL");
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
                    echo "<h2>Computer Transfer Requests</h2>";
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
                        if($row['processor']=="NULL")
                            echo("Any");
                        else
                            echo(htmlentities($row['processor']));
                        echo ("</td>");
                        echo ("<td>");
                        if($row['ram']=="NULL")
                            echo "Any";
                        else
                            echo(htmlentities($row['ram']));
                        echo ("</td>");
                        echo ("<td>");
                        if($row['hdd']=="NULL")
                            echo "Any";
                        else
                            echo(htmlentities($row['hdd']));
                        echo ("</td>");
                        echo ("<td>");
                        if($row['os']=="NULL")
                            echo "Any";
                        else
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
                //ISSUE BEGINS
                $stmt=$pdo->query("SELECT COUNT(*) FROM issue_request");
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                if($row['COUNT(*)']>0)
                {
                    echo "<h2>Issue Hardware Requests</h2>";
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Name</th><th>Requested Hardware</th><th>Purpose</th><th>Date Of Request</th><th>Action</th></tr>");
                    $stmt=$pdo->query("SELECT * FROM issue_request");
                    $i=1;
                    while($row2=$stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        echo "<tr>";
                            echo "<td>".$i++."</td>";
                            echo "<td>";
                                $stmtname=$pdo->prepare("SELECT first_name,last_name FROM member where member_id = :id");
                                $stmtname->execute(array(":id"=>$row2['id']));
                                $name=$stmtname->fetch(PDO::FETCH_ASSOC);
                                echo $name['first_name'].' '.$name['last_name'];
                            echo "</td>";
                            echo "<td>";
                            /*
                                $stmtname=$pdo->prepare("SELECT description from hardware where hardware_id = :name");
                                $stmtname->execute(array(":name"=>$row2['name_of_hardware']));
                                $name=$stmtname->fetch(PDO::FETCH_ASSOC);
                                echo $name['description'];
                            */
                            $stmtname=$pdo->prepare("SELECT name from name WHERE name_id = :name");
                            $stmtname->execute(array(":name"=>$row2['name_of_hardware']));
                            $rowname=$stmtname->fetch(PDO::FETCH_ASSOC);
                            echo $rowname['name'];
                            echo "</td>";
                            echo "<td>";
                                echo $row2['purpose'];
                            echo "</td>";
                            echo "<td>";
                                echo $row2['date_of_request'];
                            echo "</td>";
                            echo "<td>";
                                echo "<a class='link-black' href='issue_hardware.php?id=".$row2['issue_report_id']."&name_id=".$row2['name_of_hardware']."'>Issue</a>/
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
                    while ( $rowpartsdisplay = $stmtread->fetch(PDO::FETCH_ASSOC) )
                    {
                        $stmtr = $pdo->prepare("SELECT MAC_ADDR FROM machine WHERE machine_id = :mid ");
                        $stmtr->execute(array(':mid' => $rowpartsdisplay['machine_id']));
                        $rowr = $stmtr->fetch(PDO::FETCH_ASSOC);

                        $stmtt = $pdo->prepare("SELECT * FROM temp WHERE machine_id = :mid AND completed = 1");
                        $stmtt->execute(array(':mid' => $rowpartsdisplay['machine_id']));
                        $rowt = $stmtt->fetch(PDO::FETCH_ASSOC);
                        if($rowt == false)
                        {
                            echo ("<tr>");
                            echo ("<td>");
                            echo($i);
                            echo("</td>");
                            echo ("<td>");
                            echo(htmlentities($rowpartsdisplay['Date_of_complaint']));
                            echo ("</td>");
                            echo ("<td>");
                            echo(htmlentities($rowr['MAC_ADDR']));
                            echo ("</td>");

                            $stmtwf = $pdo->prepare("SELECT * FROM member WHERE member_id = :mid ");
                            $stmtwf->execute(array(':mid' => $rowpartsdisplay['work_for']));
                            $rowwf = $stmtwf->fetch(PDO::FETCH_ASSOC);

                            echo ("<td>");
                            echo(htmlentities($rowwf['first_name']));
                            echo " ";
                            echo(htmlentities($rowwf['last_name']));
                            echo ("</td>");
                            echo ("<td>");
                            if($rowpartsdisplay['processor'] == NULL)
                            {
                                echo("NO");
                            }
                            else
                            {
                                echo("YES");
                            }
                            echo ("</td>");
                            echo ("<td>");
                            if($rowpartsdisplay['ram'] == NULL)
                            {
                                echo("NO");
                            }
                            else
                            {
                                echo("YES");
                            }
                            echo ("</td>");
                            echo ("<td>");
                            if($rowpartsdisplay['harddisk'] == NULL)
                            {
                                echo("NO");
                            }
                            else
                            {
                                echo("YES");
                            }
                            echo ("</td>");
                            echo ("<td>");
                            if($rowpartsdisplay['monitor'] == NULL)
                            {
                                echo("NO");
                            }
                            else
                            {
                                echo("YES");
                            }
                            echo ("</td>");
                            echo ("<td>");
                            if($rowpartsdisplay['keyboard'] == NULL)
                            {
                                echo("NO");
                            }
                            else
                            {
                                echo("YES");
                            }
                            echo ("</td>");
                            echo ("<td>");
                            if($rowpartsdisplay['mouse'] == NULL)
                            {
                                echo("NO");
                            }
                            else
                            {
                                echo("YES");
                            }
                            echo ("</td>");
                            echo ("<td>");
                            echo('<a class="link-black" href="issue_parts.php?cb_id='.$rowpartsdisplay['complaint_book_id'].'&mc_id='.$rowpartsdisplay['machine_id'].'">'. 'Issue Parts' . '</a>' . ' / ' . '<a class="link-black" href="deny_parts.php?cb_id='.$rowpartsdisplay['complaint_book_id'].'">'. 'Deny' . '</a>');

                            echo ("</td>");
                        }
                        
                        
                        $i++;
                    }
                    echo('</table>');
                }
            }
            else if($_SESSION['role']=='2')
            {
                $stmtcnt = $pdo->query("SELECT COUNT(*) FROM complaint_book WHERE remarks IS NULL AND work_for = ".$_SESSION['id']."");
                $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

               if($row['COUNT(*)']!=='0')
                {
                    echo "<h2>Repair Jobs PC</h2>";
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

                        $stmtc = $pdo->prepare("SELECT * FROM complaint_book WHERE machine_id = :mid AND (processor = 1 OR ram = 1 OR harddisk = 1 OR mouse =1  OR monitor =1  OR keyboard = 1) AND (completed IS NULL) ");
//                        var_dump($row['machine_id']);
                        $stmtc->execute(array(':mid' => $row['machine_id']));
                        $rowc = $stmtc->fetch(PDO::FETCH_ASSOC);

                        $stmtc2 = $pdo->prepare("SELECT * FROM temp WHERE machine_id = :mid AND completed = 1");
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
                            echo('<a class="link-black "href="partsreq.php?mc_id='.$row['machine_id'].'">'. 'Parts Required' . '/</a>');
                            echo('<a class="link-black "href="partsasked.php?mc_id='.$row['machine_id'].'">'. 'Parts Requested' . '</a>');
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
                    echo "<h2>Repair Jobs Hardware</h2>";
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
            else if($_SESSION['role']=='1')
            {
                $stmt=$pdo->query("SELECT COUNT(*) FROM hardware_position WHERE final_date='0000-00-00' OR  final_date='1970-01-01'");
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                if($row['COUNT(*)']>0)
                {
                    echo "<h2>Issued Devices</h2>";
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Member ID</th><th>Name</th><th>Hardware Issued</th>
                        <th>Hardware Desc.</th><th>Date</th><th>Hardware Company</th></tr>");
                    $stmt=$pdo->query("SELECT * FROM hardware_position WHERE final_date='0000-00-00' OR  final_date='1970-01-01'");
                    $i=1;
                    while($row2=$stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        echo "<tr>";
                            echo "<td>".$i++."</td>";
                            echo "<td>";
                                //$stmtname7=$pdo->prepare("SELECT * FROM hardware_position where final_date = '0000-00-00' OR final_date = '1990-00-00'");
                               // $stmtname7->execute(array());
                               // $name7=$stmtname7->fetch(PDO::FETCH_ASSOC);

                                $stmtname=$pdo->prepare("SELECT * FROM member where member_id=:mid");
                                $stmtname->execute(array(':mid'=>$row2['member_id']));
                                $name=$stmtname->fetch(PDO::FETCH_ASSOC);


                                echo $name['id'];
                            echo "</td>";
                            echo "<td>";
                                //$stmtname1=$pdo->prepare("SELECT first_name,last_name FROM member where member_id=:mid");
                                //$stmtname1->execute(array(':mid'=>$name['id']));
                                //$name1=$stmtname1->fetch(PDO::FETCH_ASSOC);

                                echo $name['first_name'].' '.$name['last_name'];
                            echo "</td>";

                            echo "<td>";
                                $stmtname2=$pdo->prepare("SELECT name,description,company FROM hardware where hardware_id=:hid");
                                $stmtname2->execute(array(':hid'=>$row2['hardware_id']));
                                $name2=$stmtname2->fetch(PDO::FETCH_ASSOC);
                                //echo $name2['name'];

                                $stmtname0=$pdo->prepare("SELECT name FROM name where name_id=:nnid");
                                $stmtname0->execute(array(':nnid'=>$name2['name']));
                                $name0=$stmtname0->fetch(PDO::FETCH_ASSOC);
                                echo $name0['name'];

                            echo "</td>";

                            echo "<td>";

                                $stmtname3=$pdo->prepare("SELECT spec FROM specification where spec_id=:nid");
                                $stmtname3->execute(array(':nid'=>$name2['description']));
                                $name3=$stmtname3->fetch(PDO::FETCH_ASSOC);

                                echo $name3['spec'];

                            echo "</td>";

                            echo "<td>";

                                echo $row2['initial_date'];

                            echo "</td>";

                            
                            
                           echo "<td>";

                           $stmtname4=$pdo->prepare("SELECT name FROM company where company_id=:cid");
                                $stmtname4->execute(array(':cid'=>$name2['company']));
                                $name4=$stmtname4->fetch(PDO::FETCH_ASSOC);
                                echo $name4['name'];

                            echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            }
        ?></div>
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