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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>DigiTrack</title>
 
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="style3.css">
    <link rel="stylesheet" href="css/form-style.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">

    <!-- Font Awesome JS --> 
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <style>

th, td {
  padding: 15px;
  text-align:center;
}
td:hover{
    background-color:#c394ff;
    text-decoration:underline;
}
</style>
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

                    <button type="button" id="sidebarCollapse" class="btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Menu</span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                        
                        <li class="nav-item">
                            <a class="nav-link" href="#"><?php
   echo "You are logged in as - ".$_SESSION['name']." ".$_SESSION['lname']
?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Sign Out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <br>
            
   <center>   <h1>MACHINES</h1></center>

    
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
        $stmtcnt = $pdo->query("SELECT COUNT(*) FROM machine");
        $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);

        if($row['COUNT(*)']!=='0')
        {
            $i=1;
            $stmtread = $pdo->prepare("SELECT lab_id FROM lab where  department = :dpt");
            $stmtread->execute(array(':dpt' => $_GET['dept']));
            echo ("<table class=\"table table-striped\">
                <tr> <th>S.no.</th><th>MAC ADDRESS</th><th>Processor</th><th>RAM</th><th>Storage</th><th>OS</th><th>Keyboard</th><th>Mouse</th><th>Monitor</th><th>DOP</th><th>Price</th><th>Lab</th> <th>State</th> </tr>");
            while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
            {
                $pcf=$pdo->prepare("SELECT * FROM position where lab_id=:labid AND final_date='1970-01-01'");
                $pcf->execute(array(':labid'=>$row['lab_id']));
                while($row2=$pcf->fetch(PDO::FETCH_ASSOC))
                {
                    $mid=$row2['machine_id'];
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
                $labname=$pdo->prepare("SELECT name FROM lab WHERE lab_id = :lab_id");
                $labname->execute(array(":lab_id"=>$row['lab_id']));
                $labname=$labname->fetch(PDO::FETCH_ASSOC);
                $labname=$labname['name'];
                echo($monitorn['spec']);
                        echo ("</td>");
                        
                        echo ("<td>");
                        echo(htmlentities($row2['DOP']));
                        echo ("</td>");
                        
                        echo ("<td>");
                        echo(htmlentities($row2['price']));
                        echo ("</td>");
                        echo("<td>");
                        echo(htmlentities($labname));
                        echo("</td>");
                        echo ("<td>");
                        echo(htmlentities($row2['state']));
                        echo ("</td>");
                        $i++;
                    }
                }
            }
            echo('</table>');
        }
                echo("<br><center><h2>Devices</h2><center>");
        $stmtread = $pdo->query("SELECT hardware.`hardware_id`, hardware.`company`, hardware.`description`, hardware.`price`, hardware.`grn`, hardware.`name`, hardware.`state`, hardware.`supplier`, hardware_position.`lab_id` FROM hardware JOIN hardware_position ON hardware.hardware_id = hardware_position.hardware_id  AND (hardware_position.final_date = '0000-00-00' OR hardware_position.final_date = '1970-01-01') ORDER BY name");
                echo ("<table class=\"table table-striped\">
                    <tr> <th>S.no.</th><th>Name</th><th>description</th><th>Company</th><th>GRN</th><th>Supplier</th><th>Lab</th></tr>");
                while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
                {
                    $stmtdept = $pdo->prepare("SELECT department FROM lab WHERE lab_id = :labid");
                    $stmtdept->execute(array(":labid"=>$row['lab_id']));
                    $row2 = $stmtdept->fetch(PDO::FETCH_ASSOC);
                    if($row2['department']!=$_GET['dept'])
                        continue;
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
                $labname=$pdo->prepare("SELECT name FROM lab WHERE lab_id = :lab_id");
                $labname->execute(array(":lab_id"=>$row['lab_id']));
                $labname=$labname->fetch(PDO::FETCH_ASSOC);
                $labname=$labname['name'];
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
                        echo(htmlentities($labname));
                    echo("</td>");   
                    $i++;
                }
                echo('</table>');
    ?>

   </div>
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