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
    $les_periodes = json_decode($_POST["periodes"]);
    for ($i = 0; $i < count($les_periodes); $i++) {
        $une_periode=$les_periodes[$i];
        $query = dynamicInsert($table_name, array(
            "id_tontine" =>$params["id_tontine"] ,
            "id_createur" =>$params["id_createur"] ,
            "mois"=> $une_periode-> mois ,
            "annee"=> $une_periode-> annee ,
	 	    "created_at" => $params["date_enregistrement"],
        ));
        // $reponse["query"]=$query;
        if ($connexion->exec($query)) {
            $reponse["status"] = true;
            $id_cotisation = $connexion->lastInsertId();
            $reponse["data"][] = $id_cotisation;
        } else {
            $reponse["status"] = false;
            $reponse["erreur"] = "Erreur d'insertion à la base de ";
        }
        $reponse["membre_$i"] = $les_periodes[$i]->mois;
    }

    echo json_encode($reponse);
} catch (\Throwable $th) {

    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}
