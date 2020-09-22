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
    echo <<<_END
        <div class="container">
        <div class="row">
        <div class="col-lg-12">
        <table class="table table-bordered">
        <tbody>
        <tr>
        <th class="w-10">Sno.</th>
        <th>Name</th>
        <th>Qty</th>
        <th>Del</th>
        </tr>
_END;
    $q="SELECT * from customer_subscription where is_deleted=0 and is_active=1 order by cid";
    $r=mysqli_query($db,$q);
    while($res=mysqli_fetch_assoc($r)){
        $id=$res['cid'];
        $name=getDimensionValue($db,'customer',$res['cid'],'fname').' '.getDimensionValue($db,'customer',$res['cid'],'lname');
        $qty=$res['qty'];
        echo <<<_END
        <tr>
        <td width="5%">$id</td>
        <td width="20%">$name</td>
        <td width="8%">$qty</td>
        </tr>
_END;
    }

    echo <<<_END
    </tbody>
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