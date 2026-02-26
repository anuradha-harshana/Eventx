<?php 
    class AuthController extends Controller {
        private $userModel;

        public function __construct(){
            $this->userModel = $this->model('UserModel');
        }

        public function register(){

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = $_POST['role'] ?? 'participant';

            $errors=[];

            if(strlen($username)<3) $errors[]="Username must be 3+ chars";
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]="Invalid email";
            if(strlen($password)<6) $errors[]="Password must be 6+ chars";

            if($errors) {
                $this->view('register',['errors'=>$errors]);
                return;
            }

            $result = $this->userModel->register(['username'=>$username,'email'=>$email,'password'=>$password,'role'=>$role]);
            if($result['status']) header('Location: '.SITE_URL.'login');
            else $this->view('register',['errors'=>[$result['message']]]);
        }

        public function login() {
            $email = trim($_POST['email']??'');
            $password = trim($_POST['password']??'');

            $user = $this->userModel->login($email,$password);
            if($user) {
                $_SESSION['user_id']=$user['id'];
                $_SESSION['username']=$user['username'];
                $_SESSION['role']=$user['role'];
                
                switch($user['role']){

                    case 'admin':
                        header('Location: '.SITE_URL.'adDash');
                        break;

                    case 'participant':
                        header('Location: '.SITE_URL.'parDash');
                        break;

                    case 'organizer':
                        header('Location: '.SITE_URL.'orgDash');
                        break;

                    case 'sponsor':
                        header('Location: '.SITE_URL.'sponDash');
                        break;

                    case 'supplier':
                        header('Location: '.SITE_URL.'suppDash');
                        break;
                }

            } else {
                $this->view('login',['errors'=>['Invalid email or password']]);
            }
        }

        public function logout() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_unset();
            session_destroy();
            header('Location: '.SITE_URL.'login');
        }

        public function profile() {
            Middleware::auth();

            $user = $this->userModel->getById($_SESSION['user_id']);
            $this->view('profile',['user'=>$user]);
        }

    }

?>
