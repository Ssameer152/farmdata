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
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
    </head>
    
    <body>    
    
_END;
 include_once 'nav.php';
 echo <<<_END
 <div class="container">
 <div class="row">
 <div class="col-lg-12">
 <h3 class="mb-4">Customer Summary</h3>
 <form action="customer_summary.php" method="get">
                 <div class="col-lg-10">
                 <div class="row">
                 <select id="myselect" class="form-control" name="custname">
                 <option value="">--Select customer--</option>
_END;
            $q="SELECT id,fname,lname from customer where is_deleted=0 order by fname asc";
            $r=mysqli_query($db,$q);
            while($res=mysqli_fetch_assoc($r)){
                $name=$res['fname'].' '.$res['lname'];
                $id=$res['id'];
                echo <<<_END
            <option value="$id">$name</option>
_END;
            }
                echo <<<_END
                </select>
                 </div>
                 </div>
                 <br>
                 <button type="submit" class="btn btn-primary">Show Summary</button>
             </form>
             </div>
_END;
if(isset($_GET['custname']) && $_GET['custname']!=''){
    
    $customer=mysqli_real_escape_string($db,$_GET['custname']);
    
    $q="SELECT t.cid,t.delivery_time,cast(t.dod as date) as d,COALESCE(t.CowMilk,0) as cow_milk, COALESCE(t.sCowMilk,0) as s_cow_milk, COALESCE(t.Sahiwal,0) as sahiwal_milk,COALESCE(t.sSahiwalMilk,0) as s_sahiwal_milk, COALESCE(t.buffalo,0) as buffalo_milk,COALESCE(t.sBuffaloMilk,0) as s_buffalo_milk from (SELECT cd.id,cd.cid,cs.delivery_time,cd.dod,case when cs.milktype=1 then cd.delivered_qty end as CowMilk ,case when cs.milktype=1 then cd.qty end as sCowMilk, case when cs.milktype=2 then cd.delivered_qty end as Sahiwal , case when cs.milktype=2 then cd.qty end as sSahiwalMilk ,case when cs.milktype=3 then cd.delivered_qty end as buffalo, case when cs.milktype=3 then cd.qty end as sBuffaloMilk FROM customer_delivery_log cd INNER JOIN customer_subscription cs on cs.id=cd.csid where cs.is_active=1  and  cs.is_deleted=0 and cd.cid='$customer') as t";
    $r=mysqli_query($db,$q);
    $t1=0;
    $t2=0;
    $t3=0;
    $tprcow=0;
    $tprsahiwal=0;
    $tprbflo=0;
    if(mysqli_num_rows($r)>0){
        $prcow=0;
        $prsahiwal=0;
        $prbflo=0;
    while($res=mysqli_fetch_assoc($r)){
        $cname=getDimensionValue($db,'customer',$res['cid'],'fname').' '.getDimensionValue($db,'customer',$res['cid'],'lname');
        $phone=getDimensionValue($db,'customer',$res['cid'],'phone');
        $amt1=getDimensionValue($db,'customer',$res['cid'],'price_cow_milk');
        $amt2=getDimensionValue($db,'customer',$res['cid'],'price_sahiwal_milk');
        $amt3=getDimensionValue($db,'customer',$res['cid'],'price_buffalo_milk');
        $qty=$res['cow_milk'];
        $qty1=$res['sahiwal_milk'];
        $qty2=$res['buffalo_milk'];
       
        if($qty<0){
        $prcow=$amt1*$qty;
        }
        elseif($qty1<0){
        $prsahiwal=$amt2*$qty1;
        }
        elseif($qty2<0){
        $prbflo=$amt3*$qty2;
        }
        $t1+=$qty;
        $t2+=$qty1;
        $t3+=$qty2;
        $tprcow+=$prcow;
        $tprsahiwal+=$prsahiwal;
        $tprbflo+=$prbflo;
    }
    
    echo <<<_END
        <div class="col-lg-12">
        <h5 class="mb-4 mt-4">Customer : <strong>$cname<strong></h5>
        <h5>Mobile No. : $phone</h5>
        <div class="table table-responsive">
        <table class="table table-bordered">
            <tr>
            <th>Type</th>
            <th>Cow</th>
            <th>Sahiwal</th>
            <th>Buffalo</th>
            </tr>
            <tr>
            <th>Total Delivered</th>
            <td>$t1</td>
            <td>$t2</td>
            <td>$t3</td>
            </tr>
            <tr>
            <th>Total Bill</th>
            <td>$tprcow</td>
            <td>$tprsahiwal</td>
            <td>$tprbflo</td>
            </tr>
            <tr>
            <th>Unbilled Qty</th>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            </table>
            </div>
            </div>
          <div class="col-lg-12">
           <form action="" method="post">
           <div class="form-group">
           <label for="amount" class="mr-4">Total Amount Due</label>
           <input type="text" name="due" value=""/>
           </div>
            <button type="submit" name="btn" class="btn btn-primary btn-block mt-4">Generate Bill</button>
           </form>
           </div>

_END;
                  
    }
}
else{
    echo 'No record found';
}
echo <<<_END
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
}
else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
?>