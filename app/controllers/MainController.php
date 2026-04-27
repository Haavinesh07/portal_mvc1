<?php
namespace App\Controllers;

use App\Models\StudentModel;

class MainController
{
    private const MAX_PROFILE_IMAGE_SIZE = 2097152;
    private const PROFILE_UPLOAD_DIR = __DIR__ . '/../../uploads/profile_pictures';
    private const PROFILE_UPLOAD_WEB_PATH = 'uploads/profile_pictures';

    private StudentModel $model;
    private array $programs = ['JTMK', 'JKE', 'JKM', 'JP'];

    public function __construct(\mysqli $conn)
    {
        $this->model = new StudentModel($conn);
    }

    public function home(): void
    {
        if (isset($_SESSION['logged_in'])) {
            $this->redirect('dashboard');
        }

        require __DIR__ . '/../views/home.php';
    }

    public function register(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $nric = trim($_POST['ic'] ?? '');
            $program = trim($_POST['program'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            $error = $this->validateRegistration($name, $nric, $program, $password, $confirm);

            if (!$error && $this->model->registerUser($name, $nric, $program, $password)) {
                $this->redirect('login', 'status=registered');
            }

            $error ??= 'Registration failed. The NRIC may already be registered.';
        }

        require __DIR__ . '/../views/register.php';
    }

    public function login(): void
    {
        $error = null;
        $success = ($_GET['status'] ?? '') === 'registered'
            ? 'Registration successful. Please log in using your NRIC and password.'
            : null;

        if (isset($_SESSION['logged_in'])) {
            $this->redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->model->verifyLogin(trim($_POST['username'] ?? ''), $_POST['password'] ?? '');

            if ($user) {
                session_regenerate_id(true);
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_nric'] = $user['nric'];
                $this->redirect('dashboard');
            }

            $error = 'Invalid NRIC or password.';
        }

        require __DIR__ . '/../views/login.php';
    }

    public function dashboard(): void
    {
        $this->requireLogin();

        $students = $this->model->getAllStudents();
        $model = $this->model;
        $success = $this->statusMessage('success');
        $error = $this->statusMessage('error');

        require __DIR__ . '/../views/dashboard.php';
    }

    public function profile(): void
    {
        $this->requireLogin();

        $profile = $this->model->getCurrentUser((int) $_SESSION['user_id']);
        if (!$profile) {
            $this->logout();
        }

        $profilePictureUrl = null;
        if (!empty($profile['profile_picture']) && basename($profile['profile_picture']) === $profile['profile_picture']) {
            $imagePath = self::PROFILE_UPLOAD_DIR . DIRECTORY_SEPARATOR . $profile['profile_picture'];
            if (is_file($imagePath)) {
                $profilePictureUrl = self::PROFILE_UPLOAD_WEB_PATH . '/' . rawurlencode($profile['profile_picture']);
            }
        }

        $statusMessages = [
            'uploaded' => ['success', 'Profile picture uploaded successfully.'],
            'no_file' => ['error', 'Please choose a profile picture to upload.'],
            'upload_error' => ['error', 'The profile picture could not be uploaded.'],
            'too_large' => ['error', 'Profile picture must be 2MB or smaller.'],
            'invalid_type' => ['error', 'Only JPG, JPEG, and PNG profile pictures are allowed.'],
            'save_failed' => ['error', 'The profile picture could not be saved.'],
            'db_failed' => ['error', 'The database could not be updated.'],
        ];
        $message = $statusMessages[$_GET['profile_status'] ?? ''] ?? null;
        $success = $message && $message[0] === 'success' ? $message[1] : null;
        $error = $message && $message[0] === 'error' ? $message[1] : null;

        require __DIR__ . '/../views/profile.php';
    }

    public function uploadProfilePicture(): void
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('profile');
        }

        $file = $_FILES['profile_picture'] ?? null;
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            $this->redirect('profile', 'profile_status=no_file');
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || empty($file['tmp_name'])) {
            $this->redirect('profile', 'profile_status=upload_error');
        }

        if (($file['size'] ?? 0) > self::MAX_PROFILE_IMAGE_SIZE) {
            $this->redirect('profile', 'profile_status=too_large');
        }

        $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        $allowedTypes = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png'];
        $detectedType = (new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);

        if (!isset($allowedTypes[$extension]) || $detectedType !== $allowedTypes[$extension] || @getimagesize($file['tmp_name']) === false) {
            $this->redirect('profile', 'profile_status=invalid_type');
        }

        if (!is_dir(self::PROFILE_UPLOAD_DIR) && !mkdir(self::PROFILE_UPLOAD_DIR, 0755, true)) {
            $this->redirect('profile', 'profile_status=save_failed');
        }

        $filename = uniqid('profile_', true) . '.' . $extension;
        $targetPath = self::PROFILE_UPLOAD_DIR . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            $this->redirect('profile', 'profile_status=save_failed');
        }

        if ($this->model->updateUserProfilePicture((int) $_SESSION['user_id'], $filename)) {
            $this->redirect('profile', 'profile_status=uploaded');
        }

        @unlink($targetPath);
        $this->redirect('profile', 'profile_status=db_failed');
    }

    public function changePassword(): void
    {
        $this->requireLogin();
        $error = $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $_POST['old_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if ($old === '' || $new === '' || $confirm === '') {
                $error = 'All password fields are required.';
            } elseif (strlen($new) < 8) {
                $error = 'New password must contain at least 8 characters.';
            } elseif ($new !== $confirm) {
                $error = 'New password and confirmation do not match.';
            } elseif (!$this->model->verifyUserPassword((int) $_SESSION['user_id'], $old)) {
                $error = 'Old password is incorrect.';
            } elseif ($this->model->updateUserPassword((int) $_SESSION['user_id'], $new)) {
                $success = 'Password updated successfully.';
            } else {
                $error = 'Password could not be updated.';
            }
        }

        require __DIR__ . '/../views/change_password.php';
    }

    public function createStudent(): void
    {
        $this->requireLogin();
        [$ok, $name, $ic, $marks] = $this->studentInput($_POST);

        if ($ok && $this->model->createStudent($name, $ic, $marks)) {
            $this->redirect('dashboard', 'status=created');
        }

        $this->redirect('dashboard', 'status=error');
    }

    public function editStudent(): void
    {
        $this->requireLogin();
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $student = $id ? $this->model->getStudent($id) : false;

        if (!$student) {
            $this->redirect('dashboard', 'status=not_found');
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$ok, $name, $ic, $marks] = $this->studentInput($_POST);

            if ($ok && $this->model->updateStudent($id, $name, $ic, $marks)) {
                $this->redirect('dashboard', 'status=updated');
            }

            $error = 'Please enter a valid name, 12-digit IC, and marks from 0 to 100.';
            $student = ['id' => $id, 'name' => $name, 'ic' => $ic, 'marks' => $marks];
        }

        require __DIR__ . '/../views/edit_student.php';
    }

    public function deleteStudent(): void
    {
        $this->requireLogin();
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $status = ($id && $this->model->deleteStudent($id)) ? 'deleted' : 'error';
        $this->redirect('dashboard', "status=$status");
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        $this->redirect('login');
    }

    private function requireLogin(): void
    {
        if (empty($_SESSION['logged_in']) || empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }

    private function redirect(string $action, string $query = ''): void
    {
        $url = "index.php?action=$action" . ($query ? "&$query" : '');
        header("Location: $url");
        exit;
    }

    private function validateRegistration(string $name, string $nric, string $program, string $password, string $confirm): ?string
    {
        if ($name === '' || !preg_match('/^\d{12}$/', $nric) || !in_array($program, $this->programs, true)) {
            return 'Please enter a valid name, 12-digit NRIC, and program.';
        }
        if (strlen($password) < 8) {
            return 'Password must contain at least 8 characters.';
        }
        return $password === $confirm ? null : 'Password and confirmation do not match.';
    }

    private function studentInput(array $input): array
    {
        $name = trim($input['name'] ?? '');
        $ic = trim($input['ic'] ?? '');
        $marks = filter_var($input['marks'] ?? null, FILTER_VALIDATE_INT);
        $ok = $name !== '' && preg_match('/^\d{12}$/', $ic) && $marks !== false && $marks >= 0 && $marks <= 100;

        return [$ok, $name, $ic, (int) $marks];
    }

    private function statusMessage(string $type): ?string
    {
        $messages = [
            'success' => [
                'created' => 'Student record added successfully.',
                'updated' => 'Student record updated successfully.',
                'deleted' => 'Student record deleted successfully.',
            ],
            'error' => [
                'error' => 'Request failed. Please check the student data.',
                'not_found' => 'Student record was not found.',
            ],
        ];

        return $messages[$type][$_GET['status'] ?? ''] ?? null;
    }
}
