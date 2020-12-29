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
        header("Location: index.php");
        return;
    }
    if(!isset($_POST['department']))
    {
        $autoc=$pdo->prepare("SELECT department,purpose,quantity from transfer_request where transfer_request_id = :tid");
        $autoc->execute(array(':tid'=>$_GET['id']));
        $rowauto=$autoc->fetch(PDO::FETCH_ASSOC);
        $dept=$rowauto['department'];
        $pur=$rowauto['purpose']; 
        $qty=$rowauto['quantity'];         
    }
    if(!isset($_GET['id']))
    {
        $_SESSION['error']="Transfer Request not Found<br>";
        header("Location:homev2.php");
        return;
    }
    else if(isset($_POST['department']) )
    {
        /*
        if ( strlen($_POST['name']) < 1 || strlen($_POST['department']) < 1 || strlen($_POST['purpose']) < 1|| strlen($_POST['quantity']) < 1)
        {
            $_SESSION['error'] = "All Fields are required";
            header('Location: servicerptv2.php');
            return;
        }
        */
       // else
                $flag1=0;
                 for($i=1;$i<=$_POST['totalqty'];$i++)
                 {
                    $getmid=$pdo->prepare('SELECT COUNT(*),machine_id from machine where MAC_ADDR = :mid and state=:act');
                    $getmid->execute( array(':mid' => $_POST["machine".$i],':act' => 'ACTIVE'));
                    $row=$getmid->fetch(PDO::FETCH_ASSOC);
                    if($row['COUNT(*)']==0)
                    {
                        $flag1=1;
                        $_SESSION['error']="Machine".$row['machine_id']."Not found \n";
                        header("Location:homev2.php");
                        return;
                    }
                 }
                 $stmt2=$pdo->prepare('SELECT lab_id from lab where name = :labid');
                 $stmt2->execute( array(':labid' => $_POST['labid'] ));
                 $row=$stmt2->fetch(PDO::FETCH_ASSOC);
                 $labid=$row['lab_id'];
                
                $stmt = $pdo->prepare('INSERT INTO system_transfer_report( department, purpose, lab_id ,date_of_assignment,trid) VALUES (:dept, :purpose, :labid, :dat,:trid)');
                    $stmt->execute(array(':dept' => $_POST['department'], ':purpose' => $_POST['purpose'], ':labid'=>$labid,':dat' => date('y-m-d'),':trid'=>$_GET['id']));

                $strid = $pdo->lastInsertId();
                 if($flag1==1)
                 {
                    return;
                 }
                 for($i =1 ;$i<=$_POST['totalqty'];$i++)
                 {
                    $getmid=$pdo->prepare('SELECT machine_id,COUNT(*) from machine where MAC_ADDR = :mid and state=:act');
                    $getmid->execute( array(':mid' => $_POST["machine".$i],':act' => 'ACTIVE'));
                    $row=$getmid->fetch(PDO::FETCH_ASSOC);
                    $mid=$row['machine_id'];
                    if($row['COUNT(*)']!=0)
                    {
                        $stmt3= $pdo->prepare("UPDATE position set final_date= :fdate WHERE machine_id = :mid AND final_date='1970-01-01'");
                        $stmt3->execute(array(':mid' => $mid,':fdate'=>date('y-m-d') ));
                        $insdata=$pdo->prepare("INSERT INTO position (machine_id,lab_id,initial_date,final_date) VALUES(:mid,:labid,:idate,:fdate)");
                        $insdata->execute(array(':mid'=>$mid,':labid' =>$labid ,':idate' => date('y-m-d'),':fdate' =>'1970-01-01'));

                        $insdata2=$pdo->prepare("INSERT INTO system_transfer_report_history (system_transfer_report_id, machine_id) VALUES(:strid, :mid)");
                        $insdata2->execute(array(':strid'=>$strid,':mid' =>$mid));
                        $_SESSION['success'] = "Machine ".$_POST['machine'.$i]." Sent Successfully";
                    }
                    else
                    {
                        $_SESSION['error']="Unable to transfer machine ".$_POST['machine'.$i].". Machine is either inactive or does not exsists";
                    }
                }
                        //header("Location: printservice_reportv2.php?strid=$strid");

                        echo("<script>
         window.open('printservice_reportv2.php?strid=$strid', '_blank'); 
</script>");
        echo("<script>window.open('homev2.php','_self')</script>");

                    //header("Location:homev2.php");
                    //return;
            
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
            
   <center><h1>System Transfer Report</h1></center>
   
   
    <div id="error" style="color: red; margin-left: 90px; margin-bottom: 20px;">
        </div>
    <?php
        if ( isset($_SESSION['error']) )
        {
            echo('<p style="color: red;">'.$_SESSION['error']."</p>\n");
            unset($_SESSION['error']);
        }
    ?>

    <form method="POST" action= "<?= 'servicerptv2.php?id='.$_GET['id'] ?>"  class="register-form">

    <div class="form-row">
    <div class="form-group">
    <div class="form-input">
    <label>Department </label>
    <input type="text" value="<?= $dept ?>" disabled class="form-control"> </div>
    <input type="text" name="department" value="<?= $dept ?>" hidden>

    <div class="form-input">
    <label>Purpose</label>
    <input type="text" value ="<?= $pur ?>" disabled class="form-control"> </div>
    <input type="text" name="purpose" value ="<?= $pur ?>" hidden>
    
    <div class="form-input">
    <label>Lab no.</label>
    <select name="labid" class="form-control" required>
        <?php
            $read=$pdo->query('select name,lab_id from lab order by name');
            while($row = $read->fetch(PDO::FETCH_ASSOC))
            {
                $labname=$row['name'];
                $labid=$row['lab_id'];
                echo '<option name = $labid>';
                echo    $labname;
                echo '</option>';
            }
        ?>
    </select>   
    </div>
    <div class="form-input">
        <label>Choose number of PC</label><input type="Number" class="form-control" name="totalqty" id="totalqty" value = "<?php echo $qty; ?>" min=1 onchange="addtags();" required>
            
           <!--a class="link-black" href="#" onclick="addtags()">Add Machines</a-->
        </div>
        
        <div id="add-machine" class="form-input"></div>
        <script type="text/javascript">
                    var total=document.getElementById("totalqty").value;
        var addimg=document.getElementById("add-machine");
        while (addimg.hasChildNodes()) 
        {
            addimg.removeChild(addimg.lastChild);
        }   
        for (i=1;i<=total;i++)
        {
            addimg.appendChild(document.createTextNode("Machine " + i+" Address"));
            var ipt = document.createElement("input");
            ipt.type = "text";
            ipt.name = "machine"+ i;
            idadd=document.createAttribute('id');
            idadd.value="machine"+ i;
            ipt.setAttributeNode(idadd);
            classadd=document.createAttribute('class');
            classadd.value="form-control";
            ipt.setAttributeNode(classadd);
            onchangeadd=document.createAttribute("onchange");
            onchangeadd.value= "Number(this.id);"//Number('machine'+i);";
            ipt.setAttributeNode(onchangeadd);
            var att=document.createAttribute("required");
            ipt.setAttributeNode(att);
            //              addimg.appendChild(ipt); 
            //                addimg.appendChild(document.createElement("br"));
            document.getElementById("add-machine").appendChild(ipt);
            document.getElementById("add-machine").innerHTML+='<br><br>';
        }
        </script>

<div class="form-submit">
        
        <input type="submit" value="Submit" name="add" id="Submit" class="Submit">
        <input type="reset" value="Reset" class="submit" id="reset" name="reset" />
            </div></div>
    </div>
    </form>


<hr>
    
        <form method="POST" class="form-inline register-form">
            <label id="processor">Processor &nbsp</label>
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
                </select>
            <label id="ram">&nbsp RAM &nbsp</label>
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
                </select>
            <label id="memory">&nbsp Memory &nbsp</label>
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
                    <!--ipt.onchange="Number(machine"+i+")";-->
                </select>
            <label id="os">&nbsp OS &nbsp</label>
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
                </select>&nbsp &nbsp &nbsp
                <div class="form-submit">
            <input class="btn btn-my" class="Submit" id="Submit" type="submit" name="submit">
                </div>
        </form>
    
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
        if(isset($_POST['processor'])||isset($_POST['ram'])||isset($_POST['memory'])||isset($_POST['os']))
        {
            //Checking if machine exsists
            $stmtcnt = $pdo->query("SELECT COUNT(*) FROM machine ");
            $row = $stmtcnt->fetch(PDO::FETCH_ASSOC);
            if($row['COUNT(*)']!=='0')
            {
                echo ("<table class=\"table table-striped\">
                    <tr> <th>S.no.</th><th>MAC ADDRESS</th><th>Processor</th><th>RAM</th><th>Storage</th><th>OS</th><th>DOP</th><th>Price</th><th>Location</th></tr>");
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
                    if($row['state']=='INACTIVE')
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
                    $i++;
                }
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
        function addtags()
           {
          var total=document.getElementById("totalqty").value;
        var addimg=document.getElementById("add-machine");
        while (addimg.hasChildNodes()) 
        {
            addimg.removeChild(addimg.lastChild);
        }   
        for (i=1;i<=total;i++)
        {
            addimg.appendChild(document.createTextNode("Machine " + i+" Address"));
            var ipt = document.createElement("input");
            ipt.type = "text";
            ipt.name = "machine"+ i;
            idadd=document.createAttribute('id');
            idadd.value="machine"+ i;
            ipt.setAttributeNode(idadd);
            classadd=document.createAttribute('class');
            classadd.value="form-control";
            ipt.setAttributeNode(classadd);
            onchangeadd=document.createAttribute("onchange");
            onchangeadd.value= "Number(this.id);"//Number('machine'+i);";
            ipt.setAttributeNode(onchangeadd);
            var att=document.createAttribute("required");
            ipt.setAttributeNode(att);
            //              addimg.appendChild(ipt); 
            //                addimg.appendChild(document.createElement("br"));
            document.getElementById("add-machine").appendChild(ipt);
            document.getElementById("add-machine").innerHTML+='<br><br>';
        }
    }
       
    </script>
</body>

</html>