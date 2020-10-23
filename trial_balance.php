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
        @media print  { 
            header,#report,#btn { 
               display:none; 
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
        <h3 class="mb-4">Trial Balance</h3>
        <form action="trial_balance.php" method="get">
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
    $q="SELECT sum(amt_paid) as debit,sum(amt_received) as credit,particular,transaction_account,transaction_category,cast(dot as date) as d from transactions where is_deleted=0 and cast(dot as date)>='$start_date' and cast(dot as date)<='$end_date' group by transaction_account order by cast(dot as date)";
    $r=mysqli_query($db,$q);
    $q1="SELECT t.cid,t.delivery_time,cast(t.dod as date) as d,COALESCE(sum(t.CowMilk),0) as cow_milk, COALESCE(sum(t.Sahiwal),0) as sahiwal_milk,COALESCE(sum(t.buffalo),0) as buffalo_milk from (SELECT cd.id,cd.cid,cs.delivery_time,cd.dod,case when cs.milktype=1 then cd.delivered_qty end as CowMilk , case when cs.milktype=2 then cd.delivered_qty end as Sahiwal , case when cs.milktype=3 then cd.delivered_qty end as buffalo FROM customer_delivery_log cd INNER JOIN customer_subscription cs on cs.id=cd.csid where cs.is_active=1  and  cs.is_deleted=0 and cast(cd.dod as date)>='$start_date' and cast(cd.dod as date)<='$end_date') as t group by t.cid order by t.dod";
    $r1=mysqli_query($db,$q1);
    
    $prcow1=0;
    $sacow1=0;
    $bflo1=0;
    while($res1=mysqli_fetch_assoc($r1)){
        $cqty=$res1['cow_milk'];
        $sqty=$res1['sahiwal_milk'];
            $bqty=$res1['buffalo_milk'];
            $cid=$res1['cid'];
            $amt1=getDimensionValue($db,'customer',$res1['cid'],'price_cow_milk');
            $amt2=getDimensionValue($db,'customer',$res1['cid'],'price_sahiwal_milk');
            $amt3=getDimensionValue($db,'customer',$res1['cid'],'price_buffalo_milk');
            $prcow=$cqty*$amt1;
            $sacow=$sqty*$amt2;
            $bflo=$bqty*$amt3;
            $prcow1+=$prcow;
            $sacow1+=$sacow;
            $bflo1+=$bflo;
    }
        $debators=$prcow1+$sacow1+$bflo1;

    $sdt=date("d-m-Y", strtotime($start_date));
    $edt=date("d-m-Y", strtotime($end_date));
    $date='';
    $sn=0;
    $total1=0;
    $total2=0;
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
        <table class="table table-bordered table-md">
        <tr>
        <th colspan="1">Particulars</th>
        <th colspan="2" class="text-center">Closing Balance</th>
        </tr>
        <tr>
        <th>Purchases Accounts -- Yet to be calculated</th>
        </tr>
        <tr>
        <th>Sundry Debtors</th>
        <th>Debit</th>
        <th>Credit</th>
        </tr>
        <tr>
        <th></th>
        <td>$debators</td>
        </tr>
        <tr>
        <th>Indirect Expenses</th>
        <th>Debit</th>
        <th>Credit</th>
        </tr>
_END;
while($res=mysqli_fetch_assoc($r)){
    $particular=$res['particular'];
    $account=getDimensionValue($db,'transactions_accounts',$res['transaction_account'],'account');
    $debit=$res['debit'];
    $credit=$res['credit'];
    $total1+=$debit;
    $total2+=$credit;
    $balance=$credit-$debit;
    echo <<<_END
        <tr>
        <td>$account</td>
        <td>$debit</td>
        <td>$credit</td>
        </tr>
_END;
}
    }
    echo <<<_END
    <tr>
    <th>Grand Total</th>
    <th>$total1</th>
    <th>$total2</th>
    </tr>
_END;
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
else {
    echo 'No expenses found';
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