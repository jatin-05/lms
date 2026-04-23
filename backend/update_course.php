<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'instructor') {
    die("Access denied");
}

$course_id = $_POST['course_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$instructor_id = $_SESSION['user_id'];

// Update ONLY if owned by instructor
$sql = "UPDATE courses 
        SET title='$title', description='$description'
        WHERE id='$course_id' AND instructor_id='$instructor_id'";

if ($conn->query($sql)) {
    echo "Course updated successfully!";
    header("Location: ../frontend/instructor/dashboard.php");

} else {
    echo "Error: " . $conn->error;
}
?>