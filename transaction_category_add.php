<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['t_category']) && $_POST['t_category']!='')
    {
        $category = mysqli_real_escape_string($db,$_POST['t_category']);   
        
        $q = "INSERT INTO transactions_category(category) VALUES('$category')";
        $r = mysqli_query($db,$q);
        
        $msg = "Transaction Category Added";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=transaction_category.php?msg=$msg'>
_END;
        
    }
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=transaction_category.php?msg=$msg'>   
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