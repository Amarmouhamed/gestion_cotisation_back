<?php
try {
    require './config.php';
    $params = $add_params;

    if (count($params) == 0) {
        $reponse["status"] = false;
        $reponse["erreur"] = "Parameters required";
        echo json_encode($reponse);
        exit;
    }
    // pour charger l'heure courante
    $params["date_enregistrement"] = date("Y-m-d H:i:s");
    $les_amandes = json_decode($_POST["amandes"]);
    for ($i = 0; $i < count($les_amandes); $i++) {
        $une_amande=$les_amandes[$i];
        $query = "update amande set etat_amande=1 where id_amande=".$une_amande->id_amande;
        // $reponse["query"]=$query;
        if ($connexion->exec($query)) {
            $reponse["status"] = true;
            $reponse["data"][] = $une_amande->id_amande;
        } else {
            $reponse["status"] = false;
            $reponse["erreur"] = "Erreur d'insertion à la base de ";
        }
        $reponse["amande_$i"] = $une_amande->id_amande;
    }

    echo json_encode($reponse);
} catch (\Throwable $th) {

    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}
