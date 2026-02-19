<?php 

    class AdminController extends Controller {
        public function __construct(){
            Middleware::role('admin');
        }
        public function dashboard(){
            $this->view('admin/dashboard');
        }

        public function analytics(){
            $this->view('admin/analytics');
        }

        public function reports(){
            $this->view('admin/reports');
        }

        public function settings(){
            $this->view('admin/settings');
        }
    }

?>