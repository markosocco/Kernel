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
		if (!isset($_SESSION['email']))
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
		if (isset($_SESSION['email']))
		{
			$this->load->view('contact');
		}

		else
		{
			unset($_SESSION);
		  session_destroy();
		  session_write_close();

			$this->load->view("login");
		}
	}

// CHECKS IF email AND PASSWORD MATCH DB
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
				//$userType = $this->dbtest_model->getUserType($data);

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
		$this->load->view('dashboard');
	}

	public function myProjects()
	{
		$this->load->view('myProjects');
	}

	public function myTeam()
	{
		$this->load->view('myTeam');
	}

	public function myTasks()
	{
		$this->load->view('myTasks');
	}

	public function templates()
	{
		$this->load->view('templates');
	}

	public function archives()
	{
		$this->load->view('archives');
	}

	public function newProject()
	{
		$this->load->view('newProject');
	}

// DELETE THIS AFTER
	public function frame()
	{
		$this->load->view('frame');

	}
}
