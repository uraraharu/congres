<?php
$status = $_GET['status'] ?? null;
?>
<main class="container">
  <h1>Connexion</h1>

  <?php if ($status === 'login_error'): ?>
    <div class="flash err">Email ou mot de passe incorrect.</div>
  <?php elseif ($status === 'need_login'): ?>
    <div class="flash err">Veuillez vous connecter pour continuer.</div>
  <?php endif; ?>

  <div class="card">
    <form action="?action=dologin" method="POST" autocomplete="off">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required>

      <button type="submit" class="btn">Se connecter</button>
      <a href="?action=accueil" class="btn secondary">Annuler</a>
    </form>
  </div>
</main>