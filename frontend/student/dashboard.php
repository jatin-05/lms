<?php
session_start();
include '../../backend/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch enrolled courses
$sql = "SELECT courses.* FROM courses
        JOIN enrollments ON courses.id = enrollments.course_id
        WHERE enrollments.user_id = '$user_id'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
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
      <a href="../index.php">Home</a>
      <a href="dashboard.php">My Courses</a>
      <a href="../../backend/logout.php" class="btn btn-secondary">Logout</a>
    </nav>
  </div>
</header>
<div class="page-shell">
<div class="container">
<h2 class="page-title">Welcome, <?php echo $_SESSION['name']; ?></h2>

<h3>My Courses</h3>

<div class="course-container">

<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
?>
<a href="../course.php?id=<?php echo $row['id']; ?>">
        <div class="course-card">
        <img src="../../uploads/<?php echo $row['thumbnail']; ?>">
        <h4><?php echo $row['title']; ?></h4>
        <p><?php echo substr($row['description'], 0, 80); ?>...</p>
    </div>
</a>
<?php
    }
} else {
    echo "You have not enrolled in any courses yet.";
}
?>

</div>

<br>

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