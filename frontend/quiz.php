<?php
session_start();
include '../backend/db.php';

$quiz_id = $_GET['id'];

$sql = "SELECT * FROM questions WHERE quiz_id='$quiz_id'";
$result = $conn->query($sql);

$pageTitle = 'Quiz';
$cssPath = 'assets/styles.css';
$scriptPath = 'assets/js/app.js';
$brandHref = 'index.php';
$showSearch = false;
$navLinks = [
    ['href' => 'index.php', 'label' => 'Courses']
];
if (isset($_SESSION['user_id'])) {
    $navLinks[] = ['href' => '../backend/logout.php', 'label' => 'Logout', 'class' => 'btn btn-secondary'];
} else {
    $navLinks[] = ['href' => 'login.html', 'label' => 'Login', 'class' => 'btn'];
}
include 'partials/layout_start.php';
include 'partials/header.php';
?>

<section class="form-card">
  <h2 class="page-title">Quiz</h2>
  <form action="../backend/submit_quiz.php" method="POST">
    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

<?php
$i = 1;
while($row = $result->fetch_assoc()) {
?>
<fieldset class="question-group">
  <legend><?php echo $i++ . ". " . $row['question']; ?></legend>
  <label class="quiz-option">
    <input type="radio" name="q<?php echo $row['id']; ?>" value="a" required>
    <span><?php echo $row['option_a']; ?></span>
  </label>
  <label class="quiz-option">
    <input type="radio" name="q<?php echo $row['id']; ?>" value="b">
    <span><?php echo $row['option_b']; ?></span>
  </label>
  <label class="quiz-option">
    <input type="radio" name="q<?php echo $row['id']; ?>" value="c">
    <span><?php echo $row['option_c']; ?></span>
  </label>
  <label class="quiz-option">
    <input type="radio" name="q<?php echo $row['id']; ?>" value="d">
    <span><?php echo $row['option_d']; ?></span>
  </label>
</fieldset>
<?php } ?>

    <button class="btn" type="submit">Submit Quiz</button>
  </form>
</section>
<?php include 'partials/layout_end.php'; ?>