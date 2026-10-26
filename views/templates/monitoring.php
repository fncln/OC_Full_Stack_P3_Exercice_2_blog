<?php
    /**
     * Affichage de la page de monitoring : un tableau avec, pour chaque article,
     * son titre, sa date de publication, son nombre de vues et son nombre de commentaires.
     */
?>

<h2>Monitoring des articles</h2>

<table class="monitoringTable">
    <caption>Statistiques de chaque article</caption>
    <thead>
        <tr>
            <th scope="col">Titre</th>
            <th scope="col">Date de publication</th>
            <th scope="col" class="number">Nombre de vues</th>
            <th scope="col" class="number">Nombre de commentaires</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $article) { ?>
            <tr>
                <td><?= htmlspecialchars($article->getTitle()) ?></td>
                <td><?= ucfirst(Utils::convertDateToFrenchFormat($article->getDateCreation())) ?></td>
                <td class="number"><?= $article->getViews() ?></td>
                <td class="number"><?= $article->getCommentCount() ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>