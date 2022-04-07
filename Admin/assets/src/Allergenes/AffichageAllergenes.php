<?php

require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/AllergenesFunctions.php';

use App\Allergenes;

$ua = getBrowser();

$record_per_page = 10;
$page = 0;
$output = '';

if (isset($_POST['page'])) {

  $page = $_POST['page'];
} else {

  $page = 1;
}

$start_from = ($page - 1) * $record_per_page;

$query = $pdo->query("SELECT * FROM allergenes ORDER BY titre ASC LIMIT $start_from,$record_per_page");



$output .= '<table>

          <thead>
            <tr>
              <th class="dnone">ID</th>
              <th>Titre</th>';

$output .= '<th class="desktop">Description</th>';
$output .= '<th class="desktop">Exclusions</th>';
$output .= '<th class="desktop"># Plats</th>';
$output .= ' <th>Actions</th>';
$output .= ' </tr></thead>';
$output .= '<tbody class="page_list">';

while ($row = $query->fetch()) {


  $output .= '<td id="' . $row['id'] . '"class="dnone">' . $row['id'] . '</td>';
  $output .= '<td><strong>' . $row['titre'] . '</strong></td>';
  $output .= '<td class="desktop description">' . $row['description'] .  '</td>';
  $output .= '<td class="desktop description">' . $row['exclusions'] .  '</td>';
  $output .= '<td class="desktop">' . Allergenes::getNbPlats($pdo, $row['id']) . '</td>';
  $output .= '<td class="member_action">';
  $output .= '<div class="member_action-container">';
  $output .= '<input type="button" class="viewbtn" name="view" id="' . $row['id'] . '"></input>';
  $output .= '<input type="button" class="editbtn" id="' . $row['id'] . '"></input>';
  $output .= '<input type="button" class="deletebtn"></input>';
  $output .= '</div>';
  $output .= '</td>';
  $output .= '</tr>';
}

$output .= '</tbody></table>';
if (countAller($pdo) > 10) {
  $output .= '<br /><div class="custom_pagination">';

  $page_query = $pdo->query('SELECT * FROM allergenes ORDER BY titre ASC');
  $total_records = $page_query->rowCount();
  $total_pages = ceil($total_records / $record_per_page);


  $output .= '<ul class="pagination">';

  if ($page > 1) {
    $previous = $page - 1;
    $output .= '<li class="pagination_link" id="' . $previous . '"><span class="page-link"><i class="fas fa-caret-left"></i> Précédent</span></li>';
  }

  if ($page < $total_pages) {
    $page++;
    $output .= '<li class="pagination_link" id="' . $page . '"><span class="page-link">Suivant <i class="fas fa-caret-right"></i></span></li>';
  }

  $output .= '</ul>';

  $output .= '</div>';
}



echo $output;