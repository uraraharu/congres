<main class="container">
  <h1>Attribuer l’hôtel #<?= (int)$idHotel ?> à un congressiste</h1>

  <?php if ($status === 'assign_already'): ?>
    <div class="flash err">Une hotel a déjà été attribué a ce congressiste</div>
  <?php elseif ($status === 'unassigned_ok'): ?>
    <div class="flash ok">Désattribution effectuée</div>
  <?php elseif ($status === 'unassigned_err'): ?>
    <div class="flash err">Impossible de désattribuer ce congressiste</div>
  <?php endif; ?>

  <!-- Barre de recherche + liste pour ATTRIBUER -->
  <form action="?action=attribuerHotel" method="POST" class="card" style="padding:12px;max-width:560px;margin-bottom:18px">
    <input type="hidden" name="id_hotel" value="<?= (int)$idHotel ?>">

    <label for="searchAttribuer"><strong>Rechercher un congressiste à attribuer</strong></label>
    <input type="text" id="searchAttribuer" placeholder="Tapez un nom..." style="width:100%;margin:6px 0;padding:6px;">

    <select name="id_congressiste" id="id_congressiste" required size="6" style="width:100%;margin-top:6px">
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

  <!-- Barre de recherche + liste pour DÉSATTRIBUER -->
  <form action="?action=unassignHotel" method="POST" class="card" style="padding:12px;max-width:560px">
    <input type="hidden" name="id_hotel" value="<?= (int)$idHotel ?>">

    <label for="searchDesattribuer"><strong>Rechercher un congressiste déjà logé</strong></label>
    <input type="text" id="searchDesattribuer" placeholder="Tapez un nom..." style="width:100%;margin:6px 0;padding:6px;">

    <select name="id_congressiste" id="id_congressiste_unset" required size="6" style="width:100%;margin-top:6px">
      <?php if (empty($assignedCongressistes)): ?>
        <option value="" disabled>Aucun congressiste attribué</option>
      <?php else: ?>
        <?php foreach ($assignedCongressistes as $c): ?>
          <?php
            $label = htmlspecialchars($c['nom'].' '.$c['prenom'].' — Hôtel #'.$c['id_hotel'].' ('.$c['nom_hotel'].')', ENT_QUOTES, 'UTF-8');
          ?>
          <option value="<?= (int)$c['id_congressiste'] ?>"><?= $label ?></option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>

    <div style="margin-top:12px;display:flex;gap:8px">
      <button type="submit" class="btn danger">Désattribuer</button>
    </div>
  </form>

  <!-- Script JS de filtrage -->
  <script>
    function filterOptions(inputId, selectId) {
      const input = document.getElementById(inputId);
      const select = document.getElementById(selectId);

      input.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase().trim();

        for (const opt of select.options) {
          const txt = opt.text.toLowerCase();
          opt.style.display = txt.includes(filter) ? '' : 'none';
        }
      });
    }

    // Activation des deux filtres
    filterOptions('searchAttribuer', 'id_congressiste');
    filterOptions('searchDesattribuer', 'id_congressiste_unset');
  </script>
</main>
