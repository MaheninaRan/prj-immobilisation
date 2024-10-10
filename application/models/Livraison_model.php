<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Livraison_model extends CI_Model {



    function getCategorieById($id){
        $this->db->select('*');
        $this->db->from('categorie');
        $this->db->where('numerocompte',$id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getCategorie($id){
        $this->db->select('*');
        $this->db->from('categorie');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function allCompte(){
        $this->db->select('*');
        $this->db->from('numerocompte');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getCompteById($id){
        $this->db->select('*');
        $this->db->from('numerocompte');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert_marchandise($data) {
        $this->db->insert('marchandises', $data);
        return $this->db->insert_id();
    }

    function getMaxMarchandise(){
        $this->db->select('max(id) as maxNum');
        $this->db->from('marchandises');
        $query = $this->db->get();
        return $query->row()->maxNum;
    }

    public function insert_livraison($data) {
        $this->db->insert('livraison', $data);
        return $this->db->insert_id();
    }

    function getLivreurFournisseur($id){
        $this->db->select('*');
        $this->db->from('livreur');
        $this->db->where('idfournisseur',$id); 
        $query = $this->db->get();
        return $query->result_array();
    }

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





   

}
