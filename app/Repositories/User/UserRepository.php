<?php
     namespace App\Repositories\User;
     use App\Repositories\BaseRepository;
     use Illuminate\Http\Request;

     class UserRepository extends BaseRepository implements UserRepositoryInterface {
       
        public function getModel()
        {
            return \App\Model\User::class;
        }

        public function login($email, $password)
        {
            return  $this->with('roles')->with('info')->where([['email',$email],['password',$password],['is_active',1]])->first();
        }

        public function createUser(array $attribute) 
        {
            return $this->_model->insertGetId($attribute);
        }

        public function getInfoUser($userId)
        {
            return $this->with('info')
                        ->where('id',$userId)
                        ->first();
        }

        public function getUserByRole($role_id){
            return $this->_model->join('info','users.id','=','info.user_id')
                                ->join('roles','users.role_user_id','=','roles.id')
                                ->where('users.role_user_id',$role_id)
                                ->get();
        }

        public function numberTeacher(){
            return $this->_model->where('role_user_id',2)
                                ->count();
        }

        public function numberStudent(){
            return $this->_model->where('role_user_id',1)
                                ->count();
        }

        public function getIsActiveUser($userID){
            return $this->_model->where('id',$userID)
                                ->select('is_active')
                                ->get();
        }

        public function blockUser(){
            
        }

        public function checkEmail($email){
            $checkEmail = $this->_model->where('email',$email)
                                       ->count();
            return $checkEmail;
        }
     }
?>