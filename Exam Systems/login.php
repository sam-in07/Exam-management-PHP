<?php
session_start();    // দিয়ে সেশন চালু করা হয়েছে, যাতে ইউজার লগইন করলে তার তথ্য সংরক্ষণ করা যায়। 
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");   
    //প্রিপেয়ার্ড স্টেটমেন্ট ব্যবহার করে ইউজারের ইউজারনেম অনুসারে ডাটাবেস থেকে তথ্য নেয়া হচ্ছে।
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();    //দিয়ে ইউজারের তথ্য পাওয়া যাচ্ছে।

    if ($user && password_verify($password, $user['password'])) // versify  kortesi pass & user
    {
        if ($user['role'] != 'admin' && !$user['approved']) {
            echo "❌ Your account is pending approval by admin.";
            exit;
        }
         //যদি ইউজার "অ্যাডমিন" না হয় এবং অনুমোদিত (Approved) না হয়, তাহলে তাকে লগইন করতে দেয়া হবে না এবং exit; দিয়ে স্ক্রিপ্ট বন্ধ হয়ে যাবে।
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        //   সেশন ভেরিয়েবল তৈরি করা হয়েছে যাতে ইউজার আইডি (user_id) এবং ভূমিকা (role) সংরক্ষণ থাকে।
        // Redirect to dashboards
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } elseif ($user['role'] == 'teacher') {
            header("Location: teacher_dashboard.php");
        } else {
            header("Location: student_dashboard.php");
        }
    } else {
        echo "❌ Invalid username or password.";
    }
}
?>

<h2>Login</h2>
<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>

<p><a href="register.php">Don't have an account? Register</a></p>
