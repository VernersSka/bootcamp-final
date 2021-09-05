<?php
include_once 'DB.php';
class Schedule extends DB
{
  public function __construct() {
    parent::__construct("reservations");
  }

  public function addReservation(string $name, string $email, string $time) {
    return parent::add([
      'name' => $name,
      'email' => $email,
      'time' => $time
    ]);
  }

  public function sendConfirmation(string $name, string $email, string $time) {
    $to = $email;
    $subject = "Scheduled meeting with Verners confirmed";
    $message = "Hi, $name! \n\nYour meeting with Verners has been scheduled for $time";
    $headers = "From: ver@ner.ver";
    mail($to, $subject, $message, $headers);
  }

}