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
    <h1>PARTS REQUESTED</h1>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript"src="script.js"></script>
</body>
</html>