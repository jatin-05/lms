<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'instructor') {
    die("Access denied");
}

$lesson_id = $_POST['lesson_id'];
$title = $_POST['title'];
$video_url = $_POST['video_url'];
$content = $_POST['content'];
$order = $_POST['lesson_order'];

// Update
$sql = "UPDATE lessons 
        SET title='$title', video_url='$video_url', content='$content', lesson_order='$order'
        WHERE id='$lesson_id'";

$conn->query($sql);

echo "Lesson updated!";
header("Location: ../frontend/instructor/dashboard.php");

?>