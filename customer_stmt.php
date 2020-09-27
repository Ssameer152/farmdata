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
            $q="SELECT id,fname from customer where is_deleted=0 order by fname asc";
            $r=mysqli_query($db,$q);
            while($res=mysqli_fetch_assoc($r)){
                $name=$res['fname'];
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
                 <button type="submit" class="btn btn-primary">Show Statement</button>
             </form>
             </div>
_END;
if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date']!='' && $_GET['end_date']!='' && isset($_GET['custname']) && $_GET['custname']!=''){
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);
    $customer=mysqli_real_escape_string($db,$_GET['custname']);

    $q="SELECT id,cast(dod as date) as d ,cid,csid,delivered_qty from customer_delivery_log where cast(dod as date)>='$start_date' and cast(dod as date)<='$end_date' and cid='$customer' and is_deleted=0";
    $r=mysqli_query($db,$q);
    if(mysqli_num_rows($r)>0){
    $q1="SELECT fname from customer where id='$customer' and is_deleted=0";
    $r1=mysqli_query($db,$q1);
    $re1=mysqli_fetch_assoc($r1);
    $cname=$re1['fname'];
    $date='';
    echo <<<_END
        <div class="col-lg-12">
        <h4>Customer: $cname</h4>
        <div class="table table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
            <th>Date</th>
           <th>Milk type</th>
           <th>Quantity</th>
            <th>Amount</th>
            </tr>
            </thead>
            <tbody>
_END;
    $q1="SELECT milktype from customer_subscription where id='$customer' and is_deleted=0";
    $r1=mysqli_query($db,$q1);
    $res1=mysqli_fetch_assoc($r1);
    $milktype=$res1['milktype'];
    if($milktype==1){
        $milktype='Cow Milk';
    }
    elseif($milktype==2){
        $milktype='Sahiwal Milk';
    }
    elseif($milktype==3){
        $milktype='Buffalo Milk';
    }
    $q2="SELECT sum(delivered_qty) as total from customer_delivery_log where cid='$customer'";
    $r2=mysqli_query($db,$q2);
    $res2=mysqli_fetch_assoc($r2);
    $total=$res2['total'];
    while($res=mysqli_fetch_assoc($r)){
        $d=$res['d'];
        $dt=date("d-m-Y", strtotime($d));
                if($d!=$date){
                echo <<<_END
                <tr>
                <td>$dt</td>
_END;
                }
           $qty=$res['delivered_qty'];
           if($milktype=='Cow Milk'){
               $amt=50*$qty;
           }
                    echo <<<_END
                <td>$milktype</td>
                 <td>$qty</td>
                 <td>$amt</td>      
_END;
        }
        echo <<<_END
        <tr>
        <th colspan='2'>Total</th>
        <td>$total</td>
        <td>
_END;
        $TotalAmt=50*$total;
        echo <<<_END
        $TotalAmt
        </td>
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