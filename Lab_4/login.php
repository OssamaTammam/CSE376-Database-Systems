<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $dbServer = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "registiration";

    $userEmail = $_POST['email'];
    $userHashedPassword = md5($_POST['password']);

    $db = new mysqli($dbServer, $dbUsername, $dbPassword, $dbName);

    if ($db->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sqlQuery = "SELECT * FROM user WHERE email = ? AND password = ?";
    $stmt = $db->prepare($sqlQuery);

    if ($stmt) {
        $stmt->bind_param("ss", $userEmail, $userHashedPassword);
        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }

    if ($result) {
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $name = $row['name'];
            $db->close();
            echo "<script>alert('Login successful! Welcome, $name.');</script>";
            header("Location: welcome.php?name=" . urlencode($name));
            exit();
        } else {
            echo "<script>alert('Invalid login credentials. Please check your email and password.');
            window.location.href = \"loginForm.php\";
            </script>";
            exit();
        }
    } else {
        echo "Error getting record: " . $stmt->error;
    }
}
