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
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>        
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"/>
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <style>
        @media print { 
            header,#report,#btn { 
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
 <h3 class="mb-4">Customers Total</h3>
 <form action="customer_total.php" method="get">
                 <div class="row">
                     <div class="col-lg">
                         <input type="date" class="form-control" name="start_date">
                     </div>
                     <div class="col-lg">
                         <input type="date" class="form-control" name="end_date">
                     </div>
                 </div>
                 <br>
                 <button type="submit" class="btn btn-primary">Show Total</button>
             </form>
             </div>
_END;
if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date']!='' && $_GET['end_date']!=''){
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);
    $q="SELECT t.cid,t.delivery_time,cast(t.dod as date) as d,COALESCE(sum(t.CowMilk),0) as cow_milk, COALESCE(sum(t.Sahiwal),0) as sahiwal_milk,COALESCE(sum(t.buffalo),0) as buffalo_milk from (SELECT cd.id,cd.cid,cs.delivery_time,cd.dod,case when cs.milktype=1 then cd.delivered_qty end as CowMilk , case when cs.milktype=2 then cd.delivered_qty end as Sahiwal , case when cs.milktype=3 then cd.delivered_qty end as buffalo FROM customer_delivery_log cd INNER JOIN customer_subscription cs on cs.id=cd.csid where cs.is_active=1  and  cs.is_deleted=0 and cast(cd.dod as date)>='$start_date' and cast(cd.dod as date)<='$end_date') as t group by t.cid order by t.dod";
    $r=mysqli_query($db,$q);
    $sn=0;
    if(mysqli_num_rows($r)>0){

    $date='';
    echo <<<_END
        <div class="col-lg-12">
        <button class="btn btn-primary" id="btn" style="position: absolute;right:10;top:-50;" onclick="window.print()">Print Statement</button>
        <div class="table table-responsive">
        <table id="table" class="table table-bordered">
            <thead>
            <tr>
            <th>S.no</th>
            <th>Customer</th>
            <th>Cow</th>
            <th>Sahiwal</th>
            <th>Buffalo</th>
            <th>Price Cow</th>
            <th>Price Sahiwal</th>
            <th>Price Buffalo</th>
            </tr>
            </thead>
            <tbody>
_END;
        $prcow1=0;
        $sacow1=0;
        $bflo1=0;
        $total1=0;
        $total2=0;
        $total3=0;
        while($res=mysqli_fetch_assoc($r)){
            $sn=$sn+1;
            $cqty=$res['cow_milk'];
            $cid=$res['cid'];
            $cname=getDimensionValue($db,'customer',$res['cid'],'fname').' '.getDimensionValue($db,'customer',$res['cid'],'lname');
            $amt1=getDimensionValue($db,'customer',$res['cid'],'price_cow_milk');
            $amt2=getDimensionValue($db,'customer',$res['cid'],'price_sahiwal_milk');
            $amt3=getDimensionValue($db,'customer',$res['cid'],'price_buffalo_milk');
            $d=$res['d'];
            $d=date('d/m/Y',strtotime($d));
            $sqty=$res['sahiwal_milk'];
            $bqty=$res['buffalo_milk'];
            $prcow=$cqty*$amt1;
            $sacow=$sqty*$amt2;
            $bflo=$bqty*$amt3;
            $prcow1+=$prcow;
            $sacow1+=$sacow;
            $bflo1+=$bflo;
            $total1+=$cqty;
            $total2+=$sqty;
            $total3+=$bqty;
            echo <<<_END
            <tr>
            <th>$sn</th>
            <th>$cname</th>
            <td>$cqty</td>
            <td>$sqty</td>
            <td>$bqty</td>
            <td>$prcow</td>
            <td>$sacow</td>
            <td>$bflo</td>
            </tr>
_END;
        }
        echo <<<_END
        <tr>
        <th>$sn</th>
        <th>Grand Total</th>
        <th>$total1</th>
        <th>$total2</th>
        <th>$total3</th>
        <th>$prcow1</th>
        <th>$sacow1</th>
        <th>$bflo1</th>
        </tr>
_END;
                  
    }
}
else{
    echo 'No record found';
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
      $('#myselect').select2();
    });
</script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
$('#table').DataTable();
});
</script> 
    </body>
</html>
_END;
}
else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
?>