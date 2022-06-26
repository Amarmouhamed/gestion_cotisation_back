<?php
try {
    require './config.php';
    $params=$add_params;

    // $reponse["condition"]=$condition;
    $id_tontine=$params["id_tontine"];
    $id_periode=$params["id_periode"];
    $query="SELECT m.id_membre,m.matricule,m.prenom,m.nom,c.* FROM membre m join cotisation c on m.id_membre=c.id_membre where c.id_tontine=$id_tontine and  id_periode=$id_periode";
    $reponse["data"]["encaissees"] = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);

    $query="with membre_tontine as (
            select * FROM membre where id_tontine=$id_tontine
            )
        SELECT m.id_membre,m.matricule,m.prenom,m.nom,m.adresse,m.id_tontine FROM membre_tontine m WHERE id_membre not in (select id_membre from cotisation where id_periode=$id_periode and id_tontine=$id_tontine)";
    $reponse["data"]["non_encaissees"] = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $reponse["status"] = true;

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}
