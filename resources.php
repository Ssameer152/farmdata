<?php
session_start();

if (isset($_SESSION['user'])) {
    include_once 'db.php';
    if (isset($_GET['rid']) && $_GET['rid'] != '') {
        $mid = $_GET['rid'];
        $q = "SELECT * from resources where id='$mid' and is_deleted=0";
        $r = mysqli_query($db, $q);
        $res = mysqli_fetch_assoc($r);
        $db_rname = $res['resourcename'];
        $db_unit = $res['unit'];
    } else {
        $db_rname = '';
        $db_unit = '';
    }
    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css"/>
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
                    <h2>Resources</h2>
                    <form action="resources_add.php" method="post">
                        <div class="form-group">
                            <label for="resourcename">Title</label>
_END;
    if ($db_rname == '') {
        echo <<<_END
                            <input type="text" name="resource" class="form-control">
_END;
    } else {
        echo <<<_END
                            <input type="text" name="resource" value="$db_rname" class="form-control">
_END;
    }
    echo <<<_END
                        </div>
                        <div class="form-group">
                            <label for="unit">Measuring Unit</label>
_END;
    if ($db_unit == '') {
        echo <<<_END
                            <input type="text" name="unit" class="form-control" placeholder="kg/packet/piece/litre"> 
_END;
    } else {
        echo <<<_END
                            <input type="text" name="unit" class="form-control" value="$db_unit" placeholder="kg/packet/piece/litre">
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
						<button type="submit" class="btn btn-primary">Add Resource</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table id="table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Unit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

    $q = "SELECT * FROM resources WHERE is_deleted=0 order by resourcename asc";
    $r = mysqli_query($db, $q);

    while ($res = mysqli_fetch_assoc($r)) {
        $sn = $res['id'];
        $name = $res['resourcename'];
        $unit = $res['unit'];
        echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$name</td>
        <td>$unit</td>
        <td><a href="resources.php?table=resources&return=resources&rid=$sn">Modify</a> | <a href="delete.php?table=resources&rid=$sn&return=resources"><span class="fa fa-trash fa-lg"></a></td>
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
} 
else {
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}
