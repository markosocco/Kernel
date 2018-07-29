<?php

// START OF LOGS/NOTIFS
$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];

$taskDetails = $this->model->getTaskByID($taskID);
$taskTitle = $taskDetails['TASKTITLE'];

$projectID = $taskDetails['projects_PROJECTID'];
$projectDetails = $this->model->getProjectByID($projectID);
$projectTitle = $projectDetails['PROJECTTITLE'];

$userDetails = $this->model->getUserByID($deptID);
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
	'users_USERID' => $deptID,
	'DETAILS' => $details,
	'TIMESTAMP' => date('Y-m-d H:i:s'),
	'status' => 'Unread'
);

$this->model->addNotification($notificationData);
// END: Notification

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
	'status' => 'Unread'
);

$this->model->addNotification($notificationData);
// END: Notification


// notify project owner
$notificationData = array(
	'users_USERID' => $projectOwnerID,
	'DETAILS' => $details,
	'TIMESTAMP' => date('Y-m-d H:i:s'),
	'status' => 'Unread'
);

$this->model->addNotification($notificationData);

// notify next ACI
$data['ACI'] = $this->model->getACIbyTask($id);
if($data['ACI'] != NULL) {

	foreach($data['ACI'] as $ACIusers){

		$details = $taskTitle . " has been marked as done by " . $userName . " in " . $projectTitle . ".";

		$notificationData = array(
			'users_USERID' => $ACIusers['users_USERID'],
			'DETAILS' => $details,
			'TIMESTAMP' => date('Y-m-d H:i:s'),
			'status' => 'Unread'
		);
		$this->model->addNotification($notificationData);
	}

?>
