<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recepteur extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Fournisseur_model');
        $this->load->model('Livraison_model');
        $this->load->model('Recepteur_model');

    }

    public function indexLogin(){
		$fournisseur=$this->Fournisseur_model->allFournisseur();
		$data=array(
			'data'=>"Sysco",
			'fournisseur'=>$fournisseur
		);
		$this->load->view('header',$data);
        $this->load->view('recepteur/loginChef',$data);		
	}



    function loginRecepteur(){
        $sessionAdmin=$this->session->userdata('idRecepteur');
        if (isset($sessionAdmin)) {     
            $data=array(
                'data'=>"Dexonnexion"
        );
            $this->load->view('header',$data);  
            $this->load->view('recepteur/IndexReception');
        }
      else{ 
            $email=$this->input->post('email');
            $motdepasse=$this->input->post('password');
            $valiny=$this->Recepteur_model->getRecepteur($email,$motdepasse); 
		    $fournisseur=$this->Fournisseur_model->allFournisseur();

            if ($valiny==false){
                echo "diso leizy";
            }else{
                $this->session->set_userdata('idRecepteur',$valiny); 
                $nonLivre =$this->Recepteur_model->getUndelivered();
                $data=array(
                        'data'=>"Dexonnexion",
                        'nonlivre'=>$nonLivre,
			            'fournisseur'=>$fournisseur

                );
                $this->load->view('header',$data);  
                $this->load->view('recepteur/IndexReception');
            }
      }
    }

    function detailReception(){
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $idNonLivre =$_GET['idSend'];
        $livraisonSelect=$this->Recepteur_model->getNonLivreById($idNonLivre);
        $societe = $this->Recepteur_model->getSocieteById(1);
        $amortissement=$this->Recepteur_model->getAmortissement();
        $data=array(
            'data'=>"Dexonnexion",
            'nonlivre'=>$livraisonSelect,
            'fournisseur'=>$fournisseur,
            'societe'=>$societe,
            'amortissement'=>$amortissement
        );
        $this->load->view('header',$data);  
        $this->load->view('recepteur/detailReception');
    }
    
    function insertReception(){
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $idAmortissement=$this->input->post("amortissement");
        $idlivraison=$this->input->post("idlivraison");
        $idMarchandise=$this->input->post("idMarchandises");
        $categorie=$this->input->post("categorie");
        echo $categorie;
        $this->Recepteur_model->UpdateMarchandise($idMarchandise,1);
        $dataInsert= array(
            'idlivraison'=>$idlivraison,
            'amortissement'=>$idAmortissement,
            'statut'=>0
        );
        $this->Recepteur_model->insert_reception($dataInsert);
        $nonLivre =$this->Recepteur_model->getUndelivered();
        $data=array(
            'data'=>"Sysco",
            'fournisseur'=>$fournisseur,
            'idlivraison'=>$idlivraison
        );
        if($categorie=="Voiture"){
            $this->load->view('header',$data);
            $this->load->view('recepteur/details',$data);
        }

        if($categorie=="Moto"){
            $this->load->view('header',$data);
            $this->load->view('recepteur/detailmoto',$data);
        }

        if($categorie=="Ordinateur"){
            $this->load->view('header',$data);
            $this->load->view('recepteur/detailordi',$data);
        }
        
    }


    function insertDetailVoiture(){
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $couleur=$this->input->post("couleur");
        $moteur=$this->input->post("moteur");
        $datevisite=$this->input->post("datevisite");
        $assurance=$this->input->post("assurance");
        $vidange=$this->input->post("vidange");
        $annesortie=$this->input->post("anneesortie");
        $idmarchandise=$this->input->post("idlivraison");
        
        $data1= array (
            'idlivraison'=>$idmarchandise,
            'couleur'=>$couleur,
            'moteur'=>$moteur,
            'datevisite'=>$datevisite,
            'assurance'=>$assurance,
            'vidange'=>$vidange,
            'annesortie'=>$annesortie
            
        );
        $this->Recepteur_model->insert_detailvoiture($data1);

        $data=array(
            'data'=>"Sysco",
            'fournisseur'=>$fournisseur
        );
        
        $this->load->view('header',$data);  
        $this->load->view('index'); 
        

    }

    function insertDetailMoto(){
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $couleur=$this->input->post("couleur");
        $vinette=$this->input->post("vinette");
        $assurance=$this->input->post("assurance");
        $vidange=$this->input->post("vidange");
        $cc=$this->input->post("cc");
        $moteur=$this->input->post("moteur");
        $anneesortie=$this->input->post("anneesortie");
        $idmarchandise=$this->input->post("idlivraison");
        
        $data1= array (
            'idlivraison'=>$idmarchandise,
            'couleur'=>$couleur,
            'vinette'=>$vinette,
            'assurance'=>$assurance,
            'vidange'=>$vidange,
            'cc'=>$cc,
            'moteur'=>$moteur,
            'annesortie'=>$annesortie
            
        );
        $this->Recepteur_model->insert_detailmoto($data1);

        $data=array(
            'data'=>"Sysco",
            'fournisseur'=>$fournisseur
        );
        
        $this->load->view('header',$data);  
        $this->load->view('index'); 
        

    }
    
    function insertDetailOrdi(){
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $couleur=$this->input->post("couleur");
        $graphe=$this->input->post("graphe");
        $ram=$this->input->post("ram");
        $systemeexploitation=$this->input->post("systemeexploitation");
        $processeur=$this->input->post("processeur");
        $memoire=$this->input->post("memoire");
        $ecran=$this->input->post("ecran");
        $idmarchandise=$this->input->post("idlivraison");
        
        $data1= array (
            'idlivraison'=>$idmarchandise,
            'couleur'=>$couleur,
            'graphe'=>$graphe,
            'ram'=>$ram,
            'systemeexploitation'=>$systemeexploitation,
            'processeur'=>$processeur,
            'memoire'=>$memoire,
            'ecran'=>$ecran
            
        );
        $this->Recepteur_model->insert_detailordi($data1);

        $data=array(
            'data'=>"Sysco",
            'fournisseur'=>$fournisseur
        );
        
        $this->load->view('header',$data);  
        $this->load->view('index'); 
        

    }




   
   

    

    

   }
