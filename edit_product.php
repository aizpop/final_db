<?php
// Database connection parameters
$servername = 'localhost';
$dbName = 'hardware';
$username = 'root';
$password = '';

// Establish a connection to the MySQL database
$conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Retrieve form data
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $productId = isset($_POST['productid']) ? $_POST['productid'] : '';
    $productName = isset($_POST['productname']) ? $_POST['productname'] : '';
    $productPrice = isset($_POST['productprice']) ? $_POST['productprice'] : '';
    $productDescription = isset($_POST['productdescription']) ? $_POST['productdescription'] : '';
    $productImage = '';

    // Check if the product image is updated
    if (!empty($_FILES['productimage']['name'])) {
        // Upload the updated product image to the desired directory (adjust the path accordingly)
        $targetDirectory = 'images/';
        $targetFile = $targetDirectory . basename($_FILES['productimage']['name']);
        if (move_uploaded_file($_FILES['productimage']['tmp_name'], $targetFile)) {
            $productImage = $_FILES['productimage']['name'];
            echo 'Image uploaded successfully.';
        } else {
            echo 'Error uploading image.';
        }
    } else {
        // No new image selected, retain the existing image
        $sql = "SELECT p_image FROM products WHERE p_id = :productId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':productId', $productId);
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $productImage = $result['p_image'];
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    }

    // Update the product in the database
    $sql = "UPDATE products SET category = :category, p_name = :productName, p_price = :productPrice, p_desc = :productDescription, p_image = :productImage WHERE p_id = :productId";
    $stmt = $conn->prepare($sql);

    // Bind the values to the parameters
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':productName', $productName);
    $stmt->bindParam(':productPrice', $productPrice);
    $stmt->bindParam(':productDescription', $productDescription);
    $stmt->bindParam(':productImage', $productImage);
    $stmt->bindParam(':productId', $productId);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Product updated successfully!";
        // Redirect to a different page or display a success message
        header("Location: seller_product.php");
        exit;
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}

// Retrieve the product ID from the query string
// Prepare the SQL statement to retrieve data from the "products" table
$sql = "SELECT * FROM products";
$stmt = $conn->prepare($sql);

// Execute the statement
if ($stmt->execute()) {
    // Fetch all rows from the result set as an associative array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Error: " . $stmt->errorInfo()[2];
}

// Close the database connection
$conn = null;
?>
