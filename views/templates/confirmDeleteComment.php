<?php

    /**
     * Page de confirmation avant la suppression d'un commentaire.
     * La suppression se fait avec un formulaire en POST (et pas avec un simple lien),
     * pour qu'un lien piégé ne puisse pas supprimer un commentaire à l'insu de l'administratrice.
     *
     * Variable reçue :
     * $comment : le commentaire à supprimer.
     */

?>

<form action="index.php" method="post" class="foldedCorner">
    <h2>Supprimer ce commentaire ?</h2>

    <p>Commentaire de <?= htmlspecialchars($comment->getPseudo()) ?>, publié le <?= Utils::convertDateToFrenchFormat($comment->getDateCreation()) ?> :</p>
    <?= Utils::format($comment->getContent()) ?>
    <p class="warningMessage">Attention, cette action est définitive.</p>

    <input type="hidden" name="action" value="deleteComment">
    <input type="hidden" name="id" value="<?= $comment->getId() ?>">

    <div class="confirmButtons">
        <button type="submit" class="submit">Confirmer la suppression</button>
        <a class="submit" href="index.php?action=showComments&amp;id=<?= $comment->getIdArticle() ?>">Annuler</a>
    </div>
</form>