<?php
include '../backend/db.php';
session_start();

$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Home</title>
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
      <a href="login.html">Login</a>
      <a href="register.html">Register</a>
    </nav>
  </div>
</header>

<section class="hero-panel">
  <div class="label-pill">Discover</div>
  <h1>Find the right course for your next milestone</h1>
  <p class="subheading">Browse high-quality learning paths for students, instructors, and administrators with lessons, quizzes, and progress tracking.</p>
  <div class="hero-actions">
    <a class="btn" href="#courses">Browse Courses</a>
    <a class="btn btn-secondary" href="login.html">Login</a>
  </div>
</section>

<div id="courses" class="course-container">

<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        $user_id = $_SESSION['user_id'] ?? 0;
        $is_enrolled = false;

        if ($user_id) {
            $check = "SELECT * FROM enrollments 
                      WHERE user_id='$user_id' AND course_id='{$row['id']}'";

            $res = $conn->query($check);
            $is_enrolled = $res->num_rows > 0;
        }
?>

<div class="course-card">
    <a class="card-link" href="course.php?id=<?php echo $row['id']; ?>">
        <img src="../uploads/<?php echo $row['thumbnail']; ?>">
        <div class="course-card-content">
            <span class="label-pill">Course</span>
            <h3><?php echo $row['title']; ?></h3>
            <p><?php echo substr($row['description'], 0, 100); ?>...</p>
        </div>
    </a>

    <div class="course-card-footer">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'student') { ?>
            <?php if ($is_enrolled) { ?>
                <span class="status-pill success">✔ Enrolled</span>
            <?php } else { ?>
                <form action="../backend/enroll.php" method="POST">
                    <input type="hidden" name="course_id" value="<?php echo $row['id']; ?>">
                    <button class="btn" type="submit">Enroll</button>
                </form>
            <?php } ?>
        <?php } else { ?>
            <span class="status-pill">View course details</span>
        <?php } ?>
    </div>
</div>

<?php
    }
} else {
    echo "No courses found";
}
?>

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