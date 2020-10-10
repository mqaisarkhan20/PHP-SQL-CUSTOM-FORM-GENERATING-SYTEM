<nav class="navbar bg-primary navbar-dark navbar-expand-sm">
  <div class="container">
    <!-- Links -->
    <ul class="nav navbar-nav" style="visibility: visible;">
      <li class="nav-item">
        <a class="nav-link <?= ($nav_active == 'forms') ? 'active': false; ?>" href="<?= URL ?>forms.php">Forms</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($nav_active == 'questions') ? 'active': false; ?>" href="<?= URL ?>questions.php">Questions</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($nav_active == 'users') ? 'active': false; ?>" href="<?= URL ?>users.php">Users</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($nav_active == 'answers') ? 'active': false; ?>" href="<?= URL ?>answers.php">Answers</a>
      </li>
            <li class="nav-item">
        <a class="nav-link <?= ($nav_active == 'eindcontrole') ? 'active': false; ?>" href="<?= URL ?>eindcontrole.php">Eindcontrole</a>
      </li>  
    </ul>

    <ul class="navbar-nav">
      <?php if ($_SESSION['user_role'] == 'admin'): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?= URL ?>/">Admin</a>
      </li>
      <?php endif; ?>
      <?php if (isset($_SESSION['username'])): ?>
      <li class="nav-item">
        <a class="nav-link" href="?logout">Logout</a>
      </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>