<?= $this->extend('l_comptable') ?>

<?= $this->section('body') ?>
<div id="contenu">
	<h2>Liste de mes fiches de frais</h2>
	 	
	<?php if(!empty($notify)) echo '<p id="notify" >'.$notify.'</p>';?>
	 
	<table class="listeLegere">
		<thead>
			<tr>
                <th >Mois</th>
                <th >Nom/prénom</th>
				<th >Etat</th>  
				<th >Montant</th>  
				<th >Date modif.</th>  
				<th  colspan="4">Actions</th>              
			</tr>
		</thead>
		<tbody>
          
		<?php    
			foreach($lesFiches as $uneFiche) 
			{
				$link = '';
				$reLink = '';
				$mpLink = '';
				$idVisiteur = $uneFiche['idVisiteur'];
				$mois = $uneFiche['mois'];

				if ($uneFiche['id'] == 'CL') {
					$link = anchor('comptable/validerFiche/'.$idVisiteur.'/'.$mois, 'Valider',  'title="Valider la fiche" onclick="return confirm(\'Voulez-vous vraiment valider cette fiche ?\');"');
					$reLink = "<a onclick=\"confirmPrompt('$idVisiteur','$mois')\">Refuser</a>";
					//$reLink = "<a onclick=\"confirmPrompt('b13','202109');\">Refuser</a>";
				}elseif($uneFiche['id'] == 'VA'){
					$link = anchor('comptable/miseEnPaiementFiche/'.$idVisiteur.'/'.$mois, 'Mettre en paiement',  'title="Mettre en paiement la fiche" onclick="return confirm(\'Voulez-vous vraiment mettre en paiement cette fiche ?\');"');
				}

				echo 
				'<tr>
                    <td class="date">'.anchor('comptable/voirLaFiche/'.$idVisiteur.'/'.$uneFiche['mois'], $uneFiche['mois'],  'title="Consulter la fiche"').'</td>
                    <td class="nom">'.$uneFiche['nom'].' '.$uneFiche['prenom'].'</td>
					<td class="libelle">'.$uneFiche['libelle'].'</td>
					<td class="montant">'.$uneFiche['montantValide'].'</td>
					<td class="date">'.$uneFiche['dateModif'].'</td>
					<td class="action">'.$link.'</td>
					<td class="action">'.$reLink.'</td>
				</tr>';
			}
		?>	  
		</tbody>
		<script type="text/javascript">
			 function confirmPrompt(idVisiteur, mois){
				var commentaire = prompt('Motif du refus ?',);

			 	if(commentaire){
			 		if(confirm('Confirmer le refus ? Motif : ' + commentaire)){
			 			window.location.href = 'refuserFiche/' + idVisiteur + '/' + mois + '/' + commentaire;
			 		}
				
			 	}
			 	else{
			 		alert('Aucun motif saisi');
			 	}
			 }
		</script>
    </table>

</div>
<?= $this->endSection() ?>