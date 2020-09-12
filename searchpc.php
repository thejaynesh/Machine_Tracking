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

    $processor = $pdo->query("SELECT name_id FROM name where name = 'processor'");
    $processorn = $processor->fetch(PDO::FETCH_ASSOC);
    $processorn=$processorn['name_id'];

    $ram= $pdo->query("SELECT name_id FROM name where name = 'ram'");
    $ramn = $ram->fetch(PDO::FETCH_ASSOC);
    $ramn=$ramn['name_id'];

    $memory = $pdo->query("SELECT name_id FROM name where name = 'harddisk'");
    $memoryn = $memory->fetch(PDO::FETCH_ASSOC);
    $memoryn=$memoryn['name_id'];

    if(isset($_POST['processor'])&&$_POST['processor']=='-1')
        $processorfilter='*';
    else if(isset($_POST['processor']))
        $processorfilter=$_POST['processor'];
    if(isset($_POST['ram'])&&$_POST['ram']=='-1')
        $ramfilter='*';
    else if(isset($_POST['ram']))
        $ramfilter=$_POST['ram'];
    if(isset($_POST['memory'])&&$_POST['memory']=='-1')
        $memoryfilter='*';
    else if(isset($_POST['memory']))
        $memoryfilter=$_POST['memory'];
    if(isset($_POST['os'])&&$_POST['os']=='-1')
        $osfilter='*';
    else if(isset($_POST['os']))
        $osfilter=$_POST['os'];
 
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
            <label id="processor">Processor</label>
                <select class="form-control" id="processor" name="processor">
                    <option value="-1">Any</option>
                    <?php

                    //This query will select all distinct(description) and hardware_id from hardware table and name will be equal to processor number selected in line 13

                        $qr=$pdo->prepare("SELECT spec from specification WHERE name_id=:processorn");
                        $qr->execute(array(":processorn"=>$processorn));
                        while($row=$qr->fetch(PDO::FETCH_ASSOC))
                        {

                            echo "<option>". $row['spec']."</option>   ";
                        }
                    ?>
                </select>
            <label id="ram">RAM</label>
                <select class="form-control" id="ram" name="ram">
                    <option value='-1'>Any</option>
                    <?php

                    //This query will select all distinct(description) and hardware_id from hardware table and name will be equal to ram number selected in line 13

                        $qr=$pdo->prepare("SELECT spec from specification WHERE name_id=:ramn");
                        $qr->execute(array(":ramn"=>$ramn));
                        while($row=$qr->fetch(PDO::FETCH_ASSOC))
                        {

                            echo "<option>". $row['spec']."</option>";
                        }
                    ?>
                </select>
            <label id="memory">Memory</label>
                <select class="form-control" id="memory" name="memory">
                    <option value='-1'>Any</option>
                    <?php

                    //This query will select all distinct(description) and hardware_id from hardware table and name will be equal to memory number selected in line 13

                        $qr=$pdo->prepare("SELECT spec from specification WHERE name_id=:memoryn");
                        $qr->execute(array(":memoryn"=>$memoryn));
                       // $qr=$pdo->query("SELECT distinct(os) from machine");
                        while($row=$qr->fetch(PDO::FETCH_ASSOC))
                        {
                            echo "<option>". $row['spec']."</option>";
                            //echo "<option>". $row['memory']."</option>";
                        }
                    ?>
                </select>
            <label id="os">OS</label>
                <select class="form-control" id="os" name="os">
                    <option value='-1'>Any</option>
                    <?php
                    //AS OS is stored directly in machine table simple query is used
                        $qr=$pdo->query("SELECT distinct(os) from machine");
                        while($row=$qr->fetch(PDO::FETCH_ASSOC))
                        {
                            echo "<option>". $row['os']."</option>";
                        }
                    ?>
                </select>
            <input class="btn btn-my"type="submit" name="submit">
        </form>
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

        //Now this code does all the magic
        if(isset($_POST['processor'])||isset($_POST['ram'])||isset($_POST['memory'])||isset($_POST['os']))
        {
            //Checking if machine exsists
            $stmtcnt = $pdo->query("SELECT COUNT(*) FROM machine ");
            $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);
            if($row['COUNT(*)']!=='0')
            {
                echo ("<table class=\"table table-striped\">
                    <tr> <th>S.no.</th><th>MAC ADDRESS</th><th>Processor</th><th>RAM</th><th>Storage</th><th>OS</th><th>DOP</th><th>Price</th><th>Location</th> <th>State</th></tr>");
                $stmt=$pdo->query("SELECT * FROM machine");
                $i=1;
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {   
                    $stmtn = $pdo->prepare("SELECT lab_id FROM position where machine_id = :mid AND final_date = '1970-01-01'");

                    $stmtn->execute(array(':mid' => $row['machine_id']));
                    $rown = $stmtn->fetch(PDO::FETCH_ASSOC);

                    $stmtn2 = $pdo->prepare("SELECT name FROM lab where lab_id = :lid");
                    $stmtn2->execute(array(':lid' => $rown['lab_id']));
                    $rownlabid = $stmtn2->fetch(PDO::FETCH_ASSOC);

                    //echo $row['processor'];
                    $stmtrow=$pdo->prepare("SELECT spec FROM specification JOIN hardware ON(specification.spec_id = hardware.description AND hardware.hardware_id=:hid)"
                        ); 
                    $stmtrow->execute(array(":hid"=>$row['processor']));
                    $processorspec=$stmtrow->fetch(PDO::FETCH_ASSOC);
                    $stmtrow=$pdo->prepare("SELECT spec FROM specification JOIN hardware ON(specification.spec_id = hardware.description AND hardware.hardware_id = :hid)"
                        );
                    $stmtrow->execute(array(":hid"=>$row['ram']));
                    $ramspec=$stmtrow->fetch(PDO::FETCH_ASSOC);
                    $stmtrow=$pdo->prepare("SELECT spec FROM specification JOIN hardware ON(specification.spec_id = hardware.description AND  hardware.hardware_id = :hid)"
                        );
                    $stmtrow->execute(array(":hid"=>$row['memory']));
                    $memoryspec=$stmtrow->fetch(PDO::FETCH_ASSOC);
                    if($_POST['processor']!='-1'&&$processorspec['spec']!=$_POST['processor'])
                        continue;
                    if($_POST['ram']!='-1'&&$ramspec['spec']!=$_POST['ram'])
                        continue;
                    if($_POST['memory']!='-1'&&$memoryspec['spec']!=$_POST['memory'])
                        continue;
                   if($_POST['os']!='-1'&&$row['os']!=$_POST['os'])
                        continue;
                     
                    echo ("<tr>");
                    echo ("<td>");
                    echo($i);
                    echo("</td>");
                    echo ("<td>");
                    echo(htmlentities($row['MAC_ADDR']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($processorspec['spec']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($ramspec['spec']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($memoryspec['spec']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($row['os']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($row['DOP']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($row['price']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($rownlabid['name']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($row['state']));
                    echo ("</td>");
                    $i++;
                }
            }

        }
    ?>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>