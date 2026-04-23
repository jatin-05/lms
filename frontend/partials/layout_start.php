<?php
if (!isset($pageTitle)) {
    $pageTitle = 'LMS Portal';
}
if (!isset($cssPath)) {
    $cssPath = 'assets/styles.css';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($cssPath); ?>">
</head>
<body>
<div class="page-shell">
<div class="container">
