<?php
    session_start();
    require_once "pdo.php";
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
    if( $_SESSION['role'] != '2' )
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
            
   <center><h1>PARTS REQUESTED</h1></center>
  
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
        $stmtcnt = $pdo->prepare("SELECT COUNT(*) FROM complaint_book WHERE machine_id = :mcid AND completed IS NULL ");
        $stmtcnt->execute(array(':mcid' => $_GET['mc_id']));
                $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);
                if($row['COUNT(*)']!=='0')
                {
                    $i=1;
                    $stmtread = $pdo->prepare("SELECT * FROM complaint_book WHERE machine_id = :mcid AND completed IS NULL ");
                    $stmtread->execute(array(':mcid' => $_GET['mc_id']));
                    echo ("<table class=\"table table-striped\">
                        <tr> <th>S.no.</th><th>Date of Request</th><th>MAC_ADDR</th><th>Work For</th><th>Processor</th><th>Ram</th><th>Hard Disk</th><th>Monitor</th><th>Keyboard</th><th>Mouse</th> </tr>");
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
                            
                        }
                        
                        
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