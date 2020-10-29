<?php
session_start();

if (isset($_SESSION['user'])) {
    include_once 'db.php';
    if (isset($_GET['cid']) && $_GET['cid'] != '') {
        $mid = $_GET['cid'];
        $q = "SELECT * from cattle_breed where id='$mid' and is_deleted=0";
        $r = mysqli_query($db, $q);
        $res = mysqli_fetch_assoc($r);
        $db_breed = $res['breed'];
    } else {
        $db_breed = '';
    }
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
                    <h2>Cattle Breed</h2>
                    <form action="cattle_breed_add.php" method="post">
                        <div class="form-group">
                            <label for="cattlebreed">Breed</label>
_END;
    if ($db_breed == '') {
        echo <<<_END
                            <input type="text" name="breed" class="form-control">
_END;
    } else {
        echo <<<_END
                            <input type="text" value="$db_breed" name="breed" class="form-control">
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
						<button type="submit" class="btn btn-primary">Add Cattle Breed</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Breed</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

    $q = "SELECT * FROM cattle_breed where is_deleted=0";
    $r = mysqli_query($db, $q);

    while ($res = mysqli_fetch_assoc($r)) {
        $sn = $res['id'];
        $name = $res['breed'];

        echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$name</td>
        <td><a href="cattle_breed.php?table=cattle_breed&return=cattle_breed&cid=$sn">Modify</a> | <a href="delete.php?table=cattle_breed&rid=$sn&return=cattle_breed">Delete</a></td>
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
