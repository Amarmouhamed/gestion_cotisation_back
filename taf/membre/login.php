<?php
try {
    require './config.php';
    $params=$add_params;
    
    if(count($params)==0){
        $reponse["status"] = false;
        $reponse["erreur"] = "Parameters required";
        echo json_encode($reponse);
        exit;
    }
    $numero=addslashes($params["numero"]);
    $password=addslashes($params["password"]);
    $query="select id_membre,id_tontine,prenom,nom, adresse,poste ,numero,matricule, etat, adhesion, privilege, date_demarrage from membre where numero='$numero' and password='$password'";
    $resultat = $connexion->query($query);
    if ($ligne=$resultat->fetch(PDO::FETCH_ASSOC)) {
        $reponse["status"]=true;
        $ligne["tontine"]=$connexion->query("select * from tontine where id_tontine=".$ligne["id_tontine"])->fetch(PDO::FETCH_ASSOC);
        $reponse["data"]=$ligne;
    } else {
        # code...
        $reponse["status"]=false;
    }
    echo json_encode($reponse);
} catch (\Throwable $th) {
    
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>