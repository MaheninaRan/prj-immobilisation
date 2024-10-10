<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Amortissement extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Fournisseur_model');
        $this->load->model('Livraison_model');
        $this->load->model('Recepteur_model');
        $this->load->model('Amortissement_model');

    }


    public function indexLogin(){
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$data=array(
			'data'=>"Sysco",
			'fournisseur'=>$fournisseur
		);
		$this->load->view('header',$data);
        $this->load->view('amortissement/LoginChefCompta',$data);		
	}



    public function loginCompta(){
        $sessionAdmin=$this->session->userdata('idCompta');
		$fournisseur=$this->Fournisseur_model->allFournisseur();
        $marchandises=$this->Amortissement_model->getMarchandisesByStatut(1);
        if (isset($sessionAdmin)) {     
            $data=array(
                'data'=>"Dexonnexion",
			            'fournisseur'=>$fournisseur,
                        'marchandises'=>$marchandises

        );
            $this->load->view('header',$data);  
            $this->load->view('amortissement/indexComptable');
        }
      else{ 
            $email=$this->input->post('email');
            $motdepasse=$this->input->post('password');
            $valiny=$this->Amortissement_model->getCompta($email,$motdepasse); 

            if ($valiny==false){
                echo "diso leizy";
            }else{
                $this->session->set_userdata('idCompta',$valiny); 
                $data=array(
                        'data'=>"Dexonnexion",
			            'fournisseur'=>$fournisseur,
                        'marchandises'=>$marchandises
                );
                $this->load->view('header',$data);  
                $this->load->view('amortissement/indexComptable');
            }
      }
    }    

    function amortiObject(){
		$fournisseur=$this->Fournisseur_model->allFournisseur();
        $idMarhandises = $this->input->post('idmarchandises');
        $IdLivraison = $this->Amortissement_model->getIdLivraison($idMarhandises);
        $idAmmortissement=$this->Amortissement_model->getAmortissementType($IdLivraison);
        $getMarchandisesById = $this->Amortissement_model->getMarchandisesById($idMarhandises);
        $Utilisation=$this->Amortissement_model->getUtilisation($idMarhandises);
     
    
        date_default_timezone_set('Europe/Paris'); 
        $start_date = new DateTime($Utilisation[0]['date']);
        $end_date = new DateTime("2024-12-31");
        $diffDate = $start_date->diff($end_date);

        if ($idAmmortissement==1) {
            $nomAmortissement="Amortissement lineaire";
        }else {
           $nomAmortissement="Amortissement degressive";
        }
        $data=array(
            'data'=>"Dexonnexion",
            'fournisseur'=>$fournisseur,
            'marchandises'=>$getMarchandisesById,
            'utilisation'=>$Utilisation,
            'amortissement'=>$nomAmortissement,
            'diffDate'=>$diffDate

        );
        $this->load->view('header',$data);  
        if ($idAmmortissement==1) {
            $this->load->view('amortissement/AmortLineaireObjet',$data);
        }else {
            $this->load->view('amortissement/AmortAgressiveObject',$data);
        }

        
       
    }

   }
