<?php
try {
    require './config.php';
    $params=$add_params;

    // $reponse["condition"]=$condition;
    $id_periode=$params["id_periode"];
    $query="select * from amande a join membre m on a.id_membre=m.id_membre join type_amande ta on a.id_type_amande=ta.id_type_amande where a.etat_amande=1 and a.id_periode=$id_periode ";
    $reponse["data"]["encaissees"] = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $query="select *from amande a join membre m on a.id_membre=m.id_membre join type_amande ta on a.id_type_amande=ta.id_type_amande where a.etat_amande=0 and a.id_periode=$id_periode ";
    $reponse["data"]["non_encaissees"] = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $reponse["status"] = true;

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>