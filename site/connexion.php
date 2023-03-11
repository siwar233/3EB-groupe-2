<?php require_once("inc/init.inc.php");

//--------------------------------- TRAITEMENTS PHP ---------------------------------//

if(isset($_GET['action']) && $_GET['action'] == "deconnexion")
{
    session_destroy();
}

if(internauteEstConnecte())
{
    header("location:profil.php");
}

if($_POST)
{
    $resultat = executeRequete("SELECT * FROM membre WHERE pseudo='$_POST[login]' OR email='$_POST[login]'");
    if($resultat->num_rows != 0)
    {
        $membre = $resultat->fetch_assoc();
        if($membre['mdp'] == $_POST['mdp'])
        {
            foreach($membre as $indice => $element)
            {
                if($indice != 'mdp')
                {
                    $_SESSION['membre'][$indice] = $element;
                }
            }
            header("location:profil.php");
        }
        else
        {
            $contenu .= '<div class="erreur">Erreur de MDP</div>';
        }       
    }
    else
    {
        $contenu .= '<div class="erreur">Erreur de login</div>';
    }
}

//--------------------------------- AFFICHAGE HTML ---------------------------------//

?>
<?php require_once("inc/haut.inc.php"); ?>
<?php echo $contenu; ?>
 
<form method="post" action="">
    <label for="login">Pseudo ou email</label><br>
    <input type="text" id="login" name="login"><br><br>
         
    <label for="mdp">Mot de passe</label><br>
    <input type="password" id="mdp" name="mdp"><br><br>
 
     <input type="submit" value="Se connecter">
</form>
 <a href="mot-de-passe-oublie.php">Mot de passe oublié ?</a>
 
<?php require_once("inc/bas.inc.php"); ?>
