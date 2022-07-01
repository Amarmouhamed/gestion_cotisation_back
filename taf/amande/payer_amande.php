<?php
try {
    require './config.php';
    $params=$edit_params;

    if(count($params)==0){
        $reponse["status"] = false;
        $reponse["erreur"] = "Parameters required";
        echo json_encode($reponse);
        exit;
    }
    // pour charger l'heure courante
    // $params["date_enregistrement"]=date("Y-m-d H:i:s");
    // recupération de a clé primaire de la table pour la condition de modification
    // execution de la requete de modification
    $id_amande=$params["id_amande"];
    $etat_amande=$params["etat_amande"];
    $query="update amande set etat_amande=$etat_amande where id_amande=$id_amande";
    //$reponse["query"]=$query;
    $resultat=$connexion->exec($query);
    if ($resultat) {
        $reponse["status"] = true;
    } else {
        $reponse["status"] = false;
        $reponse["erreur"] = "Erreur $resultat";
    }
    echo json_encode($reponse);
} catch (\Throwable $th) {
    
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>