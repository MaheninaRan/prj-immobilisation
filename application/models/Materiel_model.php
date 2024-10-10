<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materiel_model extends CI_Model {


    public function selectMarchandises($statut){
        $this->db->select('*');
        $this->db->from('receptiondetail');
        $this->db->where('statut', $statut);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectMarchandises2($statut){
        $this->db->select('*');
        $this->db->from('receptiondetail');
        $this->db->where('statutreception', $statut);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectpersonnelle(){
        $this->db->select('*');
        $this->db->from('personnelle');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectLieu(){
        $this->db->select('*');
        $this->db->from('lieu');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function insertUtilisation($data) {
        return $this->db->insert('utilisation',$data);
    }
    public function insertSuivie($data) {
        return $this->db->insert('suivie',$data);
    }
    public function selectUtilisation(){
        $query= $this->db->get('utilisations');
        return $query->result();
    }
    public function updateStatusM($id, $statut) {
        $data = array('statutreception' => $statut);
        $this->db->where('id', $id);
        $result = $this->db->update('receptiondetail', $data);
        return $result;
    }
    
    public function selectSuivie($id){
        $this->db->select('*');
        $this->db->from('suivie_v');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function updateUtilisation($id){
        $query=$this->db->query(
           'UPDATE utilisation SET statut =1 WHERE id = ?',
           array($id)
        );
        return $query;
    }

    public function getPersonnelle($id){ 
        $this->db->select('*');
        $this->db->from('personnelle'); 
        $this->db->where('id', $id); 
        $query = $this->db->get(); 
        return $query->result_array(); 
    }

    public function insertMaintenance($data) {
        return $this->db->insert('maintenance',$data);
    }
    public function selectMaintenance($id){
        $this->db->select('*');
        $this->db->from('maintenancedetail');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
}
