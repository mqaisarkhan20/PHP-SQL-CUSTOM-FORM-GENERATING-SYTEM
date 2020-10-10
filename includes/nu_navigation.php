<nav class="navbar bg-primary navbar-dark navbar-expand-sm">
  <div class="container">
    <!-- Links -->
    <ul class="nav navbar-nav" style="visibility: visible;">
      <li class="nav-item">
        <a class="nav-link <?= ($nav_active == 'answers') ? 'active': false; ?>" href="<?= URL ?>answers.php">Forms</a>
      </li>
    </ul>

    <ul class="navbar-nav">
      <?php if (isset($_SESSION['username'])): ?>
      <li class="nav-item">
        <a class="nav-link" href="?logout">Logout</a>
      </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>