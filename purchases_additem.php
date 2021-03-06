<?php
session_start();

if(isset($_SESSION['user']))
{
    include_once 'db.php';


if(isset($_GET['pid']) && $_GET['pid']!='')
{    
    $pid = $_GET['pid'];
    
    
    if(isset($_GET['id']) && $_GET['id']!=''){
        $mid = $_GET['id'];
        $q = "select * from purchase_items WHERE id='$mid'";
        $r = mysqli_query($db,$q);
        
        $res = mysqli_fetch_assoc($r);
        
        $db_qty = $res['qty'];
        $db_resource = $res['resourceid'];
        $db_cpu=$res['costperunit'];
        
    }
    else
    {
        $db_resource = '';
        $db_qty = '';
        $db_cpu='';
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
                    <h2>Purchase #$pid</h2>
_END;


    echo <<<_END
                        <form action="purchases_additempro.php" method="post">
                        <div class="form-group">
                            <label for="area">Resource</label>
                            <select name="resource" class="form-control">
                                <option value="">--Select Resource--</option>
_END;

$q = "SELECT * FROM resources WHERE is_deleted=0 order by resourcename asc";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sid = $res['id'];
    $rname = $res['resourcename'];
    $unit = $res['unit'];
    
    if($db_resource == $sid){
        echo <<<_END
    <option value="$sid" selected="selected">$rname ($unit)</option>
_END;
    }
    else{
    echo <<<_END
    <option value="$sid">$rname ($unit)</option>
_END;
}

}

echo <<<_END
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
_END;
                        if($db_qty==''){
                            echo <<<_END
                            <input type="text" name="qty" class="form-control">
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="text" name="qty" value="$db_qty" class="form-control">
_END;
                        }
                        echo <<<_END
                        </div>
                        <div class="form-group">
                            <label for="quantity">Cost Per Unit</label>
_END;
                        if($db_cpu==''){
                            echo <<<_END
                            <input type="text" name="cpu" class="form-control">
_END;
                        }
                        else {
                            echo <<<_END
                            <input type="text" name="cpu" value="$db_cpu" class="form-control">
_END;
                        }
                        echo <<<_END
                        </div>
                        <input type="hidden" name="pid" value="$pid">
_END;

if(isset($mid)){
    echo <<<_END
    <input type="hidden" name="mid" value="$mid">
_END;
}

echo <<<_END
						<button type="submit" class="btn btn-primary">Add</button>
                    </form>
_END;


echo <<<_END
                </div>
<div class="col-lg-12">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Resource</th>
                    <th>Quantity</th>
                    <th>Cost Per Unit</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
_END;

$q = "SELECT * FROM purchase_items WHERE pid='$pid' AND is_deleted=0";
$r = mysqli_query($db,$q);
$sn = 0;
$st1=0;
while($res = mysqli_fetch_assoc($r))
{
    $pid=$res['pid'];
    $id=$res['id'];
    $qty = $res['qty'];
    $rid = $res['resourceid'];
    $cpu = $res['costperunit'];
    
    $q2 = "SELECT resourcename,unit FROM resources WHERE id='$rid'";
    $r2 = mysqli_query($db,$q2);
    
    $re2 = mysqli_fetch_assoc($r2);
    
    $unit = $re2['unit'];
    $resourcename  = $re2['resourcename'];
    $sn = $sn + 1;
    $st = $qty * $cpu;
    $st1+=$st;

    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$resourcename</td>
        <td>$qty $unit</td>
        <td>&#8377; $cpu</td>
        <td>&#8377; $st</td>
        <td><a href="purchases_additem.php?table=purchase_items&return=purchases_additem&pid=$pid&id=$id">Modify</a> | <a href="delete.php?table=purchase_items&return=purchases_additem&rid=$id&pid=$pid">Delete</a></td>
       
    </tr>
_END;
}  
    echo <<<_END
    <tr>
    <td colspan="5" class="text-right">$st1</td>
    <td></td>
    </tr>
_END;
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
    $msg = "Please select a work log";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=purchases_additem.php?msg=$msg'>
_END;
}


}
else
{
    $msg = "Please Login";
    echo <<<_END
    <meta http-equiv='refresh' content='0;url=index.php?msg=$msg'>
_END;
}

?>	
