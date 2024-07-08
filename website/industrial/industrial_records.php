<?php
require("../top_foot.inc.php");

$_SESSION['where'][0] = 'industrial';
$_SESSION['where'][1] = 'seiners';

top();

$radice = $_SERVER['HTTP_HOST'];
$self = $_SERVER['PHP_SELF'];

if(right_read($_SESSION['username'],5)) {
  ?>

  <a name="1"></a>
  <h2 style="background-image: url('../img/tuna.png'); background-repeat: no-repeat; padding-left: 230px;" >P&ecirc;che Thoni&egrave;re a la Senne<br/>Programme Observateurs</h2>
  <table id="results">
    <tr>
      <td>Route de navire</td>
      <td><a href="view_seiners_route.php?source=seiners&table=route&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="view_seiners_route.php?source=seiners&table=route&action=map"><i class="material-icons">location_on</i>Carte</a></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_seiners.php?source=seiners&table=route"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_seiners.php?source=seiners&table=route&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Activite sur Objets</td>
      <td><a href="view_seiners_objet.php?source=seiners&table=objet&action=show"><i class="material-icons">search</i>Voir</a></a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_seiners.php?source=seiners&table=objet"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_seiners.php?source=seiners&table=objet&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Statistiques thon retenu</td>
      <td><a href="view_seiners_thon_ret.php?source=seiners&table=thon_ret&action=show"><i class="material-icons">search</i>Voir</a></a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_seiners.php?source=seiners&table=thon_ret"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_seiners.php?source=seiners&table=thon_ret&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Statistiques thon rejet&eacute;</td>
      <td><a href="view_seiners_thon_rej.php?source=seiners&table=thon_rej&action=show"><i class="material-icons">search</i>Voir</a></a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_seiners.php?source=seiners&table=thon_rej"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_seiners.php?source=seiners&table=thon_rej&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Taille thon rejet&eacute;</td>
      <td><a href="view_seiners_thon_rej_taille.php?source=seiners&table=thon_rej_taille&action=show"><i class="material-icons">search</i>Voir</a></a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_seiners.php?source=seiners&table=thon_rej_taille"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_seiners.php?source=seiners&table=thon_rej_taille&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Statistiques capture associ&eacute;e</td>
      <td><a href="view_seiners_prise_access.php?source=seiners&table=prise_access&action=show"><i class="material-icons">search</i>Voir</a></a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_seiners.php?source=seiners&table=prise_access"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_seiners.php?source=seiners&table=prise_access&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Taille capture associ&eacute;e</td>
      <td><a href="view_seiners_prise_access_taille.php?source=seiners&table=prise_access_taille&action=show"><i class="material-icons">search</i>Voir</a></a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_seiners.php?source=seiners&table=prise_access_taille"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_seiners.php?source=seiners&table=prise_access_taille&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
  </table>

  <a name="2"></a>
  <h2 style="background-image: url('../img/fish.png'); background-repeat: no-repeat; padding-left: 230px;" >P&ecirc;che Chalutier<br/>Programme Observateurs </h2>
  <table id="results">
    <tr>
      <td>D&eacute;tails lance &eacute;chantillion&eacute; (montant rejets et echantillion)</td>
      <td><a href="view_trawlers_route.php?source=trawlers&table=route&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="view_trawlers_route.php?source=trawlers&table=route&action=map"><i class="material-icons">location_on</i>Carte</a></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=route"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=trawlers&table=route&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Capture par Lanc&eacute; (3 &eacute;chantillons par jour)</td>
      <td><a href="view_trawlers_captures.php?source=trawlers&table=production&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=production"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=trawlers&table=production&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Rapport Production par Lanc&eacute; (3 &eacute;chantillons par jour)</td>
      <td><a href="view_trawlers_p_lance.php?source=trawlers&table=p_lance&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=p_lance"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=seiners&table=p_lance&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Production Quotidienne (1 fois par jour)</td>
      <td><a href="view_trawlers_p_day.php?source=trawlers&table=p_day&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=p_day"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=seiners&table=p_day&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Fr&eacute;quence de Taille de Crevette (1 fois par jour)</td>
      <td><a href="view_trawlers_ft_cre.php?source=trawlers&table=ft_cre&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=ft_cre"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=seiners&table=ft_cre&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Cat&eacute;gorie de March&eacute; de Crevette</td>
      <td><a href="view_trawlers_cm_cre.php?source=trawlers&table=cm_cre&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=cm_cre"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=seiners&table=cm_cre&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Fr&eacute;quence de Taille de Poisson (1 fois par jour)</td>
      <td><a href="view_trawlers_ft_poi.php?source=trawlers&table=ft_poi&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=ft_poi"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=seiners&table=ft_poi&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Cat&eacute;gorie de March&eacute; de Poisson</td>
      <td><a href="view_trawlers_cm_poi.php?source=trawlers&table=cm_poi&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=cm_poi"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=seiners&table=cm_poi&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Relation Poids-Taille (chaque lance&eacute;)</td>
      <td><a href="view_trawlers_poids_taille.php?source=trawlers&table=poids_taille&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=poids_taille"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=trawlers&table=poids_taille&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
  </table>

  <h2 style="background-image: url('../img/shark.png'); background-repeat: no-repeat; padding-left: 250px;" >Esp&egrave;ces sensibles<br/>Programme Observateurs</h2>
  <table id="results">
    <tr>
      <td>Route de navire</td>
      <td><a href="view_trawlers_route_accidentelle.php?source=trawlers&table=route_accidentelle&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="view_trawlers_route_accidentelle.php?source=trawlers&table=route_accidentelle&action=map"><i class="material-icons">location_on</i>Carte</a></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=route_accidentelle"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=trawlers&table=route_accidentelle&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Capture et observation de mammif&eacute;res marin</td>
      <td><a href="view_trawlers_captures_mammal.php?source=trawlers&table=captures_mammal&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=captures_mammal"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=trawlers&table=captures_mammal&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Capture et observation de requins, rais et molas</td>
      <td><a href="view_trawlers_captures_requin.php?source=trawlers&table=captures_requin&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=captures_requin"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=trawlers&table=captures_requin&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Capture et observation de tortues</td>
      <td><a href="view_trawlers_captures_tortue.php?source=trawlers&table=captures_tortue&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],5,2)) {print '<a href="input_trawlers.php?source=trawlers&table=captures_tortue"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="download_trawlers.php?source=trawlers&table=captures_tortue&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
  </table>
  <?php
}
?>

<h3>Rapports de fin d'annee</h3>
<table id="results">
  <tr><td><a href="./files/pdf/2015_Observateurs_Etat_des_lieux_peche_industrielle.pdf">2015 Observateurs Etat des lieux peche industrielle (pdf)</a></td></tr>
  <tr><td><a href="./files/pdf/2015_Observateurs_Etat_des_lieux_peche_industrielle_resume.pdf">2015 Observateurs Etat des lieux peche industrielle resume (pdf)</a></td></tr>
  <!--<tr><td><a href="./files/pdf/2016_Observateurs_Etat_des_lieux_peche_industrielle+Annexes.pdf">2016 Observateurs Etat des lieux peche industrielle Annexes (pdf)</a></td></tr>-->
  <!--<tr><td><a href="./files/pdf/2016_Observateurs_Etat_des_lieux_peche_industrielle_resume.pdf">2016 Observateurs Etat des lieux peche industrielle resume (pdf)</a></td></tr>-->
  <tr><td><a href="./files/pdf/2016_Situation_thoniers_Observateurs_de_peche.pdf">2016 Situation thoniers Observateurs de peche (pdf)</a></td></tr>
  <tr><td><a href="./files/pdf/2017_Situation_thoniers_Observateurs_de_peche+Annexe.pdf">2017 Situation thoniers Observateurs de peche + Annexe(pdf)</a></td></tr>
  <tr><td><a href="./files/pdf/2018_Situation_thoniers_Observateurs_de_peche_Resume.pdf">2018 Situation thoniers Observateurs de peche - Resume (pdf)</a></td></tr>
  <tr><td><a href="./files/pdf/2018_Situation_thoniers_Observateurs_de_peche_Capture_Totale.pdf">2018 Situation thoniers Observateurs de peche - Capture Totale (pdf)</a></td></tr>
  <tr><td><a href="./files/pdf/2018_Situation_thoniers_Observateurs_de_peche_Especes_Sensibles.pdf">2018 Situation thoniers Observateurs de peche - Especes Sensibles (pdf)</a></td></tr>
  <tr><td><a href="./files/pdf/2019_Situation_thoniers_Observateurs_de_peche_Resume.pdf">2019 Situation thoniers Observateurs de peche - Resume (pdf)</a></td></tr>
  <tr><td><a href="./files/pdf/Fiches_Protocole_Peche_Thoniere.pdf">Fiches Protocole Peche Thoniere (pdf)</a></td></tr>
  <tr><td><a href="./files/pdf/Protocole_Peche_Thoniere.pdf">Protocole Peche Thoniere (pdf)</a></td></tr>
</table>

<h3>Rapports automatiques</h3>
<table id="results">
  <tr><td>Rapport Peche Chalutier</td>
    <td><a href="analysis_trawlers.php"><i class="material-icons">search</i>Voir</a></td>
  </tr>
  <!--<tr><td>Rapport mar&eacute;e sennier<a href="./analysis_seiners.php"></a></td></tr>-->
</table>


<?php
if(right_read($_SESSION['username'],7)) {
  ?>

  <a name="3"></a>
  <h2 style="background-image: url('../img/shrimp.png'); background-repeat: no-repeat; padding-left: 230px;" >P&ecirc;che Crevettier<br/>Logbook Capitaine</h2>
  <table id="results">
    <tr>
      <td>Capture Crevettes par Lanc&eacute;e</td>
      <td><a href="crevette/view_crevette_lance.php?source=crevette&table=lance&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="crevette/view_crevette_lance.php?source=crevette&table=lance&action=map"><i class="material-icons">location_on</i>Carte</a></td>
      <td><?php if(right_write($_SESSION['username'],8,2) OR right_write($_SESSION['username'],5,2)) {print '<a href="crevette/input_crevette.php?source=crevette&table=lance"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="crevette/download_crevette.php?source=crevette&table=production&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Prise Accessoires</td>
      <td><a href="crevette/view_crevette_captures.php?source=crevette&table=prise_access&action=show"><i class="material-icons">search</i>Voir</a></a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],8,2) OR right_write($_SESSION['username'],5,2)) {print '<a href="crevette/input_crevette.php?source=crevette&table=captures"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="crevette/download_crevette.php?source=crevette&table=prise_access&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
  </table>

  <a name="4"></a>
  <h2 style="background-image: url('../img/fish.png'); background-repeat: no-repeat; padding-left: 230px;" >P&ecirc;che Poissonnier<br/>Logbook Capitaine</h2>
  <table id="results">
    <tr>
      <td>Details maree</td>
      <td><a href="poisson/view_poisson_maree.php?source=poisson&table=maree&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><?php if(right_write($_SESSION['username'],8,2) OR right_write($_SESSION['username'],5,2)) {print '<a href="poisson/input_poisson.php?source=poisson&table=maree"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="poisson/download_poisson.php?source=poisson&table=production&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Capture poissons par maree</td>
      <td><a href="poisson/view_poisson_captures.php?source=poisson&table=captures&action=show"><i class="material-icons">search</i>Voir</a></a></td>
      <td><?php if(right_write($_SESSION['username'],8,2)  OR right_write($_SESSION['username'],5,2)) {print '<a href="poisson/input_poisson.php?source=poisson&table=captures"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="poisson/download_poisson.php?source=poisson&table=prise_access&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
  </table>

  <a name="5"></a>
  <h2 style="background-image: url('../img/tuna.png'); background-repeat: no-repeat; padding-left: 230px;" >P&ecirc;che Thoniere<br/>Logbook Capitaine</h2>
  <table id="results">
    <tr>
      <td>Declarations Capture</td>
      <td><a href="thon/view_thon_lance.php?source=thon&table=lance&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td><a href="thon/view_thon_lance.php?source=thon&table=lance&action=map"><i class="material-icons">location_on</i>Carte</a></td>
      <td><?php if(right_write($_SESSION['username'],7,2)) {print '<a href="thon/input_thon.php?source=thon&table=lance"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="thon/download_thon.php?source=thon&table=lance&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
    <tr>
      <td>Rapports Entre/Sortie</td>
      <td><a href="thon/view_thon_entreesortie.php?source=thon&table=captures&action=show"><i class="material-icons">search</i>Voir</a></td>
      <td></td>
      <td><?php if(right_write($_SESSION['username'],7,2)) {print '<a href="thon/input_thon.php?source=thon&table=entreesortie"><i class="material-icons">create</i>Saisir</a>';} ?></td>
      <td><a href="thon/download_thon.php?source=thon&table=entreesortie&action=download"><i class="material-icons">file_download</i>T&eacute;l&eacute;charger</a></td>
    </tr>
  </table>

  <?php

}

foot();
