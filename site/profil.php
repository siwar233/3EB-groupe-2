<?php
require_once("inc/init.inc.php");

//--------------------------------- TRAITEMENTS PHP ---------------------------------//

if(!internauteEstConnecte()) {
  header("location:connexion.php");
}

// Get member information
$member_id = $_SESSION['membre']['id_membre'];
$member_email = $_SESSION['membre']['email'];
$member_ville = $_SESSION['membre']['ville'];
$member_cp = $_SESSION['membre']['code_postal'];
$member_adresse = $_SESSION['membre']['adresse'];

// Get member orders
$orders_query = "
  SELECT 
    c.id_commande,
p.titre,
d.quantite,
    c.date_enregistrement,
    c.montant,
    c.etat
    
  FROM commande c
  INNER JOIN details_commande d ON c.id_commande = d.id_commande
  INNER JOIN produit p ON d.id_produit = p.id_produit
  WHERE c.id_membre = $member_id
  ORDER BY c.date_enregistrement DESC
";
$orders_result = $mysqli->query($orders_query);

// Build HTML for member information
$contenu .= '<p class="centre">Bonjour <strong>' . $_SESSION['membre']['pseudo'] . '</strong></p>';
$contenu .= '<div class="cadre"><h2> Voici vos informations </h2>';
$contenu .= '<p> votre email est: ' . $member_email . '<br>';
$contenu .= 'votre ville est: ' . $member_ville . '<br>';
$contenu .= 'votre cp est: ' . $member_cp . '<br>';
$contenu .= 'votre adresse est: ' . $member_adresse . '</p></div><br><br>';

// Build HTML for product suggestions based on previous orders or last viewed category
$product_suggestions_query = '';
$product_suggestions_result = '';
if ($orders_result->num_rows > 0) { 
  // User has previous orders, get product suggestions based on those orders
  $product_suggestions_query = "
    SELECT 
      p.id_produit,
      p.titre,
      p.photo,
      p.prix,
      COUNT(*) AS nb_achats
    FROM commande c
    INNER JOIN details_commande d ON c.id_commande = d.id_commande
    INNER JOIN produit p ON d.id_produit = p.id_produit
    WHERE c.id_membre = $member_id AND p.id_produit NOT IN (
      SELECT d2.id_produit FROM details_commande d2 INNER JOIN commande c2 ON d2.id_commande = c2.id_commande WHERE c2.id_membre = $member_id
    )
    GROUP BY p.id_produit, p.titre, p.photo, p.prix
    ORDER BY nb_achats DESC
    LIMIT 3
  ";
  $product_suggestions_result = $mysqli->query($product_suggestions_query);

} else if (isset($_SESSION['last_category_id'])) {
  // User has not ordered before, get product suggestions based on last viewed category
  $last_category_id = $_SESSION['last_category_id'];
  $product_suggestions_query = "
    SELECT 
      id_produit,
      titre,
      photo,
      prix
    FROM produit
    WHERE id_categorie = $last_category_id
    ORDER BY rand()
    LIMIT 3
  ";
  $product_suggestions_result = $mysqli->query($product_suggestions_query);
}

// Build HTML for product suggestions table
if ($product_suggestions_result->num_rows > 0) {
  echo '<h2>Voici des produits que vous pourriez aimer</h2>';
  echo  '<table border="3">';
  echo '<thead><tr><th>Produit</th><th>Prix</th></tr></thead>';
  echo '<tbody>';
  while ($product_suggestion_row = $product_suggestions_result->fetch_assoc()) {
    echo '<tr><td><a href="fiche_produit.php?id_produit=' . $product_suggestion_row['id_produit'] . '"><img src="' . $product_suggestion_row['photo'] . '" width="100" height="100">' . $product_suggestion_row['titre'] . '</a></td>';
    echo '<td>' . $product_suggestion_row['prix'] . ' €</td></tr>';
  }
  echo '</tbody></table>';
}
// Build HTML for member orders table
if ($orders_result->num_rows > 0) {
  $contenu .= '<h2>Voici vos commandes</h2>';
  $contenu .= '<table border="3">';
 $contenu .= '<thead><tr><th>ID Commande</th><th>Produit</th><th>Quantite</th><th>Date</th><th>Total</th><th>Statut</th></tr></thead>';
$contenu .= '<tbody>';
while ($order_row = $orders_result->fetch_assoc()) {
  $contenu .= '<tr><td>' . $order_row['id_commande'] . '</td>';
  $contenu .= '<td>' . $order_row['titre'] . '</td>';
  $contenu .= '<td>' . $order_row['quantite'] . '</td>';
  $contenu .= '<td>' . $order_row['date_enregistrement'] . '</td>';
  $contenu .= '<td>' . $order_row['montant'] . '</td>';
  $contenu .= '<td>' . $order_row['etat'] . '</td></tr>';

}
  $contenu .= '</tbody></table>';
} else {
  $contenu .= '<h2>Aucune commande trouvée</h2>';
}

// Build HTML for member profile options
$contenu .= '<h3><a href="membres.php" style=color:blue;>Modifier le profil</a></h3><br><br>';
$contenu .= '<form method="post" action="">
  <button type="submit" name="delete_account" >Delete Account</button>
</form>';

// Handle delete account form submission
if (isset($_POST['delete_account']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the member ID from the session
  $user_id = $_SESSION['membre']['id_membre'];

  // Delete member from database
  $stmt = $mysqli->prepare('DELETE FROM membre WHERE id_membre = ?');
  $stmt->bind_param('i', $user_id);
  $stmt->execute();

  // Destroy the session and redirect to the login page
  session_destroy();
header('Location: connexion.php');
exit();
}


//--------------------------------- AFFICHAGE HTML ---------------------------------//

require_once("inc/haut.inc.php");
echo $contenu;
require_once("inc/bas.inc.php");