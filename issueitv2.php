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
        header("Location: homev2.php");
        return;
    }

    if(!isset($_GET['id']))
    {
        $_SESSION['error'].="No such page exsists<br>";
        header("Location:homev2.php");
        return;
    }
    $stmt=$pdo->prepare("SELECT COUNT(*) FROM issue_request WHERE issue_report_id = :id");
    $stmt->execute(array(":id"=>$_GET['id']));
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row['COUNT(*)']==0)
    {
        $_SESSION['error'].="No such page exsists<br>";
        header("Location:homev2.php");
        return;
    }
    $stmt ->execute(array(":id"=> $_GET['id']));
    $stmt=$pdo->prepare("SELECT * FROM issue_request where issue_report_id = :id");
    $stmt->execute(array(":id"=>$_GET['id']));
    $request=$stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("SELECT state FROM hardware WHERE hardware_id = :hid");
    $stmt ->execute(array(":hid"=>$_GET['dev_id']));
    $rowr=$stmt->fetch(PDO::FETCH_ASSOC);
    if($rowr['state']!=0)
    {
        $_SESSION['error']="Unable to Issue hardware ".$_GET['dev_id'].". This is either issue to someone or placed in a lab<br>".
        header("Location:homev2.php");
        return;
    }
    $stmt=$pdo->prepare("INSERT INTO hardware_position
        (hardware_id,member_id,initial_date,final_date)
        VALUES
        (:hid,:memberid,:idate,:fdate)
        ");
    $dat=date('y-m-d');
    $stmt->execute(array(
        ":hid" => $_GET['dev_id'],
        ":memberid" => $request['id'],
        ":idate" => $dat,
        ":fdate" => '1970-01-01'
    ));
    $stmt=$pdo->prepare("UPDATE hardware SET state = '2' WHERE hardware_id = :id");
    $stmt->execute(array(":id"=>$_GET['dev_id']));
    $stmt=$pdo->prepare("DELETE FROM issue_request WHERE issue_report_id = :id");
    $stmt->execute(array(":id"=>$_GET['id']));
    $_SESSION['success'].="Hardware Issued";
    header("Location:homev2.php");
    return;
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
                            <a class="nav-link" href="#"><?php echo "You are logged in as - ".$_SESSION['name']." ".$_SESSION['lname'] ?></a>
                        </li>
                        <li class="nav-item">
                                <a class="nav-link" href="logout.php">Sign Out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <br>
            
   <center><h1>LABS</h1></center>
   
  
    
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
        $stmtcnt = $pdo->query("SELECT COUNT(*) FROM lab");
        $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);
        if($row['COUNT(*)']!=='0')
        {
            $i=1;
            $stmtread = $pdo->query("SELECT * FROM lab order by name");
            echo ("<table class=\"table table-striped\">
                <tr> <th>S.no.</th><th>Lab Name</th><th>Department</th> </tr>");
            while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
            {
                echo ("<tr>");
                echo ("<td>");
                echo($i);
                echo("</td>");
                echo ("<td>");
                //Ghanta Consistent
                echo ("<a class='link-black' href='viewpcbylabv2.php?lab=".$row['lab_id'])."'>";
                echo (htmlentities($row['name']));
                echo ("</a>");
                echo ("</td>");
                echo ("<td>");
                echo ("<a class='link-black' href='viewpcbydeptv2.php?dept=".$row['department'])."'>";
                echo (htmlentities($row['department']));
                echo ("</a>");
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