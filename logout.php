<?php
session_start();

if(isset($_SESSION['user']))
{
session_destroy();
echo <<<_END
<meta http-equiv='refresh' content='0;url=index.php'>
_END;
}
else
{
session_destroy();
echo <<<_END
<meta http-equiv='refresh' content='0;url=index.php'>
_END;
}

?>