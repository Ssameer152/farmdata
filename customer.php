<?php
session_start();
function getDimensionValue($db,$table,$gid,$name){
    $q = "SELECT * FROM $table WHERE id=$gid";
    $r = mysqli_query($db,$q);
    $res = mysqli_fetch_assoc($r);
    $value = $res[$name];
    return $value;
}
if(isset($_SESSION['user']))
{
    include_once 'db.php';
    if(isset($_GET['id']) && $_GET['id']!=''){
        $mid = $_GET['id'];
        $q = "SELECT * from customer WHERE id='$mid' and is_deleted=0";
        $r = mysqli_query($db,$q);
        $res = mysqli_fetch_assoc($r);

        $db_fname = $res['fname'];
        $db_lname = $res['lname'];
        $db_email = $res['email'];
        $db_phone = $res['phone'];
        $db_zipcode=$res['zipcode'];
        $db_city=$res['city'];
        $db_state=$res['state'];
        $db_address=$res['address'];
        
    }
    else
    {
        $db_fname = '';
        $db_lname = '';
        $db_email = '';
        $db_phone='';
        $db_zipcode= '';
        $db_city='';
        $db_state='';
        $db_address='';
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
                <div class="col-lg-12">
                    <h2>Enter Customer Details</h2>
                    <form action="customer_add.php" method="post">
                    <div class="form-row">
                    <div class="form-group col-lg-6">
                            <label for="fname">First Name</label>
_END;
                        if($db_fname==''){
                            echo <<<_END
                            <input type="text" name="fname" class="form-control">
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="text" name="fname" value="$db_fname" class="form-control">
_END;
                        }
                            echo <<<_END
                        </div>
                        <div class="form-group col-lg-6">
                        <label for="lname">Last Name(Optional)</label>
_END;
                        if($db_lname==''){
                            echo <<<_END
                        <input type="text" name="lname" class="form-control">
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="text" name="lname" value="$db_lname" class="form-control">
_END;
                        }
                            echo <<<_END
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-lg-6">
                            <label for="email">Email(Optional)</label>
_END;
                        if($db_email==''){
                            echo <<<_END
                            <input type="text" name="email" class="form-control">
_END;
                        }
                        else {
                            echo <<<_END
                            <input type="text" name="email" value="$db_email" class="form-control">
_END;
                        }
                            echo <<<_END
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="phone">Phone</label>
_END;
                        if($db_phone==''){
                            echo <<<_END
                            <input type="text" name="phone" class="form-control">
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="text" name="phone" value="$db_phone" class="form-control">

_END;
                        }
                            echo <<<_END
                        </div>
                        </div>
                        <div class="form-row">
                        <div class="form-group col-lg-6">
                        <label for="zipcode">ZipCode(Optional)</label>
_END;
                        if($db_zipcode==''){
                            echo <<<_END
                        <input type="text" name="zipcode" class="form-control">
_END;
                        }
                        else{
                            echo <<<_END
                            <input type="text" name="zipcode" value="$db_zipcode" class="form-control">
_END;
                        }
                            echo <<<_END
                    </div>
                    <div class="form-group col-lg-6">
                            <label for="city">City</label>
                            <select name="city"  class="form-control">
                            <option value="">--Select City--</option>
_END;
                        $q="SELECT * from city where is_deleted=0";
                        $r=mysqli_query($db,$q);
                        while($res=mysqli_fetch_assoc($r)){
                            $id=$res['id'];
                            $name=$res['name'];
                            if($id==$db_city){
                            echo <<<_END
                            <option value="$id" selected="selected">$name</option>
_END;
                        }
                        else {
                            echo <<<_END
                            <option value="$id">$name</option>
_END;
                        }
                    }
                        echo <<<_END
                        </select>
                        </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-lg-6">
                            <label for="state">State(Optional)</label>
                            <select name="state"  class="form-control">
                            <option value="">--Select State--</option>
_END;
                        $q="SELECT * from state where is_deleted=0";
                        $r=mysqli_query($db,$q);
                        while($res=mysqli_fetch_assoc($r)){
                            $id=$res['id'];
                            $name=$res['name'];
                            if($id==$db_state){
                            echo <<<_END
                            <option value="$id" selected="selected">$name</option>
_END;
                        }
                        else{
                            echo <<<_END
                            <option value="$id">$name</option>
_END;
                        }
                    }
                        echo <<<_END
                        </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="address">Address</label>
_END;
                            if($db_address==''){
                        echo <<<_END
                            <input type="text" name="address" class="form-control">
_END;
                            }
                            else{
                                echo <<<_END
                                <input type="text" name="address" value="$db_address" class="form-control">
_END;
                            }
                            echo <<<_END
                        </div>
                        </div>
_END;
                        if(isset($mid)){
                            echo <<<_END
                            <input type="hidden" name="mid" value="$mid">
_END;
                        }
                        echo <<<_END
						<button type="submit" class="btn btn-primary">Add Customer</button>
                    </form>
                </div>
                </div>
                <div class="col-lg-12">
                <h4 class="text-center mb-4">Customer Details</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>firstname</th>
                                    <th>lastname</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>City</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
_END;

$q = "SELECT * FROM customer WHERE is_deleted=0 order by fname asc";
$r = mysqli_query($db,$q);

while($res = mysqli_fetch_assoc($r))
{
    $sn = $res['id'];
    $fname = $res['fname'];
    $lname = $res['lname'];
    $email = $res['email'];
    $phone = $res['phone'];
    $city=getDimensionValue($db,'city',$res['city'],'name');
    $address=$res['address'];
    echo <<<_END
    <tr>
        <td>$sn</td>
        <td>$fname</td>
        <td>$lname</td>
        <td>$email</td>
        <td>$phone</td>
        <td>$city</td>
        <td>$address</td>
        <td><a href="customer.php?id=$sn">Modify</a> | <a href="delete.php?table=customer&rid=$sn&return=customer">Delete</a> | <a href="customer_subscription.php?cid=$sn">Subscribe</a></td>
    </tr>
_END;
}

echo <<<_END
                            </tbody>
                        </table>
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
