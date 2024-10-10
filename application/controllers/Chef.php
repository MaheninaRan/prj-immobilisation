<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chef extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Fournisseur_model');
        $this->load->model('Service_model');
		$this->load->helper('file');
        $this->load->helper('mon_helper');
        $this->load->helper('ChiffreLettre_helper');
    }
    public function redirectChef(){
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
    public function approuver(){
        $idBesoin=$_GET['idBesoin'];
        $this->Service_model->modifier_etat_besoin($idBesoin,1);
        $dataDemande=array(
            'idBesoin'=>$idBesoin,
            'daty'=>'2023-12-11'
        );
        $this->Service_model->ajouter_demande($dataDemande);

        redirect('index.php/Chef/redirectChef');
    }

    public function rejeter(){
        $idBesoin=$_GET['idBesoin'];
        $this->Service_model->modifier_etat_besoin($idBesoin,2);
        redirect('index.php/Chef/redirectChef');
    }

    public function proforma(){
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $num=$this->Service_model->get_besoins_ordered_by_num(1);
		$data=array(
			'data'=>"Deconnexion",
			'fournisseur'=>$fournisseur,
            'resultats'=>$num
		);
		$this->load->view('header',$data);
		$this->load->view('departement/proforma',$data);
    }

    public function proformaDetail(){
        $numBesoin=$_GET['num'];
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $num=$this->Service_model->get_besoins_ordered_by_num(1);
        $selectProformaLeaderPrice=$this->Service_model->selectProforma('Leader price',$numBesoin);
        $selectProformaJumbo=$this->Service_model->selectProforma('Jumbo score',$numBesoin);
        $selectProformaShopU=$this->Service_model->selectProforma('Shop U',$numBesoin);
        $fournisseurJumbo=$this->Fournisseur_model->fournisseurSelect(1);
        $fournisseurLeader=$this->Fournisseur_model->fournisseurSelect(2);
        $fournisseurShop=$this->Fournisseur_model->fournisseurSelect(3);

		$data=array(
			'data'=>"Deconnexion",
			'fournisseur'=>$fournisseur,
            'resultats'=>$num,
            'leaderPrice'=>$selectProformaLeaderPrice,
            'jumboScore'=>$selectProformaJumbo,
            'shopU'=>$selectProformaShopU,
            'jumbo'=>$fournisseurJumbo,
            'leader'=>$fournisseurLeader,
            'shop'=>$fournisseurShop
		);
		$this->load->view('header',$data);
		$this->load->view('departement/proformaDetail',$data);
    }

     public function getCommande(){
        $article=$this->input->post('article');       
        for ($i = 0; $i < count($article); $i++){
            $selectMinPrix[$i]= $this->Service_model->selectMinByArticle($article[$i],1);
            $dataFournisseur[$i]=$this->Fournisseur_model->nomFournisseur($selectMinPrix[$i]['fournisseur']);
        }
        $departementSelect=$this->Service_model->selectDepartement($selectMinPrix[0]['idDepartement']);

        $fournisseur=$this->Fournisseur_model->allFournisseur();
		$data=array(
			'data'=>"Deconnexion",
			'fournisseur'=>$fournisseur,
            'datafournisseur'=>$dataFournisseur,
            'resultats'=>$selectMinPrix,
            'departementSelect'=>$departementSelect
		);
		$this->load->view('header',$data);
		$this->load->view('departement/bandeCommande',$data);
    }
    
    function finance(){
        $fournisseur=$this->Fournisseur_model->allFournisseur();
		$data=array(
			'data'=>"Sysco",
			'fournisseur'=>$fournisseur,
		);
		$this->load->view('header',$data);
		$this->load->view('departement/loginDAF',$data);
    }

   
    public function envoyerFinance(){
        $proformaIds = $this->input->post('idProforma');    
        for ($i = 0; $i < count($proformaIds); $i++){ 
            $id_proforma = intval($proformaIds[$i]);  
            $this->Service_model->insert_Envoyer_Finance(['id_proforma' => $id_proforma,'dateCommande'=> "2023-12-11"]);
        }
		 redirect('index.php/Welcome/index');
        

        // $idProforma=$_GET['idProforma'];
        // $this->Service_model->modifier_etat_besoin($idProforma,3);
		// redirect('index.php/Chef/getCommande');
    }
    function loginDAF(){
        $proformaFinance=$this->Service_model->selectFinanceDemande();
        $idsArray = array(); 
        for ($i=0; $i < count($proformaFinance); $i++) { 
            $id_proforma = intval($proformaFinance[$i]['id_proforma']);
            $idsArray[] = $id_proforma;
            $proformaSelecter[$i]=$this->Service_model->selectProformaById($proformaFinance[$i]['id_proforma']);
            $fournisseurSelect[$i]=$this->Fournisseur_model->fournisseurSelect($proformaSelecter[$i][0]['idfournisseur']);
            $selectDepartementById[$i]=$this->Service_model->selectDepartementById($proformaSelecter[$i][0]['idDepartement']);
        }
        $totalPayer=$this->Service_model->calculSomme($idsArray);
        $calculSommeTva=$this->Service_model->calculSommeTva($idsArray);
        $calculHT=$this->Service_model->calculHT($idsArray);
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $compte=189778880;
        $lettre=new ChiffreLettre_helper();
        $totalLettre=$lettre->Conversion($totalPayer);
        
		$data=array(
			'data'=>"Deconnexion",
			'fournisseur'=>$fournisseur,
            'proforma' =>$proformaSelecter,
            'fournisseurSelect'=>$fournisseurSelect,
            'departement'=>$selectDepartementById,
            'total'=>$totalPayer,
            'prixTva'=>$calculSommeTva,
            'prixHT'=>$calculHT,
            'compte'=>number_format($compte, 2, ',', ' '),
            'totalLettre'=>$totalLettre
		);
       
        $this->load->view('header',$data);
		$this->load->view('departement/indexDAF',$data);
    }

    function demandeUtil(){
        $marchandise = $this->input->post('marchandise');    
        $dateDebut = $this->input->post('dateDebut');    
        $heureDebut = $this->input->post('heureDebut');    
        $dateFin = $this->input->post('dateFin');    
        $heureFin = $this->input->post('heureFin');    
        $Lieu=$this->input->post('Lieu');  

        $insertData = array(
            'idpersonnelle' =>$this->session->userdata('idDepartement'),
            'idmarchandise' =>$marchandise,
            'dateDebut' => $dateDebut,
            'heureDebut'=>$dateFin,
            'dateFin' =>$dateFin,
            'heureFin' =>$heureFin,
            'lieu'=>$Lieu
        );
        $this->Responsable_model->insert_demandeUtilisation($insertData);
        
    }

}
