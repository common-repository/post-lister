=== Post-lister ===
Contributors: Darathor
Donate link: http://wp.darathor.com/
Tags: posts, comments, related, tags, post-plugins, query, inside, wp query, list, WP_Query, custom, widget, shortcode
Requires at least: 2.5
Tested up to: 2.8.4
Stable tag: 0.4

Displays a custom list of posts or comments.

== Description ==

This plugin, inspired on the plugin "Query Inside Post" by k-ny displays *custom lists* of **posts** or **comments** inside a post or the sidebar.

**This plugin requires PHP5**. Look at the FAQ for more details.

= Versions History =

* 0.5
	* [NEW] adding two methods in WlistBlock and ClistBlock to get the value of a custom field where shortcodes are translated (see FAQ).
	* [NEW] added a parameter "showtitle" to the shortcode wlist to display or not the titles. This attribute is of very limited use and in order not to overload the edit form of widget, it is only present for shortcode version.
	* [FIX] the names of authors of articles and comments appear again (if enabled). 
	* [FIX] fixed attribute "showposts". It is however deprecated in favor of "limit" (to standardize the parameters between the two lists), it is strongly advised to use only the latter.
	* [FIX] The dates are now displayed with the date format defined in the WordPress configuration.
	
* 0.4
	* [NEW] added two fields enabling to display post or comment content and the author's avatar.
	* [ENHANCE] widget forms "order by" fields is now a combo box instead of a text field.
	* [ENHANCE] widget forms have now 2 columns to avoid scrolling.
	* [ENHANCE] the link in comment lists now contains an anchor to the comment.
	* [FIX] click "cancel" on a widget form and save modifications no longer clear all the widget configurations.
	* [DOC] added readme-fr.txt files containing the french documentation for the plugin.
	* [DOC] update readme.txt.

* 0.3
	* [NEW] enable to use global variables, functions and static methods in the configuration of the widgets.
	* [FIX] the comment count was not displayed in post-list.

* 0.2
	* [NEW] added the comment list functionality.
	* [ENHANCE] separated showdate and showtime parameters for more flexibility.
	* [MISC] large refactoring to use my plugin generator (currently in development).
	
* 0.1
	* [NEW] first version.

== Installation ==

1. Upload the plugin folder to your /wp-content/plugins/ folder.
2. Go to the **Plugins** page and activate the plugin.
3. Put `[wlist]` or `[clist]` at the place in your posts where you want the custom list of posts or comments to appear OR go to the widget page to add a list in your sidebar.

== Frequently Asked Questions ==

= List of attributes available in post list (wlist) =

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
* **showdate:** `false` par défaut. Permet d'afficher ou la date des messages listés (ex: `[wlist showdate=true]`)
* **showtime:** `false` par défaut. Permet d'afficher ou l'heure des messages listés (ex: `[wlist showtime=true]`)
* **showcommentcount:** `false` par défaut. Permet d'afficher ou le nombre de commentaires des messages listés (ex: `[wlist showcommentcount=true]`)
* **showauthoravatar:** `none` par défaut. Permet d'afficher l'avatar de l'auteur en spécifiant sa taille (ex: `[wlist showauthoravatar=32]` ou `[wlist showauthoravatar=64]`).
* **showtext:** `none` par défaut. Permet d'afficher le contenu des messages en spécifiant le nombre de caractères à afficher ou 'all' pour afficher le message complet (ex: `[wlist showtext=50]` ou `[wlist showtext=all]`).

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

= Using dynamic values in widgets' configuration =

For advenced users only :

* **utiliser une variable globale :** saisir `${toto}` va appeler `$toto`.
* **utiliser un champ d'une variable globale :** saisir`${toto->name}` va appeler `$toto->name`.
* **utiliser une fonction :** saisir`${aFunction()}` va appeler `aFunction()` (sans argument).
* **utiliser une méthode statique :** saisir`${SomeClass::someStaticMethod()}` va appeler `SomeClass::someStaticMethod()` (sans argument). 

= I have a PHP fatal error when I acivate the plugin! =

This plugin requires PHP5. On some hosting, default version of PHP associated wih .php files is PHP4. To activate PHP5, you will most often add a few lines in the .htaccess file.  

= The link in comment lists doesn't lead to the comment but just to the top of the page =

Please check that the `comments.php` file in your theme correctly set the `id` attribute to `comment-xxx` where `xxx` is the comment id.

= How to use shortcodes in custom fields? =

Classes WlistBlock and ClistBlock provide two static methods to get the value of a custom field of the current article by replacing the shortcodes their value:
* getShortcodedCustomFieldValue($fieldName): returns the given field after having replaced shotcodes
* echoShortcodedCustomFieldValue($fieldName): displays the field value shown after having replaced shotcodes
To display this value in a template, just call the next line indicating the proper field name (in this example, the field is called "foo"): `<?php WlistBlock::echoShortcodedCustomFieldValue('foo'); ?>`

== Screenshots ==

1. Configuration of a post list widget.
1. Configuration of a comment list widget.
1. A post list widget.
1. A comment list widget.
1. Adding a post list in a post.
1. Rendering a post list in a post.