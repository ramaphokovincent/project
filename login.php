<?php
session_start();
$conn = new mysqli("localhost", "root", "", "student_portal");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['student_num'] = $user['student_num'];
            header("Location: profile.php");
            exit();
        }
    }
    echo "Invalid login credentials.";
    
}
?>
