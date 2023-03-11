<?php
require_once("../inc/init.inc.php");
//--------------------------------- TRAITEMENTS PHP ---------------------------------//
//--- VERIFICATION ADMIN ---//
if(!internauteEstConnecteEtEstAdmin())
{
    header("location:../connexion.php");
    exit();
}
//--- AFFICHAGE PRODUITS ---//
$test = $_POST['trie'];
if($test =="date_enregistrement")
    { $resultat = executeRequete("SELECT commande.id_commande,montant,date_enregistrement,produit.id_produit,titre,photo,quantite,membre.id_membre,pseudo,adresse,ville,code_postal,etat FROM commande,produit,details_commande,membre where details_commande.id_commande=commande.id_commande AND details_commande.id_produit=produit.id_produit AND membre.id_membre=commande.id_membre group by date_enregistrement ");
  }
else if ($test =="montant")
{
$resultat = executeRequete("SELECT commande.id_commande,montant,date_enregistrement,produit.id_produit,titre,photo,quantite,membre.id_membre,pseudo,adresse,ville,code_postal,etat FROM commande,produit,details_commande,membre where details_commande.id_commande=commande.id_commande AND details_commande.id_produit=produit.id_produit AND membre.id_membre=commande.id_membre group by montant ");
}
else if ($test =="etat")
{
$resultat = executeRequete("SELECT commande.id_commande,montant,date_enregistrement,produit.id_produit,titre,photo,quantite,membre.id_membre,pseudo,adresse,ville,code_postal,etat FROM commande,produit,details_commande,membre where details_commande.id_commande=commande.id_commande AND details_commande.id_produit=produit.id_produit AND membre.id_membre=commande.id_membre group by etat ");
}
else  { $resultat = executeRequete("SELECT commande.id_commande,montant,date_enregistrement,produit.id_produit,titre,photo,quantite,membre.id_membre,pseudo,adresse,ville,code_postal,etat FROM commande,produit,details_commande,membre where details_commande.id_commande=commande.id_commande AND details_commande.id_produit=produit.id_produit AND membre.id_membre=commande.id_membre ");
  }
     
    $contenu .= '<h2> Affichage des commande </h2>';
    $contenu .= '<table border="1" cellpadding="5"><tr>';
while($colonne = $resultat->fetch_field())
    {  


        $contenu .= '<th>' . $colonne->name . '</th>';
    }
$contenu .= '<th>' . 'Modifier l\'etat' . '</th>';
$contenu .= '</tr>';

while ($ligne = $resultat->fetch_assoc())
    {
        $contenu .= '<tr>';
        foreach ($ligne as $indice => $information)
        {
            if($indice == "photo")
            {
                $contenu .= '<td><img src="' . $information . '" ="70" height="70"></td>';
         
}
            else
            {
                $contenu .= '<td>' . $information . '</td>';

            }


        }

        
  // Modify this line to include the id of the order
  $contenu .= '<td><a href="?action=updateEtat&id=' . $ligne['id_commande'] . '"><img src="../photo/edit.png" width="70" height="70"></a></td>';
  $contenu .= '</tr>';
}  

// Check if the updateEtat action is set and the id of the order is provided
if(isset($_GET['action']) && $_GET['action'] == 'updateEtat' && isset($_GET['id'])) {
  $id_commande = $_GET['id'];
  // Update the etat of the order with the given id
  executeRequete("UPDATE commande SET etat='livré' WHERE id_commande={$_GET['id']}");
   mail($_SESSION['membre']['email'], "en cours d'envoi", "Merci, Votre commande est en route", "From:vendeur@dp_site.com");
  $contenu .= "<div class='validation'>Merci </div>";
   
// Redirect to the current page to display the updated orders
  header('Location: ' . $_SERVER['PHP_SELF']);
  exit();
}
    $contenu .= '</table><br><hr><br>';

 $CA = executeRequete("SELECT Sum(montant) from commande");
while ($ligne = $CA->fetch_assoc())
    {
        $contenu .= '<tr>';
        foreach ($ligne as $indice => $information)
        {
           
               $contenu .= '<br><h1>Chiffre d\'affaire total est ' . $information . '</h1>' ; 
        }
}


//--------------------------------- AFFICHAGE HTML ---------------------------------//
require_once("../inc/haut.inc.php");
echo '<h4>
<label>trie par </label></h4> <form method="post" action="">
<select name="trie" id="trie">
<option name="aucune" value="Aucune">Aucune</option>
  <option name="date_enregistrement" value="date_enregistrement">DATE</option>
  <option name="montant" value="montant">MONTANT</option>
  <option name="etat" value="etat">ETAT</option>
</select>
<input type="submit" value="recherche">
 </form>';
echo $contenu;

require_once("../inc/bas.inc.php"); ?>