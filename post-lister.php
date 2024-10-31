<?php
/*
Plugin Name: Listeur d'articles / Post lister
Plugin URI: http://wp.darathor.com/mes-plugins-maison/post-lister/
Author: Darathor
Author URI: http://wp.darathor.com/
Description: Ce plugin dérivé du plugin <a href="http://www.webinventif.fr/query-inside-post-plugin-wordpress-pour-inserer-facilement-une-boucle-dans-un-billet/" hreflang="fr">Query inside post</a> de k-ny permet de générer des listes de messages ou de commentaires publiés sur votre site en les sélectionnant selon différents critères (auteur, tag, catégoie, etc.). Ces listes peuvent être intégrées à des messages via les shortcodes [wlist] et [clist] ou bien intégrés dans une sidebar via un widget. 
Version: 0.4
*/ 

$includePath = dirname(__FILE__) . DIRECTORY_SEPARATOR;
include($includePath . 'generated/AuthorDocumentBase.class.php');
include($includePath . 'include/AuthorDocument.class.php');
include($includePath . 'generated/CommentDocumentBase.class.php');
include($includePath . 'include/CommentDocument.class.php');
include($includePath . 'generated/WlistBlockBase.class.php');
include($includePath . 'include/WlistBlock.class.php');
include($includePath . 'generated/ClistBlockBase.class.php');
include($includePath . 'include/ClistBlock.class.php');

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first.
add_action('plugins_loaded', array('WlistBlock', 'getInstance'));
add_action('plugins_loaded', array('ClistBlock', 'getInstance'));
?>