<?php

namespace App\Http\Controllers\ManageData;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Response as ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Constants;
use App\Models\PermissionModel;
use Illuminate\Http\Request;
use App\Models\RoleModel;
use App\Helpers\Token;

class PermissionController extends Controller
{
  public function __construct(Request $request)
  {
    $constants = new Constants();
    $this->superAdmin = $constants->roleSuperAdmin();
    $getTokenAuth = json_decode(Token::viewToken($request->header('Authorization')));
    $this->generateToken = $getTokenAuth->response->data;
  }

  public function index(Request $request)
  {
    try {
      $role = $this->getRole();

      $response = array();
      $idx = 0;
      foreach ($role as $row) {
        if ($row->id !== $this->superAdmin->id) {
          $response[$idx]['id'] = $row->id;
          $response[$idx]['name'] = $row->name;
          $response[$idx]['permission'] = $this->getPermission($row->id);
          $idx++;
        }
      }
      
      return ResponseApi::SetResponseApi(200, "success", $response);
    } catch (\Exception $e) {
      return ResponseApi::SetResponseApi(422, $e->getMessage(), (object)array());
    }
  }

  public function edit(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|exists:permission,id',
      'view' => 'required',
      'add' => 'required',
      'edit' => 'required',
      'delete' => 'required',
      'other' => 'required'
    ]);

    if ($validator->fails()) {
      return ResponseApi::SetResponseApi(Response::HTTP_BAD_REQUEST, $validator->messages()->first(), null);
    }

    try {
      $query = PermissionModel::where('id', $request->id)->first();

      $query->update([
        'view' => $request->view,
        'add' => $request->add,
        'edit' => $request->edit,
        'delete' => $request->delete,
        'other' => $request->other
      ]);
      return ResponseApi::SetResponseApi(200, "success", (object)array());
    } catch (\Exception $e) {
      return ResponseApi::SetResponseApi(Response::HTTP_BAD_REQUEST, $e->getMessage(), (object)array());
    }
  }

  public function getRole()
  {
    return RoleModel::where(function ($query) {
      $query->where('deleted_at', NULL);
      if (!$this->isSuperAdmin()) $query->where('id', $this->generateToken->role);
    })->get();
  }

  public function getPermission($role)
  {
    $permission = PermissionModel::select($this->selectField())
      ->where('deleted_at', '=', NULL)
      ->where('id_role', '=', $role)
      ->get();

    $response = array();
    foreach ($permission as $idx => $row) {
      $response[] = $row;
    }

    return $response;
  }

  public function selectField()
  {
    return [
      'id',
      'slug',
      'view',
      'add',
      'delete',
      'edit',
      'other',
      'id_role',
    ];
  }

  public function isSuperAdmin()
  {
    return $this->generateToken->role === $this->superAdmin->id;
  }
}
