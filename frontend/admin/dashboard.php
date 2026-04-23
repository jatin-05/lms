<?php
session_start();
include '../../backend/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.html");
    exit();
}
?>
<?php
$userCountResult = $conn->query("SELECT COUNT(*) AS total FROM users");
$courseCountResult = $conn->query("SELECT COUNT(*) AS total FROM courses");
$enrollmentCountResult = $conn->query("SELECT COUNT(*) AS total FROM enrollments");
$userTotal = $userCountResult ? (int) $userCountResult->fetch_assoc()['total'] : 0;
$courseTotal = $courseCountResult ? (int) $courseCountResult->fetch_assoc()['total'] : 0;
$enrollmentTotal = $enrollmentCountResult ? (int) $enrollmentCountResult->fetch_assoc()['total'] : 0;

$pageTitle = 'Admin Dashboard';
$cssPath = '../assets/styles.css';
$scriptPath = '../assets/js/app.js';
$brandText = 'Admin Panel';
$brandHref = '../index.php';
$showSearch = false;
$navLinks = [
    ['href' => '../../backend/logout.php', 'label' => 'Logout', 'class' => 'btn btn-secondary']
];
include '../partials/layout_start.php';
include '../partials/header.php';
?>
  <section class="hero-panel hero-layout">
    <div>
      <h2 class="page-title">Admin Dashboard</h2>
      <p class="subheading">Welcome, <?php echo $_SESSION['name']; ?>. Monitor platform growth and keep core operations healthy.</p>
      <div class="hero-actions">
        <a class="btn" href="../index.php">Open Frontend</a>
        <a class="btn btn-secondary" href="../../backend/logout.php">Logout</a>
      </div>
    </div>
    <div class="hero-media">
      <img src="../assets/images/hero-learning.jpg" alt="Keep learning hero image">
    </div>
  </section>

  <section class="stats-grid">
    <article class="stat-card">
      <div class="stat-value"><?php echo $userTotal; ?></div>
      <div class="stat-label">Registered Users</div>
    </article>
    <article class="stat-card">
      <div class="stat-value"><?php echo $courseTotal; ?></div>
      <div class="stat-label">Published Courses</div>
    </article>
    <article class="stat-card">
      <div class="stat-value"><?php echo $enrollmentTotal; ?></div>
      <div class="stat-label">Total Enrollments</div>
    </article>
  </section>

  <section class="slider-shell">
    <div class="slider-header">
      <h3 class="section-heading">Operations Slider</h3>
      <div class="slider-nav">
        <a href="#adminSlider">Start</a>
        <a href="#adminPanels">Panels</a>
      </div>
    </div>
    <div class="slider-track" id="adminSlider">
      <article class="slider-card">
        <span class="label-pill">Users</span>
        <h4>Manage Access</h4>
        <p>Track user growth and review account activity to keep access secure.</p>
      </article>
      <article class="slider-card">
        <span class="label-pill">Courses</span>
        <h4>Content Quality</h4>
        <p>Monitor course count, improve coverage, and ensure each track stays updated.</p>
      </article>
      <article class="slider-card">
        <span class="label-pill">Enrollments</span>
        <h4>Engagement</h4>
        <p>Analyze enrollment trends to identify top programs and weak conversion paths.</p>
      </article>
    </div>
  </section>

  <section id="adminPanels" class="split-grid">
    <article class="panel-card">
      <h3>Platform Health</h3>
      <p>Use these top-level metrics to evaluate adoption and identify where to improve onboarding.</p>
    </article>
    <article class="panel-card">
      <h3>Admin Notes</h3>
      <p>All blocks on this page use live database counts, so values remain accurate and actionable.</p>
    </article>
  </section>
<?php include '../partials/layout_end.php'; ?>