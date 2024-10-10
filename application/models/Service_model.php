<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_model extends CI_Model {

    public function insert_Besoin($data) {
        return $this->db->insert('besoin', $data);
    }
    public function getChef($email,$pass) {
        $this->db->select('id');
        $this->db->from('chef');
        $this->db->where('email' , $email);
        $this->db->where('motdepasse', $pass);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result->id;
        } else {
            return false;
        }
    }

    function selectDepartement(){
        $this->db->select('*');
        $this->db->from('departement');
        $query = $this->db->get();
        return $query->result_array();
    }

    function selectDepartementById($id){
        $this->db->select('*');
        $this->db->from('departement');
        $this->db->where('id' , $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function allBesoin(){
        $this->db->select('*');
        $this->db->from('besoinDetail');
        $query = $this->db->get();
        return $query->result_array();
    }

    function besoinDetail($num){
        $this->db->select('*');
        $this->db->from('besoinDetail');
        $this->db->where('num' , $num);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function dernierNum(){
        $query = $this->db->query(
            'select max(num) from besoin'
        );
        return $query->result_array();
    }
    
    public function get_besoins_ordered_by_num($etat) {
        $this->db->select('*');
        $this->db->from('besoindetail');
        $this->db->where('etat', $etat); 
        $this->db->order_by('num', 'ASC'); 
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function ajouter_demande($data) {
        $this->db->insert('demande', $data);
    }

    public function insert_Envoyer_Finance($data) {
        $this->db->insert('finance', $data);
    }

    public function selectFinanceDemande() {
        $this->db->select('distinct(id_proforma),dateCommande');
        $this->db->from('finance');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function modifier_etat_besoin($idBesoin, $etat) {
        $this->db->where('id', $idBesoin);
        $this->db->set('etat', $etat);
        $this->db->update('besoin');
    }

    function selectProforma($fournisseur, $num){
        $this->db->select('id,fournisseur, MIN(article) AS article,marque, num, MIN(quantite) AS quantite, MIN(prix) AS prix, MIN(tva) AS tva, MIN(prixTva) AS prixTva, MIN(total) AS total');
        $this->db->from('proforma');
        $this->db->where('fournisseur', $fournisseur);
        $this->db->where('num', $num);
        $this->db->group_by('fournisseur, num, article,marque, quantite, prix, tva, prixTva, total');
        $query = $this->db->get();
        return $query->result_array();
    }

    function selectMinPrix($article){
        // $this->db->select('*');
        // $this->db->from('proforma');
        // $this->db->where('total=(select min(total) from proforma group by article)');
        // $this->db->group_by('daty');
        // $query = $this->db->get();
        // return $query->result_array();

        $this->db->select('*');
        $this->db->from('fournisseur,');
        $this->db->where('article',$article);
        $query = $this->db->get();
        return $query->result_array();
    }

    function selectMinByArticle($article,$etat) {
        $this->db->select('*');
        $this->db->from('proforma p1');
        $this->db->where('(daty, total, article) IN (
            SELECT daty, MIN(total) AS min_total, article
            FROM proforma p2
            WHERE p1.daty = p2.daty
            GROUP BY daty, article
        )');
        $this->db->where('article', $article);
        $this->db->where('etat',$etat);
        $this->db->limit(25);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    function selectSocieteId($id){
        $this->db->select('*');
        $this->db->from('societe');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function selectProformaById($id) {
        $this->db->select('*');
        $this->db->from('proforma');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }

        public function calculSomme($ids) {
            $this->db->select_sum('total');
            $this->db->from('proforma');
            $this->db->where_in('id', $ids);
            $query = $this->db->get();
            return $query->row()->total;
        }

        public function calculSommeTva($ids) {
            $this->db->select_sum('prixTva');
            $this->db->from('proforma');
            $this->db->where_in('id', $ids);
            $query = $this->db->get();
            return $query->row()->prixTva;
        }
        public function calculHT($ids) {
            $this->db->select('(SUM(quantite * prix)) AS prixQuantite', FALSE);
            $this->db->from('proforma');
            $this->db->where_in('id', $ids);
            $query = $this->db->get();
            return $query->row()->prixQuantite;
        }

        
        
        

}
