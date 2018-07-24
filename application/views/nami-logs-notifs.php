<?php

LOGDETAILS, TIMESTAMP, projects_PROJECTID

// START: LOG DETAILS
$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
$taskDetails = $this->model->getTaskByID($id);
$taskTitle = $taskDetails['TASKTITLE'];
$projectID = $taskDetails['projects_PROJECTID'];
$details = $userName . " uploaded " . $fileName;

$logData = array (
	'LOGDETAILS' => $details,
	'TIMESTAMP' => date('Y-m-d H:i:s'),
	'projects_PROJECTID' => $id
);

$this->model->addToProjectLogs($logData);
// END: LOG DETAILS



users_USERID, DETAILS, TIMESTAMP, status

// START: Notifications
$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
$details = $userName . " has uploaded " . $fileName . " and needs your acknowledgement.";
$notificationData = array(
	'users_USERID' => $userIDByDepartment['users_USERID'],
	'DETAILS' => $details,
	'TIMESTAMP' => date('Y-m-d H:i:s'),
	'status' => 'Unread'
);

$this->model->addNotification($notificationData);
// END: Notification

$taskDetails = $this->model->getTaskByID($taskID);
$taskTitle = $taskDetails['TASKTITLE'];

$projectID = $taskDetails['projects_PROJECTID'];
$projectDetails = $this->model->getProjectByID($projectID);
$projectTitle = $projectDetails['PROJECTTITLE'];

$userDetails = $this->model->getUserByID($deptID);
$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];



// START: LOG DETAILS
$userName = $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'];
$taskDetails = $this->model->getTaskByID($taskID);
$taskTitle = $taskDetails['TASKTITLE'];
$projectID = $taskDetails['projects_PROJECTID'];
$userDetails = $this->model->getUserByID($deptID);
$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];
$details = $userName . " has marked " . $taggedUserName . " as accountable for " . $taskTitle . ".";

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
$details = "You have been tagged as accountable for " . $taskTitle . " in " . $projectTitle . ".";
$notificationData = array(
	'users_USERID' => $deptID,
	'DETAILS' => $details,
	'TIMESTAMP' => date('Y-m-d H:i:s'),
	'status' => 'Unread'
);

$this->model->addNotification($notificationData);
// END: Notification

?>
