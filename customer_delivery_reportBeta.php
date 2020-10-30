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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <style>
        @media print { 
            header,#report,#btn{ 
               display:none; 
            } 
            #t{
                border: solid white !important;
            }
            #fnt{
                font-size:12px;
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
        <form action="customer_delivery_reportBeta.php" method="get">
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

    $q="SELECT t.cid,t.delivery_time,cast(t.dod as date) as d,COALESCE(sum(t.CowMilk),0) as cow_milk, COALESCE(sum(t.Sahiwal),0) as sahiwal_milk, COALESCE(sum(t.buffalo),0) as buffalo_milk from (SELECT cd.id,cd.cid,cs.delivery_time,cd.dod,case when cs.milktype=1 then cd.delivered_qty end as CowMilk , case when cs.milktype=2 then cd.delivered_qty end as Sahiwal , case when cs.milktype=3 then cd.delivered_qty end as buffalo FROM customer_delivery_log cd INNER JOIN customer_subscription cs on cs.id=cd.csid where cs.is_active=1 and cs.delivery_time=1 and  cs.is_deleted=0 and cast(cd.dod as date)>='$start_date' and cast(cd.dod as date)<='$end_date') as t group by t.cid order by t.dod";
    $r=mysqli_query($db,$q);
    $sdt=date("d-m-Y", strtotime($start_date));
        $edt=date("d-m-Y", strtotime($end_date));
$date='';
$sn=0;
echo <<<_END
<div class="col-lg-12">
<div class="row">
<h4 class="mb-4">From $sdt to $edt</h4>
<button class="btn btn-primary" id="btn" style="position: absolute;right:10;" onclick="window.print()">Print Report</button>
</div>
_END;
    if(mysqli_num_rows($r)>0){
        echo <<<_END
        <div class="row">
        <table id="t" class="table table-bordered table-sm">
        <tr>
        <th class="text-center">
        <h5>Morning</h5>
        <table class="table table-bordered">
        <tr>
        <th>S.no</th>
        <th>Customer</th>
        <th>Cow</th>
        <th>Sahiwal</th>
        <th>Buffalo</th>
        </tr>
_END;
    $cqty=0;
    $sqty=0;
    $bqty=0;
    while($res=mysqli_fetch_assoc($r)){
        $sn=$sn+1;
        $cid=$res['cid'];
        $qty=$res['cow_milk'];
        $qty1=$res['sahiwal_milk'];
        $qty2=$res['buffalo_milk'];
        $cqty+=$qty;
        $sqty+=$qty1;
        $bqty+=$qty2;
        $cust=getDimensionValue($db,'customer',$res['cid'],'fname').' '.getDimensionValue($db,'customer',$res['cid'],'lname');
            echo <<<_END
            <tr>
            <td>$sn</td>
            <td>$cust</td>
            <td>$qty</td>
            <td>$qty1</td>
            <td>$qty2</td>
            </tr>
_END;
           }
           echo <<<_END
           <tr>
           <th colspan="2">Total</th>
           <th>$cqty</th>
           <th>$sqty</th>
           <th>$bqty</th>
           </tr>
           </table>
           </th>
_END;
           echo <<<_END
           <th id="t">
           <!-- 2nd table -->
           <table class="table table-bordered">
           <tr>
           <h5 class="text-center">Evening</h5>
           </tr>
           <tr>
        <th>S.no</th>
        <th>Customer</th>
        <th>Cow</th>
        <th>Sahiwal</th>
        <th>Buffalo</th>
           </tr>
_END;
        $ecqty=0;
        $esqty=0;
        $ebqty=0;
        $sn1=0;
        $q2="SELECT t.cid,t.delivery_time,cast(t.dod as date) as d,COALESCE(sum(t.CowMilk),0) as cow_milk, COALESCE(sum(t.Sahiwal),0) as sahiwal_milk, COALESCE(sum(t.buffalo),0) as buffalo_milk from (SELECT cd.id,cd.cid,cs.delivery_time,cd.dod,case when cs.milktype=1 then cd.delivered_qty end as CowMilk , case when cs.milktype=2 then cd.delivered_qty end as Sahiwal , case when cs.milktype=3 then cd.delivered_qty end as buffalo FROM customer_delivery_log cd INNER JOIN customer_subscription cs on cs.id=cd.csid where cs.is_active=1 and cs.delivery_time=2 and  cs.is_deleted=0 and cast(cd.dod as date)>='$start_date' and cast(cd.dod as date)<='$end_date') as t group by t.cid order by t.dod";
        $r2=mysqli_query($db,$q2);
    while($res2=mysqli_fetch_assoc($r2)){
        $sn1=$sn1+1;
        $cid=$res2['cid'];
        $qty=$res2['cow_milk'];
        $qty1=$res2['sahiwal_milk'];
        $qty2=$res2['buffalo_milk'];
        $ecqty+=$qty;
        $esqty+=$qty1;
        $ebqty+=$qty2;
        $cust=getDimensionValue($db,'customer',$res2['cid'],'fname').' '.getDimensionValue($db,'customer',$res2['cid'],'lname');
        echo <<<_END
        <tr>
        <td>$sn1</td>
        <td>$cust</td>
        <td>$qty</td>
        <td>$qty1</td>
        <td>$qty2</td>
        </tr>
_END;
    }
    echo <<<_END
    <tr>
           <th colspan="2">Total</th>
           <th>$ecqty</th>
           <th>$esqty</th>
           <th>$ebqty</th>
           </tr>
        
_END;
    }
    else {
        echo 'No deliveries found';
    }
    echo <<<_END
    </table>
    </th>
    </tr>
</table>
</div>
</div>
</div>
</div>
_END;

include_once 'foot.php';

echo <<<_END
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
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