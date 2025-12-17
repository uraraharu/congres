<main class="container">
  <h1>Créer un hôtel</h1>

  <?php if (!empty($flash_error)): ?>
    <div class="flash err"><?= $flash_error ?></div>
  <?php endif; ?>

  <form action="?action=creerhotel" method="POST" autocomplete="off">
    <fieldset>
      <legend>Créer un hôtel</legend>

      <label for="nom_hotel">Nom de l’hôtel</label>
      <input type="text" id="nom_hotel" name="nom_hotel" required maxlength="255">

      <label for="adresse_hotel">Adresse</label>
      <input type="text" id="adresse_hotel" name="adresse_hotel" required maxlength="255">

      <label for="prix">Prix (€/nuit)</label>
      <input type="number" id="prix" name="prix" required step="0.01" min="0">

      <label for="prix_supplement_petit_dejeuner">Supplément petit-déjeuner (€)</label>
      <input type="number" id="prix_supplement_petit_dejeuner" name="prix_supplement_petit_dejeuner" required step="0.01" min="0">

      <label for="etoile">Étoiles</label>
      <input type="number" id="etoile" name="etoile" required min="0" max="5" step="1">

      <label for="chambre_disponible">Chambres disponibles</label>
      <input type="number" id="chambre_disponible" name="chambre_disponible" required min="0" step="1">

      <button type="submit" class="btn">Créer</button>
      <a href="?action=hotels" class="btn">Annuler</a>
    </fieldset>
  </form>
</main>
