<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recepteur_model extends CI_Model {


    public function getRecepteur($email, $password) {
        $this->db->select('id');
        $this->db->from('recepteur');
        $this->db->where('email' , $email);
        $this->db->where('mdp', $password);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result->id;
        } else {
            return false;
        }
    }

    public function getUndelivered() {
        $query = $this->db->query(
            'select * from livraisondetail where id not in(select idlivraison from reception)'
        );
        return $query->result_array();
    }

    public function AllMaterielSociete() {
        $this->db->select('*');
        $this->db->from('receptiondetail');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function UpdateMarchandise($id, $statut) {
        $query = $this->db->query(
            'UPDATE livraisondetail SET statut = ? WHERE id = ?',
            array($statut, $id)
        );
        return $query;
    }
    public function insert_detailvoiture($data) {
        return $this->db->insert('detail_voiture',$data);
    }

    public function insert_detailmoto($data) {
        return $this->db->insert('detail_moto',$data);
    }

    public function insert_detailordi($data) {
        return $this->db->insert('detail_ordi',$data);
    }
    
    

    function getNonLivreById($id){
        $this->db->select('*');
        $this->db->from('livraisondetail');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSocieteById($id){
        $this->db->select('*');
        $this->db->from('societe');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function getAmortissement(){
        $this->db->select('*');
        $this->db->from('amortissement');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert_reception($data) {
        $this->db->insert('reception', $data);
        return $this->db->insert_id();
    }
    
}
