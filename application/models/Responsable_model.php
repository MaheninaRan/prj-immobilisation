<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Responsable_model extends CI_Model {


    public function getResponsable($email, $password) {
        $this->db->select('id');
        $this->db->from('responsable');
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

    function getAllMarchandises(){
        $this->db->select('*');
        $this->db->from('receptiondetail');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function insert_demandeUtilisation($data) {
        return $this->db->insert('demandeUtilisation',$data);
    }
    
    public function rechercheIdPers($motdepasse) {
       $this->db->select('id');
    $this->db->from('personnelle');
    $this->db->where('pass', $motdepasse);
    $query = $this->db->get();

    // Vérifier si la requête a renvoyé au moins une ligne
    if ($query->num_rows() > 0) {
        // Retourner l'ID de la première ligne
        return $query->row()->id;
    } else {
        // Aucun résultat trouvé, vous pouvez choisir de retourner false ou une valeur par défaut
        return false;
    }
    }

    
    
}
