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
     }
?>