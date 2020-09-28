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
    
    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
    </head>
    
    <body>    
_END;

include_once 'nav.php';

echo <<<_END

		<div class="container">
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
    
    echo <<<_END
    <option value="$sid">$sitename ($location)</option>
_END;

}

echo <<<_END
                            </select>
                        </div>
						<div class="form-group">
							<label for="particular">Particular</label>
							<input type="text" name="particular" class="form-control">
						</div>
                        <div class="form-row mb-4">
                            <div class="col">
                                <label for="particular">Amount Received</label>
                                <input type="text" class="form-control" name="rec" value="0">
                            </div>
                            <div class="col">
                                <label for="particular">Amount Paid</label>
                                <input type="text" class="form-control" name="paid" value="0">
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
                                echo <<<_END
                                <option value="$id">$account</option>
_END;
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
                                echo <<<_END
                            <option value="$id">$category</option>
_END;
                            }
                            echo <<<_END
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="leaseduntil">Date of Transaction</label>
                            <input type="date" name="dot" class="form-control">
                        </div>
						<button type="submit" class="btn btn-primary">Add Transaction</button>
					</form>
                </div>
                
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Date</th>
                                    <th>Area</th>
                                    <th>Particular</th>
                                    <th>Transaction Account</th>
                                    <th>Transaction catrgory</th>
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
    $paid = $res['amt_paid'];
    $received = $res['amt_received'];
    $part = $res['particular'];
    $area = getDimensionValue($db,'areas',$res['area'],'sitename');
    $tr_account=getDimensionValue($db,'transactions_accounts',$res['transaction_account'],'account');
    $tr_category=getDimensionValue($db,'transactions_category',$res['transaction_category'],'category');
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$dot</td>
        <td>$area</td>
        <td>$part</td>
        <td>$tr_account</td>
        <td>$tr_category</td>
        <td>&#8377; $received</td>
        <td>&#8377; $paid</td>
        <td><a href="delete.php?table=transactions&rid=$sn&return=transactions">Delete</a></td>
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

_END;

include_once 'foot.php';

echo <<<_END
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
