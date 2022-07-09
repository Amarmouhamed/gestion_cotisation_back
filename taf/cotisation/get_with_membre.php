<?php
try {
    require './config.php';
    $params = $add_params;

    // $reponse["condition"]=$condition;
    $id_tontine = $params["id_tontine"];
    $id_periode = $params["id_periode"];
    $query = "with amande_membre as (
        select id_membre,sum(ta.montant) as montant_amande_total from amande a join type_amande ta on a.id_type_amande=ta.id_type_amande where a.id_tontine=$id_tontine and a.etat_amande=0 group by a.id_membre
        ),montant_total_membre as (
            select id_membre,sum(montant) as montant_total from cotisation group by id_membre
            )
        SELECT m.id_membre,m.matricule,m.prenom,m.nom,m.date_adhesion,c.*, am.montant_amande_total, mtm.montant_total FROM membre m join cotisation c on m.id_membre=c.id_membre left  join amande_membre am on m.id_membre=am.id_membre left join montant_total_membre mtm on m.id_membre=mtm.id_membre where c.id_tontine=$id_tontine and  id_periode=$id_periode";
        // $reponse["query_encaissees"]=$query;
    $reponse["data"]["encaissees"] = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);

    $query = "with amande_membre as (
                 select id_membre,sum(ta.montant) as montant_amande_total from amande a join type_amande ta on a.id_type_amande=ta.id_type_amande where a.id_tontine=$id_tontine and a.etat_amande=0 group by a.id_membre
            ),membre_tontine as (
                select m.*,am.montant_amande_total FROM membre m left join amande_membre am on m.id_membre=am.id_membre where id_tontine=$id_tontine
            ),montant_total_membre as (
                select id_membre,sum(montant) as montant_total from cotisation group by id_membre
                )
            SELECT m.id_membre,m.matricule,m.prenom,m.nom,m.date_adhesion,m.adresse,m.id_tontine, m.montant_amande_total, mtm.montant_total FROM membre_tontine m left join montant_total_membre mtm on m.id_membre=mtm.id_membre WHERE m.id_membre not in (select id_membre from cotisation where id_periode=$id_periode and id_tontine=$id_tontine) ";
    $reponse["data"]["non_encaissees"] = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $reponse["status"] = true;

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}
