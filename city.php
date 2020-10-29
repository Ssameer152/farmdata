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

    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
        <script>
        $(function(){
        setTimeout(function(){
            $('#success').hide('blind',{},400);
            },4000);
            });
        </script>
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
    <div class="alert alert-primary" id="success" role="alert">
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
                <div class="col-lg-6">
                    <h2>City</h2>
                    <form action="city_add.php" method="post">
                    <div class="form-group">
                            <label for="state">State</label>
                            <select name="state"  class="form-control">
                            <option value="">--Select State--</option>
_END;
    $q = "SELECT * from state where is_deleted=0";
    $r = mysqli_query($db, $q);
    while ($res = mysqli_fetch_assoc($r)) {
        $id = $res['id'];
        $name = $res['name'];
        echo <<<_END
                            <option value="$id">$name</option>
_END;
    }
    echo <<<_END
                        </select>
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" name="city" class="form-control">
                        </div>
						<button type="submit" class="btn btn-primary">Add City</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

    $q = "SELECT * FROM city WHERE is_deleted=0";
    $r = mysqli_query($db, $q);

    while ($res = mysqli_fetch_assoc($r)) {
        $sn = $res['id'];
        $city = $res['name'];
        $state = getDimensionValue($db, 'state', $res['state_id'], 'name');
        echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$city</td>
        <td>$state</td>
        <td><a href="delete.php?table=city&rid=$sn&return=city">Delete</a></td>
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
    </body>
</html>
_END;
} else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
