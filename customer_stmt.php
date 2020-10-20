<?php
session_start();
function getDimensionValue($db, $table, $gid, $name)
{
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db, $q);
    $res = mysqli_fetch_assoc($r);
    $value = $res[$name];
    return $value;
}
if (isset($_SESSION['user'])) {
    include_once 'db.php';
    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>        
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
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
 <h3 class="mb-4">Customer Statement</h3>
 <form action="customer_stmt.php" method="get">
                 <div class="row">
                     <div class="col-lg">
                         <input type="date" class="form-control" name="start_date">
                     </div>
                     <div class="col-lg">
                         <input type="date" class="form-control" name="end_date">
                     </div>
                 </div>
                 <br>
                 <div class="col-lg-6">
                 <div class="row">
                 <select id="myselect" class="form-control" name="custname">
                 <option value="">--Select customer--</option>
_END;
    $q = "SELECT id,fname from customer where is_deleted=0 order by fname asc";
    $r = mysqli_query($db, $q);
    while ($res = mysqli_fetch_assoc($r)) {
        $name = $res['fname'];
        $id = $res['id'];
        echo <<<_END
            <option value="$id">$name</option>
_END;
    }
    echo <<<_END
                </select>
                 </div>
                 </div>
                 <br>
                 <button type="submit" class="btn btn-primary">Show Statement</button>
             </form>
             </div>
_END;
    if (isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] != '' && $_GET['end_date'] != '' && isset($_GET['custname']) && $_GET['custname'] != '') {
        $start_date = mysqli_real_escape_string($db, $_GET['start_date']);
        $end_date = mysqli_real_escape_string($db, $_GET['end_date']);
        $customer = mysqli_real_escape_string($db, $_GET['custname']);
        // USED FOR TOTAL
        $q2 = "SELECT t.cid,t.delivery_time,cast(t.dod as date) as d,COALESCE(sum(t.CowMilk),0) as cow_milk, COALESCE(sum(t.Sahiwal),0) as sahiwal_milk, COALESCE(sum(t.buffalo),0) as buffalo_milk from (SELECT cd.id,cd.cid,cs.delivery_time,cd.dod,case when cs.milktype=1 then cd.delivered_qty end as CowMilk , case when cs.milktype=2 then cd.delivered_qty end as Sahiwal ,case when cs.milktype=3 then cd.delivered_qty end as buffalo FROM customer_delivery_log cd INNER JOIN customer_subscription cs on cs.id=cd.csid where cs.is_active=1  and  cs.is_deleted=0 and cast(cd.dod as date)>='$start_date' and cast(cd.dod as date)<='$end_date' and cd.cid='$customer' ORDER by cd.delivered_qty) as t ORDER by cast(t.dod as date)";
        $r2 = mysqli_query($db, $q2);
        $res3 = mysqli_fetch_assoc($r2);
        $total1 = $res3['cow_milk'];
        $total2 = $res3['sahiwal_milk'];
        $total3 = $res3['buffalo_milk'];

        $q = "SELECT t.cid,t.delivery_time,COALESCE(cast(t.dod as date)) as d,t.CowMilk,t.Sahiwal,0) as sahiwal_milk,COALESCE(t.sSahiwalMilk,0) as s_sahiwal_milk, COALESCE(t.buffalo,0) as buffalo_milk,COALESCE(t.sBuffaloMilk,0) as s_buffalo_milk from (SELECT cd.id,cd.cid,cs.delivery_time,cd.dod,case when cs.milktype=1 then cd.delivered_qty end as CowMilk ,case when cs.milktype=1 then cd.qty end as sCowMilk, case when cs.milktype=2 then cd.delivered_qty end as Sahiwal , case when cs.milktype=2 then cd.qty end as sSahiwalMilk ,case when cs.milktype=3 then cd.delivered_qty end as buffalo, case when cs.milktype=3 then cd.qty end as sBuffaloMilk FROM customer_delivery_log cd INNER JOIN customer_subscription cs on cs.id=cd.csid where cs.is_active=1  and  cs.is_deleted=0 and cast(cd.dod as date)>='$start_date' and cast(cd.dod as date)<='$end_date' and cd.cid='$customer') as t order by cast(t.dod as date)";
        $r = mysqli_query($db, $q);
        if (mysqli_num_rows($r) > 0) {
            $q1 = "SELECT fname,lname,price_cow_milk,price_sahiwal_milk,price_buffalo_milk from customer where id='$customer' and is_deleted=0";
            $r1 = mysqli_query($db, $q1);
            $re1 = mysqli_fetch_assoc($r1);
            $cname = $re1['fname'] . ' ' . $re1['lname'];
            $amt1 = $re1['price_cow_milk'];
            $amt2 = $re1['price_sahiwal_milk'];
            $amt3 = $re1['price_buffalo_milk'];
            $date = '';
            echo <<<_END
        <div class="col-lg-12">
        <h4>Customer: $cname</h4>
        <div class="table table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
            <th>Date</th>
            <th>Cow</th>
            <th>Sahiwal</th>
            <th>Buffalo</th>
            <th>Price Cow Milk</th>
            <th>Price Sahiwal Milk</th>
            <th>Price Buffalo Milk</th>
            </tr>
            </thead>
            <tbody>
_END;
            $prcow1 = 0;
            $sacow1 = 0;
            $bflo1 = 0;
            while ($res = mysqli_fetch_assoc($r)) {
                $cqty = $res['cow_milk'];
                $d = $res['d'];
                $d = date('d/m/Y', strtotime($d));
                $sqty = $res['sahiwal_milk'];
                $bqty = $res['buffalo_milk'];
                $prcow = $cqty * $amt1;
                $sacow = $sqty * $amt2;
                $bflo = $bqty * $amt3;
                $prcow1 += $prcow;
                $sacow1 += $sacow;
                $bflo1 += $bflo;
                echo <<<_END
            <tr>
            <td>$d</td>
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
    } else {
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
    </body>
</html>
_END;
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
