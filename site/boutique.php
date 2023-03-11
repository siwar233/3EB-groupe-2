<?php
require_once("inc/init.inc.php");

//--------------------------------- TRAITEMENTS PHP ---------------------------------//

//--- AFFICHAGE DES CATEGORIES ---//
$categories_des_produits = executeRequete("SELECT DISTINCT categorie FROM produit");
$contenu .= '<div class="boutique-gauche">';
$contenu .= "<ul>";
while($cat = $categories_des_produits->fetch_assoc())
{
    $contenu .= "<li><a href='?categorie=" . $cat['categorie'] . "'>" . $cat['categorie'] . "</a></li>";
}
$contenu .= "</ul>";
$contenu .= "</div>";


// Search bar
$contenu .= '<div class="search-bar">';
$contenu .= '<form method="GET" action="">';
$contenu .= '<input type="hidden" name="page" value="boutique">';
$contenu .= '<input type="text" name="search" placeholder="Search for a product">';
$contenu .= '<button type="submit">Search</button>';
$contenu .= '</form>';
$contenu .= '</div>';

if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $search = strtolower($search);
    $where = "WHERE LOWER(titre) LIKE '%$search%'";
// Display products
$contenu .= '<div class="boutique-droite">';
$produits = executeRequete("SELECT * FROM produit $where");
while($produit = $produits->fetch_assoc())
{
    $contenu .= '<div class="boutique-produit">';
    $contenu .= "<h2>$produit[titre]</h2>";
    $contenu .= "<a href=\"fiche_produit.php?id_produit=$produit[id_produit]\"><img src=\"$produit[photo]\" =\"130\" height=\"100\"></a>";
    $contenu .= "<p>$produit[prix] €</p>";
    $contenu .= '<a href="fiche_produit.php?id_produit=' . $produit['id_produit'] . '">Voir la fiche</a>';
    $contenu .= '</div>';
}
$contenu .= '</div>'; // Close "boutique-droite" div
$contenu .= '</div>'; // Close "filters" div

// Check if any products are displayed
if ($produits->num_rows === 0) {
$contenu .= '<p>No products found.</p>';
}
} 


// Category and price range filters
$contenu .= '<div class="filters">';
$contenu .= '<form method="GET" action="">';
$contenu .= '<select name="categorie">';
$contenu .= '<option value="">-- All Categories --</option>';

// Category options
$selectedCat = '';
if(isset($_GET['categorie'])) {
    $selectedCat = $_GET['categorie'];
}
$categories = executeRequete("SELECT DISTINCT categorie FROM produit");
while($cat = $categories->fetch_assoc())
{
    $selected = ($cat['categorie'] == $selectedCat) ? 'selected' : '';
    $contenu .= "<option value='{$cat['categorie']}' $selected>{$cat['categorie']}</option>";
}
$contenu .= '</select>';

// Price range options
$selectedRange = '';
if(isset($_GET['prix'])) {
    $selectedRange = $_GET['prix'];
}
$contenu .= '<div class="filter">';
$contenu .= '<label for="prix">Price range:</label>';
$contenu .= '<select name="prix">';
$contenu .= '<option value="">-- All Prices --</option>';
$contenu .= '<option value="0-50" '.(($selectedRange=='0-50') ? 'selected' : '').'>0-50€</option>';
$contenu .= '<option value="50-100" '.(($selectedRange=='50-100') ? 'selected' : '').'>50-100€</option>';
$contenu .= '<option value="100-200" '.(($selectedRange=='100-200') ? 'selected' : '').'>100-200€</option>';
$contenu .= '<option value="200-9999" '.(($selectedRange=='200-9999') ? 'selected' : '').'>200€ et plus</option>';
$contenu .= '</select>';

$contenu .= '<button type="submit">Filter</button>';
$contenu .= '</div>';
$contenu .= '</form>';

// Check if price range filter is applied
$price_range_query = '';
if (!empty($selectedRange)) {
    $price_range = explode('-', $selectedRange);
    $price_range_query = "AND prix BETWEEN {$price_range[0]} AND {$price_range[1]}";
}

// Check if category filter is applied
$category_query = '';
if (!empty($selectedCat)) {
    $category_query = "AND categorie = '$selectedCat'";
// Fetch products from database with price range and category filters applied
$produits_query = "SELECT id_produit,reference,titre,photo,prix FROM produit WHERE 1=1 $category_query $price_range_query";
$produits = executeRequete($produits_query);
// Display products
$contenu .= '<div class="boutique-droite">';
while($produit = $produits->fetch_assoc())
{
    $contenu .= '<div class="boutique-produit">';
    $contenu .= "<h2>$produit[titre]</h2>";
    $contenu .= "<a href=\"fiche_produit.php?id_produit=$produit[id_produit]\"><img src=\"$produit[photo]\" =\"130\" height=\"100\"></a>";
    $contenu .= "<p>$produit[prix] €</p>";
    $contenu .= '<a href="fiche_produit.php?id_produit=' . $produit['id_produit'] . '">Voir la fiche</a>';
    $contenu .= '</div>';
}
$contenu .= '</div>'; // Close "boutique-droite" div
$contenu .= '</div>'; // Close "filters" div

// Check if any products are displayed
if ($produits->num_rows === 0) {
$contenu .= '<p>No products found.</p>';
}
}




//--------------------------------- AFFICHAGE HTML ---------------------------------//

require_once("inc/haut.inc.php");
echo $contenu;
require_once("inc/bas.inc.php");