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
                    header("Location:home.php");
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
                    header("Location:home.php");
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
                    header("Location:home.php");
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
                    header("Location:home.php");
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
                    header("Location:home.php");
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
                    header("Location:home.php");
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
                header("Location:home.php");
                return;
            
        }
        
        else
        {
                 
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: home.php');
            return;
        }
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
    <style>
        .input-group-addon {
        min-width:150px;
        text-align:left;
    }
    </style>
</head>
<body>
                   <div class="wrapper">
         <?php if ($_SESSION['id']=='0') include "navbar.php"; else include "navbar_index.php" ;?>
    <div class="container-fluid row" id="content">
    <div class="page-header">
    <h1>Parts Request</h1>
    </div>
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

    <form method="POST" action="issue_parts.php?cb_id=<?= $_GET['cb_id']?>&mc_id=<?= $_GET['mc_id']?>" class="col-xs-5">
        <?php
        if ($row['processor']=='1')
            echo "
            <div class='input-group'>
                <div class='input-group-addon'>
                    Processor
                </div>
                <input type='text' name='processor' required='' class='form-control' placeholder='Enter Hardware ID' >
            </div><br>";
        if ($row['ram']=='1')
        echo "
        <div class='input-group'>
            <div class='input-group-addon'>
                Ram
            </div>
            <input type='text' name='ram' required='' class='form-control' placeholder='Enter Hardware ID'>
        </div><br>";
        if ($row['harddisk']=='1')
            echo "
            <div class='input-group'>
                <div class='input-group-addon'>
                    Harddisk
                </div>
                <input type='text' name='harddisk' required='' class='form-control' placeholder='Enter Hardware ID'>
            </div><br>";
        if ($row['mouse']=='1')
            echo "
            <div class='input-group'>
                <div class='input-group-addon'>
                    Mouse
                </div>
                <input type='text' name='mouse' required='' class='form-control' placeholder='Enter Hardware ID'>
            </div><br>";
        if ($row['keyboard']=='1')
            echo "
            <div class='input-group'>
                <div class='input-group-addon'>
                    Keyboard
                </div>
                <input type='text' name='keyboard' required='' class='form-control' placeholder='Enter Hardware ID'>
            </div><br>";
        if ($row['monitor']=='1')
            echo "
            <div class='input-group'>
                <div class='input-group-addon'>
                    Monitor
                </div>
                <input type='text' name='monitor' required='' class='form-control' placeholder='Enter Hardware ID'>
            </div><br>";
        ?>
        <input type="submit" name="submit" class="btn btn-my">
    </form>
    
    <form  method="post" action="" class="form-inline col-xs-12" >
            <label id="processor">Search Device </label>
                <select class="form-control" id="chillana" name="chillana" >
                    <?php
                        $qr=$pdo->query("SELECT DISTINCT(name) from name");
                        while($row=$qr->fetch(PDO::FETCH_ASSOC))
                        {
                            echo "<option>". $row['name']."</option>";
                        }
                    ?>    
                </select>

            <button class="btn btn-my" id="searchhardware" value="Search">Search</button>
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
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
