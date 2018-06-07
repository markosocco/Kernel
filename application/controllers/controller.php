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

				redirect('controller/frame');

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

	/******************** START OF VIEWS ********************/

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
			$this->load->view("myProjects");
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
			$this->load->view("templates");
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
			$this->load->view("archives");
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
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("addTasks");
		}
	}

	public function step2()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("step2");
		}
	}

	public function step3()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$this->load->view("step3");
		}
	}
	/******************** END OF VIEWS ********************/

	/******************** MY PROJECTS START ********************/

// SAVE NEW PROJECT WITH TITLE, DETAILS, START DATE, END DATE
	public function addProject()
	{
		//$this->load->view("contact");

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

		$data['project'] = $this->model->addProject($data);
		$data['dateDiff'] = $this->model->getDateDiff($data);
		$data['departments'] = $this->model->getAllDepartments($data);
		$data['counter'] = 1;

		if ($data)
		{
			//echo $project['PROJECTTITLE'];
			$this->load->view('addTasks', $data);
			//redirect('controller/newProject');
		}

		else
		{
			redirect('controller/contact');
		}
	}

	/******************** MY PROJECTS END ********************/

	public function gantt()
	{
		if (!isset($_SESSION['EMAIL']))
		{
			$this->load->view('contact');
		}

		else
		{
			$data['ganttData'] = $this->model->getGanttData();
			$data['preReq'] = $this->model->getPreReqID();
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
