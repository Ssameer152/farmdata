<?php

include_once 'db.php';

if(isset($_SESSION['user'])){
    $uid = $_SESSION['user'];
    $q = "select * from user_permission where userid='$uid'";
    $r = mysqli_query($db,$q);
    
    $row = mysqli_num_rows($r);
    
    if($row>0){
        $res = mysqli_fetch_assoc($r);
        $master_data = $res['master_data'];
        $logs = $res['logs'];
        $reports = $res['reports'];
        $cattle = $res['cattle'];
        $customer = $res['customer'];
        $purchases = $res['purchases'];
        $transactions = $res['transactions'];
        $delivery = $res['delivery'];
        
    }
    else{
        $master_data = 0;
        $logs = 0;
        $reports = 0;
        $cattle = 0;
        $customer = 0;
        $purchases = 0;
        $transactions = 0;
        $delivery = 0;
    }
    
}
else{
    
    $master_data = 0;
    $logs = 0;
    $reports = 0;
    $cattle = 0;
    $customer = 0;
    $purchases = 0;
    $transactions = 0;
    $delivery = 0;
    
}

?>

    <nav class="navbar navbar-dark navbar-expand-sm fixed-top bg-success">
    	<div class="container">
    		<a class="navbar-brand" href="index.php">FarmDB</a>
    		<div class="collapse navbar-collapse" id="Navbar">
    			<ul class="navbar-nav mr-auto">
    				
                    
                    <?php
                    
                    if($master_data == 1){
                    echo <<<_END
                    <li class="nav-item dropdown">
    					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    						Master Data
    					</a>
    					<div class="dropdown-menu dropdown-menu-left animate slideIn" aria-labelledby="navbarDropdown">
    						<a class="dropdown-item" href="areas.php"> Areas</a>
    						<a class="dropdown-item" href="people.php"> People</a>
    						<a class="dropdown-item" href="assets.php"> Assets</a>
    						<a class="dropdown-item" href="resources.php"> Resources</a>
    						<a class="dropdown-item" href="activity.php"> Activities</a>
							<a class="dropdown-item" href="city.php">City</a>
    						<a class="dropdown-item" href="state.php">State</a>
    					</div>
    				</li>
_END;
                    }
?>
    				
                    <?php
                    
                    if($logs == 1)
                    {
                        echo <<<_END
					<li class="nav-item"><a class="nav-link" href="logs.php"> Logs</a></li> 
					<li class="nav-item"><a class="nav-link" href="log_resource_data.php">Common</a></li>                        
_END;
                    }

?>
                    
                    
                    
            <?php

                if($reports == 1)
                {
                    echo <<<_END
                    <li class="nav-item dropdown">
    					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    						Reports
    					</a>
    					<div class="dropdown-menu dropdown-menu-left animate slideIn" aria-labelledby="navbarDropdown">
    						<a class="dropdown-item" href="reports.php">Individual Report</a>
							<a class="dropdown-item" href="stock_master_report.php">Inventory Snapshot</a>
                            <a class="dropdown-item" href="stock_report.php">Inventory</a>
							<a class="dropdown-item" href="stock_report2.php">Inventory <sup>Beta</sup></a>
							<a class="dropdown-item" href="cattle_activity_report.php">Cattle Report</a>
							<a class="dropdown-item" href="cattle_activity_report2.php">Cattle Report 2</a>
    						<a class="dropdown-item" href="custom_report.php">Daily Activity Report</a>
							<a class="dropdown-item" href="custom_report2.php">Daily TotalWise Report</a>
							<a class="dropdown-item" href="delivery_report_price.php">Delivery Report in Excel</a>
							<a class="dropdown-item" href="customer_delivery_report.php">Customer Delivery Report</a>
							<a class="dropdown-item" href="customer_delivery_reportBeta.php">Customer Delivery Report<sup>Beta</sup></a>
							<a class="dropdown-item" href="stats.php">Stats</a>
    						<a class="dropdown-item" href="reports_v1.php">Report<sup>Beta</sup></a>
    					</div>
    				</li>
_END;
    }
?>
                    

<?php

        if($cattle == 1){
            echo <<<_END
                    <li class="nav-item dropdown">
    					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    						Cattle
    					</a>
    					<div class="dropdown-menu dropdown-menu-left animate slideIn" aria-labelledby="navbarDropdown">
    						<a class="dropdown-item" href="cattle.php">Add/View Cattle</a>
    						<a class="dropdown-item" href="cattle_activity.php">Cattle Activity</a>
    						<a class="dropdown-item" href="cattle_breed.php">Cattle Breed</a>
    						<a class="dropdown-item" href="cattle_type.php">Cattle Type</a>
							<a class="dropdown-item" href="cattle_activity_log_avg.php">Cattle Activity Log</a>
							<a class="dropdown-item" href="cattle_activity_report_avg.php">Cattle Activity average Report</a>
    					</div>
    				</li>
_END;
    }
?>               


<?php
        if($customer == 1){
            echo <<<_END
                    <li class="nav-item dropdown">
    					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    						Customer
    					</a>
    					<div class="dropdown-menu dropdown-menu-left animate slideIn" aria-labelledby="navbarDropdown">
    						<a class="dropdown-item" href="customer.php">Add Customer</a>
							<a class="dropdown-item" href="customer_subscription_report.php">Customer Subscription Report</a>
							<a class="dropdown-item" href="customer_delivery_preview.php">Customer Delivery Preview</a>
							<a class="dropdown-item" href="customer_stmt.php">Customer Statement</a>
							<a class="dropdown-item" href="customer_summary.php">Customer Summary</a>
							<a class="dropdown-item" href="customer_total.php">Customers Total</a>
							<a class="dropdown-item" href="ledger_account.php">Ledger account</a>
    					</div>
    				</li>
_END;
        }
?>    
                    
<?php

        if($purchases == 1){
            echo <<<_END
                    <li class="nav-item dropdown">
    					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Purchases</a>
    					<div class="dropdown-menu dropdown-menu-left animate slideIn" aria-labelledby="navbarDropdown2">
    						<a class="dropdown-item" href="purchases.php">Add Purchase</a>
    						<a class="dropdown-item" href="purchases.php">View Purchases</a>
    						<a class="dropdown-item" href="vendor.php">Add Vendor</a>
    						<a class="dropdown-item" href="vendor.php">View Vendors</a>
    					</div>
    				</li>
_END;
        }		
?>                


<?php

if($transactions == 1){
    echo <<<_END
                    <li class="nav-item dropdown">
    					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    						Transactions
    					</a>
    					<div class="dropdown-menu dropdown-menu-left animate slideIn" aria-labelledby="navbarDropdown">
    						<a class="dropdown-item" href="transactions.php">Add transaction</a>
    						<a class="dropdown-item" href="transaction_account.php">Transaction Account</a>
							<a class="dropdown-item" href="transaction_category.php">Transaction Category</a>
							<a class="dropdown-item" href="transaction_report.php">Transaction Report</a>
							<a class="dropdown-item" href="trial_balance.php">Trial Balance</a>
    					</div>
    				</li>
_END;
}
?>
                    
        <?php
        
        if($delivery == 1){
			echo <<<_END
			<li class="nav-item dropdown">
    					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    						Deliveries
    					</a>
    					<div class="dropdown-menu dropdown-menu-left animate slideIn" aria-labelledby="navbarDropdown">
    						<a class="dropdown-item" href="customer_delivery_log.php">Morning Delivery</a>
    						<a class="dropdown-item" href="customer_delivery_log2.php">Evening Delivery</a>
							<a class="dropdown-item" href="customer_delivery_reportBeta.php">Delivery Report</a>
    					</div>
    				</li>
                   
_END;
        }	
?>    
                
                
                </ul>


    			<span class="navbar-text">
    				<a href="logout.php">
    					<span class="fa fa-sign-in"></span> Logout
    				</a>
    			</span>


    		</div>
    	</div>
    	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Navbar"><span class="navbar-toggler-icon"></span></button>
    </nav>

    <header class="jumbotron"></header>