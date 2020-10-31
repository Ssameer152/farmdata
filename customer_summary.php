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
 _END;
 if(isset($_GET['msg']) && $_GET['msg']!=''){
    $msg = $_GET['msg'];
    echo<<<_END
<div class="col-lg-6">
    <div class="alert alert-primary" role="alert">
$msg
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
</div>
_END;
} 
echo <<<_END
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
    
    $q="SELECT t.cid,t.delivery_time,cast(t.dod as date) as d,COALESCE(t.CowMilk,0) as cow_milk, COALESCE(t.Sahiwal,0) as sahiwal_milk, COALESCE(t.buffalo,0) as buffalo_milk from (SELECT cd.id,cd.cid,cs.delivery_time,cd.dod,case when cs.milktype=1 then cd.delivered_qty end as CowMilk , case when cs.milktype=2 then cd.delivered_qty end as Sahiwal , case when cs.milktype=3 then cd.delivered_qty end as buffalo FROM customer_delivery_log cd INNER JOIN customer_subscription cs on cs.id=cd.csid where cs.is_active=1  and  cs.is_deleted=0 and cd.cid='$customer') as t";
    $r=mysqli_query($db,$q);
    $q1="SELECT t.cid,t.delivery_time,cast(t.dod as date) as d,COALESCE(t.CowMilk,0) as cow_milk, COALESCE(t.Sahiwal,0) as sahiwal_milk, COALESCE(t.buffalo,0) as buffalo_milk from (SELECT cd.id,cd.cid,cs.delivery_time,cd.dod,case when cs.milktype=1 then cd.delivered_qty end as CowMilk , case when cs.milktype=2 then cd.delivered_qty end as Sahiwal , case when cs.milktype=3 then cd.delivered_qty end as buffalo FROM customer_delivery_log cd INNER JOIN customer_subscription cs on cs.id=cd.csid where cs.is_active=1  and  cs.is_deleted=0 and cd.cid='$customer' and cd.delivered_qty<0) as t";
    $r1=mysqli_query($db,$q1);
    $t1=0;
    $t2=0;
    $t3=0;
    $t4=0;
    $t5=0;
    $t6=0;
    $tprcow=0;
    $tprsahiwal=0;
    $tprbflo=0;
    if(mysqli_num_rows($r)>0){
        $prcow=0;
        $prsahiwal=0;
        $prbflo=0;
    while($res=mysqli_fetch_assoc($r)){
        $cid=$res['cid'];
        $date=$res['d'];
        $cname=getDimensionValue($db,'customer',$res['cid'],'fname').' '.getDimensionValue($db,'customer',$res['cid'],'lname');
        $phone=getDimensionValue($db,'customer',$res['cid'],'phone');
        $amt1=getDimensionValue($db,'customer',$res['cid'],'price_cow_milk');
        $amt2=getDimensionValue($db,'customer',$res['cid'],'price_sahiwal_milk');
        $amt3=getDimensionValue($db,'customer',$res['cid'],'price_buffalo_milk');
        $qty=$res['cow_milk'];
        $qty1=$res['sahiwal_milk'];
        $qty2=$res['buffalo_milk'];
        $t1+=$qty;
        $t2+=$qty1;
        $t3+=$qty2;
        $prcow=$amt1*$qty;
        $prsahiwal=$amt2*$qty1;
        $prbflo=$amt3*$qty2;
        $tprcow+=$prcow;
        $tprsahiwal+=$prsahiwal;
        $tprbflo+=$prbflo;
    }
    
    while($res1=mysqli_fetch_assoc($r1)){
        $qty3=$res1['cow_milk'];
        $qty4=$res1['sahiwal_milk'];
        $qty5=$res1['buffalo_milk'];
        $t4+=abs($qty3);
        $t5+=abs($qty4);
        $t6+=abs($qty5);
    }
    $u1=$t1-$t4;
    $u2=$t2-$t5;
    $u3=$t3-$t6;
    $u=$u1*$amt1+$u2*$amt2+$u3*$amt3;
    $u=number_format((float)$u, 2, '.', ''); 
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
            <th>Total Delivered Qty</th>
            <td>$t1</td>
            <td>$t2</td>
            <td>$t3</td>
            </tr>
            <tr>
            <th>Total Bill Qty</th>
            <td>$t4</td>
            <td>$t5</td>
            <td>$t6</td>
            </tr>
            <tr>
            <th>Unbilled Qty</th>
            <td>$u1</td>
            <td>$u2</td>
            <td>$u3</td>
            </tr>
            </table>
            </div>
            </div>
          <div class="col-lg-12">
           <form action="Generate_bill.php" method="post">
           <div class="form-group">
           <label for="amount" class="mr-4">Total Amount Due</label>
           <input type="hidden" name="cid" value="$cid"/>
           <input type="hidden" name="cow_milk" value="$t1"/>
           <input type="hidden" name="sahiwal_milk" value="$t2"/>
           <input type="hidden" name="buffalo_milk" value="$t3"/>
           <input type="hidden" name="price_cow" value="$amt1"/>
           <input type="hidden" name="price_sahiwal" value="$amt2"/>
           <input type="hidden" name="price_buffalo" value="$amt3"/>
           <input type="hidden" name="bill_date" value="$date"/>
           <input type="text" name="due" value="$u"/>
           </div>
            <button type="submit" name="btn" class="btn btn-primary btn-block mt-4">Generate Bill</button>
           </form>
           </div>

_END;
                  
    }
    else{
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=customer_summary.php?custname=$cid&msg=$msg'>
_END;
    }
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