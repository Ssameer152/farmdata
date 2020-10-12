<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_GET['tid']) && $_GET['tid']!=''){
        $mid = $_GET['tid'];
        $q="SELECT * from transactions_category where id='$mid' and is_deleted=0";
        $r=mysqli_query($db,$q);
        $res=mysqli_fetch_assoc($r);
        $db_trcategory=$res['category'];

    }
    else{
        $db_trcategory='';
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
            <div class="row">
                <div class="col-lg-6">
                    <h2>Transaction Category</h2>
                    <form action="transaction_category_add.php" method="post">
                        <div class="form-group">
                            <label for="account">Category</label>
_END;
                        if($db_trcategory==''){
                            echo <<<_END
                            <input type="text" name="t_category" class="form-control">
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="text" value="$db_trcategory" name="t_category" class="form-control">
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
						<button type="submit" class="btn btn-primary">Add Category</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Transaction Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

$q = "SELECT * FROM transactions_category WHERE is_deleted=0";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $category= $res['category'];
    
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$category</td>
        <td><a href="transaction_category.php?table=transactions_category&return=transaction_category&tid=$sn">Modify</a> | <a href="delete.php?table=transactions_category&rid=$sn&return=transaction_category">Delete</a></td>
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
