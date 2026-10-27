<?php 
/**
 * Contrôleur de la partie admin.
 */
 
class AdminController {

    /**
     * Affiche la page d'administration.
     * @return void
     */
    public function showAdmin() : void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();

        // On récupère les articles.
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        // On affiche la page d'administration.
        $view = new View("Administration");
        $view->render("admin", [
            'articles' => $articles
        ]);
    }

     /**
     * Affiche la page de monitoring : les statistiques de chaque article.
     * @return void
     */
    public function showMonitoring() : void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();

        // Les colonnes triables : la clé est utilisée dans l'URL, la valeur est affichée dans le tableau.
        $columns = [
            'title' => 'Titre',
            'date' => 'Date de publication',
            'views' => 'Nombre de vues',
            'comments' => 'Nombre de commentaires'
        ];
 
        // On récupère le tri demandé dans l'URL (par défaut : les articles les plus récents en premier).
        $column = Utils::request('column', 'date');
        $order = Utils::request('order', 'desc');
 
        // Si une valeur de l'URL est incorrecte, on la remplace par la valeur par défaut.
        $column = in_array($column, array_keys($columns), true) ? $column : 'date';
        $order = in_array($order, ['asc', 'desc'], true) ? $order : 'desc';
 
        // On récupère les articles avec leur nombre de commentaires, puis on les trie.
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticlesWithCommentCount();
        $articles = $this->sortArticles($articles, $column, $order);

        // On affiche la page de monitoring.
        $view = new View("Monitoring");
        $view->render("monitoring", [
            'articles' => $articles,
            'columns' => $columns,
            'column' => $column,
            'order' => $order
        ]);
    }

    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function checkIfUserIsConnected() : void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    /**
     * Trie un tableau d'articles selon une colonne et un ordre.
     * @param array $articles : le tableau d'objets Article à trier.
     * @param string $column : la colonne de tri (title, date, views ou comments).
     * @param string $order : l'ordre de tri (asc ou desc).
     * @return array : le tableau trié.
     */
    private function sortArticles(array $articles, string $column, string $order) : array
    {
        // Le Collator compare les textes comme en français (accents et majuscules bien gérés).
        $collator = new Collator('fr_FR');
 
        // usort() trie en appelant cette fonction sur deux articles à la fois.
        // Elle renvoie un nombre négatif si le premier passe avant le second, 0 si égalité, positif sinon.
        usort($articles, function (Article $first, Article $second) use ($column, $collator) {
            return match ($column) {
                'title' => $collator->compare($first->getTitle(), $second->getTitle()),
                'date' => $first->getDateCreation() <=> $second->getDateCreation(),
                'views' => $first->getViews() <=> $second->getViews(),
                'comments' => $first->getCommentCount() <=> $second->getCommentCount()
            };
        });
 
        // Le tri est croissant par défaut : pour l'ordre décroissant, on inverse le tableau.
        if ($order === 'desc') {
            $articles = array_reverse($articles);
        }
 
        return $articles;
    }

    /**
     * Affichage du formulaire de connexion.
     * @return void
     */
    public function displayConnectionForm() : void 
    {
        $view = new View("Connexion");
        $view->render("connectionForm");
    }

    /**
     * Connexion de l'utilisateur.
     * @return void
     */
    public function connectUser() : void 
    {
        // On récupère les données du formulaire.
        $login = Utils::request("login");
        $password = Utils::request("password");

        // On vérifie que les données sont valides.
        if (empty($login) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires. 1");
        }

        // On vérifie que l'utilisateur existe.
        $userManager = new UserManager();
        $user = $userManager->getUserByLogin($login);
        if (!$user) {
            throw new Exception("L'utilisateur demandé n'existe pas.");
        }

        // On vérifie que le mot de passe est correct.
        if (!password_verify($password, $user->getPassword())) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            throw new Exception("Le mot de passe est incorrect : $hash");
        }

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getId();

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser() : void 
    {
        // On déconnecte l'utilisateur.
        unset($_SESSION['user']);

        // On redirige vers la page d'accueil.
        Utils::redirect("home");
    }

    /**
     * Affichage du formulaire d'ajout d'un article.
     * @return void
     */
    public function showUpdateArticleForm() : void 
    {
        $this->checkIfUserIsConnected();

        // On récupère l'id de l'article s'il existe.
        $id = Utils::request("id", -1);

        // On récupère l'article associé.
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($id);

        // Si l'article n'existe pas, on en crée un vide. 
        if (!$article) {
            $article = new Article();
        }

        // On affiche la page de modification de l'article.
        $view = new View("Edition d'un article");
        $view->render("updateArticleForm", [
            'article' => $article
        ]);
    }

    /**
     * Ajout et modification d'un article. 
     * On sait si un article est ajouté car l'id vaut -1.
     * @return void
     */
    public function updateArticle() : void 
    {
        $this->checkIfUserIsConnected();

        // On récupère les données du formulaire.
        $id = Utils::request("id", -1);
        $title = Utils::request("title");
        $content = Utils::request("content");

        // On vérifie que les données sont valides.
        if (empty($title) || empty($content)) {
            throw new Exception("Tous les champs sont obligatoires. 2");
        }

        // On crée l'objet Article.
        $article = new Article([
            'id' => $id, // Si l'id vaut -1, l'article sera ajouté. Sinon, il sera modifié.
            'title' => $title,
            'content' => $content,
            'id_user' => $_SESSION['idUser']
        ]);

        // On ajoute l'article.
        $articleManager = new ArticleManager();
        $articleManager->addOrUpdateArticle($article);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }


    /**
     * Suppression d'un article.
     * @return void
     */
    public function deleteArticle() : void
    {
        $this->checkIfUserIsConnected();

        $id = Utils::request("id", -1);

        // On supprime l'article.
        $articleManager = new ArticleManager();
        $articleManager->deleteArticle($id);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }
}