	<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materiel extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('Materiel_model');
        $this->load->model('Fournisseur_model');
        $this->load->model('Recepteur_model');

	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$marchandises = $this->Materiel_model->selectMarchandises();
		$personnelle=$this->Materiel_model->selectpersonnelle();
		$data=array(
					'personnelle'=>$personnelle,
					'marchandises' =>$marchandises
			);
		$this->load->view('IndexResponsable');
	}
	public function utilisation(){
		$marchandises = $this->Materiel_model->selectMarchandises(0);
		$personnelle=$this->Materiel_model->selectpersonnelle();
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$lieu=$this->Materiel_model->selectLieu();
		$data=array(
					'personnelle'=>$personnelle,
					'marchandises' =>$marchandises,
					'fournisseur'=>$fournisseur,
					'lieu'=>$lieu,
					'data'=>"Deconnexion"
			);
		$this->load->view('header',$data);
		$this->load->view('responsable/Utilisation',$data);
	}

	public function suivie(){
		$marchandises = $this->Materiel_model->selectMarchandises2(1);
		$personnelle=$this->Materiel_model->selectpersonnelle();
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$lieu=$this->Materiel_model->selectLieu();
		
		$data=array(
				'personnelle'=>$personnelle,
				'marchandises' =>$marchandises,
				'data'=>"Deconnexion",
				'lieu'=>$lieu,
				'fournisseur'=>$fournisseur
			);
		$this->load->view('header',$data);
		$this->load->view('responsable/Suivie',$data);

	}

	public function insertData() {
		$marchandises=$this->input->post('materiel');
        $personnelle=$this->input->post('personnelle');
        $desc=$this->input->post('descri');
		$date=$this->input->post('dates');
		$duree=$this->input->post('dure');
		$taux=(1/$duree)*100;
        $data_to_insert = array(
            'idmarchandises' =>$marchandises,
			'idpersonnelle'=>$personnelle,
            'descript' =>$desc,
			'etat'=>$this->input->post('etat'),
			'lieu'=>$this->input->post('lieu'),
			'dureedevie'=>$duree,
			'date'=>$date,
            'tauxAmorti' => $taux,
			'statut'=>0
        );
		$result1=$this->Materiel_model->updateStatusM($marchandises,1);
        $result = $this->Materiel_model->insertUtilisation($data_to_insert);
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$data=array(
			'data'=>"Deconnexion",
			'fournisseur'=>$fournisseur
		);
		$this->load->view('header',$data);
		$this->load->view('responsable/indexResponsable');
    }

	public function insertS(){
		$marchandises=$this->input->post('materiel');
        $personnelle=$this->input->post('personnelle');
        $desc=$this->input->post('descri');
		$etat=$this->input->post('etat');
		$lieu=$this->input->post('lieu');
		date_default_timezone_set('Europe/Paris');
		$date =date('Y-m-d H:i:s');
        $data_to_insert = array(
            'idmarchandise' =>$marchandises,
			'idpersonnelle'=>$personnelle,
            'descript' =>$desc,
			'lieu'=>$lieu,
			'etat'=>$etat,
			'datesuivie'=>$date,
			'statut'=>0
        );
        $result = $this->Materiel_model->insertSuivie($data_to_insert);
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$data=array(
			'data'=>"Sysco",
			'fournisseur'=>$fournisseur
		);
		$this->load->view('header',$data);
		$this->load->view('responsable/indexResponsable');
    }

	public function ListS(){
		$idMarchandise = $this->input->post('materiel');
		$suivie=$this->Materiel_model->selectSuivie($idMarchandise);
		$livraisonSelect=$this->Recepteur_model->getNonLivreById($idMarchandise);
        $societe = $this->Recepteur_model->getSocieteById(1);
        $amortissement=$this->Recepteur_model->getAmortissement();
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$maintenance=$this->Materiel_model->selectMaintenance($idMarchandise);

		$data=array(
			'data'=>"Dexonnexion",
            'nonlivre'=>$livraisonSelect,
            'societe'=>$societe,
            'amortissement'=>$amortissement,
			'fournisseur'=>$fournisseur,
			'v_maintenance'=>$maintenance,
			'suivie_v'=>$suivie
		);
		$this->load->view('header',$data);
		$this->load->view('responsable/listeSuivie');
	}

		
	public function maintenance(){
		$marchandises = $this->Materiel_model->selectMarchandises(1);
		$fournisseur=$this->Fournisseur_model->allFournisseur();

		$data=array(
			'marchandises' =>$marchandises,
			'fournisseur'=>$fournisseur,
			'data'=>"Dexonnexion"
	);
	$this->load->view('header',$data);
	$this->load->view('responsable/maintenance');
	}

	
	public function insertM() {
        $this->load->model('Materiel_model');
		$marchandises=$this->input->post('materiel');
		$idreception=$this->input->post('idreception');
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$maintenance_materielByCategorie=$this->Fournisseur_model->maintenance_materielByCategorie($marchandises);
        $dates=$this->input->post('dates');
        $data_to_insert = array(
			'dates'=>$dates,
            'idmarchandise' =>$marchandises,
			'idreception'=>$idreception,
			'maintenance_materielByCategorie' => $maintenance_materielByCategorie
        );

		$data=array(
			'fournisseur'=>$fournisseur,
			'data'=>"Dexonnexion"
		);
        $this->load->view('header',$data);
		$this->load->view('responsable/maintenance2',$data_to_insert);
    }


	public function insertM1() {
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$idreception=$this->input->post('idreception');
		$idmaintenance=$this->input->post('idmaintenance');
		date_default_timezone_set('Europe/Paris');
		$date =date('Y-m-d H:i:s');
		$prix=$this->input->post('prix');
        $data_to_insert = array(
			'dates'=>$date,
            'idreception' =>$idreception,
			'idmaintenance_materiel' => $idmaintenance,
			'prix' => $prix
        );
		
		$result = $this->Materiel_model->insertMaintenance($data_to_insert);

		$data=array(
			'fournisseur'=>$fournisseur,
			'data'=>"Dexonnexion"
		);
		$this->load->view('header',$data);
    }


}
