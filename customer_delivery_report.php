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
        <style>
        @media print { 
            header,#report { 
               display:none; 
            } 
         } 
         </style>
    </head>
    
    <body>    
_END;

include_once 'nav.php';
    echo <<<_END
        <div class="container">
        <div class="row">
        <div class="col-lg-12" id="report">
        <h3 class="mb-4">Customer Delivery Report</h3>
        <form action="customer_delivery_report.php" method="get">
                        <div class="row">
                            <div class="col-lg">
                                <input type="date" class="form-control" name="start_date">
                            </div>
                            <div class="col-lg">
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Show Report</button>
                    </form>
                    </div>
_END;
if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date']!='' && $_GET['end_date']!='')
{
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);

    $q1="SELECT id,cast(dod as date) as d,cid,csid from customer_delivery_log where cast(dod as date)>='$start_date' AND cast(dod as date)<='$end_date' and is_deleted=0 group by cast(dod as date)";
    $r1=mysqli_query($db,$q1);
    if(mysqli_num_rows($r1)>0){
    while($res1=mysqli_fetch_assoc($r1)){
        $did=$res1['cid'];
        $sdt=date("d-m-Y", strtotime($start_date));
        $edt=date("d-m-Y", strtotime($end_date));
        echo <<<_END
        <div class="col-lg-12">
        <div class="row">
        <h4 class="mb-4">From $sdt to $edt</h4>
        <button class="btn btn-primary" style="position: absolute;right:10;" onclick="window.print()">Print Report</button>
        </div>
        </div>
_END;
       
    $q="SELECT t.cid,t.delivery_time,COALESCE(sum(t.CowMilk),0) as cow_milk, COALESCE(sum(t.Sahiwal),0) as sahiwal_milk, COALESCE(sum(t.buffalo),0) as buffalo_milk from (SELECT cs.id,cs.cid,cs.delivery_time,case when cs.milktype=1 then cd.delivered_qty end as CowMilk ,case when cs.milktype=2 then cd.delivered_qty end as Sahiwal ,case when cs.milktype=3 then cd.delivered_qty end as buffalo FROM customer_delivery_log cd INNER JOIN customer_subscription cs on cd.csid=cs.id where cd.is_deleted=0) as t group by t.cid,t.delivery_time";
    $r=mysqli_query($db,$q);
    $sn=0;
    echo <<<_END
    <table class="table table-bordered" width="100%">
    <tbody>
    <tr>
    <th width="5%">Sno.</th>
    <th width="24%">Name</th>
    <th width="16%">Delivery Time</th>
    <th width="16%">Cow</th>
    <th width="16%">Sahiwal</th>
    <th width="16%">Buffalo</th>
    </tr>
_END;
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
        <td width="8%">$sn</td>
        <td width="24%">$name</td>
_END;
        if($deliverytime==1)
        echo <<<_END
        <td width="16%">Morning</td>
_END;
        if($deliverytime==2)
        echo <<<_END
        <td width="16%">Evening</td>
_END;
        echo <<<_END
        <td width="16%">$qty</td>
        <td width="16%">$qty1</td>
        <td width="16%">$qty2</td>
        </tr>
_END;
    }
    }
    $q2="SELECT t.cid,t.delivery_time,COALESCE(sum(t.CowMilk),0) as cow_milk, COALESCE(sum(t.Sahiwal),0) as sahiwal_milk, COALESCE(sum(t.buffalo),0) as buffalo_milk from (SELECT cs.id,cs.cid,cs.delivery_time,case when cs.milktype=1 then sum(cd.delivered_qty) end as CowMilk ,case when cs.milktype=2 then sum(cd.delivered_qty) end as Sahiwal ,case when cs.milktype=3 then sum(cd.delivered_qty) end as buffalo FROM customer_delivery_log cd INNER JOIN customer_subscription cs on cd.csid=cs.id where cd.is_deleted=0 GROUP by cs.milktype) as t group by t.cid,t.delivery_time";
    $r2=mysqli_query($db,$q2);
    $res2=mysqli_fetch_assoc($r2);
    $total1=$res2['cow_milk'];
    $total2=$res2['sahiwal_milk'];
    $total3=$res2['buffalo_milk'];
    echo <<<_END
    <tr>
    <th>Total</th>
    <td></td>
    <td></td>
    <th>$total1</th>
    <th>$total2</th>
    <th>$total3</th>
    </tr>
_END;
}
    else {
        echo 'No deliveries found';
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
}
else
{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
?>