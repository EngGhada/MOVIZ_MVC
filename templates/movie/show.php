<?php
require_once _ROOTPATH_ . '/templates/header.php';
use App\Security\Security;
use App\Entity\Movie;
?>

<div class="container my-5 px-4">
    <h1 class="text-center fw-bold mb-4" style="color: #28a745;">
        <?= $movie->getTitle() ?>
    </h1>

    <div class="row g-5 align-items-start">

        <div class="col-md-7">
            <p><strong>Date de sortie :</strong> <?= $movie->getReleaseDate()->format('d/m/Y') ?></p>
            <p><strong>Durée :</strong> <?= $movie->getDuration()->format('H\hi') ?></p>
            <p><strong>Genres :</strong> <?= implode(',', array_map(fn($g) => $g->getName(), $genres)) ?></p>
            <p><strong>Réalisateurs :</strong> <?= implode(', ', array_map(fn($d) => $d->getFirstName() . ' ' . $d->getLastName(), $directors)) ?></p>

            <p class="mt-4 lh-lg"><?= $movie->getSynopsis() ?></p>
        </div>

        <div class="col-md-5 text-center">
            <img src="<?= $movie->getImagePath() ?>" alt="<?= $movie->getTitle() ?>" class="img-fluid rounded shadow-sm">
        </div>
    </div>


   <!-- Admin controle buttons  -->
    
   <div class="mt-5">
  <div class="row g-2 justify-content-between align-items-center">
    <div class="col-auto">

      <?php if (Security::isAdmin()) : ?>
        <a href="index.php?controller=movie&action=edit&id=<?= $movie->getId() ?>" class="btn btn-primary">
          <i class="bi bi-pencil-square"></i> Modifier
        </a>

        <a href="index.php?controller=movie&action=delete&id=<?= $movie->getId() ?>" 
           onclick="return confirm('Supprimer ce film ?')" 
           class="btn btn-danger">
          <i class="bi bi-trash"></i> Supprimer
        </a>
        
      <?php endif; ?>
    </div>
    <div class="col-auto">
      <a href="index.php?controller=movie&action=list" class="btn btn-outline-light text-dark">
        <i class="bi bi-arrow-left-circle"></i> Retour à la liste
      </a>
    </div>
  </div>
</div>


    <!-- Existing Reviews from MongoDB -->
    <div class="mt-5">
        <h3 class="text-success mb-4">Avis des utilisateurs</h3>
        <?php if (!empty($reviews)) : ?>
            
            <?php foreach ($reviews as $review): ?>

                <?php if (isset($editReview) && $editReview['_id'] == $review['_id']): ?>
                        <!-- Inline Edit Form -->
                <form method="POST" action="index.php?controller=review&action=update">
                        <input type="hidden" name="id" value="<?= $review['_id'] ?>">
                        <input type="hidden" name="movie_id" value="<?= $movie->getId() ?>">

                        <div class="mb-2">
                            <label>Note (/5)</label>
                            <input type="number" name="rate" min="1" max="5" value="<?= $review['rate'] ?>" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Commentaire</label>
                            <textarea name="review" class="form-control"><?= htmlspecialchars($review['review']) ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">💾 Enregistrer</button>
                        <a href="index.php?controller=movie&action=show&id=<?= $movie->getId() ?>" class="btn btn-secondary btn-sm">Annuler</a>
                    </form>

                <?php else: ?>
                <!-- Display Review -->

                <div class="card mb-4 shadow-sm border-0 rounded-4 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            <?= strtoupper(substr($review['user_name'], 0, 1)) ?>
                            </div>
                        </div>
                        <div>
                           
                            <small class="text-muted">
                            Ajouté le : <?= (new \DateTime('@' . $review['created_at']->toDateTime()->getTimestamp()))->format('d/m/Y H:i') ?>
                            </small>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-success">⭐ <?=$review['rate'] ?>/5</span>

                            <?php if (isset($_SESSION['user'])&&$_SESSION['user']['id'] === $review['user_id']) : ?>

                                <a href="index.php?controller=review&action=edit&id=<?= $review['_id'] ?>&movie_id=<?= $movie->getId() ?>" class="btn btn-sm btn-outline-secondary">✏️ Modifier</a>                                
                                <a href="index.php?controller=review&action=delete&id=<?= $review['_id'] ?>&movie_id=<?= $movie->getId() ?>" onclick="return confirm('Supprimer cet avis ?')" class="btn btn-sm btn-outline-danger">🗑️ Supprimer</a>
                           
                            <?php endif; ?>
                        </div>

                        </div>
                        <p class="mb-0 fs-5 text-dark">
                        <?= nl2br(htmlspecialchars($review['review'])) ?>
                        </p>
                      </div>
                  </div>
                <?php endif; ?>


                
            <?php endforeach; ?>


        <?php else : ?>
            <p class="text-muted fst-italic">Aucun avis pour ce film.</p>
        <?php endif; ?>
    </div>
   
    <!-- Add Review Form -->
    <?php if (Security::isUser()) : ?>
        <div class="mt-5">
            <h4 class="text-success">Ajouter un commentaire</h4>
            <form action="index.php?controller=review&action=create&movie_id=<?= $movie->getId() ?>" method="post">
                <div class="mb-3">
                    <label for="rate">Note (/5)</label>
                    <input type="number" class="form-control" name="rate" id="rate" min="0" max="5" required>
                </div>

                <div class="mb-3">
                    <label for="review">Votre avis</label>
                    <textarea class="form-control" name="review" id="review" rows="3" required></textarea>
                </div>

                <button type="submit" name="submitReview" class="btn btn-primary">Soumettre le commentaire</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php require_once _ROOTPATH_ . '/templates/footer.php'; ?>

