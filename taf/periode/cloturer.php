<?php
try {
    require './config.php';
    $params=$add_params;
    
    if(count($params)==0){
        $reponse["status"] = false;
        $reponse["erreur"] = "Parameters required";
        echo json_encode($reponse);
        exit;
    }
    // pour charger l'heure courante
    // $params["date_enregistrement"]=date("Y-m-d H:i:s");
    $id_periode=$params["id_periode"];
    $id_tontine=$params["id_tontine"];
    $mois_actuel=$params["mois_actuel"];
    $annee_actuelle=$params["annee_actuelle"];
    $periode_amande=3;
    // créer des amandes pour les personnes qui n'ont pas cotise depuis trois mois
    $query="with periode_passees as (
        select * from periode where id_tontine=$id_tontine and mois<=$mois_actuel and annee<=$annee_actuelle order by mois, annee
        ),membre_cotisation as(
        select p.id_periode,c.id_cotisation, c.id_membre from periode_passees p join cotisation c on p.id_periode=c.id_periode and p.id_tontine=$id_tontine order by p.id_periode,c.id_cotisation
          ),
          tout_les_membres as(
        select m.id_membre, m.prenom,m.nom,p.id_periode from membre m ,periode_passees p where m.id_tontine=$id_tontine and p.id_tontine=$id_tontine order by m.id_membre
          )      
          select tlm.id_membre,count(tlm.id_membre) periode_non_cotisee from tout_les_membres tlm left join membre_cotisation mc on tlm.id_membre=mc.id_membre and tlm.id_periode=mc.id_periode WHERE mc.id_periode is null group by tlm.id_membre having periode_non_cotisee>=$periode_amande order by periode_non_cotisee desc";
    // $reponse["query"]=$query;
    $resultat=$connexion->query($query);
    while ($ligne=$resultat->fetch(PDO::FETCH_ASSOC)) {
        $reponse["data"][] = $connexion->exec(dynamicInsert("amande",array(
            "id_tontine"=>$id_tontine,
            "id_membre"=>$ligne["id_membre"],
            "id_type_amande"=>1,
            "etat_amande"=>0,
            "id_periode"=>$id_periode,
        )));
    } 
    $connexion->exec("update periode set etat_periode=1 where id_periode=$id_periode");
    $reponse["status"]=true;
    echo json_encode($reponse);
} catch (\Throwable $th) {
    
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>