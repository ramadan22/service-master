<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

class Constants extends Controller
{
  public function excludedPermission()
  {
    return [
      'permission'
    ];
  }

  public function roleSuperAdmin()
  {
    return (object)array('id' => 1, 'name' => 'Super Admin');
  }
}
