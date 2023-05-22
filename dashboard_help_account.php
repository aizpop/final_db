<?php
    // Start session
    session_start();

    // Establish database connection
   include 'config.php';	

    // Retrieve data from the database for the currently logged in user
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
    }
	

    // Check if query is successful
    if ($result) {
        // Fetch data
        $row = mysqli_fetch_assoc($result);
    }

	// If the form has been submitted
 // If the form has been submitted
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Validate input data and update database
	$id = $_POST['user_id'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$dob = $_POST['dob'];
	$gender = $_POST['gender'];
	$address = $_POST['address'];
	$pnumber = $_POST['phone'];
	$type = $_POST['type'];
	$profile_picture = $_POST['profile_picture'];

	// Validate input data
	// ...

	// Update database
	$sql = "UPDATE users SET fname='$fname', lname='$lname', dob='$dob', gender='$gender', address='$address', pnumber='$pnumber', usertype='$type', profile_picture='$profile_picture' WHERE id='$id'";
	if (mysqli_query($conn, $sql)) {
		// Display success message
		$_SESSION['success_message'] = "User information updated successfully";
		// Redirect to user_profile.php to refresh the page
		header("Location: dashboard_help_account.php");
		exit();
	} else {
		// Display error message
		echo "Error updating user information: " . mysqli_error($conn);
	}
}

// Close database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Account Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
		@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Poppins', sans-serif;
		}

		.sidebar {
			position: fixed;
			height: 100%;
			width: 240px;
			background: #F09E00;
			transition: all 0.5s ease;
		}

		.sidebar.active {
			width: 60px;
		}

		.sidebar .nav-links {
			margin-top: 10px;
		}

		.sidebar .nav-links li {
			position: relative;
			list-style: none;
			height: 50px;
		}

		.sidebar .nav-links li a {
			height: 100%;
			width: 100%;
			display: flex;
			align-items: center;
			text-decoration: none;
			transition: all 0.4s ease;
		}

		.sidebar .nav-links li a.active {
			background: #d9d9d9;
			color: #000000;
		}

		.sidebar .nav-links li a:hover {
			background: #FFFFFF;
		}

		.sidebar .nav-links li i {
			min-width: 60px;
			text-align: center;
			font-size: 18px;
			color: #fff;
		}

		.sidebar .nav-links li a .links_name {
			color: #fff;
			font-size: 15px;
			font-weight: 400;
			white-space: nowrap;
		}

		.sidebar .nav-links .log_out {
			position: absolute;
			bottom: 0;
			width: 100%;
		}

		.home-section {
			position: relative;
			background: #f5f5f5;
			min-height: 100vh;
			width: calc(100% - 240px);
			left: 240px;
			transition: all 0.5s ease;
		}

		.sidebar.active~.home-section {
			width: calc(100% - 60px);
			left: 60px;
		}

		.home-section nav {
			display: flex;
			justify-content: space-between;
			height: 80px;
			background: #fff;
			display: flex;
			align-items: center;
			position: fixed;
			width: calc(100% - 240px);
			left: 240px;
			z-index: 100;
			padding: 0 20px;
			box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
			transition: all 0.5s ease;
		}

		.sidebar.active~.home-section nav {
			left: 60px;
			width: calc(100% - 60px);
		}

		.home-section nav .sidebar-button {
			display: flex;
			align-items: center;
			font-size: 24px;
			font-weight: 500;
		}

		nav .sidebar-button i {
			font-size: 35px;
			margin-right: 10px;
		}

		.home-section nav .search-box {
			position: relative;
			height: 50px;
			max-width: 550px;
			width: 100%;
			margin: 0 20px;
		}

		nav .search-box input {
			height: 100%;
			width: 100%;
			outline: none;
			background: #F5F6FA;
			border: 2px solid #EFEEF1;
			border-radius: 6px;
			font-size: 18px;
			padding: 0 15px;
		}

		nav .search-box .bx-search {
			position: absolute;
			height: 40px;
			width: 40px;
			background: #2697FF;
			right: 5px;
			top: 50%;
			transform: translateY(-50%);
			border-radius: 4px;
			line-height: 40px;
			text-align: center;
			color: #fff;
			font-size: 22px;
			transition: all 0.4 ease;
		}

		.home-section nav .profile-details {
			display: flex;
			align-items: center;
			background: #F5F6FA;
			border: 2px solid #EFEEF1;
			border-radius: 6px;
			height: 50px;
			min-width: 190px;
			padding: 0 15px 0 2px;
		}

		.home-section .home-content {
			position: relative;
			padding-top: 104px;
		}

		.home-content .overview-boxes {
			display: flex;
			align-items: center;
			justify-content: space-between;
			flex-wrap: wrap;
			padding: 0 20px;
			margin-bottom: 26px;
		}

		.overview-boxes .box {
			display: flex;
			align-items: center;
			justify-content: center;
			width: calc(100% / 4 - 15px);
			background: #fff;
			padding: 15px 14px;
			border-radius: 12px;
			box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
		}

		.overview-boxes .box-topic {
			font-size: 20px;
			font-weight: 500;
		}

		.home-content .box .number {
			display: inline-block;
			font-size: 35px;
			margin-top: -6px;
			font-weight: 500;
		}

		.home-content .box .indicator {
			display: flex;
			align-items: center;
		}

		.home-content .box .indicator i {
			height: 20px;
			width: 20px;
			background: #8FDACB;
			line-height: 20px;
			text-align: center;
			border-radius: 50%;
			color: #fff;
			font-size: 20px;
			margin-right: 5px;
		}

		.box .indicator i.down {
			background: #e87d88;
		}

		.home-content .box .indicator .text {
			font-size: 12px;
		}

		.home-content .box .cart {
			display: inline-block;
			font-size: 32px;
			height: 50px;
			width: 50px;
			background: #cce5ff;
			line-height: 50px;
			text-align: center;
			color: #66b0ff;
			border-radius: 12px;
			margin: -15px 0 0 6px;
		}

		.home-content .box .cart.two {
			color: #2BD47D;
			background: #C0F2D8;
		}

		.home-content .box .cart.three {
			color: #ffc233;
			background: #ffe8b3;
		}

		.home-content .box .cart.four {
			color: #e05260;
			background: #f7d4d7;
		}

		.home-content .total-order {
			font-size: 20px;
			font-weight: 500;
		}

		.home-content .sales-boxes {
			display: flex;
			justify-content: space-between;
			/* padding: 0 20px; */
		}

		/* left box */
		.home-content .sales-boxes .recent-sales {
			width: 100%;
			background: #fff;
			padding: 20px 30px;
			margin: 0 20px;
			border-radius: 12px;
			box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
		}

		.home-content .sales-boxes .sales-details {
			display: flex;
			align-items: center;
			justify-content: space-between;
		}

		.sales-boxes .box .title {
			font-size: 24px;
			font-weight: 500;
			/* margin-bottom: 10px; */
		}

		.sales-boxes .sales-details li.topic {
			font-size: 20px;
			font-weight: 500;
		}

		.sales-boxes .sales-details li {
			list-style: none;
			margin: 8px 0;
		}

		.sales-boxes .sales-details li a {
			font-size: 18px;
			color: #333;
			font-size: 400;
			text-decoration: none;
		}

		.sales-boxes .box .button {
			width: 100%;
			display: flex;
			justify-content: flex-end;
		}

		.sales-boxes .box .button a {
			color: #fff;
			background: #0A2558;
			padding: 4px 12px;
			font-size: 15px;
			font-weight: 400;
			border-radius: 4px;
			text-decoration: none;
			transition: all 0.3s ease;
		}

		.sales-boxes .box .button a:hover {
			background: #0d3073;
		}
		.sales-boxes .recent-sales .sales-details{
			margin-top:15px;
		}
		.sales-boxes .recent-sales img{
			width:80px;
			height:80px;
			padding: 10px;
		}
		.sales-boxes .recent-sales .name{
			word-wrap: break-word; 

		}
		.sales-boxes .recent-sales i{
			margin-top: 90px;
		}

		/* Responsive Media Query */
		@media (max-width: 1240px) {
		.sidebar {
			width: 60px;
		}

		.sidebar.active {
			width: 220px;
		}

		.home-section {
			width: calc(100% - 60px);
			left: 60px;
		}

		.sidebar.active~.home-section {
		  /* width: calc(100% - 220px); */
		  overflow: hidden;
		  left: 220px;
		}

		.home-section nav {
		  width: calc(100% - 60px);
		  left: 60px;
		}

		.sidebar.active~.home-section nav {
		  width: calc(100% - 220px);
		  left: 220px;
		}
		}

		@media (max-width: 1150px) {
		.home-content .sales-boxes {
		  flex-direction: column;
		}

		.home-content .sales-boxes .box {
		  width: 100%;
		  overflow-x: scroll;
		  margin-bottom: 30px;
		}

		.home-content .sales-boxes .top-sales {
		  margin: 0;
		}
		}

		@media (max-width: 1000px) {
		.overview-boxes .box {
		  width: calc(100% / 2 - 15px);
		  margin-bottom: 15px;
		}
		}

		@media (max-width: 700px) {

		nav .sidebar-button .dashboard,
		nav .profile-details .admin_name,
		nav .profile-details i {
		  display: none;
		}

		.home-section nav .profile-details {
		  height: 50px;
		  min-width: 40px;
		}

		.home-content .sales-boxes .sales-details {
		  width: 560px;
		}
		}

		@media (max-width: 550px) {
		.overview-boxes .box {
		  width: 100%;
		  margin-bottom: 15px;
		}

		.sidebar.active~.home-section nav .profile-details {
		  display: none;
		}
		}

		@media (max-width: 400px) {
		.sidebar {
		  width: 0;
		}

		.sidebar.active {
		  width: 60px;
		}

		.home-section {
		  width: 100%;
		  left: 0;
		}

		.sidebar.active~.home-section {
		  left: 60px;
		  width: calc(100% - 60px);
		}

		.home-section nav {
		  width: 100%;
		  left: 0;
		}

		.sidebar.active~.home-section nav {
		  left: 60px;
		  width: calc(100% - 60px);
		}
		}
    </style>
  </head>
  
  <body>
  
    <!---Sidebar-->
    <div class="sidebar">
      <div class="d-flex flex-column align-items-center text-center mt-5 ">
	  <div class="d-flex flex-column align-items-center text-center">
									<img src="images/<?php echo $row['profile_picture']; ?>" alt="Profile Picture" class="rounded-circle" style="width: 150px; height: 150px;">
								</div>
        <span class="logo_name text-white mt-3"><?php echo $row['fname'] . ' ' . $row['lname']; ?></span>
      </div>
      <ul class="nav-links">
	 	 <li>
			<a href="seller_product.php" class="active">
			  <i class='bx bx-grid-alt'></i>
			  <span class="links_name">Product</span>
			</a>
		  </li>
       
        <li>
          <a href="dashboard_order_product.php" class="active">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">Orders</span>
          </a>
        </li>
        <li>
          <a href="dashboard_pastreview_product.php">
            <i class='bx bx-list-ul'></i>
            <span class="links_name">Past Reviews</span>
          </a>
        </li>
        <li>
          <a href="dashboard_orderhistory_product.php">
            <i class='bx bx-coin-stack'></i>
            <span class="links_name">Order History</span>
          </a>
        </li>
		<li>
          <a href="#">
            <i class='bx bx-coin-stack'></i>
            <span class="links_name">Settings</span>
          </a>
        </li>
        <li class="log_out">
          <a href="logout.php">
            <i class='bx bx-log-out'></i>
            <span class="links_name">Logout</span>
          </a>
        </li>
      </ul>
    </div>
    <section class="home-section">
      <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
					
                    
					
                </div>
            </nav>

        </header>
        <!-- Order Nav -->
      <div class="home-content">
        <ul class="nav nav-tabs px-5">
          <li class="active">
            <a data-toggle="tab" href="#home">Account</a>
          </li>
          <li class="px-5">
            <a data-toggle="tab" href="#menu1">Security</a>
          </li>
         
          <li class="px-5">
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit"><i class="fa-sharp fa-solid fa-magnifying-glass fa-sm" style="color: #000000;"></i></button>
            </form> 
          </li>
        </ul>
        
        <div class="tab-content">
        <!-- account -->
            <div id="home" class="tab-pane fade in active">
                <div class="sales-boxes py-5 border-top ">
                    <div class="recent-sales box ">
                        <div class="container">
                            <div class="main-body">
                                <div class="row gutters-sm">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5>User's Account</h5>
												<div class="d-flex flex-column align-items-center text-center">
													<img src="images/<?php echo $row['profile_picture']; ?>" alt="Profile Picture" class="rounded-circle" style="width: 250px; height: 250px;">
												</div>
                                            </div>
                                        </div>
                                        <div class="card mb-auto rounded-top bg-secondary">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                    <h4 class="mb-auto text-white text" style="text-align:center; "><?php // Check if $row variable is defined and not null
													if (isset($row) && is_array($row)) {
														// Access array offset only if $row is an array and not null
														echo $row['fname'] . ' ' . $row['lname'];
													} else {
														// Handle case where $row is not defined or null
														echo 'Error: $row is not defined or null';
													}
													?></h4>
                                                </div>
                                                <div class="col-sm-6 ml-auto text-white">                                          
                                                    
                                                    &nbsp;
                                                    <a href ="">
                                                        <i class="bi bi-pen text-white"></i>
                                                    </a> 
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>    
                                </div>
                                <div class="col-md-8">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <h6 class="mb-auto">First Name</h6>
                                                </div>
                                                <div class="col-sm-6 ml-auto text-secondary">
                                                <?php echo $row['fname']; ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-auto">Last Name</h6>
                                                </div>
                                                <div class="col-sm-6 ml-auto text-secondary">
                                                <?php echo $row['lname']; ?>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <h6 class="mb-0">Date of Birth</h6>
                                                </div>
                                                <div class="col-sm-6 ml-auto text-secondary">
                                                <?php echo $row['dob']; ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <h6 class="mb-0">Gender</h6>
                                                </div>
                                                <div class="col-sm-6 ml-auto text-secondary">
                                                <?php echo $row['gender']; ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <h6 class="mb-0">Address</h6>
                                                </div>
                                                <div class="col-sm-6 ml-auto text-secondary">
                                                <?php echo $row['address']; ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <h6 class="mb-0">Phone Number</h6>
                                                </div>
                                                <div class="col-sm-6 ml-auto text-secondary">
                                                <?php echo $row['pnumber']; ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <h6 class="mb-0">Type</h6>
                                                </div>
                                                <div class="col-sm-6 ml-auto text-secondary">
                                                <?php echo $row['usertype']; ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="btn-toolbar row">
                                                <div class="col-sm-6 ml-auto">
												<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal"><i class="bi bi-pen"></i> Edit</button>
												<!-- Edit User Modal -->
												<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-dialog-centered" role="document">
													<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="editModalLabel">Edit User Information</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
													<form action="dashboard_help_account.php" method="POST">
														<input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
														<img id="preview" src="images/<?php echo $product['profile_picture']; ?>" alt="Profile Picture" style="width: 60%; height: 60%; margin:auto; padding:15px;">
														<div class="mb-3">
															<label for="profile_picture" class="form-label">Upload Profile Picture</label>
															<input type="file" class="form-control-file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(event)">
														</div>

														<script>
															function previewImage(event) {
																var reader = new FileReader();
																reader.onload = function() {
																	var output = document.getElementById('preview');
																	output.src = reader.result;
																};
																reader.readAsDataURL(event.target.files[0]);
															}
														</script>
														<div class="form-group">
															<label for="fname">First Name:</label>
															<input type="text" class="form-control" id="fname" name="fname" value="<?php echo $row['fname']; ?>">
														</div>
														<div class="form-group">
															<label for="lname">Last Name:</label>
															<input type="text" class="form-control" id="lname" name="lname" value="<?php echo $row['lname']; ?>">
														</div>
														<div class="form-group">
															<label for="dob">Date of Birth:</label>
															<input type="date" class="form-control" id="dob" name="dob" value="<?php echo $row['dob']; ?>">
														</div>
														<div class="form-group">
															<label for="gender">Gender:</label>
															<select class="form-control" id="gender" name="gender">
															<option value="Male" <?php if ($row['gender'] == 'male') echo 'selected'; ?>>Male</option>
															<option value="Female" <?php if ($row['gender'] == 'female') echo 'selected'; ?>>Female</option>
															<option value="Other" <?php if ($row['gender'] == 'other') echo 'selected'; ?>>Other</option>
															</select>
														</div>
														<div class="form-group">
															<label for="address">Address:</label>
															<textarea class="form-control" id="address" name="address"><?php echo $row['address']; ?></textarea>
														</div>
														<div class="form-group">
															<label for="phone">Phone Number:</label>
															<input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row['pnumber']; ?>">
														</div>
														<div class="form-group">
															<label for="phone">Type:</label>
															<input type="text" class="form-control" id="phone" name="type" value="<?php echo $row['usertype']; ?>" readonly>
														</div>
													
														<div class="form-group">
															<button type="submit" class="btn btn-primary" name="edit_user">Save Changes</button>
																														
																	
																	<?php
																	// Process your request here

																	// Refresh the page after processing the request
																	echo '<script>refreshPage();</script>';
																	?>
																
														</div>
														</form>
													</div>
													</div>
												</div>
												</div>		
                                                </div>                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
        <div id="menu1" class="tab-pane fade ">
            <div id="home" class="tab-pane fade in active">
                <div class="sales-boxes py-5 border-top ">
                    <div class="recent-sales box ">
                        <div class="container">
                            <div class="main-body">
                                <div class="row gutters-sm">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5>User's Account</h5>
                                                <div class="d-flex flex-column align-items-center text-center">
                                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150" style="width: 200px; height: 200px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-auto rounded-top bg-secondary">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                    <h4 class="mb-auto text-white">Username</h4>
                                                </div>
                                                <div class="col-sm-6 ml-auto text-white">
												<?php echo $row['fname'] . ' ' . $row['lname']; ?>
                                                    &nbsp;
                                                    <a href ="">
                                                        <i class="bi bi-pen text-white"></i>
                                                    </a> 
                                                </div>
                                            </div>

                                            
                                        </div>
                                    </div>    
                                </div>
                                <div class="col-md-8">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <h6 class="mb-auto">Phone Number</h6>
                                                </div>
                                                <div class="col-sm-6 ml-auto text-secondary">
												<?php echo $row['pnumber']; ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-auto">Password</h6>
                                                </div>
                                                <div class="col-sm-6 ml-auto text-secondary">
												<?php echo $row['password']; ?>
                                                </div>
                                            </div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </div>                       
                </div>
            </div>
        </div>
    </section>
    <script>
      let sidebar = document.querySelector(".sidebar");
      let sidebarBtn = document.querySelector(".sidebarBtn");
      sidebarBtn.onclick = function() {
        sidebar.classList.toggle("active");
        if (sidebar.classList.contains("active")) {
          sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
      }
	
    </script>
	
  </body>
</html>