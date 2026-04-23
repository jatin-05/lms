<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Login required");
}

$user_id = $_SESSION['user_id'];
$lesson_id = $_POST['lesson_id'];
$course_id = $_POST['course_id'];

// Prevent duplicate
$check = "SELECT id FROM progress WHERE user_id='$user_id' AND lesson_id='$lesson_id'";
$result = $conn->query($check);

if ($result->num_rows == 0) {
    $conn->query("INSERT INTO progress (user_id, lesson_id, completed)
                  VALUES ('$user_id', '$lesson_id', 1)");
}

// ✅ Correct redirect
header("Location: ../frontend/course.php?id=$course_id");
exit();