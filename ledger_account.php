<?php
echo <<<_END
    <!DOCTYPE html>
    <html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   _END;
include_once 'nav.php';
echo <<<_END
</head>
<body>
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
</body>
</html>
_END;
