<?php
// attend : $idHotel, $congressistes, $assignedCongressistes, $status
?>
<main class="container">
  <h1>Attribuer l’hôtel #<?= (int)$idHotel ?> à un congressiste</h1>

  <?php if ($status === 'assign_already'): ?>
    <div class="flash err">Une hotel a déjà été attribué a ce congressiste</div>
  <?php elseif ($status === 'unassigned_ok'): ?>
    <div class="flash ok">Désattribution effectuée</div>
  <?php elseif ($status === 'unassigned_err'): ?>
    <div class="flash err">Impossible de désattribuer ce congressiste</div>
  <?php endif; ?>

  <!-- Formulaire d'attribution -->
  <form action="?action=attribuerHotel" method="POST" class="card" style="padding:12px;max-width:560px;margin-bottom:18px">
    <input type="hidden" name="id_hotel" value="<?= (int)$idHotel ?>">
    <label for="id_congressiste"><strong>Choisir un congressiste à attribuer</strong></label>
    <select name="id_congressiste" id="id_congressiste" required style="width:100%;margin-top:6px">
      <option value="" disabled selected>— Sélectionner —</option>
      <?php foreach ($congressistes as $c): ?>
        <?php
          $hasHotel = !empty($c['id_hotel']);
          $label = htmlspecialchars($c['nom'].' '.$c['prenom'], ENT_QUOTES, 'UTF-8');
        ?>
        <option value="<?= (int)$c['id_congressiste'] ?>" <?= $hasHotel ? 'disabled' : '' ?>>
          <?= $label ?> <?= $hasHotel ? ' (déjà attribué)' : '' ?>
        </option>
      <?php endforeach; ?>
    </select>
    <div style="margin-top:12px;display:flex;gap:8px">
      <a class="btn" href="?action=hotels">← Retour</a>
      <button type="submit" class="btn primary">Attribuer</button>
    </div>
  </form>

  <!-- Formulaire de désattribution -->
  <form action="?action=unassignHotel" method="POST" class="card" style="padding:12px;max-width:560px">
    <input type="hidden" name="id_hotel" value="<?= (int)$idHotel ?>">
    <label for="id_congressiste_unset"><strong>Désattribuer un congressiste déjà logé</strong></label>
    <select name="id_congressiste" id="id_congressiste_unset" required style="width:100%;margin-top:6px">
      <option value="" disabled selected>— Sélectionner —</option>
      <?php if (empty($assignedCongressistes)): ?>
        <option value="" disabled>Aucun congressiste attribué</option>
      <?php else: ?>
        <?php foreach ($assignedCongressistes as $c): ?>
          <?php
            $label = htmlspecialchars($c['nom'].' '.$c['prenom'].' — Hôtel #'.$c['id_hotel'].' ('. $c['nom_hotel'] .')', ENT_QUOTES, 'UTF-8');
          ?>
          <option value="<?= (int)$c['id_congressiste'] ?>"><?= $label ?></option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
    <div style="margin-top:12px;display:flex;gap:8px">
      <button type="submit" class="btn danger">Désattribuer</button>
    </div>
  </form>
</main>
