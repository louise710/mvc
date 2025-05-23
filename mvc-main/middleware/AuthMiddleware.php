<?php
namespace Middleware;

use responses\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    private string $secret = "im_gonna_be_pirateking";

    public function handle($headers): mixed {
        $token = $headers['Authorization'] ?? null;

        if (!$token) {
            return new Response(401, json_encode(['error' => 'Token required']));
        }

        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return new Response(401, json_encode(['error' => 'Invalid or expired token']));
        }
    }
}
