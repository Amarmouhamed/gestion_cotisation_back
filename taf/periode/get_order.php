<?php
try {
    require './config.php';
    $params=$get_params;

    $condition=dynamicCondition($params,"like");
    // $reponse["condition"]=$condition;
    $id_tontine=$params["id_tontine"];
    $query="SELECT * FROM periode WHERE id_tontine=$id_tontine order by mois, annee";
    $reponse["data"] = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $reponse["status"] = true;

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>