<?php
try {
    require './config.php';
    $params=$get_params;

    $id_tontine=$params["id_tontine"];
    // les membres
    $query="with montant_total_membre as (
            select id_membre,sum(montant) as montant_total from cotisation group by id_membre
            )
        SELECT * FROM membre m left join montant_total_membre mtm on m.id_membre=mtm.id_membre WHERE m.id_tontine=$id_tontine order by mtm.montant_total desc";
    $reponse["data"]["membres"] = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
    // Total caisse du mois
    $reponse["data"]["caisse_mois"] = "15000";

    // cotisation moyenne
    $reponse["data"]["cotisation_moyenne"] = "50000";

    // taux de participation
    $reponse["data"]["taux_participation"] = "70";

    // total caisse
    $query="select sum(montant) as montant_total from cotisation WHERE id_tontine=$id_tontine";
    $reponse["data"]["total_caisse"] = $connexion->query($query)->fetch(PDO::FETCH_ASSOC)["montant_total"];




    $reponse["status"] = true;

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>