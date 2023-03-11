<?php require_once("inc/init.inc.php");

if(isset($_POST['submit']))
{
    $pseudo_email = $_POST['pseudo_email'];
    $resultat = executeRequete("SELECT * FROM membre WHERE pseudo='$pseudo_email' OR email='$pseudo_email'");
    if($resultat->num_rows != 0)
    {
        $membre = $resultat->fetch_assoc();
        $nouveau_mdp = genererMdp();
        $mdp_crypte = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
        executeRequete("UPDATE membre SET mdp='$mdp_crypte' WHERE id_membre='$membre[id_membre]'");
        $destinataire = $membre['email'];
        $sujet = "Réinitialisation de votre mot de passe";
        $message = "Bonjour $membre[pseudo],\r\n\r\nVotre mot de passe a été réinitialisé. Votre nouveau mot de passe est : $nouveau_mdp\r\n\r\nConnectez-vous à votre compte avec ce nouveau mot de passe et modifiez-le immédiatement.\r\n\r\nCordialement,\r\nL'équipe du site";
        $headers = "From: noreply@monsite.com";
        mail($destinataire, $sujet, $message, $headers);
        $contenu .= '<div class="validation">Un email vous a été envoyé avec votre nouveau mot de passe.</div>';
    }
    else
    {
        $contenu .= '<div class="erreur">Pseudo ou email inconnu.</div>';
    }
}
function genererMdp($longueur = 8) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $mdp = '';
    for ($i = 0; $i < $longueur; $i++) {
        $mdp .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $mdp;
}

//--------------------------------- AFFICHAGE HTML ---------------------------------//
?>
<?php require_once("inc/haut.inc.php"); ?>
<?php echo $contenu; ?>
 
<form method="post" action="">
    <label for="pseudo_email">Pseudo ou adresse email</label><br>
    <input type="text" id="pseudo_email" name="pseudo_email"><br> <br>
         
    <input type="submit" name="submit" value="Réinitialiser mon mot de passe">
</form>
 
<?php require_once("inc/bas.inc.php"); ?>
