<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_GET['tid']) && $_GET['tid']!=''){
        $mid = $_GET['tid'];
        $q="SELECT * from transactions_accounts where id='$mid' and is_deleted=0";
        $r=mysqli_query($db,$q);
        $res=mysqli_fetch_assoc($r);
        $db_traccount=$res['account'];
    }
    else{
        $db_traccount='';
    }
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
                <div class="col-lg-6">
                    <h2>Transaction Accounts</h2>
                    <form action="transaction_account_add.php" method="post">
                        <div class="form-group">
                            <label for="account">Account</label>
_END;
                        if($db_traccount==''){
                            echo <<<_END
                            <input type="text" name="t_account" class="form-control">
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="text" value="$db_traccount" name="t_account" class="form-control">
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
						<button type="submit" class="btn btn-primary">Add Account</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Transaction Account</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

$q = "SELECT * FROM transactions_accounts WHERE is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $ac = $res['account'];
    
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$ac</td>
        <td><a href="transaction_account.php?table=transactions_accounts&return=transaction_account&tid=$sn">Modify</a> | <a href="delete.php?table=transactions_accounts&rid=$sn&return=transaction_account">Delete</a></td>
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
