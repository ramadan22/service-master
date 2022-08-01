<?php
  namespace App\Helpers;

  use Firebase\JWT\JWT;
  use Firebase\JWT\Key;
 
  class Token {
    public static function setToken($userData) {
      $generate_time = time();

      $expired_time = $generate_time + (60 * 60 * 72); // 60 seconds * 60 minutes * 72 hours (3 days)

      $payload = [
        'iat' => $generate_time,
        'exp' => $expired_time,
        'data' => [
          'id' => $userData->id,
          'name' => $userData->name,
          'email' => $userData->email,
          'role' => $userData->id_role,
        ],
      ];

      $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
      return $jwt;
    }

    public static function viewToken($token) {
      try {
        return $result = json_encode([
          "message" => "Success",
          "response" => JWT::decode(str_replace('Bearer ', '', $token), new Key(env('JWT_SECRET'), 'HS256')),
        ]);
      } catch (\Exception $e) {
        return json_encode([
          "message" => $e->getMessage(),
          "response" => (object)array(),
        ]);
      }
    }
  }
