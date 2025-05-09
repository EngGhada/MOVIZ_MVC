use
<?php 
require_once _ROOTPATH_ . '/templates/header.php'; 
use App\Security\Security;
?>

    <div class="container hero">
        <h1>Bienvenue sur Moviz 🎬</h1>
        <p>Explorez les meilleurs films, retrouvez vos réalisateurs préférés, et plongez dans l’univers du cinéma.</p>
        <a href="?controller=movie&action=list" class="btn btn-moviz btn-lg mt-3">Voir les films</a>
            <?php if (Security::isAdmin()) : ?>
             <a href="/?controller=movie&action=create" class="btn btn-moviz btn-lg mt-3">➕Ajouter un film</a>
             <?php endif; ?>

    </div>


<?php require_once _ROOTPATH_ . '/templates/footer.php'; ?>

