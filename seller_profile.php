<?php
// Start session
session_start();

// Establish database connection
include 'config.php';

// Retrieve data from the database for the currently logged in user
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");

    // Check if query is successful
    if ($result) {
        // Fetch data
        $row = mysqli_fetch_assoc($result);
    }
}

// If the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input data and update database
    $id = $_POST['user_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $pnumber = $_POST['pnumber'];
    $type = $_POST['usertype'];

    // Check if a file was selected for upload
    if (!empty($_FILES['profile_picture']['tmp_name'])) {
        // Handle file upload
        $target_directory = 'images/';
        $profile_picture = $_FILES['profile_picture']['name'];
        $target_file = $target_directory . basename($profile_picture);
        $upload_ok = true;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            // Update database with the new profile picture filename
            $sql = "UPDATE users SET fname='$fname', lname='$lname', dob='$dob', gender='$gender', address='$address', pnumber='$pnumber', usertype='$type', profile_picture='$profile_picture' WHERE id='$id'";
            if (mysqli_query($conn, $sql)) {
                // Display success message
                $_SESSION['success_message'] = "User information updated successfully";
                // Redirect to user_profile.php to refresh the page
                header("Location: seller_profile.php");
                exit();
            } else {
                // Display error message
                echo "Error updating user information: " . mysqli_error($conn);
            }
        } else {
            // Display error message
            echo "Error moving uploaded file.";
            $upload_ok = false;
        }
    } else {
        // Update database without changing the profile picture
        $sql = "UPDATE users SET fname='$fname', lname='$lname', dob='$dob', gender='$gender', address='$address', pnumber='$pnumber', usertype='$type' WHERE id='$id'";
        if (mysqli_query($conn, $sql)) {
            // Display success message
            $_SESSION['success_message'] = "User information updated successfully";
            // Redirect to user_profile.php to refresh the page
            header("Location: seller_profile.php");
            exit();
        } else {
            // Display error message
            echo "Error updating user information: " . mysqli_error($conn);
        }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dashboard_orderhistory_product.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
                <a href="seller_product.php">
                    <i class="bi bi-box"></i>
                    <span class="links_name">Products</span>
                </a>
            </li>
            <li>
                <a href="seller_order.php">
                    <i class="bi bi-truck"></i>
                    <span class="links_name">Orders</span>
                </a>
            </li>
            <li>
                <a href="seller_review.php" >
                    <i class="bi bi-chat-left-dots"></i>
                    <span class="links_name">Past Reviews</span>
                </a>
            </li>
            <li>
                <a href="seller_orderhistory.php">
                    <i class="bi bi-clock-history"></i>
                    <span class="links_name">Order History</span>
                </a>
            </li>
            <li>
                <a href="seller_profile.php" class="active">
                    <i class="bi bi-person-circle"></i>
                    <span class="links_name">User Profile</span>
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
                <div id="welcome-container">
                <h2>Welcome, <span id="welcome-fname"><?php echo strtoupper($row['fname']); ?></span>!</h2>
                <!-- Other welcome message elements -->
            </div>


                </div>
            </nav>

        </header>
        <!-- Order Nav -->
      <div class="home-content">
        <ul class="nav nav-tabs px-5">
          <li class="active">
            <a data-toggle="tab" href="#home">Account</a>
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
													<form action="seller_profile.php" method="POST" enctype="multipart/form-data">
														<input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
														<img id="preview" src="images/<?php echo $product['profile_picture']; ?>" alt="Profile Picture" style="width: 60%; height: 60%; margin:auto; padding:15px;">
														<div class="mb-3">
															<label for="profile_picture" class="form-label">Upload Profile Picture</label>
															<input type="file" class="form-control-file" id="profile_picture" onchange="updateProfilePicture()" name="profile_picture" accept="image/*" onchange="previewImage(event)">
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
															<label for="pnumber">Phone Number:</label>
															<input type="text" class="form-control" id="pnumber" name="pnumber" value="<?php echo $row['pnumber']; ?>">
														</div>
														<div class="form-group">
															<label for="usertype">User Type:</label>
															<input type="text" class="form-control" id="usertype" name="usertype" value="<?php echo $row['usertype']; ?>" readonly>
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
     <script>
    // Submit the form using AJAX
    document.getElementById('profileForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent form submission

        var form = this;
        var formData = new FormData(form);

        // Send the form data to the server using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open(form.method, form.action, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Request successful, update the page if needed
                console.log(xhr.responseText);
                // Refresh the page after processing the request
                location.reload();
            }
        };
        xhr.send(formData);
    });
</script>
<script>
    // Inside the success block of your AJAX request
if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
    // Request successful, update the page if needed
    console.log(xhr.responseText);

    // Update the modal values
    var userFnameModal = document.getElementById('fname');
    var userLnameModal = document.getElementById('lname');
    // Update the values in the modal if needed
    if (userFnameModal) {
        userFnameModal.value = '<?php echo $fname; ?>';
    }
    if (userLnameModal) {
        userLnameModal.value = '<?php echo $lname; ?>';
    }

    // Update the other values on the page
    var welcomeContainer = document.getElementById('welcome-container');
    if (welcomeContainer) {
        var welcomeFnameElement = welcomeContainer.querySelector('#welcome-fname');
        // Update the relevant element with the new value
        if (welcomeFnameElement) {
            welcomeFnameElement.textContent = '<?php echo $fname; ?>';
        }
        // Update other welcome message elements as needed
    }
}

</script>
<script>
// JavaScript code using AJAX
function updateProfilePicture() {
    // Get the user ID and updated profile picture file from the form
    var userId = document.getElementById("user_id").value;
    var profilePictureFile = document.getElementById("profile_picture").files[0];

    // Create a FormData object to send the data
    var formData = new FormData();
    formData.append("user_id", userId);
    formData.append("profile_picture", profilePictureFile);

    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Set up the AJAX request
    xhr.open("POST", "update_profile_picture.php", true);

    // Set the onload function to handle the AJAX response
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Handle the response
            var newProfilePicture = xhr.responseText;
            if (newProfilePicture) {
                // Replace the existing profile picture on the page
                var profilePictureElement = document.getElementById("profile_picture_element");
                profilePictureElement.src = "images/" + newProfilePicture;
            } else {
                // Display an error message
                alert("Error updating profile picture.");
            }
        }
    };

    // Send the AJAX request
    xhr.send(formData);
}
</script>
	
  </body>
</html>