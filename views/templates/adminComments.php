<?php

    /**
     * Affichage des commentaires d'un article dans la partie admin.
     * Chaque commentaire a un bouton "Supprimer" qui mène à une page de confirmation.
     *
     * Variables reçues :
     * $article : l'article dont on affiche les commentaires.
     * $comments : les commentaires de cet article.
     */

?>

<div class="comments adminComments">
    <h2 class="commentsTitle">Commentaires de l'article « <?= htmlspecialchars($article->getTitle()) ?> »</h2>

    <?php if (empty($comments)) { ?>
        <p class="info">Aucun commentaire pour cet article.</p>
    <?php } else { ?>
        <ul>
            <?php foreach ($comments as $comment) { ?>
                <li>
                    <div class="smiley">☻</div>
                    <div class="detailComment">
                        <h3 class="info">Le <?= Utils::convertDateToFrenchFormat($comment->getDateCreation()) ?>, <?= htmlspecialchars($comment->getPseudo()) ?> a écrit :</h3>
                        <div class="content"><?= Utils::format($comment->getContent()) ?></div>
                    </div>
                    <a class="submit deleteComment" 
                       href="index.php?action=confirmDeleteComment&amp;id=<?= $comment->getId() ?>"
                       title="Supprimer le commentaire de <?= htmlspecialchars($comment->getPseudo()) ?>">Supprimer</a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

    <a class="submit backLink" href="index.php?action=monitoring">Retour au monitoring</a>
</div>