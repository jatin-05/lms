<?php
session_start();
include '../backend/db.php';

$quiz_id = $_GET['id'];

$sql = "SELECT * FROM questions WHERE quiz_id='$quiz_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="page-shell">
<div class="container">

<header class="app-header">
  <div class="header-inner">
    <div class="app-brand">LMS Portal</div>
    <div class="search-container">
      <input type="text" placeholder="Search courses..." class="search-input">
      <button class="search-btn">🔍</button>
    </div>
    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <nav class="app-nav" id="mobileNav">
      <a href="index.php">Courses</a>
      <?php if (isset($_SESSION['user_id'])) { ?>
        <a href="../backend/logout.php" class="btn btn-secondary">Logout</a>
      <?php } else { ?>
        <a href="login.html" class="btn">Login</a>
      <?php } ?>
    </nav>
  </div>
</header>

<section class="form-card">
  <h2 class="page-title">Quiz</h2>
  <form action="../backend/submit_quiz.php" method="POST">
    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

<?php
$i = 1;
while($row = $result->fetch_assoc()) {
?>

<p><?php echo $i++ . ". " . $row['question']; ?></p>

<input type="radio" name="q<?php echo $row['id']; ?>" value="a"> <?php echo $row['option_a']; ?><br>
<input type="radio" name="q<?php echo $row['id']; ?>" value="b"> <?php echo $row['option_b']; ?><br>
<input type="radio" name="q<?php echo $row['id']; ?>" value="c"> <?php echo $row['option_c']; ?><br>
<input type="radio" name="q<?php echo $row['id']; ?>" value="d"> <?php echo $row['option_d']; ?><br><br>

<?php } ?>

    <button class="btn" type="submit">Submit Quiz</button>
  </form>
</section>
</div>
</div>

<script>
function toggleMobileMenu() {
  const nav = document.getElementById('mobileNav');
  nav.classList.toggle('active');
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
  const nav = document.getElementById('mobileNav');
  const toggle = document.querySelector('.mobile-menu-toggle');
  
  if (!nav.contains(event.target) && !toggle.contains(event.target)) {
    nav.classList.remove('active');
  }
});
</script>
</body>
</html>