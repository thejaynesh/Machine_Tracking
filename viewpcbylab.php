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
        //echo('<p><a href="logout.php">Logout</a></p>');
        $stmtcnt = $pdo->query("SELECT COUNT(*) FROM machine");
        $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

        if($row['COUNT(*)']!=='0')
        {
            $i=1;
            $stmtread = $pdo->prepare("SELECT * FROM position where lab_id = :lid and final_date='1970-01-01'");
            $stmtread->execute(array(':lid' => $_GET['lab'] ));
            echo ("<table class=\"table table-striped\">
                <tr> <th>S.no.</th><th>MAC ADDRESS</th><th>Processor</th><th>RAM</th><th>Storage</th><th>OS</th><th>Keyboard</th><th>Mouse</th><th>Monitor</th><th>DOP</th><th>Price</th> <th>State</th> </tr>");
            while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
            {
                $mid=$row['machine_id'];
                $read2= $pdo -> query("SELECT * FROM machine where machine_id='$mid'");

                while($row2 = $read2->fetch(PDO::FETCH_ASSOC))
                {
                    
                    $stmtreadn = $pdo->prepare("SELECT * FROM position where machine_id = :mid");
                    $stmtreadn->execute(array(':mid' => $row2['machine_id']));
                    $rown=$stmtreadn->fetch(PDO::FETCH_ASSOC);

                    $processor = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                    $processor->execute(array(':hid' => $row2['processor']));
                    $processorn = $processor->fetch(PDO::FETCH_ASSOC);

                    $ram = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                    $ram->execute(array(':hid' => $row2['ram']));
                    $ramn = $ram->fetch(PDO::FETCH_ASSOC);

                    $memory = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                    $memory->execute(array(':hid' => $row2['memory']));
                    $memoryn = $memory->fetch(PDO::FETCH_ASSOC);


                    $keyboard = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                    $keyboard->execute(array(':hid' => $row2['keyboard']));
                    $keyboardn = $keyboard->fetch(PDO::FETCH_ASSOC);

                    $mouse = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                    $mouse->execute(array(':hid' => $row2['mouse']));
                    $mousen = $mouse->fetch(PDO::FETCH_ASSOC);

                    $monitor = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                    $monitor->execute(array(':hid' => $row2['monitor']));
                    $monitorn = $monitor->fetch(PDO::FETCH_ASSOC);

                    echo ("<tr>");
                    echo ("<td>");
                    echo($i);
                    echo("</td>");
                    echo ("<td>");

                    echo(htmlentities($row2['MAC_ADDR']));
                    echo ("</td>");

                    echo ("<td>");
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $pro->execute(array(':spec_id' => $processorn['description']));
                $pron = $pro->fetch(PDO::FETCH_ASSOC);
                echo($pron['spec']);
                echo ("</td>");

                    echo ("<td>");
                 $ram = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $ram->execute(array(':spec_id' => $ramn['description']));
                $ramn = $ram->fetch(PDO::FETCH_ASSOC);
                echo($ramn['spec']);
                echo ("</td>");

                    echo ("<td>");
                 $memory = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $memory->execute(array(':spec_id' => $memoryn['description']));
                $memoryn = $memory->fetch(PDO::FETCH_ASSOC);
                echo($memoryn['spec']);
                echo ("</td>");

                    echo ("<td>");
                    echo(htmlentities($row2['os']));
                    echo ("</td>");

                    echo ("<td>");
                $keyboard = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $keyboard->execute(array(':spec_id' => $keyboardn['description']));
                $keyboardn = $keyboard->fetch(PDO::FETCH_ASSOC);
                echo($keyboardn['spec']);
                echo ("</td>");
                

                    echo ("<td>");
                $mouse = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $mouse->execute(array(':spec_id' => $mousen['description']));
                $mousen = $mouse->fetch(PDO::FETCH_ASSOC);
                echo($mousen['spec']);
                echo ("</td>");
               
                echo ("<td>");
                $monitor = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $monitor->execute(array(':spec_id' => $monitorn['description']));
                $monitorn = $monitor->fetch(PDO::FETCH_ASSOC);
                echo($monitorn['spec']);
                echo ("</td>");
                
                    echo ("<td>");
                    echo(htmlentities($row2['DOP']));
                    echo ("</td>");

                    echo ("<td>");
                    echo(htmlentities($row2['price']));
                    echo ("</td>");

                    echo ("<td>");
                    echo(htmlentities($row2['state']));
                    echo ("</td>");

                    $i++;
                }
            }
            echo('</table>');
        }
        echo("<h2>Devices</h2>");
        $stmtread = $pdo->prepare("SELECT hardware.`hardware_id`, hardware.`company`, hardware.`description`, hardware.`price`, hardware.`grn`, hardware.`name`, hardware.`state`, hardware.`supplier` FROM hardware JOIN hardware_position ON hardware.hardware_id = hardware_position.hardware_id AND hardware_position.lab_id =:labid AND (hardware_position.final_date = '0000-00-00' OR hardware_position.final_date = '1970-01-01') ORDER BY name");
                $stmtread->execute(array(":labid"=>$_GET['lab']));
                echo ("<table class=\"table table-striped\">
                    <tr> <th>S.no.</th><th>Name</th><th>description</th><th>Company</th><th>GRN</th><th>Supplier</th></tr>");
                while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
                {
                    $stmtn = $pdo->prepare("SELECT name FROM company where company_id = :cname ");
                    $stmtn->execute(array(':cname' => $row['company']));
                    $cname = $stmtn->fetch(PDO::FETCH_ASSOC);

                    $supplier = $pdo->prepare("SELECT supname FROM supplier where sup_id = :sid");
                    $supplier->execute(array(':sid' => $row['supplier']));
                    $supplierid = $supplier->fetch(PDO::FETCH_ASSOC);

                    $spec = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                    $spec->execute(array(':spec_id' => $row['description']));
                    $specn = $spec->fetch(PDO::FETCH_ASSOC);

                    $namefind = $pdo->prepare("SELECT name.name FROM name JOIN hardware ON hardware.name = name.name_id AND hardware.hardware_id = :hid");
                    $namefind->execute(array(":hid"=>$row['hardware_id']));
                    $namefind = $namefind->fetch(PDO::FETCH_ASSOC);

                    echo ("<tr>");
                    echo ("<td>");
                    echo($i);
                    echo("</td>");
                    
                    echo ("<td>");
                    echo(htmlentities($namefind['name']));
                    echo ("</td>");

                
                    echo ("<td>");
                    echo($specn['spec']);
                    echo ("</td>");
               

                   // echo ("<td>");
                   // echo(htmlentities($row['description']));
                    //echo ("</td>");

                    echo ("<td>");
                    echo(htmlentities($cname['name']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($row['grn']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($supplierid['supname']));
                    echo ("</td>");
                    echo ("<td>");   
                    $i++;
                }
                echo('</table>');
    ?>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>