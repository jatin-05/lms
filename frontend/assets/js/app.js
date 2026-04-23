function toggleMobileMenu() {
  var nav = document.getElementById('mobileNav');
  if (!nav) {
    return;
  }
  nav.classList.toggle('active');
}

document.addEventListener('click', function (event) {
  var nav = document.getElementById('mobileNav');
  var toggle = document.querySelector('.mobile-menu-toggle');
  if (!nav || !toggle) {
    return;
  }
  if (!nav.contains(event.target) && !toggle.contains(event.target)) {
    nav.classList.remove('active');
  }
});

document.addEventListener('keydown', function (event) {
  if (event.key !== 'Escape') {
    return;
  }
  var nav = document.getElementById('mobileNav');
  if (nav) {
    nav.classList.remove('active');
  }
});

function performSearch(input) {
  var targetSelector = input.dataset.searchTarget || '.course-card';
  var fields = (input.dataset.searchFields || 'h3,h4,p').split(',');
  var items = document.querySelectorAll(targetSelector);
  var searchQuery = input.value.trim().toLowerCase();

  items.forEach(function (item) {
    var combined = fields
      .map(function (selector) {
        var node = item.querySelector(selector.trim());
        return node ? node.textContent.toLowerCase() : '';
      })
      .join(' ');
    item.style.display = searchQuery.length === 0 || combined.includes(searchQuery) ? '' : 'none';
  });
}

document.addEventListener('DOMContentLoaded', function () {
  var searchInput = document.querySelector('.search-input');
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      performSearch(searchInput);
    });
  }
});
