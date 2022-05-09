<?php namespace App\Models;

use CodeIgniter\Model;
use \App\Models\DataAccess;

/**
 * Class Authentif
 *
 * Modèle comportant les fonctions utiles a la verification d'authentification ainsi que la création et la déstruction de variable de session.
 * Extend this class in any new controllers:
 * 
 */
class Authentif extends Model
{
	private $session;
	
    function __construct()
    {
        parent::__construct();
		$this->session = session();
    }

	 /**
	 * Teste si un quelconque visiteur est connecté
	 * 
	 * @return vrai ou faux 
	 */
	public function estConnecte()
	{
	  return !is_null($this->session->get('idUser'));
	}
	
	/**
	 * Enregistre dans une variable session les infos d'un visiteur
	 * 
	 * @param $id 
	 * @param $nom
	 * @param $prenom
	 */
	public function connecter($idUser,$nom,$prenom,$profil)
	{ // TODO : Lorsqu'il y aura d'autres profils d'utilisateurs (comptables, etc.)
	  // il faudra ajouter cette information de profil dans la session 
		$authUser = array(
                   'idUser'  => $idUser,
                   'nom' => $nom,
                   'prenom' => $prenom,
				   'profil' => $profil
				);

		$this->session->set($authUser);
	}

	/**
	 * Détruit la session active et redirige vers le contrôleur par défaut
	 */
	public function deconnecter()
	{
		$authUser = array('idUser', 'nom', 'prenom');
	
		$this->session->remove($authUser);
		$this->session->stop();

		return redirect()->to(site_url('anonyme'));
	}

	/**
	 * Vérifie en base de données si les informations de connexions sont correctes
	 * 
	 * @param $login
	 * @param $mdp
	 * @return : renvoie l'id, le nom et le prenom de l'utilisateur dans un tableau s'il est reconnu, sinon un tableau vide.
	 */
	public function authentifier ($login, $mdp) 
	{
		$dao = new DataAccess();
		$authUser = $dao->getInfosUtilisateur($login, $mdp);

		return $authUser;
	}
}