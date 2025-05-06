<?php
$conn = new mysqli("localhost", "root", "", "student_portal");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

// Check password match
if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

// Check password strength
if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) ||
    !preg_match('/[\W]/', $password)) {
    die("Password must be at least 8 characters and include upper/lowercase, number, and symbol.");
}

    $student_num = $_POST['student_num'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $contact = $_POST['contact_num'];
    $module = $_POST['module_code'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        die("Passwords do not match.");
    }

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? OR student_num=?");
    $stmt->bind_param("ss", $email, $student_num);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("Email or Student Number already registered.");
    }

    $stmt = $conn->prepare("INSERT INTO users (student_num, name, surname, contact_num, module_code, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $student_num, $name, $surname, $contact, $module, $email, $password_hashed);

    if ($stmt->execute()) {
        echo "Registration successful. <a href='index.html'>Go to login</a>";

    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
