<?php
/**
 * confirmEmail.php
 * 
 * E-Mail Adresse bestätigen
 */
$title = "E-Mail Adresse bestätigen";

$content.= "<h1><span class='fas icon'>&#xf058;</span>E-Mail Adresse bestätigen</h1>";

if(!empty($_GET['hash'])) {
  if(preg_match('/[a-f0-9]{64}/i', defuse($_GET['hash']), $match) === 1) {
    $hash = defuse($match[0]);
    $result = mysqli_query($dbl, "SELECT * FROM `users` WHERE `emailHash`='".$hash."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
    if(mysqli_num_rows($result) == 1) {
      mysqli_query($dbl, "UPDATE `users` SET `emailHash`=NULL, `validEmail`=1 WHERE `emailHash`='".$hash."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
      if(mysqli_affected_rows($dbl) == 1) {
        $row = mysqli_fetch_array($result);
        userLog($row['id'], 1, "Neue E-Mail Adresse bestätigt");
        $content.= "<div class='successbox'>Neue E-Mail Adresse bestätigt.</div>";
        $content.= "<div class='row'>".
          "<div class='col-s-12 col-l-12'><a href='/login'><span class='fas icon'>&#xf2f6;</span>Login</a></div>".
        "</div>";
      }
    } else {
      http_response_code(403);
      $content.= "<div class='warnbox'>Der übergebene Hash ist ungültig oder wurde bereits benutzt. Bitte klicke den Link in der Änderungsmail an oder probiere dich einzuloggen um fortzufahren.</div>";
      $content.= "<div class='row'>".
        "<div class='col-s-12 col-l-12'><a href='/start'><span class='fas icon'>&#xf015;</span>Startseite</a></div>".
      "</div>";
    }
  } else {
    http_response_code(403);
    $content.= "<div class='warnbox'>Der übergebene Hash hat ein ungültiges Format. Bitte klicke den Link in der Änderungsmail an um fortzufahren.</div>";
    $content.= "<div class='row'>".
      "<div class='col-s-12 col-l-12'><a href='/start'><span class='fas icon'>&#xf015;</span>Startseite</a></div>".
    "</div>";
  }
} else {
  http_response_code(403);
  $content.= "<div class='warnbox'>Es wurde kein Hash übergeben. Bitte klicke den Link in der Änderungsmail an um fortzufahren.</div>";
  $content.= "<div class='row'>".
    "<div class='col-s-12 col-l-12'><a href='/start'><span class='fas icon'>&#xf015;</span>Startseite</a></div>".
  "</div>";
}
?>
