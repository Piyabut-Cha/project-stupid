<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $id = $_POST["id"];
    $name = $_POST["name"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    $type = $_POST["type"];
    
    // Process the uploaded image
    $image = $_FILES["image"]["tmp_name"];
    $image_name = $_FILES["image"]["name"];
    $image_path = "uploads/" . $image_name; // Adjust the directory as per your preference
    
    // Create the "uploads" directory if it doesn't exist
    if (!is_dir("uploads")) {
        mkdir("uploads");
    }
    
    // Move the uploaded image to the desired location
    if (move_uploaded_file($image, $image_path)) {
        // Get the file extension
        $image_extension = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
        
        // Check the file extension and create an image resource accordingly
        if ($image_extension === "jpg" || $image_extension === "jpeg") {
            $image_resource = imagecreatefromjpeg($image_path);
        } elseif ($image_extension === "png") {
            $image_resource = imagecreatefrompng($image_path);
        } elseif ($image_extension === "gif") {
            $image_resource = imagecreatefromgif($image_path);
        }
        
        // If the image resource was created successfully, save it to the correct location
        if ($image_resource !== false) {
            $output_image_path = "uploads/" . $id . "." . $image_extension;
            
            // Define the new dimensions for the resized image
            $new_width = 200; // Adjust as per your preference
            $new_height = 200; // Adjust as per your preference
            
            // Create a new image with the desired dimensions
            $new_image = imagecreatetruecolor($new_width, $new_height);
            
            // Resize and copy the original image to the new image with preserved transparency
            if ($image_extension === 'png' || $image_extension === 'gif') {
                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
            }
            imagecopyresampled($new_image, $image_resource, 0, 0, 0, 0, $new_width, $new_height, imagesx($image_resource), imagesy($image_resource));
            
            // Save the resized image to the correct location
            if ($image_extension === "jpg" || $image_extension === "jpeg") {
                imagejpeg($new_image, $output_image_path);
            } elseif ($image_extension === "png") {
                imagepng($new_image, $output_image_path);
            } elseif ($image_extension === "gif") {
                imagegif($new_image, $output_image_path);
            }
            
            // Update the image path in the database
            $image_path = $output_image_path;
            
            // Free the image resources
            imagedestroy($image_resource);
            imagedestroy($new_image);
        } else {
            echo "Error creating image resource.";
        }
        
        // Connect to the database
        $servername = "localhost"; // Change if necessary
        $username = "root"; // Change if necessary
        $password = ""; // Change if necessary
        $dbname = "shoping"; // Change if necessary
        
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Check if the ID already exists in the table
        $existing_product = $conn->query("SELECT id FROM sp_product WHERE id = $id");
        
        if ($existing_product->num_rows > 0) {
            // Update the existing product
            $stmt = $conn->prepare("UPDATE sp_product SET name = ?, img = ?, price = ?, description = ?, type = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $name, $image_path, $price, $description, $type, $id);
        } else {
            // Insert a new product
            $stmt = $conn->prepare("INSERT INTO sp_product (`id`, `name`, `img`, `price`, `description`, `type`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $id, $name, $image_path, $price, $description, $type);
        }
        
        // Execute the SQL statement
        if ($stmt->execute()) {
            echo "Product added successfully!";
        } else {
            echo "Error adding product: " . $conn->error;
        }
        
        // Close the database connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Error moving uploaded file.";
    }
}
?>
