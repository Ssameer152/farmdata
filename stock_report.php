<?php
session_start();
function getDimensionValue($db,$table,$gid,$name){
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db,$q);
    
    $res = mysqli_fetch_assoc($r);
    
    $value = $res[$name];
    
    return $value;
}
if(isset($_SESSION['user'])){
    include_once 'db.php';
    echo <<<_END
    <html>
        <head>
            <title>FarmDB</title>
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        </head>
        
        <body>    
_END;    
include_once 'nav.php';
    
    $q2="SELECT sum(qty) as q,resourceid from log_resource where is_deleted=0  group by resourceid";
    $r2=mysqli_query($db,$q2);
    echo <<<_END
        <div class="container">
        <div class="row">
        <div class="col-lg-12">
        <table class="table table-responsive">
        <tr>
            <th>Resource</th>
            <th>Purchased</th>
            <th>consumed</th>
            <th>Left</th>
        </tr>
_END;
    while($res2=mysqli_fetch_assoc($r2)){
        $name=getDimensionValue($db,'resources',$res2['resourceid'],'resourcename');
        $qty=$res2['q'];
        $unit=getDimensionValue($db,'resources',$res2['resourceid'],'unit');

        echo <<<_END
        <tr>
        <td>$name</td>
_END;
        $q="SELECT * from purchase_items where is_deleted=0 and resourceid=23 group by resourceid";
        $r=mysqli_query($db,$q);
        while($res=mysqli_fetch_assoc($r)){
            $pqty=$res['qty'];
            $left=$pqty-$qty;
        echo <<<_END
        <td>$pqty $unit</td>
        <td>$qty $unit</td>
        <td>$left $unit</td>
 _END;
        }
        echo <<<_END
        </tr>
_END;
    }
    echo <<<_END
        </table>
        </div>
        </div>
        </div>
_END;
        include_once 'foot.php';
    echo <<<_END
        </body>
        </html>
_END;
}
else
{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
?>