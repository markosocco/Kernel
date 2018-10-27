<?php

date_default_timezone_set('Asia/Manila');
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
		$this->load->library('email');

	}

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function email(){
		$this->load->helper('form');
    $this->load->view('email_form');
	}

	public function send_mail() {
		$from_email = "kernelPMS@gmail.com";
		$to_email = $this->input->post('email');

		 //Load email library
	 	$this->load->library('email');

		$this->email->from($from_email, 'Your Name');
		$this->email->to($to_email);
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');

		 //Send mail
		if($this->email->send())
			$this->session->set_flashdata("email_sent","Email sent successfully.");
		else
			$this->session->set_flashdata("email_sent","Error in sending Email.");

		 $this->load->view('email_form');
  }

	public function login()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('login');
		}

		else
		{
			$this->load->view('restrictedAccess');
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

				$notifications = $this->model->getAllNotificationsByUser();
				$this->session->set_userdata('notifications', $notifications);

				$tasks = $this->model->getAllTasksByUser($_SESSION['USERID']);

				$count = 0;
				foreach ($tasks as $taskCount){
					$count++;
				}

				$this->session->set_userdata('taskCount', $count);

				$currentDate = date('Y-m-d');
				$this->model->updateTaskStatus($currentDate);
				$this->model->updateProjectStatus($currentDate);

				$allTasks = $this->model->getAllTasksByUser($_SESSION['USERID']);

				$this->session->set_userdata('tasks', $allTasks);


				$taskDeadlines = $this->model->getTasks2DaysBeforeDeadline();

				// echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'] . "<br>";

				if($taskDeadlines != NULL){

					foreach ($taskDeadlines as $taskWithDeadline) {

						$projectDetails = $this->model->getProjectByID($taskWithDeadline['projects_PROJECTID']);
						$projectTitle = $projectDetails['PROJECTTITLE'];

						if($taskWithDeadline['DATEDIFF'] == 2)
							$details = "Deadline for " . $taskWithDeadline['TASKTITLE'] . " in " . $projectTitle . " is in 2 days.";
						else if($taskWithDeadline['DATEDIFF'] == 1)
							$details = "Deadline for " . $taskWithDeadline['TASKTITLE'] . " in " . $projectTitle . " is tomorrow";
						else if($taskWithDeadline['DATEDIFF'] == 0)
							$details = "Deadline for " . $taskWithDeadline['TASKTITLE']. " in " . $projectTitle . " is today.";
						else
							$details = $taskWithDeadline['TASKTITLE'] .  " in " . $projectTitle . " is already delayed. Please accomplish immediately.";

						// for task owner
						$isFound = $this->model->checkNotification($currentDate, $details, $_SESSION['USERID']);
						if(!$isFound){
							// START: Notifications
							$notificationData = array(
								'users_USERID' => $taskWithDeadline['TASKOWNER'],
								'DETAILS' => $details,
								'TIMESTAMP' => date('Y-m-d H:i:s'),
								'status' => 'Unread',
								'tasks_TASKID' => $taskWithDeadline['TASKID'],
								'projects_PROJECTID' => $taskWithDeadline['projects_PROJECTID'],
								'TYPE' => '3'
							);

							$this->model->addNotification($notificationData);
							// END: Notification
						}

						if($taskWithDeadline['DATEDIFF'] < 0){

							$details = $taskWithDeadline['TASKTITLE'] . " in " . $projectTitle .  " is delayed.";

							// for project owner
							$isFound = $this->model->checkNotification($currentDate, $details, $taskWithDeadline['PROJECTOWNER']);
							if(!$isFound){
								// START: Notifications
								$notificationData = array(
									'users_USERID' => $taskWithDeadline['PROJECTOWNER'],
									'DETAILS' => $details,
									'TIMESTAMP' => date('Y-m-d H:i:s'),
									'status' => 'Unread',
									'tasks_TASKID' => $taskWithDeadline['TASKID'],
									'projects_PROJECTID' => $taskWithDeadline['projects_PROJECTID'],
									'TYPE' => '1'
								);

								$this->model->addNotification($notificationData);
								// END: Notification
							}

							// for ACI
							$data['ACI'] = $this->model->getACIbyTask($taskWithDeadline['TASKID']);
							if($data['ACI'] != NULL) {
								foreach($data['ACI'] as $ACIusers){
									$isFound = $this->model->checkNotification($currentDate, $details, $ACIusers['users_USERID']);

									if(!$isFound){
										// START: Notifications
										$notificationData = array(
											'users_USERID' => $ACIusers['users_USERID'],
											'DETAILS' => $details,
											'TIMESTAMP' => date('Y-m-d H:i:s'),
											'status' => 'Unread',
											'tasks_TASKID' => $taskWithDeadline['TASKID'],
											'projects_PROJECTID' => $taskWithDeadline['projects_PROJECTID'],
											'TYPE' => '4'
										);
										$this->model->addNotification($notificationData);
									}
								}
							}

							// for next task person
							$postTasksData['nextTaskID'] = $this->model->getPostDependenciesByTaskID($taskWithDeadline['TASKID']);
							if($postTasksData['nextTaskID'] != NULL){

								foreach($postTasksData['nextTaskID'] as $nextTaskDetails) {

									$nextTaskID = $nextTaskDetails['tasks_POSTTASKID'];
									$postTasksData['users'] = $this->model->getRACIbyTask($nextTaskID);
									$nextTaskTitle = $nextTaskDetails['TASKTITLE'];

									foreach($postTasksData['users'] as $postTasksDataUsers){
										$details = "Pre-requisite task of " . $nextTaskTitle . " in " . $projectTitle . " is delayed.";

										$isFound = $this->model->checkNotification($currentDate, $details, $postTasksDataUsers['users_USERID']);

										if(!$isFound){
											// START: Notifications
											$notificationData = array(
												'users_USERID' => $postTasksDataUsers['users_USERID'],
												'DETAILS' => $details,
												'TIMESTAMP' => date('Y-m-d H:i:s'),
												'status' => 'Unread',
												'tasks_TASKID' => $taskWithDeadline['TASKID'],
												'projects_PROJECTID' => $taskWithDeadline['projects_PROJECTID'],
												'TYPE' => '4'
											);
											$this->model->addNotification($notificationData);
										}
									}
								}
							}
						}
					}
				}

				// // check for project weekly progress
				// $data['latestProgress'] = $this->model->getLatestWeeklyProgress();
				//
				// foreach($data['latestProgress'] as $latestProgressDetails){
				//
				// 	// echo "<br> latest progress" . $latestProgressDetails['projects_PROJECTID'] . " " . $latestProgressDetails['datediff'] ."<br>";
				//
				// 	$isFound = $this->model->checkAssessmentProject($latestProgressDetails['projects_PROJECTID']);
				//
				// 	if(!$isFound){
				// 		$completeness = $this->model->compute_completeness_project($latestProgressDetails['projects_PROJECTID']);
				// 		$timeliness = $this->model->compute_timeliness_project($latestProgressDetails['projects_PROJECTID']);
				//
				// 		$progressData = array(
				// 			'projects_PROJECTID' => $latestProgressDetails['projects_PROJECTID'],
				// 			'DATE' => date('Y-m-d'),
				// 			'COMPLETENESS' => $completeness['completeness'],
				// 			'TIMELINESS' => $timeliness['timeliness']
				// 		);
				// 		$this->model->addAssessmentProject($progressData);
				// 	}
				// }

				// // check for department assessment
				// $data['latestAssessmentDepartment'] = $this->model->getLatestAssessmentDepartment();
				//
				// foreach($data['latestAssessmentDepartment'] as $latestAssessment){
				//
				// 	$isFound = $this->model->checkAssessmentDepartment($latestAssessment['departments_DEPARTMENTID']);
				//
				// 	if(!$isFound){
				//
				// 		$completeness = $this->model->compute_completeness_department($latestAssessment['departments_DEPARTMENTID']);
				// 		$timeliness = $this->model->compute_timeliness_department($latestAssessment['departments_DEPARTMENTID']);
				//
				// 		$progressData = array(
				// 			'departments_DEPARTMENTID' => $latestAssessment['departments_DEPARTMENTID'],
				// 			'DATE' => date('Y-m-d'),
				// 			'COMPLETENESS' => $completeness['completeness'],
				// 			'TIMELINESS' => $timeliness['timeliness']
				// 		);
				// 		$this->model->addAssessmentDepartment($progressData);
				// 	}
				// }

				// // check for employee assessment
				// $data['latestAssessmentEmployee'] = $this->model->getLatestAssessmentEmployee();
				//
				// foreach($data['latestAssessmentDepartment'] as $latestAssessment){
				//
				// 	$isFound = $this->model->checkAssessmentDepartment($latestAssessment['users_USERID']);
				//
				// 	if(!$isFound){
				//
				// 		$completeness = $this->model->compute_completeness_employee($latestAssessment['users_USERID']);
				// 		$timeliness = $this->model->compute_timeliness_employee($latestAssessment['users_USERID']);
				//
				// 		$progressData = array(
				// 			'departments_DEPARTMENTID' => $latestAssessment['users_USERID'],
				// 			'DATE' => date('Y-m-d'),
				// 			'COMPLETENESS' => $completeness['completeness'],
				// 			'TIMELINESS' => $timeliness['timeliness']
				// 		);
				// 		$this->model->addAssessmentDepartment($progressData);
				// 	}
				// }


				if ($_SESSION['USERID'] == 1)
				{
					redirect('controller/dashboardAdmin');
				}

				else
				{
					redirect('controller/dashboard');
				}
			}

			else
			{
				$email = $this->input->post('email');
				$this->session->set_flashdata('stickyemail', $email);

				// ALERTS
				$this->session->set_flashdata('danger', 'alert');
				$this->session->set_flashdata('alertMessage', 'Email and password do not match');

				redirect('controller/login');
			}
		}
	}

	public function restrictedAccess()
	{
		$this->load->view('restrictedAccess');
	}

	public function contact()
	{
		$this->load->view('contact');
	}

	public function dashboard()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
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
			$data['tasks2DaysBeforeDeadline'] = $this->model->getTasks2DaysBeforeDeadline();
			$data['toAcknowledgeDocuments'] = $this->model->getAllDocumentAcknowledgementByUser($_SESSION['USERID']);

			// RFC Approval Data
			$userID = $_SESSION['USERID'];
			$deptID = $_SESSION['departments_DEPARTMENTID'];
			switch($_SESSION['usertype_USERTYPEID'])
			{
				case '4': // if supervisor is logged in
					$filter = "(usertype_USERTYPEID = '5' && users_SUPERVISORS = '$userID' && REQUESTSTATUS = 'Pending')
						|| (projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending')"; break;
				case '3': // if head is logged in
					$filter = "((usertype_USERTYPEID = '4' || usertype_USERTYPEID = '5')&& users.departments_DEPARTMENTID = '$deptID' && REQUESTSTATUS = 'Pending')
					|| (projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending')"; break;
				case '5': // if PO is logged in
					$filter = "projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending'"; break;
				default:
					$filter = "usertype_USERTYPEID = '3' && REQUESTSTATUS = 'Pending'"; break;
			}


			$data['changeRequests'] = $this->model->getChangeRequestsForApproval($filter, $_SESSION['USERID']);
			$data['userRequests'] = $this->model->getChangeRequestsByUser($_SESSION['USERID']);
			$data['delegateTasks'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID']);
			$data['lastWeekProgress'] = $this->model->getLatestWeeklyProgress();
			$data['employeeCompleteness'] = $this->model->compute_completeness_employee($_SESSION['USERID']);
			$data['departmentCompleteness'] = $this->model->compute_completeness_department($_SESSION['departments_DEPARTMENTID']);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employee($_SESSION['USERID']);
			$data['departmentTimeliness'] = $this->model->compute_timeliness_department($_SESSION['departments_DEPARTMENTID']);

			$this->load->view("dashboard", $data);
		}
	}

	public function myProjects()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
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

			$data['templates'] = $this->model->getAllTemplates();
			$this->load->view("myProjects", $data);
		}
	}

	public function monitorTeam()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$deptID = $_SESSION['DEPARTMENTID'];

			$data['performance'] = array();
			$data['tCountStaff'] = array();
			$data['pCountStaff'] = array();

			if ($_SESSION['usertype_USERTYPEID'] == 3)
			{
				// echo "head";
				$data['staff'] = $this->model->getAllUsersByDepartment($deptID);
			}

			elseif ($_SESSION['usertype_USERTYPEID'] == 4)
			{
				// echo "sup";
				$data['staff'] = $this->model->getAllUsersBySupervisor($_SESSION['USERID']);
			}

			$data['projects'] = $this->model->getAllProjects();
			$data['projectCount'] = $this->model->getProjectCount();
			$data['taskCount'] = $this->model->getTaskCount();

			foreach ($data['staff'] as $row)
			{
				$data['performance'][] = $this->model->compute_timeliness_employee($row['USERID']);
			}

			// SAVES USER IDS WITH TASKS INTO ARRAY
			foreach ($data['taskCount'] as $row2)
			{
				$data['tCountStaff'][] = $row2['USERID'];
			}

			// CHECKS IF STAFF HAS TASK, SAVES INTO ARRAY
			foreach ($data['staff'] as $s)
			{
				if (in_array($s['USERID'], $data['tCountStaff']))
				{
					$data['tCountStaff'][] = $s['USERID'];
 				}
			}

			// SAVES USER IDS WITH PROJECTS INTO ARRAY
			foreach ($data['projectCount'] as $row2)
			{
				$data['pCountStaff'][] = $row2['USERID'];
			}

			// CHECKS IF STAFF HAS PROJECTS, SAVES INTO ARRAY
			foreach ($data['staff'] as $s)
			{
				if (in_array($s['USERID'], $data['pCountStaff']))
				{
					$data['pCountStaff'][] = $s['USERID'];
 				}
			}

			$this->load->view("monitorTeam", $data);
		}
	}

	public function monitorMembers()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$id = $this->input->post('employee_ID');

			$data['pCount'] = array();
			$data['tCount'] = array();

			$projectCount = $this->model->getProjectCount();
			$taskCount = $this->model->getTaskCount();

			// SET PROJECT COUNT FOR EMPLOYEE
			foreach ($projectCount as $p)
			{
				if ($p['USERID'] == $id)
				{
					$data['pCount'][] = $p;
				}
			}

			// SET TASK COUNT FOR EMPLOYEE
			foreach ($taskCount as $t)
			{
				if ($t['USERID'] == $id)
				{
					$data['tCount'][] = $t;
				}
			}

			$data['user'] = $this->model->getUserByID($id);
			$data['projects'] = $this->model->getAllProjectsByUser($id);
			$data['tasks'] = $this->model->getAllTasksForAllOngoingProjects($id);
			$data['timeliness'] = $this->model->compute_timeliness_employee($id);
			$data['completeness'] = $this->model->compute_completeness_employee($id);
			$data['raci'] = $this->model->getAllACI();

			$this->load->view("monitorMembers", $data);
		}
	}

	public function monitorDepartment()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$projectID = $this->input->post('project_ID');
			$data['projectProfile'] = $this->model->getProjectByID($projectID);
			$data['projectCompleteness'] = $this->model->compute_completeness_project($projectID);
			$data['projectTimeliness'] = $this->model->compute_timeliness_project($projectID);
			$data['allDepartments'] = $this->model->getAllDepartmentsByProject($projectID);
			$data['departments'] = $this->model->compute_timeliness_departmentByProject($projectID);
			$data['tasks'] = $this->model->getAllTasksByProject($projectID);

			// foreach($data['departments'] as $d)
			// {
			// 	echo " == " . $d['DEPARTMENTNAME'] . " == <br>";
			//
			// 	foreach ($data['departmentsPerf'] as $p)
			// 	{
			// 		if ($d['DEPARTMENTID'] == $p['DEPARTMENTID'])
			// 		{
			// 			echo $p['timeliness'] . "<br>";
			// 		}
			// 	}
			// }

			$this->load->view("monitorDepartment", $data);
		}
	}

	public function monitorDepartmentDetails()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$projectID = $this->input->post('project_ID');
			$deptID = $this->input->post('dept_ID');

			$data['projectProfile'] = $this->model->getProjectByID($projectID);
			$data['tasks'] = $this->model->getAllDepartmentTasksByProject($projectID, $deptID);
			$data['raci'] = $this->model->getAllACI();

			$this->load->view("monitorDepartmentDetails", $data);
		}
	}

	public function monitorProjectDetails()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$projectID = $this->input->post('project_ID');

			$data['projectProfile'] = $this->model->getProjectByID($projectID);
			$data['tasks'] = $this->model->getAllTasksByProject($projectID);
			$data['raci'] = $this->model->getAllACI();

			$this->load->view("monitorDepartmentDetails", $data);
		}
	}

	public function monitorProject()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['ongoingProjects'] = $this->model->getAllOngoingOwnedProjectsByUser($_SESSION['USERID']);
			$data['plannedProjects'] = $this->model->getAllPlannedOwnedProjectsByUser($_SESSION['USERID']);
			$data['delayedProjects'] = $this->model->getAllDelayedOwnedProjectsByUser($_SESSION['USERID']);
			$data['completedProjects'] = $this->model->getAllCompletedOwnedProjectsByUser($_SESSION['USERID']);

			$data['ongoingProjectProgress'] = $this->model->getOngoingProjectProgress();
			$data['delayedProjectProgress'] = $this->model->getDelayedProjectProgress();

			$this->load->view("monitorProject", $data);
		}
	}

	public function myTasks()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
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
			$data['projectCountR'] = $this->model->getProjectCount($_SESSION['departments_DEPARTMENTID']);
			$data['taskCountR'] = $this->model->getTaskCount($_SESSION['departments_DEPARTMENTID']);
			$data['projectCount'] = $this->model->getProjectCount();
			$data['taskCount'] = $this->model->getTaskCount();

			$data['users'] = $this->model->getAllUsers();
			$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
			$data['ACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Ongoing");
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

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$taskDetails = $this->model->getTaskByID($id);
			$taskTitle = $taskDetails['TASKTITLE'];

			$projectID = $taskDetails['projects_PROJECTID'];
			$projectDetails = $this->model->getProjectByID($projectID);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS

			$details = $userName . " has completed " . $taskTitle . ".";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $projectID
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			$projectOwnerID = $projectDetails['users_USERID'];

			// START: Notifications

			// notify project owner
			$details = $userName . " has completed " . $taskTitle . " in " . $projectTitle . ".";

			$notificationData = array(
				'users_USERID' => $projectOwnerID,
				'DETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'status' => 'Unread',
				'projects_PROJECTID' => $projectID,
				'tasks_TASKID' => $id,
				'TYPE' => '1'
			);

			$this->model->addNotification($notificationData);

			// notify next task person
			$postTasksData['nextTaskID'] = $this->model->getPostDependenciesByTaskID($id);
			if($postTasksData['nextTaskID'] != NULL){

				foreach($postTasksData['nextTaskID'] as $nextTaskDetails) {

					$nextTaskID = $nextTaskDetails['tasks_POSTTASKID'];
					$postTasksData['users'] = $this->model->getRACIbyTask($nextTaskID);
					$nextTaskTitle = $nextTaskDetails['TASKTITLE'];

					foreach($postTasksData['users'] as $postTasksDataUsers){

						$details = "Pre-requisite task of " . $nextTaskTitle . " in " . $projectTitle . " has been completed.";

						$notificationData = array(
							'users_USERID' => $postTasksDataUsers['users_USERID'],
							'DETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'status' => 'Unread',
							'tasks_TASKID' => $id,
							'projects_PROJECTID' => $projectID,
							'TYPE' => '3'
						);

						$this->model->addNotification($notificationData);
					}
				}
			}

			// notify ACI
			$ACIdata['ACI'] = $this->model->getACIbyTask($id);
			if($ACIdata['ACI'] != NULL) {

				foreach($ACIdata['ACI'] as $ACIusers){

					$details = $userName . " has completed " . $taskTitle . " in " . $projectTitle . ".";

					$notificationData = array(
						'users_USERID' => $ACIusers['users_USERID'],
						'DETAILS' => $details,
						'TIMESTAMP' => date('Y-m-d H:i:s'),
						'status' => 'Unread',
						'projects_PROJECTID' => $projectID,
						'tasks_TASKID' => $id,
						'TYPE' => '4'
					);
					$this->model->addNotification($notificationData);
				}
			}
			// END: Notification

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

				// START OF LOGS/NOTIFS
				$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

				$taskDetails = $this->model->getTaskByID($id);
				$taskTitle = $taskDetails['TASKTITLE'];

				$projectID = $taskDetails['projects_PROJECTID'];
				$projectDetails = $this->model->getProjectByID($projectID);
				$projectTitle = $projectDetails['PROJECTTITLE'];

				// START: LOG DETAILS
				$details = $userName . " has completed Sub Activity - " . $taskTitle . ".";

				$logData = array (
					'LOGDETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'projects_PROJECTID' => $projectID
				);

				$this->model->addToProjectLogs($logData);
				// END: LOG DETAILS

				$projectOwnerID = $projectDetails['users_USERID'];

				// START: Notifications
				$details = "Sub Activty - " . $taskTitle . " has been completed by " . $userName . " in " . $projectTitle . ".";

				$notificationData = array(
					'users_USERID' => $projectOwnerID,
					'DETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'status' => 'Unread',
					'projects_PROJECTID' => $projectID,
					'tasks_TASKID' => $id,
					'TYPE' => '1'
				);

				$this->model->addNotification($notificationData);

				// notify ACI
				$ACIdata['ACI'] = $this->model->getACIbyTask($id);
				if($ACIdata['ACI'] != NULL) {

					foreach($ACIdata['ACI'] as $ACIusers){

						$details = "Sub Activity - " . $taskTitle . " has been completed in " . $projectTitle . ".";

						$notificationData = array(
							'users_USERID' => $ACIusers['users_USERID'],
							'DETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'status' => 'Unread',
							'projects_PROJECTID' => $projectID,
							'tasks_TASKID' => $id,
							'TYPE' => '4'
						);
						$this->model->addNotification($notificationData);
					}
				}
				// END: Notification

				$mainID = $this->model->getParentTask($parentID['tasks_TASKPARENT']);
				$completeSubs = $this->model->checkTasksStatus($mainID['tasks_TASKPARENT']);
				if($completeSubs == 0)
				{
					$mainData = array(
								'TASKSTATUS' => 'Complete',
								'TASKACTUALENDDATE' => date('Y-m-d')
					);
					$this->model->updateTaskDone($mainID['tasks_TASKPARENT'], $mainData); // Complete Main Activity

					// START OF LOGS/NOTIFS
					$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

					$taskDetails = $this->model->getTaskByID($id);
					$taskTitle = $taskDetails['TASKTITLE'];

					$projectID = $taskDetails['projects_PROJECTID'];
					$projectDetails = $this->model->getProjectByID($projectID);
					$projectTitle = $projectDetails['PROJECTTITLE'];

					// START: LOG DETAILS
					$details = $userName . " has completed Main Activity - " . $taskTitle . ".";

					$logData = array (
						'LOGDETAILS' => $details,
						'TIMESTAMP' => date('Y-m-d H:i:s'),
						'projects_PROJECTID' => $projectID
					);

					$this->model->addToProjectLogs($logData);
					// END: LOG DETAILS

					$projectOwnerID = $projectDetails['users_USERID'];

					// START: Notifications
					$details = "Main Activity - " . $taskTitle . " has been completed in " . $projectTitle . ".";

					$notificationData = array(
						'users_USERID' => $projectOwnerID,
						'DETAILS' => $details,
						'TIMESTAMP' => date('Y-m-d H:i:s'),
						'status' => 'Unread',
						'projects_PROJECTID' => $projectID,
						'tasks_TASKID' => $id,
						'TYPE' => '1'
					);

					$this->model->addNotification($notificationData);

					// notify next ACI
					$ACIdata['ACI'] = $this->model->getACIbyTask($id);
					if($ACIdata['ACI'] != NULL) {

						foreach($ACIdata['ACI'] as $ACIusers){

							$details = "Main Activity - " . $taskTitle . " has been completed in " . $projectTitle . ".";

							$notificationData = array(
								'users_USERID' => $ACIusers['users_USERID'],
								'DETAILS' => $details,
								'TIMESTAMP' => date('Y-m-d H:i:s'),
								'status' => 'Unread',
								'projects_PROJECTID' => $projectID,
								'tasks_TASKID' => $id,
								'TYPE' => '4'
							);
							$this->model->addNotification($notificationData);
						}
					}
					// END: Notification

					// Check and Complete a Project
					$completeProject = $this->model->checkProjectStatus($mainID['projects_PROJECTID']);
					if($completeProject == 0)
					{
						$projectData = array(
									'PROJECTSTATUS' => 'Complete',
									'PROJECTACTUALENDDATE' => date('Y-m-d')
						);
						$this->model->completeProject($mainID['projects_PROJECTID'], $projectData); // Complete Project

						// START OF LOGS/NOTIFS
						$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

						$taskDetails = $this->model->getTaskByID($id);
						$taskTitle = $taskDetails['TASKTITLE'];

						$projectID = $taskDetails['projects_PROJECTID'];
						$projectDetails = $this->model->getProjectByID($projectID);
						$projectTitle = $projectDetails['PROJECTTITLE'];

						// START: LOG DETAILS
						$details = "Project completed!";

						$logData = array (
							'LOGDETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'projects_PROJECTID' => $projectID
						);

						$this->model->addToProjectLogs($logData);
						// END: LOG DETAILS

						$projectOwnerID = $projectDetails['users_USERID'];

						$details = $projectTitle . " has been completed and will be archived in 7 days.";

						// notify PO
						$notificationData = array(
							'users_USERID' => $projectDetails['users_USERID'],
							'DETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'status' => 'Unread',
							'projects_PROJECTID' => $projectID,
							'TYPE' => '1'
						);

						$this->model->addNotification($notificationData);

						// notify all people involved in that project
						$data['projectUsers'] = $this->model->getAllUsersByProject($projectID);

						if($data['projectUsers'] != NULL){
							foreach($data['projectUsers'] as $projectUsers ) {
								// START: Notifications
								$details = $projectTitle . " has been completed and will be archived in 7 days.";

								$notificationData = array(
									'users_USERID' => $projectUsers['users_USERID'],
									'DETAILS' => $details,
									'TIMESTAMP' => date('Y-m-d H:i:s'),
									'status' => 'Unread',
									'projects_PROJECTID' => $projectID,
									'TYPE' => '1'
								);

								$this->model->addNotification($notificationData);
							}
						}
						// END: Notification
					}
				}
			}
		}
		$this->session->set_flashdata('success', 'alert');
		$this->session->set_flashdata('alertMessage', ' Task has been Marked Complete');
		$this->taskTodo();
	}

	public function loadTaskHistory()
	{
		$taskID = $this->input->post("task_ID");
		$data['task'] = $this->model->getTaskByID($taskID);
		$data['raciHistory'] = $this->model->getAllRACIbyTask($taskID);
		$data['changeRequests'] = $this->model->getChangeRequestsByTask($taskID);
		$data['users'] = $this->model->getAllUsers();

		echo json_encode($data);
	}

	public function loadTasks()
	{
		$data['users'] = $this->model->getAllUsers();
		$data['departments'] = $this->model->getAllDepartments();
		$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
		$data['ACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Ongoing");
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

	public function getPostDependenciesByTaskID()
	{
		$taskID = $this->input->post("task_ID");
		$data['dependencies'] = $this->model->getPostDependenciesByTaskID($taskID);
		$data['taskID'] = $this->model->getTaskByID($taskID);

		echo json_encode($data);
	}

	public function acceptTask()
	{
		$taskID = $this->input->post("task_ID");

		$updateD = $this->model->updateRACI($taskID, '0'); // change status to 'changed'

		$delegateData = array(
			'ROLE' => '5',
			'users_USERID' => $_SESSION['USERID'],
			'tasks_TASKID' => $taskID,
			'STATUS' => 'Current'
		);
		$result = $this->model->addToRaci($delegateData);

		// START OF LOGS/NOTIFS
		$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

		$taskDetails = $this->model->getTaskByID($taskID);
		$taskTitle = $taskDetails['TASKTITLE'];

		$projectID = $taskDetails['projects_PROJECTID'];
		$projectDetails = $this->model->getProjectByID($projectID);
		$projectTitle = $projectDetails['PROJECTTITLE'];

		// START: LOG DETAILS
		$details = $userName . " has accepted the responsibility for " . $taskTitle . ".";

		$logData = array (
			'LOGDETAILS' => $details,
			'TIMESTAMP' => date('Y-m-d H:i:s'),
			'projects_PROJECTID' => $projectID
		);

		$this->model->addToProjectLogs($logData);
		// END: LOG DETAILS

		$taskRACI = $this->model->getRACIbyTask($taskID);
		foreach($taskRACI as $raci){

			if($raci['ROLE'] != 5){
				// START: Notifications
				$details = $userName . " has accepted the responsibility for " . $taskTitle . " in " . $projectTitle . ".";
				$notificationData = array(
					'users_USERID' => $raci['users_USERID'],
					'DETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'status' => 'Unread'
				);

				$this->model->addNotification($notificationData);
				// END: Notification
			}
		}
		$this->session->set_flashdata('success', 'alert');
		$this->session->set_flashdata('alertMessage', ' Task has been Accepted');
		$this->taskDelegate();
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

			$delegate = $this->model->checkForDelegation($taskID);

			if($delegate == '1')
			{
				$updateD = $this->model->updateRACI($taskID, '0'); // change status to 'changed'

				$delegateDataNew = array(
					'ROLE' => '0',
					'users_USERID' => $responsibleEmp,
					'tasks_TASKID' => $taskID,
					'STATUS' => 'Current'
				);
				$result = $this->model->addToRaci($delegateDataNew);

				$delegateData = array(
					'ROLE' => '5',
					'users_USERID' => $_SESSION['USERID'],
					'tasks_TASKID' => $taskID,
					'STATUS' => 'Current'
				);
				$result = $this->model->addToRaci($delegateData);
			}

			$responsibleData = array(
				'ROLE' => '1',
				'users_USERID' => $responsibleEmp,
				'tasks_TASKID' => $taskID,
				'STATUS' => 'Current'
			);
			$result = $this->model->addToRaci($responsibleData);

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$taskDetails = $this->model->getTaskByID($taskID);
			$taskTitle = $taskDetails['TASKTITLE'];

			$projectID = $taskDetails['projects_PROJECTID'];
			$projectDetails = $this->model->getProjectByID($projectID);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			$userDetails = $this->model->getUserByID($this->input->post('responsibleEmp'));
			$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

			// START: LOG DETAILS
			$details = $userName . " has tagged " . $taggedUserName . " as responsible for " . $taskTitle . ".";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $projectID
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS


			// START: Notifications
			$details =  $userName . " has tagged you responsible for " . $taskTitle . " in " . $projectTitle . ".";

			$notificationData = array(
				'users_USERID' => $this->input->post('responsibleEmp'),
				'DETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'status' => 'Unread',
				'projects_PROJECTID' => $projectID,
				'tasks_TASKID' => $taskID,
				'TYPE' => '3'
			);

			$this->model->addNotification($notificationData);
			// END: Notification
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

				// START OF LOGS/NOTIFS
				$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

				$taskDetails = $this->model->getTaskByID($taskID);
				$taskTitle = $taskDetails['TASKTITLE'];

				$projectID = $taskDetails['projects_PROJECTID'];
				$projectDetails = $this->model->getProjectByID($projectID);
				$projectTitle = $projectDetails['PROJECTTITLE'];

				$userDetails = $this->model->getUserByID($empID);
				$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

				// START: LOG DETAILS
				$details = $userName . " has tagged " . $taggedUserName . " as accountable for " . $taskTitle . ".";

				$logData = array (
					'LOGDETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'projects_PROJECTID' => $projectID
				);

				$this->model->addToProjectLogs($logData);
				// END: LOG DETAILS

				// START: Notifications
				$details =  $userName . " has tagged you accountable for " . $taskTitle . " in " . $projectTitle . ".";
				$notificationData = array(
					'users_USERID' => $empID,
					'DETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'status' => 'Unread',
					'projects_PROJECTID' => $projectID,
					'tasks_TASKID' => $taskID,
					'TYPE' => '4'
				);

				$this->model->addNotification($notificationData);
				// END: Notification
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

				// START: LOG DETAILS
				$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
				$taskDetails = $this->model->getTaskByID($taskID);
				$taskTitle = $taskDetails['TASKTITLE'];
				$projectID = $taskDetails['projects_PROJECTID'];
				$userDetails = $this->model->getUserByID($empID);
				$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];
				$details = $userName . " has tagged " . $taggedUserName . " as consulted for " . $taskTitle . ".";

				$logData = array (
					'LOGDETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'projects_PROJECTID' => $projectID
				);

				$this->model->addToProjectLogs($logData);
				// END: LOG DETAILS

				// START: Notifications
				$projectDetails = $this->model->getProjectByID($projectID);
				$projectTitle = $projectDetails['PROJECTTITLE'];

				$details =  $userName . " has tagged you consulted for " . $taskTitle . " in " . $projectTitle . ".";
				$notificationData = array(
					'users_USERID' => $empID,
					'DETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'status' => 'Unread',
					'projects_PROJECTID' => $projectID,
					'tasks_TASKID' => $taskID,
					'TYPE' => '4'
				);

				$this->model->addNotification($notificationData);
				// END: Notification
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

				// START: LOG DETAILS
				$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
				$taskDetails = $this->model->getTaskByID($taskID);
				$taskTitle = $taskDetails['TASKTITLE'];
				$projectID = $taskDetails['projects_PROJECTID'];
				$userDetails = $this->model->getUserByID($empID);
				$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];
				$details = $userName . " has tagged " . $taggedUserName . " as informed for " . $taskTitle . ".";

				$logData = array (
					'LOGDETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'projects_PROJECTID' => $projectID
				);

				$this->model->addToProjectLogs($logData);
				// END: LOG DETAILS

				// START: Notifications
				$projectDetails = $this->model->getProjectByID($projectID);
				$projectTitle = $projectDetails['PROJECTTITLE'];

				$details =  $userName . " has tagged you informed for " . $taskTitle . " in " . $projectTitle . ".";

				$notificationData = array(
					'users_USERID' => $empID,
					'DETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'status' => 'Unread',
					'projects_PROJECTID' => $projectID,
					'tasks_TASKID' => $taskID,
					'TYPE' => '4'
				);

				$this->model->addNotification($notificationData);
				// END: Notification

			}
		}
		$this->session->set_flashdata('success', 'alert');
		$this->session->set_flashdata('alertMessage', ' Task has been Delegated');
		$this->taskDelegate();
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
				'REQUESTEDDATE' => date('Y-m-d'),
				'users_APPROVEDBY' => '1'
			);

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$taskID = $this->input->post("task_ID");
			$taskDetails = $this->model->getTaskByID($taskID);
			$taskTitle = $taskDetails['TASKTITLE'];

			$projectID = $taskDetails['projects_PROJECTID'];
			$projectDetails = $this->model->getProjectByID($projectID);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS
			$details = $userName . " requested a change in performer for " . $taskTitle . ".";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $taskDetails['projects_PROJECTID']
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			// START: Notifications
			$details =  "A change in performer was requested by " . $userName . " for " . $taskTitle . " in " . $projectTitle . ".";

			$taggedUserID = "";

			if($_SESSION['usertype_USERTYPEID'] == 5 || 4) {
				$taggedUserID = $_SESSION['users_SUPERVISORS'];
			} else {
				$taggedUserID = $projectDetails['users_USERID'];
			}

			$notificationData = array(
				'users_USERID' => $taggedUserID,
				'DETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'status' => 'Unread',
				'projects_PROJECTID' => $projectID,
				'tasks_TASKID' => $taskID,
				'TYPE' => '6'
			);

			$this->model->addNotification($notificationData);
			// END: Notification

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

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$taskID = $this->input->post("task_ID");
			$taskDetails = $this->model->getTaskByID($taskID);
			$taskTitle = $taskDetails['TASKTITLE'];

			$projectID = $taskDetails['projects_PROJECTID'];
			$projectDetails = $this->model->getProjectByID($projectID);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS
			$details = $userName . " requested a change in dates for " . $taskTitle . ".";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $projectID
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			// START: Notifications
			$details =  "A change in dates was requested by " . $userName . " for " . $taskTitle . " in " . $projectTitle . ".";
			$taggedUserID = "";

			if($_SESSION['usertype_USERTYPEID'] == 5 || 4) {
				$taggedUserID = $_SESSION['users_SUPERVISORS'];
			} else {
				$taggedUserID = $projectDetails['users_USERID'];
			}

			$notificationData = array(
				'users_USERID' => $taggedUserID,
				'DETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'status' => 'Unread',
				'projects_PROJECTID' => $projectID,
				'tasks_TASKID' => $taskID,
				'TYPE' => '6'
			);

			$this->model->addNotification($notificationData);
			// END: Notification
		}
		$this->model->addRFC($data);
		$this->session->set_flashdata('success', 'alert');
		$this->session->set_flashdata('alertMessage', ' Request for Change Submitted');
		$this->taskTodo();
	}

	public function approveDenyRFC()
	{
		$requestID = $this->input->post('request_ID');
		$requestType = $this->input->post('request_type');
		$projectID = $this->input->post('project_ID');
		$remarks = $this->input->post('remarks');
		$status = $this->input->post('status');
		$taskID = $this->input->post('task_ID');
		$requestorID = $this->input->post('requestor_ID');

		$data = array(
			'REQUESTSTATUS' => $status,
			'REMARKS' => $remarks,
			'users_APPROVEDBY' => $_SESSION['USERID'],
			'APPROVEDDATE' => date('Y-m-d')
		);

		$this->model->updateRFC($requestID, $data);

		// START OF LOGS/NOTIFS
		$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

		$taskDetails = $this->model->getTaskByID($taskID);
		$taskTitle = $taskDetails['TASKTITLE'];

		$projectID = $taskDetails['projects_PROJECTID'];
		$projectDetails = $this->model->getProjectByID($projectID);
		$projectTitle = $projectDetails['PROJECTTITLE'];

		$details = $userName . " has " . $status . " change request for " . $taskTitle . ".";

		// it doesn't work sa deny
		$logData = array (
			'LOGDETAILS' => $details,
			'TIMESTAMP' => date('Y-m-d H:i:s'),
			'projects_PROJECTID' => $projectID
		);

		$this->model->addToProjectLogs($logData);
		// END: LOG DETAILS

		// START: Notifications
		$details = $userName . " has " . $status . " your change request for " . $taskTitle . ".";

		$notificationData = array(
			'users_USERID' => $requestorID,
			'DETAILS' => $details,
			'TIMESTAMP' => date('Y-m-d H:i:s'),
			'status' => 'Unread',
			'projects_PROJECTID' => $projectID,
			'tasks_TASKID' => $taskID,
			'TYPE' => '6'
		);

		$this->model->addNotification($notificationData);
		// END: Notification

		if($status == 'Approved' && $requestType == '1') // if approved change performer
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

					// START OF LOGS/NOTIFS
					$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

					$taskDetails = $this->model->getTaskByID($taskID);
					$taskTitle = $taskDetails['TASKTITLE'];

					$projectID = $taskDetails['projects_PROJECTID'];
					$projectDetails = $this->model->getProjectByID($projectID);
					$projectTitle = $projectDetails['PROJECTTITLE'];

					$userDetails = $this->model->getUserByID($responsibleEmp);
					$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

					// START: LOG DETAILS
					$details = $userName . " has tagged " . $taggedUserName . " as responsible for " . $taskTitle . ".";

					$logData = array (
						'LOGDETAILS' => $details,
						'TIMESTAMP' => date('Y-m-d H:i:s'),
						'projects_PROJECTID' => $projectID
					);

					$this->model->addToProjectLogs($logData);
					// END: LOG DETAILS

					// START: Notifications
					$details = $userName . " has tagged " . $taggedUserName . " as responsible for " . $taskTitle . ".";
					$details =  $userName . " has tagged you responsible for " . $taskTitle . " in " . $projectTitle . ".";

					$notificationData = array(
						'users_USERID' => $responsibleEmp,
						'DETAILS' => $details,
						'TIMESTAMP' => date('Y-m-d H:i:s'),
						'status' => 'Unread',
						'projects_PROJECTID' => $projectID,
						'tasks_TASKID' => $taskID,
						'TYPE' => '3'
					);

					$this->model->addNotification($notificationData);
					// END: Notification
				}

				// SAVE ACCOUNTABLE
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

						// START OF LOGS/NOTIFS
						$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

						$taskDetails = $this->model->getTaskByID($taskID);
						$taskTitle = $taskDetails['TASKTITLE'];

						$projectID = $taskDetails['projects_PROJECTID'];
						$projectDetails = $this->model->getProjectByID($projectID);
						$projectTitle = $projectDetails['PROJECTTITLE'];

						$userDetails = $this->model->getUserByID($empID);
						$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

						// START: LOG DETAILS
						$details = $userName . " has tagged " . $taggedUserName . " as accountable for " . $taskTitle . ".";
						$details =  $userName . " has tagged you responsible for " . $taskTitle . " in " . $projectTitle . ".";

						$logData = array (
							'LOGDETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'projects_PROJECTID' => $projectID
						);

						$this->model->addToProjectLogs($logData);
						// END: LOG DETAILS

						// START: Notifications
						$details =  $userName . " has tagged you accountable for " . $taskTitle . " in " . $projectTitle . ".";

						$notificationData = array(
							'users_USERID' => $empID,
							'DETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'status' => 'Unread',
							'projects_PROJECTID' => $projectID,
							'tasks_TASKID' => $taskID,
							'TYPE' => '4'
						);

						$this->model->addNotification($notificationData);
						// END: Notification

					}
				}

				// SAVE CONSULTED
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

						// START OF LOGS/NOTIFS
						$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

						$taskDetails = $this->model->getTaskByID($taskID);
						$taskTitle = $taskDetails['TASKTITLE'];

						$projectID = $taskDetails['projects_PROJECTID'];
						$projectDetails = $this->model->getProjectByID($projectID);
						$projectTitle = $projectDetails['PROJECTTITLE'];

						$userDetails = $this->model->getUserByID($empID);
						$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

						// START: LOG DETAILS
						$details = $userName . " has tagged " . $taggedUserName . " as consulted for " . $taskTitle . ".";

						$logData = array (
							'LOGDETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'projects_PROJECTID' => $projectID
						);

						$this->model->addToProjectLogs($logData);
						// END: LOG DETAILS

						// START: Notifications
						$details =  $userName . " has tagged you consulted for " . $taskTitle . " in " . $projectTitle . ".";

						$notificationData = array(
							'users_USERID' => $empID,
							'DETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'status' => 'Unread',
							'projects_PROJECTID' => $projectID,
							'tasks_TASKID' => $taskID,
							'TYPE' => '4'
						);

						$this->model->addNotification($notificationData);
						// END: Notification
					}
				}

				// SAVE INFORMED
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

						// START OF LOGS/NOTIFS
						$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

						$taskDetails = $this->model->getTaskByID($taskID);
						$taskTitle = $taskDetails['TASKTITLE'];

						$projectID = $taskDetails['projects_PROJECTID'];
						$projectDetails = $this->model->getProjectByID($projectID);
						$projectTitle = $projectDetails['PROJECTTITLE'];

						// START: LOG DETAILS
						$details = $userName . " has tagged " . $taggedUserName . " as informed for " . $taskTitle . ".";

						$logData = array (
							'LOGDETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'projects_PROJECTID' => $projectID
						);

						$this->model->addToProjectLogs($logData);
						// END: LOG DETAILS

						// START: Notifications
						$details =  $userName . " has tagged you informed for " . $taskTitle . " in " . $projectTitle . ".";
						$notificationData = array(
							'users_USERID' => $empID,
							'DETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'status' => 'Unread',
							'projects_PROJECTID' => $projectID,
							'tasks_TASKID' => $taskID,
							'TYPE' => '4'
						);

						$this->model->addNotification($notificationData);
						// END: Notification
					}
				}
				$this->session->set_flashdata('success', 'alert');

				$this->session->set_flashdata('alertMessage', ' Request for Change Approved');
		} // end if appoved change Performer
		else // if approved change date
		{
			$taskID = $this->input->post("task_ID");
			$changeRequest = $this->model->getChangeRequestbyID($requestID);

			$taskData = array(
				'TASKADJUSTEDENDDATE' => $changeRequest['NEWENDDATE']
			);

			$this->model->updateTaskDates($taskID, $taskData); //save adjusted dates of requested task

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$taskDetails = $this->model->getTaskByID($taskID);
			$taskTitle = $taskDetails['TASKTITLE'];

			$projectID = $taskDetails['projects_PROJECTID'];
			$projectDetails = $this->model->getProjectByID($projectID);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS
			$details = $userName . " has adjusted the end date for " . $taskTitle . ".";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $projectID
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			// START: Notifications
			$details = "End Date for " . $taskTitle . " in " . $projectTitle . " has been adjusted.";

			// notify project owner
			$notificationData = array(
				'users_USERID' => $projectDetails['users_USERID'],
				'DETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'status' => 'Unread',
				'projects_PROJECTID' => $projectID,
				'tasks_TASKID' => $taskID,
				'TYPE' => '1'
			);

			$this->model->addNotification($notificationData);

			// notify next task person
			$postTasksData['nextTaskID'] = $this->model->getPostDependenciesByTaskID($taskID);
			if($postTasksData['nextTaskID'] != NULL){

				foreach($postTasksData['nextTaskID'] as $nextTaskDetails) {

					$nextTaskID = $nextTaskDetails['tasks_POSTTASKID'];
					$postTasksData['users'] = $this->model->getRACIbyTask($nextTaskID);

					foreach($postTasksData['users'] as $postTasksDataUsers){
						$details = "End Date for " . $taskTitle . " in " . $projectTitle . " has been adjusted.";

						$notificationData = array(
							'users_USERID' => $postTasksDataUsers['users_USERID'],
							'DETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'status' => 'Unread',
							'projects_PROJECTID' => $projectID,
							'tasks_TASKID' => $taskID,
							'TYPE' => '1'
						);

						$this->model->addNotification($notificationData);
					}
				}
			}

			// notify ACI
			$ACIdata['ACI'] = $this->model->getACIbyTask($taskID);
			if($ACIdata['ACI'] != NULL) {

				foreach($ACIdata['ACI'] as $ACIusers){

					$details = "End Date for " . $taskTitle . " in " . $projectTitle . " has been adjusted.";

					$notificationData = array(
						'users_USERID' => $ACIusers['users_USERID'],
						'DETAILS' => $details,
						'TIMESTAMP' => date('Y-m-d H:i:s'),
						'status' => 'Unread',
						'projects_PROJECTID' => $projectID,
						'tasks_TASKID' => $taskID,
						'TYPE' => '4'
					);
					$this->model->addNotification($notificationData);
				}
			}
			// END: Notification
			$this->session->set_flashdata('success', 'alert');

			$this->session->set_flashdata('alertMessage', ' Request for Change Denied');
		} // end if approved change dates

		$data['projectProfile'] = $this->model->getProjectByID($projectID);
		$data['ganttData'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);
		$data['dependencies'] = $this->model->getDependenciesByProject($projectID);
		$data['users'] = $this->model->getAllUsers();
		$data['responsible'] = $this->model->getAllResponsibleByProject($projectID);
		$data['accountable'] = $this->model->getAllAccountableByProject($projectID);
		$data['consulted'] = $this->model->getAllConsultedByProject($projectID);
		$data['informed'] = $this->model->getAllInformedByProject($projectID);
		// $data['subActivityProgress'] = $this->model->getSubActivityProgress($projectID);

		$data['employeeCompleteness'] = $this->model->compute_completeness_employeeByProject($_SESSION['USERID'], $projectID);
		$data['employeeTimeliness'] = $this->model->compute_timeliness_employeeByProject($_SESSION['USERID'], $projectID);
		$data['projectCompleteness'] = $this->model->compute_completeness_project($projectID);
		$data['projectTimeliness'] = $this->model->compute_timeliness_project($projectID);

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

	public function getUserWorkloadTasksUnique()
	{
		$userID = $this->input->post('userID');
		$projectID = $this->input->post('projectID');
		$data['workloadTasks'] = $this->model->getWorkloadTasksUnique($userID, $projectID);

		echo json_encode($data);
	}


	public function templates()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
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
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['archives'] = $this->model->getAllProjectArchives();
			// $data['templates'] = $this->model->getAllTemplates();

			$this->load->view("archives", $data);
		}
	}

	public function addProjectDetails()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$id = $this->input->post("project_ID");
			$edit = $this->input->post("edit");
			$templates = $this->input->post("templates");

			// TEMPLATES
			if (isset($id))
			{
				if (isset($edit))
				{
					$this->session->set_flashdata('edit', $edit);
				}

				elseif (isset($templates))
				{
					$this->session->set_flashdata('templates', $id);
				}

				$data['project'] = $this->model->getProjectByID($id);
				$data['allTasks'] = $this->model->getAllProjectTasks($id);
				$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
				$data['mainActivity'] = $this->model->getAllMainActivitiesByID($id);
				$data['subActivity'] = $this->model->getAllSubActivitiesByID($id);
				$data['tasks'] = $this->model->getAllTasksByIDRole1($id);

				$this->load->view("addProjectDetails", $data);
			}

			else
			{
				$this->load->view("addProjectDetails");
			}
		}
	}

	public function editProject()
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
		$diff = date_diff($eDate, $sDate, true);
		$dateDiff = $diff->format('%R%a');

		$edit = $this->input->post('edit');

		// PLUGS DATA INTO DB AND RETURNS ARRAY OF THE PROJECT
		$editProject = $this->model->editProject($edit, $data);
		$data['project'] = $this->model->getProjectByID($edit);
		$data['dateDiff'] =$dateDiff;
		$data['departments'] = $this->model->getAllDepartments();

		if ($data)
		{
			// TODO PUT ALERT

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$projectDetails = $this->model->getProjectByID($edit);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS
			$details = $userName . " has edited " . $projectTitle . ".";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $edit
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			// TODO NAMI: put notif "user Edited Project projectitile"

			// // START: Notifications
			// $details = "You have been tagged as accountable for " . $taskTitle . " in " . $projectTitle . ".";
			// $notificationData = array(
			// 	'users_USERID' => $empID,
			// 	'DETAILS' => $details,
			// 	'TIMESTAMP' => date('Y-m-d H:i:s'),
			// 	'status' => 'Unread',
			// 'projects_PROJECTID' => $projectID,
			// 'tasks_TASKID' => $taskID, 'TYPE' => '4'
			// );
			//
			// $this->model->addNotification($notificationData);
			// // END: Notification

			if (isset($edit))
			{
				$data['editProject'] = $this->model->getProjectByID($edit);
				$data['editAllTasks'] = $this->model->getAllProjectTasks($edit);
				$data['editGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($edit);
				$data['editMainActivity'] = $this->model->getAllMainActivitiesByID($edit);
				$data['editSubActivity'] = $this->model->getAllSubActivitiesByID($edit);
				$data['editTasks'] = $this->model->getAllTasksByIDRole1($edit);
				$data['editRaci'] = $this->model->getRaci($edit);
				$data['editUsers'] = $this->model->getAllUsers();
			}

			$this->session->set_flashdata('edit', $edit);

			$this->load->view('addMainActivities', $data);
		}

		else
		{
			// TODO PUT ALERT
			redirect('controller/restrictedAccess');
		}
	}

	public function myCalendar()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
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
			$this->load->view('restrictedAccess');
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
			$this->load->view('restrictedAccess');
		}

		else
		{

			
			$this->load->view("reports");
		}
	}

	// REPORTS STARTS

	public function reportsProjectPerDept()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['allProjects'] = $this->model->getAllProjects();
			$data['departments'] = $this->model->getAllDepartments();
			$data['projectCompleteness'] = $this->model->compute_completeness_allProjects();
			$data['projectTimeliness'] = $this->model->compute_timeliness_allProjects();

			$this->load->view("reportsProjectPerDept", $data);
		}
	}

	public function reportsOngoingProjects()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['ongoingProjects'] = $this->model->getAllOngoingProjects();
			$data['ongoingProjectProgress'] = $this->model->getOngoingProjectProgress();

			$this->load->view("reportsOngoingProjects", $data);
		}
	}

	public function reportsPlannedProjects()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['plannedProjects'] = $this->model->getAllPlannedProjects();

			$this->load->view("reportsPlannedProjects", $data);
		}
	}

	public function reportsParkedProjects()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("reportsParkedProjects");
		}
	}

	public function reportsProjectSummary()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("reportsProjectSummary");
		}
	}

	public function reportsEmployeesPerformancePerProject()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("reportsEmployeesPerformancePerProject");
		}
	}

	public function reportsEmployeesPerformancePerEmployee()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("reportsEmployeesPerformancePerEmployee");
		}
	}

	public function reportsDepartmentalPerformancePerDepartment()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("reportsDepartmentalPerformancePerDepartment");
		}
	}

	public function reportsDepartmentalPerformancePerProject()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("reportsDepartmentalPerformancePerProject");
		}
	}

	public function reportsChangeRequestsPerEmployee()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("reportsChangeRequestsPerEmployee");
		}
	}

	public function reportsChangeRequestsPerDepartment()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("reportsChangeRequestsPerDepartment");
		}
	}

	public function reportsChangeRequestsPerProject()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("reportsChangeRequestsPerProject");
		}
	}
	// REPORTS END

	public function dashboardAdmin()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("dashboardAdmin");
		}
	}

	public function manageDepartments()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("manageDepartments");
		}
	}

	public function manageUsers()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("manageUsers");
		}
	}

	public function projectLogs()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$id = $this->input->post("projectID_logs");
			$this->session->set_flashdata('projectIDlogs', $id);
			$data['projectLog'] = $this->model->getProjectLogs($id);
			$data['projectID'] = $id;
			$data['projectProfile'] = $this->model->getProjectByID($id);
			$this->load->view("projectLogs", $data);
		}
	}

	public function teamGantt()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$id = $this->input->post("project_ID");
			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['ganttData'] = $this->model->getAllProjectTasks($id);
			$data['dependencies'] = $this->model->getDependenciesByProject($id);
			$data['users'] = $this->model->getAllUsers();

			$departmentID = $_SESSION['departments_DEPARTMENTID'];

			$data['ganttData'] = $this->model->getAllProjectTasksByDepartment($id, $departmentID);

			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['ganttData'] = $this->model->getAllProjectTasksByDepartment($id, $departmentID);
			$data['dependencies'] = $this->model->getDependenciesByProject($id);
			$data['users'] = $this->model->getAllUsers();

			$data['responsible'] = $this->model->getAllResponsibleByProject($id);
			$data['accountable'] = $this->model->getAllAccountableByProject($id);
			$data['consulted'] = $this->model->getAllConsultedByProject($id);
			$data['informed'] = $this->model->getAllInformedByProject($id);

			$data['employeeCompleteness'] = $this->model->compute_completeness_employeeByProject($_SESSION['USERID'], $id);
			$deptC = $this->model->compute_completeness_departmentByProject($id);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employeeByProject($_SESSION['USERID'], $id);
			$deptT = $this->model->compute_timeliness_departmentByProject($id);

			foreach($deptC as $dc){
				if($dc['DEPARTMENTID'] == $departmentID){
					$data['departmentCompleteness'] = $dc;
				}
			}

			foreach($deptT as $dt){
				if($dt['DEPARTMENTID'] == $_SESSION['departments_DEPARTMENTID']){
					$data['departmentTimeliness'] = $dt;
				}
			}

			$this->load->view("teamGantt", $data);
		}
	}

	public function taskTodo()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);

			$this->load->view("taskTodo", $data);
		}
	}

	public function taskDelegate()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$dashboard = $this->input->post("dashboard");
			if (isset($dashboard))
			{
				$this->session->set_flashdata('dashboard', $dashboard);
			}
			switch($_SESSION['usertype_USERTYPEID'])
			{
				case '2':
					$filter = "users.usertype_USERTYPEID = '3'";
					break;

				case '3':
					$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
					break;

				case '4':
					$filter = "(users.usertype_USERTYPEID = '3' &&  users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."')
					|| users.users_SUPERVISORS = '" . $_SESSION['USERID'] ."' && users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
					break;

				default:
					$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
					break;
			}

			$data['delegateTasksByProject'] = $this->model->getAllProjectsToEditByUser($_SESSION['USERID'], "projects.PROJECTID");
			$data['delegateTasks'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID']);
			$data['departments'] = $this->model->getAllDepartments();
			$data['users'] = $this->model->getAllUsers();
			$data['wholeDept'] = $this->model->getAllUsersByUserType($filter);
			$data['projectCount'] = $this->model->getProjectCount();
			$data['taskCount'] = $this->model->getTaskCount();

			$this->load->view("taskDelegate", $data);
		}
	}

	public function taskMonitor()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['allPlannedACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Planning");
			$data['uniquePlannedACItasks'] = $this->model->getUniqueACITasksByUser($_SESSION['USERID'], "Planning");

			$data['allOngoingACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Ongoing");
			$data['uniqueOngoingACItasks'] = $this->model->getUniqueACITasksByUser($_SESSION['USERID'], "Ongoing");

			$data['allCompletedACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Complete");
			$data['uniqueCompletedACItasks'] = $this->model->getUniqueACITasksByUser($_SESSION['USERID'], "Complete");

			$this->load->view("taskMonitor", $data);
		}
	}

	public function getRACIByTaskID()
	{
		$taskID = $this->input->post('taskID');
		$data['raci'] = $this->model->getRACIbyTask($taskID);

		$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";

		$data['departments'] = $this->model->getAllDepartments();
		$data['users'] = $this->model->getAllUsers();
		$data['wholeDept'] = $this->model->getAllUsersByDepartment($_SESSION['departments_DEPARTMENTID']);
		$data['projectCount'] = $this->model->getProjectCount();
		$data['taskCount'] = $this->model->getTaskCount();

		echo json_encode($data);
	}

	public function setDelegationRestriction()
	{
		$data['delegateTasksByProject'] = $this->model->getAllProjectsToEditByUser($_SESSION['USERID'], "projects.PROJECTID");
		$data['delegateTasks'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID']);

		echo json_encode($data);
	}

	public function notifications()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$data['notification'] = $this->model->getAllNotificationsByUser();

			$this->load->view("notifications", $data);
		}
	}

	public function projectSummary()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$id = $this->input->post('project_ID');
			$templateProjSummary = $this->input->post('templateProjSummary');

			// if (isset($templateProjSummary))
			// {
			// 	echo $templateProjSummary;
			// }
			// else {
			// 	echo "hello";
			// }

			$data['project'] = $this->model->getProjectByID($id);
			$data['mainActivity'] = $this->model->getAllMainActivitiesByID($id);
			$data['subActivity'] = $this->model->getAllSubActivitiesByID($id);
			$data['tasks'] = $this->model->getAllTasksByIDRole1($id);
			$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
			$data['changeRequests'] = $this->model->getChangeRequestsByProject($id);
			$data['documents'] = $this->model->getAllDocumentsByProject($id);
			$data['projectCompleteness'] = $this->model->compute_completeness_project($id);
			$data['projectTimeliness'] = $this->model->compute_timeliness_project($id);
			$data['departments'] = $this->model->compute_timeliness_departmentByProject($id);
			$data['team'] = $this->model->getTeamByProject($id);
			$data['users'] = $this->model->getAllUsers();
			$data['allDepartments'] = $this->model->getAllDepartments();
			$data['taskCount'] = $this->model->getTaskCountByProjectByRole($id);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employeesByProject($id);

			$this->load->view("projectSummary", $data);
		}
	}

// DELETE THIS MAYBE?
	public function newProjectTask()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$this->load->view("newProjectTask");
		}
	}

	public function addMainActivities()
	{
		if (isset($_SESSION['USERID']))
		{
				// IMPORT PROJECT
			if ($this->input->post('isImport') == 1)
			{
				$config['upload_path'] = './assets/uploads/templates';
		 	 	$config['allowed_types'] = 'xlsx|csv|xls';
		 	 	$config['max_size'] = '10000000';
		 	 	$this->load->library('upload', $config);
		 	 	$this->upload->initialize($config);

		 	 	if (!$this->upload->do_upload('uploadFile'))
		 	 	{
					$error = array('error' => $this->upload->display_errors());

					 $this->session->set_flashdata('danger', 'alert');
					 $this->session->set_flashdata('alertMessage', $error['error']);

			 		 redirect('controller/addProjectDetails');
		 	 	}

			 	 else
			 	 {
					 $data = array('upload_data' => $this->upload->data());
				   $path = './assets/uploads/templates/';

				   $import_xls_file = $data['upload_data']['file_name'];
				   $inputFileName = $path . $import_xls_file;
				   $sheetname = 'Project Details';

					 try
				   {
				    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
				    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
				    $reader->setLoadSheetsOnly($sheetname);
				    $spreadsheet = $reader->load($inputFileName);
				    $worksheet = $spreadsheet->getActiveSheet()->toArray('NULL', 'true', 'true', 'true');

						$title = $spreadsheet->getActiveSheet()->getCell('B1')->getValue();
						$description = $spreadsheet->getActiveSheet()->getCell('B2')->getValue();
						$startDate = $spreadsheet->getActiveSheet()->getCell('B3')->getValue();
						$endDate = $spreadsheet->getActiveSheet()->getCell('B4')->getValue();
						$status = $spreadsheet->getActiveSheet()->getCell('B5')->getValue();

						echo $title . "<br>";
						echo $description . "<br>";
						echo $startDate . "<br>";
						echo $endDate . "<br>";
						echo $status . "<br>";


				  }

					catch (Exception $e)
				  {
				     die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
				              . '": ' .$e->getMessage());
				  }
				 }
		 	 }

			// NEW PROJECT FROM SCRATCH
			else
			{
				$startDate = $this->input->post('startDate');
				date_default_timezone_set("Singapore");
				$currDate = date("Y-m-d");

				if ($currDate >= $startDate)
				{
					$status = 'Ongoing';
				}

				else
				{
					$status = 'Drafted';
				}

				$data = array(
						'PROJECTTITLE' => $this->input->post('projectTitle'),
						'PROJECTSTARTDATE' => $startDate,
						'PROJECTENDDATE' => $this->input->post('endDate'),
						'PROJECTDESCRIPTION' => $this->input->post('projectDetails'),
						'PROJECTSTATUS' => $status,
						'users_USERID' => $_SESSION['USERID'],
						'DATECREATED' => $currDate
				);

				$sDate = date_create($startDate);
				$eDate = date_create($this->input->post('endDate'));
				$diff = date_diff($eDate, $sDate, true);
				$dateDiff = $diff->format('%R%a');

				// PLUGS DATA INTO DB AND RETURNS ARRAY OF THE PROJECT
				$data['project'] = $this->model->addProject($data);
				$data['dateDiff'] =$dateDiff;
				$data['departments'] = $this->model->getAllDepartments();

				if ($data)
				{
					// TODO PUT ALERT

					// START OF LOGS/NOTIFS
					$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

					$projectID = $data['project']['PROJECTID'];

					// START: LOG DETAILS
					$details = $userName . " created this project.";

					$logData = array (
						'LOGDETAILS' => $details,
						'TIMESTAMP' => date('Y-m-d H:i:s'),
						'projects_PROJECTID' => $projectID
					);

					$this->model->addToProjectLogs($logData);
					// END: LOG DETAILS

					$progressData = array(
						'projects_PROJECTID' => $projectID = $data['project']['PROJECTID'],
						'DATE' => date('Y-m-d'),
						'COMPLETENESS' => 0,
						'TIMELINESS' => 0
					);

					$this->model->addAssessmentProject($progressData);

					$templates = $this->input->post('templates');

					if (isset($templates))
					{
						$this->session->set_flashdata('templates', $templates);

						$data['templateProject'] = $this->model->getProjectByID($templates);
						$data['templateAllTasks'] = $this->model->getAllProjectTasks($templates);
						$data['templateGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($templates);
						$data['templateMainActivity'] = $this->model->getAllMainActivitiesByID($templates);
						$data['templateSubActivity'] = $this->model->getAllSubActivitiesByID($templates);
						$data['templateTasks'] = $this->model->getAllTasksByIDRole1($templates);
						$data['templateRaci'] = $this->model->getRaci($templates);
						$data['templateUsers'] = $this->model->getAllUsers();
					}

						$this->load->view('addMainActivities', $data);
				}
			}
		}

		else
		{
			// TODO PUT ALERT
			redirect('controller/restrictedAccess');
		}
	}

// DELETE THIS MAYBE??
	public function addTasks()
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
				case 'Human Resource':
					$hrHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Management Information System':
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

		date_default_timezone_set("Singapore");
		$currDate = date("Y-m-d");

		foreach ($title as $key=> $row)
		{
			if ($currDate >= $startDates[$key])
			{
				$tStatus = 'Ongoing';
			}

			else
			{
				$tStatus = 'Planning';
			}

			$data = array(
					'TASKTITLE' => $row,
					'TASKSTARTDATE' => $startDates[$key],
					'TASKENDDATE' => $endDates[$key],
					'TASKSTATUS' => $tStatus,
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
									case 'Human Resource':
										$deptHead = $hrHead;
										break;
									case 'Management Information System':
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
										'ROLE' => '0',
										'users_USERID' => $deptHead,
										'tasks_TASKID' => $a,
										'STATUS' => 'Current'
								);

								// ENTER INTO RACI
								$result = $this->model->addToRaci($data);

								// START OF LOGS/NOTIFS
								$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

								$taskDetails = $this->model->getTaskByID($a);
								$taskTitle = $taskDetails['TASKTITLE'];

								$projectID = $taskDetails['projects_PROJECTID'];
								$projectDetails = $this->model->getProjectByID($projectID);
								$projectTitle = $projectDetails['PROJECTTITLE'];

								$userDetails = $this->model->getUserByID($deptHead);
								$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

								// START: LOG DETAILS
								$details = $userName . " has tagged " . $taggedUserName . " to delegate " . $taskTitle . ".";

								$logData = array (
									'LOGDETAILS' => $details,
									'TIMESTAMP' => date('Y-m-d H:i:s'),
									'projects_PROJECTID' => $projectID
								);

								$this->model->addToProjectLogs($logData);
								// END: LOG DETAILS

								//START: Notifications
								$details = "A new project has been created. " .  $userName . " has tagged you to delegate " . $taskTitle . " in " . $projectTitle . ".";
								$notificationData = array(
									'users_USERID' => $deptHead,
									'DETAILS' => $details,
									'TIMESTAMP' => date('Y-m-d H:i:s'),
									'status' => 'Unread',
									'projects_PROJECTID' => $projectID,
									'tasks_TASKID' => $a,
									'TYPE' => '2'
								);

								$this->model->addNotification($notificationData);
								// END: Notification

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
		$data['tasks'] = $this->model->getAllTasksByIDRole0($id);
		$data['users'] = $this->model->getAllUsers();
		$data['departments'] = $this->model->getAllDepartments();

		$sDate = date_create($data['project']['PROJECTSTARTDATE']);
		$eDate = date_create($data['project']['PROJECTENDDATE']);
		$diff = date_diff($eDate, $sDate, true);
		$dateDiff = $diff->format('%R%a');

		$data['dateDiff'] = $dateDiff;

		// $this->load->view("dashboard", $data);
		// redirect('controller/projectGantt');
		$this->load->view("addDependencies", $data);
	}

	public function projectGantt()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$id = $this->input->post("project_ID");
			$archives =$this->input->post("archives");
			$rfc =$this->input->post("rfc");
			$userRequest =$this->input->post("userRequest");
			$myTasks =$this->input->post("myTasks");
			$templates =$this->input->post("templates");
			$dashboard =$this->input->post("dashboard");
			$templateProjectGantt = $this->input->post("templateProjectGantt");
			$monitorTasks =$this->input->post("monitorTasks");

			$data['isTemplate'] = $this->model->checkIfTemplate($id);

			// DASHBOARD
			if (isset($dashboard))
			{
				$dashboard =$this->input->post("dashboard");
				$this->session->set_flashdata('dashboard', $dashboard);

				if (isset($rfc))
				{
					$rfc =$this->input->post("rfc");
					$this->session->set_flashdata('rfc', $rfc);
					$requestID = $this->input->post("request_ID");
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
							$filter = "(users.usertype_USERTYPEID = '3' &&  users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."')
							|| users.users_SUPERVISORS = '" . $_SESSION['USERID'] ."' && users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
							break;

						default:
							$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";
							break;
					}
					$data['departments'] = $this->model->getAllDepartments();
					$data['deptEmployees'] = $this->model->getAllUsersByUserType($filter);
					$data['wholeDept'] = $this->model->getAllUsersByUserType($filter);
					$data['projectCountR'] = $this->model->getProjectCount();
					$data['taskCountR'] = $this->model->getTaskCount();
					$data['projectCount'] = $this->model->getProjectCount($data['changeRequest']['departments_DEPARTMENTID']);
					$data['taskCount'] = $this->model->getTaskCount($data['changeRequest']['departments_DEPARTMENTID']);
				}
			}

			// ARCHIVES
			elseif (isset($archives))
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
						$filter = "users.departments_DEPARTMENTID = '". $data['changeRequest']['departments_DEPARTMENTID'] ."'";
						break;

					case '4':
						$filter = "users.users_SUPERVISORS = '" . $_SESSION['USERID'] ."'";
						break;

					default:
						$filter = "users.departments_DEPARTMENTID = '". $data['changeRequest']['departments_DEPARTMENTID'] ."'";
						break;
				}
				$data['departments'] = $this->model->getAllDepartments();
				$data['deptEmployees'] = $this->model->getAllUsersByUserType($filter);
				$data['wholeDept'] = $this->model->getAllUsersByDepartment($data['changeRequest']['departments_DEPARTMENTID']);
				$data['projectCount'] = $this->model->getProjectCount();
				$data['taskCount'] = $this->model->getTaskCount();
			}
			elseif (isset($myTasks))
			{
				$mytasks = $this->input->post("myTasks");
				$this->session->set_flashdata('myTasks', $mytasks);
			}
			elseif (isset($templates))
			{
				$templates = $this->input->post("templates");
				$this->session->set_flashdata('templates', $templates);
			}
			elseif (isset($userRequest))
			{
				$userRequest = $this->input->post("userRequest");
				$requestID = $this->input->post("request_ID");
				$data['changeRequest'] = $this->model->getChangeRequestbyID($requestID);
				$this->session->set_flashdata('userRequest', $userRequest);
			}
			elseif (isset($templateProjectGantt))
			{
				$templateProjectGantt = $this->input->post("templateProjectGantt");
				$this->session->set_flashdata('templateProjectGantt', $templateProjectGantt);
			}
			elseif (isset($monitorTasks))
			{
				$templateProjectGantt = $this->input->post("monitorTasks");
				$this->session->set_flashdata('monitorTasks', $monitorTasks);
			}

			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['ganttData'] = $this->model->getAllProjectTasksGroupByTaskID($id);
			$data['dependencies'] = $this->model->getDependenciesByProject($id);
			$data['users'] = $this->model->getAllUsers();

			$data['responsible'] = $this->model->getAllResponsibleByProject($id);
			$data['accountable'] = $this->model->getAllAccountableByProject($id);
			$data['consulted'] = $this->model->getAllConsultedByProject($id);
			$data['informed'] = $this->model->getAllInformedByProject($id);

			$data['employeeCompleteness'] = $this->model->compute_completeness_employeeByProject($_SESSION['USERID'], $id);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employeeByProject($_SESSION['USERID'], $id);
			$data['projectCompleteness'] = $this->model->compute_completeness_project($id);
			$data['projectTimeliness'] = $this->model->compute_timeliness_project($id);

			$this->load->view("projectGantt", $data);
			// $this->load->view("gantt2", $data);
		}
	}

	public function projectDocuments()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			$id = $this->input->post("project_ID");
			$this->session->set_flashdata('projectID', $id);

			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['departments'] = $this->model->getAllDepartmentsByProject($id);
			$data['documentsByProject'] = $this->model->getAllDocumentsByProject($id);
			$data['documentAcknowledgement'] = $this->model->getDocumentsForAcknowledgement($id, $_SESSION['USERID']);
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

						if ($dependencies != NULL)
						{
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
						}
						// echo "<br>";
					}
				}
			}
		}

		$data['projectProfile'] = $this->model->getProjectByID($id);
		$data['ganttData'] = $this->model->getAllProjectTasksGroupByTaskID($id);
		$data['dependencies'] = $this->model->getDependenciesByProject($id);
		$data['users'] = $this->model->getAllUsers();
		$data['responsible'] = $this->model->getAllResponsibleByProject($id);
		$data['accountable'] = $this->model->getAllAccountableByProject($id);
		$data['consulted'] = $this->model->getAllConsultedByProject($id);
		$data['informed'] = $this->model->getAllInformedByProject($id);

		$data['employeeCompleteness'] = $this->model->compute_completeness_employeeByProject($_SESSION['USERID'], $id);
		$data['employeeTimeliness'] = $this->model->compute_timeliness_employeeByProject($_SESSION['USERID'], $id);
		$data['projectCompleteness'] = $this->model->compute_completeness_project($id);
		$data['projectTimeliness'] = $this->model->compute_timeliness_project($id);

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
			$this->load->view('restrictedAccess');
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
					$filter = "((usertype_USERTYPEID = '4' || usertype_USERTYPEID = '5') && users.departments_DEPARTMENTID = '$deptID' && REQUESTSTATUS = 'Pending')
					|| (projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending')"; break;
				case '5': // if PO is logged in
					$filter = "projects.users_USERID = '$userID' && REQUESTSTATUS = 'Pending'"; break;
				default:
					$filter = "usertype_USERTYPEID = '3' && REQUESTSTATUS = 'Pending'"; break;
			}

			$data['changeRequests'] = $this->model->getChangeRequestsForApproval($filter, $_SESSION['USERID']);
			$data['userRequests'] = $this->model->getChangeRequestsByUser($_SESSION['USERID']);
			$this->load->view("rfc", $data);
		}
	}

	public function notifRedirect(){

		$projectID = $this->input->post("projectID");
		$taskID = $this->input->post("taskID");
		$type = $this->input->post("type");
		$notifID = $this->input->post("notifID");

		$statusArray = array(
				"status" => "Read"
		);

		$this->model->updateNotification($notifID, $statusArray);

		$data['notifications'] = $this->model->getAllNotificationsByUser();
		$this->session->set_userdata('notifications', $data['notifications']);

		if ($type == 2){ // taskDelegate

			$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";

			$data['delegateTasksByProject'] = $this->model->getAllProjectsToEditByUser($_SESSION['USERID'], "projects_PROJECTID");
			$data['delegateTasksByMainActivity'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID'], "1");
			$data['delegateTasksBySubActivity'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID'], "2");
			$data['delegateTasks'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID']);
			$data['departments'] = $this->model->getAllDepartments();
			$data['users'] = $this->model->getAllUsers();
			$data['wholeDept'] = $this->model->getAllUsersByDepartment($_SESSION['departments_DEPARTMENTID']);
			$data['projectCount'] = $this->model->getProjectCount();
			$data['taskCount'] = $this->model->getTaskCount();

			$this->load->view("taskDelegate", $data);

		} else if ($type == 3){ //taskTodo

			$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);

			$this->load->view("taskTodo", $data);

		} else if ($type == 4){ // taskMonitor

			$data['allOngoingACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Ongoing");
			$data['uniqueOngoingACItasks'] = $this->model->getUniqueACITasksByUser($_SESSION['USERID'], "Ongoing");

			$data['allCompletedACItasks'] = $this->model->getAllACITasksByUser($_SESSION['USERID'], "Complete");
			$data['uniqueCompletedACItasks'] = $this->model->getUniqueACITasksByUser($_SESSION['USERID'], "Complete");

			$this->load->view("taskMonitor", $data);

		} else if ($type == 5){ // projectDocuments

			$data['projectProfile'] = $this->model->getProjectByID($projectID);
			$data['departments'] = $this->model->getAllDepartmentsByProject($projectID);
			$data['documentsByProject'] = $this->model->getAllDocumentsByProject($projectID);
			$data['documentAcknowledgement'] = $this->model->getDocumentsForAcknowledgement($projectID, $_SESSION['USERID']);
			$data['users'] = $this->model->getAllUsersByProject($projectID);

			$this->load->view("projectDocuments", $data);

		} else if ($type == 6){ // rfc

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
				default:
					$filter = "usertype_USERTYPEID = '3' && REQUESTSTATUS = 'Pending'"; break;
			}

			$data['changeRequests'] = $this->model->getChangeRequestsForApproval($filter, $_SESSION['USERID']);
			$data['userRequests'] = $this->model->getChangeRequestsByUser($_SESSION['USERID']);
			$this->load->view("rfc", $data);

		} else { // projectGantt

			$data['projectProfile'] = $this->model->getProjectByID($projectID);
			$data['ganttData'] = $this->model->getAllProjectTasksGroupByTaskID($projectID);
			$data['dependencies'] = $this->model->getDependenciesByProject($projectID);
			$data['users'] = $this->model->getAllUsers();

			$data['responsible'] = $this->model->getAllResponsibleByProject($projectID);
			$data['accountable'] = $this->model->getAllAccountableByProject($projectID);
			$data['consulted'] = $this->model->getAllConsultedByProject($projectID);
			$data['informed'] = $this->model->getAllInformedByProject($projectID);

			$data['employeeCompleteness'] = $this->model->compute_completeness_employeeByProject($_SESSION['USERID'], $projectID);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employeeByProject($_SESSION['USERID'], $projectID);
			$data['projectCompleteness'] = $this->model->compute_completeness_project($projectID);
			$data['projectTimeliness'] = $this->model->compute_timeliness_project($projectID);

			$this->load->view("projectGantt", $data);

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
		$templateTaskID = $this->input->post('templateTaskID');

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
				case 'Human Resource':
					$hrHead = $row['users_DEPARTMENTHEAD'];
					break;
				case 'Management Information System':
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

		date_default_timezone_set("Singapore");
		$currDate = date("Y-m-d");

		foreach ($title as $key=> $row)
		{
			if ($currDate >= $startDates[$key])
			{
				$tStatus = 'Ongoing';
			}

			else
			{
				$tStatus = 'Planning';
			}

			if (isset($templateTaskID[$key]))
			{
				$data = array(
	          'TASKTITLE' => $row,
	          'TASKSTARTDATE' => $startDates[$key],
	          'TASKENDDATE' => $endDates[$key],
	          'TASKSTATUS' => $tStatus,
	          'CATEGORY' => '1',
	          'projects_PROJECTID' => $id,
						'TEMPLATETASKID' => $templateTaskID[$key]
	      );
			}

			else
			{
				$data = array(
	          'TASKTITLE' => $row,
	          'TASKSTARTDATE' => $startDates[$key],
	          'TASKENDDATE' => $endDates[$key],
	          'TASKSTATUS' => $tStatus,
	          'CATEGORY' => '1',
	          'projects_PROJECTID' => $id
	      );
			}

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
									case 'Marketing':
										$deptHead = $mktHead;
										break;
									case 'Finance':
										$deptHead = $finHead;
										break;
									case 'Procurement':
										$deptHead = $proHead;
										break;
									case 'Human Resource':
										$deptHead = $hrHead;
										break;
									case 'Management Information System':
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

								if ($value == 'All')
								{
									foreach ($departments as $deptKey => $dept)
									{
										if ($dept['DEPARTMENTNAME'] != 'Executive')
										{
											$data = array(
													'ROLE' => '0',
													'users_USERID' => $dept['users_DEPARTMENTHEAD'],
													'tasks_TASKID' => $a,
													'STATUS' => 'Current'
											);

											// ENTER INTO RACI
											$result = $this->model->addToRaci($data);

											// START OF LOGS/NOTIFS
											$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

											$taskDetails = $this->model->getTaskByID($a);
											$taskTitle = $taskDetails['TASKTITLE'];

											$projectID = $taskDetails['projects_PROJECTID'];
											$projectDetails = $this->model->getProjectByID($projectID);
											$projectTitle = $projectDetails['PROJECTTITLE'];

											$userDetails = $this->model->getUserByID($dept['users_DEPARTMENTHEAD']);
											$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

											// START: LOG DETAILS
											$details = $userName . " has tagged " . $taggedUserName . " to delegate Main Activity - " . $taskTitle . ".";

											$logData = array (
												'LOGDETAILS' => $details,
												'TIMESTAMP' => date('Y-m-d H:i:s'),
												'projects_PROJECTID' => $projectID
											);

											$this->model->addToProjectLogs($logData);
											// END: LOG DETAILS

											//START: Notifications
											$details = "A new project has been created. " . $userName . " has tagged you to delegate Main Activity - " . $taskTitle . " in " . $projectTitle . ".";
											$notificationData = array(
												'users_USERID' => $dept['users_DEPARTMENTHEAD'],
												'DETAILS' => $details,
												'TIMESTAMP' => date('Y-m-d H:i:s'),
												'status' => 'Unread',
												'projects_PROJECTID' => $projectID,
												'tasks_TASKID' => $a,
												'TYPE' => '2'
											);

											$this->model->addNotification($notificationData);
											// END: Notification
										}
									}
								}

								else
								{
									$data = array(
											'ROLE' => '0',
											'users_USERID' => $deptHead,
											'tasks_TASKID' => $a,
											'STATUS' => 'Current'
									);

									// ENTER INTO RACI
									$result = $this->model->addToRaci($data);

									// START OF LOGS/NOTIFS
									$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

									$taskDetails = $this->model->getTaskByID($a);
									$taskTitle = $taskDetails['TASKTITLE'];

									$projectID = $taskDetails['projects_PROJECTID'];
									$projectDetails = $this->model->getProjectByID($projectID);
									$projectTitle = $projectDetails['PROJECTTITLE'];

									$userDetails = $this->model->getUserByID($deptHead);
									$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

									// START: LOG DETAILS
									$details = $userName . " has tagged " . $taggedUserName . " to delegate Main Activity - " . $taskTitle . ".";

									$logData = array (
										'LOGDETAILS' => $details,
										'TIMESTAMP' => date('Y-m-d H:i:s'),
										'projects_PROJECTID' => $projectID
									);

									$this->model->addToProjectLogs($logData);
									// END: LOG DETAILS

									//START: Notifications
									$details = "A new project has been created. " . $userName . " has tagged you to delegate Main Activity - " . $taskTitle . " in " . $projectTitle . ".";
									$notificationData = array(
										'users_USERID' => $deptHead,
										'DETAILS' => $details,
										'TIMESTAMP' => date('Y-m-d H:i:s'),
										'status' => 'Unread',
										'projects_PROJECTID' => $projectID,
										'tasks_TASKID' => $a,
										'TYPE' => '2'
									);

									$this->model->addNotification($notificationData);
									// END: Notification
								}
							}
							// echo "<br>";
						}
					}
				}
			}
		}

		$startDate = $this->model->getProjectByID($id);
		date_default_timezone_set("Singapore");
		$currDate = date("Y-m-d");

		if ($currDate >= $startDate['PROJECTSTARTDATE'])
		{
			$status = array(
				"PROJECTSTATUS" => "Ongoing");
		}

		else
		{
			$status = array(
				"PROJECTSTATUS" => "Planning");
		}

		$changeStatues = $this->model->updateProjectStatusPlanning($id, $status);

			$data['project'] = $this->model->getProjectByID($id);
			$data['tasks'] = $this->model->getAllProjectTasks($id);
			$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
			$data['users'] = $this->model->getAllUsers();
			$data['departments'] = $this->model->getAllDepartments();

			$sDate = date_create($data['project']['PROJECTSTARTDATE']);
			$eDate = date_create($data['project']['PROJECTENDDATE']);
			$diff = date_diff($eDate, $sDate, true);
			$dateDiff = $diff->format('%R%a');

			$data['dateDiff'] = $dateDiff;

			$templates = $this->input->post('templates');

			if (isset($templates))
			{
				$this->session->set_flashdata('templates', $templates);

				$data['templateProject'] = $this->model->getProjectByID($templates);
				$data['templateAllTasks'] = $this->model->getAllProjectTasks($templates);
				$data['templateGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($templates);
				$data['templateMainActivity'] = $this->model->getAllMainActivitiesByID($templates);
				$data['templateSubActivity'] = $this->model->getAllSubActivitiesByID($templates);
				$data['templateTasks'] = $this->model->getAllTasksByIDRole1($templates);
				$data['templateRaci'] = $this->model->getRaci($templates);
				$data['templateUsers'] = $this->model->getAllUsers();
			}

			// $this->output->enable_profile(TRUE);
			$this->load->view('addSubActivities', $data);
		}

		public function editMainActivity()
		{
			// GET PROJECT ID
			$id = $this->input->post("project_ID");

			// echo $id;

			// GET ARRAY OF INPUTS FROM VIEW
			$title = $this->input->post('title');
			$startDates = $this->input->post('taskStartDate');
			$endDates = $this->input->post('taskEndDate');
			$department = $this->input->post("department");
			$rowNum = $this->input->post('row');
			$taskID = $this->input->post('taskid');

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
					case 'Human Resource':
						$hrHead = $row['users_DEPARTMENTHEAD'];
						break;
					case 'Management Information System':
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

				if (isset($taskID[$key]))
				{
					$addedTask[] = $this->model->editProjectTask($taskID[$key], $data);
				}

				else
				{
					$addedTask[] = $this->model->addTasksToProject($data);

				}
			}



			// TESTING
			foreach ($addedTask as $aKey=> $a)
			{
				foreach ($rowNum as $rKey => $row)
				{
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
										case 'Human Resource':
											$deptHead = $hrHead;
											break;
										case 'Management Information System':
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
											'ROLE' => '0',
											'users_USERID' => $deptHead,
											'tasks_TASKID' => $a,
											'STATUS' => 'Current'
									);

									$status = array(
										'STATUS' => 'Changed'
									);

									// ENTER INTO RACI
									if (isset($a))
									{
										$updateStatus = $this->model->updateRaciStatus($a, $status);
									}

									$result = $this->model->addToRaci($data);
								}
								// echo "<br>";
							}
						}
					}
				}
			}

			$status = array(
				"PROJECTSTATUS" => "Planning");

			$changeStatues = $this->model->updateProjectStatusPlanning($id, $status);

				$data['project'] = $this->model->getProjectByID($id);
				$data['tasks'] = $this->model->getAllProjectTasks($id);
				$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
				$data['users'] = $this->model->getAllUsers();
				$data['departments'] = $this->model->getAllDepartments();

				$sDate = date_create($data['project']['PROJECTSTARTDATE']);
				$eDate = date_create($data['project']['PROJECTENDDATE']);
				$diff = date_diff($eDate, $sDate, true);
				$dateDiff = $diff->format('%R%a');

				$data['dateDiff'] = $dateDiff;

				$templates = $this->input->post('templates');

				if (isset($templates))
				{
					$this->session->set_flashdata('templates', $templates);

					$data['templateProject'] = $this->model->getProjectByID($templates);
					$data['templateAllTasks'] = $this->model->getAllProjectTasks($templates);
					$data['templateGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($templates);
					$data['templateMainActivity'] = $this->model->getAllMainActivitiesByID($templates);
					$data['templateSubActivity'] = $this->model->getAllSubActivitiesByID($templates);
					$data['templateTasks'] = $this->model->getAllTasksByIDRole1($templates);
					$data['templateRaci'] = $this->model->getRaci($templates);
					$data['templateUsers'] = $this->model->getAllUsers();
				}

				// $this->output->enable_profile(TRUE);
				// $this->load->view('addSubActivities', $data);
			}

		// ADDS SUB ACTIVITIES TO MAIN ACTIVITIES OF PROJECT
		public function addSubActivities()
		{
		  $id = $this->input->post('project_ID');

		  $parent = $this->input->post('mainActivity_ID');
		  $title = $this->input->post('title');
		  $startDates = $this->input->post('taskStartDate');
		  $endDates = $this->input->post('taskEndDate');
			$department = $this->input->post("department");
			$rowNum = $this->input->post('row');
			$templateTaskID = $this->input->post('templateTaskID');

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
		      case 'Human Resource':
		        $hrHead = $row['users_DEPARTMENTHEAD'];
		        break;
		      case 'Management Information System':
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

			date_default_timezone_set("Singapore");
			$currDate = date("Y-m-d");

		  foreach ($title as $key=> $row)
		  {
				if ($currDate >= $startDates[$key])
				{
					$tStatus = 'Ongoing';
				}

				else
				{
					$tStatus = 'Planning';
				}

				if (isset($templateTaskID[$key]))
				{
					$data = array(
		          'TASKTITLE' => $row,
		          'TASKSTARTDATE' => $startDates[$key],
		          'TASKENDDATE' => $endDates[$key],
		          'TASKSTATUS' => $tStatus,
		          'CATEGORY' => '2',
		          'projects_PROJECTID' => $id,
		          'tasks_TASKPARENT' => $parent[$key],
							'TEMPLATETASKID' => $templateTaskID[$key]
		      );
				}

				else
				{
					$data = array(
		          'TASKTITLE' => $row,
		          'TASKSTARTDATE' => $startDates[$key],
		          'TASKENDDATE' => $endDates[$key],
		          'TASKSTATUS' => $tStatus,
		          'CATEGORY' => '2',
		          'projects_PROJECTID' => $id,
		          'tasks_TASKPARENT' => $parent[$key]
		      );
				}

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
	 									case 'Human Resource':
	 										$deptHead = $hrHead;
	 										break;
	 									case 'Management Information System':
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
	 										'ROLE' => '0',
	 										'users_USERID' => $deptHead,
	 										'tasks_TASKID' => $a,
											'STATUS' => 'Current'
	 								);

	 								// ENTER INTO RACI
	 								$result = $this->model->addToRaci($data);

									// START OF LOGS/NOTIFS
									$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

									$taskDetails = $this->model->getTaskByID($a);
									$taskTitle = $taskDetails['TASKTITLE'];

									$projectID = $taskDetails['projects_PROJECTID'];
									$projectDetails = $this->model->getProjectByID($projectID);
									$projectTitle = $projectDetails['PROJECTTITLE'];

									$userDetails = $this->model->getUserByID($deptHead);
									$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];

									// START: LOG DETAILS
									$details = $userName . " has tagged " . $taggedUserName . " to delegate Sub Activity - " . $taskTitle . ".";

									$logData = array (
										'LOGDETAILS' => $details,
										'TIMESTAMP' => date('Y-m-d H:i:s'),
										'projects_PROJECTID' => $projectID
									);

									$this->model->addToProjectLogs($logData);
									// END: LOG DETAILS

									//START: Notifications
									$details = "A new project has been created. " . $userName . " has tagged you to delegate Sub Activity - " . $taskTitle . " in " . $projectTitle . ".";
									$notificationData = array(
										'users_USERID' => $deptHead,
										'DETAILS' => $details,
										'TIMESTAMP' => date('Y-m-d H:i:s'),
										'status' => 'Unread',
										'projects_PROJECTID' => $projectID,
										'tasks_TASKID' => $a,
										'TYPE' => '3'
									);

									$this->model->addNotification($notificationData);
									// END: Notification

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
			$diff = date_diff($eDate, $sDate, true);
			$dateDiff = $diff->format('%R%a');

		  $data['dateDiff'] = $dateDiff;

			$templates = $this->input->post('templates');

			if (isset($templates))
			{
				$this->session->set_flashdata('templates', $templates);

				$data['templateProject'] = $this->model->getProjectByID($templates);
				$data['templateAllTasks'] = $this->model->getAllProjectTasks($templates);
				$data['templateGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($templates);
				$data['templateMainActivity'] = $this->model->getAllMainActivitiesByID($templates);
				$data['templateSubActivity'] = $this->model->getAllSubActivitiesByID($templates);
				$data['templateTasks'] = $this->model->getAllTasksByIDRole1($templates);
				$data['templateRaci'] = $this->model->getRaci($templates);
				$data['templateUsers'] = $this->model->getAllUsers();
				$data['templateSubActTaskID'] = $this->model->getSubActivityTaskID($templates);
			}

			// foreach ($data['templateSubActTaskID'] as $row)
			// {
			// 	echo $row['TASKID'] . "<br>";
			// }

		  // $this->load->view("dashboard", $data);
		  // redirect('controller/projectGantt');
		  $this->load->view("addTasks", $data);
		}

		public function archiveProject()
		{
			$id = $this->input->post("project_ID");

			$data = array(
				'PROJECTSTATUS' => 'Archived'
			);

			$result = $this->model->archiveProject($id, $data);

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$projectDetails = $this->model->getProjectByID($id);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS
			$details = $userName . " has archived this project.";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $id
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			if ($result)
			{
				$data['archives'] = $this->model->getAllProjectArchives();

				$this->load->view("archives", $data);
			}
		}

		public function parkProject()
		{
			$id = $this->input->post("project_ID");

			$data = array(
				'PROJECTSTATUS' => 'Parked'
			);

			$result = $this->model->parkProject($id, $data);

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$projectDetails = $this->model->getProjectByID($id);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS
			$details = $userName . " has parked this project.";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $id
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			if ($result)
			{
				$this->myProjects();
			}
		}

		public function templateProject()
		{
			$id = $this->input->post("project_ID");

			$project = $this->model->getProjectByID($id);

			// templates.PROJECTID == TEMPLATEID
			// templates.PROJECTSTATUS == projects.PROJECTID

			$data = array(
				'PROJECTTITLE' => $project['PROJECTTITLE'] . " Template",
				'PROJECTSTARTDATE' => $project['PROJECTSTARTDATE'],
				'PROJECTENDDATE' => $project['PROJECTACTUALENDDATE'],
				'PROJECTDESCRIPTION' => $project['PROJECTDESCRIPTION'],
				'projects_PROJECTID' => $id,
				'users_USERID' => $_SESSION['USERID']
			);

			$result = $this->model->templateProject($data);

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$projectDetails = $this->model->getProjectByID($id);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS
			$details = $userName . " has made this project a template.";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $id
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			//
			if ($result)
			{
				$data['templates'] = $this->model->getAllTemplates();

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
					foreach ($this->model->getAllUsersByProjectByDepartment($id, $departmentRow) as $userIDByDepartment) {
						$acknowledgementData = array (
							'documents_DOCUMENTID' => $documentID,
							'users_ACKNOWLEDGEDBY' => $userIDByDepartment['users_USERID']
						);

						// INSERT IN DOCUMENT ACKNOWLEDGMENT TABLE
						$this->model->addToDocumentAcknowledgement($acknowledgementData);

						// START: Notification
						$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
						$projectTitle = $projectID['PROJECTTITLE'];
						$details = $userName . " has uploaded " . $fileName . " to the project " .  $projectTitle . " and needs your acknowledgement.";

						$notificationData = array(
							'users_USERID' => $userIDByDepartment['users_USERID'],
							'DETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'status' => 'Unread',
							'projects_PROJECTID' => $id,
							'TYPE' => '5'
						);

						$this->model->addNotification($notificationData);
						// END: Notification

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
				$allUsers = $this->model->getAllUsersByProject($id);

				// START: Notification
				$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
				$details = $userName . " has uploaded " . $fileName . ".";

				foreach($allUsers as $user){
					if($user['users_USERID'] != $_SESSION['USERID']){

						$notificationData = array(
							'users_USERID' => $user['users_USERID'],
							'DETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'status' => 'Unread',
							'projects_PROJECTID' => $id,
							'TYPE' => '5'
						);

						$this->model->addNotification($notificationData);
						// END: Notification

					}
				}
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

						// START: Notification
						$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
						$projectTitle = $projectID['PROJECTTITLE'];
						$details = $userName . " has uploaded " . $fileName . " to the project " .  $projectTitle . " and needs your acknowledgement.";

						$notificationData = array(
							'users_USERID' => $userIDByDepartment['users_USERID'],
							'DETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'status' => 'Unread',
							'projects_PROJECTID' => $id,
							'TYPE' => '5'
						);

						$this->model->addNotification($notificationData);
						// END: Notification

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
		$data['departments'] = $this->model->getAllDepartmentsByProject($id);
		$data['documentsByProject'] = $this->model->getAllDocumentsByProject($id);
		$data['documentAcknowledgement'] = $this->model->getDocumentsForAcknowledgement($id, $_SESSION['USERID']);
		$data['users'] = $this->model->getAllUsersByProject($id);

		$this->load->view("projectDocuments", $data);
	}

	public function acknowledgeDocument()
	{
		//GET DOCUMENT ID
		$documentID = $this->input->post("documentID");
		$projectID = $this->input->post("projectID");
		$dashboard = $this->input->post("fromWhere");
		$fileName = $this->input->post("fileName");

		$currentDate = date('Y-m-d');

		$result = $this->model->updateDocumentAcknowledgement($documentID, $_SESSION['USERID'], $currentDate);

		// START: LOG DETAILS
		$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
		$details = $userName . " has acknowledged " . $fileName;

		$logData = array (
			'LOGDETAILS' => $details,
			'TIMESTAMP' => date('Y-m-d H:i:s'),
			'projects_PROJECTID' => $projectID
		);

		$this->model->addToProjectLogs($logData);
		// END: LOG DETAILS

		if ($result)
		{
			if(isset($dashboard)){
				redirect('controller/dashboard');
			} else {
				// redirect('controller/projectDocuments');

				$data['projectProfile'] = $this->model->getProjectByID($projectID);
				$data['departments'] = $this->model->getAllDepartmentsByProject($projectID);
				$data['documentsByProject'] = $this->model->getAllDocumentsByProject($projectID);
				$data['documentAcknowledgement'] = $this->model->getDocumentsForAcknowledgement($projectID, $_SESSION['USERID']);
				$data['users'] = $this->model->getAllUsersByProject($projectID);

				$this->load->view("projectDocuments", $data);
			}
		}
	}

	public function getAllNotificationsByUser()
	{
		$data['notification'] = $this->model->getAllNotificationsByUser();
		$data['notifications'] = $this->model->getAllNotificationsByUser();

		// $notifications = $this->model->getAllNotificationsByUser($sessionData['USERID']);
		$this->session->set_userdata('notifications', $data['notifications']);

		echo json_encode($data);
	}

	public function getAllTasksByUser()
	{
		$data['allTasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);

		$this->session->set_userdata('allTasks', $data['allTasks']);

		echo json_encode($data);
	}

	/******************** MY PROJECTS END ********************/

	public function myprojects2(){

		$data['completeness_departments'] = $this->model->getTimeliness_AllDepartments();


		$this->load->view("myprojects2", $data);
	}

	public function gantt2(){

		$filter = "tasks.TASKSTARTDATE"; // default
		$id = 1;

		// $data['ganttData'] = $this->model->getAllProjectTasks($id, $filter);
		// $data['dependencies'] = $this->model->getDependencies();

		$data['projectProfile'] = $this->model->getProjectByID($id);
		$data['ganttData'] = $this->model->getAllProjectTasksGroupByTaskID($id);
		$data['dependencies'] = $this->model->getDependenciesByProject($id);
		$data['users'] = $this->model->getAllUsers();
		$data['responsible'] = $this->model->getAllResponsibleByProject($id);
		$data['accountable'] = $this->model->getAllAccountableByProject($id);
		$data['consulted'] = $this->model->getAllConsultedByProject($id);
		$data['informed'] = $this->model->getAllInformedByProject($id);
		// $data['subActivityProgress'] = $this->model->getSubActivityProgress($id);

		$this->load->view("gantt2", $data);
	}

	public function changePassword()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('oldPass', 'Old Password', 'required'); // check if match with current password
		$this->form_validation->set_rules('newPass', 'New Password', 'required');

		$oldPass = $this->input->post('oldPass');
		$newPass = $this->input->post('newPass');
		$checker = $this->model->samePassword($oldPass);

		if(!$checker)
		{
			$this->session->set_flashdata('danger', 'alert');
			$this->session->set_flashdata('alertMessage', ' Current Password is Incorrect');
			redirect('controller/dashboard');
		}

		else
		{
			$this->form_validation->set_rules('confirmPass', 'Confirm Password', 'required|matches[newPass]'); //check na dapat same with new pass

			if ($this->form_validation->run() == FALSE)
			{
				$this->session->set_flashdata('danger', 'alert');
				$this->session->set_flashdata('alertMessage', ' Passwords do not match');
				redirect('controller/dashboard');
			}

			else
			{
				$this->form_validation->set_rules('newPass', 'New Password', 'required|min_length[6]');

				if ($this->form_validation->run() == FALSE)
				{
					$this->session->set_flashdata('danger', 'alert');
					$this->session->set_flashdata('alertMessage', ' Password too short');
					redirect('controller/dashboard');
				}

				else
				{
					$isSamePassword = $this->model->checkSamePassword($newPass);

					if ($isSamePassword)
					{
						$this->session->set_flashdata('danger', 'alert');
						$this->session->set_flashdata('alertMessage', ' New password must be different');
						redirect('controller/dashboard');
					}

					else
					{
						$data = array(
							'PASSWORD' => password_hash($newPass, PASSWORD_DEFAULT)
						);

						echo password_hash($newPass, PASSWORD_DEFAULT);

						// echo "<br>$2y$10$weMZc/mMJqs0HLU58WGfDeHr.SzSEBtDlEBxt3WOk/T3zM/zc25.S";

						$result = $this->model->updatePassword($data);

						if ($result)
						{
							$this->session->set_flashdata('success', 'alert');
							$this->session->set_flashdata('alertMessage', ' Password changed');
							redirect('controller/dashboard');
						}

						else
						{
							$this->session->set_flashdata('danger', 'alert');
							$this->session->set_flashdata('alertMessage', ' Error in changing password');
							redirect('controller/dashboard');
						}
					}
				}
			}
		}
	}

// DELETE THIS AFTER
	public function frame()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('restrictedAccess');
		}

		else
		{
			// $this->load->view("frame");
			$projectTimeliness = $this->model->compute_timeliness_projectByUser();
			$projectCompleteness = $this->model->compute_completeness_projectByUser();

			foreach ($projectCompleteness as $project) {
				echo "project id - " . $project['projects_PROJECTID'] . "<br>";
				echo "completeness - " . $project['completeness'] . "<br><br>";
			}
		}
	}

	// DELETE THIS draftedProjects
	public function importTest()
	{
		$this->load->view('importTest');
	}

	public function uploadData()
	{
	 $config['upload_path'] = './assets/uploads/templates';
	 $config['allowed_types'] = 'xlsx|csv|xls';
	 $config['max_size'] = '10000000';
	 $this->load->library('upload', $config);
	 $this->upload->initialize($config);

	 if (!$this->upload->do_upload('uploadFile'))
	 {
		 $error = array('error' => $this->upload->display_errors());

		 // $this->session->set_flashdata('danger', 'alert');
		 // $this->session->set_flashdata('alertMessage', 'File type is not allowed');
		 //
		 // redirect('controller/importTest');
	 }

	 else
	 {
		 $this->session->set_flashdata('success', 'alert');
		 $this->session->set_flashdata('alertMessage', ' Success');

		 redirect('controller/importTest');
	 }

		 if(!empty($error))
		 {
			 echo $error['error'];
		 }

		 // else
		 // {
			//  echo "sad<br>";
		 // }
	}

	public function getDelayEffect()
	{
		$taskID = $this->input->post("task_ID");
		$task = $this->model->getTaskByID($taskID);
		$taskPostReqs = $this->model->getPostDependenciesByTaskID($taskID);

		$affectedTasks = array();

		if(COUNT($taskPostReqs) > 0) // if there are post requisite tasks
		{
			$postReqsToAdjust = array();
			$postReqsToAdjust[] = $taskID; // add requested task to array
			$i = 0; // set counter
			while(COUNT($postReqsToAdjust) > 0) // loop while array is not empty/there are postreqs to check
			{
				$postReqs = $this->model->getPostDependenciesByTaskID($postReqsToAdjust[$i]); // get post reqs of current task

				if(COUNT($postReqs) > 0) // if there are post reqs found
				{
					foreach($postReqs as $postReq)
					{
						$startDate = $postReq['TASKSTARTDATE'];
						$endDate = $postReq['currDate'];

						if($endDate >= $startDate) //check if currTasks's end date will exceed the postreq's start date
						{
							if($postReq['TASKADJUSTEDSTARTDATE'] != null && $postReq['TASKADJUSTEDENDDATE'] != null)
								$taskDuration = $postReq['adjustedTaskDuration2'];
							elseif($postReq['TASKSTARTDATE'] != null && $postReq['TASKADJUSTEDENDDATE'] != null)
								$taskDuration = $postReq['adjustedTaskDuration1'];
							else
								$taskDuration = $postReq['initialTaskDuration'];

							if($postReq['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
								$currTaskEnd = $postReq['TASKENDDATE'];
							else
								$currTaskEnd = $postReq['TASKADJUSTEDENDDATE'];

							$new_start = date('Y-m-d', strtotime($endDate . ' +1 day')); // set start date to one day after enddate
							$new_end = date('Y-m-d', strtotime($new_start . ' +' . ($taskDuration-1) . ' day')); // set end date according to duration

							foreach($affectedTasks as $affectedTask)
							{
								if($affectedTask['id'] == $postReqsToAdjust[$i])
								{
									$new_start = date('Y-m-d', strtotime($affectedTask['newEndDate'] . ' +1 day'));
									$new_end = date('Y-m-d', strtotime($new_start . ' +' . ($taskDuration-1) . ' day'));
								}
							}

							$affectedTasks[] = array("id" => $postReq['TASKID'],
																		"taskTitle" => $postReq['TASKTITLE'],
																		"taskStatus" => $postReq['TASKSTATUS'],
								                    "startDate" => $postReq['TASKSTARTDATE'],
								                    "newStartDate" => $new_start,
								                    "endDate" => $currTaskEnd,
																		"newEndDate" => $new_end,
																		"responsible" => $postReq['FIRSTNAME'] . " " . $postReq['LASTNAME']);

						}
						array_push($postReqsToAdjust, $postReq['TASKID']); // save task to array for checking
					}
				}
				unset($postReqsToAdjust[$i]); // remove current task from array
				$i++; // increase counter
			}
		}

		// ARRAY CLEAN UP
		$index = 0;
		foreach($affectedTasks as $affectedTask1)
		{
			$doubleCount = 0;
			foreach($affectedTasks as $affectedTask2)
			{
				if($affectedTask1['id'] == $affectedTask2['id'])
				{
					$doubleCount++;
					if($doubleCount >= 2)
					{
						$affectedTasks[$index] = array("id" => null);
					}
				}
			}
			$index++;
		}

		echo json_encode($affectedTasks);
	}

}
