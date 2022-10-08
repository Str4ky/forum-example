<h2>Forum Example</h2>

Exemple complet de forum en PHP

__Fonctionnalités :__

<li>Création et connexion à un compte</li>
<li>Modification d'identifiant, d'email et de mot de passe ou suppression du compte</li>
<li>Création de catégories, rubriques et d'articles en étant administrateur</li>
<li>Suppression d'articles et de messages étant modérateur</li>
<li>Lecture d'articles et réponse à ceux-ci étant connecté</li>
<li>Administration du type et des permissions des membres ainsi que la suppression de comptes</li>

<br>

__Utilisation :__

Importer le fichier __forum.sql__ sur votre base de donnée à l'aide de votre outil favori

Dans le fichier __config/database.php__ changez ces 4 lignes avec les logins de votre base de données

```
$host = "localhost";
$bdd = "forum";
$user = "root";
$passwd = "";
```

<br>

__Type de membre :__

<li>Non connecté : Peut uniquement lire les articles et voir le profil d'un utilisateur</li>
<li>Utilisateur : Peut créer et répondres aux articles</li>
<li>Modérateur : Peut supprimer des articles ou des messages</li>
<li>Administrateur : Peut administrer les membres, créer, supprimer et modifier des catégories et des articles</li>

<br>

__Administration :__

Pour une première utilisation, il faut vous mettre en administrateur via la base de données, en modifiant votre compte
Vous pouvez le faire en modifiant la valeur de la colone "typeMemb" de la table "membre", la changeant de 2 à 0
Vous pouvez aussi le faire en réalisant cette requette SQL ```UPDATE membre SET typeMemb = 0 WHERE idMemb = 'Votre email';```

<br>

__Personalisation :__

Pour customiser le forul à votre goût, vous pouvez premièrement changer la couleur du site en changeant le bloc "root" dans `assets/css/style.css`

```css
:root {
    --main-color: #4c60cf;
    --background-color: #e7eaff;
    --main-button-color: #5a87e7;
    --main-button-hover-color: #6b93eb;
    --delete-logout-button-color: #cf2c2c;
    --delete-logout-button-hover-color: #e04747;
    --admin-button-color: #dd9611;
    --admin-button-hover-color: #eea928;
    --login-color: #4c60cf;
    --login-border-color: #3e50b3;
    --main-block-color: #5a87e7;
}
```

Vous pouvez aussi changer les logos se situant dans `assets/images`

Si les icônes de certification ou de type de membres ne vous plaisent pas, vous pouvez les changer dans ce fichier `config/badges.php`

```php
    $certif = 'fa-solid fa-badge-check';
    $mod = 'fa-solid fa-gears';
    $admin = 'fa-solid fa-shield-halved';
```
Il vous suffi de copier la classe d'une icône disponible sur <a href='https://fontawesome.com'>Font Awesome</a> et de la remplacer

Pour rajouter une icône dans la liste déroulante lors de la création ou l'édition d'une catégorie il faut aller dans les fichiers `admin/category/add/index.php` et `admin/category/edit/index.php` et rajouter cette ligne dans le \<select>

```html
<option value='classe FontAwesome'>Nom de l'icône</option>
```
Il faut renseigner la classe d'une icône FintAwesome ainsi qu'un nom pour l'icône

<br>

__Test du projet :__

Utilisez un logiciel de wamp tel que [Wamp server](https://www.wampserver.com/) par exemple
<br>
Mettez le dossier du projet dans le dossier __www__ du logiciel de Wamp et lancer le projet depuis votre navigateur web
<br><br>
Vous pouvez aussi l'hoster sur votre propre site web/serveur
