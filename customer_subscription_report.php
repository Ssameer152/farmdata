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
        <th>Delivery Time</th>
        <th>Cow</th>
        <th>Sahiwal</th>
        <th>Buffalo</th>
        </tr>
_END;
    $q="SELECT t.cid,t.delivery_time,COALESCE(sum(t.CowMilk),0) as cow_milk, COALESCE(sum(t.Sahiwal),0) as sahiwal_milk, COALESCE(sum(t.buffalo),0) as buffalo_milk from (SELECT id,cid,delivery_time,case when milktype=1 then qty end as CowMilk ,case when milktype=2 then qty end as Sahiwal ,case when milktype=3 then qty end as buffalo FROM `customer_subscription`) as t group by t.cid,t.delivery_time";
    $r=mysqli_query($db,$q);
    $sn=0;
    while($res=mysqli_fetch_assoc($r)){
        $sn=$sn+1;
        $id=$res['cid'];
        $name=getDimensionValue($db,'customer',$res['cid'],'fname').' '.getDimensionValue($db,'customer',$res['cid'],'lname');
        $qty=$res['cow_milk'];
        $qty1=$res['sahiwal_milk'];
        $qty2=$res['buffalo_milk'];
        $deliverytime=$res['delivery_time'];

        echo <<<_END
        <tr>
        <td width="5%">$sn</td>
        <td width="20%">$name</td>
_END;
        if($deliverytime==1)
        echo <<<_END
        <td width="10%">Morning</td>
_END;
        if($deliverytime==2)
        echo <<<_END
        <td width="10%">Evening</td>
_END;
        echo <<<_END
        <td width="9%">$qty</td>
        <td width="9%">$qty1</td>
        <td width="9%">$qty2</td>
        </tr>
_END;
    }
    $q1="SELECT t.cid,t.delivery_time,COALESCE(sum(t.CowMilk),0) as cow_milk, COALESCE(sum(t.Sahiwal),0) as sahiwal_milk, COALESCE(sum(t.buffalo),0) as buffalo_milk from (SELECT id,cid,delivery_time,case when milktype=1 then sum(qty) end as CowMilk ,case when milktype=2 then sum(qty) end as Sahiwal ,case when milktype=3 then sum(qty) end as buffalo FROM `customer_subscription` where is_deleted=0 group by milktype) as t";
    $r1=mysqli_query($db,$q1);
    $res1=mysqli_fetch_assoc($r1);
    $total1=$res1['cow_milk'];
    $total2=$res1['sahiwal_milk'];
    $total3=$res1['buffalo_milk'];
    echo <<<_END
        <tr>
        <th>Total</th>
        <td></td>
        <td></td>
        <td>$total1</td>
        <td>$total2</td>
        <td>$total3</td>
        </tr>
_END;
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