<?php
/**
 * In dieser Datei werden die Grundeinstellungen für WordPress vorgenommen.
 *
 * Zu diesen Einstellungen gehören: MySQL-Zugangsdaten, Tabellenpräfix,
 * Secret-Keys, Sprache und ABSPATH. Mehr Informationen zur wp-config.php gibt es
 * auf der {@link http://codex.wordpress.org/Editing_wp-config.php wp-config.php editieren}
 * Seite im Codex. Die Informationen für die MySQL-Datenbank bekommst du von deinem Webhoster.
 *
 * Diese Datei wird von der wp-config.php-Erzeugungsroutine verwendet. Sie wird ausgeführt,
 * wenn noch keine wp-config.php (aber eine wp-config-sample.php) vorhanden ist,
 * und die Installationsroutine (/wp-admin/install.php) aufgerufen wird.
 * Man kann aber auch direkt in dieser Datei alle Eingaben vornehmen und sie von
 * wp-config-sample.php in wp-config.php umbenennen und die Installation starten.
 *
 * @package WordPress
 */

/**  MySQL Einstellungen - diese Angaben bekommst du von deinem Webhoster. */
/**  Ersetze database_name_here mit dem Namen der Datenbank, die du verwenden möchtest. */
define('DB_NAME', 'spielwiese');

/** Ersetze username_here mit deinem MySQL-Datenbank-Benutzernamen */
define('DB_USER', 'root');

/** Ersetze password_here mit deinem MySQL-Passwort */
define('DB_PASSWORD', 'root');

/** Ersetze localhost mit der MySQL-Serveradresse */
define('DB_HOST', 'localhost');

/** Der Datenbankzeichensatz der beim Erstellen der Datenbanktabellen verwendet werden soll */
define('DB_CHARSET', 'utf8');

/** Der collate type sollte nicht geändert werden */
define('DB_COLLATE', '');

/**#@+
 * Sicherheitsschlüssel
 *
 * Ändere jeden KEY in eine beliebige, möglichst einzigartige Phrase.
 * Auf der Seite {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * kannst du dir alle KEYS generieren lassen.
 * Bitte trage für jeden KEY eine eigene Phrase ein. Du kannst die Schlüssel jederzeit wieder ändern,
 * alle angemeldeten Benutzer müssen sich danach erneut anmelden.
 *
 * @seit 2.6.0
 */
define('AUTH_KEY',         '<-9% I[ojFWL&b~xrq[P;M4*H>+1#A#u>r(v|<<.n*KR/Ro0|.=6*ItyzP>J[_xH');
define('SECURE_AUTH_KEY',  'U$eNQ}#rf(&$`5AtU*3[@[5AkY+Ly2Lc Z:- 4-qWM91L_jm#O0M$2f#n78:v| Q');
define('LOGGED_IN_KEY',    'W|O F55w)?M,KOHCqC9RQ.b&q75=n<dt#*<&>&Gu+HXKB-|>fU0E@7W++AD?)@#8');
define('NONCE_KEY',        '+6BV9R(lqH#A@!i}r}7{[N&:{B9blLR|q(l=|F-g&2^Q^q^,kvRGZy+iNfH;>-k^');
define('AUTH_SALT',        'Z|XvFojSV-mVCo%,y##rdL(4Ii}}gr.R6W,ma5+F|}Z9]nlGUTPwx@`UonOJ:G<;');
define('SECURE_AUTH_SALT', 'h=.|6l!<q(fch_}QVg&ccG|-s7JO~6b[(wk{I`<:fZk|tKo&9&PXjAEn?/j-pP#V');
define('LOGGED_IN_SALT',   'Vd,RV-h^{6_?lZ:*K+WU]i*#S`!c/H4;|L.<C|ne{f9+Y;8T-YZxe+wxc{}fH|@|');
define('NONCE_SALT',       'k.m$@^_`[68=bVT@@-m(2vc>W6sLV,d]k8%.yCnVN,*=;AWQHjQ/zP/G`b-8-o%3');

/**#@-*/

/**
 * WordPress Datenbanktabellen-Präfix
 *
 *  Wenn du verschiedene Präfixe benutzt, kannst du innerhalb einer Datenbank
 *  verschiedene WordPress-Installationen betreiben. Nur Zahlen, Buchstaben und Unterstriche bitte!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
