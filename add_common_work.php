<?php
session_start();
if (isset($_SESSION['user'])) {
    include_once 'db.php';
    /*function getDimensionValue($db, $table, $gid, $name)
{
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db, $q);
    $res = mysqli_fetch_assoc($r);
    $value = $res[$name];
    return $value;
}
if (isset($_SESSION['user'])) {
    include_once 'db.php';
    if (isset($_GET['id']) && $_GET['id'] != '') {
        $mid = $_GET['id'];
        $q = "SELECT * from customer WHERE id='$mid' and is_deleted=0";
        $r = mysqli_query($db, $q);
        $res = mysqli_fetch_assoc($r);

        $db_fname = $res['fname'];
        $db_lname = $res['lname'];
        $db_email = $res['email'];
        $db_phone = $res['phone'];
        $db_zipcode = $res['zipcode'];
        $db_city = $res['city'];
        $db_state = $res['state'];
        $db_address = $res['address'];
        $db_cowMilkPrice = $res['price_cow_milk'];
        $db_sahiwalMilkPrice = $res['price_sahiwal_milk'];
        $db_buffaloMilkPrice = $res['price_buffalo_milk'];
    } else {
        $db_fname = '';
        $db_lname = '';
        $db_email = '';
        $db_phone = '';
        $db_zipcode = '';
        $db_city = '';
        $db_state = '';
        $db_address = '';
        $db_cowMilkPrice = '';
        $db_sahiwalMilkPrice = '';
        $db_buffaloMilkPrice = '';
    }*/
    echo <<<_END
    <html>

    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" />
        <script>
            function ask(anchor) {
                var conf = confirm("Do you want to delete?");
                if (conf)
                    window.location = anchor.attr("href");
            }
        </script>
    </head>
_END;

    include_once 'nav.php';

    echo <<<_END
<body>
<div class="container">
            <div class="row">
                <div class="col-lg-12 mb-4">
                <div class="table-responsive">
                    <h2>Add Common work Details</h2>
                    <p></p>
                    <form action="add_common_work_ap.php" name="" method="post">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="4"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                    <th>Start Date</th>
                                    <td>
                                        <input type="date" class="form-control" name="sd" id="">
                                    </td>
                                </tr>
                                <!------------------Start Activity field---------------------->
                                <tr>
                                    <th>Activity</th> 
                                    <td>
 

                                        <select class="form-control" name="activity">
_END;
    $q = "SELECT * FROM activities where is_deleted=0";
    $r = mysqli_query($db, $q);
    while ($res = mysqli_fetch_assoc($r)) {
        $aid = $res['id'];
        $name = $res['activity'];
        echo <<<_END
                                            <option value="$aid">$name</option>
    _END;
    }

    echo <<<_END
                                        </select>
                                         </td>                              
_END;
    echo <<<_END
                                </tr>
                                <!------------------Start person field---------------------->
                                <tr> 
                                <th>Person</th>
                                <td>
                                <select class="form-control" name="person">
_END;
    $q = "SELECT * FROM `people` WHERE is_deleted=0";
    $r = mysqli_query($db, $q);

    while ($res = mysqli_fetch_assoc($r)) {
        $pid = $res['id'];
        $name = $res['fname'];
        echo <<<_END
                                    <option value="$pid">$name</option>
_END;
    }
    echo <<<_END
                                </select>
                                </td>
                                </tr>
                                <!------------------Start Resource field---------------------->
                                <tr>
                                    <th>Resource</th>
                                    <td>
                                        <select class="form-control" name="resource">
_END;
    $q = "SELECT * FROM resources WHERE is_deleted=0 order by resourcename asc";
    $r = mysqli_query($db, $q);
    while ($res = mysqli_fetch_assoc($r)) {
        $rid = $res['id'];
        $name = $res['resourcename'];
        $unit = $res['unit'];
        echo <<<_END
                                            <option value="$rid">$name</option>
    _END;
    }
    echo <<<_END
                                            
                                        </select>
                                    </td>
                                </tr>
                                <!------------------Start Quantity field---------------------->
                                    <tr>
                                        <th>Cow Milk quantity <p class="mt-2 mb-0"><input type="text" class="form-control" name="cmq" id=""></p>
                                        </th>
                                            <th>Sahiwal Milk quantity<p class="mt-2 mb-0"><input type="text" class="form-control" name="smq" id=""></p>
                                            </th>
                                            <th>buffalos Milk quantity<p class="mt-2 mb-0"><input type="text" class="form-control" name="bmq" id=""></p>
                                            </th>    
                                    </tr>   
                                    <tr>
                                    <th>Quantity<p class="mt-2 mb-0"><input type="text" class="form-control" name="qty" id=""></p>
                                        </th>
                                    </tr>
                    <!-----------------------------Close field---------------------->
                                <tr>
                                    <td colspan="4"><input type="submit" name="submit" class="btn btn-block btn-primary" value="Add work"></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    </div>
                </div>
                <div class="col-lg-12 mb-4">
                    <h4 class="text-center mb-4">Common Details</h4>
                    <div class="table-responsive">
                        <table id="table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>person</th>
                                    <th>Activity</th>
                                    <th>Resource</th>
                                    <th>Cow Milk qty</th>
                                    <th>Sahiwal Milk qty</th>
                                    <th>Buffalo Milk qty</th>
                                    <th>Quantity</th> 
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;
    /*  $q = "SELECT * FROM customer WHERE is_deleted=0";
                            $r = mysqli_query($db, $q);
                            $sn = 0;
                            while ($res = mysqli_fetch_assoc($r)) {
                                $sn = $sn + 1;
                                $id = $res['id'];
                                $name = $res['fname'] . ' ' . $res['lname'];
                                $email = $res['email'];
                                $phone = $res['phone'];
                                $city = getDimensionValue($db, 'city', $res['city'], 'name');
                                $address = $res['address'];
                                $priceCowMilk = $res['price_cow_milk'];
                                $priceSahiwalMilk = $res['price_sahiwal_milk'];
                                $priceBuffaloMilk = $res['price_buffalo_milk'];*/
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$name</td>
        <td>$address</td>
        <td>$priceCowMilk</td>
        <td>$priceSahiwalMilk</td>
        <td>$priceBuffaloMilk</td>
        <td>$phone</td>
        <td><a href="customer.php?id=$id">Modify</a> | <a onclick='javascript:ask($(this));return false;' href="delete.php?table=customer&rid=$id&return=customer">Delete</a> | <a href="customer_subscription.php?cid=$id">Subscribe</a></td>
    </tr>
_END;
    //   }
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
        <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#table').DataTable();
            });
        </script>
    </body>
    </html>
_END;
    //} else {
    //  $msg = "Please Login";
    /*echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;*/
}
