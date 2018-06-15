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
			$data['ongoingProjects'] = $this->model->getAllOngoingProjects();
			$data['plannedProjects'] = $this->model->getAllPlannedProjects();

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
			$this->load->view("myTasks");
		}
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
			$status = 'Pending';
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

	public function arrangeTasks()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("arrangeTasks");
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
			$id = $this->input->get("id");
			$data['projectProfile'] = $this->model->getProjectByID($id);


			$data['ganttData'] = $this->model->getGanttData();
			// $data['preReq'] = $this->model->getPreReqID();
			$data['dependencies'] = $this->model->getDependecies();
			$this->load->view("projectGantt", $data);
		}
	}

	/******************** MY PROJECTS START ********************/

	public function addTasksToProject()
	{
		// GET PROJECT ID
		$id = $this->input->get("id");

		// GET ARRAY OF INPUTS FROM VIEW
		$category = $this->input->post('category');
		$title = $this->input->post('title');
		$department = $this->input->post('department');

		// GET ALL DEPTS TO ASSIGN DEPT HEAD TO TASK
		$departments = $this->model->getAllDepartments();

		// SAVES DATA FROM ARRAY VIA INDEX AND PLUGS INTO DB
		foreach ($category as $key => $value)
		{
			switch ($category[$key])
			{
				case 'Main Activity':
					$cat = 1;
					break;
				case 'Sub Activity':
					$cat = 2;
					break;
				case 'Task':
					$cat = 3;
					break;
			}

			// ASSIGN DEPARTMENT HEAD PER VARIABLE
			foreach ($departments as $row)
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

			switch ($department[$key])
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

			$data = array(
					'TASKTITLE' => $title[$key],
					'TASKSTATUS' => 'Pending',
					'CATEGORY' => $cat,
					'projects_PROJECTID' => $id,
					'users_USERID' => $deptHead
			);

			$addTasks = $this->model->addTasksToProject($data);

			if (!$addTasks)
			{
				// TODO PUT ALERT
				redirect('controller/contact');
			}
		}

		$data['project'] = $this->model->getProjectByID($id);
		$data['tasks'] = $this->model->getAllProjectTasks($id);
		$data['users'] = $this->model->getAllUsers();
		$data['departments'] = $this->model->getAllDepartments();
		$data['dateDiff'] = $this->model->getDateDiff($data['project']);

		$this->load->view('arrangeTasks', $data);
	}

	/******************** MY PROJECTS END ********************/

	/******************** GANTT CHART DELETE THIS AFTER ********************/
	public function gantt()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$data['ganttData'] = $this->model->getGanttData();
			// $data['preReq'] = $this->model->getPreReqID();
			$data['dependencies'] = $this->model->getDependecies();
			$this->load->view("gantt", $data);
		}
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
