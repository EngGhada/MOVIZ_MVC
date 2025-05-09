<?php require_once _TEMPLATEPATH_ . '/header.php'; ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-lg border-0">
        <div class="card-body p-4">
          <h2 class="mb-4 fw-bold text-success text-center">Connexion</h2>

          <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger text-center"><?= $error; ?></div>
          <?php endforeach; ?>

          <form method="POST">
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Mot de passe</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="d-grid">
              <input type="submit" name="loginUser" class="btn btn-success" value="Se connecter">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>