<?php
     namespace App\Repositories\User;
     use App\Repositories\BaseRepository;

     class InfoRepository extends BaseRepository implements InfoRepositoryInterface {
       
        public function getModel()
        {
            return \App\Model\Info::class;
        }
     }
?>