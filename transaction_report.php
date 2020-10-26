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
        <h3 class="mb-4">Transaction Report</h3>
        <form action="transaction_report.php" method="get">
                        <div class="row">
                            <div class="col-lg">
                                <input type="date" class="form-control" name="start_date">
                            </div>
                            <div class="col-lg">
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                        <br>
                        <div class="form-row">
                        <div class="col-lg-6">
                        <select class="form-control" name="account">
                        <option value="">--Select Account(Optional)--</option>
_END;
                        $q="SELECT * from transactions_accounts where is_deleted=0";
                        $r=mysqli_query($db,$q);
                        while($res=mysqli_fetch_assoc($r)){
                            $id=$res['id'];
                            $account=$res['account'];
                            echo <<<_END
                            <option value="$id">$account</option>
_END;
                        }
                        echo <<<_END
                        </select>
                    </div>
                        <div class="col-lg-6">
                            <select class="form-control" name="category">
                            <option value="">--Select Category(Optional)--</option>
_END;
                        $q="SELECT * from transactions_category where is_deleted=0";
                        $r=mysqli_query($db,$q);
                        while($res=mysqli_fetch_assoc($r)){
                            $id=$res['id'];
                            $category=$res['category'];
                            echo <<<_END
                            <option value="$id">$category</option>
_END;
                        }
                            echo <<<_END
                            </select>
                        </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Show Report</button>
                    </form>
                    </div>
_END;

if(isset($_GET['start_date']) && isset($_GET['end_date']) && isset($_GET['account']) && $_GET['start_date']!='' && $_GET['end_date']!='' && $_GET['account']!='')
{
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);
    $account=mysqli_real_escape_string($db,$_GET['account']);
    $q="SELECT sum(amt_paid) as debit,sum(amt_received) as credit,particular,transaction_account,transaction_category,cast(dot as date) as d from transactions where is_deleted=0 and cast(dot as date)>='$start_date' and cast(dot as date)<='$end_date' and transaction_account='$account' group by transaction_account order by cast(dot as date)";
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
        <table class="table table-bordered">
        <tr>
        <th>Particular</th>
        <th>Account</th>
        <th>Category</th>
        <th>Debit</th>
        <th>Credit</th>
        </tr>
_END;
while($res=mysqli_fetch_assoc($r)){
    $particular=$res['particular'];
    $account=getDimensionValue($db,'transactions_accounts',$res['transaction_account'],'account');
    $category=getDimensionValue($db,'transactions_category',$res['transaction_category'],'category');
    $debit=$res['debit'];
    $credit=$res['credit'];
    $balance=$credit-$debit;
    echo <<<_END
        <tr>
        <td>$particular</td>
        <td>$account</td>
        <td>$category</td>
        <td>$debit</td>
        <td>$credit</td>
        </tr>
_END;
}
    }
}
elseif(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date']!='' && $_GET['end_date']!='')
{
    $start_date = mysqli_real_escape_string($db,$_GET['start_date']);
    $end_date = mysqli_real_escape_string($db,$_GET['end_date']);
    $q="SELECT sum(amt_paid) as debit,sum(amt_received) as credit,particular,transaction_account,transaction_category,cast(dot as date) as d from transactions where is_deleted=0 and cast(dot as date)>='$start_date' and cast(dot as date)<='$end_date' group by transaction_account order by cast(dot as date)";
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
        <table class="table table-bordered">
        <tr>
        <th>Particular</th>
        <th>Account</th>
        <th>Category</th>
        <th>Debit</th>
        <th>Credit</th>
        </tr>
_END;
while($res=mysqli_fetch_assoc($r)){
    $particular=$res['particular'];
    $account=getDimensionValue($db,'transactions_accounts',$res['transaction_account'],'account');
    $category=getDimensionValue($db,'transactions_category',$res['transaction_category'],'category');
    $debit=$res['debit'];
    $credit=$res['credit'];
    $balance=$credit-$debit;
    echo <<<_END
        <tr>
        <td>$particular</td>
        <td>$account</td>
        <td>$category</td>
        <td>$debit</td>
        <td>$credit</td>
        </tr>
_END;
}
    }
    
    echo <<<_END
    </table>
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