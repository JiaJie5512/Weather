<?php
class Theme {
  public function getTheme() {
    return "Light Mode";
  }
}

class DarkTheme extends Theme {
  public function getTheme() {
    return "Dark Mode";
  }
}
