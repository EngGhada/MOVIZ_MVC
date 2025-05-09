<?php require_once _ROOTPATH_ . '/templates/header.php'; ?>
<?php /** @var App\Entity\Genre[] $genres */ ?>
<?php /** @var App\Entity\Director[] $directors */ ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-body">
          <h2 class="mb-4 text-success">Ajouter un nouveau film</h2>

          <form action="?controller=movie&action=create" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="create">

            <div class="mb-3">
              <label for="title" class="form-label fw-semibold text-success">Titre :</label>
              <input type="text" class="form-control" name="title" id="title" required>
            </div>

            <div class="mb-3">
              <label for="synopsis" class="form-label fw-semibold text-success">Synopsis :</label>
              <textarea name="synopsis" id="synopsis" class="form-control" rows="5" required></textarea>
            </div>

            <div class="mb-3">
              <label for="release_date" class="form-label fw-semibold text-success">Date de sortie :</label>
              <input type="date" class="form-control" name="release_date" id="release_date" required>
            </div>

            <div class="mb-3">
              <label for="duration" class="form-label fw-semibold text-success">Durée :</label>
              <input type="time" class="form-control" name="duration" id="duration" step="1" required>
            </div>

            <div class="mb-3">
              <label for="image" class="form-label fw-semibold text-success">Affiche (image) :</label>
              <input type="file" class="form-control" name="image" id="image" accept="image/*" required>
            </div>

            <div class="mb-3">
              <label for="genres" class="form-label fw-semibold text-success">Genres :</label>
              <select name="genres[]" id="genres" class="form-select" multiple required>

                <?php foreach ($genres as $genre): ?>
                  <option value="<?= $genre->getId(); ?>">
                    <?= htmlspecialchars($genre->getName()); ?>
                  </option>
                <?php endforeach; ?>

              </select>
            </div>

            <div class="mb-3">
              <label for="directors" class="form-label fw-semibold text-success">Réalisateurs :</label>
              <select name="directors[]" id="directors" class="form-select" multiple required>

                <?php foreach ($directors as $director): ?>
                  <option value="<?= $director->getId(); ?>">
                    <?= htmlspecialchars($director->getFirstName() . ' ' . $director->getLastName()); ?>
                  </option>
                <?php endforeach; ?>  
                
              </select>       
            </div>
            <div class="mb-3 text-center">
              <button type="submit" class="btn btn-success w-50">Ajouter le film</button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once _ROOTPATH_ . '/templates/footer.php'; ?>
