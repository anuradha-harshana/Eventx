<?php 
    class SupplierController extends Controller{
        private $suppModel;
        public function __construct(){
            Middleware::role('supplier');
            $this->suppModel = $this->model('SupplierModel');
        }
        private function getSupplierId(){
            return $this->suppModel->getSupplierByUserId($_SESSION['user_id']);
        }
        public function dashboard(){
            $supplierId = $this->getSupplierId();
            $products = $this->suppModel->getProductsBySupplier($supplierId);
            $this->view('supplier/dashboard', [
                'products' => $products
            ]);
        }
        public function profile(){
            $this->view('supplier/profile');
        }
        public function analytics(){
            $this->view('supplier/analytics');
        }

        public function addProduct(){
            $categories = $this->suppModel->getCategories();
            $this->view('supplier/createProduct', [
                'categories' => $categories
            ]);
        }
        public function updateProfile() {
            $profilePicPath = null;

            if (!empty($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] !== UPLOAD_ERR_NO_FILE) {
                require_once __DIR__ . '/../model/FileUploader.php';

                try {
                    $uploader = new FileUploader(__DIR__ . '/../../uploads/profile/');
                    $profilePicPath = $uploader->upload($_FILES['profile_pic'], 'profile_');
                } catch (Exception $e) {
                    // Handle upload error gracefully
                    $_SESSION['upload_error'] = $e->getMessage();
                    header('Location: ' . SITE_URL . 'sponProf');
                    exit;
                }
            }

            $this->suppModel->updateProfile($_SESSION["user_id"], $_POST, $profilePicPath);
            header('Location:' .SITE_URL. 'suppDash');
        }

        public function createProduct(){
            $supplierId = $this->getSupplierId();

            $imageUrl = null;

            if (!empty($_FILES['image_url']) && $_FILES['image_url']['error'] !== UPLOAD_ERR_NO_FILE) {
                require_once __DIR__ . '/../model/FileUploader.php';

                try {
                    $uploader = new FileUploader(__DIR__ . '/../../uploads/products/');
                    $imageUrl = $uploader->upload($_FILES['image_url'], 'image_');
                } catch (Exception $e) {
                    // Handle upload error gracefully
                    $_SESSION['upload_error'] = $e->getMessage();
                    header('Location: ' . SITE_URL . 'orgDash');
                    exit;
                }
            }

            $this->suppModel->createProduct($supplierId, $_POST, $imageUrl);

            header('Location: ' . SITE_URL . 'suppDash');
            exit;
        }
        public function editProduct($id){
            $supplierID = $this->getSupplierId();
            $categories = $this->suppModel->getCategories();

            $product = $this->suppModel->getProductById($id, $supplierID);

            $this->view('supplier/editProduct', [
                'product' => $product,
                'categories' => $categories
            ]);
        }

        public function updateProduct(){
            $supplierId = $this->getSupplierId();

            $imageUrl = null;

            if (!empty($_FILES['image_url']) && $_FILES['image_url']['error'] !== UPLOAD_ERR_NO_FILE) {
                require_once __DIR__ . '/../model/FileUploader.php';

                try {
                    $uploader = new FileUploader(__DIR__ . '/../../uploads/products/');
                    $imageUrl = $uploader->upload($_FILES['image_url'], 'image_');
                } catch (Exception $e) {
                    // Handle upload error gracefully
                    $_SESSION['upload_error'] = $e->getMessage();
                    header('Location: ' . SITE_URL . 'suppDash');
                    exit;
                }
            }

            $this->suppModel->updateProducts($supplierId, $_POST, $imageUrl);

            header('Location: ' . SITE_URL . 'suppDash');
            exit;
        }
        public function deleteProduct()
        {
            if (!isset($_POST['product_id'])) {
                header('Location: ' . SITE_URL . 'suppDash');
                exit;
            }

            $supplierId = $this->suppModel->getSupplierByUserId($_SESSION['user_id']);

            $productId = $_POST['product_id'];

            $this->suppModel->deleteProduct($productId, $supplierId);

            header('Location: ' . SITE_URL . 'suppDash');
            exit;
        }

    }

?>
