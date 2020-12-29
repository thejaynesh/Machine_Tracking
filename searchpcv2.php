<?php
    session_start();
    require_once "pdo.php";
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
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
        if(isset($_POST['mno'])&&$_POST['mno']=='')
        $mnofilter='*';
    else if(isset($_POST['mno']))
        $processorfilter=$_POST['mno'];
 
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
            <br><br><br>
            <center> 
            <div class="page-header">
       <form method="POST" class="form-inline" style="align-items:center; padding =10">
       <!-- <label id="processor" style="padding =10;">&nbsp&nbsp&nbsp&nbsp Machine No. &nbsp</label>
       <input type="text" name="mno" id="mno" placeholder="Machine No.">&nbsp -->
            <label id="processor" style="padding =10;">Processor&nbsp</label>
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
                </select>&nbsp
            <label id="ram">RAM&nbsp</label>
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
                </select>&nbsp
            <label id="memory">Memory&nbsp</label>
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
                </select>&nbsp
            <label id="os">OS&nbsp</label>
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
                </select>&nbsp
            <input class="btn btn-my"type="submit" name="submit">
        </form>
    
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
        /*if(isset($_POST['mno'])){

        }*/
        if(isset($_POST['processor'])||isset($_POST['ram'])||isset($_POST['memory'])||isset($_POST['os']))
        {
            //Checking if machine exsists
            $stmtcnt = $pdo->query("SELECT COUNT(*) FROM machine ");
            $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);
            if($row['COUNT(*)']!=='0')
            {
                echo("<h1>MACHINES</h1>");
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
<center>
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