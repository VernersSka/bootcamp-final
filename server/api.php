<?php

define('DEBUG_MODE', true);

if (DEBUG_MODE) {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

header('Content-type: application/json');

if (isset($_REQUEST['api'])) {
  include __DIR__ .  "Schedule.php";
  include __DIR__ .  "Comments.php";

  $schedule = new Schedule();
  $comments = new Comments();

  if ($_REQUEST['api'] === 'schedule') {
    if (
      isset($_REQUEST['name']) && 
      is_string($_REQUEST['name']) &&
      isset($_REQUEST['email']) &&
      is_string($_REQUEST['email']) &&
      isset($_REQUEST['schedule-time']) &&
      is_string($_REQUEST['schedule-time'])
    ) {
      $mailed = $schedule->sendConfirmation($_REQUEST['name'], $_REQUEST['email'], $_REQUEST['schedule-time']);
      $response = $schedule->addReservation($_REQUEST['name'], $_REQUEST['email'], $_REQUEST['schedule-time']);
      echo json_encode($response);
    }
    else {
      echo 'Data passed not valid';
    }
  }

  elseif ($_REQUEST['api'] === 'get') {
    $comment_list = $comments->getAll();
    echo json_encode($comment_list);
  }

  elseif ($_REQUEST['api'] === 'getTimes') {
    $taken_times = $schedule->getSelectData('time');
    echo json_encode($taken_times);
  }

  elseif ($_REQUEST['api'] === 'add') {
    if (
      isset($_REQUEST['author']) && 
      is_string($_REQUEST['author']) &&
      isset($_REQUEST['message']) &&
      is_string($_REQUEST['message'])
    ) {
      $response = $comments->addComment($_REQUEST['author'], $_REQUEST['message']);
      echo json_encode($response);
    }
  }
  else {
    echo 'Api parameter is not recognized';
  }
}
else {
  echo 'Api parameter is not set';
}

