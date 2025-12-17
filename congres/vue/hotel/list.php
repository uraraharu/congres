<?php
$status = $_GET['status'] ?? null;
$logged = !empty($_SESSION['congressiste']);

$etoileFiltre = $_GET['etoile'] ?? '';
$chambreFiltre = $_GET['chambres'] ?? '';
?>
<main class="container">
  <h1>Liste des hôtels</h1>

  <?php if (!empty($etoileFiltre)): ?>
    <div class="flash ok">Filtre appliqué : hôtels avec au moins <?php echo htmlspecialchars($etoileFiltre) ?> ★</div>
  <?php endif; ?>

  <?php if (!empty($chambreFiltre)): ?>
    <div class="flash ok">Filtre appliqué : au moins <?php echo htmlspecialchars($chambreFiltre) ?> chambres disponibles</div>
  <?php endif; ?>

  <?php if ($status === 'login_ok'): ?>
    <div class="flash ok">Connexion réussie</div>
  <?php elseif ($status === 'logout_ok'): ?>
    <div class="flash ok">Déconnecté</div>
  <?php elseif ($status === 'created'): ?>
    <div class="flash ok">Hôtel créé avec succès</div>
  <?php elseif ($status === 'updated'): ?>
    <div class="flash ok">Hôtel mis à jour</div>
  <?php elseif ($status === 'deleted'): ?>
    <div class="flash ok">Hôtel supprimé</div>
  <?php elseif ($status === 'assigned'): ?>
    <div class="flash ok">Hôtel attribué au congressiste connecté</div>
  <?php elseif ($status === 'assign_error'): ?>
    <div class="flash err">Impossible d’attribuer cet hôtel (vérifiez facture/hôtel).</div>
  <?php elseif ($status === 'error'): ?>
    <div class="flash err">Erreur</div>
  <?php endif; ?>

  <div class="card" style="margin-bottom:12px">
    <a class="btn" href="?action=formhotel">+ Nouvel hôtel</a>
  </div>

  <!-- Formulaire de tri -->
  <form method="GET" action="" class="card" style="padding:10px;margin-bottom:16px;display:flex;gap:12px;align-items:flex-end;">
    <input type="hidden" name="action" value="hotels">
    
    <div>
      <label for="etoile">Filtrer par étoiles (≥)</label><br>
      <input type="number" name="etoile" id="etoile" min="1" max="5" value="<?= htmlspecialchars($etoileFiltre) ?>"style="width:70px;padding:4px;">
    </div>

    <div>
      <label for="chambres">Filtrer par chambres disponibles (≥)</label><br>
      <input type="number" name="chambres" id="chambres" min="0" value="<?= htmlspecialchars($chambreFiltre) ?>"style="width:70px;padding:4px;">
    </div>

    <button type="submit" class="btn">Filtrer</button>
    <a href="?action=hotels" class="btn">Réinitialiser</a>
  </form>

  <table>
    <thead>
      <tr>
        <th>ID</th><th>Nom</th><th>Adresse</th><th>Prix</th>
        <th>Petit-déj.</th><th>Étoiles</th><th>Chambres</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($hotels)): ?>
        <tr><td colspan="8">Aucun hôtel trouvé.</td></tr>
      <?php else: ?>
        <?php foreach ($hotels as $h): ?>
          <tr>
            <td><?= htmlspecialchars($h['id_hotel']) ?></td>
            <td><?= htmlspecialchars($h['nom_hotel']) ?></td>
            <td><?= htmlspecialchars($h['adresse_hotel']) ?></td>
            <td><?= htmlspecialchars($h['prix']) ?></td>
            <td><?= htmlspecialchars($h['prix_supplement_petit_dejeuner']) ?></td>
            <td><?= htmlspecialchars($h['etoile']) ?></td>
            <td><?= htmlspecialchars($h['chambre_disponible']) ?></td>
            <td>
              <a class="btn" href="?action=formupdate&id=<?= (int)$h['id_hotel'] ?>">Modifier</a>
              <form action="?action=deletehotel" method="POST" style="display:inline">
                <input type="hidden" name="id_hotel" value="<?= (int)$h['id_hotel'] ?>">
                <button type="submit" class="btn danger">Supprimer</button>
              </form>
              <a class="btn" href="?action=choisirCongressiste&id_hotel=<?= (int)$h['id_hotel'] ?>">Attribuer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</main>
