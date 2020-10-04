<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    
}
else
{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>