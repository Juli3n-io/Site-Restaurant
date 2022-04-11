<?php
require_once __DIR__ . '/../../Config/Init.php';
require_once __DIR__ . '/../../Functions/DrinksFunctions.php';

use App\General;
use App\Drinks;

/* #############################################################################

vue rapide d'une boisson a partir Drinks.php en Ajax

############################################################################# */


if (isset($_POST['drink_id'])) {

  $result = '';
  $id = $_POST['drink_id'];

  $query = $pdo->query("SELECT * FROM boissons WHERE id = '$id'");

  $result .= '<div class="list_container plat">';



  while ($row = $query->fetch()) {
    $ua = getBrowser();
    $date = str_replace('/', '-', $row['date_enregistrement']);
    $update = str_replace('/', '-', $row['date_modification']);

    if ($options['show_drink_photo']) {
      $result .= '<div class="img-cat-container">' . Drinks::getDrinksPhoto($pdo, $row['photo_id'], $ua['name']) . '</div>';
    }

    $result .= '<ul>';
    if ($user['statut'] == 0) {
      $result .= '<li>
                    <h6>ID : </h6>
                    <p>' . $row['id'] . '</p>
                  </li>';
    }
    $result .= '<li>
                    <h6>Créer par : </h6>
                    <p>' . General::getMembre($pdo, $row['author_id']) . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Titre : </h6>
                    <p>' . $row['titre'] . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Prix : </h6>
                    <p>' . $row['prix'] . ' €</p>
                  </li>';
    $result .= '<li class="description">
                    <h6>Description : </h6>
                    <p class="description">' . $row['description'] . '</p>
                  </li>';
    $result .= '<li class="description">
                    <h6>Catégorie : </h6>
                    <p>' . Drinks::getCat($pdo, $row['cat_id']) . '</p>
                  </li>';
    if ($options['show_drink_sous_cat']) {
      if ($row['souscat_id'] != NULL) {
        $result .= '<li class="description">
                    <h6>Sous Catégorie : </h6>
                    <p>' . Drinks::getSousCat($pdo, $row['souscat_id']) . '</p>
                  </li>';
      }
    }
    if ($row['have_allergenes']) {
      $result .= '<li class="description">
                    <h6>Liste des allergenes : </h6><ul>';
      foreach (getAllergeneDrinks($pdo, $row['id']) as $allergene) {
        $result .= '<li>' . $allergene['titre'] . '</li> ';
      }

      $result .= '</ul></li>';
    }

    $result .= '<li>
                    <h6>Publié : </h6>';
    $result .= ($row['est_publie']) ? '<p class="badge success confirmation">Oui</p>' : '<p class="badge danger confirmation">Non</p>';
    $result .= '</li>';
    $result .= '<li>
                    <h6>Nouveauté : </h6>';
    $result .= ($row['est_nouveau']) ? '<p class="badge success confirmation">Oui</p>' : '<p class="badge danger confirmation">Non</p>';
    $result .= '</li>';
    $result .= '<li>
                    <h6>Mit en avant : </h6>';
    $result .= ($row['est_en_avant']) ? '<p class="badge success confirmation">Oui</p>' : '<p class="badge danger confirmation">Non</p>';
    $result .= '</li>';
    $result .= '<li>
                    <h6>Crée : </h6>
                    <p>' . date('d-m-Y', strtotime($date)) . '</p>
                  </li>';
    if ($row['date_modification'] != null) {
      $result .= '<li>
                    <h6>Modifié : </h6>
                    <p>' . date('d-m-Y', strtotime($update)) . '</p>
                  </li>';
    }


    $result .= '</ul>';

    $result .= '</div>';
  }
  echo $result;
}