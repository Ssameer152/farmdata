<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_POST['cid']) && $_POST['cid']!='' && isset($_POST['cow_milk']) && $_POST['cow_milk']!='' && isset($_POST['sahiwal_milk']) && $_POST['sahiwal_milk']!='' && isset($_POST['buffalo_milk']) && $_POST['buffalo_milk']!='' && isset($_POST['price_cow']) && $_POST['price_cow']!='' && isset($_POST['price_sahiwal']) && $_POST['price_sahiwal']!='' && isset($_POST['price_buffalo']) && $_POST['price_buffalo']!='' && isset($_POST['bill_date']) && $_POST['bill_date']!='' && isset($_POST['due']) && $_POST['due']!='')
    {
        $cid = mysqli_real_escape_string($db,$_POST['cid']);
        $cow_milk = mysqli_real_escape_string($db,$_POST['cow_milk']);
        $sahiwal_milk = mysqli_real_escape_string($db,$_POST['sahiwal_milk']);
        $buffalo_milk = mysqli_real_escape_string($db,$_POST['buffalo_milk']);
        $cow_price = mysqli_real_escape_string($db,$_POST['price_cow']);
        $sahiwal_price = mysqli_real_escape_string($db,$_POST['price_sahiwal']);
        $buffalo_price = mysqli_real_escape_string($db,$_POST['price_buffalo']);
        $bill_date = mysqli_real_escape_string($db,$_POST['bill_date']);
        $amtdue = mysqli_real_escape_string($db,$_POST['due']);
                $q = "INSERT INTO customer_bill(cid,bill_date,cow_milk,sahiwal_milk,buffalo_milk,price_cow_milk,price_sahiwal_milk,price_buffalo_milk,amount_due) VALUES('$cid','$bill_date','$cow_milk','$sahiwal_milk','$buffalo_milk','$cow_price','$sahiwal_price','$buffalo_price','$amtdue')";
                $r = mysqli_query($db,$q);
        
                $msg = "Customer Bill Added";
        }
    
    else
    {
        $msg = "Please enter the details";
        echo <<<_END
        <meta http-equiv='refresh' content='0;url=customer_summary.php?custname=$cid&msg=$msg'>   
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