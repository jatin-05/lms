<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'instructor') {
    die("Access denied");
}

$course_id = $_POST['course_id'];
$title = $_POST['title'];

// Insert quiz
$sql = "INSERT INTO quizzes (course_id, title)
        VALUES ('$course_id', '$title')";

if ($conn->query($sql)) {

    $quiz_id = $conn->insert_id;

    // 🔥 Redirect to add questions
    // header("Location: ../frontend/instructor/create_quiz.php?quiz_id=$quiz_id");
    header("Location: ../frontend/instructor/create_quiz.php?course_id=$course_id");
    exit();

} else {
    echo "Error: " . $conn->error;
}
?>