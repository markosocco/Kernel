<?php
class model extends CI_Model
{
  public function __construct()
  {
    $this -> load -> database();
  }

// CHECK IF EMAIL AND PASSWORD EXIST AND MATCH IN DB
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

// GET DATA OF USER, GIVEN THE EMAIL
  public function getUserData($data)
  {
    $condition = "EMAIL =" . "'" . $data['email'] ."'";
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row_array();
  }

// GET PROJECTID

  public function getProjectID($data)
  {
    $condition = "PROJECTTITLE =" . "'" . $data['PROJECTTITLE'] ."' AND PROJECTSTARTDATE = '" . $data['PROJECTSTARTDATE'] ."' AND '". $data['PROJECTENDDATE'] ."'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row("PROJECTID");
  }

// SAVE NEW PROJECT TO DB
public function addProject($data)
  {
    $result = $this->db->insert('projects', $data);

    if ($result)
    {
      $condition = "PROJECTTITLE =" . "'" . $data['PROJECTTITLE'] ."' AND PROJECTSTARTDATE = '" . $data['PROJECTSTARTDATE'] ."' AND '". $data['PROJECTENDDATE'] ."'";
      $this->db->select('*');
      $this->db->from('projects');
      $this->db->where($condition);
      $query = $this->db->get();

      return $query->row_array();
    }

    else
    {
      return false;
    }
  }

// COMPUTE FOR NUMBER OF DAYS, GIVEN A DATE PERIOD
  public function getDateDiff($data)
  {
    $startDate = $data['PROJECTSTARTDATE'];
    $endDate = $data['PROJECTENDDATE'];

    $start = str_replace("/", "-", $startDate);
    $end = str_replace("/", "-", $endDate);

    $startMonth = substr($start, 0, 2);
    $startDay = substr($start, 3, 2);
    $startYear = substr($start, 6, 4);
    $sDate = $startYear . "-" . $startMonth . "-" . $startDay;

    $endMonth = substr($end, 0, 2);
    $endDay = substr($end, 3, 2);
    $endYear = substr($end, 6, 4);
    $eDate = $endYear . "-" . $endMonth . "-" . $endDay;

    $sql = "SELECT DATEDIFF('" . $eDate . "', '" . $sDate . "') as datediff";

		$data = $this->db->query($sql);

    return $data->row('datediff');
  }

// GET DATA FOR THE GANTT CHART
// TODO: edit condition
  public function getGanttData()
  {
    $condition = "projects.PROJECTID = 1";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('users', 'tasks.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

// GET PRE-REQUISITE ID
// TODO: edit condition
  public function getPreReqID()
  {
    $condition = "projects.PROJECTID = 1";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('dependencies', 'tasks.TASKID = dependencies.PRETASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }



}
?>
