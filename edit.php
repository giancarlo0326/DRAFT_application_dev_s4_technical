<?php
require_once 'database.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve current content from the database
try {
    $stmt = $db->prepare("SELECT * FROM homepage_content WHERE id = 1");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($row) {
    $welcome_message = $row['welcome_message'];
    $subject = $row['subject'];
    $faculty = $row['faculty'];
} else {
    // Initialize with default values if no record found
    $welcome_message = "Welcome to our website!";
    $subject = "";
    $faculty = "";
}

// Handle form submission to update content
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_welcome_message = $_POST['welcome_message'];
    $new_subject = $_POST['subject'];
    $new_faculty = $_POST['faculty'];

    try {
        $stmt = $db->prepare("UPDATE homepage_content SET welcome_message = ?, subject = ?, faculty = ? WHERE id = 1");
        $stmt->execute([$new_welcome_message, $new_subject, $new_faculty]);
        header("Location: edit.php?success=true");
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include necessary meta tags, stylesheets, and scripts -->
</head>
<body>
    <div class="container mt-5">
        <form method="POST">
            <div class="form-group">
                <label for="welcome_message">Welcome Message</label>
                <textarea class="form-control" id="welcome_message" name="welcome_message"><?php echo htmlspecialchars($welcome_message); ?></textarea>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" value="<?php echo htmlspecialchars($subject); ?>">
            </div>
            <div class="form-group">
                <label for="faculty">Faculty</label>
                <input type="text" class="form-control" id="faculty" name="faculty" value="<?php echo htmlspecialchars($faculty); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
