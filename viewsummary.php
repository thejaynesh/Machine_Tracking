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
    /*
    $stmt = $pdo->query("SELECT * FROM machine");
    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
    {
        $stmtn = $pdo->prepare("SELECT lab_id FROM position where machine_id = :mid AND final_date = '1970-01-01'");

        $stmtn->execute(array(':mid' => $row['machine_id']));
        $rown = $stmtn->fetch(PDO::FETCH_ASSOC);//rown will store labid

        $stmtn2 = $pdo->prepare("SELECT name FROM lab where lab_id = :lid");
        $stmtn2->execute(array(':lid' => $rown['lab_id']));
        $rownlabid = $stmtn2->fetch(PDO::FETCH_ASSOC);//rowlabid will store lab name

        //echo $row['processor'];
        $stmtrow=$pdo->prepare("SELECT spec FROM specification JOIN hardware ON(specification.spec_id = hardware.description AND hardware.hardware_id=:hid)"
            ); 
        $stmtrow->execute(array(":hid"=>$row['processor']));
        $processorspec=$stmtrow->fetch(PDO::FETCH_ASSOC);//processorspec will store description of processor
        $stmtrow=$pdo->prepare("SELECT spec FROM specification JOIN hardware ON(specification.spec_id = hardware.description AND hardware.hardware_id = :hid)"
            );
        $stmtrow->execute(array(":hid"=>$row['ram']));
        $ramspec=$stmtrow->fetch(PDO::FETCH_ASSOC);//ramspec will store description of ram
        $stmtrow=$pdo->prepare("SELECT spec FROM specification JOIN hardware ON(specification.spec_id = hardware.description AND  hardware.hardware_id = :hid)"
            );
        $stmtrow->execute(array(":hid"=>$row['memory']));
        $memoryspec=$stmtrow->fetch(PDO::FETCH_ASSOC);//memoryspec will store description of memory

        //Inserting in machine_view
        $stmt = $pdo->prepare("INSERT INTO machine_view(`machine_id`, `MAC_ADDR`, `processor`, `ram`, `memory`, `DOP`, `price`, `state`, `os`, `monitor`, `keyboard`, `mouse`, `grn`, `lab`) VALUES(:machineid, :macadd, :processor,:ram,:memory,:DOP,:price,:state,:os,:monitor,:keyboard,:mouse,:grn,:lab )
            ");
        $stmt ->execute(array(
            ":machineid" =>$row[',
            ":macadd",
            ":processor",
            ":ram",
            ":memory",
            ":DOP",
            ":price",
            ":state",
            ":os",
            ":monitor",
            ":keyboard",
            ":mouse",
            ":grn",
            ":lab"
        ));

    }
    */

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
    <h1>STOCK TABLE</h1>
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
            $i=1;
            $stmtread = $pdo->query("SELECT *,COUNT(*) FROM machine GROUP BY grn,state ORDER BY DOP");
            echo ("<table class=\"table table-striped\">
                <tr> <th>S.no.</th><th>Processor</th><th>RAM</th><th>Storage</th><th>OS</th><th>Keyboard</th><th>Mouse</th><th>Monitor</th><th>DOP</th><th>Price</th> <th>State</th><th>GRN</th><th>Quantity</th></tr>");
            while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
            {
             
                $processor = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                $processor->execute(array(':hid' => $row['processor']));
                $processorn = $processor->fetch(PDO::FETCH_ASSOC);

                $ram = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                $ram->execute(array(':hid' => $row['ram']));
                $ramn = $ram->fetch(PDO::FETCH_ASSOC);

                $memory = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                $memory->execute(array(':hid' => $row['memory']));
                $memoryn = $memory->fetch(PDO::FETCH_ASSOC);

                $keyboard = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                $keyboard->execute(array(':hid' => $row['keyboard']));
                $keyboardn = $keyboard->fetch(PDO::FETCH_ASSOC);

                $mouse = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                $mouse->execute(array(':hid' => $row['mouse']));
                $mousen = $mouse->fetch(PDO::FETCH_ASSOC);

                $monitor = $pdo->prepare("SELECT description FROM hardware where hardware_id = :hid");
                $monitor->execute(array(':hid' => $row['monitor']));
                $monitorn = $monitor->fetch(PDO::FETCH_ASSOC);

                echo ("<tr>");
                echo ("<td>");
                echo($i);
                echo("</td>");
                
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
                echo(htmlentities($row['os']));
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
                echo(htmlentities($row['DOP']));
                echo ("</td>");
                echo ("<td>");
                echo(htmlentities($row['price']));
                echo ("</td>");
                echo ("<td>");
                echo(htmlentities($row['state']));
                echo ("</td>");           
                echo "<td>";
                echo(htmlentities($row['grn']));
                echo "</td>";
                echo "<td>";
                echo(htmlentities($row['COUNT(*)']));
                echo "</td>";     
                $i++;
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