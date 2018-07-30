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
    $condition = "users.EMAIL =" . "'" . $data['email'] . "'";
    $this->db->select('*');
    $this->db->from('users');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
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
    $this->db->join('users', 'users.USERID = projects.users_USERID');
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

  public function getChangeRequestsForApproval($filter, $id)
  {
    $this->db->select('*');
    $this->db->from('changerequests');
    $this->db->join('tasks', 'changerequests.tasks_REQUESTEDTASK = tasks.TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'users.USERID = changerequests.users_REQUESTEDBY');
    $this->db->where($filter . " && changerequests.users_REQUESTEDBY != '$id' && changeRequests.REQUESTSTATUS = 'Pending'");
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getChangeRequestsByUser($id)
  {
    $this->db->select('*');
    $this->db->from('changerequests');
    $this->db->join('tasks', 'changerequests.tasks_REQUESTEDTASK = tasks.TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'users.USERID = changerequests.users_APPROVEDBY');
    $this->db->where("changerequests.users_REQUESTEDBY = '$id' && projects.PROJECTSTATUS = 'Ongoing'");
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getChangeRequestbyID($requestID)
  {
    $condition = "REQUESTID = '$requestID'";
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('changerequests');
    $this->db->join('tasks', 'changerequests.tasks_REQUESTEDTASK = tasks.TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'users.USERID = changerequests.users_REQUESTEDBY');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->row_array();
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
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getAllUsersByDepartment($deptID)
  {
    $condition = "users.departments_DEPARTMENTID = '$deptID' && USERID != '1'";
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($condition);
    $query = $this->db->get();

    return $query->result_array();
  }

  public function getProjectCount($filter)
  {
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.ROLE = '1' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'";
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
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.ROLE = '1' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'";
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
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.users_USERID = '$userID' && raci.ROLE = '1' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'";
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
    $condition = "projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && raci.users_USERID = '$userID' && projects.PROJECTID = '$projectID' && raci.ROLE = '1' && raci.STATUS = 'Current' && tasks.CATEGORY = '3'";
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
    $condition = "PROJECTSTARTDATE <= CURDATE() && PROJECTENDDATE >= CURDATE() && PROJECTSTATUS = 'Ongoing'";
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
    $condition = "((raci.users_USERID = '$userID' && raci.STATUS = 'Current') || projects.users_USERID = '$userID') && projects.PROJECTSTARTDATE <= CURDATE() && projects.PROJECTENDDATE > CURDATE() && projects.PROJECTSTATUS = 'Ongoing'";
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
    $condition = "((raci.users_USERID = '$userID' && raci.STATUS = 'Current') || projects.users_USERID = '$userID') && projects.PROJECTSTATUS = 'Planning'";
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
    $condition = "((raci.users_USERID = '$userID' && raci.STATUS = 'Current') || projects.users_USERID = '$userID') && PROJECTENDDATE < CURDATE() && PROJECTSTATUS = 'Ongoing'";
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
    $condition = "((raci.users_USERID = '$userID' && raci.STATUS = 'Current') || projects.users_USERID = '$userID') && PROJECTSTATUS = 'Parked'";
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
    $this->db->select('templates.*, projects.PROJECTACTUALSTARTDATE, projects.PROJECTACTUALENDDATE, users.FIRSTNAME, users.LASTNAME');
    $this->db->from('templates');
    $this->db->join('projects', 'templates.PROJECTSTATUS = projects.PROJECTID');
    $this->db->join('users', 'projects.users_USERID = users.USERID');

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
		$this->db->select('raci.STATUS = "Current" && COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
		ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
		$this->db->from('tasks');
		$this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
		$this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
		$this->db->join('users', 'raci.users_USERID = users.USERID');
		$this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Ongoing"
		AND !(projectenddate < CURDATE()) AND raci.status = "Current" AND role = 1 AND users.departments_DEPARTMENTID = ' . $departmentID);
		$this->db->group_by('projects_PROJECTID');
		$this->db->order_by('PROJECTENDDATE');
		$this->db->limit('');

		return $this->db->get()->result_array();
	}

	public function getDelayedProjectProgressByTeam($departmentID)
	{
		$this->db->select('raci.STATUS = "Current" && COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
		ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
		$this->db->from('tasks');
		$this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
		$this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
		$this->db->join('users', 'raci.users_USERID = users.USERID');
		$this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Ongoing"
		AND projectenddate < CURDATE() AND raci.status = "Current" AND role = 1 AND users.departments_DEPARTMENTID = ' . $departmentID);
		$this->db->group_by('tasks.projects_PROJECTID');
		$this->db->order_by('projects.PROJECTENDDATE');

		return $this->db->get()->result_array();
	}

	public function getParkedProjectProgressByTeam($departmentID)
	{
		$this->db->select('raci.STATUS = "Current" && COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
		ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL))*(100 / COUNT(taskid))), 2) AS "projectProgress"');
		$this->db->from('tasks');
		$this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
		$this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
		$this->db->join('users', 'raci.users_USERID = users.USERID');
		$this->db->where('CATEGORY = 3 AND projects.PROJECTSTATUS = "Parked"
		AND !(projectenddate < CURDATE()) AND raci.status = "Current" AND role = 1 AND users.departments_DEPARTMENTID = ' . $departmentID);
		$this->db->group_by('tasks.projects_PROJECTID');
		$this->db->order_by('projects.PROJECTENDDATE');

		return $this->db->get()->result_array();
	}

  public function getAllTasksByUser($id)
  {
    $condition = "raci.users_USERID = '" . $id . "' && raci.STATUS = 'Current' && projects.PROJECTSTATUS != 'Complete' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '3' && raci.ROLE = '1'";
    $this->db->select('*, DATE_ADD(CURDATE(), INTERVAL +2 day) as "threshold" , DATEDIFF(CURDATE(), tasks.TASKSTARTDATE) as "delay",
    CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllACITasksByUser($id, $status)
  {
    $condition = "raci.users_USERID = '" . $id . "' && raci.STATUS = 'Current' && projects.PROJECTSTATUS = 'Ongoing' && tasks.TASKSTATUS = '$status' && tasks.CATEGORY = '3' && raci.ROLE != '1' && raci.ROLE != '0' && raci.ROLE != '5'";
    $this->db->select('*, CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getUniqueACITasksByUser($id, $status)
  {
    $condition = "raci.users_USERID = '" . $id . "' && raci.STATUS = 'Current' && projects.PROJECTSTATUS = 'Ongoing' && tasks.TASKSTATUS = '$status' && tasks.CATEGORY = '3' && raci.ROLE != '1' && raci.ROLE != '0' && raci.ROLE != '5'";
    $this->db->select('*, CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->group_by('tasks.TASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllMainActivitiesByUser($id)
  {
    $condition = "raci.users_USERID = '" . $id . "' && raci.STATUS = 'Current' && projects.PROJECTSTATUS = 'Planning' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '1'";
    $this->db->select('*, CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
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
    $condition = "raci.ROLE = '0' && raci.users_USERID = '" . $id . "' && raci.STATUS = 'Current' && projects.PROJECTSTATUS = 'Planning' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '2'";
    $this->db->select('*, CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKENDDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllProjectsToEditByUser($id)
  {
    $condition = "raci.ROLE = '0' &&  raci.users_USERID = '" . $id . "' && raci.STATUS = 'Current' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '3'";
    $this->db->select('*, DATE_ADD(CURDATE(), INTERVAL +2 day) as "threshold", CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration",
    DATEDIFF(PROJECTSTARTDATE, CURDATE())+1 as "launching"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->group_by('projects.PROJECTID');
    $this->db->order_by('tasks.TASKSTARTDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllActivitiesToEditByUser($id)
  {
    $condition = "raci.ROLE = '0' && raci.users_USERID = '" . $id . "' && raci.STATUS = 'Current' && tasks.TASKSTATUS != 'Complete' && tasks.CATEGORY = '3'";
    $this->db->select('*, DATE_ADD(CURDATE(), INTERVAL +2 day) as "threshold", CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2",
    (DATEDIFF(projects.PROJECTENDDATE, projects.PROJECTSTARTDATE) + 1) as "projectDuration",
    DATEDIFF(PROJECTSTARTDATE, CURDATE())+1 as "launching"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->order_by('tasks.TASKSTARTDATE');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function checkForDelegation($taskID)
  {
    $condition = "tasks_TASKID = " . $taskID . " && ROLE = '0' && STATUS = 'Current'";
    $this->db->select('*');
    $this->db->from('raci');
    $this->db->where($condition);

    return $this->db->get()->num_rows();
  }

// GET PRE-REQUISITE ID
  public function getDependenciesByProject($projectID)
  {
    $condition = "projects.PROJECTID = " . $projectID;
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->join('tasks', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('dependencies', 'tasks.TASKID = dependencies.tasks_POSTTASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getDependenciesByTaskID($taskID)
  {
    $condition = "raci.STATUS = 'Current' && dependencies.tasks_POSTTASKID = '$taskID' && raci.ROLE = '1'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('dependencies', 'raci.tasks_TASKID = dependencies.PRETASKID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getPostDependenciesByTaskID($taskID)
  {
    $condition = "raci.STATUS = 'Current' && dependencies.PRETASKID = '$taskID' && raci.ROLE = '1'";
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('dependencies', 'raci.tasks_TASKID = dependencies.tasks_POSTTASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
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
    $condition = "raci.STATUS = 'Current' && tasks.projects_PROJECTID = " . $projectID;
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
  public function getAllUsersByProjectByDepartment($projectID, $departmentID)
  {
    $condition = "raci.STATUS = 'Current' && projects_PROJECTID = " . $projectID . " AND departments_DEPARTMENTID = " . $departmentID;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
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
    $condition = "raci.STATUS = 'Current' && tasks.projects_PROJECTID = " . $projectID;
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
    $this->db->select('*, CURDATE() as "currentDate", DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
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
    $condition = "raci.STATUS = 'Current' && projects.PROJECTID = " . $id;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
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
    // initialTaskDuration
    $condition = "raci.STATUS = 'Current' && projects.PROJECTID = " . $id;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
    $this->db->from('tasks');
    $this->db->join('projects', 'projects.PROJECTID = tasks.projects_PROJECTID');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('tasks.TASKID');
    $this->db->group_by('tasks.TASKSTARTDATE');

    return $this->db->get()->result_array();
  }

  public function getAllMainActivitiesByID($id)
  {
    $condition = "raci.STATUS = 'Current' && projects.PROJECTID = " . $id . " AND tasks.CATEGORY = '1'";
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
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
    $condition = "raci.STATUS = 'Current' && projects.PROJECTID = " . $id . " AND tasks.CATEGORY = 2";
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
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
    $condition = "raci.STATUS = 'Current' && projects.PROJECTID = " . $id . " AND tasks.CATEGORY = 3";
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
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
    $condition = "raci.STATUS = 'Current' && projects_PROJECTID = " . $projectID . " AND departments_DEPARTMENTID = " . $departmentID;
    $this->db->select('*, DATEDIFF(tasks.TASKENDDATE, tasks.TASKSTARTDATE) + 1 as "initialTaskDuration",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKSTARTDATE) + 1 as "adjustedTaskDuration1",
    DATEDIFF(tasks.TASKADJUSTEDENDDATE, tasks.TASKADJUSTEDSTARTDATE) + 1 as "adjustedTaskDuration2"');
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

    return true;
  }

  // FOR PRESIDENT
  public function getAllDocuments()
  {
    $this->db->select('*');
    $this->db->from('documents');
    $this->db->join('projects', 'documents.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', 'documents.users_UPLOADEDBY = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');

    return $this->db->get()->result_array();
  }

  // DOCUMENTS IN SPECIFIC PROJECT
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

  // DOCUMENTS IN SPECIFIC PROJECT THAT NEEDS ACKNOWLEDGEMENT
  public function getDocumentsForAcknowledgement($projectID, $userID)
  {
    $condition = "documents.projects_PROJECTID = " . $projectID . " AND users_ACKNOWLEDGEDBY = " . $userID . " AND users_UPLOADEDBY != " . $userID;
    $this->db->select('*');
    $this->db->from('documents');
    $this->db->join('projects', 'documents.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('documentAcknowledgement', 'documents.DOCUMENTID = documentAcknowledgement.documents_DOCUMENTID');
    $this->db->join('users', 'documents.users_UPLOADEDBY = users.USERID');
    $this->db->join('departments', 'users.departments_DEPARTMENTID = departments.DEPARTMENTID');
    $this->db->where($condition);
    $this->db->group_by('documents.DOCUMENTID');

    return $this->db->get()->result_array();
  }

  // CHECKS FOR DUPLICATE WHEN UPLOADING
  public function getDocumentAcknowledgementID($userID, $documentID)
  {
    $condition = "users_ACKNOWLEDGEDBY = " . $userID . " AND documents_DOCUMENTID = " . $documentID;
    $this->db->select('*');
    $this->db->from('documentAcknowledgement');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  // DASHBOARD
  public function getAllDocumentAcknowledgementByUser($userID)
  {
    $condition = "ACKNOWLEDGEDDATE = '' && users_ACKNOWLEDGEDBY = " . $userID . " && users_UPLOADEDBY != '$userID'";
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

  public function updateRACI($taskID, $role)
  {
    $condition = "tasks_TASKID = '$taskID' && ROLE = '$role'";
    $this->db->set('STATUS', 'Changed');
    $this->db->where($condition);
    $this->db->update('raci');
  }

  public function getAllResponsibleByProject($projectID)
  {
    $condition = "raci.STATUS = 'Current' && tasks.projects_PROJECTID = '$projectID' && ROLE = '1'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAlLAccountableByProject($projectID)
  {
    $condition = "raci.STATUS = 'Current' && tasks.projects_PROJECTID = '$projectID' && ROLE = '2'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllConsultedByProject($projectID)
  {
    $condition = "raci.STATUS = 'Current' && tasks.projects_PROJECTID = '$projectID' && ROLE = '3'";
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('users', 'raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getAllInformedByProject($projectID)
  {
    $condition = "raci.STATUS = 'Current' && tasks.projects_PROJECTID = '$projectID' && ROLE = '4'";
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

  public function updateRFC($requestID, $data)
  {
    $this->db->where('REQUESTID', $requestID);
    $result = $this->db->update('changerequests', $data);

    return true;
  }

  public function updateTaskDates($taskID, $data)
  {
    $this->db->where('TASKID', $taskID);
    $result = $this->db->update('tasks', $data);

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

  public function updateTaskStatus()
  {
    $condition = "TASKSTARTDATE <= CURDATE() AND TASKSTATUS = 'Planning';";
    $this->db->set('TASKSTATUS', 'Ongoing');
    $this->db->set('TASKACTUALSTARTDATE', 'TASKSTARTDATE');
    $this->db->where($condition);
    $this->db->update('tasks');
  }

  public function updateProjectStatus()
  {
    $condition = "PROJECTSTARTDATE <= CURDATE() AND PROJECTSTATUS = 'Planning';";
    $this->db->set('PROJECTSTATUS', 'Ongoing');
    $this->db->set('PROJECTACTUALSTARTDATE', 'PROJECTSTARTDATE');
    $this->db->where($condition);
    $this->db->update('projects');
  }

  public function parkProjectByID($projectID)
  {
    $condition = "PROJECTID = '$projectID';";
    $this->db->set('PROJECTSTATUS', 'Parked');
    $this->db->where($condition);
    $this->db->update('projects');
  }

  public function getTasks2DaysBeforeDeadline()
  {
    $condition = "raci.STATUS = 'Current' AND TASKSTATUS != 'Complete' AND DATEDIFF(TASKENDDATE, CURDATE()) <= 2
     AND CATEGORY = 3 AND raci.users_USERID = " . $_SESSION['USERID'];
    $this->db->select('*, DATEDIFF(TASKENDDATE, CURDATE()) AS "DATEDIFF", raci.users_USERID AS "TASKOWNER", projects.users_USERID AS "PROJECTOWNER"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks.TASKID = raci.tasks_TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);
    $this->db->group_by('tasks.TASKID');
    $this->db->order_by('tasks.TASKENDDATE','ASC');

    return $this->db->get()->result_array();
  }

  public function getAllNotificationsByUser()
  {
    $condition = "users_USERID = " . $_SESSION['USERID'];
    $this->db->select('notifications.*, datediff(curdate(), timestamp) as "DATEDIFF"');
    $this->db->from('notifications');
    $this->db->where($condition);
    $this->db->order_by('TIMESTAMP','DESC');

    return $this->db->get()->result_array();
  }

  public function checkNotification($currentDate, $details, $userID)
  {
    $condition = "datediff(TIMESTAMP, CURDATE()) = 0 AND DETAILS = '" . $details . "' AND users_USERID =" . $userID;
    $this->db->select('*, datediff(curdate(), timestamp) as "DATEDIFF"');
    $this->db->from('notifications');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function addNotification($data)
  {
    $this->db->insert('notifications', $data);
    return true;
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

  public function parkProject($id, $data)
  {
    $this->db->where('PROJECTID', $id);
    $result = $this->db->update('projects', $data);

    return true;
  }

  public function templateProject($data)
  {
    $this->db->insert('templates', $data);
    return true;
  }

  public function getRaci($id)
  {
    $condition = "projects.PROJECTID = '" . $id . "' AND raci.STATUS = 'Current'";
    $this->db->select('raci.*, users.departments_DEPARTMENTID as uDept, tasks.CATEGORY as tCat');
    $this->db->from('raci');
    $this->db->join('tasks', 'raci.tasks_TASKID = tasks.TASKID');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->join('users', ' raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getRACIbyTask($taskID){
    $condition = "tasks_TASKID = '" . $taskID . "' AND raci.STATUS = 'Current'";
    $this->db->select('*');
    $this->db->from('raci');
    $this->db->join('users', ' raci.users_USERID = users.USERID');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getACIbyTask($taskID){
    $condition = "tasks_TASKID = " . $taskID . " AND raci.STATUS = 'Current' AND role != 1";
    $this->db->select('*');
    $this->db->from('raci');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function getLatestWeeklyProgress(){
    $condition = "datediff(curdate(), DATE) <= 7 && datediff(curdate(), DATE) > 0 ";
    $this->db->select('*,  datediff(curdate(), DATE) as "datediff"');
    $this->db->from('assessmentProject');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function checkAssessmentProject($projectID){
    $condition = "datediff(curdate(), DATE) = 7";
    $this->db->select('*, datediff(curdate(), DATE) as "datediff"');
    $this->db->from('assessmentProject');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function addAssessmentProject($data){

    $this->db->insert('assessmentProject', $data);

    return true;
  }

  public function getLatestAssessmentDepartment(){
    $condition = "datediff(curdate(), DATE) <= 7 && datediff(curdate(), DATE) > 0 ";
    $this->db->select('*,  datediff(curdate(), DATE) as "datediff"');
    $this->db->from('assessmentDepartment');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function checkAssessmentDepartment(){
    $condition = "datediff(curdate(), DATE) = 7";
    $this->db->select('*, datediff(curdate(), DATE) as "datediff"');
    $this->db->from('assessmentDepartment');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function addAssessmentDepartment($data){

    $this->db->insert('assessmentDepartment', $data);

    return true;
  }

  public function getLatestAssessmentEmployee(){
    $condition = "datediff(curdate(), DATE) <= 7 && datediff(curdate(), DATE) > 0 ";
    $this->db->select('*,  datediff(curdate(), DATE) as "datediff"');
    $this->db->from('assessmentEmployee');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function checkAssessmentEmployee(){
    $condition = "datediff(curdate(), DATE) = 7";
    $this->db->select('*, datediff(curdate(), DATE) as "datediff"');
    $this->db->from('assessmentEmployee');
    $this->db->where($condition);

    return $this->db->get()->result_array();
  }

  public function addAssessmentEmployee($data){

    $this->db->insert('assessmentEmployee', $data);

    return true;
  }

  public function editProject($id, $data)
  {
    $this->db->where('PROJECTID', $id);
    $result = $this->db->update('projects', $data);
  }

  public function updateProjectStatusPlanning($id, $status)
  {
    $this->db->where('PROJECTID', $id);
    $result = $this->db->update('projects', $status);
  }

  public function editProjectTask($id, $data)
  {
    $this->db->where('TASKID', $id);
    $result = $this->db->update('tasks', $data);

    return $id;
  }

  public function updateRaciStatus($id, $data)
  {
    $this->db->where('tasks_TASKID', $id);
    $result = $this->db->update('raci', $data);
  }

  public function compute_completeness_employee($userID){
    $condition = "CATEGORY = 3 && raci.status = 'Current' && role = 1 && users_USERID = " . $userID;
		$this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
		ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
		$this->db->from('tasks');
		$this->db->join('raci', 'tasks_TASKID = TASKID');
		$this->db->where($condition);

		return $this->db->get()->row_array();
  }

  public function compute_timeliness_employee($userID){
    $condition = "CATEGORY = 3 && TASKACTUALSTARTDATE != ''  && raci.status = 'Current' && role = 1 && users_USERID = " . $userID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE, 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_completeness_employeeByProject($userID, $projectID)
  {
    $condition = "CATEGORY = 3 && raci.status = 'Current' && role = 1 && users_USERID = " . $userID . " && projects_PROJECTID = " . $projectID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_timeliness_employeeByProject($userID, $projectID)
  {
    $condition = "CATEGORY = 3 && TASKACTUALSTARTDATE != ''  && raci.status = 'Current' && role = 1 && users_USERID = " . $userID . " && projects_PROJECTID = " . $projectID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE, 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_completeness_department($deptID)
  {
    $condition = "CATEGORY = 3 && raci.status = 'Current' && role = 1 && departments_DEPARTMENTID = " . $deptID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('users', 'users_USERID = USERID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_timeliness_department($deptID)
  {
    $condition = "CATEGORY = 3 && TASKACTUALSTARTDATE != '' && raci.status = 'Current' && role = 1 && departments_DEPARTMENTID = " . $deptID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE, 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('users', 'users_USERID = USERID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_completeness_departmentByProject($deptID, $projectID)
  {
    $condition = "CATEGORY = 3 && raci.status = 'Current' && role = 1 && departments_DEPARTMENTID = " . $deptID . " && projects_PROJECTID = " . $projectID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('users', 'users_USERID = USERID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_timeliness_departmentByProject($deptID, $projectID)
  {
    $condition = "CATEGORY = 3 && TASKACTUALSTARTDATE != '' && raci.status = 'Current' && role = 1 && departments_DEPARTMENTID = " . $deptID . " && projects_PROJECTID = " . $projectID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE, 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('raci', 'tasks_TASKID = TASKID');
    $this->db->join('users', 'users_USERID = USERID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_completeness_project($projectID)
  {
    $condition = "CATEGORY = 3 AND tasks.projects_PROJECTID = " . $projectID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(taskstatus = "Complete", 1, NULL)) * (100 / COUNT(taskid))), 2) AS "completeness"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

  public function compute_timeliness_project($projectID)
  {
    $condition = "CATEGORY = 3 AND TASKACTUALSTARTDATE != '' AND tasks.projects_PROJECTID = " . $projectID;
    $this->db->select('COUNT(TASKID), projects_PROJECTID, (100 / COUNT(taskstatus)),
    ROUND((COUNT(IF(TASKACTUALENDDATE <= TASKENDDATE, 1, NULL)) * (100 / COUNT(taskid))), 2) AS "timeliness"');
    $this->db->from('tasks');
    $this->db->join('projects', 'tasks.projects_PROJECTID = projects.PROJECTID');
    $this->db->where($condition);

    return $this->db->get()->row_array();
  }

}
?>
