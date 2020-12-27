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
            
   <center><h1>Devices</h1></center>
   <br>
   <h6>Note - You can also Position, Unposition and Delete devices from here. To delete a device it need to be unpostioned first.</h6><br>
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
    ?>
    <form method="post" class="form-inline">
            <label id="processor">Device Name&nbsp</label>
                <select class="form-control" id="chillana" name="chillana">
                    <?php
                        $qr=$pdo->query("SELECT DISTINCT(name) from name");
                        while($row=$qr->fetch(PDO::FETCH_ASSOC))
                        {
                            echo "<option>". $row['name']."</option>";
                        }
                    ?>    
                </select>

            <label id="state">&nbsp&nbspState</label>&nbsp
                <select class="form-control" id="state" name="state">           
                    <option value="0">Unpositioned</option>
                    <option value="1">Positioned</option>
                    <option value="2">Issued</option> 
                </select>

            &nbsp &nbsp<div class="form-submit"><input class="Submit" id="Submit" type="submit" name="submit"></div>
        </form>    
    
    <?php

        
        //echo('<p><a href="logout.php">Logout</a></p>');
        if(isset($_POST['chillana']))
        {
            $stmtcnt = $pdo->query("SELECT COUNT(*) FROM hardware");
            $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);
            if($row['COUNT(*)']!=='0')
            {
                $i=1;
                $stmtread=$pdo->prepare("SELECT name_id from name where name =:name");
                $stmtread->execute(array(":name"=>$_POST['chillana']));
                $nameid=$stmtread->fetch(PDO::FETCH_ASSOC);

                $stmtread = $pdo->prepare("SELECT * FROM hardware  where name= :name AND state=:state ORDER BY name");
                $stmtread->execute(array(":name"=>$nameid['name_id'],":state"=>$_POST['state']));
                echo ("<table class=\"table table-striped\">
                    <tr> <th>S.no.</th><th>Name</th><th>description</th><th>Company</th><th>GRN</th><th>Supplier</th><th>State</th><th>Date of Purchase</th><th>Action</th></tr>");
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

                    echo ("<tr>");
                    echo ("<td>");
                    echo($i);
                    echo("</td>");
                    
                    echo ("<td>");
                    echo(htmlentities($_POST['chillana']));
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
                    if($_POST['state']==0)
                        echo "Unpositoned";
                    else if($_POST['state']==1)
                        echo "Positioned";
                    else if($_POST['state']==2)
                        echo "Issued";
                    echo ("</td>")  ; 
                    echo ("<td>");
                    echo(htmlentities($row['DOP']));
                    echo("</td>");
                    echo ("<td>");
                    if($_POST['state']==0)
                    {
                        echo('<a class="link-black" href="deletedevv2.php?dev_id='.$row['hardware_id'].'">'. 'Delete Device ' . '</a>');

                        $flag=0;
                        $stmtcheck = $pdo->prepare("SELECT * FROM machine where processor = :hid");
                        $stmtcheck->execute(array(':hid' => $row['hardware_id']));
                        $rowcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
                        if($rowcheck != FALSE)
                        {
                            $flag++;
                        }
                        $stmtcheck = $pdo->prepare("SELECT * FROM machine where ram = :hid");
                        $stmtcheck->execute(array(':hid' => $row['hardware_id']));
                        $rowcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
                        if($rowcheck != FALSE)
                        {
                            $flag++;
                        }
                        $stmtcheck = $pdo->prepare("SELECT * FROM machine where memory = :hid");
                        $stmtcheck->execute(array(':hid' => $row['hardware_id']));
                        $rowcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
                        if($rowcheck != FALSE)
                        {
                            $flag++;
                        }
                        $stmtcheck = $pdo->prepare("SELECT * FROM machine where monitor = :hid");
                        $stmtcheck->execute(array(':hid' => $row['hardware_id']));
                        $rowcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
                        if($rowcheck != FALSE)
                        {
                            $flag++;
                        }
                        $stmtcheck = $pdo->prepare("SELECT * FROM machine where keyboard = :hid");
                        $stmtcheck->execute(array(':hid' => $row['hardware_id']));
                        $rowcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
                        if($rowcheck != FALSE)
                        {
                            $flag++;
                        }
                        $stmtcheck = $pdo->prepare("SELECT * FROM machine where mouse = :hid");
                        $stmtcheck->execute(array(':hid' => $row['hardware_id']));
                        $rowcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
                        if($rowcheck != FALSE)
                        {
                            $flag++;
                        }

                        if($flag==0)
                        echo('<a class="link-black" href="posdevv2.php?dev_id='.$row['hardware_id'].'">'. ' / Position Device ' . '</a>');
                    }
                    else if($_POST['state']==2)
                    {
                        echo('<a class="link-black" href="returndevv2.php?dev_id='.$row['hardware_id'].'">'. 'Return Device ' . '</a>');
                    }
                    else if($_POST['state']==1)
                    {
                        $flag=0;
                        $stmtcheck = $pdo->prepare("SELECT * FROM machine where processor = :hid");
                        $stmtcheck->execute(array(':hid' => $row['hardware_id']));
                        $rowcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
                        if($rowcheck != FALSE)
                        {
                            $flag++;
                        }
                        $stmtcheck = $pdo->prepare("SELECT * FROM machine where ram = :hid");
                        $stmtcheck->execute(array(':hid' => $row['hardware_id']));
                        $rowcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
                        if($rowcheck != FALSE)
                        {
                            $flag++;
                        }
                        $stmtcheck = $pdo->prepare("SELECT * FROM machine where memory = :hid");
                        $stmtcheck->execute(array(':hid' => $row['hardware_id']));
                        $rowcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
                        if($rowcheck != FALSE)
                        {
                            $flag++;
                        }
                        $stmtcheck = $pdo->prepare("SELECT * FROM machine where monitor = :hid");
                        $stmtcheck->execute(array(':hid' => $row['hardware_id']));
                        $rowcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
                        if($rowcheck != FALSE)
                        {
                            $flag++;
                        }
                        $stmtcheck = $pdo->prepare("SELECT * FROM machine where keyboard = :hid");
                        $stmtcheck->execute(array(':hid' => $row['hardware_id']));
                        $rowcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
                        if($rowcheck != FALSE)
                        {
                            $flag++;
                        }
                        $stmtcheck = $pdo->prepare("SELECT * FROM machine where mouse = :hid");
                        $stmtcheck->execute(array(':hid' => $row['hardware_id']));
                        $rowcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
                        if($rowcheck != FALSE)
                        {
                            $flag++;
                        }

                        if($flag==0)
                        echo('<a class="link-black" href="unposdevv2.php?dev_id='.$row['hardware_id'].'">'. ' Unposition Device ' . '</a>');
                    }
                    echo("</td>");    
                    $i++;
                }
                echo('</table>');
            }
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