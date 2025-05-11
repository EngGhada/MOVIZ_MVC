<?php require_once _TEMPLATEPATH_ . '/header.php'; ?>
<?php /** @var App\Entity\User $user */ ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg border-0">
        <div class="card-body px-4 py-5">
          <h2 class="text-center text-success mb-4">Inscription</h2>

          <form method="POST">
          <!-- Prénom -->
          <div class="mb-3">
            <label for="first_name" class="form-label fw-semibold text-dark">Prénom</label>
            <input type="text" name="first_name" id="first_name" class="form-control" value="">
          </div>

          <!-- Nom -->
          <div class="mb-3">
            <label for="last_name" class="form-label fw-semibold text-dark">Nom</label>
            <input type="text" name="last_name" id="last_name" class="form-control" value="">
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label for="email" class="form-label fw-semibold text-dark">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="">
          </div>

          <!-- Mot de passe -->
          <div class="mb-4">
            <label for="password" class="form-label fw-semibold text-dark">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control">
          </div>

          <!-- Submit -->
          <div class="d-grid">
            <button type="submit" name="saveUser" class="btn btn-success">Enregistrer</button>
          </div>
        </form>


        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>
