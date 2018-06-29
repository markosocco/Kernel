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
			$this->load->view("dashboard");
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
			}
			else
			{
				$data['ongoingProjects'] = $this->model->getAllOngoingProjectsByUser($_SESSION['USERID']);
				$data['plannedProjects'] = $this->model->getAllPlannedProjectsByUser($_SESSION['USERID']);
			}

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
			$this->load->view("myTeam");
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
			$data['users'] = $this->model->getAllUsers();
			$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
			$this->load->view("myTasks", $data);
		}
	}

	public function doneTask()
	{
		$id = $this->input->post("task_ID");
		$remarks = $this->input->post('remarks');

		$data = array(
					'TASKSTATUS' => 'Complete',
					'TASKREMARKS' => $remarks
		);

		$updateTasks = $this->model->updateTaskDone($id, $data);

		$data['users'] = $this->model->getAllUsers();
		$data['tasks'] = $this->model->getAllTasksByUser($_SESSION['USERID']);
		$this->load->view("myTasks", $data);
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

	public function calendar()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("calendar");
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
			$this->load->view("documents");
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

		// PLUGS DATA INTO DB AND RETURNS ARRAY OF THE PROJECT
		$data['project'] = $this->model->addProject($data);
		$data['dateDiff'] = $this->model->getDateDiff($data);
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
			$data['ganttData'] = $this->model->getAllProjectTasks($id);
			// $data['preReq'] = $this->model->getPreReqID();
			$data['dependencies'] = $this->model->getDependecies();
			$data['users'] = $this->model->getAllUsers();

			$this->load->view("projectGantt", $data);
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
			// $id = $this->input->get("id");
			$data['projectProfile'] = $this->model->getProjectByID($id);

			$this->load->view("projectDocuments", $data);
			// echo $id;
		}
	}

	/******************** MY PROJECTS START ********************/

	public function addTasksToProject()
	{
		// GET PROJECT ID
		$id = $this->input->post("project_ID");

		// GET ARRAY OF INPUTS FROM VIEW
		$title = $this->input->post('title');

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

		$x = 0;

		foreach ($title as $row)
		{
			$department = $this->input->post('department_' . $x);

			foreach ($departments as $row2)
				{
					switch ($row2)
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
				}

				echo "Title: " . $row . "<br>";
				echo "Project ID: " . $id . "<br>";
				echo "Departments: ";

				foreach($department as $a)
				{
					echo $a . ", ";
				}

				echo "<br>---------------------------------------------<br>";
				$x++;
			}
		}

		// SAVES DATA FROM ARRAY VIA INDEX AND PLUGS INTO DB
		// foreach ($title as $key => $value)
		// {
		// 	// switch ($category[$key])
		// 	// {
		// 	// 	case 'Main Activity':
		// 	// 		$cat = 1;
		// 	// 		break;
		// 	// 	case 'Sub Activity':
		// 	// 		$cat = 2;
		// 	// 		break;
		// 	// 	case 'Task':
		// 	// 		$cat = 3;
		// 	// 		break;
		// 	// }
		//
		// 	// ASSIGN DEPARTMENT HEAD PER VARIABLE
		// 	foreach ($departments as $row)
		// 	{
		// 		switch ($row['DEPARTMENTNAME'])
		// 		{
		// 			case 'Executive':
		// 				$execHead = $row['users_DEPARTMENTHEAD'];
		// 				break;
		// 			case 'Marketing':
		// 				$mktHead = $row['users_DEPARTMENTHEAD'];
		// 				break;
		// 			case 'Finance':
		// 				$finHead = $row['users_DEPARTMENTHEAD'];
		// 				break;
		// 			case 'Procurement':
		// 				$proHead = $row['users_DEPARTMENTHEAD'];
		// 				break;
		// 			case 'HR':
		// 				$hrHead = $row['users_DEPARTMENTHEAD'];
		// 				break;
		// 			case 'MIS':
		// 				$misHead = $row['users_DEPARTMENTHEAD'];
		// 				break;
		// 			case 'Store Operations':
		// 				$opsHead = $row['users_DEPARTMENTHEAD'];
		// 				break;
		// 			case 'Facilities Administration':
		// 				$fadHead = $row['users_DEPARTMENTHEAD'];
		// 				break;
		// 		}
		// 	}
		//
		// 	switch ($department[$key])
		// 	{
		// 		case 'Executive':
		// 			$deptHead = $execHead;
		// 			break;
		// 		case 'Marketing':
		// 			$deptHead = $mktHead;
		// 			break;
		// 		case 'Finance':
		// 			$deptHead = $finHead;
		// 			break;
		// 		case 'Procurement':
		// 			$deptHead = $proHead;
		// 			break;
		// 		case 'HR':
		// 			$deptHead = $hrHead;
		// 			break;
		// 		case 'MIS':
		// 			$deptHead = $misHead;
		// 			break;
		// 		case 'Store Operations':
		// 			$deptHead = $opsHead;
		// 			break;
		// 		case 'Facilities Administration':
		// 			$deptHead = $fadHead;
		// 			break;
		// 	}
		//
		// 	$data = array(
		// 			'TASKTITLE' => $title[$key],
		// 			'TASKSTATUS' => 'Pending',
		// 			'CATEGORY' => 'Main Activity',
		// 			'projects_PROJECTID' => $id,
		// 			'users_USERID' => $deptHead
		// 	);
		//
		// 	$addTasks = $this->model->addTasksToProject($data);
		//
		// 	if (!$addTasks)
		// 	{
		// 		// TODO PUT ALERT
		// 		redirect('controller/contact');
		// 	}
		// }
		//
		// $data['project'] = $this->model->getProjectByID($id);
		// $data['tasks'] = $this->model->getAllProjectTasks($id);
		// $data['users'] = $this->model->getAllUsers();
		// $data['departments'] = $this->model->getAllDepartments();
		// $data['dateDiff'] = $this->model->getDateDiff($data['project']);
		//
		// $this->load->view('arrangeTasks', $data);

		public function arrangeTasks()
		{
			$id = $this->input->post('project_ID');

			$tasks = $this->input->post('task_ID');
			$startDates = $this->input->post('taskStartDate');
			$endDates = $this->input->post('taskEndDate');

			foreach ($tasks as $key => $value)
			{
				$dates = array(
						'sDate' => $startDates[$key],
						'eDate' => $endDates[$key]
				);

				$period = $this->model->getDateDiff($dates);

				$data = array(
						'TASKSTARTDATE' => $startDates[$key],
						'TASKENDDATE' => $endDates[$key],
						'PERIOD' => $period
				);

				// $dependencies = $this->input->post('dependencies_' . $tasks[$key]);
				//
				// echo $tasks[$key] . ": " . $dependencies . "<br>";

				// foreach ($dependencies as $row)
				// {
				// 	echo $tasks['TASKID'] . ": " . $row . "<br>";
				// }

				$arrangeTasks = $this->model->arrangeTasks($data, $tasks[$key]);

				// echo "-------------------------------------<br>";
			}

			// // SET PARENT TASK
			$allTasks = $this->model->getAllProjectTasks($id);

			foreach ($allTasks as $row)
			{
				$currentTask = $this->model->getTaskByID($row['TASKID']);
				$isCurrent = false;

				if ($row['CATEGORY'] == 2)
				{
					foreach ($allTasks as $row_2)
					{
						if ($row_2['TASKID'] == $currentTask['TASKID'])
						{
							$isCurrent = true;
							// echo $row_2['TASKID'] . ": this is the current task <br>";
						}

						else
						{
							if ($row_2['CATEGORY'] == 1 && $isCurrent == false)
							{
								$parent = $row_2['TASKID'];
							}
						}

						// echo $row_2['TASKID'] . "<br>";
					}

					$data = array (
						'tasks_TASKPARENT' => $parent
					);

					$insertParentTask = $this->model->insertParentTask($data, $currentTask['TASKID']);
				}

				if ($row['CATEGORY'] == 3)
				{
					foreach ($allTasks as $row_2)
					{
						if ($row_2['TASKID'] == $currentTask['TASKID'])
						{
							$isCurrent = true;
						}

						else
						{
							if ($row_2['CATEGORY'] == 2 && $isCurrent == false)
							{
								$parent = $row_2['TASKID'];
							}
						}
					}

					$data = array (
						'tasks_TASKPARENT' => $parent
					);

					$insertParentTask = $this->model->insertParentTask($data, $currentTask['TASKID']);
				}
			}

			// GANTT CODE
			$data['projectProfile'] = $this->model->getProjectByID($id);
			$data['ganttData'] = $this->model->getAllProjectTasks($id);
			// $data['preReq'] = $this->model->getPreReqID();
			$data['dependencies'] = $this->model->getDependecies();
			$data['users'] = $this->model->getAllUsers();

			// $this->load->view("dashboard", $data);
			// redirect('controller/projectGantt');
			$this->load->view("projectGantt", $data);
	}

	public function uploadDocument()
	{
		$config['upload_path']          = './assets/uploads';
		$config['allowed_types'] 				= '*';

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('docu'))
		{
			$this->load->view('dashboard');
		}

		else
		{
			// GET PROJECT ID
			// Hi nami this should work
			$id = $this->input->get("id");
			// $id = $this->input->post("projectID");
			$data['projectProfile'] = $this->model->getProjectByID($id);

			$user = $_SESSION['USERID'];
			$fileName = $this->upload->data('file_name');
			$src = "http://localhost/Kernel/assets/uploads/" . $fileName;

			// echo $id;

			$uploadData = array(
				'DOCUMENTSTATUS' => 'Uploaded',
				'DOCUMENTNAME' => $fileName,
				'DOCUMENTLINK' => $src,
				'users_UPLOADEDBY' => $user,
				'UPLOADEDDATE' => date('m/d/Y'),
				'projects_PROJECTID' => $id
			);

			$result = $this->model->uploadDocument($uploadData);

			// $data['ongoingProjects'] = $this->model->getAllOngoingProjects();
			// $data['plannedProjects'] = $this->model->getAllPlannedProjects();

			$id = $this->input->post("project_ID");
			$this->session->set_flashdata('projectID', $id);
			// $id = $this->input->get("id");
			$data['projectProfile'] = $this->model->getProjectByID($id);

			$this->load->view("projectDocuments", $data);
		}
	}

	/******************** MY PROJECTS END ********************/

	public function gantt2(){

		$filter = "tasks.TASKSTARTDATE"; // default
		$data['ganttData'] = $this->model->getAllProjectTasks(1, $filter);
		// $data['preReq'] = $this->model->getPreReqID();
		$data['dependencies'] = $this->model->getDependecies();

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
