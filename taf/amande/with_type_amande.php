<?php
try {
    require './config.php';
    $params=$get_params;

    $id_membre=$params["id_membre"];
    // $reponse["condition"]=$condition;
    $query="select *from $table_name a join type_amande ta on a.id_type_amande=ta.id_type_amande where id_membre=$id_membre and a.etat_amande=0";
    $reponse["data"] = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $reponse["status"] = true;

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>