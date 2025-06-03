<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'admin') {
    die("Access denied!");
}

// Count stats
$student_count = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'")->fetch_assoc()['total'];
$teacher_count = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='teacher'")->fetch_assoc()['total'];
$course_count = $conn->query("SELECT COUNT(*) as total FROM courses")->fetch_assoc()['total'];
$question_count = $conn->query("SELECT COUNT(*) as total FROM questions")->fetch_assoc()['total'];

// Handle approve/delete requests
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    $conn->query("UPDATE users SET approved=1 WHERE id=$id");
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
}
?>

<h2>Admin Dashboard</h2>
<p>👨‍🎓 Students: <?= $student_count ?></p>
<p>👨‍🏫 Teachers: <?= $teacher_count ?></p>
<p>📚 Courses: <?= $course_count ?></p>
<p>❓ Questions: <?= $question_count ?></p>

<hr>
<h3>Pending Teachers (Approve/Delete)</h3>
<table border="1">
    <tr><th>ID</th><th>Username</th><th>Action</th></tr>
    <?php
    $pending = $conn->query("SELECT * FROM users WHERE role='teacher' AND approved=0");
    while ($row = $pending->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['username']}</td>
            <td>
                <a href='?approve={$row['id']}'>Approve</a> |
                <a href='?delete={$row['id']}'>Delete</a>
            </td>
        </tr>";
    }
    ?>
</table>

<hr>
<h3>All Students</h3>
<table border="1">
    <tr><th>ID</th><th>Username</th><th>Action</th></tr>
    <?php
    $students = $conn->query("SELECT * FROM users WHERE role='student'");
    while ($row = $students->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['username']}</td>
            <td><a href='?delete={$row['id']}'>Delete</a></td>
        </tr>";
    }
    ?>
</table>

<p><a href="logout.php">Logout</a></p>
