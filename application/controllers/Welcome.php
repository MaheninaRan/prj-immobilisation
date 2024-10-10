<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Fournisseur_model');
        $this->load->model('Service_model');
		$this->load->helper('file');
    }

	public function index(){
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$departement=$this->Service_model->selectDepartement();
		$data=array(
				'data'=>"Sysco",
				'fournisseur'=>$fournisseur,
				'departement'=>$departement
		);
		$this->load->view('header',$data);
        $this->load->view('index',$data);
	}	

	public function connexionDepartement(){
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$departement=$this->Service_model->selectDepartement();
		$data=array(
				'data'=>"Deconnexion",
				'fournisseur'=>$fournisseur,
				'departement'=>$departement
		);
		$this->load->view('header',$data);
        $this->load->view('departement/ServiceIndex');
	}

	public function deconnexion(){
		$this->session->sess_destroy();
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$departement=$this->Service_model->selectDepartement();
		$data=array(
				'data'=>"Sysco",
				'fournisseur'=>$fournisseur,
				'departement'=>$departement
		);
		$this->load->view('header',$data);
        $this->load->view('index');		
	}

	public function fournisseur(){
		$fournisseurAnarana =$_GET['id'];
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$departement=$this->Service_model->selectDepartement();
		$data=array(
			'data'=>"Sysco",
			'fournisseur'=>$fournisseur,
			'nomFournisseur'=>$fournisseurAnarana,
			'departement'=>$departement
		);
		$this->load->view('header',$data);
        $this->load->view('fournisseur/login',$data);		
	}

	public function connexionFournisseur(){
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$departement=$this->Service_model->selectDepartement();
		$data=array(
				'data'=>"Deconnexion",
				'fournisseur'=>$fournisseur,
				'departement'=>$departement
		);
		$this->load->view('header',$data);
		$fournisseur['data'] = $this->input->post("fournisseur");
        $this->load->view('fournisseur/fournisseur',$fournisseur);		
	}

	function ajoutArticle(){
		$sessionAdmin=$this->session->userdata('idFournisseur');
		echo "Session : " , $sessionAdmin;
		$fournisseurNom=$this->Fournisseur_model->fournisseurSelect($sessionAdmin);
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $data=array(
            'fournisseur'=>$fournisseur,
            'nom'=>$fournisseurNom
        );
        $this->load->view('fournisseur/headFournisseur',$data);  
        $this->load->view('fournisseur/ajoutArticle',$fournisseur);		
	}

	function chefDeparte(){
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$departement=$this->Service_model->selectDepartement();
		$data=array(
				'data'=>"SysCo",
				'fournisseur'=>$fournisseur,
				'departement'=>$departement
		);
		$this->load->view('header',$data);
        $this->load->view('departement/loginChef');		
	}
	
}
