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
        header("Location: home.php");
        return;
    }

    if(!isset($_GET['id']))
    {
        $_SESSION['error'].="No such page exsists<br>";
        header("Location:home.php");
        return;
    }
    $stmt=$pdo->prepare("SELECT COUNT(*) FROM issue_request WHERE issue_report_id = :id");
    $stmt->execute(array(":id"=>$_GET['id']));
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row['COUNT(*)']==0)
    {
        $_SESSION['error'].="No such page exsists<br>";
        header("Location:home.php");
        return;
    }
    /*
    $stmt ->execute(array(":id"=> $_GET['id']));
    $stmt=$pdo->prepare("SELECT * FROM issue_request where issue_report_id = :id");
    $stmt->execute(array(":id"=>$_GET['id']));
    $request=$stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("SELECT state FROM hardware WHERE hardware_id = :hid");
    $stmt ->execute(array(":hid"=>$request['id']));
    $rowr=$stmt->fetch(PDO::FETCH_ASSOC);
    if($rowr['state']!=0)
    {
        $_SESSION['error']="Unable to Issue hardware ".$request['hid'].". This is either issue to someone or placed in a lab".
        header("Location:home.php");
        return;
    }
    $stmt=$pdo->prepare("INSERT INTO hardware_position
        (hardware_id,member_id,initial_date,final_date)
        VALUES
        (:hid,:memberid,:idate,:fdate)
        ");
    $dat=date('y-m-d');
    $stmt->execute(array(
        ":hid" => $request['name_of_hardware'],
        ":memberid" => $request['id'],
        ":idate" => $dat,
        ":fdate" => '0-0-0'
    ));
    $stmt=$pdo->prepare("UPDATE hardware SET state = '2' WHERE hardware_id = :id");
    $stmt->execute(array(":id"=>$request['name_of_hardware']));
    $stmt=$pdo->prepare("DELETE FROM issue_request WHERE issue_report_id = :id");
    $stmt->execute(array(":id"=>$_GET['id']));
    $_SESSION['success'].="Hardware Issued";
    header("Location:home.php");
    return;
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
    <style>
        .input-group-addon {
        min-width:150px;
        text-align:left;
    }
    </style>
</head>
<body>
    <div class="wrapper">
    <?php if (isset($_SESSION['id'])&&$_SESSION['role']=='0') include "navbar.php"; 
                else if(isset($_SESSION['id'])&&$_SESSION['role']=='1')  include "navbar_faculty.php";
                else include "navbar_tech.php";?>
      <div class="container-fluid row" id="content">
        <div class="page-header">
            <?php
            $stmtread = $pdo->prepare("SELECT * FROM hardware  where name= :name AND state=:state ORDER BY name");
                $stmtread->execute(array(":name"=>$_GET['name_id'],":state"=>0));
                echo ("<table class=\"table table-striped\">
                    <tr> <th>S.no.</th><th>Name</th><th>description</th><th>Company</th><th>GRN</th><th>Supplier</th><th>Action</th></tr>");
                $i=0;
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
                    $stmttmp=$pdo->prepare("SELECT name from name WHERE name_id =:name");
                    $stmttmp->execute(array(":name"=>$_GET['name_id']));
                    $rowtmp=$stmttmp->fetch(PDO::FETCH_ASSOC);
                    echo(htmlentities($rowtmp['name']));
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
                    echo("<a class='link-black' href='issueit.php?dev_id=".$row['hardware_id']."&id=".$_GET['id']."'>". "Issue Device " . "</a>");
                    echo("</td>");    
                    $i++;
                }
                echo('</table>');
            ?>
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>