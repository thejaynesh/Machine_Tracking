<?php
    session_start();
    require_once "pdo.php";
    
    if(isset($_POST['cancel']))
    {
        header("Location: index.php");
        return;
    }
    if( !isset($_SESSION['id'])&&$_SESSION['role']!=0 )
    {
        die('ACCESS DENIED');
    }


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
                echo ("<table class=\"table table-striped col-xs-12\">
                    <tr> <th>S.no.</th><th>Hardware ID</th><th>Name</th><th>description</th><th>Company</th><th>GRN</th><th>Supplier</th><th>State</th></tr>");
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
                    echo(htmlentities($row['description']));
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
                    echo ("<td>");
                    if($_POST['state']==0)
                        echo "Unpositoned";
                    else if($_POST['state']==1)
                        echo "Positioned";
                    else if($_POST['state']==2)
                        echo "Issued";
                    echo ("</td>")  ;     
                    $i++;
                }
                echo('</table>');
            }
        }
    ?>