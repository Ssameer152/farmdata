<?php
session_start();


function getDimensionValue($db, $table, $gid, $name)
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
        $q = "SELECT * from vendor WHERE id='$mid' and is_deleted=0";
        $r = mysqli_query($db, $q);
        $res = mysqli_fetch_assoc($r);

        $db_name = $res['name'];
        $db_email = $res['email'];
        $db_phone = $res['phone'];
        $db_address = $res['address'];
        $db_contact = $res['contact_person'];
    } else {
        $db_name = '';
        $db_email = '';
        $db_phone = '';
        $db_address = '';
        $db_contact = '';
    }
    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"/>
    </head>
    
    <body>    
_END;

    include_once 'nav.php';

    echo <<<_END

        <div class="container">
_END;
    if (isset($_GET['msg']) && $_GET['msg'] != '') {
        $msg = $_GET['msg'];
        echo <<<_END
<div class="col-lg-6">
    <div class="alert alert-primary" role="alert">
$msg
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
</div>
_END;
    }
    echo <<<_END
			<div class="row">
                <div class="col-lg-12">
                    <h2>Vendors</h2>
                    <form action="vendors_add.php" method="post">
                        
						<div class="form-group">
                            <label for="particular">Name</label>
_END;
    if ($db_name == '') {
        echo <<<_END
                            <input type="text" name="vname" class="form-control" required>
_END;
    } else {
        echo <<<_END
                            <input type="text" name="vname" value="$db_name" class="form-control" required>
_END;
    }
    echo <<<_END
						</div>
                        <div class="form-row">
                            <div class="col">
                                <label for="particular">Mobile No.</label>
_END;
    if ($db_phone == '') {
        echo <<<_END
                                <input type="text" class="form-control" name="mobile required">
_END;
    } else {
        echo <<<_END
                            <input type="text" class="form-control" value="$db_phone" name="mobile"  required> 
_END;
    }
    echo <<<_END
                            </div>
                            <div class="col">
                                <label for="particular">Email</label>
_END;
    if ($db_email == '') {
        echo <<<_END
                                <input type="email" class="form-control" name="email">
_END;
    } else {
        echo <<<_END
                            <input type="email" value="$db_email" class="form-control" name="email">
_END;
    }
    echo <<<_END
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="leaseduntil">Contact Person</label>
_END;
    if ($db_contact == '') {
        echo <<<_END
                            <input type="text" name="cperson" class="form-control" required>
_END;
    } else {
        echo <<<_END
                            <input type="text" value="$db_contact" name="cperson" class="form-control" required>
_END;
    }
    echo <<<_END
                        </div>
                        <div class="form-group">
                            <label for="leaseduntil">Address</label>
_END;
    if ($db_address == '') {
        echo <<<_END
                            <input type="text" name="address" class="form-control">
_END;
    } else {
        echo <<<_END
                            <input type="text" value="$db_address" name="address" class="form-control">
_END;
    }
    echo <<<_END
                        </div>
_END;
    if (isset($mid)) {
        echo <<<_END
                            <input type="hidden" name="mid" value="$mid">
_END;
    }
    echo <<<_END
						<button type="submit" class="btn btn-primary">Add Vendor</button>
					</form>
                </div>
                
                <div class="col-lg-12 mb-4">
                <h4 class="text-center mb-4">Vendor Details</h4>
                    <div class="table-responsive">
                        <table id="table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Mobile</th>
                                    <th>email</th>
                                    <th>Contact Person</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

    $q = "SELECT * FROM vendor WHERE is_deleted=0 ORDER BY id DESC";
    $r = mysqli_query($db, $q);

    while ($res = mysqli_fetch_assoc($r)) {
        $sn = $res['id'];
        $name = $res['name'];
        $address = $res['address'];
        $email = $res['email'];
        $phone = $res['phone'];
        $cperson = $res['contact_person'];

        echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$name</td>
        <td>$address</td>
        <td>$phone</td>
        <td>$email</td>
        <td>$cperson</td>
        <td><a href="vendor.php?id=$sn">Modify</a> | <a href="delete.php?table=vendor&rid=$sn&return=vendor">Delete</a></td>
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
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
