<?php
include "Device.php";

class Weather extends Device {
  public function __construct($name) {
    $this->deviceName = $name;
  }

  public function showDevice() {
    return "Device Name: " . $this->deviceName;
  }
}
