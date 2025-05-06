<?php
session_start();
$conn = new mysqli("localhost", "root", "", "student_portal");

if (!isset($_SESSION['student_num'])) {
    die("Unauthorized");
}

$student_num = $_SESSION['student_num'];
$email = $_POST['email'];
$contact = $_POST['contact_num'];
$module = $_POST['module_code'];

$profile_pic = '';
$upload_dir = __DIR__ . "/uploads/";
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$max_size = 2 * 1024 * 1024; // 2MB

if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $file_tmp = $_FILES['profile_pic']['tmp_name'];
    $file_name = basename($_FILES['profile_pic']['name']);
    $file_type = mime_content_type($file_tmp);
    $file_size = $_FILES['profile_pic']['size'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($file_type, $allowed_types) && $file_size <= $max_size && in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        $safe_name = uniqid("profile_", true) . '.' . $ext;
        $target = $upload_dir . $safe_name;

        if (move_uploaded_file($file_tmp, $target)) {
            $profile_pic = $safe_name;
        } else {
            die("Failed to upload file.");
        }
    } else {
        die("Invalid file type or size.");
    }
}


if ($profile_pic) {
    $stmt = $conn->prepare("UPDATE users SET email=?, contact_num=?, module_code=?, profile_pic=? WHERE student_num=?");
    $stmt->bind_param("sssss", $email, $contact, $module, $profile_pic, $student_num);
} else {
    $stmt = $conn->prepare("UPDATE users SET email=?, contact_num=?, module_code=? WHERE student_num=?");
    $stmt->bind_param("ssss", $email, $contact, $module, $student_num);
}

if ($stmt->execute()) {
    echo "Profile updated.<br><a href='profile.php'>Back to Profile</a>";
} else {
    echo "Update failed: " . $stmt->error;
}
?>
