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
        <title>Delivery Report</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <link rel="stylesheet" href="css/bootstrap.min.css"/>
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css"/>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"/>            
            <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
    </head>
    
    <body>    
_END;

include_once 'nav.php';
    echo <<<_END
        <div class="container-fluid">
        <div class="row">
        <div class="col-lg-12" id="report">
        <h3 class="mb-4">Customer Delivery Report</h3>
        <form action="delivery_report_price.php" method="get">
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
                    </div>
_END;
if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date']!='' && $_GET['end_date']!='')
{
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);

    $q="SELECT t.cid,t.delivery_time,cast(t.dod as date) as d,COALESCE(sum(t.CowMilk),NULL) as cow_milk, COALESCE(sum(t.Sahiwal),NULL) as sahiwal_milk, COALESCE(sum(t.buffalo),NULL) as buffalo_milk from (SELECT cd.id,cd.cid,cs.delivery_time,cd.dod,case when cs.milktype=1 then cd.delivered_qty end as CowMilk , case when cs.milktype=2 then cd.delivered_qty end as Sahiwal ,case when cs.milktype=3 then cd.delivered_qty end as buffalo FROM customer_delivery_log cd INNER JOIN customer_subscription cs on cs.id=cd.csid where cs.is_active=1  and  cs.is_deleted=0 and cast(cd.dod as date)>='$start_date' and cast(cd.dod as date)<='$end_date') as t group by t.cid order by t.dod";
    $r=mysqli_query($db,$q);
    $sdt=date("d-m-Y", strtotime($start_date));
    $edt=date("d-m-Y", strtotime($end_date));
    $date='';
    $sn=0;

echo <<<_END
<h4 class="mb-4">From $sdt to $edt</h4>
<div class="col-lg-12">
<div class="table table-responsive">
        <table id="" class="table table-bordered display"> 
        <thead>
        <tr>
        <th>S.no</th>
        <th>Customer</th>
        <th>Cow</th>
        <th>Price</th>
        <th>Amount</th>
        <th>Sahiwal</th>
        <th>Price</th>
        <th>Amount</th>
        <th>Buffalo</th>
        <th>Price</th>
        <th>Amount</th>
        <th>total</th>
        </tr>
        </thead>
        <tbody>
_END;
    $cqty=0;
    $sqty=0;
    $bqty=0;
    $amt1=0;
    $amt2=0;
    $amt3=0;
    while($res=mysqli_fetch_assoc($r)){
        $sn=$sn+1;
        $cid=$res['cid'];
        $qty=$res['cow_milk'];
        $qty1=$res['sahiwal_milk'];
        $qty2=$res['buffalo_milk'];
        $cust=getDimensionValue($db,'customer',$res['cid'],'fname').' '.getDimensionValue($db,'customer',$res['cid'],'lname');
        $price_cow=getDimensionValue($db,'customer',$res['cid'],'price_cow_milk');
        $price_sahiwal=getDimensionValue($db,'customer',$res['cid'],'price_sahiwal_milk');
        $price_buffalo=getDimensionValue($db,'customer',$res['cid'],'price_buffalo_milk');
        $cqty+=$qty;
        $sqty+=$qty1;
        $bqty+=$qty2;
        $amt_cow=$qty*$price_cow;
        $amt_sahiwal=$qty1*$price_sahiwal;
        $amt_buffalo=$qty2*$price_buffalo;
        $amt1+=$amt_cow;
        $amt2+=$amt_sahiwal;
        $amt3+=$amt_buffalo;
        $T=$amt_cow+$amt_sahiwal+$amt_buffalo;
        $T1=$amt1+$amt2+$amt3;
            echo <<<_END
            <tr>
            <td>$sn</td>
            <td>$cust</td>
            <td>$qty</td>
_END;
            if($price_cow==0.00){
                echo <<<_END
                <td></td>
_END;
            }
            else {
            echo <<<_END
            <td>$price_cow</td>
_END;
            }
            if($amt_cow==0){
                echo <<<_END
                <td></td>
_END;
            }
            else {
            echo <<<_END
            <td>$amt_cow</td>
_END;
            }
            echo <<<_END
            <td>$qty1</td>
_END;
            if($price_sahiwal==0.00){
                echo <<<_END
                <td></td>
_END;
            }
            else{
            echo <<<_END
            <td>$price_sahiwal</td>
_END;
            }
            if($amt_sahiwal==0){
                echo <<<_END
                <td></td>
_END;
            }
            else{
                echo <<<_END
                <td>$amt_sahiwal</td>
_END;
            }
            echo <<<_END
            <td>$qty2</td>
_END;
            if($price_buffalo==0.00){
                echo <<<_END
                <td></td>
_END;
            }
            else{
            echo <<<_END
            <td>$price_buffalo</td>
_END;
            }
            if($amt_buffalo==0){
                echo <<<_END
                <td></td>
_END;
            }
            else {
                echo <<<_END
                <td>$amt_buffalo</td>
_END;
            }
            if($T==0){
                echo <<<_END
                <td></td>
_END;
            }
            else{
            echo <<<_END
            <th>$T</th>
_END;
            }
            echo <<<_END
            </tr>
_END;
           }
           echo <<<_END
           <tr>
           <th>$sn</th>
           <th>Grand Total</th>
           <th>$cqty</th>
           <th></th>
           <th>$amt1</th>
           <th>$sqty</th>
           <th></th>
           <th>$amt2</th>
           <th>$bqty</th>
           <th></th>
           <th>$amt3</th>
           <th>$T1</th>
           </tr>
_END;
    }
    else {
        echo 'No deliveries found';
    }

include_once 'foot.php';

echo <<<_END
</tbody>
</table>
</div>
</div>
</div>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
<script>
$(document).ready(function() {
$('table.display').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
});
});
</script> 
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