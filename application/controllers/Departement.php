<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departement extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Fournisseur_model');
        $this->load->model('Service_model');
        $this->load->model('Recepteur_model');
        $this->load->model('Responsable_model');
		$this->load->helper('date');
    }

    function loginChef(){
		$sessionChef=$this->session->userdata('idChef');		
		if (isset($sessionChef)) {
			$fournisseur=$this->Fournisseur_model->allFournisseur();
			$allBesoin=$this->Service_model->allBesoin();
			$num=$this->Service_model->get_besoins_ordered_by_num(0);
			$data=array(
					'data'=>"Dexonnexion",
					'fournisseur'=>$fournisseur,
					'besoin'=>$allBesoin,
					'resultats'=>$num
			);
			$this->load->view('header',$data);
			$this->load->view('departement/chefDepartement',$data);
		}else {
			$email=$this->input->post('email');
			$motdepasse=$this->input->post('password');
			$valiny=$this->Service_model->getChef($email,$motdepasse); 
			if ($valiny==false){
				echo "diso leizy";
			}else{
				$this->session->set_userdata('idChef',$valiny); 
				$fournisseur=$this->Fournisseur_model->allFournisseur();
				$allBesoin=$this->Service_model->allBesoin();
				$num=$this->Service_model->get_besoins_ordered_by_num(0);
				$data=array(
						'data'=>"Dexonnexion",
						'fournisseur'=>$fournisseur,
						'besoin'=>$allBesoin,
						'resultats'=>$num
				);
				$this->load->view('header',$data);
				$this->load->view('departement/chefDepartement',$data);
			}
		}
		
    }
	
	public function connexionDepartement(){
		// $idDepartement=$this->session->userdata('idDepartement');
		// if (isset($idDepartement)) {
		// 	$fournisseur=$this->Fournisseur_model->allFournisseur();
		// 	$data=array(
		// 		'data'=>"Deconnexion",
		// 		'fournisseur'=>$fournisseur
		// 	);
		// 	$this->load->view('header',$data);
		// 	$this->load->view('departement/ServiceIndex');
		// }else {
			
			$idService = $this->input->post('id');
			$pass = $this->input->post('mdp');
			$query = $this->db->get_where('services', array('id' => $idService, 'mdp' => $pass));
			$services = $query->row_array();
			
			if ($services){
				$session_data = array('idDepartement' => $idService);
				$this->session->set_userdata($session_data);
				$fournisseur=$this->Fournisseur_model->allFournisseur();
				if ($idService==4){
					
					$num=$this->Service_model->get_besoins_ordered_by_num(1);
					$data=array(
						'data'=>"Deconnexion",
						'fournisseur'=>$fournisseur,
						'resultats'=>$num
					);
					$this->load->view('header',$data);
					$this->load->view('departement/proforma',$data);
				}
				if($idService==1  $idService==2 & $idService==3 ){	
					$selectProduit=$this->Fournisseur_model->allNomProduit();
					$allCompte = $this->Fournisseur_model->allCompte(); 
					$data=array(
						'data'=>"Deconnexion",
						'fournisseur'=>$fournisseur,
						'produit'=>$selectProduit,
						'compte'=>$allCompte
					);
					$this->load->view('header',$data);
					$this->load->view('departement/ServiceIndex',$data);
				}
				if ($idService==5){
					$this->session->set_userdata('idRecepteur',5); 
					$nonLivre =$this->Recepteur_model->getUndelivered();
					$data=array(
							'data'=>"Dexonnexion",
							'nonlivre'=>$nonLivre,
							'fournisseur'=>$fournisseur

					);
					
					$this->load->view('header',$data);
					$this->load->view('recepteur/indexReception',$data);
				}
				if($idService==6){
					$rechercheIdPers =$this->Responsable_model->rechercheIdPers($pass);
					echo $rechercheIdPers;
					$this->session->set_userdata('idPersonnelle',$rechercheIdPers);
					$marchandises=$this->Recepteur_model->AllMaterielSociete();
					$data=array(
							'data'=>"Dexonnexion", 
							'fournisseur'=>$fournisseur, 
							'marchandises'=>$marchandises
					);
					
					$this->load->view('header',$data);
					$this->load->view('departement/indexPersonnelle',$data);
				}
			}else{
				echo "Tsy mety";
				redirect(base_url('index.php/Welcome/index'));
			}
		// }
	}

   
	function nextBesoin(){
		$compte = $this->input->post("compte");
		echo "ttttt ! ", $compte;
		$genres =$this->Fournisseur_model->genreById($compte);
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$data=array(
			'data'=>"Deconnexion",
			'genre'=>$genres,
			'fournisseur'=>$fournisseur
		);
		$this->load->view('header',$data);
		$this->load->view('departement/insertBesoin',$data);
	}

    function proformaDetail(){
        $fournisseur=$this->Fournisseur_model->allFournisseur();
		$data=array(
			'data'=>"Deconnexion",
			'fournisseur'=>$fournisseur
		);
		$this->load->view('header',$data);
		$this->load->view('departement/proformaDetail',$data);
    }

	function autreBesoin(){
		$idDepartement=$this->session->userdata('idDepartement');
	    $dernierNum=$this->Service_model->dernierNum();
		$article=$this->input->post('genre');
		$num=$dernierNum[0]['max(num)']+1;
		$numMisy=$this->input->post('num');
		if ($numMisy=="misy") {
			$num=$dernierNum[0]['max(num)'];
		}
		$quantite=$this->input->post('quantite');  
		$dateBesoin=$this->input->post('dateBesoin'); 

		$data=array(
			'idService' =>$idDepartement,
			'num'=>$num,
			'article'=>$article,
			'quantite'=>$quantite,
			'dateEnvoie'=>'2023-11-19',
			'dateBesoin'=>$dateBesoin,
			'etat'=>0
		);
		$this->Service_model->insert_Besoin($data);
		$fournisseur=$this->Fournisseur_model->allFournisseur(); 
		$selectProduit=$this->Fournisseur_model->allNomProduit(); 
		$data=array(
			'data'=>"Deconnexion",
			'fournisseur'=>$fournisseur,
			'produit'=>$selectProduit,
			'num'=>$num
		);
		$this->load->view('header',$data);
		$this->load->view('departement/ServiceIndex',$data);
	}

	function EnvoyerBesoin(){
		$idDepartement=$this->session->userdata('idDepartement');
	    $dernierNum=$this->Service_model->dernierNum();
		$article=$this->input->post('genre');
		$num=$dernierNum[0]['max(num)']+1;
		$numMisy=$this->input->post('num');
		if ($numMisy=="misy") {
			$num=$dernierNum[0]['max(num)'];
		}
		$quantite=$this->input->post('quantite');  
		$dateBesoin=$this->input->post('dateBesoin'); 

		$data=array(
			'idService' =>$idDepartement,
			'num'=>$num,
			'article'=>$article,
			'quantite'=>$quantite,
			'dateEnvoie'=>'2023-11-19',
			'dateBesoin'=>$dateBesoin,
			'etat'=>0
		);
		$this->Service_model->insert_Besoin($data);
		$fournisseur=$this->Fournisseur_model->allFournisseur(); 
		$selectProduit=$this->Fournisseur_model->allProduit(); 
		$allCompte = $this->Fournisseur_model->allCompte(); 		
		$data=array(
			'data'=>"Deconnexion",
			'fournisseur'=>$fournisseur,
			'produit'=>$selectProduit,
			'num'=>$num,
			'compte'=>$allCompte

		);
		$this->load->view('header',$data);
		$this->load->view('departement/ServiceIndex',$data);
	}
   }
