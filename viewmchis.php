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
    $stmtcnt = $pdo->prepare("SELECT COUNT(*) FROM machine WHERE MAC_ADDR = :mac_addr");
    $stmtcnt->execute(array(':mac_addr' => $_GET['mac_addr']));
        $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

        if($row['COUNT(*)']==='0')
        {
            $_SESSION['error'] = "This Machine does not exist<br>";
            header('Location: viewmchistory.php');
            return;
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
</head>
<body>
            <div class="wrapper">
       <?php if (isset($_SESSION['id'])&&$_SESSION['role']=='0') include "navbar.php"; 
                else if(isset($_SESSION['id'])&&$_SESSION['role']=='1')  include "navbar_faculty.php";
                else include "navbar_tech.php";?>
    <div class="container-fluid row" id="content">
    <div class="page-header">
    <h1>MACHINE HISTORY</h1>
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
        $stmtcnt = $pdo->query("SELECT COUNT(*) FROM machine ");
        $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

        if($row['COUNT(*)']!=='0')
        {
            $stmt = $pdo->prepare('SELECT * FROM machine WHERE MAC_ADDR = :mac_addr');
            $stmt->execute(array(':mac_addr' => $_GET['mac_addr']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $mid = $row['machine_id'];

            $i=1;
            $stmtread = $pdo->prepare("SELECT * FROM position WHERE machine_id = :mid ORDER BY initial_date");
            $stmtread->execute(array(':mid' => $mid));

            echo("<h2> POSITION HISTORY </h2>");

            echo ("<table class=\"table table-striped\">
                <tr> <th>S.no.</th><th>MAC ADDRESS</th><th>LAB NAME</th><th>FROM</th><th>TO</th>");
            while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
            {
                $stmt = $pdo->prepare('SELECT * FROM lab WHERE lab_id = :lid');
                $stmt->execute(array(':lid' => $row['lab_id']));
                $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                $lname = $row2['name'];

                echo ("<tr>");
                echo ("<td>");
                echo($i);
                echo("</td>");
                echo ("<td>");
                echo(htmlentities($_GET['mac_addr']));
                echo ("</td>");
                echo ("<td>");
                echo(htmlentities($lname));
                echo ("</td>");
                echo ("<td>");
                echo(htmlentities($row['initial_date']));
                echo ("</td>");
                echo ("<td>");
                echo(htmlentities($row['final_date']));
                echo ("</td>");
                $i++;
            }
            echo('</table>');


            $i=1;
            $stmtread = $pdo->prepare("SELECT * FROM repair_history WHERE machine_id = :mid ORDER BY initial_date");
            $stmtread->execute(array(':mid' => $mid));
            echo("<h2> REPAIR HISTORY </h2>");
            echo ("<table class=\"table table-striped\">
                <tr> <th>S.no.</th><th>MAC ADDRESS</th><th>FROM</th><th>TO</th><th>FAULT</th><th>COST</th>");
            while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
            {
                echo ("<tr>");
                echo ("<td>");
                echo($i);
                echo("</td>");
                echo ("<td>");
                echo(htmlentities($_GET['mac_addr']));
                echo ("</td>");
                echo ("<td>");
                echo(htmlentities($row['initial_date']));
                echo ("</td>");
                echo ("<td>");
                echo(htmlentities($row['final_date']));
                echo ("</td>");
                echo ("<td>");
                echo(htmlentities($row['fault']));
                echo ("</td>");
                echo ("<td>");
                echo(htmlentities($row['cost']));
                echo ("</td>");
                $i++;
            }
            echo('</table>');


            $i=1;
            $stmtread = $pdo->prepare("SELECT * FROM upgrade_history WHERE machine_id = :mid ORDER BY dateofupgrade");
            $stmtread->execute(array(':mid' => $mid));
            echo("<h2> UPGRADE HISTORY </h2>");
            echo ("<table class=\"table table-striped\">
                <tr> <th>S.no.</th><th>MAC ADDRESS</th><th>Initial Processor</th><th>Initial Ram</th><th>Initial Storage</th><th>Final Processor</th><th>Final Ram</th><th>Final Storage</th><th>Date</th>");
            while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
            {
                $processor = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                $processor->execute(array(':hid' => $row['processori']));
                $processori = $processor->fetch(PDO::FETCH_ASSOC);

                $ram = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                $ram->execute(array(':hid' => $row['rami']));
                $rami = $ram->fetch(PDO::FETCH_ASSOC);

                $memory = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                $memory->execute(array(':hid' => $row['memoryi']));
                $memoryi = $memory->fetch(PDO::FETCH_ASSOC);

                $processor = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                $processor->execute(array(':hid' => $row['processorf']));
                $processorf = $processor->fetch(PDO::FETCH_ASSOC);

                $ram = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                $ram->execute(array(':hid' => $row['ramf']));
                $ramf = $ram->fetch(PDO::FETCH_ASSOC);

                $memory = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                $memory->execute(array(':hid' => $row['memoryf']));
                $memoryf = $memory->fetch(PDO::FETCH_ASSOC);

                echo ("<tr>");
                echo ("<td>");
                echo($i);
                echo("</td>");
                
                echo ("<td>");
                echo(htmlentities($_GET['mac_addr']));
                echo ("</td>");
                
                echo ("<td>");
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $pro->execute(array(':spec_id' => $processori['description']));
                $proi = $pro->fetch(PDO::FETCH_ASSOC);
                echo($proi['spec']);
                echo ("</td>");
                
                echo ("<td>");
                $ram = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $ram->execute(array(':spec_id' => $rami['description']));
                $rami = $ram->fetch(PDO::FETCH_ASSOC);
                echo($rami['spec']);
                echo ("</td>");
                
                echo ("<td>");
                $memory = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $memory->execute(array(':spec_id' => $memoryi['description']));
                $memoryi = $memory->fetch(PDO::FETCH_ASSOC);
                echo($memoryi['spec']);
                echo ("</td>");
                
                echo ("<td>");
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $pro->execute(array(':spec_id' => $processorf['description']));
                $prof = $pro->fetch(PDO::FETCH_ASSOC);
                echo($prof['spec']);
                echo ("</td>");
                
                echo ("<td>");
                $ram = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $ram->execute(array(':spec_id' => $ramf['description']));
                $ramf = $ram->fetch(PDO::FETCH_ASSOC);
                echo($ramf['spec']);
                echo ("</td>");
                
                echo ("<td>");
                $memory = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $memory->execute(array(':spec_id' => $memoryf['description']));
                $memoryf = $memory->fetch(PDO::FETCH_ASSOC);
                echo($memoryf['spec']);
                echo ("</td>");
                
                echo ("<td>");
                echo($row['dateofupgrade']);
                echo ("</td>");
                $i++;
            }
            echo('</table>');
        }
        else
        {
            $_SESSION['error'] = "This Machine does not exist<br>";
            header('Location: viewmchis.php');
            return;
        }
    ?>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript"src="script.js"></script>
</body>
</html>