<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //যদি ফর্মটি সাবমিট হয় (POST মেথডে), তাহলে নিচের কোডগুলো চালু হবে।
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  
    //পাসওয়ার্ডটি নিরাপত্তার জন্য password_hash() দিয়ে এনক্রিপ্ট করা হয়।

    $role = $_POST['role'];
    //ইউজারের role (student বা teacher) নেয়া হয়। 

    $approved = ($role == 'student') ? 1 : 0; // auto-approve student
    //যদি ইউজার student হয়, তাহলে তাকে অটো-অ্যাপ্রুভ (approved = 1) করা হবে। অন্যথায়, teacher হলে approved = 0 থাকবে (মানে অ্যাডমিনকে অনুমোদন দিতে হবে)।
    $stmt = $conn->prepare("INSERT INTO users (username, password, role, approved) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $password, $role, $approved);
   //ssss মানে ৪টি ভ্যালু – ৩টি স্ট্রিং (username, password, role) এবং ১টি integer (approved)।
    if ($stmt->execute()) {
        echo "✅ Registration successful! <a href='login.php'>Login here</a>";
    } else {
        echo "❌ Error: " . $stmt->error;
    }
}
?>

<h2>Register</h2>
<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Role:
    <select name="role" required>
        <option value="student">Student</option>
        <option value="teacher">Teacher</option>
    </select><br><br>
    <button type="submit">Register</button>
</form>    