<?php
session_start();
$conn = new mysqli("localhost", "root", "", "student_portal");

if (!isset($_SESSION['student_num'])) {
    header("Location: login.html");
    exit();
}

$student_num = $_SESSION['student_num'];
$stmt = $conn->prepare("SELECT * FROM users WHERE student_num = ?");
$stmt->bind_param("s", $student_num);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
  <form action="update_profile.php" method="post" enctype="multipart/form-data">
    <p>Email: <input type="email" name="email" value="<?php echo $user['email']; ?>" required></p>
    <p>Contact: <input type="text" name="contact_num" value="<?php echo $user['contact_num']; ?>" required></p>
    <p>Module Code: <input type="text" name="module_code" value="<?php echo $user['module_code']; ?>" required></p>
    <p>Upload Profile Picture: <input type="file" name="profile_pic"></p>
    <button type="submit">Update Profile</button>
  </form>

  <?php
  if (!empty($user['profile_pic'])) {
      echo "<img src='uploads/{$user['profile_pic']}' width='100' alt='Profile Picture'>";
  }
  ?>
</body>
</html>
