<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['t_account']) && $_POST['t_account']!='')
    {
        $account = mysqli_real_escape_string($db,$_POST['t_account']);   
        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q="UPDATE transactions_accounts set account='$account' where id='$mid' LIMIT 1";
            $r=mysqli_query($db,$q);
            $msg="Account Updated";
        }
        else
        {
        $q = "INSERT INTO transactions_accounts(account) VALUES('$account')";
        $r = mysqli_query($db,$q);
        
        $msg = "Transaction Account Added";
        }
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=transaction_account.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=transaction_account.php?msg=$msg'>   
_END;
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