<?php
session_start();


function getDimensionValue($db,$table,$gid,$name){
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db,$q);
    
    $res = mysqli_fetch_assoc($r);
    
    $value = $res[$name];
    
    return $value;
}


if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_GET['tid']) && $_GET['tid']!=''){
        $mid = $_GET['tid'];
        $q="SELECT id,area,particular,amt_paid,amt_received,transaction_account,transaction_category,cast(dot as date) as dt from transactions where id='$mid' and is_deleted=0";
        $r=mysqli_query($db,$q);
        $res=mysqli_fetch_assoc($r);
        $db_area=$res['area'];
        $db_particular=$res['particular'];
        $db_amt_received=$res['amt_received'];
        $db_amt_paid=$res['amt_paid'];
        $db_traccount=$res['transaction_account'];
        $db_trcategory=$res['transaction_category'];
        $db_tdate=$res['dt'];
    }
    else{
        $db_area='';
        $db_particular='';
        $db_amt_paid='';
        $db_amt_received='';
        $db_traccount='';
        $db_trcategory='';
        $db_tdate='';
    }
    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <script>
        $(function(){
        setTimeout(function(){
        $('#success').hide('blind',{},400);
        },4000);
        });
        </script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"/>
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
    <div class="alert alert-primary" id="success" role="alert">
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
                    <h2>Transactions</h2>
                    <form action="transactions_add.php" method="post">
                        <div class="form-group">
                            <label for="area">Area</label>
                            <select name="area" class="form-control">
                                <option value="">--Select Area--</option>
_END;

$q = "SELECT * FROM areas WHERE is_deleted=0 order by sitename asc";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sid = $res['id'];
    $sitename = $res['sitename'];
    $location = $res['location'];
    if($sid==$db_area){
    echo <<<_END
    <option value="$sid" selected="selected">$sitename ($location)</option>
_END;
    }
    else{
        echo <<<_END
        <option value="$sid">$sitename ($location)</option>
_END;
    }
}

echo <<<_END
                            </select>
                        </div>
						<div class="form-group">
                            <label for="particular">Particular</label>
_END;
                        if($db_particular==''){
                            echo <<<_END
                            <input type="text" name="particular" class="form-control">
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="text" value="$db_particular" name="particular" class="form-control">
_END;
                        }
                        echo <<<_END
						</div>
                        <div class="form-row mb-4">
                            <div class="col">
                                <label for="particular">Amount Received</label>
_END;
                        if($db_amt_received==''){
                            echo <<<_END
                            <input type="text" class="form-control" name="rec" value="0">
_END;
                        }
                        else{
                                echo <<<_END
                                <input type="text" class="form-control" name="rec" value="$db_amt_received">
_END;
                        }
                            echo <<<_END
                            </div>
                            <div class="col">
                                <label for="particular">Amount Paid</label>
_END;
                            if($db_amt_paid==''){
                                echo <<<_END
                                <input type="text" class="form-control" name="paid" value="0">  
_END;
                            }
                            else{
                                echo <<<_END
                                <input type="text" class="form-control" name="paid" value="$db_amt_paid">
_END;
                            }
                            echo <<<_END
                            </div>
                        </div>
                        <div class="form-row mb-4">
                        <div class="col">
                            <label for="transaction_account">Transaction Accounts</label>
                            <select class="form-control" name="tr_account">
                            <option value="">--Select transaction account--</option>
_END;
                            $q="SELECT * from transactions_accounts where is_deleted=0";
                            $r=mysqli_query($db,$q);
                            while($res=mysqli_fetch_assoc($r)){
                                $id=$res['id'];
                                $account=$res['account'];
                                if($id==$db_traccount){
                                echo <<<_END
                                <option value="$id" selected="selected">$account</option>
_END;
                            }
                            else{
                                echo <<<_END
                                <option value="$id">$account</option>
_END;
                            }
                        }
                            echo <<<_END
                            </select>
                        </div>
                        <div class="col">
                        <label for="transaction_category">Transaction Category</label>
                        <select class="form-control" name="tr_category">
                        <option value="">--Select transaction category--</option>
_END;
                            $q="SELECT * from transactions_category where is_deleted=0";
                            $r=mysqli_query($db,$q);
                            while($res=mysqli_fetch_assoc($r)){
                                $id=$res['id'];
                                $category=$res['category'];
                                if($id==$db_trcategory){
                                echo <<<_END
                            <option value="$id" selected="selected">$category</option>
_END;
                            }
                            else{
                                echo <<<_END
                                <option value="$id">$category</option>
_END;
                            }
                        }
                            echo <<<_END
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trdate">Date of Transaction</label>
_END;
                        if($db_tdate==''){
                            echo <<<_END
                            <input type="date" name="dot" class="form-control">   
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="date" value="$db_tdate" name="dot" class="form-control">
_END;
                        }
                        echo <<<_END
                        </div>
_END;
if(isset($mid)){
    echo <<<_END
    <input type="hidden" name="mid" value="$mid">
_END;
}
                        echo <<<_END
						<button type="submit" class="btn btn-primary">Add Transaction</button>
					</form>
                </div>
                
                <div class="col-lg-12">
                    <div class="row">
                    <div class="table-responsive">
                        <table id="table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Date</th>
                                    <th>Area</th>
                                    <th>Particular</th>
                                    <th>Transaction Account</th>
                                    <th>Received</th>
                                    <th>Paid</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

$q = "SELECT * FROM transactions WHERE is_deleted=0 ORDER BY id DESC";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $dot = $res['dot'];
    $dot=date("d-m-Y", strtotime($dot));
    $paid = $res['amt_paid'];
    $received = $res['amt_received'];
    $part = $res['particular'];
    $area = getDimensionValue($db,'areas',$res['area'],'sitename');
    $tr_account=getDimensionValue($db,'transactions_accounts',$res['transaction_account'],'account');
   // $tr_category=getDimensionValue($db,'transactions_category',$res['transaction_category'],'category');
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$dot</td>
        <td>$area</td>
        <td>$part</td>
        <td>$tr_account</td>
        <td>&#8377; $received</td>
        <td>&#8377; $paid</td>
        <td><a href="transactions.php?table=transactions&return=transactions&tid=$sn">Modify</a> | <a href="delete.php?table=transactions&rid=$sn&return=transactions">Delete</a></td>
    </tr>
_END;
}

echo <<<_END
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>

_END;

include_once 'foot.php';

echo <<<_END
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
else
{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>	
