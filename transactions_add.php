<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
    if(isset($_POST['particular']) && isset($_POST['rec']) && isset($_POST['paid']) && isset($_POST['dot']) && $_POST['particular']!='' && $_POST['rec']!='' && $_POST['paid']!='' && $_POST['dot']!='' && isset($_POST['area']) && $_POST['area']!='' && isset($_POST['tr_account']) && $_POST['tr_account']!='' && isset($_POST['tr_category']) && $_POST['tr_category']!='')
    {
        $area = mysqli_real_escape_string($db,$_POST['area']);
        $particular = mysqli_real_escape_string($db,$_POST['particular']);
        $rec = mysqli_real_escape_string($db,$_POST['rec']);
        $paid = mysqli_real_escape_string($db,$_POST['paid']);
        $dot = mysqli_real_escape_string($db,$_POST['dot']);
        $tr_account=mysqli_real_escape_string($db,$_POST['tr_account']);
        $tr_category=mysqli_real_escape_string($db,$_POST['tr_category']);
        if(isset($_POST['mid']) && $_POST['mid']!='')
        {
            $mid = $_POST['mid'];
            $q="UPDATE transactions set area='$area',dot='$dot',particular='$particular',amt_paid='$paid',amt_received='$rec',transaction_account='$tr_account',transaction_category='$tr_category' where id='$mid' LIMIT 1";
            $r=mysqli_query($db,$q);
            $msg="Transaction Updated";
        }
        else
        {
        $q = "INSERT INTO transactions(area,dot,particular,amt_paid,amt_received,transaction_account,transaction_category) VALUES('$area','$dot','$particular','$paid','$rec','$tr_account','$tr_category')";
        $r = mysqli_query($db,$q);
        
        $msg = "Transaction Added";
        }
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=transactions.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=transactions.php?msg=$msg'>   
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