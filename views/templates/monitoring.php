<?php
    /**
     * Affichage de la page de monitoring : un tableau avec, pour chaque article,
     * son titre, sa date de publication, son nombre de vues et son nombre de commentaires.
     * Le titre de chaque colonne est un lien qui permet de trier le tableau.
     *
     * Variables reçues :
     * $articles : les articles déjà triés.
     * $columns : les colonnes du tableau (clé utilisée dans l'URL => titre affiché).
     * $column : la colonne actuellement triée.
     * $order : l'ordre actuel (asc ou desc).
     */
?>

<h2>Monitoring des articles</h2>

<table class="monitoringTable">
    <caption>Cliquez sur le titre d'une colonne pour trier le tableau.</caption>
    <thead>
        <tr>
            <?php foreach ($columns as $key => $label) { 
                // Par défaut, un clic sur une colonne la trie en ordre croissant.
                $nextOrder = 'asc';
                $arrow = '↕';
 
                // Si la colonne est déjà triée, on affiche son sens et un clic inverse l'ordre.
                if ($key === $column) {
                    $nextOrder = ($order === 'asc') ? 'desc' : 'asc';
                    $arrow = ($order === 'asc') ? '▲' : '▼';
                }
 
                $nextOrderName = ($nextOrder === 'asc') ? 'croissant' : 'décroissant';
            ?>
                <th scope="col">
                    <a href="index.php?action=monitoring&amp;column=<?= $key ?>&amp;order=<?= $nextOrder ?>"
                       title="Trier par <?= $label ?> en ordre <?= $nextOrderName ?>">
                        <?= $label ?> <?= $arrow ?>
                    </a>
                </th>
            <?php } ?>
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