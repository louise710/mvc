<?php
namespace Controllers;

require_once 'vendor/autoload.php';

use Core\View;
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
             return View::render('register', ['error' => 'All fields are required']);
            
        }

        if (isset($data['confirm_password']) && $data['password'] !== $data['confirm_password']) {
             return View::render('register', ['error' => 'Passwords do not match']);
           
        }

        $existingUser = $this->db->getByEmail($data['email']);
        if ($existingUser) {
             return View::render('register', ['error' => 'Email already registered']);
            
        }

        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);

        $userId = $this->db->create([
            'email' => $data['email'],
            'password' => $hashed_password
        ]);

        if (!$userId) {
            return View::render('register', ['error' => 'Failed to create user']);
           
        }

        return View::render('register', ['success' => 'Registration successful. Redirecting to login...']);

    }
    
public function login() {
    session_start(); 

    $data = $this->request->getBody();

    
    if (empty($data['email']) || empty($data['password'])) {
         return View::render('login', ['error' => 'Email and password are required']);
    }

   
    $user = $this->db->getByEmail($data['email']); 

    if (!$user) {
         return View::render('login', ['error' => 'Email not Found.']);
    }

    if (!password_verify($data['password'], $user['password'])) {
         return View::render('login', ['error' => 'Invalid password.']);
    }

   
    $payload = [
        'iss' => 'mvc-main',
        'iat' => time(),
        'exp' => time() + 3600, 
        'sub' => $user['ID']
    ];

    try {
        $token = \Firebase\JWT\JWT::encode($payload, $this->secret_key, 'HS256');
    } catch (\Exception $e) {
         return View::render('login', ['error' => 'Failed to generate token.']);
    }

    // Return success response
    $_SESSION['token'] = $token;
    $_SESSION['user'] = [
        'id' => $user['ID'],
        'email' => $user['email'],
        'name' => $user['name'] ?? ''
    ];

    
    header('Location: /mvc-main/dashboard');
    exit; 
}
public function loginapi() {
    $data = $this->request->getBody();

    if (empty($data['email']) || empty($data['password'])) {
        return new Response(400, json_encode(['error' => 'Email and password are required']));
    }

    $user = $this->db->getByEmail($data['email']);

    if (!$user || !password_verify($data['password'], $user['password'])) {
        return new Response(401, json_encode(['error' => 'Invalid email or password']));
    }

    $payload = [
        'iss' => 'mvc-main',
        'iat' => time(),
        'exp' => time() + 3600,
        'sub' => $user['ID']
    ];

    try {
        $token = JWT::encode($payload, $this->secret_key, 'HS256');
    } catch (\Exception $e) {
        return new Response(500, json_encode(['error' => 'Failed to generate token']));
    }

    // For Postman/API that will return JSON token
    return new Response(200, json_encode([
        'token' => $token,
        'user' => [
            'id' => $user['ID'],
            'email' => $user['email']
        ]
    ]));
}


    
    public function verifyToken($token) {
        try {
            return JWT::decode($token, new Key($this->secret_key, 'HS256'));
        } catch (\Exception $e) {
            return ["error" => "Invalid or expired token"];
        }
    }


public function logout() {
    session_start();
    session_unset();
    session_destroy();
    header('Location: /mvc-main/login');
    exit();
}
}