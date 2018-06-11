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

// GET PROJECTID GIVEN TITLE AND DATES
  public function getProjectID($data)
  {
    $condition = "PROJECTTITLE =" . "'" . $data['PROJECTTITLE'] ."' AND PROJECTSTARTDATE = '" . $data['PROJECTSTARTDATE'] ."' AND '". $data['PROJECTENDDATE'] ."'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row("PROJECTID");
  }

// SAVE NEW PROJECT TO DB; RETURNS PROJECT
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

  // SAVE INDIVIDUAL TASK TO PROJECT
  public function addTasksToProject($data)
  {
    $result = $this->db->insert('tasks', $data);

    if ($result)
    {
      return true;
    }

    else
    {
      return false;
    }
  }

  // GETS PROJECT BY ID; RETURNS PROJECT
  public function getProjectByID($data)
  {
    $condition = "PROJECTID =" . $data;
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row_array();
  }

  // GETS ALL TASKS OF A PROJECT AND DEPARTMENT
  public function getAllProjectTasks($data)
  {
    // $condition = "projects_PROJECTID =" . $data;
    // $this->db->select('*');
    // $this->db->from('tasks');
    // $this->db->where($condition);
    // $query = $this->db->get();
    //
    // return $query->result_array();

    $sql = "SELECT t.*, d.DEPARTMENTNAME as dName FROM tasks as t JOIN users as u on t.users_USERID = u.USERID JOIN departments as d on u.departments_DEPARTMENTID = d.DEPARTMENTID WHERE t.projects_PROJECTID = " . $data;

		$data = $this->db->query($sql);
    return $data->result_array();
  }

  public function getAllUsers()
  {
    $this->db->select('*');
    $this->db->from('users');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET CURRENT DATE IN MM/DD/YYYY FORMAT
  public function getCurrentDate()
  {
    $this->db->select('CURDATE()');
    $queryDate = $this->db->get();
    $CURDATE = $this->convertDateFormat2($queryDate->row("CURDATE()"));

    return $CURDATE;
  }

  public function getAllOngoingProjects()
  {
    $CURDATE = $this->getCurrentDate();

    $condition = "PROJECTSTARTDATE < '$CURDATE' && PROJECTENDDATE > '$CURDATE'";
    // $condition = "PROJECTSTARTDATE < '06/11/2018' && PROJECTENDDATE > '06/11/2018'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllPlannedProjects()
  {
    $CURDATE = $this->getCurrentDate();

    $condition = "PROJECTSTARTDATE > '$CURDATE'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->result_array();
  }

// CONVERTS MM/DD/YYYY TO YYYY-MM-DD
  public function convertDateFormat1($oldDate)
  {
    $date = str_replace("/", "-", $oldDate);
    $month = substr($date, 0, 2);
    $day = substr($date, 3, 2);
    $year = substr($date, 6, 4);
    $newDate = $year . "-" . $month . "-" . $day;

    return $newDate;
  }

  // CONVERTS YYYY-MM-DD TO MM/DD/YYYY
    public function convertDateFormat2($oldDate)
    {
      $date = str_replace("-", "/", $oldDate);
      $day = substr($date, 8, 2);
      $month = substr($date, 5, 2);
      $year = substr($date, 0, 4);
      $newDate = $month . "/" . $day . "/" . $year;

      return $newDate;
    }

// COMPUTE FOR NUMBER OF DAYS, GIVEN A DATE PERIOD
  public function getDateDiff($data)
  {
    // $startDate = $data['PROJECTSTARTDATE'];
    // $endDate = $data['PROJECTENDDATE'];

    $sDate = $this->convertDateFormat1($data['PROJECTSTARTDATE']);
    $eDate = $this->convertDateFormat1($data['PROJECTENDDATE']);

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
  public function getDependecies()
  {
    $condition = "projects.PROJECTID = 1";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('dependencies', 'tasks.TASKID = dependencies.tasks_POSTTASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

//   public function getAllDepartments($data)
//   {
//     $this->db->select('*');
//     $this->db->from('departments');
//     $query = $this->db->get();
//
//     return $query->result_array();
//   }

// RETURNS ARRAY OF DEPARTMENTS
  public function getAllDepartments()
  {
    $this->db->select('*');
    $this->db->from('departments');
    $query = $this->db->get();

    return $query->result_array();
  }
}
?>
