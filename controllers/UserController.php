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
        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
    
        $this->db->create([
            'ID' => $data['ID'],
            'email' => $data['email'],
            'password' => $hashed_password
        ]);
    
        return new Response(201, json_encode(['message' => 'User registered successfully']));
    }
    
    public function login() {
        $data = $this->request->getBody();
        $user = $this->db->getByEmail($data['email']); 
    
        if (!$user || !password_verify($data['password'], $user['password'])) {
            return new Response(404, json_encode(['error' => "Invalid credentials"]));
        }
    
        $payload = [
            "iss" => "rest-api",
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "sub" => $user['ID']
        ];
    
        $token = JWT::encode($payload, $this->secret_key, 'HS256');
    
        return new Response(200, json_encode(['token' => $token]));
    }
    public function verifyToken($token) {
        try {
            return JWT::decode($token, new Key($this->secret_key, 'HS256'));
        } catch (\Exception $e) {
            return ["error" => "Invalid or expired token"];
        }
    }
}



