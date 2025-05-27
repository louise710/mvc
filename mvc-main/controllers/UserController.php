<?php
namespace Controllers;

require_once 'vendor/autoload.php';

use Request\Request;
use Responses\Response;
use Models\UserRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Controllers\ApiController;

class UserController extends ApiController {
    private $db;
    private $request;
    private $secret_key = "im_gonna_be_pirateking";

    public function __construct($repo, $request) {
        $this->db = $repo;
        $this->request = $request;
    }

    public function register() {
        $data = $this->request->getBody();
        
        if (empty($data['email']) || empty($data['password'])) {
            return new Response(400, json_encode(['error' => 'All fields are required']));
        }

        if (isset($data['confirm_password']) && $data['password'] !== $data['confirm_password']) {
            return new Response(400, json_encode(['error' => 'Passwords do not match']));
        }

        $existingUser = $this->db->getByEmail($data['email']);
        if ($existingUser) {
            return new Response(409, json_encode(['error' => 'Email already registered']));
        }

        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);

        $userId = $this->db->create([
            'email' => $data['email'],
            'password' => $hashed_password
        ]);

        if (!$userId) {
            return new Response(500, json_encode(['error' => 'Failed to create user']));
        }

        return new Response(201, json_encode([
            'message' => 'Registration successful. Please login.',
            'redirect' => '/mvc-main/login'
        ]));
    }
    
    public function login() {
        $data = $this->request->getBody();
        
        if (empty($data['email']) || empty($data['password'])) {
            return new Response(400, json_encode(['error' => 'Email and password are required']));
        }
        
        $user = $this->db->getByEmail($data['email']); 
    
        if (!$user) {
            return new Response(404, json_encode(['error' => "Email not found"]));
        }
    
        if (!password_verify($data['password'], $user['password'])) {
            return new Response(401, json_encode(['error' => "Password does not match"]));
        }
    
        $payload = [
            "iss" => "rest-api",
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "sub" => $user['ID']
        ];
    
        $token = JWT::encode($payload, $this->secret_key, 'HS256');
    
        return new Response(200, json_encode([
            'token' => $token,
            'redirect' => '/mvc-main/dashboard'  
        ]));
    }
    
    public function verifyToken($token) {
        try {
            return JWT::decode($token, new Key($this->secret_key, 'HS256'));
        } catch (\Exception $e) {
            return ["error" => "Invalid or expired token"];
        }
    }
}