<?php
session_start();
if(isset($_SESSION['user'])){
echo <<<_END
    <!DOCTYPE html>
    <html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
</head>
<body>
_END;
include_once 'nav.php';
    echo <<<_END
    <div class="container">
        <div class="row">
            <div class="col mt-4">
                <h3>Ledger Account</h3>
                <div class="table-responsive mt-4">
                    <table class="table">
                        <form action="farmer_land_details_ap.php" method="post">
                            <thead>   
                            <tr>
                                <th class="w-25">Date</th>
                                <th>Particulars</th>
                                <th>Vch Type</th>
                                <th>Vch No</th>
                                <th>Debit</th>
                                <th>Credit</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="w-25">Date-Demo</td>
                                <td>Particulars-Demo</td>
                                <td>Vch Type-Demo</td>
                                <td>Vch No-Demo</td>
                                <td>Debit-Demo</td>
                                <td>Credit-Demo</td>
                            </tr>
                            </tbody>
                        </form>
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
else{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>