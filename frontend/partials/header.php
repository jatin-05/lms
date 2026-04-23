<?php
if (!isset($brandText)) {
    $brandText = 'LMS Portal';
}
if (!isset($brandHref)) {
    $brandHref = 'index.php';
}
if (!isset($navLinks)) {
    $navLinks = [];
}
if (!isset($showSearch)) {
    $showSearch = false;
}
if (!isset($searchPlaceholder)) {
    $searchPlaceholder = 'Search...';
}
if (!isset($searchTarget)) {
    $searchTarget = '.course-card';
}
if (!isset($searchFields)) {
    $searchFields = 'h3,h4,p';
}
?>
<header class="app-header">
  <div class="header-inner">
    <a class="app-brand" href="<?php echo htmlspecialchars($brandHref); ?>"><?php echo htmlspecialchars($brandText); ?></a>
    <?php if ($showSearch) { ?>
      <div class="search-container">
        <input
          type="text"
          placeholder="<?php echo htmlspecialchars($searchPlaceholder); ?>"
          class="search-input"
          data-search-target="<?php echo htmlspecialchars($searchTarget); ?>"
          data-search-fields="<?php echo htmlspecialchars($searchFields); ?>"
          aria-label="<?php echo htmlspecialchars($searchPlaceholder); ?>"
        >
        <button class="search-btn" type="button" aria-label="Search">Search</button>
      </div>
    <?php } ?>
    <button class="mobile-menu-toggle" type="button" aria-label="Toggle navigation" onclick="toggleMobileMenu()">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <nav class="app-nav" id="mobileNav">
      <?php foreach ($navLinks as $link) { ?>
        <?php
        $class = isset($link['class']) ? $link['class'] : '';
        $isButton = isset($link['button']) ? $link['button'] : false;
        ?>
        <?php if ($isButton) { ?>
          <button class="<?php echo htmlspecialchars($class); ?>" type="button"><?php echo htmlspecialchars($link['label']); ?></button>
        <?php } else { ?>
          <a href="<?php echo htmlspecialchars($link['href']); ?>" class="<?php echo htmlspecialchars($class); ?>"><?php echo htmlspecialchars($link['label']); ?></a>
        <?php } ?>
      <?php } ?>
    </nav>
  </div>
</header>
