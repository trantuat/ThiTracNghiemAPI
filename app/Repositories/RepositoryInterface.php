<?php
    namespace App\Repositories;
    
    interface RepositoryInterface
    {
        public function getAll();

        public function find($id);

        public function create(array $attributes);

        public function insertGetId(array $attributes);
    
        public function update($id, array $attributes);

        public function updateWith(array $where, array $attributes) ;

        public function delete($id);

        public function with($relations);

        public function insert($attributes);

        public function where(array $where, $columns = array('*'));
    }
?>