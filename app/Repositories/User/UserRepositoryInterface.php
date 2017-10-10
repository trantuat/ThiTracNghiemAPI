<?php
    namespace App\Repositories\User;
    use Illuminate\Http\Request;
    interface UserRepositoryInterface {
        /**
        * Get all posts only published
        * @return mixed
        */   

        public function login($email, $password);   

        public function createUser(array $attribute);

        public function getInfoUser($userId);

        public function getUserByRole($role_id);
    }
?>