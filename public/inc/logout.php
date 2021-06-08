<?php
/**
 * logout.php
 * 
 * Seite zum Löschen der Sitzung und um den Cookie zu leeren.
 */

/**
 * Einbinden der Cookieüberprüfung.
 */
require_once('cookiecheck.php');

/**
 * Titel
 */
$title = "Logout";
$content.= "<h1>Logout</h1>".PHP_EOL;

if(!isset($_POST['submit'])) {
  /**
   * Formular wird angezeigt
   */
  $content.= "<form action='/logout' method='post'>".PHP_EOL;
  /**
   * Sitzungstoken
   */
  $content.= "<input type='hidden' name='token' value='".$sessionhash."'>".PHP_EOL;
  /**
   * Auswahl
   */
  $content.= "<div class='row hover bordered'>".PHP_EOL.
  "<div class='col-s-12 col-l-12'>Möchtest du dich ausloggen?</div>".PHP_EOL.
  "<div class='col-s-12 col-l-12'><input type='submit' name='submit' value='Ja'></div>".PHP_EOL.
  "</div>".PHP_EOL;
  $content.= "</form>".PHP_EOL;
  $content.= "<div class='row'>".PHP_EOL.
    "<div class='col-s-12 col-l-12'><a href='/overview'><span class='fas icon'>&#xf0cb;</span>Zurück zur Übersicht</a></div>".PHP_EOL.
  "</div>".PHP_EOL;
} else {
  /**
   * Formular abgesendet
   */
  /**
   * Sitzungstoken
   */
  if($_POST['token'] != $sessionhash) {
    http_response_code(403);
    $content.= "<div class='warnbox'>Ungültiges Token.</div>".PHP_EOL;
    $content.= "<div class='row'>".PHP_EOL.
    "<div class='col-s-12 col-l-12'><a href='/overview'>Zurück zur Übersicht</a></div>".PHP_EOL.
    "</div>".PHP_EOL;
  } else {
    /**
     * Löschen der Sitzung.
     */
    mysqli_query($dbl, "DELETE FROM `sessions` WHERE `hash`='".$match[0]."'") OR DIE(MYSQLI_ERROR($dbl));
    mysqli_query($dbl, "INSERT INTO `log` (`userId`, `loglevel`, `text`) VALUES ('".$userId."', 1, 'Logout: ".$username."')") OR DIE(MYSQLI_ERROR($dbl));
    /**
     * Entfernen des Cookies und Umleitung zur Loginseite.
     */
    setcookie($cookieName, NULL, 0);
    header("Location: /login");
    die();
  }
}
?>
