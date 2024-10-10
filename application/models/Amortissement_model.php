<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Amortissement_model extends CI_Model {


    public function getCompta($email, $password) {
        $this->db->select('id');
        $this->db->from('compta');
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

    function getMarchandisesByStatut($statut){
        $this->db->select('*');
        $this->db->from('livraisondetail');
        $this->db->where('statut',$statut);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getIdLivraison($id){
        $this->db->select('id');
        $this->db->from('livraisondetail');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->row()->id;
    }

    function getAmortissementType($id){
        $this->db->select('amortissement');
        $this->db->from('reception');
        $this->db->where('idlivraison',$id);
        $query = $this->db->get();
        return $query->row()->amortissement;
    }

    function getMarchandisesById($id){
        $this->db->select('*');
        $this->db->from('livraisondetail');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getUtilisation($id){
        $this->db->select('*');
        $this->db->from('utilisation');
        $this->db->where('idmarchandises',$id);
        $query = $this->db->get();
        return $query->result_array();
    }


    
    
}
