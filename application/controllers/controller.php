<!-- $this->output->enable_profile(TRUE); -->

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->model("model");
		$this->load->library('session');
		$this->load->helper(array('form', 'url'));
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function login()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('login');
		}

		else
		{
			$this->load->view('contact');
		}
	}

//LOGS USER OUT AND DESTROYS SESSION
	public function logout()
	{
		unset($_SESSION);
	  session_destroy();
	  session_write_close();

		$this->load->view("login");
	}

// CHECKS IF EMAIL AND PASSWORD MATCH DB
	public function validateLogin()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'email', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			 $this->load->view('login', $this->data);
		}

		if ($this->form_validation->run() == TRUE)
		{
			$data = array(
					'email' => $this->input->post('email'),
					// 'password' => password_verify($this->input->post('password'), hashedpasswordfromDB)
					//'password' => md5($this->input->post('password'))
					'password' => $this->input->post('password')
			);

			$result = $this->model->checkDatabase($data);

			if($result == true)
			{
				$sessionData = $this->model->getUserData($data);
				$this->session->set_userdata($sessionData);

				// $notificationsData = $this->model->getAllNotificationsByUser();
				// $this->session->set_userdata('notificationsData', $notificationsData);

				$currentDate = date('Y-m-d');

				$this->model->updateTaskStatus($currentDate);
				$this->model->updateProjectStatus($currentDate);

				redirect('controller/dashboard');

					// if ($userType == 1 || $userType == 5 || $userType == 6 || $userType == 7)
					// {
					// 	$this->load->view("home");
					// 	//redirect('guardwatch_controller/viewHome');
					// }
					//
					// elseif ($userType == 2)
					// {
					// 	$sessionData = $this->dbtest_model->getUserData_Client($data);
					// 	$this->session->set_userdata($sessionData);
					//
					// 	$this->load->view("home");
					// 	// redirect('guardwatch_controller/viewHome');
					// }
					//
					// elseif ($userType == 3)
					// {
					// 	$sessionData = $this->dbtest_model->getUserData_Guard($data);
					// 	$this->session->set_userdata($sessionData);
					//
					// 	$this->load->view("home");
					// 	// redirect('guardwatch_controller/viewHome');
					// }
					//
					// elseif ($userType == 4)
					// {
					// 	$sessionData = $this->dbtest_model->getUserData_Guard($data);
					// 	$this->session->set_userdata($sessionData);
					//
					// 	$this->load->view("home");
					// 	//redirect('guardwatch_controller/viewHome');
					// }
				}

			else
			{
				$email = $this->input->post('email');

				$this->session->set_flashdata('stickyemail', $email);
				$this->session->set_flashdata('danger', 'email or Password is incorrect');
				redirect('controller/login');
			}
		}
	}

	public function contact()
	{
		$this->load->view('contact');
	}

	public function dashboard()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$data['delayedTaskPerUser'] = $this->model->getDelayedTasksByUser();
			$data['tasks3DaysBeforeDeadline'] = $this->model->getTasks3DaysBeforeDeadline();
			$this->load->view("dashboard", $data);
		}
	}

	public function myProjects()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			if ($_SESSION['departments_DEPARTMENTID'] == '1') //ONLY EXECUTIVES CAN VIEW ALL PROJECTS
			{
				$data['ongoingProjects'] = $this->model->getAllOngoingProjects();
				$data['plannedProjects'] = $this->model->getAllPlannedProjects();
				$data['delayedProjects'] = $this->model->getAllDelayedProjects();
				$data['parkedProjects'] = $this->model->getAllParkedProjects();
				$data['draftedProjects'] = $this->model->getAllDraftedProjects();
				$data['completedProjects'] = $this->model->getAllCompletedProjects();
			}
			else
			{
				$data['ongoingProjects'] = $this->model->getAllOngoingProjectsByUser($_SESSION['USERID']);
				$data['plannedProjects'] = $this->model->getAllPlannedProjectsByUser($_SESSION['USERID']);
				$data['delayedProjects'] = $this->model->getAllDelayedProjectsByUser($_SESSION['USERID']);
				$data['parkedProjects'] = $this->model->getAllParkedProjectsByUser($_SESSION['USERID']);
				$data['draftedProjects'] = $this->model->getAllDraftedProjectsByUser($_SESSION['USERID']);
				$data['completedProjects'] = $this->model->getAllCompletedProjectsByUser($_SESSION['USERID']);
			}

			$data['ongoingProjectProgress'] = $this->model->getOngoingProjectProgress();
			$data['delayedProjectProgress'] = $this->model->getDelayedProjectProgress();
			$data['parkedProjectProgress'] = $this->model->getParkedProjectProgress();

			$this->load->view("myProjects", $data);
		}
	}

	public function myTeam()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			if ($_SESSION['departments_DEPARTMENTID'] == '1') //ONLY EXECUTIVES CAN VIEW ALL PROJECTS
			{
				$data['ongoingProjects'] = $this->model->getAllOngoingProjects();
				$data['plannedProjects'] = $this->model->getAllPlannedProjects();
				$data['delayedProjects'] = $this->model->getAllDelayedProjects();
				$data['parkedProjects'] = $this->model->getAllParkedProjects();
				$data['draftedProjects'] = $this->model->getAllDraftedProjects();
			}
			else
			{
				$data['ongoingProjects'] = $this->model->getAllOngoingProjectsByUser($_SESSION['USERID']);
				$data['plannedProjects'] = $this->model->getAllPlannedProjectsByUser($_SESSION['USERID']);
				$data['delayedProjects'] = $this->model->getAllDelayedProjectsByUser($_SESSION['USERID']);
				$data['parkedProjects'] = $this->model->getAllParkedProjectsByUser($_SESSION['USERID']);
				$data['draftedProjects'] = $this->model->getAllDraftedProjectsByUser($_SESSION['USERID']);

			}

			$data['ongoingProjectProgress'] = $this->model->getOngoingProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			$data['delayedProjectProgress'] = $this->model->getDelayedProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			$data['parkedProjectProgress'] = $this->model->getParkedProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);

			$this->load->view("myTeam", $data);
		}
	}

	public function myTasks()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$data['departments'] = $this->model->getAllDepartments();

			switch($_SESSION['usertype_USERTYPEID'])
			{
				case '2':
					$filter = "users.usertype_USERTYPEID = '3'";
					break;

				case '3':
					$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
					break;

				case '4':
					$filter = "users.users_SUPERVISORS = '" . $_SESSION['USERID'] ."'";
					break;

				default:
					$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
					break;
			}

			$data['departments'] = $this->model->getAllDepartments();
			$data['deptEmployees'] = $this->model->getAllUsersByUserType($filter);
			$data['wholeDept'] = $this->model->getAllUsersByUserType("users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'");
			$data['projectCount'] = $this->model->getProjectCount($filter);
			$data['taskCount'] = $this->model->getTaskCount($filter);

			$data['users'] = $this->model->getAllUsers();
			$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
			$data['mainActivity'] = $this->model->getAllMainActivitiesByUser($_SESSION['USERID']);
			$data['subActivity'] = $this->model->getAllSubActivitiesByUser($_SESSION['USERID']);

			$this->load->view("myTasks", $data);
		}
	}

	public function doneTask()
	{
		if ($this->input->post('task_ID'))
		{
			$id = $this->input->post("task_ID");
			$remarks = $this->input->post('remarks');

			$data = array(
						'TASKSTATUS' => 'Complete',
						'TASKREMARKS' => $remarks,
						'TASKACTUALENDDATE' => date('Y-m-d')
			);

			$updateTasks = $this->model->updateTaskDone($id, $data);
		}
		$this->myTasks();
	}

	public function loadTasks()
	{
		$data['users'] = $this->model->getAllUsers();
		$data['departments'] = $this->model->getAllDepartments();
		$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
		$data['mainActivity'] = $this->model->getAllMainActivitiesByUser($_SESSION['USERID']);
		$data['subActivity'] = $this->model->getAllSubActivitiesByUser($_SESSION['USERID']);

		echo json_encode($data);
	}

	public function getDependenciesByTaskID()
	{
		$taskID = $this->input->post("task_ID");
		$data['dependencies'] = $this->model->getDependenciesByTaskID($taskID);
		$data['taskID'] = $this->model->getTaskByID($taskID);

		echo json_encode($data);
	}

	public function delegateTask()
	{
		$taskID = $this->input->post("task_ID");

		// SAVE/UPDATE RESPONSIBLE
		$responsibleEmp = $this->input->post('responsibleEmp');
		$responsibleData = array(
			'users_USERID' => $responsibleEmp,
		);
		$result = $this->model->updateResponsible($taskID, $responsibleData);

		// SAVE ACCOUNTABLE
		if($this->input->post("accountableDept[]"))
		{
			foreach($this->input->post("accountableDept[]") as $deptID)
			{
				$accountableData = array(
					'ROLE' => '2',
					'users_USERID' => $deptID,
					'tasks_TASKID' =>	$taskID
				);
				$this->model->addToRaci($accountableData);
			}
		}

		if ($this->input->post("accountableEmp[]"))
		{
			foreach($this->input->post("accountableEmp[]") as $empID)
			{
				$accountableData = array(
					'ROLE' => '2',
					'users_USERID' => $empID,
					'tasks_TASKID' =>	$taskID
				);
				$this->model->addToRaci($accountableData);
			}
		}

		// SAVE CONSULTED
		if($this->input->post("consultedDept[]"))
		{
			foreach($this->input->post("consultedDept[]") as $deptID)
			{
				$consultedData = array(
					'ROLE' => '3',
					'users_USERID' => $deptID,
					'tasks_TASKID' =>	$taskID
				);
				$this->model->addToRaci($consultedData);
			}
		}

		if($this->input->post("consultedEmp[]"))
		{
			foreach($this->input->post("consultedEmp[]") as $empID)
			{
				$consultedData = array(
					'ROLE' => '3',
					'users_USERID' => $empID,
					'tasks_TASKID' =>	$taskID
				);
				$this->model->addToRaci($consultedData);
			}
		}

		// SAVE INFORMED
		if($this->input->post("informedDept[]"))
		{
			foreach($this->input->post("informedDept[]") as $deptID)
			{
				$informedData = array(
					'ROLE' => '4',
					'users_USERID' => $deptID,
					'tasks_TASKID' =>	$taskID
				);
				$this->model->addToRaci($informedData);
			}
		}

		if($this->input->post("informedEmp[]"))
		{
			foreach($this->input->post("informedEmp[]") as $empID)
			{
				$informedData = array(
					'ROLE' => '4',
					'users_USERID' => $empID,
					'tasks_TASKID' =>	$taskID
				);
				$this->model->addToRaci($informedData);
			}
		}

		$this->myTasks();
	}

	public function submitRFC()
	{
		if($this->input->post("rfcType") == '1')
		{
			$data = array(
				'REQUESTTYPE' => $this->input->post("rfcType"),
				'tasks_REQUESTEDTASK' => $this->input->post("task_ID"),
				'REASON' => $this->input->post("reason"),
				'REQUESTSTATUS' => "Pending",
				'users_REQUESTEDBY' => $_SESSION['USERID'],
				'REQUESTEDDATE' => date('Y-m-d')
			);
		}
		else
		{
			$data = array(
				'REQUESTTYPE' => $this->input->post("rfcType"),
				'tasks_REQUESTEDTASK' => $this->input->post("task_ID"),
				'REASON' => $this->input->post("reason"),
				'REQUESTSTATUS' => "Pending",
				'users_REQUESTEDBY' => $_SESSION['USERID'],
				'REQUESTEDDATE' => date('Y-m-d'),
				'NEWSTARTDATE' => $this->input->post("startDate"),
				'NEWENDDATE' => $this->input->post("endDate"),
			);
		}
		$this->model->addRFC($data);
		$this->myTasks();
	}

	public function getUserWorkloadProjects()
	{
		$userID = $this->input->post('userID');
		$data['workloadProjects'] = $this->model->getWorkloadProjects($userID);

		echo json_encode($data);
	}

	public function getUserWorkloadTasks()
	{
		$userID = $this->input->post('userID');
		$projectID = $this->input->post('projectID');
		$data['workloadTasks'] = $this->model->getWorkloadTasks($userID, $projectID);

		echo json_encode($data);
	}


	public function templates()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$data['templates'] = $this->model->getAllTemplates();

			$this->load->view("templates", $data);
		}
	}

	public function archives()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$data['archives'] = $this->model->getAllProjectArchives();

			$this->load->view("archives", $data);
		}
	}

	public function newProject()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("newProject");
		}
	}

	public function myCalendar()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("myCalendar");
		}
	}

	public function documents()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{

			$data['documents'] = $this->model->getAllDocuments();
			$this->load->view("documents", $data);
		}
	}

	public function reports()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("reports");
		}
	}

	public function projectLogs()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$id = $this->input->post("projectID_logs");
			$this->session->set_flashdata('projectIDlogs', $id);
			$data['projectLog'] = $this->model->getProjectLogs($id);
			$this->load->view("projectLogs", $data);
		}
	}

	public function teamGantt()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$id = $this->input->post("project_ID");
			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['ganttData'] = $this->model->getAllProjectTasks($id);
			$data['dependencies'] = $this->model->getDependencies();
			$data['users'] = $this->model->getAllUsers();

			$departmentID = $_SESSION['departments_DEPARTMENTID'];

			$data['ganttData'] = $this->model->getAllProjectTasksByDepartment($id, $departmentID);
			$this->load->view("teamGantt", $data);
		}
	}

// DELETE THIS MAYBE?
	public function newProjectTask()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("newProjectTask");
		}
	}

	public function addTasks()
	{
		// CHECKS IF PROJECT HAS STARTED TO SET STATUS
		$startDate = $this->input->post('startDate');
		date_default_timezone_set("Singapore");
		$currDate = date("mm-dd-YYYY");

		if ($currDate >= $startDate)
		{
			$status = 'Ongoing';
		}

		else
		{
			$status = 'Planning';
		}

		$data = array(
				'PROJECTTITLE' => $this->input->post('projectTitle'),
				'PROJECTSTARTDATE' => $startDate,
				'PROJECTENDDATE' => $this->input->post('endDate'),
				'PROJECTDESCRIPTION' => $this->input->post('projectDetails'),
				'PROJECTSTATUS' => $status,
				'users_USERID' => $_SESSION['USERID']
		);

		$sDate = date_create($startDate);
		$eDate = date_create($this->input->post('endDate'));
		$diff = date_diff($eDate, $sDate);
		$dateDiff = $diff->format('%d');

		// PLUGS DATA INTO DB AND RETURNS ARRAY OF THE PROJECT
		$data['project'] = $this->model->addProject($data);
		$data['dateDiff'] =$dateDiff;
		$data['departments'] = $this->model->getAllDepartments();

		if ($data)
		{
			// TODO PUT ALERT
			$this->load->view('addTasks', $data);
		}

		else
		{
			// TODO PUT ALERT
			redirect('controller/contact');
		}
	}

// DELETE THIS MAYBE??
	public function scheduleTasks()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("scheduleTasks");
		}
	}

	public function projectGantt()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$id = $this->input->post("project_ID");

			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['ganttData'] = $this->model->getAllProjectTasksGroupByTaskID($id);
			$data['dependencies'] = $this->model->getDependencies();
			$data['users'] = $this->model->getAllUsers();
			$data['responsible'] = $this->model->getAllResponsibleByProject($id);
			$data['accountable'] = $this->model->getAllAccountableByProject($id);
			$data['consulted'] = $this->model->getAllConsultedByProject($id);
			$data['informed'] = $this->model->getAllInformedByProject($id);

			// foreach ($data['ganttData'] as $key => $value) {
			// 	echo $value['tasks_TASKID'] . " parent is ";
			// 	echo $value['tasks_TASKPARENT'] . "<br>";
			// }

			$this->load->view("projectGantt", $data);
			// $this->load->view("gantt2", $data);

		}
	}

	public function projectDocuments()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$id = $this->input->post("project_ID");
			$this->session->set_flashdata('projectID', $id);

			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['departments'] = $this->model->getAllDepartmentsByProject($id);
			$data['documentsByProject'] = $this->model->getAllDocumentsByProject($id);
			$data['documentAcknowledgement'] = $this->model->getDocumentAcknowledgement($_SESSION['USERID']);
			$data['users'] = $this->model->getAllUsersByProject($id);

			$this->load->view("projectDocuments", $data);
		}
	}

	public function addDependencies()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("addDependencies");
		}
	}

	public function rfc()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("rfc");
		}
	}

	/******************** MY PROJECTS START ********************/

	// ADDS MAIN ACTIVITIES TO PROJECT
	// TODO: Thes2 - fix deleting and adding of rows OR hide delete button if not last row
	public function addTasksToProject()
	{
		// GET PROJECT ID
		$id = $this->input->post("project_ID");

		// GET ARRAY OF INPUTS FROM VIEW
		$title = $this->input->post('title');
		$startDates = $this->input->post('taskStartDate');
		$endDates = $this->input->post('taskEndDate');
		$department = $this->input->post("department");
		$rowNum = $this->input->post('row');

		$addedTask = array();

		// GET ALL DEPTS TO ASSIGN DEPT HEAD TO TASK
		$departments = $this->model->getAllDepartments();

		foreach($departments as $row)
		{
			switch ($row['DEPARTMENTNAME'])
			{
				case 'Executive':
					$execHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Marketing':
					$mktHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Finance':
					$finHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Procurement':
					$proHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'HR':
					$hrHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'MIS':
					$misHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Store Operations':
					$opsHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Facilities Administration':
					$fadHead = $row['users_DEPARTMENTHEAD'];
					break;
			}
		}

		foreach ($title as $key=> $row)
		{
			$data = array(
          'TASKTITLE' => $row,
          'TASKSTARTDATE' => $startDates[$key],
          'TASKENDDATE' => $endDates[$key],
          'TASKSTATUS' => 'Planning',
          'CATEGORY' => '1',
          'projects_PROJECTID' => $id
      );

      $addedTask[] = $this->model->addTasksToProject($data);
		}

		// TESTING
		foreach ($addedTask as $aKey=> $a)
		{
				// echo " -- " . $a . " -- <br>";
			foreach ($rowNum as $rKey => $row)
			{
				// echo $aKey . " == " . $rKey . "<br>";
				// echo $row . "<br>";

				if ($aKey == $rKey)
				{
					foreach ($department as $dKey => $d)
					{
						if ($row == $dKey)
						{
							foreach ($d as $value)
							{
								switch ($value)
								{
									case 'Executive':
										$deptHead = $execHead;
										break;
									case 'Marketing':
										$deptHead = $mktHead;
										break;
									case 'Finance':
										$deptHead = $finHead;
										break;
									case 'Procurement':
										$deptHead = $proHead;
										break;
									case 'HR':
										$deptHead = $hrHead;
										break;
									case 'MIS':
										$deptHead = $misHead;
										break;
									case 'Store Operations':
										$deptHead = $opsHead;
										break;
									case 'Facilities Administration':
										$deptHead = $fadHead;
										break;
								}

								// echo $value . ", ";

								$data = array(
										'ROLE' => '1',
										'users_USERID' => $deptHead,
										'tasks_TASKID' => $a
								);

								// ENTER INTO RACI
								$result = $this->model->addToRaci($data);
							}
							// echo "<br>";
						}
					}
				}
			}
		}

			$data['project'] = $this->model->getProjectByID($id);
			$data['tasks'] = $this->model->getAllProjectTasks($id);
			$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
			$data['users'] = $this->model->getAllUsers();
			$data['departments'] = $this->model->getAllDepartments();

			$sDate = date_create($data['project']['PROJECTSTARTDATE']);
			$eDate = date_create($data['project']['PROJECTENDDATE']);
			$diff = date_diff($eDate, $sDate);
			$dateDiff = $diff->format('%d');

			$data['dateDiff'] = $dateDiff;

			// $this->output->enable_profile(TRUE);
			$this->load->view('arrangeTasks', $data);
		}

		// ADDS SUB ACTIVITIES TO MAIN ACTIVITIES OF PROJECT
		public function arrangeTasks()
		{
		  $id = $this->input->post('project_ID');

		  $parent = $this->input->post('mainActivity_ID');
		  $title = $this->input->post('title');
		  $startDates = $this->input->post('taskStartDate');
		  $endDates = $this->input->post('taskEndDate');
			$department = $this->input->post("department");
			$rowNum = $this->input->post('row');

			$addedTask = array();

		  $departments = $this->model->getAllDepartments();

		  foreach($departments as $row)
		  {
		    switch ($row['DEPARTMENTNAME'])
		    {
		      case 'Executive':
		        $execHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Marketing':
		        $mktHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Finance':
		        $finHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Procurement':
		        $proHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'HR':
		        $hrHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'MIS':
		        $misHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Store Operations':
		        $opsHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Facilities Administration':
		        $fadHead = $row['users_DEPARTMENTHEAD'];
		        break;
		    }
		  }

		  foreach ($title as $key=> $row)
		  {
	      $data = array(
	          'TASKTITLE' => $row,
	          'TASKSTARTDATE' => $startDates[$key],
	          'TASKENDDATE' => $endDates[$key],
	          'TASKSTATUS' => 'Planning',
	          'CATEGORY' => '2',
	          'projects_PROJECTID' => $id,
	          'tasks_TASKPARENT' => $parent[$key]
	      );

				// SAVES ALL ADDED TASKS INTO AN ARRAY
	      $addedTask[] = $this->model->addTasksToProject($data);
		   }

			// GETS DEPARTMENT ARRAY FOR RACI
			foreach ($addedTask as $aKey=> $a)
	 		{
	 			// echo " -- " . $a . " -- " . $parent[$aKey] . "<br>";
				// rowNum SAVES THE ORDER OF HOW THE DEPARTMENT ARRAY MUST LOOK LIKE
	 			foreach ($rowNum as $rKey => $row)
	 			{
					// echo $row . "<br>";
	 				// echo $aKey . " == " . $rKey . "<br>";
	 				if ($aKey == $rKey)
	 				{
						// echo $aKey . " == " . $rKey . "<br>";
	 					foreach ($department as $dKey => $d)
	 					{
							// echo $row . " == " . $dKey . "<br>";
	 						if ($row == $dKey)
	 						{
								// echo $row . " == " . $dKey . "<br>";
	 							foreach ($d as $value)
	 							{
	 								switch ($value)
	 								{
	 									case 'Executive':
	 										$deptHead = $execHead;
	 										break;
	 									case 'Marketing':
	 										$deptHead = $mktHead;
	 										break;
	 									case 'Finance':
	 										$deptHead = $finHead;
	 										break;
	 									case 'Procurement':
	 										$deptHead = $proHead;
	 										break;
	 									case 'HR':
	 										$deptHead = $hrHead;
	 										break;
	 									case 'MIS':
	 										$deptHead = $misHead;
	 										break;
	 									case 'Store Operations':
	 										$deptHead = $opsHead;
	 										break;
	 									case 'Facilities Administration':
	 										$deptHead = $fadHead;
	 										break;
	 								}

	 								// echo $value . ", ";

	 								$data = array(
	 										'ROLE' => '1',
	 										'users_USERID' => $deptHead,
	 										'tasks_TASKID' => $a
	 								);

	 								// ENTER INTO RACI
	 								$result = $this->model->addToRaci($data);
	 							}
	 							// echo "<br>";
	 						}
	 					}
	 				}
	 			}
	 		}

		  // $this->output->enable_profiler(TRUE);

		  // GANTT CODE
		  // $data['projectProfile'] = $this->model->getProjectByID($id);
		  // $data['ganttData'] = $this->model->getAllProjectTasks($id);
		  // // $data['preReq'] = $this->model->getPreReqID();
		  // $data['dependencies'] = $this->model->getDependencies();
		  // $data['users'] = $this->model->getAllUsers();

		  $data['project'] = $this->model->getProjectByID($id);
		  $data['tasks'] = $this->model->getAllProjectTasks($id);
		  $data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
			$data['mainActivity'] = $this->model->getAllMainActivitiesByID($id);
			$data['subActivity'] = $this->model->getAllSubActivitiesByID($id);
		  $data['users'] = $this->model->getAllUsers();
		  $data['departments'] = $this->model->getAllDepartments();

		  $sDate = date_create($data['project']['PROJECTSTARTDATE']);
		  $eDate = date_create($data['project']['PROJECTENDDATE']);
		  $diff = date_diff($eDate, $sDate);
		  $dateDiff = $diff->format('%d');

		  $data['dateDiff'] = $dateDiff;

		  // $this->load->view("dashboard", $data);
		  // redirect('controller/projectGantt');
		  $this->load->view("scheduleTasks", $data);
		}

	public function uploadDocument()
	{
		$config['upload_path']          = './assets/uploads';
		$config['allowed_types']        = '*';
		$config['max_size']							= '10000000';

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		// $option = $this->input->post('departments[]');

			foreach($this->input->post("departments[]") as $departments){
				echo "dept name - " . $departments . "<br>";
			}

		//
		// echo 'hello';
		// echo $option;

		// if (!$this->upload->do_upload('document'))
		// {
		// 	echo "<script>alert('did not upload');</script>";
		// }
		//
		// else
		// {
		// 	//GET PROJECT ID
		// 	$id = $this->input->post("project_ID");
		// 	$projectID = $this->model->getProjectByID($id);
		//
		// 	$user = $_SESSION['USERID'];
		// 	$fileName = $this->upload->data('file_name');
		// 	$src = "http://localhost/Kernel/assets/uploads/" . $fileName;
		//
		// 	$option = $this->input->post('sendTo');

			// if($option == 'All'){
			// 	$uploadData = array(
			// 		'DOCUMENTSTATUS' => 'Uploaded',
			// 		'DOCUMENTNAME' => $fileName,
			// 		'DOCUMENTLINK' => $src,
			// 		'users_UPLOADEDBY' => $user,
			// 		'UPLOADEDDATE' => date('Y-m-d'),
			// 		'projects_PROJECTID' => $id,
			// 		'REMARKS' => $this->input->post('remarks')
			// 	);
			// } else {
			// 	$uploadData = array(
			// 		'DOCUMENTSTATUS' => 'For Acknowledgement',
			// 		'DOCUMENTNAME' => $fileName,
			// 		'DOCUMENTLINK' => $src,
			// 		'users_UPLOADEDBY' => $user,
			// 		'UPLOADEDDATE' => date('Y-m-d'),
			// 		'projects_PROJECTID' => $id,
			// 		'REMARKS' => $this->input->post('remarks')
			// 	);
			// }

			// $documentID = $this->model->uploadDocument($uploadData);

			// if($option != 'All')
			// {
			// 	foreach($this->input->post("departments") as $departments){
			// 		foreach($this->model->getAllUserstsByProjectByDepartment($id, $departments) as $userID){
			// 			$acknowledgementData = array (
			// 				'documents_DOCUMENTID' => $documentID,
			// 				'users_ACKNOWLEDGEDBY' => $userID['users_USERID']
			// 			);
			//
			// 			$this->model->addToDocumentAcknowledgement($acknowledgementData);
			// 		}
			// 	}
			// }
			//
			// $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
			// $details = $userName . " uploaded " . $fileName;
			//
			// $logData = array (
			// 	'LOGDETAILS' => $details,
			// 	'TIMESTAMP' => date('Y-m-d H:i:s'),
			// 	'projects_PROJECTID' => $id
			// );
			// $this->model->addToProjectLogs($logData);
			//
			// $this->session->set_flashdata('projectID', $id);
			// $data['projectProfile'] = $this->model->getProjectByID($id);
			// $data['departments'] = $this->model->getAllDepartments();
			// $data['documentsByProject'] = $this->model->getAllDocumentsByProject($id);
			// $data['documentAcknowledgement'] = $this->model->getDocumentAcknowledgement($_SESSION['USERID']);
			//
			// $this->load->view("projectDocuments", $data);
		// }
	}

	/******************** MY PROJECTS END ********************/

	public function gantt2(){

		$filter = "tasks.TASKSTARTDATE"; // default
		$data['ganttData'] = $this->model->getAllProjectTasks(1, $filter);
		$data['dependencies'] = $this->model->getDependencies();

		$this->load->view("gantt2", $data);
	}

// DELETE THIS AFTER
	public function frame()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("frame");
		}
	}
}
