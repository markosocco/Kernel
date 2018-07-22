
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
			$data['toAcknowledgeDocuments'] = $this->model->getAllDocumentAcknowledgementByUser($_SESSION['USERID']);

			// RFC Approval Data
			$userID = $_SESSION['USERID'];
			$deptID = $_SESSION['departments_DEPARTMENTID'];
			if($_SESSION['usertype_USERTYPEID'] == '4') // if supervisor is logged in
				$filter = "(usertype_USERTYPEID = '5' && users_SUPERVISORS = '$userID') || $userID = projects.users_USERID";
			elseif($_SESSION['usertype_USERTYPEID'] == '3') // if head is logged in
				$filter = "(usertype_USERTYPEID = '4' && users.departments_DEPARTMENTID = '$deptID') || $userID = projects.users_USERID";
			elseif($_SESSION['usertype_USERTYPEID'] == '5') // if a PO is logged in
				$filter = "$userID = projects.users_USERID";

			$data['changeRequests'] = $this->model->getChangeRequestsbyUser($filter);

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

			$data['ongoingTeamProjectProgress'] = $this->model->getOngoingProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			$data['delayedTeamProjectProgress'] = $this->model->getDelayedProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			$data['parkedTeamProjectProgress'] = $this->model->getParkedProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);

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

				// $data['ongoingTeamProjects'] = $this->model->getAllOngoingProjectsByUser($_SESSION['USERID']);
				// $data['plannedTeamProjects'] = $this->model->getAllPlannedProjectsByUser($_SESSION['USERID']);
				// $data['delayedTeamProjects'] = $this->model->getAllDelayedProjectsByUser($_SESSION['USERID']);
				// $data['parkedTeamProjects'] = $this->model->getAllParkedProjectsByUser($_SESSION['USERID']);
				// $data['draftedTeamProjects'] = $this->model->getAllDraftedProjectsByUser($_SESSION['USERID']);
			}

			$data['ongoingProjectProgress'] = $this->model->getOngoingProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			$data['delayedProjectProgress'] = $this->model->getDelayedProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			$data['parkedProjectProgress'] = $this->model->getParkedProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);

			// $data['ongoingTeamProjectProgress'] = $this->model->getOngoingProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			// $data['delayedTeamProjectProgress'] = $this->model->getDelayedProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);
			// $data['parkedTeamProjectProgress'] = $this->model->getParkedProjectProgressByTeam($_SESSION['departments_DEPARTMENTID']);

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
			$data['wholeDept'] = $this->model->getAllUsersByDepartment($_SESSION['departments_DEPARTMENTID']);
			$data['projectCount'] = $this->model->getProjectCount($filter);
			$data['taskCount'] = $this->model->getTaskCount($filter);

			$data['users'] = $this->model->getAllUsers();
			$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
			$data['ACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID']);
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

			// Check and Complete Main and Sub Activities
			$parentID = $this->model->getParentTask($id);
			$completeTasks = $this->model->checkTasksStatus($parentID['tasks_TASKPARENT']);
			if($completeTasks == 0)
			{
				$subData = array(
							'TASKSTATUS' => 'Complete',
							'TASKACTUALENDDATE' => date('Y-m-d')
				);
				$this->model->updateTaskDone($parentID['tasks_TASKPARENT'], $subData); // Complete Sub Activity

				$mainID = $this->model->getParentTask($parentID['tasks_TASKPARENT']);
				$completeSubs = $this->model->checkTasksStatus($mainID['tasks_TASKPARENT']);
				if($completeSubs == 0)
				{
					$mainData = array(
								'TASKSTATUS' => 'Complete',
								'TASKACTUALENDDATE' => date('Y-m-d')
					);
					$this->model->updateTaskDone($mainID['tasks_TASKPARENT'], $mainData); // Complete Main Activity

					// Check and Complete a Project
					$completeProject = $this->model->checkProjectStatus($mainID['projects_PROJECTID']);
					if($completeProject == 0)
					{
						$projectData = array(
									'PROJECTSTATUS' => 'Complete',
									'PROJECTACTUALENDDATE' => date('Y-m-d')
						);
						$this->model->completeProject($mainID['projects_PROJECTID'], $projectData); // Complete Project
					}
				}
			}
		}
		$this->myTasks();
	}

	public function loadTasks()
	{
		$data['users'] = $this->model->getAllUsers();
		$data['departments'] = $this->model->getAllDepartments();
		$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
		$data['ACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID']);
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

		$updateR = $this->model->updateRACI($taskID, '1'); // change status to 'changed'
		$updateA = $this->model->updateRACI($taskID, '2'); // change status to 'changed'
		$updateC = $this->model->updateRACI($taskID, '3'); // change status to 'changed'
		$updateI = $this->model->updateRACI($taskID, '4'); // change status to 'changed'

		// SAVE RESPONSIBLE
		if($this->input->post("responsibleEmp"))
		{
			$responsibleEmp = $this->input->post('responsibleEmp');
			$responsibleData = array(
				'ROLE' => '1',
				'users_USERID' => $responsibleEmp,
				'tasks_TASKID' => $taskID,
				'STATUS' => 'Current'
			);
			$result = $this->model->addToRaci($responsibleData);
		}

		// SAVE ACCOUNTABLE
		if($this->input->post("accountableDept[]"))
		{
			foreach($this->input->post("accountableDept[]") as $deptID)
			{
				$accountableData = array(
					'ROLE' => '2',
					'users_USERID' => $deptID,
					'tasks_TASKID' =>	$taskID,
					'STATUS' => 'Current'
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
					'tasks_TASKID' =>	$taskID,
					'STATUS' => 'Current'
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
					'tasks_TASKID' =>	$taskID,
					'STATUS' => 'Current'
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
					'tasks_TASKID' =>	$taskID,
					'STATUS' => 'Current'
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
					'tasks_TASKID' =>	$taskID,
					'STATUS' => 'Current'
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
					'tasks_TASKID' =>	$taskID,
					'STATUS' => 'Current'
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

	public function approveDenyRFC()
	{
		$requestID = $this->input->post('request_ID');
		$requestType = $this->input->post('request_type');
		$projectID = $this->input->post('project_ID');
		$remarks = $this->input->post('remarks');
		$status = $this->input->post('status');

		$data = array(
			'REQUESTSTATUS' => $status,
			'REMARKS' => $remarks,
			'users_APPROVEDBY' => $_SESSION['USERID'],
			'APPROVEDDATE' => date('Y-m-d')
		);

		$this->model->updateRFC($requestID, $data);

		if($remarks == 'Approved' && $requestType == '1') // if approved change performer
		{
			$taskID = $this->input->post("task_ID");

			$updateR = $this->model->updateRACI($taskID, '1'); // change status to 'changed'
			$updateA = $this->model->updateRACI($taskID, '2'); // change status to 'changed'
			$updateC = $this->model->updateRACI($taskID, '3'); // change status to 'changed'
			$updateI = $this->model->updateRACI($taskID, '4'); // change status to 'changed'

			// SAVE/UPDATE RESPONSIBLE
			if($this->input->post("responsibleEmp"))
			{
				$responsibleEmp = $this->input->post('responsibleEmp');
				$responsibleData = array(
					'ROLE' => '1',
					'users_USERID' => $responsibleEmp,
					'tasks_TASKID' => $taskID,
					'STATUS' => 'Current'
				);
				$result = $this->model->addToRaci($responsibleData);
			}

			// SAVE ACCOUNTABLE
			if($this->input->post("accountableDept[]"))
			{
				foreach($this->input->post("accountableDept[]") as $deptID)
				{
					$accountableData = array(
						'ROLE' => '2',
						'users_USERID' => $deptID,
						'tasks_TASKID' =>	$taskID,
						'STATUS' => 'Current'
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
						'tasks_TASKID' =>	$taskID,
						'STATUS' => 'Current'
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
						'tasks_TASKID' =>	$taskID,
						'STATUS' => 'Current'
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
						'tasks_TASKID' =>	$taskID,
						'STATUS' => 'Current'
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
						'tasks_TASKID' =>	$taskID,
						'STATUS' => 'Current'
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
						'tasks_TASKID' =>	$taskID,
						'STATUS' => 'Current'
					);
					$this->model->addToRaci($informedData);
				}
			}

			if(!$this->input->post("responsibleEmp") && !$this->input->post("accountableDept[]") &&
					!$this->input->post("accountableEmp[]") && !$this->input->post("consultedDept[]") &&
					!$this->input->post("consultedEmp[]") && !$this->input->post("informedDept[]") &&
					!$this->input->post("informedEmp[]")) // return to approver in tasks
			{
				$responsibleData = array(
					'ROLE' => '1',
					'users_USERID' => $_SESSION['USERID'],
					'tasks_TASKID' =>	$taskID,
					'STATUS' => 'Current'
				);
				$result = $this->model->addToRaci($responsibleData);
			}

		} // end if appoved change Performer
		// else // if approved change dates
		// {
		//		$changeRequest = $this->model->getChangeRequestbyID($requestID);
		// }

		$data['projectProfile'] = $this->model->getProjectByID($projectID);
		$data['ganttData'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);
		$data['dependencies'] = $this->model->getDependencies();
		$data['users'] = $this->model->getAllUsers();
		$data['responsible'] = $this->model->getAllResponsibleByProject($projectID);
		$data['accountable'] = $this->model->getAllAccountableByProject($projectID);
		$data['consulted'] = $this->model->getAllConsultedByProject($projectID);
		$data['informed'] = $this->model->getAllInformedByProject($projectID);
		$data['SAprogress'] = $this->model->getSubActivityProgress($projectID);

		unset($_SESSION['rfc']);
		$this->session->set_flashdata('changeRequest', 0);

		$this->load->view("projectGantt", $data);
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
			$data['projectID'] = $id;
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
		$id = $this->input->post('project_ID');

		$parent = $this->input->post('subActivity_ID');
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
					'CATEGORY' => '3',
					'projects_PROJECTID' => $id,
					'tasks_TASKPARENT' => $parent[$key]
			);

			// SAVES ALL ADDED TASKS INTO AN ARRAY
			$addedTask[] = $this->model->addTasksToProject($data);
		 }

		// GETS DEPARTMENT ARRAY FOR RACI
		foreach ($addedTask as $aKey=> $a)
		{
			// echo " -- " . $a . " -- " . "<br>";
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
										'tasks_TASKID' => $a,
										'STATUS' => 'Current'
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
		$data['allTasks'] = $this->model->getAllProjectTasks($id);
		$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
		$data['mainActivity'] = $this->model->getAllMainActivitiesByID($id);
		$data['subActivity'] = $this->model->getAllSubActivitiesByID($id);
		$data['tasks'] = $this->model->getAllTasksByID($id);
		$data['users'] = $this->model->getAllUsers();
		$data['departments'] = $this->model->getAllDepartments();

		$sDate = date_create($data['project']['PROJECTSTARTDATE']);
		$eDate = date_create($data['project']['PROJECTENDDATE']);
		$diff = date_diff($eDate, $sDate);
		$dateDiff = $diff->format('%d');

		$data['dateDiff'] = $dateDiff;

		// $this->load->view("dashboard", $data);
		// redirect('controller/projectGantt');
		$this->load->view("addDependencies", $data);
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
			$archives =$this->input->post("archives");
			$rfc =$this->input->post("rfc");
			$mytasks =$this->input->post("mytasks");


			// ARCHIVES
			if (isset($archives))
			{
				$archives = $this->input->post("archives");
				$this->session->set_flashdata('archives', $archives);
			}

			// RFC
			elseif (isset($rfc))
			{
				$rfc = $this->input->post("rfc");
				$requestID = $this->input->post("request_ID");
				$this->session->set_flashdata('rfc', $rfc);

				$data['changeRequest'] = $this->model->getChangeRequestbyID($requestID);
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
				$data['wholeDept'] = $this->model->getAllUsersByDepartment($_SESSION['departments_DEPARTMENTID']);
				$data['projectCount'] = $this->model->getProjectCount($filter);
				$data['taskCount'] = $this->model->getTaskCount($filter);
			}
			elseif (isset($mytasks))
			{
				$mytasks = $this->input->post("mytasks");
				$this->session->set_flashdata('mytasks', $mytasks);
			}

			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['ganttData'] = $this->model->getAllProjectTasksGroupByTaskID($id);
			$data['dependencies'] = $this->model->getDependencies();
			$data['users'] = $this->model->getAllUsers();
			$data['responsible'] = $this->model->getAllResponsibleByProject($id);
			$data['accountable'] = $this->model->getAllAccountableByProject($id);
			$data['consulted'] = $this->model->getAllConsultedByProject($id);
			$data['informed'] = $this->model->getAllInformedByProject($id);
			$data['SAprogress'] = $this->model->getSubActivityProgress($id);

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
		$id = $this->input->post('project_ID');
		$taskID = $this->input->post('taskID');
		$dependencies = $this->input->post('dependencies');

		$allTasks = $this->model->getAllProjectTasksGroupByTaskID($id);

		foreach ($allTasks as $key => $value)
		{
			if ($value['CATEGORY'] == '3')
			{
				// echo " -- " . $value['TASKID'] . " -- <br>";
				foreach ($taskID as $tKey => $tValue)
				{
					if ($value['TASKID'] == $tValue)
					{
						// echo $value['TASKID'] . " == " . $tValue . "<br>";

						foreach ($dependencies as $dKey => $dValue)
						{
							if ($tKey == $dKey)
							{
								// echo $tKey . " == " . $dKey . "<br>";
								foreach ($dValue as $d)
								{
									$data = array(
										'PRETASKID' => $d,
										'tasks_POSTTASKID' => $value['TASKID']
									);

									// echo $d . ", ";

									// ENTER DEPENDENCIES TO DB
									$result = $this->model->addToDependencies($data);
								}
							}
						}
						// echo "<br>";
					}
				}
			}
		}

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

	public function rfc()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$userID = $_SESSION['USERID'];
			$deptID = $_SESSION['departments_DEPARTMENTID'];
			switch($_SESSION['usertype_USERTYPEID'])
			{
				case '4': // if supervisor is logged in
					$filter = "(usertype_USERTYPEID = '5' && users_SUPERVISORS = '$userID' && REQUESTSTATUS = 'Pending')
						|| (projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending')"; break;
				case '3': // if head is logged in
					$filter = "(usertype_USERTYPEID = '4' && users.departments_DEPARTMENTID = '$deptID' && REQUESTSTATUS = 'Pending')
					|| (projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending')"; break;
				case '5': // if PO is logged in
					$filter = "projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending'"; break;
			}

			$data['changeRequests'] = $this->model->getChangeRequestsbyUser($filter);
			$this->load->view("rfc", $data);
		}
	}

	/******************** MY PROJECTS START ********************/

	// ADDS MAIN ACTIVITIES TO PROJECT
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
										'tasks_TASKID' => $a,
										'STATUS' => 'Current'
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
	 										'tasks_TASKID' => $a,
											'STATUS' => 'Current'
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

		public function archiveProject()
		{
			$id = $this->input->post("project_ID");

			$data = array(
				'PROJECTSTATUS' => 'Archived'
			);

			// TODO NAMI: LOGS
			$result = $this->model->archiveProject($id, $data);

			if ($result)
			{
				$data['archives'] = $this->model->getAllProjectArchives();

				$this->load->view("archives", $data);
			}
		}

		public function templateProject()
		{
			$id = $this->input->post("project_ID");

			$project = $this->model->getProjectByID($id);

			$data = array(
				'PROJECTID' => 'Archived',
				'PROJECTTITLE' => 'Archived',
				'PROJECTSTARTDATE' => 'Archived',
				'PROJECTENDDATE' => 'Archived',
				'PROJECTDESCRIPTION' => 'Archived',
				'PROJECTSTATUS' => 'Archived',
				'users_USERID' => 'Archived'
			);

			// TODO NAMI: LOGS
			// $result = $this->model->archiveProject($id, $data);

			if ($result)
			{
				$data['archives'] = $this->model->getAllProjectArchives();

				$this->load->view("templates", $data);
			}
		}

	public function uploadDocument()
	{
		$config['upload_path']          = './assets/uploads';
		$config['allowed_types']        = '*';
		$config['max_size']							= '10000000';

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		//GET PROJECT ID
		$id = $this->input->post("project_ID");
		$projectID = $this->model->getProjectByID($id);

		// PLACEHOLDER
		$documentID = "";
		$deptID = "";

		// GET ALL DEPARTMENTS THAT WERE SELECTED
		$departmentIDs = $this->input->post("departments");

		// UPLOAD: FAILED
		if(!$this->upload->do_upload('document'))
		{
			echo "<script>alert('did not upload');</script>";
		}

		else
		{ // START: UPLOAD - SUCCESSFUL

			$user = $_SESSION['USERID'];
			$fileName = $this->upload->data('file_name');
			$src = "http://localhost/Kernel/assets/uploads/" . $fileName;

			foreach ($departmentIDs as $key => $value) {
				$value;
			}

			// NEEDS ACKNOWLEDGMENT
			if($value != 'all'){
				$uploadData = array(
					'DOCUMENTSTATUS' => 'For Acknowledgement',
					'DOCUMENTNAME' => $fileName,
					'DOCUMENTLINK' => $src,
					'users_UPLOADEDBY' => $user,
					'UPLOADEDDATE' => date('Y-m-d'),
					'projects_PROJECTID' => $id,
					'REMARKS' => $this->input->post('remarks')
				);

				// INSERT IN DOCUMENTS TABLE, RETURNS DOCUMENTID OF INSERTED DATA
				$documentID = $this->model->uploadDocument($uploadData);

				// START: FOREACH OF $departmentIDs
				foreach($departmentIDs as $departmentRow){

					// START: FOREACH - GETS ALL USERS OF A DEPARTMENT
					foreach ($this->model->getAllUserstsByProjectByDepartment($id, $departmentRow) as $userIDByDepartment) {
						$acknowledgementData = array (
							'documents_DOCUMENTID' => $documentID,
							'users_ACKNOWLEDGEDBY' => $userIDByDepartment['users_USERID']
						);

						// INSERT IN DOCUMENT ACKNOWLEDGMENT TABLE
						$this->model->addToDocumentAcknowledgement($acknowledgementData);

						// // START: Notification
						// $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
						// $projectTitle = $projectID['PROJECTTITLE'];
						// $details = $userName . " has uploaded " . $fileName . " to the project " .  $projectTitle . " and needs your acknowledgement.";
						//
						// $notificationData = array(
						// 	'users_USERID' => $userIDByDepartment['users_USERID'],
						// 	'DETAILS' => $details,
						// 	'TIMESTAMP' => date('Y-m-d'),
						// 	'status' => 'Unread'
						// );
						//
						// $this->model->addNotification($notificationData);
						// // END: Notification

					} // END: FOREACH - GETS ALL USERS OF A DEPARTMENT

				} // END: FOREACH OF $departmentIDs

			} else { // START: DOESN'T NEED ACKNOWLEDGMENT
				$uploadData = array(
					'DOCUMENTSTATUS' => 'Uploaded',
					'DOCUMENTNAME' => $fileName,
					'DOCUMENTLINK' => $src,
					'users_UPLOADEDBY' => $user,
					'UPLOADEDDATE' => date('Y-m-d'),
					'projects_PROJECTID' => $id,
					'REMARKS' => $this->input->post('remarks')
				);

				$this->model->uploadDocument($uploadData);

				// // START: Notification
				// $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
				// $details = $userName . " has uploaded " . $fileName . " and needs your acknowledgement.";
				// $notificationData = array(
				// 	'users_USERID' => $userIDByDepartment['users_USERID'],
				// 	'DETAILS' => $details,
				// 	'TIMESTAMP' => date('Y-m-d'),
				// 	'status' => 'Unread'
				// );
				//
				// $this->model->addNotification($notificationData);
				// // END: Notification

			} // END: DOESN'T NEED ACKNOWLEDGMENT

			// START: GET ALL USERS THAT WERE SELECTED
			if($this->input->post("users") != NULL){
				foreach($this->input->post("users") as $userID) {

					// CHECKS DOCUMENT ACKNOWLEDGMENT TABLE FOR DUPLICATION
					$documentAcknowledgement = $this->model->getDocumentAcknowledgementID($userID, $documentID);
					// NOT YET IN DOCUMENT ACKNOWLEDGMENT TABLE
					if(!$documentAcknowledgement['DOCUMENTACKNOWLEDGEMENTID']){

						$acknowledgementData = array (
							'documents_DOCUMENTID' => $documentID,
							'users_ACKNOWLEDGEDBY' => $userID
						);

						// INSERT IN DOCUMENT ACKNOWLEDGMENT TABLE
						$this->model->addToDocumentAcknowledgement($acknowledgementData);
					}
					// END: NOT YET IN DOCUMENT ACKNOWLEDGMENT TABLE
				}
				// END: GET ALL USERS THAT WERE SELECTED
			}

			// START: LOG DETAILS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
			$details = $userName . " uploaded " . $fileName;

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $id
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS
		}

		$this->session->set_flashdata('projectID', $id);
		$data['projectProfile'] = $this->model->getProjectByID($id);
		$data['departments'] = $this->model->getAllDepartments();
		$data['documentsByProject'] = $this->model->getAllDocumentsByProject($id);
		$data['documentAcknowledgement'] = $this->model->getDocumentAcknowledgement($_SESSION['USERID']);

		$this->load->view("projectDocuments", $data);
	}

	public function acknowledgeDocument()
	{
		//GET DOCUMENT ID
		$documentID = $this->input->post("documentID");
		$projectID = $this->input->post("project_ID");

		$currentDate = date('Y-m-d');

		$result = $this->model->updateDocumentAcknowledgement($documentID, $_SESSION['USERID'], $currentDate);

		// // START: LOG DETAILS
		// $userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
		// $details = $userName . " has acknowledged " . $fileName;
		//
		// $logData = array (
		// 	'LOGDETAILS' => $details,
		// 	'TIMESTAMP' => date('Y-m-d H:i:s'),
		// 	'projects_PROJECTID' => $projectID
		// );
		//
		// $this->model->addToProjectLogs($logData);
		// // END: LOG DETAILS

		if ($result)
		{
			$this->session->set_flashdata('projectID', $id);

			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['departments'] = $this->model->getAllDepartmentsByProject($id);
			$data['documentsByProject'] = $this->model->getAllDocumentsByProject($id);
			$data['documentAcknowledgement'] = $this->model->getDocumentAcknowledgement($_SESSION['USERID']);
			$data['users'] = $this->model->getAllUsersByProject($id);

			$this->load->view("projectDocuments", $data);

			// redirect('controller/projectDocuments');
		}

		else {
			echo "didnt work";
		}

		// $documentID = $this->model->getProjectByID($id);
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
