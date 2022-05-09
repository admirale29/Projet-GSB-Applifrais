<?php namespace App\Controllers;

use CodeIgniter\Controller;
use \App\Models\Authentif;
use \App\Models\ActionsComptable;

/**
 * Contrôleur du module VISITEUR de l'application
*/
class Comptable extends BaseController {

   private $authentif;
   private $actComptable;
   private $idComptable;
   
   private function checkAuth()
   {
		// contrôle de la bonne authentification de l'utilisateur
		// TODO : 	Lorsque des comptables utiliseront cette même application, il faudra enrichir 
		//			ce code. En effet les comptables n'ont pas le droit d'accéder à ce code et, par
		//			ailleurs, les visiteurs n'auront pas d'accès au controleur des comptables !!!
		$this->authentif = new Authentif();
		if (!$this->authentif->estConnecte()) 
		{
			$res = false;
		}
		else 
		{
			$this->actComptable = new ActionsComptable();
			$this->session = session();
			$this->idComptable = $this->session->get('idUser');
			//$this->actComptable->checkLastSix($this->idVisiteur);
			$res = true;
		}
		return $res;
	}


   private function unauthorizedAccess()
   {
		// l'accès à ce contrôleur n'est pas autorisé : on renvoie une vue erreur
		return view ('errors/html/error_401');
		// on aurait aussi  pu renvoyer vers le contrôleur par défaut comme suit : 
		//return redirect()->to(site_url('anonyme'));
   }


	public function index()
	{
		if (!$this->checkAuth()) return $this->unauthorizedAccess();
		// envoie de la vue accueil du visiteur
		$data['identite'] = $this->session->get('prenom').' '.$this->session->get('nom');

		return view('v_comptableAccueil', $data);
	}


	public function seDeconnecter()	
	{
		if (!$this->checkAuth()) return $this->unauthorizedAccess();
		return $this->authentif->deconnecter();
	}

		/**
	 * Récupere les informations du visiteur ainsi que ses fiches.
	 * 
	 * @param $message
	 * @return la vue MesFiches du visiteur.
	 */
	public function  lesFiches($message = "")
	{
		if (!$this->checkAuth()) return $this->unauthorizedAccess();
		$data['identite'] = $this->session->get('prenom').' '.$this->session->get('nom');
		$data['lesFiches'] = $this->actComptable->getFiches();
		$data['notify'] = $message;

		return view('v_comptableLesFiches', $data);	
	}
	public function miseEnPaiementFiche($idVisiteur, $mois){
		if (!$this->checkAuth()) return $this->unauthorizedAccess();
		$this->actComptable->miseEnPaiementFiche($idVisiteur, $mois);

		// ... et on revient à mesFiches
		return $this->lesFiches("La fiche $mois a été mise en paiement.");

	}
	public function validerFiche($idVisiteur, $mois){
		if (!$this->checkAuth()) return $this->unauthorizedAccess();
		$this->actComptable->validerFiche($idVisiteur, $mois);

		// ... et on revient à mesFiches
		return $this->lesFiches("La fiche $mois a été validée.");

	}
	public function refuserFiche($idVisiteur, $mois, $commentaire){
		if (!$this->checkAuth()) return $this->unauthorizedAccess();
		$this->actComptable->refuserFiche($idVisiteur, $mois, $commentaire);

		// ... et on revient à mesFiches
		return $this->lesFiches("La fiche $mois a été refusée.");

	}

	/**
	 * Récupere la vue pour afficher une fiche du visiteur.
	 * 
	 * @param $mois
	 * @return la vue VoirFiche du visiteur avec ses informations.
	 */
	public function voirLaFiche($idVisiteur, $mois)
	{	// TODO : contrôler la validité du paramètre (mois de la fiche à consulter)
	
		if (!$this->checkAuth()) return $this->unauthorizedAccess();
		$data['identite'] = $this->session->get('prenom').' '.$this->session->get('nom');
		$data['mois'] = $mois;
		$data['fiche'] = $this->actComptable->getUneFiche($idVisiteur, $mois);
		
		return view('v_comptableVoirFiche', $data);
	}

}
