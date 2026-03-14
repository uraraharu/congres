<?php
$status = $_GET['status'] ?? null;
$logged = !empty($_SESSION['congressiste']);

$etoileFiltre = $_GET['etoile'] ?? '';
$etoileOp = $_GET['etoile_op'] ?? '>=';
$chambreFiltre = $_GET['chambres'] ?? '';
$chambreOp = $_GET['chambres_op'] ?? '>=';
?>
<main class="container">
  <h1>Liste des hôtels</h1>

  <?php if ($etoileFiltre !== ''): ?>
    <div class="flash ok">Filtre appliqué : hôtels avec <?= $etoileOp === '>=' ? 'au moins' : 'au plus' ?> <?= htmlspecialchars($etoileFiltre) ?> ★</div>
  <?php endif; ?>

  <?php if ($chambreFiltre !== ''): ?>
    <div class="flash ok">Filtre appliqué : <?= $chambreOp === '>=' ? 'au moins' : 'au plus' ?> <?= htmlspecialchars($chambreFiltre) ?> chambres disponibles</div>
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

  <!-- Formulaire de filtrage -->
  <form method="GET" action="" class="card" style="padding:10px;margin-bottom:16px;display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
    <input type="hidden" name="action" value="hotels">
    
    <div>
      <label for="etoile">Étoiles</label><br>
      <div style="display:flex;gap:4px;">
        <select name="etoile_op" style="padding:4px;">
          <option value=">=" <?= $etoileOp === '>=' ? 'selected' : '' ?>>≥</option>
          <option value="<=" <?= $etoileOp === '<=' ? 'selected' : '' ?>>≤</option>
        </select>
        <input type="number" name="etoile" id="etoile" min="1" max="5" value="<?= htmlspecialchars($etoileFiltre) ?>" style="width:70px;padding:4px;">
      </div>
    </div>

    <div>
      <label for="chambres">Chambres disponibles</label><br>
      <div style="display:flex;gap:4px;">
        <select name="chambres_op" style="padding:4px;">
          <option value=">=" <?= $chambreOp === '>=' ? 'selected' : '' ?>>≥</option>
          <option value="<=" <?= $chambreOp === '<=' ? 'selected' : '' ?>>≤</option>
        </select>
        <input type="number" name="chambres" id="chambres" min="0" value="<?= htmlspecialchars($chambreFiltre) ?>" style="width:70px;padding:4px;">
      </div>
    </div>

    <button type="submit" class="btn">Filtrer</button>
    <a href="?action=hotels" class="btn">Réinitialiser</a>
  </form>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Adresse</th>
        <th>Prix</th>
        <th>Petit-déj.</th>
        <th>Étoiles</th>
        <th>Chambres</th>
        <th>Actions</th>
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
