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
            <center> 
            <h1>MACHINES</h1>
    
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
            $stmtread = $pdo->query("SELECT * FROM machine ORDER BY MAC_ADDR");
            echo ("<table class=\"table table-striped\">
                <tr> <th>S.no.</th><th>MAC ADDRESS</th><th>Processor</th><th>RAM</th><th>Storage</th><th>OS</th><th>Keyboard</th><th>Mouse</th><th>Monitor</th><th>DOP</th><th>Price</th><th>Location</th> <th>State</th></tr>");
            while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
            {
                $stmtn = $pdo->prepare("SELECT lab_id FROM position where machine_id = :mid AND final_date = '1970-01-01'");
                $stmtn->execute(array(':mid' => $row['machine_id']));
                $rown = $stmtn->fetch(PDO::FETCH_ASSOC);
                $stmtn2 = $pdo->prepare("SELECT name FROM lab where lab_id = :lid");
                $stmtn2->execute(array(':lid' => $rown['lab_id']));
                $rown2 = $stmtn2->fetch(PDO::FETCH_ASSOC);

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

                echo(htmlentities($row['MAC_ADDR']));
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
                echo(htmlentities($rown2['name']));
                echo ("</td>");
                echo ("<td>");
                echo(htmlentities($row['state']));
                echo ("</td>");                
                $i++;
            }
            echo('</table>');
        }
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