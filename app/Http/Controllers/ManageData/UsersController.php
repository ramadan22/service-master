<?php

namespace App\Http\Controllers\ManageData;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Response as ResponseApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UsersModel;

class UsersController extends Controller
{
  public function index()
  {
    try {
      $users = UsersModel::select($this->selectField())->get()->where('delete_at', NULL);
      return ResponseApi::SetResponseApi(200, "success", $users);
    } catch (\Exception $e) {
      return ResponseApi::SetResponseApi(422, $e->getMessage(), (object)array());
    }
  }

  public function add(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:6|required_with:password_confirmation|same:password_confirmation',
      'password_confirmation' => 'required|min:6',
      'id_role' => 'required',
      'created_at' => 'required',
    ]);

    if ($validator->fails()) {
      return ResponseApi::SetResponseApi(Response::HTTP_BAD_REQUEST, $validator->messages()->first(), null);
    }

    try {
      $query = UsersModel::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => md5($request->name),
        'id_role' => $request->id_role,
        'created_at' => Date("Y-m-d H:i:s"),
      ]);
      return ResponseApi::SetResponseApi(200, "success", (object)array());
    } catch (\Exception $e) {
      return ResponseApi::SetResponseApi(422, $e->getMessage(), (object)array());
    }
  }

  public function edit(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|exists:users,id',
      'name' => 'required',
      'password' => 'required|min:6|required_with:password_confirmation|same:password_confirmation',
      'password_confirmation' => 'required|min:6',
      'id_role' => 'required||exists:role,id',
    ]);

    if ($validator->fails()) {
      return ResponseApi::SetResponseApi(Response::HTTP_BAD_REQUEST, $validator->messages()->first(), null);
    }

    try {
      $query = UsersModel::where('id', $request->id)->first();

      $query->update([
        'name' => $request->name,
        'password' => md5($request->name),
        'id_role' => $request->id_role
      ]);
      return ResponseApi::SetResponseApi(200, "success", (object)array());
    } catch (\Exception $e) {
      return ResponseApi::SetResponseApi(Response::HTTP_BAD_REQUEST, $e->getMessage(), (object)array());
    }
  }

  public function delete(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|exists:users,id',
    ]);

    if ($validator->fails()) {
      return ResponseApi::SetResponseApi(Response::HTTP_BAD_REQUEST, $validator->messages()->first(), null);
    }

    try {
      $query = UsersModel::where('id', $request->id)->first();

      $query->update([
        'deleted_at' => Date("Y-m-d H:i:s"),
      ]);

      return ResponseApi::SetResponseApi(200, "success", (object)array());
    } catch (\Exception $e) {
      return ResponseApi::SetResponseApi(Response::HTTP_BAD_REQUEST, $e->getMessage(), (object)array());
    }
  }

  public function selectField()
  {
    return [
      'id',
      'name',
      'email',
      'id_guru',
      'id_siswa',
    ];
  }
}
