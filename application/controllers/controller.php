
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

				$notifications = $this->model->getAllNotificationsByUser();
				$this->session->set_userdata('notifications', $notifications);

				$filter = "raci.users_USERID= '". $_SESSION['USERID'] ."'";
				$taskCount = $this->model->getTaskCount($filter);
				$this->session->set_userdata('taskCount', $taskCount);
				echo $taskCount[0]['taskCount'] . "<br>";

				// foreach ($taskCount as $tcKey =>$tcRow)
				// {
				// 	echo $tcKey . " == " . $tcRow['taskCount'] . "<br>";
				// }

				$currentDate = date('Y-m-d');
				$this->model->updateTaskStatus();
				$this->model->updateProjectStatus();

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

				// check for project weekly progress
				$data['latestProgress'] = $this->model->getLatestWeeklyProgress();

				foreach($data['latestProgress'] as $latestProgressDetails){

					echo "<br> latest progress" . $latestProgressDetails['projects_PROJECTID'] . " " . $latestProgressDetails['datediff'] ."<br>";

					$isFound = $this->model->checkAssessmentProject($latestProgressDetails['projects_PROJECTID']);

					if(!$isFound){
						$completeness = $this->model->compute_completeness_project($latestProgressDetails['projects_PROJECTID']);
						$timeliness = $this->model->compute_timeliness_project($latestProgressDetails['projects_PROJECTID']);

						$progressData = array(
							'projects_PROJECTID' => $latestProgressDetails['projects_PROJECTID'],
							'DATE' => date('Y-m-d'),
							'COMPLETENESS' => $completeness['completeness'],
							'TIMELINESS' => $timeliness['timeliness']
						);
						$this->model->addAssessmentProject($progressData);
					}
				}

				// check for department assessment
				$data['latestAssessmentDepartment'] = $this->model->getLatestAssessmentDepartment();

				foreach($data['latestAssessmentDepartment'] as $latestAssessment){

					$isFound = $this->model->checkAssessmentDepartment($latestAssessment['departments_DEPARTMENTID']);

					if(!$isFound){

						$completeness = $this->model->compute_completeness_department($latestAssessment['departments_DEPARTMENTID']);
						$timeliness = $this->model->compute_timeliness_department($latestAssessment['departments_DEPARTMENTID']);

						$progressData = array(
							'departments_DEPARTMENTID' => $latestAssessment['departments_DEPARTMENTID'],
							'DATE' => date('Y-m-d'),
							'COMPLETENESS' => $completeness['completeness'],
							'TIMELINESS' => $timeliness['timeliness']
						);
						$this->model->addAssessmentDepartment($progressData);
					}
				}

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

			$data['templates'] = $this->model->getAllTemplates();
			$this->load->view("myProjects", $data);
		}
	}

	public function monitorTeam()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$deptID = $_SESSION['DEPARTMENTID'];

			$data['staff'] = $this->model->getAllUsersByDepartment($deptID);
			$data['projects'] = $this->model->getAllProjects();
			// $datta['projectCount'] = $this

			$this->load->view("monitorTeam", $data);
		}
	}

	public function monitorProject()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("monitorProject");
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
			$data['projectCountR'] = $this->model->getProjectCount($_SESSION['departments_DEPARTMENTID']);
			$data['taskCountR'] = $this->model->getTaskCount($_SESSION['departments_DEPARTMENTID']);
			$data['projectCount'] = $this->model->getProjectCount($filter);
			$data['taskCount'] = $this->model->getTaskCount($filter);

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
			$details = $taskTitle . " has been marked as done by " . $userName . " in " . $projectTitle . ".";

			// notify project owner
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

					$details = $taskTitle . " has been marked as done by " . $userName . " in " . $projectTitle . ".";

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

				// notify next ACI
				$ACIdata['ACI'] = $this->model->getACIbyTask($id);
				if($ACIdata['ACI'] != NULL) {

					foreach($ACIdata['ACI'] as $ACIusers){

						$details = $taskTitle . " has been completed by " . $userName . " in " . $projectTitle . ".";

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
					$details = "Main Activity - " . $taskTitle . " has been completed by " . $userName . " in " . $projectTitle . ".";

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

							$details = $taskTitle . " has been completed by " . $userName . " in " . $projectTitle . ".";

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
		$this->taskTodo();
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

			echo "<script>console.log('Delegate: " . $delegate . "');</script>";

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
			$details = $userName . " has marked " . $taggedUserName . " as responsible for " . $taskTitle . ".";

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
			$details = "You have been tagged as responsible for " . $taskTitle . " in " . $projectTitle . ".";

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
				$details = $userName . " has marked " . $taggedUserName . " as accountable for " . $taskTitle . ".";

				$logData = array (
					'LOGDETAILS' => $details,
					'TIMESTAMP' => date('Y-m-d H:i:s'),
					'projects_PROJECTID' => $projectID
				);

				$this->model->addToProjectLogs($logData);
				// END: LOG DETAILS

				// START: Notifications
				$details = "You have been tagged as accountable for " . $taskTitle . " in " . $projectTitle . ".";
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
				$details = $userName . " has marked " . $taggedUserName . " as consulted for " . $taskTitle . ".";

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
				$details = "You have been tagged as consulted for " . $taskTitle . " in " . $projectTitle . ".";
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
				$details = $userName . " has marked " . $taggedUserName . " as informed for " . $taskTitle . ".";

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
				$details = "You have been tagged as informed for " . $taskTitle . " in " . $projectTitle . ".";
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
			$details = $userName . " requested a change in performer for " . $taskTitle . " in " . $projectTitle . ".";
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
			$details = $userName . " requested a change in date/s for " . $taskTitle . ".";
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
		$details = $userName . " has " . $status . " change request for " . $taskTitle . ".";

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

			// if(!$this->input->post("responsibleEmp") && !$this->input->post("accountableDept[]") &&
			// 		!$this->input->post("accountableEmp[]") && !$this->input->post("consultedDept[]") &&
			// 		!$this->input->post("consultedEmp[]") && !$this->input->post("informedDept[]") &&
			// 		!$this->input->post("informedEmp[]")) // return to approver in tasks
			// {
			// 	$responsibleData = array(
			// 		'ROLE' => '1',
			// 		'users_USERID' => $_SESSION['USERID'],
			// 		'tasks_TASKID' =>	$taskID,
			// 		'STATUS' => 'Current'
			// 	);
			// 	$result = $this->model->addToRaci($responsibleData);
			//
			// }
			// else
			// {
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
					$details = $userName . " has delegated " . $taskTitle . " to " . $taggedUserName;

					$logData = array (
						'LOGDETAILS' => $details,
						'TIMESTAMP' => date('Y-m-d H:i:s'),
						'projects_PROJECTID' => $projectID
					);

					$this->model->addToProjectLogs($logData);
					// END: LOG DETAILS

					// START: Notifications
					$details = $taskTitle . " for " . $projectTitle . " has been assigned to you.";

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

				// // SAVE ACCOUNTABLE
				// if($this->input->post("accountableDept[]"))
				// {
				// 	foreach($this->input->post("accountableDept[]") as $deptID)
				// 	{
				// 		$accountableData = array(
				// 			'ROLE' => '2',
				// 			'users_USERID' => $deptID,
				// 			'tasks_TASKID' =>	$taskID,
				// 			'STATUS' => 'Current'
				// 		);
				// 		$this->model->addToRaci($accountableData);
				//
				// 		// START OF LOGS/NOTIFS
				// 		$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
				//
				// 		$taskDetails = $this->model->getTaskByID($taskID);
				// 		$taskTitle = $taskDetails['TASKTITLE'];
				//
				// 		$projectID = $taskDetails['projects_PROJECTID'];
				// 		$projectDetails = $this->model->getProjectByID($projectID);
				// 		$projectTitle = $projectDetails['PROJECTTITLE'];
				//
				// 		$userDetails = $this->model->getUserByID($deptID);
				// 		$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];
				//
				// 		// START: LOG DETAILS
				// 		$details = $userName . " has marked " . $taggedUserName . " as accountable for " . $taskTitle . ".";
				//
				// 		$logData = array (
				// 			'LOGDETAILS' => $details,
				// 			'TIMESTAMP' => date('Y-m-d H:i:s'),
				// 			'projects_PROJECTID' => $projectID
				// 		);
				//
				// 		$this->model->addToProjectLogs($logData);
				// 		// END: LOG DETAILS
				//
				// 		// START: Notifications
				// 		$details = "You have been tagged as accountable for " . $taskTitle . " in " . $projectTitle . ".";
				// 		$notificationData = array(
				// 			'users_USERID' => $deptID,
				// 			'DETAILS' => $details,
				// 			'TIMESTAMP' => date('Y-m-d H:i:s'),
				// 			'status' => 'Unread',
				// 			'projects_PROJECTID' => $projectID,
				// 			'tasks_TASKID' => $taskID,
				// 			'TYPE' => '4'
				// 		);
				//
				// 		$this->model->addNotification($notificationData);
				// 		// END: Notification
				// 	}
				// }

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
						$details = $userName . " has marked " . $taggedUserName . " as accountable for " . $taskTitle . ".";

						$logData = array (
							'LOGDETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'projects_PROJECTID' => $projectID
						);

						$this->model->addToProjectLogs($logData);
						// END: LOG DETAILS

						// START: Notifications
						$details = "You have been tagged as accountable for " . $taskTitle . " in " . $projectTitle . ".";
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

				// // SAVE CONSULTED
				// if($this->input->post("consultedDept[]"))
				// {
				// 	foreach($this->input->post("consultedDept[]") as $deptID)
				// 	{
				// 		$consultedData = array(
				// 			'ROLE' => '3',
				// 			'users_USERID' => $deptID,
				// 			'tasks_TASKID' =>	$taskID,
				// 			'STATUS' => 'Current'
				// 		);
				// 		$this->model->addToRaci($consultedData);
				//
				// 		// START OF LOGS/NOTIFS
				// 		$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
				//
				// 		$taskDetails = $this->model->getTaskByID($taskID);
				// 		$taskTitle = $taskDetails['TASKTITLE'];
				//
				// 		$projectID = $taskDetails['projects_PROJECTID'];
				// 		$projectDetails = $this->model->getProjectByID($projectID);
				// 		$projectTitle = $projectDetails['PROJECTTITLE'];
				//
				// 		$userDetails = $this->model->getUserByID($deptID);
				// 		$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];
				//
				// 		// START: LOG DETAILS
				// 		$details = $userName . " has marked " . $taggedUserName . " as consulted for " . $taskTitle . ".";
				//
				// 		$logData = array (
				// 			'LOGDETAILS' => $details,
				// 			'TIMESTAMP' => date('Y-m-d H:i:s'),
				// 			'projects_PROJECTID' => $projectID
				// 		);
				//
				// 		$this->model->addToProjectLogs($logData);
				// 		// END: LOG DETAILS
				//
				// 		// START: Notifications
				// 		$details = "You have been tagged as consulted for " . $taskTitle . " in " . $projectTitle . ".";
				// 		$notificationData = array(
				// 			'users_USERID' => $deptID,
				// 			'DETAILS' => $details,
				// 			'TIMESTAMP' => date('Y-m-d H:i:s'),
				// 			'status' => 'Unread',
				// 			'projects_PROJECTID' => $projectID,
				// 			'tasks_TASKID' => $taskID,
				// 			'TYPE' => '4'
				// 		);
				//
				// 		$this->model->addNotification($notificationData);
				// 		// END: Notification
				// 	}
				// }

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
						$details = $userName . " has marked " . $taggedUserName . " as consulted for " . $taskTitle . ".";

						$logData = array (
							'LOGDETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'projects_PROJECTID' => $projectID
						);

						$this->model->addToProjectLogs($logData);
						// END: LOG DETAILS

						// START: Notifications
						$details = "You have been tagged as consulted for " . $taskTitle . " in " . $projectTitle . ".";
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
				// if($this->input->post("informedDept[]"))
				// {
				// 	foreach($this->input->post("informedDept[]") as $deptID)
				// 	{
				// 		$informedData = array(
				// 			'ROLE' => '4',
				// 			'users_USERID' => $deptID,
				// 			'tasks_TASKID' =>	$taskID,
				// 			'STATUS' => 'Current'
				// 		);
				// 		$this->model->addToRaci($informedData);
				// 	}
				// 	// START OF LOGS/NOTIFS
				// 	$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
				//
				// 	$taskDetails = $this->model->getTaskByID($taskID);
				// 	$taskTitle = $taskDetails['TASKTITLE'];
				//
				// 	$projectID = $taskDetails['projects_PROJECTID'];
				// 	$projectDetails = $this->model->getProjectByID($projectID);
				// 	$projectTitle = $projectDetails['PROJECTTITLE'];
				//
				// 	$userDetails = $this->model->getUserByID($deptID);
				// 	$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];
				//
				// 	// START: LOG DETAILS
				// 	$details = $userName . " has marked " . $taggedUserName . " as informed for " . $taskTitle . ".";
				//
				// 	$logData = array (
				// 		'LOGDETAILS' => $details,
				// 		'TIMESTAMP' => date('Y-m-d H:i:s'),
				// 		'projects_PROJECTID' => $projectID
				// 	);
				//
				// 	$this->model->addToProjectLogs($logData);
				// 	// END: LOG DETAILS
				//
				// 	// START: Notifications
				// 	$details = "You have been tagged as informed for " . $taskTitle . " in " . $projectTitle . ".";
				// 	$notificationData = array(
				// 		'users_USERID' => $deptID,
				// 		'DETAILS' => $details,
				// 		'TIMESTAMP' => date('Y-m-d H:i:s'),
				// 		'status' => 'Unread',
				// 		'projects_PROJECTID' => $projectID,
				// 		'tasks_TASKID' => $taskID,
				// 		'TYPE' => '4'
				// 	);
				//
				// 	$this->model->addNotification($notificationData);
				// 	// END: Notification
				// }

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
						$details = $userName . " has marked " . $taggedUserName . " as informed for " . $taskTitle . ".";

						$logData = array (
							'LOGDETAILS' => $details,
							'TIMESTAMP' => date('Y-m-d H:i:s'),
							'projects_PROJECTID' => $projectID
						);

						$this->model->addToProjectLogs($logData);
						// END: LOG DETAILS

						// START: Notifications
						$details = "You have been tagged as informed for " . $taskTitle . " in " . $projectTitle . ".";
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
			// }
		} // end if appoved change Performer
		else // if approved change dates
		{
			$taskID = $this->input->post("task_ID");
			$changeRequest = $this->model->getChangeRequestbyID($requestID);
			$task = $this->model->getTaskByID($taskID);
			$taskPostReqs = $this->model->getPostDependenciesByTaskID($taskID);

			if($changeRequest['NEWSTARTDATE'] == "")
			{
				$taskData = array(
					'TASKADJUSTEDENDDATE' => $changeRequest['NEWENDDATE']
				);
			}
			else
			{
				$taskData = array(
					'TASKADJUSTEDSTARTDATE' => $changeRequest['NEWSTARTDATE'],
					'TASKADJUSTEDENDDATE' => $changeRequest['NEWENDDATE']
				);
			}
			$this->model->updateTaskDates($taskID, $taskData); //save adjusted dates of requested task

			if(COUNT($taskPostReqs) > 0) // if there are post requisite tasks
			{
				$postReqsToAdjust = array();
				$postReqsToAdjust[] = $taskID; // add requested task to array
				$i = 0; // set counter
				$endDate = $changeRequest['NEWENDDATE'];
				while(COUNT($postReqsToAdjust) > 0) // loop while array is not empty/there are postreqs to check
				{
					$currTask = $this->model->getTaskByID($postReqsToAdjust[$i]); // get current task data
					if($currTask['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
						$endDate = $currTask['TASKENDDATE'];
					else
						$endDate = $currTask['TASKADJUSTEDENDDATE'];

					$postReqs = $this->model->getPostDependenciesByTaskID($postReqsToAdjust[$i]); // get post reqs of current task
					if(COUNT($postReqs) > 0) // if there are post reqs found
					{
						foreach($postReqs as $postReq)
						{
							if($postReq['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
								$startDate = $postReq['TASKSTARTDATE'];
							else
								$startDate = $postReq['TASKADJUSTEDSTARTDATE'];

							if($endDate >= $startDate) //check if currTasks's end date will exceed the postreq's start date
							{
								if($postReq['TASKADJUSTEDSTARTDATE'] != null && $postReq['TASKADJUSTEDENDDATE'] != null)
									$taskDuration = $postReq['adjustedTaskDuration2'];
								elseif($postReq['TASKSTARTDATE'] != null && $postReq['TASKADJUSTEDENDDATE'] != null)
									$taskDuration = $postReq['adjustedTaskDuration1'];
								else
									$taskDuration = $postReq['initialTaskDuration'];

								$new_start = date('Y-m-d', strtotime($endDate . ' +1 day')); // set start date to one day after enddate
								$new_end = date('Y-m-d', strtotime($new_start . ' +' . ($taskDuration-1) . ' day')); // set end date according to duration

								$postTaskData = array(
									'TASKADJUSTEDSTARTDATE' => $new_start,
									'TASKADJUSTEDENDDATE' => $new_end
								);
								$this->model->updateTaskDates($postReq['TASKID'], $postTaskData); //save adjusted dates
							}
							array_push($postReqsToAdjust, $postReq['TASKID']); // save task to array for checking
						}
					}
					unset($postReqsToAdjust[$i]); // remove current task from array
					$i++; // increase counter
				}
			}
			else // if no post requisite tasks
			{
				if($changeRequest['NEWSTARTDATE'] <= 0) // if start date is not requested
				{
					$data = array(
						'TASKADJUSTEDENDDATE' => $changeRequest["NEWENDDATE"]
					);
				}
				else
				{
					$data = array(
						'TASKADJUSTEDSTARTDATE' => $changeRequest["NEWSTARTDATE"],
						'TASKADJUSTEDENDDATE' => $changeRequest["NEWENDDATE"]
					);
				}
				$this->model->updateTaskDates($taskID, $data);
			}

			// START OF LOGS/NOTIFS
			$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

			$taskDetails = $this->model->getTaskByID($taskID);
			$taskTitle = $taskDetails['TASKTITLE'];

			$projectID = $taskDetails['projects_PROJECTID'];
			$projectDetails = $this->model->getProjectByID($projectID);
			$projectTitle = $projectDetails['PROJECTTITLE'];

			// START: LOG DETAILS
			$details = $userName . " has adjusted the dates for " . $taskTitle . ".";

			$logData = array (
				'LOGDETAILS' => $details,
				'TIMESTAMP' => date('Y-m-d H:i:s'),
				'projects_PROJECTID' => $projectID
			);

			$this->model->addToProjectLogs($logData);
			// END: LOG DETAILS

			// START: Notifications
			$details = "Dates for " . $taskTitle . " in " . $projectTitle . " has been adjusted.";

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
						$details = "Dates for " . $taskTitle . " in " . $projectTitle . " has been adjusted.";

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

					$details = "Dates for " . $taskTitle . " in " . $projectTitle . " has been adjusted.";

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
			$id = $this->input->post("project_ID");
			$edit = $this->input->post("edit");

			// TEMPLATES
			if (isset($id))
			{
				if (isset($edit))
				{
					$this->session->set_flashdata('edit', $edit);
				}

				else
				{
					$this->session->set_flashdata('templates', $id);
				}

				$data['project'] = $this->model->getProjectByID($id);
				$data['allTasks'] = $this->model->getAllProjectTasks($id);
				$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
				$data['mainActivity'] = $this->model->getAllMainActivitiesByID($id);
				$data['subActivity'] = $this->model->getAllSubActivitiesByID($id);
				$data['tasks'] = $this->model->getAllTasksByID($id);

				$this->load->view("newProject", $data);
			}

			else
			{
				$this->load->view("newProject");
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
				$data['editTasks'] = $this->model->getAllTasksByID($edit);
				$data['editRaci'] = $this->model->getRaci($edit);
				$data['editUsers'] = $this->model->getAllUsers();
			}

			$this->session->set_flashdata('edit', $edit);

			$this->load->view('addTasks', $data);
		}

		else
		{
			// TODO PUT ALERT
			redirect('controller/contact');
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
			$data['projectProfile'] = $this->model->getProjectByID($id);
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
			$data['departmentCompleteness'] = $this->model->compute_completeness_departmentByProject($_SESSION['departments_DEPARTMENTID'], $id);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employeeByProject($_SESSION['USERID'], $id);
			$data['departmentTimeliness'] = $this->model->compute_timeliness_departmentByProject($_SESSION['departments_DEPARTMENTID'], $id);

			$this->load->view("teamGantt", $data);
		}
	}

	public function taskTodo()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
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
			$this->load->view('contact');
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
			$data['projectCount'] = $this->model->getProjectCount($filter);
			$data['taskCount'] = $this->model->getTaskCount($filter);

			$this->load->view("taskDelegate", $data);
		}
	}

	public function taskMonitor()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
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
		$data['projectCount'] = $this->model->getProjectCount($filter);
		$data['taskCount'] = $this->model->getTaskCount($filter);

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
			$this->load->view('contact');
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
			$this->load->view('contact');
		}

		else
		{
			$id = $this->input->post('project_ID');

			// echo $id;

			$data['project'] = $this->model->getProjectByID($id);
			$data['mainActivity'] = $this->model->getAllMainActivitiesByID($id);
			$data['subActivity'] = $this->model->getAllSubActivitiesByID($id);
			$data['tasks'] = $this->model->getAllTasksByID($id);
			$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);

			$this->load->view("projectSummary", $data);
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
				'users_USERID' => $_SESSION['USERID']
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
				$data['templateProject'] = $this->model->getProjectByID($templates);
				$data['templateAllTasks'] = $this->model->getAllProjectTasks($templates);
				$data['templateGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($templates);
				$data['templateMainActivity'] = $this->model->getAllMainActivitiesByID($templates);
				$data['templateSubActivity'] = $this->model->getAllSubActivitiesByID($templates);
				$data['templateTasks'] = $this->model->getAllTasksByID($templates);
				$data['templateRaci'] = $this->model->getRaci($templates);
				$data['templateUsers'] = $this->model->getAllUsers();
			}

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
								$details = $userName . " has marked " . $taggedUserName . " as responsible for " . $taskTitle . ".";

								$logData = array (
									'LOGDETAILS' => $details,
									'TIMESTAMP' => date('Y-m-d H:i:s'),
									'projects_PROJECTID' => $projectID
								);

								$this->model->addToProjectLogs($logData);
								// END: LOG DETAILS

								//START: Notifications
								$details = "You have been tagged as responsible " . $taskTitle . " in " . $projectTitle . ".";
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
		$data['allTasks'] = $this->model->getAllProjectTasks($id);
		$data['groupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($id);
		$data['mainActivity'] = $this->model->getAllMainActivitiesByID($id);
		$data['subActivity'] = $this->model->getAllSubActivitiesByID($id);
		$data['tasks'] = $this->model->getAllTasksByID($id);
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
			$this->load->view('contact');
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
					$data['projectCountR'] = $this->model->getProjectCount($filter);
					$data['taskCountR'] = $this->model->getTaskCount($filter);
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
				$data['projectCount'] = $this->model->getProjectCount($filter);
				$data['taskCount'] = $this->model->getTaskCount($filter);
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
			$this->load->view('contact');
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

		if ($type == 2){ // taskDelegate

			$filter = "users.departments_DEPARTMENTID = '". $_SESSION['departments_DEPARTMENTID'] ."'";

			$data['delegateTasksByProject'] = $this->model->getAllProjectsToEditByUser($_SESSION['USERID'], "projects_PROJECTID");
			$data['delegateTasksByMainActivity'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID'], "1");
			$data['delegateTasksBySubActivity'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID'], "2");
			$data['delegateTasks'] = $this->model->getAllActivitiesToEditByUser($_SESSION['USERID']);
			$data['departments'] = $this->model->getAllDepartments();
			$data['users'] = $this->model->getAllUsers();
			$data['wholeDept'] = $this->model->getAllUsersByDepartment($_SESSION['departments_DEPARTMENTID']);
			$data['projectCount'] = $this->model->getProjectCount($filter);
			$data['taskCount'] = $this->model->getTaskCount($filter);

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

			$data['employeeCompleteness'] = $this->model->compute_completeness_employeeByProject($_SESSION['USERID'], $id);
			$data['employeeTimeliness'] = $this->model->compute_timeliness_employeeByProject($_SESSION['USERID'], $id);
			$data['projectCompleteness'] = $this->model->compute_completeness_project($id);
			$data['projectTimeliness'] = $this->model->compute_timeliness_project($id);

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
								$details = $userName . " has tagged " . $taggedUserName . " to add and delegate tasks to " . $taskTitle . ".";

								$logData = array (
									'LOGDETAILS' => $details,
									'TIMESTAMP' => date('Y-m-d H:i:s'),
									'projects_PROJECTID' => $projectID
								);

								$this->model->addToProjectLogs($logData);
								// END: LOG DETAILS

								//START: Notifications
								$details = "You have been tagged to add and delegate tasks in " . $taskTitle . " in " . $projectTitle . ".";
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
				$data['templateTasks'] = $this->model->getAllTasksByID($templates);
				$data['templateRaci'] = $this->model->getRaci($templates);
				$data['templateUsers'] = $this->model->getAllUsers();
			}

			// $this->output->enable_profile(TRUE);
			$this->load->view('arrangeTasks', $data);
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
					$data['templateTasks'] = $this->model->getAllTasksByID($templates);
					$data['templateRaci'] = $this->model->getRaci($templates);
					$data['templateUsers'] = $this->model->getAllUsers();
				}

				// $this->output->enable_profile(TRUE);
				// $this->load->view('arrangeTasks', $data);
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
									$details = $userName . " has marked " . $taggedUserName . " as responsible for " . $taskTitle . ".";

									$logData = array (
										'LOGDETAILS' => $details,
										'TIMESTAMP' => date('Y-m-d H:i:s'),
										'projects_PROJECTID' => $projectID
									);

									$this->model->addToProjectLogs($logData);
									// END: LOG DETAILS

									//START: Notifications
									$details = "You have been tagged as responsible for " . $taskTitle . " in " . $projectTitle . ".";
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
				$data['templateProject'] = $this->model->getProjectByID($templates);
				$data['templateAllTasks'] = $this->model->getAllProjectTasks($templates);
				$data['templateGroupedTasks'] = $this->model->getAllProjectTasksGroupByTaskID($templates);
				$data['templateMainActivity'] = $this->model->getAllMainActivitiesByID($templates);
				$data['templateSubActivity'] = $this->model->getAllSubActivitiesByID($templates);
				$data['templateTasks'] = $this->model->getAllTasksByID($templates);
				$data['templateRaci'] = $this->model->getRaci($templates);
				$data['templateUsers'] = $this->model->getAllUsers();
			}

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
				'PROJECTSTATUS' => $id,
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

				// START: Notification
				$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
				$details = $userName . " has uploaded " . $fileName . ".";

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
		$data['departments'] = $this->model->getAllDepartments();
		$data['documentsByProject'] = $this->model->getAllDocumentsByProject($id);
		$data['documentAcknowledgement'] = $this->model->getDocumentsForAcknowledgement($id, $_SESSION['USERID']);

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

		// return $data;

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
