<?php require_once _ROOTPATH_ . '/templates/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
       <div class="col-md-8">

                <h2 class="mb-4" style="color: #28a745;">Modifier un film</h2>
                <!-- form starts here -->
                <form action="?controller=movie&action=edit&id=<?= $movie->getId(); ?>" method="POST" enctype="multipart/form-data" >
                    <input type="hidden" class="form-control" name="action" value="edit">

                    <div class="mb-3">
                        <label for="title" class="form-label  fw-semibold" style="color: #28a745;">Titre:</label>
                        <input type="text" class="form-control" name="title" id="title" value="<?= htmlspecialchars($movie->getTitle()); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="synopsis" class="form-label fw-semibold" style="color:  #28a745;">Synopsis:</label>
                        <textarea  class="form-control" name="synopsis" id="synopsis" rows="5" required><?= htmlspecialchars($movie->getSynopsis()); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="release_date" class="form-label  fw-semibold" style="color: #28a745;">Date de sortie:</label>
                        <input type="date" class="form-control" name="release_date" id="release_date" value="<?= htmlspecialchars($movie->getReleaseDate()->format("Y-m-d")) ?>" required>
                    </div>

                    <div class="mb-3 ">
                        <label for="duration" class="form-label  fw-semibold" style="color: #28a745;">Durée:</label>
                        <input type="time"  class="form-control" name="duration" id="duration" step="1"  value="<?= htmlspecialchars($movie->getDuration()->format('H:i:s')) ?>" required>
                    </div>
 
                    <?php if ($movie->getImagePath()): ?>
                        <div class="mb-3">
                            <label class="form-label  fw-semibold " style="color: #28a745;">Image actuelle :</label>
                            <img src="<?= htmlspecialchars($movie->getImagePath()); ?>"  alt="Affiche actuelle" class="img-thumbnail" width="120">
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="image" class="form-label fw-semibold" style="color:  #28a745;" >Changer l'affiche (optionnel) :</label>
                        <input type="file" class="form-control" name="image" id="image" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label for="genres" class="form-label  fw-semibold" style="color: #28a745;">Genres:</label>
                        <select name="genres[]" id="genres" class="form-select"  multiple required>
                            <?php foreach ($genres as $genre): ?>
                                <option value="<?= $genre->getId(); ?>" 
                                    <?= in_array($genre->getId(), array_map(fn($g) => $g->getId(), $movie->getGenres())) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($genre->getName()); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                                       <!-- style="color:rgb(40, 120, 199); -->
                    <div class="mb-3 ">
                        <label for="directors" class="form-label  fw-semibold" style="color: #28a745;">Réalisateurs:</label> 
                        <select name="directors[]" id="directors" class="form-select" multiple required>
                            <?php foreach ($directors as $director): ?>
                                <option value="<?= $director->getId(); ?>" 
                                    <?= in_array($director->getId(), array_map(fn($d) => $d->getId(), $movie->getDirectors())) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($director->getFirstName() . ' ' . $director->getLastName()); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-success">Mettre à jour le film</button>
                    </div>
                                         
                </form>  

             </div>
        </div>
    </div>

<?php require_once _ROOTPATH_ . '/templates/footer.php'; ?>
