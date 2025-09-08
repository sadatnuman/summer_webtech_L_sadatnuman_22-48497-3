<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    function test_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $name = test_input($_POST['name'] ?? '');
    $email = test_input($_POST['email'] ?? '');
    $website = test_input($_POST['website'] ?? '');
    $comment = test_input($_POST['comment'] ?? '');
    $gender = test_input($_POST['gender'] ?? '');

    $errors = [];

    // Name validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Name must contain only letters and whitespace.";
    }

    // Email validation
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Website validation
    if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $errors[] = "Invalid URL format for website.";
    }

    // Gender validation
    $allowed_genders = ['female', 'male', 'other'];
    if (empty($gender)) {
        $errors[] = "Gender is required.";
    } elseif (!in_array($gender, $allowed_genders)) {
        $errors[] = "Invalid gender selection.";
    }

    // File upload validation
    if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['file'];

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Error uploading file.";
        } else {
            // Validate file size (e.g., max 2MB)
            $maxFileSize = 2 * 1024 * 1024; // 2MB
            if ($file['size'] > $maxFileSize) {
                $errors[] = "File size must be less than 2MB.";
            }

            // Validate file type (example: only allow images and PDFs)
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);

            if (!in_array($mimeType, $allowedMimeTypes)) {
                $errors[] = "Only JPG, PNG, GIF images and PDF files are allowed.";
            }
        }
    } else {
        // If file is optional, you can skip this else block
        // Otherwise, uncomment the next line to require file upload:
        // $errors[] = "File upload is required.";
    }

    if ($errors) {
        echo "<h3>Errors:</h3><ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
        echo '<a href="form.html">Go back to the form</a>';
    } else {
        echo "<h3>Form submitted successfully!</h3>";
        echo "<p><strong>Name:</strong> $name</p>";
        echo "<p><strong>Email:</strong> $email</p>";
        echo "<p><strong>Website:</strong> " . ($website ?: "N/A") . "</p>";
        echo "<p><strong>Comment:</strong> " . nl2br($comment ?: "N/A") . "</p>";
        echo "<p><strong>Gender:</strong> $gender</p>";

        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            // Move uploaded file to a directory (optional)
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = basename($file['name']);
            $targetFilePath = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                echo "<p><strong>File uploaded successfully:</strong> $filename</p>";
            } else {
                echo "<p><strong>File upload failed.</strong></p>";
            }
        } else {
            echo "<p><strong>No file uploaded.</strong></p>";
        }
    }
} else {
    header("Location: form.html");
    exit();
}
?>
