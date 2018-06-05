<?php
class model extends CI_Model
{
  public function __construct()
  {
    $this -> load -> database();
  }

  public function checkDatabase($data)
  {
    $condition = "EMAIL =" . "'" . $data['email'] . "' AND " . "PASSWORD =" . "'" . $data['password'] . "'";
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);
    $this->db->limit(1);
    $query = $this->db->get();

    if ($query->num_rows() == 1)
    {
      return true;
    }

    else
    {
      return false;
    }
  }

  public function getUserData($data)
  {
    $condition = "EMAIL =" . "'" . $data['email'] ."'";
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row_array();
  }

  public function addProject($data)
  {
    $this->db->insert('projects', $data);

    return true;
  }
}
?>
