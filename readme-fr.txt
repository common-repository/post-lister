=== Post-lister ===
Contributors: Darathor
Donate link: http://wp.darathor.com/
Tags: posts, comments, related, tags, post-plugins, query, inside, wp query, list, WP_Query, custom, widget, shortcode
Requires at least: 2.5
Tested up to: 2.7
Stable tag: 0.4

Affiche des listes personnalisées d'articles ou de commentaires.

== Description ==

Ce plugin, s'inspirant du plugin "Query Inside Post" de k-ny affiche des *listes personnaliées* d'**articles** ou de **commentaires** dans un article ou dans la barre latérale.

**Ce plugin nécessite PHP5**. Reportez-vous à la F.A.Q. pour plus de détails.

= Historique des versions =

* 0.5
	* [NEW] ajout de deux méthodes dans WlistBlock et ClistBlock pour récupérer la valeur d'un champ personnalisé où les shortcodes sont traduits (cf F.A.Q.).
	* [NEW] ajout au shortcode wlist du paramètre "showtitle" permettant d'afficher ou non les titres. Cet attribut étant d'un usage très limité et afin de ne pas surcharger le formulaire d'édition du widget, celui-ci n'est présent que pour la version shortcode.
	* [FIX] les nom des auteurs des articles et commentaires s'affichent à nouveau (si l'option est activée).
	* [FIX] rétablissement du fonctionnement de l'attribut "showposts". Celui-ci est cependant déprécié au profit de "limit" (pour homogénéiser les paramètres entre les deux listes), il est donc vivement conseillé de n'utiliser que ce dernier.
	* [FIX] les dates sont maintenant affichées avec le format défini dans la configuration de WordPress.

* 0.4
	* [NEW] ajout de champs permettant d'afficher le contenu des articles et commentaires, ainsi que l'avatar de leur auteur.
	* [ENHANCE] le champ "trier par" des formulaires des widgets est maintena tun menu déroulant plutôt qu'un champ texte.
	* [ENHANCE] les formulaires de configuration des widgets sont maintenant présentés sur deux colonnes.
	* [ENHANCE] le lien dans les listes de commentaires mène maintenant directement au commentaire et non plus en haut de la page.
	* [FIX] cliquer sur "annuler" dans le formulaire d'un widget puis enregistrer les modifications n'efface plus les configurations du widget.
	* [DOC] ajout du fichier readme-fr.txt contenant la documentation française du plugin.
	* [DOC] mise à jour du fichier readme.txt.

* 0.3
	* [NEW] ajout de la possibilité d'utiser des variables globales, fonctions et méthodes statiques dans la configuration des widgets.
	* [FIX] le nombre de commentaires n'était plus affichable dans les listes d'articles.

* 0.2
	* [NEW] ajout de la possibilité d'affucher des listes de commentaires.
	* [ENHANCE] séparation des attributs swhowdate et swhowtime pour plus de felxibilité.
	* [MISC] refactorisation du code pour utiliser mon générateur de plugin maison (qui est toujours en développement).
	
* 0.1
	* [NEW] version initiale.

== Installation ==

1. Upload le plugin dans le dossier /wp-content/plugins/.
1. Rendez-vous dans la section **Extensions** et activez le plugin.
1. Insérez un tag `[wlist]` ou `[clist]` dans vos message à l'endroit où vous désirez afficher un liste d'article ou de commentaires ou bien allez dans la section **Widgets** pour ajouter une liste d'articles ou de commantairesdans votre barre latérale.

== Questions fréquentes ==

= Liste des propriétés d'une liste d'articles (wlist) =

* **cat:** Vide par défaut. Permet de preciser l'ID de la catégorie (ex: `[wlist cat=5]` ou `[wlist cat="5,6,7"]` ou `[wlist cat=-5]`)
* **category_name:** Vide par défaut. Permet de préciser le nom de la catégorie (ex: `[wlist category_name="Web Design"]`)
* **tag:** Vide par défaut. Permet de préciser le nom (slug) du tag (ex: `[wlist tag=vie-personelle]` ou `[wlist tag="vie-personelle,photos"]` ou `[wlist tag="vie-personelle+photos"]`)
* **author:** Vide par défaut. Permet de préciser l'ID de l'auteur (ex: `[wlist author=1]` ou `[wlist author=1,2]` ou `[wlist author=-1]`)
* **author_name:** Vide par défaut. Permet de préciser le nom de l'auteur (ex: `[wlist author_name="Julien"]`)
* **limit:** 5 par défaut. Permet de préciser le nombre d'éléments a retourner (ex: `[wlist limit=10]` ou `[wlist limit=-1]` pour aucune limite)
* **offset:** Vide par défaut. Permet de préciser l'offset (ex: `[wlist offset=3]`)
* **order:** `desc` par défaut. Permet de préciser l'ordre de tri (ex: `[wlist order=asc]` ou `[wlist order=desc]`)
* **orderby:** `date` par défaut. Permet de préciser le paramètre de tri (ex: `[wlist orderby=title]`). Les valeurs disponibles sont les suivantes :
 * `date` : le tri s'effectue selon la date de l'article
 * `author` : le tri s'effectue selon le pseudonyme de l'auteur de l'article
 * `title` : le tri s'effectue selon le titre de l'article
 * `modified` : le tri s'effectue selon la date de dernière modification de l'article
 * `rand` : le tri s'effectue de manière aléatoire
* **beforelist:** `<ul class="wlist">` par défaut. Permet de préciser la tag se trouvant au début de la liste (ex: `[wlist beforelist="<ol>"]`)
* **afterlist:** `</ul>` par défaut. Permet de préciser la tag se trouvant à la fin de la liste (ex: `[wlist afterlist="</ol>"]`)
* **beforeitem:** `<li>` par défaut. Permet de préciser la tag se trouvant au début de chaque élément (ex: `[wlist beforeitem="<li><p>"]`)
* **afteritem:** `</li>` par défaut. Permet de préciser la tag se trouvant à la fin de chaque élément (ex: `[wlist afteritem="</p></li>"]`)
* **showauthor:** `false` par défaut. Permet d'afficher ou non l'auteur des messages listés (ex: `[wlist showauthor=true]`)
* **showdate:** `false` par défaut. Permet d'afficher ou non la date des messages listés (ex: `[wlist showdate=true]`)
* **showtime:** `false` par défaut. Permet d'afficher ou non l'heure des messages listés (ex: `[wlist showtime=true]`)
* **showcommentcount:** `false` par défaut. Permet d'afficher ou non le nombre de commentaires des messages listés (ex: `[wlist showcommentcount=true]`)
* **showauthoravatar:** `none` par défaut. Permet d'afficher ou non l'avatar de l'auteur en spécifiant sa taille (ex: `[wlist showauthoravatar=32]` ou `[wlist showauthoravatar=64]`).
* **showtext:** `none` par défaut. Permet d'afficher ou non le contenu des messages en spécifiant le nombre de caractères à afficher ou 'all' pour afficher le message complet (ex: `[wlist showtext=50]` ou `[wlist showtext=all]`).
* **showtitle:** `true` par défaut. Permet d'afficher ou non le titre des messages listés (ex: `[wlist showtitle=false]`)

= Liste des propriétés d'une liste de commentaires (clist) =

* **post:** Vide par défaut. Permet de preciser l'ID de l'article auquel doivent être rattachés les commentaires (ex: `[clist post=5]`)
* **author:** Vide par défaut. Permet de préciser l'ID de l'auteur (ex: `[clist author=1]`)
* **author_name:** Vide par défaut. Permet de préciser le nom de l'auteur (ex: `[clist author_name="Julien"]`)
* **author_email:** Vide par défaut. Permet de préciser l'adresse email de l'auteur (ex: `[clist author_email="chuck.norris@roundhouse-kick.com"]`)
* **limit:** 5 par défaut. Permet de préciser le nombre d'éléments a retourner (ex: `[clist limit=10]` ou `[clist limit=-1]` pour aucune limite)
* **offset:** Vide par défaut. Permet de préciser l'offset (ex: `[clist offset=3]`)
* **order:** `desc` par défaut. Permet de préciser l'ordre de tri (ex: `[clist order=asc]` ou `[clist order=desc]`)
* **orderby:** `comment_date` par défaut. Permet de préciser le paramètre de tri (ex: `[clist orderby=comment_author]`). Les valeurs disponibles sont les suivantes :
 * `comment_date` : le tri s'effectue selon la date du commentaire
 * `comment_author` : le tri s'effectue selon le pseudonyme de l'auteur du commentaire
 * `comment_post_ID` : le tri s'effectue selon l'id de l'article associé au commentaire
 * `rand` : le tri s'effectue de manière aléatoire
* **beforelist:** `<ul class="clist">` par défaut. Permet de préciser la tag se trouvant au début de la liste (ex: `[clist beforelist="<ol>"]`)
* **afterlist:** `</ul>` par défaut. Permet de préciser la tag se trouvant à la fin de la liste (ex: `[clist afterlist="</ol>"]`)
* **beforeitem:** `<li>` par défaut. Permet de préciser la tag se trouvant au début de chaque élément (ex: `[clist beforeitem="<li><p>"]`)
* **afteritem:** `</li>` par défaut. Permet de préciser la tag se trouvant à la fin de chaque élément (ex: `[clist afteritem="</p></li>"]`)
* **showauthor:** `false` par défaut. Permet d'afficher ou non l'auteur des messages listés (ex: `[clist showauthor=true]`)
* **showdate:** `false` par défaut. Permet d'afficher ou la date des messages listés (ex: `[clist showdate=true]`)
* **showtime:** `false` par défaut. Permet d'afficher ou l'heure des messages listés (ex: `[clist showtime=true]`)
* **showauthoravatar:** `none` par défaut. Permet d'afficher l'avatar de l'auteur en spécifiant sa taille (ex: `[wlist showauthoravatar=32]` ou `[wlist showauthoravatar=64]`).
* **showtext:** `none` par défaut. Permet d'afficher le contenu des messages en spécifiant le nombre de caractères à afficher ou 'all' pour afficher le message complet (ex: `[wlist showtext=50]` ou `[wlist showtext=all]`).

= Utilisation de valeurs dynamiques dans la configuation des widgets =

Réservés aux utilisateurs avertis :

* **utiliser une variable globale :** saisir `${toto}` va appeler `$toto`.
* **utiliser un champ d'une variable globale :** saisir`${toto->name}` va appeler `$toto->name`.
* **utiliser une fonction :** saisir`${aFunction()}` va appeler `aFunction()` (sans argument).
* **utiliser une méthode statique :** saisir`${SomeClass::someStaticMethod()}` va appeler `SomeClass::someStaticMethod()` (sans argument). 

= PHP m'affiche une erreur fatale lorsque j'active le plugin ! =

Ce plugin nécessite PHP5. Chez certains hébergeurs, la version de PHP associée par défaut à l'extension .php est PHP4. Pour activer PHP5, vous devrez le plus souvent ajouter quelques lignes dans votre fichier .htaccess.  

= Le lien présent dans les lists de commentaires ne mène pas aux commentaires mais juste en haut de la page =

Vérifiez que le fichier `comments.php` de votre thème définit correctement l'attribut `id` à `comment-xxx` où `xxx` est l'identifiant du commentaire.

= Comment intégrer un shortcode dans un champ personnalisé ? =

Les classes WlistBlock et ClistBlock proposent deux méthodes statiques permettant de récupérer la valeur d'un champ personnalisé de l'article courant en y remplaçant les shortcodes par leur valeur :
* getShortcodedCustomFieldValue($fieldName) : retourne la valeur du champ indiqué après y avoir remplacé les <em>shotcode
* echoShortcodedCustomFieldValue($fieldName) : affiche la valeur du champ indiqué après y avoir remplacé les <em>shotcode
</ul>
Pour afficher cette valeur dans un template, il suffit d'appeler la ligne suivante en indiquant le bon nom de champ (dans l'exemple, le champ s'appelle <em>toto</em>) : `<?php WlistBlock::echoShortcodedCustomFieldValue('toto'); ?>`

== Captures d'écran ==

1. Configuration du widget correspondant à une liste d'articles.
1. Configuration du widget correspondant à une liste de commentaires.
1. Un widget affichant une liste d'articles
1. Un widget affichant une liste de commentaires.
1. Ajout d'une liste d'articles dans un article ou une page.
1. Rendu d'une liste d'articles dans un article ou une page.