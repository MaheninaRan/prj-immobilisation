<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Responsable extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Fournisseur_model');
        $this->load->model('Livraison_model');
        $this->load->model('Recepteur_model');
        $this->load->model('Responsable_model');
    }


    public function indexLogin(){
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$data=array(
			'data'=>"Sysco",
			'fournisseur'=>$fournisseur
		);
		$this->load->view('header',$data);
        $this->load->view('responsable/LoginChefActif',$data);		
	}



    function loginResponsable(){
        $sessionAdmin=$this->session->userdata('idResponsable');
		$fournisseur=$this->Fournisseur_model->allFournisseur();
        $allMarchandises=$this->Responsable_model->getAllMarchandises();
        if (isset($sessionAdmin)) {                 
            $data=array(
                'data'=>"Dexonnexion",
			    'fournisseur'=>$fournisseur,
                'marchandises'=>$allMarchandises
        );
            $this->load->view('header',$data);  
            $this->load->view('responsable/IndexResponsable');
        }
      else{ 
            $email=$this->input->post('email');
            $motdepasse=$this->input->post('password');
            $valiny=$this->Responsable_model->getResponsable($email,$motdepasse); 
            if ($valiny==false){
                echo "diso leizy";
            }else{
                $this->session->set_userdata('idResponsable',$valiny); 
                $data=array(
                    'data'=>"Dexonnexion",
                    'fournisseur'=>$fournisseur,
                    'marchandises'=>$allMarchandises
                );
                $this->load->view('header',$data);  
                $this->load->view('responsable/IndexResponsable');
            }
      }
    }

    function utilisation(){
        $fournisseur=$this->Fournisseur_model->allFournisseur();
		$data=array(
			'data'=>"Sysco",
			'fournisseur'=>$fournisseur
		);
		$this->load->view('header',$data);
        $this->load->view('responsable/Utilisation',$data);		
    }


   }
