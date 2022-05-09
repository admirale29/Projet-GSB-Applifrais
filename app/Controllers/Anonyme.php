<?php namespace App\Controllers;

use CodeIgniter\Controller;
use \App\Models\Authentif;

/**
 * Class Anonyme
 * 
 * Se charge de coordonner l'authentification utilisateur.
 */
class Anonyme extends BaseController
{

	public function index()
	{
		$authentif = new Authentif();
		if (!$authentif->estConnecte()) 
		{
			$data = array();
			return view('v_connexion', $data);
		}
		else
		{
			if($_SESSION['profil'] == 1){
				return redirect()->to(site_url('visiteur'));
			}
			elseif($_SESSION['profil'] == 2){
				return redirect()->to(site_url('comptable'));
			}
			$data = array('erreur'=>'type de personne inconnu');
			return view('v_connexion', $data);
		}
	}

	/**
	 * Traite le retour du formulaire de connexion afin de connecter l'utilisateur
	 * s'il est reconnu
	 * 
	 * @return la vue de connexion avec un message d'erreur
	 * ou
	 * @return la page d'accueil.
	*/
	public function seConnecter () 
	{	// TODO : conrôler que l'obtention des données postées ne rend pas d'erreurs 

		$login = $this->request->getPost('login');
		$mdp = $this->request->getPost('mdp');
		$authentif = new Authentif();
		$authUser = $authentif->authentifier($login, $mdp);

		if(empty($authUser))
		{
			$data = array('erreur'=>'Login ou mot de passe incorrect');
			return view('v_connexion', $data);
		}
		else
		{
			$authentif->connecter($authUser['id'], $authUser['nom'], $authUser['prenom'], $authUser['profil']);
			return $this->index();
		}
	}
}