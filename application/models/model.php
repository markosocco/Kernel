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
      $condition = "PROJECTTITLE =" . "'" . $data['PROJECTTITLE'] ."' AND PROJECTDESCRIPTION = '" . $data['PROJECTDESCRIPTION'] . "' AND PROJECTSTARTDATE = '" . $data['PROJECTSTARTDATE'] ."' AND PROJECTENDDATE = '". $data['PROJECTENDDATE'] ."'";
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
      $this->db->select('*');
      $this->db->from('tasks');
      $this->db->order_by('TASKID', 'DESC');
      $this->db->limit(1);
      $query = $this->db->get();

      return $query->row_array();
    }

    else
    {
      return false;
    }
  }

  // MARK TASK AS COMPLETE
  public function updateTaskDone($id, $data)
  {
    $this->db->where('TASKID', $id);

    $result = $this->db->update('tasks', $data);

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
    $this->db->select('*, DATEDIFF(PROJECTENDDATE, PROJECTSTARTDATE) +1 as "duration",
    DATEDIFF(PROJECTENDDATE, CURDATE())+1 as "remaining",
    DATEDIFF(PROJECTSTARTDATE, CURDATE()) as "launching"');
    $this->db->from('projects');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row_array();
  }

  // GETS ALL TASKS OF A PROJECT AND DEPARTMENT
  // public function getAllProjectTasks($data)
  // {
  //   // $condition = "projects_PROJECTID =" . $data;
  //   // $this->db->select('*');
  //   // $this->db->from('tasks');
  //   // $this->db->where($condition);
  //   // $query = $this->db->get();
  //   //
  //   // return $query->result_array();
  //
  //   $sql = "SELECT t.*, d.DEPARTMENTNAME as dName FROM tasks as t JOIN users as u on t.users_USERID = u.USERID JOIN departments as d on u.departments_DEPARTMENTID = d.DEPARTMENTID WHERE t.projects_PROJECTID = " . $data;
  //
	// 	$data = $this->db->query($sql);
  //   return $data->result_array();
  // }

  public function getAllUsers()
  {
    $this->db->select('*, ' . $_SESSION['usertype_USERTYPEID'] . ' as "userType"');
    $this->db->from('users');
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllUsersByDepartment($filter)
  {
    $condition = $filter;
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL ONGOING PROJECTS BASED ON PROJECTSTARTDATE AND PROJECTENDDATE
  public function getAllOngoingProjects()
  {
    $condition = "PROJECTSTARTDATE < CURDATE() && PROJECTENDDATE > CURDATE() && PROJECTSTATUS = 'Ongoing'";
    $this->db->select('*, DATEDIFF(projects.PROJECTENDDATE, CURDATE()) as "datediff"');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->order_by('PROJECTSTARTDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL PLANNED PROJECTS BASED ON PROJECTSTARTDATE
  public function getAllPlannedProjects()
  {
    $condition = "PROJECTSTARTDATE > CURDATE() && PROJECTSTATUS = 'Planning'";
    $this->db->select('*, DATEDIFF(projects.PROJECTSTARTDATE, CURDATE()) as "datediff"');
    $this->db->from('PROJECTS');
    $this->db->where($condition);
    $this->db->order_by('PROJECTSTARTDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

  // GET ALL ONGOING PROJECTS BASED ON PROJECTSTARTDATE AND PROJECTENDDATE OF LOGGED USER
    public function getAllOngoingProjectsByUser($userID)
    {
      $condition = "raci.users_USERID = '$userID' && projects.PROJECTSTARTDATE < CURDATE() && projects.PROJECTENDDATE > CURDATE() && projects.PROJECTSTATUS != 'Complete'";
      $this->db->select('projects.*, DATEDIFF(projects.PROJECTENDDATE, CURDATE()) as "datediff"');
      $this->db->from('projects');
      $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
      $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
      $this->db->where($condition);
      $this->db->group_by('projects.PROJECTID');
      $this->db->order_by('projects.PROJECTENDDATE');

      $query = $this->db->get();

      return $query->result_array();
    }

    // GET ALL ONGOING PROJECTS BASED ON PROJECTSTARTDATE AND PROJECTENDDATE OF LOGGED USER
      public function getAllPlannedProjectsByUser($userID)
      {
        $condition = "raci.users_USERID = '$userID' && projects.PROJECTSTARTDATE > CURDATE() && projects.PROJECTSTATUS != 'Complete'";
        $this->db->select('projects.*, DATEDIFF(projects.PROJECTSTARTDATE, CURDATE()) as "datediff"');
        $this->db->from('projects');
        $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
        $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
        $this->db->where($condition);
        $this->db->group_by('projects.PROJECTID');
        $this->db->order_by('projects.PROJECTSTARTDATE');

        $query = $this->db->get();

        return $query->result_array();
      }

  // GET ALL PROJECT ARCHIVES
    public function getAllProjectArchives()
    {
      $condition = "PROJECTSTATUS = 'Complete'";
      $this->db->select('*');
      $this->db->from('projects');
      $this->db->where($condition);
      $query = $this->db->get();

      return $query->result_array();
    }

    // GET ALL TEMPLATES
      public function getAllTemplates()
      {
        $this->db->select('*');
        $this->db->from('templates');
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

    if (isset($data['PROJECTTITLE']))
    {
      $sDate = $this->convertDateFormat1($data['PROJECTSTARTDATE']);
      $eDate = $this->convertDateFormat1($data['PROJECTENDDATE']);
    }

    else
    {
      $sDate = $this->convertDateFormat1($data['sDate']);;
      $eDate = $this->convertDateFormat1($data['eDate']);;
    }

    $sql = "SELECT DATEDIFF('" . $eDate . "', '" . $sDate . "') as datediff";

		$data = $this->db->query($sql);

    return $data->row('datediff');
  }

  public function getAllTasksByUser($id)
  {
    // $condition = "tasks.users_USERID = " . $id . " && projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete'";
    // $this->db->select('*, CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) as "taskDuration"');
    // $this->db->from('projects');
    // $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
    // $this->db->where($condition);
    //
    // return $this->db->get()->result_array();


    $condition = "raci.users_USERID = '" . $id . "' && projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete'";
    $this->db->select('*, CURDATE() as "currentDate", (DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1) as "taskDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();


    // $condition = "raci.users_USERID = " . $id;
    // $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "taskDuration"');
    // $this->db->from('tasks');
    // $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    // $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    // $this->db->join('users', 'raci.users_USERID = users.USERID');
    // $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    // $this->db->where($condition);
    // return $this->db->get()->result_array();

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

  public function arrangeTasks($data, $id)
  {
    $condition = "TASKID = " . $id;
    $this->db->where($condition);
    $this->db->update('tasks', $data);
  }

  public function getTaskByID($id)
  {
    $condition = "TASKID = " . $id;
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  // GET DATA FOR THE GANTT CHART
<<<<<<< HEAD
=======
  // TODO: edit condition
>>>>>>> 03e403603e1b4e9ee586662c6ec883c59555c9aa
  public function getAllProjectTasks($id)
  {
    $condition = "projects.PROJECTID = " . $id;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "taskDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllProjectTasksGroupByTaskID($id)
  {
    $condition = "projects.PROJECTID = " . $id;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "taskDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('tasks.TASKID');

    return $this->db->get()->result_array();
  }

  public function uploadDocument($data)
  {
    $this->db->insert('documents', $data);
    return true;
  }

  public function insertParentTask($data, $id)
  {
    // $parent = $data['tasks_TASKPARENT'];

    $this->db->where('TASKID', $id);
    $this->db->update('tasks', $data);
  }

  public function addToRaci($data)
  {
    $this->db->insert('raci', $data);
    return true;
  }

  public function getProjectLogs($id)
  {
    $condition = "projects_PROJECTID = " . $id;
    $this->db->select('*');
    $this->db->from('logs');
    $this->db->where($condition);
    $this->db->order_by('TIMESTAMP','DESC');

    return $this->db->get()->result_array();
  }

  public function updateTaskStatus($currentDate)
  {
    $condition = "TASKSTARTDATE = CURDATE() AND TASKSTATUS = 'Planned';";
    $this->db->set('TASKSTATUS', 'Ongoing');
    $this->db->set('TASKACTUALSTARTDATE', $currentDate);
    $this->db->where($condition);
    $this->db->update('tasks');
  }

  public function getDelayedTasksPerUser()
  {
    $condition = "tasks.TASKENDDATE <= CURDATE() AND TASKSTATUS = 'Ongoing' AND raci.users_USERID = " . $_SESSION['USERID'];
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);
    $this->db->order_by('tasks.TASKENDDATE','ASC');

    return $this->db->get()->result_array();
  }

  public function getTasks3DaysBeforeDeadline()
  {
    $condition = "TASKSTATUS = 'Ongoing' AND DATEDIFF(TASKENDDATE, CURDATE()) <= 3
    AND DATEDIFF(TASKENDDATE, CURDATE()) >= 0 AND raci.users_USERID = " . $_SESSION['USERID'];
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);
    $this->db->order_by('tasks.TASKENDDATE','ASC');

    return $this->db->get()->result_array();
  }
}
?>
