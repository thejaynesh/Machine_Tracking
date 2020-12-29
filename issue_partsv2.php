<?php
    session_start();
    require_once "pdo.php";
    
    if(isset($_POST['cancel']))
    {
        header("Location: index.php");
        return;
    }
    if( !isset($_SESSION['id'])&&$_SESSION['id']!=0 )
    {
        die('ACCESS DENIED');
    }

    $stmt=$pdo->prepare("SELECT * FROM complaint_book WHERE complaint_book_id = :id");
    $stmt->execute(array(":id"=>$_GET['cb_id']));
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if(isset($_POST['submit']) )
    {
        $flag=0;

        if($row['processor']==1)
        {
            if(strlen($_POST['processor'] < 1))
            {
                $flag++;
            }
            else
            {
                $stmtq=$pdo->prepare("SELECT * FROM hardware WHERE hardware_id = :hid");
                $stmtq->execute(array(":hid"=>$_POST['processor']));
                $rowq=$stmtq->fetch(PDO::FETCH_ASSOC);

                $stmtname=$pdo->prepare("SELECT * FROM name WHERE name_id = :nid");
                $stmtname->execute(array(":nid"=>$rowq['name']));
                $rowname=$stmtname->fetch(PDO::FETCH_ASSOC);

                if($rowq['state']!='0' || $rowname['name']!='processor')
                {
                    $_SESSION['error'] = "Wrong Hardware ID selected<br>";
                    header("Location:homev2.php");
                    return;
                }
            }
        }
        if($row['ram']==1)
        {
            if(strlen($_POST['ram'] < 1))
            {
                $flag++;
            }
            else
            {
                $stmtq=$pdo->prepare("SELECT * FROM hardware WHERE hardware_id = :hid");
                $stmtq->execute(array(":hid"=>$_POST['ram']));
                $rowq=$stmtq->fetch(PDO::FETCH_ASSOC);

                $stmtname=$pdo->prepare("SELECT * FROM name WHERE name_id = :nid");
                $stmtname->execute(array(":nid"=>$rowq['name']));
                $rowname=$stmtname->fetch(PDO::FETCH_ASSOC);

                if($rowq['state']!='0' || $rowname['name']!='ram')
                {
                    $_SESSION['error'] = "Wrong Hardware ID selected<br>";
                    header("Location:homev2.php");
                    return;
                }
            }
        }
        if($row['harddisk']==1)
        {
            if(strlen($_POST['harddisk'] < 1))
            {
                $flag++;
            }
            else
            {
                $stmtq=$pdo->prepare("SELECT * FROM hardware WHERE hardware_id = :hid");
                $stmtq->execute(array(":hid"=>$_POST['harddisk']));
                $rowq=$stmtq->fetch(PDO::FETCH_ASSOC);

                $stmtname=$pdo->prepare("SELECT * FROM name WHERE name_id = :nid");
                $stmtname->execute(array(":nid"=>$rowq['name']));
                $rowname=$stmtname->fetch(PDO::FETCH_ASSOC);

                if($rowq['state']!='0' || $rowname['name']!='harddisk')
                {
                    $_SESSION['error'] = "Wrong Hardware ID selected<br>";
                    header("Location:homev2.php");
                    return;
                }
            }
        }
        if($row['monitor']==1)
        {
            if(strlen($_POST['monitor'] < 1))
            {
                $flag++;
            }
            else
            {
                $stmtq=$pdo->prepare("SELECT * FROM hardware WHERE hardware_id = :hid");
                $stmtq->execute(array(":hid"=>$_POST['monitor']));
                $rowq=$stmtq->fetch(PDO::FETCH_ASSOC);

                $stmtname=$pdo->prepare("SELECT * FROM name WHERE name_id = :nid");
                $stmtname->execute(array(":nid"=>$rowq['name']));
                $rowname=$stmtname->fetch(PDO::FETCH_ASSOC);

                if($rowq['state']!='0' || $rowname['name']!='monitor')
                {
                    $_SESSION['error'] = "Wrong Hardware ID selected<br>";
                    header("Location:homev2.php");
                    return;
                }
            }
        }
        if($row['keyboard']==1)
        {
            if(strlen($_POST['keyboard'] < 1))
            {
                $flag++;
            }
            else
            {
                $stmtq=$pdo->prepare("SELECT * FROM hardware WHERE hardware_id = :hid");
                $stmtq->execute(array(":hid"=>$_POST['keyboard']));
                $rowq=$stmtq->fetch(PDO::FETCH_ASSOC);

                $stmtname=$pdo->prepare("SELECT * FROM name WHERE name_id = :nid");
                $stmtname->execute(array(":nid"=>$rowq['name']));
                $rowname=$stmtname->fetch(PDO::FETCH_ASSOC);

                if($rowq['state']!='0' || $rowname['name']!='keyboard')
                {
                    $_SESSION['error'] = "Wrong Hardware ID selected";
                    header("Location:homev2.php");
                    return;
                }
            }
        }
        if($row['mouse']==1)
        {
            if(strlen($_POST['mouse'] < 1))
            {
                $flag++;
            }
            else
            {
                $stmtq=$pdo->prepare("SELECT * FROM hardware WHERE hardware_id = :hid");
                $stmtq->execute(array(":hid"=>$_POST['mouse']));
                $rowq=$stmtq->fetch(PDO::FETCH_ASSOC);

                $stmtname=$pdo->prepare("SELECT * FROM name WHERE name_id = :nid");
                $stmtname->execute(array(":nid"=>$rowq['name']));
                $rowname=$stmtname->fetch(PDO::FETCH_ASSOC);

                if($rowq['state']!='0' || $rowname['name']!='mouse')
                {
                    $_SESSION['error'] = "Wrong Hardware ID selected";
                    header("Location:homev2.php");
                    return;
                }
            }
        }

        if($flag==0)
        {
                $date=date('y-m-d');
                $stmt = $pdo->prepare('UPDATE temp SET processor = :processor, ram = :ram, mouse = :mouse, harddisk = :harddisk, keyboard = :keyboard, monitor = :monitor, completed = 1 WHERE machine_id = :mid');
                    $stmt->execute(array(':mid' => $_GET['mc_id'], ':processor' => $_POST['processor'], ':ram' => $_POST['ram'], ':mouse' => $_POST['mouse'], ':harddisk' => $_POST['harddisk'], ':keyboard' => $_POST['keyboard'], ':monitor' => $_POST['monitor']));
                $_SESSION['success'] = "Parts Issued Successfully";
                header("Location:homev2.php");
                return;
            
        }
        
        else
        {
                 
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: homev2.php');
            return;
        }
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
            
   <center><h1>Parts Request</h1></center>

    <div id="error" style="color: red; margin-left: 90px; margin-bottom: 20px;"></div>
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

    <form method="POST" action="issue_partsv2.php?cb_id=<?= $_GET['cb_id']?>&mc_id=<?= $_GET['mc_id']?>" class="register-form">
    
            <div class='form-row'>
            <div class='form-group'>
        <?php
        if ($row['processor']=='1')
            echo "
            <div class='form-input'>
                <label>
                    Processor
                </label>
                <input type='text' name='processor' required='' class='form-control' placeholder='Enter Hardware ID' >
            </div>";
        if ($row['ram']=='1')
        echo "
        <div class='form-input'>
            <label>
                Ram
            </label>
            <input type='text' name='ram' required='' class='form-control' placeholder='Enter Hardware ID'>
        </div>";
        if ($row['harddisk']=='1')
            echo "
            <div class='form-input'>
                <label>
                    Harddisk
                </label>
                <input type='text' name='harddisk' required='' class='form-control' placeholder='Enter Hardware ID'>
            </div>";
        if ($row['mouse']=='1')
            echo "
            <div class='form-input'>
                <label>
                    Mouse
                </label>
                <input type='text' name='mouse' required='' class='form-control' placeholder='Enter Hardware ID'>
            </div>";
        if ($row['keyboard']=='1')
            echo "
            <div class='form-input'>
                <label>
                    Keyboard
                </label>
                <input type='text' name='keyboard' required='' class='form-control' placeholder='Enter Hardware ID'>
            </div>";
        if ($row['monitor']=='1')
            echo "
            <div class='form-input'>
                <label>
                    Monitor
                </label>
                <input type='text' name='monitor' required='' class='form-control' placeholder='Enter Hardware ID'>
            </div>";
        ?>
        <div class="form-submit">
        
        <input type="submit" value="Assign parts" name="submit" id="Submit" class="Submit">
        <input type="reset" value="Reset" class="submit" id="reset" name="reset" />
            </div>
    </form>
    <hr> 
    
    <form  method="post" action="" class="register-form" >
            <label id="processor">Search Device </label>
            <div class='form-input'>
                <select class="form-control" id="chillana" name="chillana" >
                    <?php
                        $qr=$pdo->query("SELECT DISTINCT(name) from name");
                        while($row=$qr->fetch(PDO::FETCH_ASSOC))
                        {
                            echo "<option>". $row['name']."</option>";
                        }
                    ?>    
                </select>
            </div>
                    
            <button class="btn btn-my" id="searchhardware" class="Submit" value="Search">Search</button>
            </div>
        </form>
<?php
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
                $stmtread->execute(array(":name"=>$nameid['name_id'],":state"=>0));
                echo ("<table class=\"table table-striped col-xs-12\">
                    <tr> <th>S.no.</th><th>Hardware ID</th><th>Name</th><th>description</th><th>Company</th><th>GRN</th><th>Supplier</th></tr>");
                while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
                {
                    $stmtn = $pdo->prepare("SELECT name FROM company where company_id = :cname ");
                    $stmtn->execute(array(':cname' => $row['company']));
                    $cname = $stmtn->fetch(PDO::FETCH_ASSOC);

                    $supplier = $pdo->prepare("SELECT supname FROM supplier where sup_id = :sid");
                    $supplier->execute(array(':sid' => $row['supplier']));
                    $supplierid = $supplier->fetch(PDO::FETCH_ASSOC);

                    echo ("<tr>");
                    echo ("<td>");
                    echo($i);
                    echo("</td>");
                    echo "<td>";
                    echo $row['hardware_id'];
                    echo "</td>";
                    echo ("<td>");
                    echo(htmlentities($_POST['chillana']));
                    echo ("</td>");
                    echo ("<td>");

                    $stmtn1 = $pdo->prepare("SELECT spec FROM specification where spec_id = :name ");
                    $stmtn1->execute(array(':name' => $row['description']));
                    $cname1 = $stmtn1->fetch(PDO::FETCH_ASSOC);
                    echo(htmlentities($cname1['spec']));
                    
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($cname['name']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($row['grn']));
                    echo ("</td>");
                    echo ("<td>");
                    echo(htmlentities($supplierid['supname']));
                    echo ("</td>");     
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