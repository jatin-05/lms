<?php
session_start();
include '../backend/db.php';

$lesson_id = $_GET['id'];

$sql = "SELECT * FROM lessons WHERE id='$lesson_id'";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    die("Lesson not found");
}

$lesson = $result->fetch_assoc();

$pageTitle = $lesson['title'];
$cssPath = 'assets/styles.css';
$scriptPath = 'assets/js/app.js';
$brandHref = 'index.php';
$showSearch = false;
$navLinks = [
    ['href' => 'index.php', 'label' => 'Courses'],
    ['href' => 'course.php?id=' . $lesson['course_id'], 'label' => 'Back to Course']
];
if (isset($_SESSION['user_id'])) {
    $navLinks[] = ['href' => '../backend/logout.php', 'label' => 'Logout', 'class' => 'btn btn-secondary'];
} else {
    $navLinks[] = ['href' => 'login.html', 'label' => 'Login', 'class' => 'btn'];
}
include 'partials/layout_start.php';
include 'partials/header.php';
?>

<section class="card course-summary">
  <h1><?php echo $lesson['title']; ?></h1>

  <?php 
  if (!empty($lesson['video_url'])) {
      $video_url = trim($lesson['video_url']);
      $video_id = '';

      if (strpos($video_url, 'watch?v=') !== false) {
          parse_str(parse_url($video_url, PHP_URL_QUERY), $params);
          $video_id = $params['v'] ?? '';
      } elseif (strpos($video_url, 'youtu.be/') !== false) {
          $parts = explode('/', $video_url);
          $video_id = end($parts);
      }

      if (!empty($video_id)) {
  ?>
      <div class="video-wrap">
        <iframe width="100%" height="420"
          src="https://www.youtube.com/embed/<?php echo $video_id; ?>"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen>
        </iframe>
      </div>
  <?php 
      } else {
          echo "<p class=\"notification\">Invalid video URL</p>";
      }
  }
  ?>

  <?php
  $user_id = $_SESSION['user_id'] ?? 0;
  $check = "SELECT * FROM progress WHERE user_id='$user_id' AND lesson_id='$lesson_id'";
  $result = $conn->query($check);

  if ($result->num_rows > 0) {
  ?>
      <p class="status-message success">✔ Completed</p>
  <?php } else { ?>
      <form action="../backend/complete_lesson.php" method="POST" class="form-actions">
          <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">
          <button class="btn" type="submit">Mark as Complete</button>
      </form>
  <?php } ?>

  <div class="lesson-content">
    <p><?php echo nl2br(htmlspecialchars($lesson['content'])); ?></p>
  </div>
</section>
<?php include 'partials/layout_end.php'; ?>