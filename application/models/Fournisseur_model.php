<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fournisseur_model extends CI_Model {
    public function getFournisseurByPassword($pass) {
        $this->db->where('id', $idFournisseur);
        $this->db->where('pass', $pass);
        $query = $this->db->get('fournisseur');
        return $query->row_array();
    }
    public function insert_facture($data) {
        $this->db->insert('facture', $data);
        return $this->db->insert_id();
    }
    public function maintenance_materielByCategorie($idcategorie){
        $this->db->select('*');
        $this->db->from('maintenance_materiel');
        $this->db->where('idcategorie', $idcategorie);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert_livraison($data) {
        $this->db->insert('livraison', $data);
        return $this->db->insert_id();
    }

    public function allFournisseur(){
        $this->db->select('*');
        $this->db->from('fournisseur');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function allCompte() {
        $this->db->select('*');
        $this->db->from('compte');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function genreById($id){
        $this->db->select('*');
        $this->db->from('genre');
        $this->db->where('idcompte',$id);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function getFournisseur($email, $password) {
        $this->db->select('id');
        $this->db->from('fournisseur');
        $this->db->where('email' , $email);
        $this->db->where('pass', $password);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result->id;
        } else {
            return false;
        }
    }
    function fournisseurSelect($id){
        $this->db->select('*');
        $this->db->from('fournisseur');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }
    

    
    public function ajouter_produit($data) {
        $this->db->insert('produit', $data);
        return $this->db->insert_id();
    }

    
    function nomFournisseur($nom){
        $this->db->select('*');
        $this->db->from('fournisseur');
        $this->db->where('nom',$nom);
        $query = $this->db->get();
        return $query->result_array();
    }
    function livreurFournisseur($id){
        $this->db->select('*');
        $this->db->from('livreur');
        $this->db->where('idfournisseur',$id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function OneLivreur($id){
        $this->db->select('*');
        $this->db->from('livreur');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }


    function selectProduit($id){
        $this->db->select('*');
        $this->db->from('produitDetail');
        $this->db->where('idfournisseur',$id); 
        $query = $this->db->get();
        return $query->result_array();
    }

    function selectProduitId($id){
        $this->db->select('*');
        $this->db->from('produit');
        $this->db->where('id',$id); 
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getvuedata($idFournisseur){
        $this->db->select('*');
        $this->db->from('commandes');
        $this->db->where('idfournisseur',$idFournisseur);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function commandeSelect($idFournisseur,$date){
        $this->db->select('*');
        $this->db->from('commandes');
        $this->db->where('idfournisseur',$idFournisseur);
        $this->db->where('dateCommande',$date);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getDistinctDateCommande() {
        $query = $this->db->query(
            'SELECT DISTINCT dateCommande, nom FROM `commandes`'
        );
        return $query->result_array();
    }
    
    function selectProduitIdArray($id){
        $this->db->select('*');
        $this->db->from('produitdetail');
        $this->db->where_in('id',$id); 
        $query = $this->db->get();
        return $query->result_array();
    }

    function allProduit(){
        $this->db->select('');
        $this->db->from('produit');
        $query = $this->db->get();
        return $query->result_array();
    }

    function allNomProduit(){
        $this->db->select('DISTINCT(nom)');
        $this->db->from('produit');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    function updateProduit($idBesoin,$etat){
        $mise_a_jour = array(
            'etat' => $etat
        );
        $this->db->where('id', $idBesoin);
        $this->db->update('besoinservice', $mise_a_jour);
        if ($this->db->affected_rows() > 0) {
            return true; 
        } else {
            return false; 
        }
    }

    function selectProduitProforma($article) {
        $this->db->select('*');
        $this->db->from('produit');
        $this->db->where('article',$article); 
        $query = $this->db->get();
        return $query->result_array();
    }

    public function updateArticle($idProduit, $nom,$tva, $prix, $quantite) {
        $data = array(
            'nom' => $nom,
            'prix' => $prix,
            'tva'=>$tva,
            'quantite' => $quantite,
        );
        $this->db->where('id', $idProduit);
        $this->db->update('produit', $data);
    
        return $this->db->affected_rows(); 
    }

    function selectMaxNumFacture(){
        $this->db->select('max(num) as maxNum');
        $this->db->from('facture');
        $query = $this->db->get();
        return $query->row()->maxNum;
    }

}
