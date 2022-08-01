<?php

namespace App\Http\Controllers\Authentication;

use App\Helpers\Token;
use App\Models\UsersModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Response as ResponseApi;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class Login extends Controller
{
  public function index(Request $request) {
    $validator = Validator::make($request->all(), [
      'email' => 'required',
      'password' => 'required',
    ]);

    $resultGetUser = (object)array();

    if ($validator->fails())
    {
      return ResponseApi::SetResponseApi(Response::HTTP_BAD_REQUEST, $validator->messages()->first(), null);
    }
    
    $resultGetUser = $this->getUser($request->email, md5($request->password));
    
    if ($resultGetUser) { 
      // $resultGetUser['role'] = 'super_admin';
      $rememberToken = Token::setToken($resultGetUser);
      $resultUpdateUserToken = $this->updateUserToken($resultGetUser->id, $rememberToken);

      if ($resultUpdateUserToken) {
        $resultGetUser['rememberToken'] = $rememberToken;
        return ResponseApi::SetResponseApi(200, 'success', $resultGetUser);
      }
    }

    return ResponseApi::SetResponseApi(Response::HTTP_BAD_REQUEST, 'Wrong email or Password!', null);
  }

  public function getUser($email, $password) {
    return $result = UsersModel::where('email', $email)
          ->where('password', $password)
          ->first();
  }

  public function updateUserToken($id, $token) {
    return $result = UsersModel::where('id', $id)->update(['rememberToken' => $token]);
  }
}
