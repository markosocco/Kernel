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
      // $condition = "PROJECTTITLE =" . "'" . $data['PROJECTTITLE'] ."' AND PROJECTDESCRIPTION = '" . $data['PROJECTDESCRIPTION'] . "' AND PROJECTSTARTDATE = '" . $data['PROJECTSTARTDATE'] ."' AND PROJECTENDDATE = '". $data['PROJECTENDDATE'] ."'";
      // $this->db->select('*');
      // $this->db->from('projects');
      // $this->db->where($condition);

      $this->db->select('*');
      $this->db->from('projects');
      $this->db->order_by('PROJECTID', 'DESC');
      $this->db->limit(1);

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

      return $query->row('TASKID');
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
  }

  // MARK PROJECT AS COMPLETE
  public function completeProject($id, $data)
  {
    $this->db->where('PROJECTID', $id);
    $result = $this->db->update('projects', $data);
  }

  // GETS PROJECT BY ID; RETURNS PROJECT
  public function getProjectByID($data)
  {
    $condition = "PROJECTID =" . $data;
    $this->db->select('*, DATEDIFF(PROJECTENDDATE, PROJECTSTARTDATE) +1 as "duration",
    DATEDIFF(PROJECTENDDATE, CURDATE())+1 as "remaining",
    DATEDIFF(PROJECTSTARTDATE, CURDATE())+1 as "launching",
    DATEDIFF(PROJECTACTUALENDDATE, PROJECTSTARTDATE)+1 as "actualDuration",
    DATEDIFF(CURDATE(), PROJECTENDDATE) as "delayed"');
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

  public function getChangeRequestsbyUser($filter)
  {
    $condition = "REQUESTSTATUS = 'Pending'";
    $this->db->select('*');
    $this->db->from('changerequests');
    $this->db->join('tasks', 'changerequests.tasks_REQUESTEDTASK = tasks.TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'users.USERID = changerequests.users_REQUESTEDBY');
    $this->db->where($condition . " && " . $filter);
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllUsers()
  {
    $this->db->select('*, ' . $_SESSION['usertype_USERTYPEID'] . ' as "userType"');
    $this->db->from('users');
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllUsersByUserType($filter)
  {
    $condition = $filter;
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition . "&& users.USERID !='" . $_SESSION['USERID'] . "'");
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllUsersByDepartment($deptID)
  {
    $condition = "users.departments_DEPARTMENTID = '$deptID'";
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getProjectCount($filter)
  {
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.ROLE = '1' && tasks.CATEGORY = '3'";
    $this->db->select('users.*, count(distinct projects.PROJECTID) AS "projectCount"');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition . " && " . $filter);
    $this->db->group_by('users.USERID');

    return $this->db->get()->result_array();
  }

  public function getTaskCount($filter)
  {
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.ROLE = '1' && tasks.CATEGORY = '3'";
    $this->db->select('users.*, count(distinct tasks.TASKID) AS "taskCount"');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition . " && " . $filter);
    $this->db->group_by('users.USERID');

    return $this->db->get()->result_array();
  }

  public function getWorkloadProjects($userID)
  {
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.users_USERID = '$userID' && raci.ROLE = '1' && tasks.CATEGORY = '3'";
    $this->db->select('projects.*');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->group_by('projects.PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getWorkloadTasks($userID, $projectID)
  {
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.users_USERID = '$userID' && projects.PROJECTID = '$projectID' && raci.ROLE = '1' && tasks.CATEGORY = '3'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->order_by('tasks.TASKSTARTDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

// GET ALL ONGOING PROJECTS BASED ON PROJECTSTARTDATE AND PROJECTENDDATE
  public function getAllOngoingProjects()
  {
    $condition = "PROJECTSTARTDATE <= CURDATE() && PROJECTENDDATE => CURDATE() && PROJECTSTATUS = 'Ongoing'";
    $this->db->select('*, DATEDIFF(projects.PROJECTENDDATE, CURDATE()) as "datediff"');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->order_by('PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL PLANNED PROJECTS BASED ON PROJECTSTARTDATE
  public function getAllPlannedProjects()
  {
    $condition = "PROJECTSTARTDATE > CURDATE() && PROJECTSTATUS = 'Planning'";
    $this->db->select('*, DATEDIFF(PROJECTSTARTDATE, CURDATE()) as "datediff"');
    $this->db->from('PROJECTS');
    $this->db->where($condition);
    $this->db->order_by('PROJECTSTARTDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL DELAYED PROJECTS BASED ON PROJECTENDDATE
  public function getAllDelayedProjects()
  {
    $condition = "PROJECTENDDATE < CURDATE() && PROJECTSTATUS = 'Ongoing'";
    $this->db->select('*, ABS(DATEDIFF(PROJECTENDDATE, CURDATE())) AS "datediff"');
    $this->db->from('PROJECTS');
    $this->db->where($condition);
    $this->db->order_by('PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL PARKED PROJECTS BASED ON PROJECTSTATUS
  public function getAllParkedProjects()
  {
    $condition = "PROJECTSTATUS = 'Parked'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->order_by('PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL DRAFTED PROJECTS BASED ON PROJECTSTATUS
  public function getAllDraftedProjects()
  {
    $condition = "PROJECTSTATUS = 'Drafted'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->order_by('PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL PLANNED PROJECTS BASED ON PROJECTSTARTDATE
  public function getAllCompletedProjects()
  {
    $condition = "PROJECTSTATUS = 'Complete'";
    $this->db->select('*, ((7-datediff(PROJECTACTUALENDDATE, curdate()))-1) as "datediff"');
    $this->db->from('PROJECTS');
    $this->db->where($condition);
    $this->db->order_by('PROJECTSTARTDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL ONGOING PROJECTS BASED ON PROJECTSTARTDATE AND PROJECTENDDATE OF LOGGED USER
  public function getAllOngoingProjectsByUser($userID)
  {
    $condition = "(raci.users_USERID = '$userID' || projects.users_USERID = '$userID') && projects.PROJECTSTARTDATE <= CURDATE() && projects.PROJECTENDDATE > CURDATE() && projects.PROJECTSTATUS = 'Ongoing'";
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
    $condition = "(raci.users_USERID = '$userID' || projects.users_USERID = '$userID') && projects.PROJECTSTARTDATE > CURDATE() && projects.PROJECTSTATUS = 'Planning' && projects.users_USERID = " . $_SESSION['USERID'];
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

// GET ALL DELAYED PROJECTS BASED ON PROJECTENDDATE OF LOGGED END USER
  public function getAllDelayedProjectsByUser($userID)
  {
    $condition = "(raci.users_USERID = '$userID' || projects.users_USERID = '$userID') && PROJECTENDDATE < CURDATE() && PROJECTSTATUS = 'Ongoing'";
    $this->db->select('*, ABS(DATEDIFF(PROJECTENDDATE, CURDATE())) AS "datediff"');
    $this->db->from('PROJECTS');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL PARKED PROJECTS BASED ON PROJECTSTATUS
  public function getAllParkedProjectsByUser($userID)
  {
    $condition = "(raci.users_USERID = '$userID' || projects.users_USERID = '$userID') && PROJECTSTATUS = 'Parked'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL DRAFTED PROJECTS BASED ON PROJECTSTATUS
  public function getAllDraftedProjectsByUser($userID)
  {
    $condition = "PROJECTSTATUS = 'Drafted' && users_USERID = " . $userID;
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

// GET ALL PLANNED PROJECTS BASED ON PROJECTSTARTDATE
  public function getAllCompletedProjectsByUser($userID)
  {
    $condition = "PROJECTSTATUS = 'Complete' && users_USERID = " . $userID;
    $this->db->select('*, ((7-datediff(PROJECTACTUALENDDATE, curdate()))-1) as "datediff"');
    $this->db->from('projects');
    $this->db->where($condition);
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');
    $query = $this->db->get();

    return $query->result_array();
  }

  // GET ALL PROJECT ARCHIVES
  public function getAllProjectArchives()
  {
    $condition = "PROJECTSTATUS = 'Archived'";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('users', 'projects.users_USERID = users.USERID');
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

  public function getAllTasksByUser($id)
  {
    $condition = "raci.users_USERID = '" . $id . "' && projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '3' && raci.ROLE = '1'";
    $this->db->select('*, CURDATE() as "currentDate", (DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1) as "taskDuration", (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllACITasksByUser($id)
  {
    $condition = "raci.users_USERID = '" . $id . "' && projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '3' && raci.ROLE != '1'";
    $this->db->select('*, CURDATE() as "currentDate", (DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1) as "taskDuration", (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllMainActivitiesByUser($id)
  {
    $condition = "raci.users_USERID = '" . $id . "' && projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '1'";
    $this->db->select('*, CURDATE() as "currentDate", (DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1) as "taskDuration", (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllSubActivitiesByUser($id)
  {
    $condition = "raci.users_USERID = '" . $id . "' && projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '2'";
    $this->db->select('*, CURDATE() as "currentDate", (DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1) as "taskDuration", (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

// GET PRE-REQUISITE ID
  public function getDependencies()
  {
    $condition = "projects.PROJECTID = 1";
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('dependencies', 'tasks.TASKID = dependencies.tasks_POSTTASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getDependenciesByTaskID($taskID)
  {
    $condition = "dependencies.tasks_POSTTASKID = '$taskID'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('dependencies', 'raci.tasks_TASKID = dependencies.PRETASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  // Returns 0 if all tasks under a parent task are complete
  public function checkTasksStatus($parentID)
  {
    $condition = "tasks_TASKPARENT = '$parentID' && tasks.TASKSTATUS != 'Complete'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->where($condition);

    return $this->db->get()->num_rows();
  }

  // Returns 0 if all tasks in a project are complete
  public function checkProjectStatus($projectID)
  {
    $condition = "projects_PROJECTID = '$projectID' && tasks.TASKSTATUS != 'Complete'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->where($condition);

    return $this->db->get()->num_rows();
  }

  public function getParentTask($taskID)
  {
    $condition = "TASKID = '$taskID'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

// RETURNS ARRAY OF DEPARTMENTS
  public function getAllDepartments()
  {
    $this->db->select('*');
    $this->db->from('departments');
    $query = $this->db->get();

    return $query->result_array();
  }

// GETS ALL DEPARTMENTS INVOLVED IN A PROJECT
  public function getAllDepartmentsByProject($projectID)
  {
    $condition = "tasks.projects_PROJECTID = " . $projectID;
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('DEPARTMENTNAME');

    return $this->db->get()->result_array();
  }

// GETS ALL THE USERS OF A DEPARTMENT THAT ARE INVOLVED IN A PROJECT EXCEPT THE SESSION USER
  public function getAllUserstsByProjectByDepartment($projectID, $departmentID)
  {
    $condition = "projects_PROJECTID = " . $projectID . " AND departments_DEPARTMENTID = " . $departmentID;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "taskDuration"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('users_USERID');

    return $this->db->get()->result_array();
  }

// GETS ALL USERS INVOLVED IN A PROJECT
  public function getAllUsersByProject($projectID)
  {
    $condition = "tasks.projects_PROJECTID = " . $projectID;
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('users_USERID');

    return $this->db->get()->result_array();
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
    $this->db->select('*, CURDATE() as "currentDate"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function getUserByID($id)
  {
    $condition = "USERID = " . $id;
    $this->db->select('*, CURDATE() as "currentDate"');
    $this->db->from('users');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  // GET DATA FOR THE GANTT CHART
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

  public function getAllMainActivitiesByID($id)
  {
    $condition = "projects.PROJECTID = " . $id . " AND tasks.CATEGORY = '1'";
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

  public function getAllSubActivitiesByID($id)
  {
    $condition = "projects.PROJECTID = " . $id . " AND tasks.CATEGORY = 2";
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

  public function getAllTasksByID($id)
  {
    $condition = "projects.PROJECTID = " . $id . " AND tasks.CATEGORY = 3";
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

// FOR TEAM GANTT CHART
  public function getAllProjectTasksByDepartment($projectID, $departmentID)
  {
    $condition = "projects_PROJECTID = " . $projectID . " AND departments_DEPARTMENTID = " . $departmentID;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "taskDuration"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function uploadDocument($data)
  {
    $this->db->insert('documents', $data);
    $id = $this->db->insert_id();

    return $id;
  }

  public function addToDocumentAcknowledgement($data)
  {
    $this->db->insert('documentAcknowledgement', $data);

    return true;
  }

  public function updateDocumentAcknowledgement($documentID, $userID, $currentDate)
  {
    $condition = "documents_DOCUMENTID = " . $documentID . " AND users_ACKNOWLEDGEDBY = " . $userID;
    $this->db->set('ACKNOWLEDGEDDATE', $currentDate);
    $this->db->where($condition);
    $this->db->update('documentAcknowledgement');
  }

  public function getAllDocuments()
  {
    $this->db->select('*');
    $this->db->from('documents');
    $this->db->join('projects', 'documents.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'documents.users_UPLOADEDBY = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');

    return $this->db->get()->result_array();
  }

  public function getAllDocumentsByProject($projectID)
  {
    $condition = "documents.projects_PROJECTID = " . $projectID;
    $this->db->select('*');
    $this->db->from('documents');
    $this->db->join('projects', 'documents.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'documents.users_UPLOADEDBY = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

// RETURNS ALL
  public function getDocumentAcknowledgement($userID)
  {
    $condition = "users_ACKNOWLEDGEDBY = " . $userID;
    $this->db->select('*');
    $this->db->from('documentAcknowledgement');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getDocumentAcknowledgementID($userID, $documentID)
  {
    $condition = "users_ACKNOWLEDGEDBY = " . $userID . " AND documents_DOCUMENTID = " . $documentID;
    $this->db->select('*');
    $this->db->from('documentAcknowledgement');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function getAllDocumentAcknowledgementByUser($userID)
  {
    $condition = "ACKNOWLEDGEDDATE = '' && users_ACKNOWLEDGEDBY = " . $userID;
    $this->db->select('*');
    $this->db->from('documentAcknowledgement');
    $this->db->join('documents', 'documents_DOCUMENTID = DOCUMENTID');
    $this->db->join('users', 'users_UPLOADEDBY = USERID');
    $this->db->join('departments', 'departments_DEPARTMENTID = DEPARTMENTID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function insertParentTask($data, $id)
  {
    $this->db->where('TASKID', $id);
    $this->db->update('tasks', $data);
  }

  public function addToRaci($data)
  {
    $this->db->insert('raci', $data);
    return true;
  }

  public function updateResponsible($taskID, $data)
  {
    $condition = "tasks_TASKID = '$taskID' && ROLE = '1'";
    $this->db->where($condition);
    $this->db->update('raci', $data);
  }

  public function getAllResponsibleByProject($projectID)
  {
    $condition = "tasks.projects_PROJECTID = '$projectID' && ROLE = '1'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAlLAccountableByProject($projectID)
  {
    $condition = "tasks.projects_PROJECTID = '$projectID' && ROLE = '2'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllConsultedByProject($projectID)
  {
    $condition = "tasks.projects_PROJECTID = '$projectID' && ROLE = '3'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllInformedByProject($projectID)
  {
    $condition = "tasks.projects_PROJECTID = '$projectID' && ROLE = '4'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function addRFC($data)
  {
    $this->db->insert('changerequests', $data);
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

  public function addToProjectLogs($data)
  {
    $this->db->insert('logs', $data);
    return true;
  }

  public function updateTaskStatus($currentDate)
  {
    $condition = "TASKSTARTDATE = CURDATE() AND TASKSTATUS = 'Planning';";
    $this->db->set('TASKSTATUS', 'Ongoing');
    $this->db->set('TASKACTUALSTARTDATE', $currentDate);
    $this->db->where($condition);
    $this->db->update('tasks');
  }

  public function updateProjectStatus($currentDate)
  {
    $condition = "PROJECTSTARTDATE = CURDATE() AND PROJECTSTATUS = 'Planning';";
    $this->db->set('PROJECTSTATUS', 'Ongoing');
    $this->db->set('PROJECTACTUALSTARTDATE', $currentDate);
    $this->db->where($condition);
    $this->db->update('projects');
  }

  public function getDelayedTasksByUser()
  {
    $condition = "tasks.TASKENDDATE < CURDATE() AND TASKSTATUS = 'Ongoing' AND raci.users_USERID = " . $_SESSION['USERID'];
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
    $this->db->select('*, DATEDIFF(TASKENDDATE, CURDATE()) AS TASKDATEDIFF');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);
    $this->db->order_by('tasks.TASKENDDATE','ASC');

    return $this->db->get()->result_array();
  }

  public function getAllNotificationsByUser()
  {
    $condition = "status = 'Unread' AND users_USERID = " . $_SESSION['USERID'];
    $this->db->select('*');
    $this->db->from('notifications');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function addNotification($data)
  {
    $this->db->insert('logs', $data);
    return true;
  }

  public function getTasksWithTomorrowDeadline()
  {
    $condition = "TASKSTATUS != 'Complete' AND DATEDIFF(TASKENDDATE, CURDATE()) = 1";
    $this->db->select('*, DATEDIFF(TASKENDDATE, CURDATE()) AS TASKDATEDIFF');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);
    $this->db->order_by('tasks.TASKENDDATE','ASC');

    return $this->db->get()->result_array();
  }

  public function getOngoingProjectProgress()
  {
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Ongoing" AND !(projectenddate < CURDATE())');
    $this->db->group_by('projects_PROJECTID');
    $this->db->order_by('PROJECTENDDATE');
    $this->db->limit('');

    return $this->db->get()->result_array();
  }

  public function getDelayedProjectProgress()
  {
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Ongoing" AND projectenddate < CURDATE()');
    $this->db->group_by('tasks.projects_PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');

    return $this->db->get()->result_array();
  }

  public function getParkedProjectProgress()
  {
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Parked" AND !(projectenddate < CURDATE())');
    $this->db->group_by('tasks.projects_PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');

    return $this->db->get()->result_array();
  }

  public function getOngoingProjectProgressByTeam($departmentID)
  {
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Ongoing"
    AND !(projectenddate < CURDATE()) AND users.departments_DEPARTMENTID = ' . $departmentID);
    $this->db->group_by('projects_PROJECTID');
    $this->db->order_by('PROJECTENDDATE');
    $this->db->limit('');

    return $this->db->get()->result_array();
  }

  public function getDelayedProjectProgressByTeam($departmentID)
  {
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Ongoing"
    AND projectenddate < CURDATE() AND users.departments_DEPARTMENTID = ' . $departmentID);
    $this->db->group_by('tasks.projects_PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');

    return $this->db->get()->result_array();
  }

  public function getParkedProjectProgressByTeam($departmentID)
  {
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Parked"
    AND !(projectenddate < CURDATE()) AND users.departments_DEPARTMENTID = ' . $departmentID);
    $this->db->group_by('tasks.projects_PROJECTID');
    $this->db->order_by('projects.PROJECTENDDATE');

    return $this->db->get()->result_array();
  }

  public function addToDependencies($data)
  {
    $this->db->insert('dependencies', $data);
    return true;
  }

  public function archiveProject($id, $data)
  {
    $this->db->where('PROJECTID', $id);
    $result = $this->db->update('projects', $data);

    return true;
  }
}
?>
