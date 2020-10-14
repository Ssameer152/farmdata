<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_GET['aid']) && $_GET['aid']!=''){
        $mid = $_GET['aid'];
        $q="SELECT * from assets where id='$mid' and is_deleted=0";
        $r=mysqli_query($db,$q);
        $res=mysqli_fetch_assoc($r);
        $db_asset=$res['assetname'];
    }
    else{
        $db_asset='';
    }
    echo <<<_END
<html>
    <head>
        <title>FarmDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://use.fontawesome.com/d1f7bf0fea.js"></script>
    </head>
    
    <body>    
_END;

include_once 'nav.php';

echo <<<_END

        <div class="container">
_END;
if(isset($_GET['msg']) && $_GET['msg']!=''){
    $msg = $_GET['msg'];
    echo<<<_END
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
                <div class="col-lg-6">
                    <h2>Assets</h2>
                    <form action="assets_add.php" method="post">
                        <div class="form-group">
                            <label for="assetname">Title</label>
_END;
                        if($db_asset==''){
                            echo <<<_END
                            <input type="text" name="asset" class="form-control">
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="text" value="$db_asset" name="asset" class="form-control">
_END;
                        }
                        echo <<<_END
                        </div>
_END;
                    if(isset($mid)){
                    echo <<<_END
                    <input type="hidden" name="mid" value="$mid">
_END;
                    }
                    echo <<<_END
						<button type="submit" class="btn btn-primary">Add Asset</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

$q = "SELECT * FROM assets WHERE is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $name = $res['assetname'];
    
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$name</td>
        <td><a href="assets.php?table=assets&return=assets&aid=$sn">Modify</a> | <a href="delete.php?table=assets&rid=$sn&return=assets"><span class="fa fa-trash fa-lg"></a></td>
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

}
else
{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>	
