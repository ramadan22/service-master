<?php
  namespace App\Helpers;
 
class Response {
  public static function SetResponseApi($status, $message, $response) {
    return response()->json([
      'status' => $status,
      'message' => $message,
      'response' => $response ?: (object)array(),
    ], $status);
  }
}
