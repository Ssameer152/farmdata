<?php
session_start();

if (isset($_SESSION['user'])) {
    include_once 'db.php';

    if (isset($_GET['id']) && $_GET['id'] != '') {
        $mid = $_GET['id'];
        $q = "SELECT * from cattle WHERE id='$mid' and is_deleted=0";
        $q1 = "SELECT cast(date_purchase as date) as pd from cattle where is_deleted=0";
        $r1 = mysqli_query($db, $q1);
        $re1 = mysqli_fetch_assoc($r1);
        $r = mysqli_query($db, $q);
        $res = mysqli_fetch_assoc($r);
        $cid = $res['id'];

        $db_cname = $res['name'];
        $db_ctype = $res['type_id'];
        $db_pdate = $re1['pd'];
        $db_cbreed = $res['breed_id'];
        $db_age = $res['age_when_purchased'];
    } else {
        $db_cname = '';
        $db_ctype = '';
        $db_pdate = '';
        $db_cbreed = '';
        $db_age = '';
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
        <script>
        $(function(){
        setTimeout(function(){
        $('#success').hide('blind',{},300);
        },3000);
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
                    <h2>Cattle</h2>
_END;
    echo <<<_END
                        <form action="cattle_ap.php" method="post">
                        <div class="form-group">
                        <label for="cattlename">Name</label>
_END;
    if ($db_cname == '') {
        echo <<<_END
                        <input type="text" name="name" class="form-control">
_END;
    } else {
        echo <<<_END
                <input type="text" name="name" value="$db_cname" class="form-control">
_END;
    }
    echo <<<_END
                    </div>
                        <div class="form-group">
                        <label for="type">Cattle Type</label>
                        <select name="ctype" class="form-control">
                            <option value="">--Select Cattle Type--</option>
_END;
    $q = "SELECT * FROM cattle_type WHERE is_deleted=0 order by name asc";
    $r = mysqli_query($db, $q);
    while ($res = mysqli_fetch_assoc($r)) {
        $sid = $res['id'];
        $name = $res['name'];
        if ($sid == $db_ctype) {
            echo <<<_END
            <option value="$sid" selected="selected">$name</option>
_END;
        } else {
            echo <<<_END
            <option value="$sid">$name</option>
_END;
        }
    }
    echo <<<_END
                        </select>
                        </div>
                        <div class="form-group">
                        <label for="date">Purchase Date</label>
_END;
    if ($db_pdate == '') {
        echo <<<_END
                        <input type="date" name="pdate" class="form-control">
_END;
    } else {
        echo <<<_END
                        <input type="date" name="pdate" value="$db_pdate" class="form-control">
_END;
    }
    echo <<<_END
                    </div>
    <div class="form-group">
        <label for="breed">Breed</label>
        <select name="breed" class="form-control">
            <option value="">--Select Breed--</option>
_END;
    $q = "SELECT * FROM cattle_breed WHERE is_deleted=0 order by breed asc";
    $r = mysqli_query($db, $q);

    while ($res = mysqli_fetch_assoc($r)) {
        $sid = $res['id'];
        $breed = $res['breed'];

        if ($sid == $db_cbreed) {
            echo <<<_END
    <option value="$sid" selected="selected">$breed</option>
_END;
        } else {
            echo <<<_END
    <option value="$sid">$breed</option>
_END;
        }
    }
    echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="age">Age when purchased (in Months)</label>
_END;

    if ($db_age == '') {
        echo <<<_END
       <input type="text" name="age"  class="form-control"> 
_END;
    } else {
        echo <<<_END
      <input type="text" name="age" value="$db_age" class="form-control">
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
						<button type="submit" class="btn btn-primary">Add Cattle</button>
                    </form>
_END;
    echo <<<_END
                </div>
<div class="col-lg-12 mb-4">
<h4 class="text-center mb-4">Cattle Details</h4>
    <div class="table-responsive">
        <table id="table" class="table table-striped">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Cattle Name</th>
                    <th>Cattle Type</th>
                    <th>Date of purchase</th>
                    <th>Breed</th>
                    <th>Age when purchased</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
_END;

    $q = "SELECT id,name,type_id,cast(date_purchase as date) as dp,breed_id,age_when_purchased from cattle where is_deleted=0";
    $r = mysqli_query($db, $q);
    $sn = 0;
    while ($res = mysqli_fetch_assoc($r)) {
        $id = $res['id'];
        $cname = $res['name'];
        $ctype = $res['type_id'];
        $pdate = $res['dp'];
        $age = $res['age_when_purchased'];
        $q2 = "SELECT * FROM cattle_type WHERE id='$ctype'";
        $r2 = mysqli_query($db, $q2);

        $re2 = mysqli_fetch_assoc($r2);
        $cattleType = $re2['name'];
        $sn = $sn + 1;

        $breed = $res['breed_id'];

        $q3 = "SELECT * FROM cattle_breed WHERE id='$breed'";
        $r3 = mysqli_query($db, $q3);

        $re3 = mysqli_fetch_assoc($r3);

        $cbreed = $re3['breed'];

        echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$cname</td>
        <td>$cattleType</td>
        <td>$pdate</td>
        <td>$cbreed</td>
        <td>$age</td>
        <td><a href="cattle_activity_log.php?cid=$id">Add Cattle activity log</a> | <a href="cattle.php?id=$sn">Modify</a> | <a href="delete.php?table=cattle&return=cattle&rid=$sn">Delete</a></td>
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
