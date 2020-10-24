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
        <h2  class="mb-4">Customer Subscription Report</h2>
        <h5 style="margin-left:70px">Morning</h5>
        <div class="row">
        <div class="col-lg-5 px-0">
        <table class="table table-bordered">
        <tbody>
        <tr>
        <th width="2%">Sno.</th>
        <th>Name</th>
        <th>Cow</th>
        <th>Sahiwal</th>
        <th>Buffalo</th>
        </tr>
_END;
    $q="SELECT t.cid,t.delivery_time,COALESCE(sum(t.CowMilk),0) as cow_milk, COALESCE(sum(t.Sahiwal),0) as sahiwal_milk, COALESCE(sum(t.buffalo),0) as buffalo_milk from (SELECT id,cid,delivery_time,case when milktype=1 then qty end as CowMilk ,case when milktype=2 then qty end as Sahiwal ,case when milktype=3 then qty end as buffalo FROM `customer_subscription` where is_active=1 and is_deleted=0 and delivery_time=1) as t group by t.cid,t.delivery_time";
    $r=mysqli_query($db,$q);
    $sn=0;
    $t1=0;
    $t2=0;
    $t3=0;
    while($res=mysqli_fetch_assoc($r)){
        $sn=$sn+1;
        $id=$res['cid'];
        $name=getDimensionValue($db,'customer',$res['cid'],'fname').' '.getDimensionValue($db,'customer',$res['cid'],'lname');
        $qty=$res['cow_milk'];
        $qty1=$res['sahiwal_milk'];
        $qty2=$res['buffalo_milk'];
        $deliverytime=$res['delivery_time'];
        $t1+=$qty;
        $t2+=$qty1;
        $t3+=$qty2;
        echo <<<_END
        <tr>
        <td width="2%">$sn</td>
        <td width="40%">$name</td>
_END;
        echo <<<_END
        <td width="9%">$qty</td>
        <td width="9%">$qty1</td>
        <td width="9%">$qty2</td>
        </tr>
_END;
    }
    echo <<<_END
        <tr>
        <th>Total</th>
        <td></td>
        <th>$t1</th>
        <th>$t2</th>
        <th>$t3</th>
        </tr>
_END;
    echo <<<_END
    </tbody>
</table>
</div>
<div class="col-lg-6">
<h5>Evening</h5>
<table class="table table-bordered">
<tbody>
<tr>
<th width="2%">Sno.</th>
<th>Name</th>
<th>Cow</th>
<th>Sahiwal</th>
<th>Buffalo</th>
</tr>
_END;
$q="SELECT t.cid,t.delivery_time,COALESCE(sum(t.CowMilk),0) as cow_milk, COALESCE(sum(t.Sahiwal),0) as sahiwal_milk, COALESCE(sum(t.buffalo),0) as buffalo_milk from (SELECT id,cid,delivery_time,case when milktype=1 then qty end as CowMilk ,case when milktype=2 then qty end as Sahiwal ,case when milktype=3 then qty end as buffalo FROM `customer_subscription` where is_active=1 and is_deleted=0 and delivery_time=2) as t group by t.cid,t.delivery_time";
$r=mysqli_query($db,$q);
$sn=0;
$t4=0;
$t5=0;
$t6=0;
while($res=mysqli_fetch_assoc($r)){
$sn=$sn+1;
$id=$res['cid'];
$name=getDimensionValue($db,'customer',$res['cid'],'fname').' '.getDimensionValue($db,'customer',$res['cid'],'lname');
$qty=$res['cow_milk'];
$qty1=$res['sahiwal_milk'];
$qty2=$res['buffalo_milk'];
$deliverytime=$res['delivery_time'];
$t4+=$qty;
$t5+=$qty1;
$t6+=$qty2;
echo <<<_END
<tr>
<td width="2%">$sn</td>
<td width="40%">$name</td>
_END;
echo <<<_END
<td width="9%">$qty</td>
<td width="9%">$qty1</td>
<td width="9%">$qty2</td>
</tr>
_END;
}
echo <<<_END
<tr>
<th>Total</th>
<td></td>
<th>$t4</th>
<th>$t5</th>
<th>$t6</th>
</tr>
_END;
echo <<<_END
</tbody>
</table>
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