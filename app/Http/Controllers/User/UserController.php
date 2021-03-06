<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\InfoRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use App\Model\Token;

class UserController extends Controller
{
    protected $userRepository;
    protected $infoRepository;
    
    public function __construct(UserRepositoryInterface $userRepository, InfoRepositoryInterface $infoRepository)
    {
        $this->userRepository = $userRepository;
        $this->infoRepository = $infoRepository;
    }

    public function getUserById(Request $request) 
    {
        try {
            $userId = $this->getUserId($request);
            if ($userId == -1){
                return  $this->Unauthentication();
            }
            $user = $this->userRepository->getInfoUser($userId);
            if ($user == null) {
                return $this->BadRequest("User not found");
            }
             return $this->OK($user);
       } catch (\Exception $ex) {
           return $this->BadRequest($ex);
       }
    }

    public function getAll(Request $request) 
    {
        try {
            $user = $this->userRepository->getAll();
            if ($user == null) {
                return $this->BadRequest("User not found");
            }
            return $this->OK($user);
       } catch (\Exception $ex) {
           return $this->BadRequest($ex);
       }
    }

    public function updateUser(Request $request)
    {
        try {
            $address = $request->address;
            $phone = $request->phone;
            $fullname = $request->fullname;
            $birthday = $request->birthday;
            $userId = $this->getUserId($request);
            if ($userId == -1){
                return  $this->Unauthentication();
            }
            $username = $request->username;
            $this->userRepository->updateWith([['id',$userId]], ['username'=>$username]);
            $this->infoRepository->updateWith([['user_id',$userId]],['address'=>$address,'phone'=>$phone,'fullname'=>$fullname,'day_of_birth'=>$birthday]);
            $user = $this->userRepository->getInfoUser($userId);
            return $this->OK($user);
        } catch (\Exception $ex) {
            return $this->BadRequest($ex);
        }
    }
    
    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email|max:255',
                'password' => 'required|min:6|max:25'
            ]);
            $email = $request->email;
            $password = $request->password;
            $user = $this->userRepository->login($email,md5($password));
            if ($user == null) {
                return  $this->BadRequest("Email or password wrong");
            }
            $api_token = str_random(120);
            $token = Token::insert(['user_id'=>$user->id,'api_token'=>$api_token]);
            $user = json_decode($user);
            $user->api_token = $api_token;
            return  $this->OK($user);

         } catch (\Exception $ex) {
             return $this->BadRequest("Email or password wrong");
         }
    }

    public function logout(Request $request)
    {
        try {
            $api_token = $request->header('api_token');
            $userId = $this->getUserId($request);
            if ($userId == -1){
                return  $this->Unauthentication();
            }
            Token::where('api_token', $api_token)->where('user_id',$userId)->delete();
            return  $this->OK(true);

         } catch (\Exception $ex) {
             return $this->BadRequest("Unable logout");
         }
    }


    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|max:25',
                'role' => 'required|digits:1',
                'username' => 'required|string|max:255|min:6'
            ]);
            $username = $request->username;
            $password = md5($request->password);
            $role = $request->role;
            $email = $request->email;
            $gender = $request->gender;
            $address = $request->address;
            $phone = $request->phone;
            $birthday = $request->birthday;
            $fullname = $request->fullname;
            try {
                $userId = $this->userRepository->createUser(['username'=>$username,
                                                                'password'=>$password,
                                                                'role_user_id'=>$role, 
                                                                'email'=>$email, 
                                                                'is_active'=>1,
                                                                'created_at'=>date("Y-m-d H:m:s"),
                                                                'updated_at'=>date("Y-m-d H:m:s")]);
                $this->infoRepository->insert(['address'=>$address,
                                                'phone'=>$phone,
                                                'fullname'=>$fullname, 
                                                'gender'=>$gender,
                                                'day_of_birth'=>$birthday, 
                                                'user_id'=>$userId, 
                                                'created_at'=>date("Y-m-d H:m:s"),
                                                'updated_at'=>date("Y-m-d H:m:s")]);
                return $this->OK("Create account successfully");
            } catch (\Exception $ex) {
                return $this->BadRequest("Error create account");
            }

        } catch (\Exception $ex) {
            return $this->BadRequest("Unvalid field");
        }
    }

    public function changePassword(Request $request) 
    {
        try {
            $this->validate($request, [
                'email' => 'required|email|max:255',
                'old_password' => 'required|min:6|max:25',
                'new_password' => 'required|min:6|max:25'
            ]);

            $email = $request->email;
            $oldPassword = $request->old_password;
            $newPassword = $request->new_password;

            $success = $this->userRepository->updateWith([['email',$email],['password',md5($oldPassword)]],['password'=>md5($newPassword)]);
            if ($success) {
               return  $this->OK("Change password successfully");
            }
            return $this->OK("Password wrong");
        } catch (\Exception $ex) {
            return $this->BadRequest("Unvalid field");
        }
    }
}
