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
    $les_membres = json_decode($_POST["membres"]);
    for ($i = 0; $i < count($les_membres); $i++) {
        $un_membre=$les_membres[$i];
        $query = dynamicInsert($table_name, array(
            "id_tontine" =>$params["id_tontine"] ,
            "id_periode" =>$params["id_periode"] ,
            "id_membre"=> $un_membre-> id_membre ,
            "id_type_amande" =>$un_membre->id_type_amande  ,
	 	    "date_amande" => $params["date_enregistrement"],
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
        $reponse["membre_$i"] = $les_membres[$i]->prenom;
    }

    echo json_encode($reponse);
} catch (\Throwable $th) {

    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}
