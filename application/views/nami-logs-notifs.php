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
	'TIMESTAMP' => date('Y-m-d'),
	'status' => 'Unread'
);

$this->model->addNotification($notificationData);
// END: Notification

$taskDetails = $this->model->getTaskByID($taskID);
$taskTitle = $taskDetails['TASKTITLE'];

$projectID = $taskDetails['projects_PROJECTID'];
$projectTitle = $projectDetails['PROJECTTITLE'];

$userDetails = $this->model->getUserByID($deptID);
$taggedUserName = $userDetails['FIRSTNAME']. " " . $userDetails['LASTNAME'];


?>
