<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<header class="app-header">
  <div class="header-inner">
    <div class="app-brand">Admin Dashboard</div>
    <nav class="app-nav">
      <a href="../../backend/logout.php">Logout</a>
    </nav>
  </div>
</header>
<div class="page-shell">
<div class="container">
  <h2 class="page-title">Admin Dashboard</h2>
  <p class="subheading">Welcome, <?php echo $_SESSION['name']; ?></p>
  <a class="btn" href="../../backend/logout.php">Logout</a>
</div>
</div>
</body>
</html>