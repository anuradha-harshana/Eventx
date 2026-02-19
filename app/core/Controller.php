<?php 
    class Controller {

        protected function model($model){

            require ROOT . '/app/model/' . $model . '.php';

            $db = new DatabaseConnector();

            return new $model($db);
        }

        public function view($view, $data = [])
        {
            extract($data);

            // Start wrapper
            echo '<div class="page-wrapper">';

            // Include header
            require ROOT . '/includes/header.php';

            // Include main content
            echo '<main class="content">';
            require ROOT . '/app/views/' . $view . '.php';
            echo '</main>';

            // Include footer
            require ROOT . '/includes/footer.php';

            // Close wrapper
            echo '</div>';
        }

    }

?>