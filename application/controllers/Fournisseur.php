<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fournisseur extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Fournisseur_model');
        $this->load->model('Service_model');
        $this->load->helper('ChiffreLettre_helper');
    }

    function connexionFournisseur(){
        $sessionAdmin=$this->session->userdata('idFournisseur');
        if (isset($sessionAdmin)) {
            $fournisseur=$this->Fournisseur_model->allFournisseur();
            $fournisseurNom=$this->Fournisseur_model->fournisseurSelect($sessionAdmin);
            $selectProduit=$this->Fournisseur_model->selectProduit($sessionAdmin);
            $data=array(
                'data'=>"Dexonnexion",
                'fournisseur'=>$fournisseur,
                'nom'=>$fournisseurNom,
                'produit'=>$selectProduit
        );
            $fournisseurNom['data']=$this->Fournisseur_model->fournisseurSelect($sessionAdmin);
            // $this->load->view('header',$data);
            $this->load->view('fournisseur/headFournisseur',$data);  
            $this->load->view('fournisseur/fournisseur',$fournisseurNom);
        }
      else{ 
            $email=$this->input->post('email');
            $motdepasse=$this->input->post('password');
            $valiny=$this->Fournisseur_model->getFournisseur($email,$motdepasse); 
            if ($valiny==false){
                echo "diso leizy";
            }else{
                $this->session->set_userdata('idFournisseur',$valiny); 
                $idFournisseur['idFournisseur']= $this->session->userdata('id');
                $fournisseurNom=$this->Fournisseur_model->fournisseurSelect($valiny);
                
                $fournisseur=$this->Fournisseur_model->allFournisseur();
                $selectProduit=$this->Fournisseur_model->selectProduit($valiny);
                $data=array(
                        'data'=>"Dexonnexion",
                        'fournisseur'=>$fournisseur,
                        'nom'=>$fournisseurNom,
                        'produit'=>$selectProduit
                );
                // $this->load->view('header',$data);
                $this->load->view('fournisseur/headFournisseur',$data);  
                $this->load->view('fournisseur/fournisseur',$data);
            }
      }
    }

    function updateView(){
        $idProduct=$_GET['id'];
        $idFournisseur= $this->session->userdata('idFournisseur');
        echo "IDFOURNI : " , $idFournisseur;
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $fournisseurNom=$this->Fournisseur_model->fournisseurSelect($idFournisseur);
        $selectProduit=$this->Fournisseur_model->selectProduitId($idProduct);
        $data=array(
                'data'=>"Dexonnexion",
                'fournisseur'=>$fournisseur,
                'nom'=>$fournisseurNom,
                'produit'=>$selectProduit
        );
        $this->load->view('fournisseur/headFournisseur',$data);  
        $this->load->view('fournisseur/modifier',$data);
    }

    function checkUpdate(){
        
    }
    function article(){
        $sessionAdmin=$this->session->userdata('idFournisseur');
        $fournisseurNom=$this->Fournisseur_model->fournisseurSelect($sessionAdmin);
        
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $selectProduit=$this->Fournisseur_model->selectProduit($sessionAdmin);
        $data=array(
                'data'=>"Dexonnexion",
                'fournisseur'=>$fournisseur,
                'nom'=>$fournisseurNom,
                'produit'=>$selectProduit
        );
        // $this->load->view('header',$data);
        $this->load->view('fournisseur/headFournisseur',$data);  
        $this->load->view('fournisseur/fournisseur',$data);   
    }

    function commande(){
        $sessionAdmin=$this->session->userdata('idFournisseur');
        $fournisseurNom=$this->Fournisseur_model->fournisseurSelect($sessionAdmin);
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $commande=$this->Fournisseur_model->getvuedata($sessionAdmin);
        
        $distinctDate=$this->Fournisseur_model->getDistinctDateCommande();
        $data=array(
            'data'=>"Dexonnexion",
            'fournisseur'=>$fournisseur,
            'nom'=>$fournisseurNom,
            'commandes'=>$commande,
            'distinctDate'=>$distinctDate
        );
        // $this->load->view('header',$data);
        $this->load->view('fournisseur/headFournisseur',$data);   
        $this->load->view('fournisseur/commande',$data);   
    }
    public function fournir(){
        $sessionAdmin=$this->session->userdata('idFournisseur');
        $dateCommande= $_GET['dateCommande'];
        $fournisseurNom=$this->Fournisseur_model->fournisseurSelect($sessionAdmin);
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $selectProduit=$this->Fournisseur_model->selectProduit($sessionAdmin);
        $selectLivreur=$this->Fournisseur_model->livreurFournisseur($sessionAdmin);
        $societe=$this->Service_model->selectSocieteId(1);
        $commande= $this->Fournisseur_model->commandeSelect($sessionAdmin,$dateCommande);
        $lettre=new ChiffreLettre_helper();
        $data=array(
            'fournisseur'=>$fournisseur,
            'nom'=>$fournisseurNom,
            'session'=>$sessionAdmin,
            'produit'=>$selectProduit,
            'livreur'=>$selectLivreur, 
            'societe'=>$societe,
            'commandes'=>$commande,
            'lettre'=>$lettre
        );
        // $this->load->view('header',$data);
        $this->load->view('fournisseur/headFournisseur',$data);  
        $this->load->view('fournisseur/fournir',$data);   
    }

    function ajoutArticle(){
        $sessionAdmin=$this->session->userdata('idFournisseur');
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $fournisseurNom=$this->Fournisseur_model->fournisseurSelect($sessionAdmin);
        $selectProduit=$this->Fournisseur_model->selectProduit($sessionAdmin);
        $data=array(
            'data'=>"Dexonnexion",
            'fournisseur'=>$fournisseur,
            'nom'=>$fournisseurNom,
            'produit'=>$selectProduit
        );
            $fournisseurNom['data']=$this->Fournisseur_model->fournisseurSelect($sessionAdmin);
            // $this->load->view('header',$data);
            $this->load->view('fournisseur/headFournisseur',$data);  
            $this->load->view('fournisseur/ajoutArticle',$fournisseurNom);
    }
    
    public function passerfacture(){ 
        $sessionAdmin=$this->session->userdata('idFournisseur');
        if (isset($sessionAdmin)){
            $sessionAdmin =$this->input->post('sessionGet');
        }
        $dateLivraison=$this->input->post('dateLivraison');
        $heureLivraison=$this->input->post('heureLivraison');
        $fraisLivraison=$this->input->post('fraisLivraison');
        $idLivreur=$this->input->post('livreur');
        $OneLivreur=$this->Fournisseur_model->OneLivreur($idLivreur);
        $fournisseurNom=$this->Fournisseur_model->fournisseurSelect($sessionAdmin);
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $lettre=new ChiffreLettre_helper();
        $totalLettre=$lettre->Conversion(28800);
        $Societe=$this->Service_model->selectSocieteId(1);
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $dataInsertFournir=array();
            $articles = array();
            $quantites = array();
            foreach ($_POST as $name => $value) {
                if (strpos($name, 'Article') === 0){
                    $articles[] = $value; 
                }
                
                if(strpos($name, 'Quantite') === 0){
                    $quantites[] = $value;
                }
            }
            for ($i=0; $i < count($articles); $i++) { 
                $dataInsertFournir[$i]= array(     
                    'article'=>$articles[$i],  
                    'quantite'=>$quantites[$i]
                );
            }    
        }
       
        for($i=0;$i<count($articles);$i++){
            $detailArticle[]= $this->Fournisseur_model->selectProduitIdArray($articles[$i]);
        }
        var_dump($detailArticle);
        $getMaxNum=$this->Fournisseur_model->selectMaxNumFacture();
        $data=array(
            'fournisseur'=>$fournisseur,
            'nom'=>$fournisseurNom,
            'totalLetter'=>$totalLettre,
            'dataArticle' => $dataInsertFournir,
            'dateLivraison'=>$dateLivraison,
            'heureLivraison'=>$heureLivraison,
            'livreur'=>$OneLivreur,
            'detailArticle'=>$detailArticle,
            'fraisLivraison'=>$fraisLivraison,
            'lettre'=>$lettre,
            'societe'=>$Societe,
            'numFacture'=>$getMaxNum+1
        );
        
      

        for($i=0;$i<count($articles);$i++){
            $dataInsertFacture=array(
                'idSociete'=>1,
                'idfournisseur'=>$sessionAdmin,
                'dateFacture'=>'2023-12-11',
                'heure'=>'08:00:00',
                'num'=>$getMaxNum+1,
                'idArticle'=>$articles[$i],
                'quantite'=>$quantites[$i],
                'fraisLivraison'=>$fraisLivraison
            );
            $this->Fournisseur_model->insert_facture($dataInsertFacture);

        }
        $this->load->view('fournisseur/headFournisseur',$data);  
        $this->load->view('fournisseur/facture_livraison',$data);         
    }
  
    public function ajouterproduit() {
        $idFournisseur= $this->session->userdata('idFournisseur');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = array(
                'nom' => $this->input->post('nom'),
                'prix' => $this->input->post('prix'),
                'tva' => $this->input->post('tva'),
                'idfournisseur' =>$idFournisseur,
                'quantite' => $this->input->post('quantite')
            );
            $this->load->model('Fournisseur_model');
            $produit_id = $this->Fournisseur_model->ajouter_produit($data);
            if($produit_id){
                redirect("index.php/Fournisseur/article");
            } else{
                redirect("index.php/Fournisseur/ajoutArticle");
            }
        } else{
            $this->load->view('fournisseur/fournisseur');
        }
    }

    function updateArticle(){
        $idFournisseur= $this->session->userdata('idFournisseur');
        echo "IDFOURNI : " , $idFournisseur;
        $idProduct=$this->input->post('idProduit');
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $fournisseurNom=$this->Fournisseur_model->fournisseurSelect($idFournisseur);
        $selectProduit=$this->Fournisseur_model->selectProduitId($idProduct);
        $nom = $this->input->post('article');
        $prix = $this->input->post('prix');
        $quantite = $this->input->post('quantite');
        $tva=$this->input->post('tva');

        $this->load->model('Fournisseur_model');
        $this->Fournisseur_model->updateArticle($idProduct,$nom,$tva,$prix, $quantite);
        $data=array(
                'data'=>"Dexonnexion",
                'fournisseur'=>$fournisseur,
                'nom'=>$fournisseurNom,
                'produit'=>$selectProduit
        );
        redirect("index.php/Fournisseur/article");
        
    }

    function livraison(){
        $sessionAdmin=$this->session->userdata('idFournisseur');
        $idLivreur=$this->input->post('idLivreur');
        $numFacture=$this->input->post('numFacture');
        $dateLivraison=$this->input->post('dateLivraison');
        $heureLivraison=$this->input->post('heureLivraison');
        $fraisLivraison=$this->input->post('fraisLivraison');
        $nomLivreur=$this->input->post('nomLivraison');
        $fournisseurNom=$this->Fournisseur_model->fournisseurSelect($sessionAdmin);
        $fournisseur=$this->Fournisseur_model->allFournisseur();
        $data=array(
            'data'=>"Dexonnexion",
            'fournisseur'=>$fournisseur,
            'nom'=>$fournisseurNom,
            'numFacture'=>$numFacture,
            'date'=>$dateLivraison,
            'heure'=>$heureLivraison,
            'livraison'=>$nomLivreur
            
        );
        $dataInsertLivraison=array(
            'idLivreur'=>$idLivreur,
            'numfacture'=>$numFacture,
            'dateLivraison'=>$dateLivraison,
            'heure'=>$heureLivraison,
            'fraisLivraison'=>$fraisLivraison,
            'statut'=>0
        );
        $this->Fournisseur_model->insert_livraison($dataInsertLivraison);
        $this->load->view('fournisseur/headFournisseur',$data);   
        $this->load->view('fournisseur/bonLivraison',$data);
    }
    
   }
