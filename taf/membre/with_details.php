<?php
try {
    require './config.php';
    $params=$get_params;

    $id_tontine=$params["id_tontine"];
    // $reponse["condition"]=$condition;
    $query="with montant_total_membre as (
        select id_membre,sum(montant) as montant_total from cotisation group by id_membre
        ),
        amande_membre as (
        select a.id_membre,sum(ta.montant) as montant_amande_total from amande a join type_amande ta on a.id_type_amande=ta.id_type_amande where a.etat_amande=0 group by a.id_membre
        )
    SELECT m.*,mtm.montant_total,am.montant_amande_total FROM membre m left join montant_total_membre mtm on m.id_membre=mtm.id_membre left join amande_membre am on m.id_membre=am.id_membre WHERE m.id_tontine=$id_tontine order by mtm.montant_total desc";
    $reponse["data"] = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $reponse["status"] = true;

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>