<?php
    namespace App\Repositories;
    use App\Model\User;

    abstract class BaseRepository implements RepositoryInterface
    {
    /**
        * @var \Illuminate\Database\Eloquent\Model
        */
        protected $_model;

        /**
        * EloquentRepository constructor.
        */
        public function __construct()
        {
            $this->setModel();
        }

        /**
        * get model
        * @return string
        */
        abstract public function getModel();

        /**
        * Set model
        */
        public function setModel()
        {
            $this->_model = app()->make(
                $this->getModel()
            );
        }

        /**
        * Get All
        * @return \Illuminate\Database\Eloquent\Collection|static[]
        */
        public function getAll()
        {
            return $this->_model->all();
        }

        /**
        * Get one
        * @param $id
        * @return mixed
        */
        public function find($id)
        {
            $result = $this->_model->find($id);
            return $result;
        }

        /**
        * Create
        * @param array $attributes
        * @return mixed
        */
        public function create(array $attributes)
        {
            return $this->_model->create($attributes);
        }

        public function insertGetId(array $attributes) 
        {
            return $this->_model->insertGetId($attributes);
        }

        /**
        * Update
        * @param $id
        * @param array $attributes
        * @return bool|mixed
        */
        public function update($id, array $attributes)
        {
            $result = $this->find($id);
            if($result) {
                $result->update($attributes);
                return $result;
            }
            return false;
        }

        public function updateWith(array $where, array $attributes) 
        {
            return $this->_model->where($where)
                ->update($attributes);
        }

        /**
        * Delete
        * 
        * @param $id
        * @return bool
        */
        public function delete($id)
        {
            $result = $this->find($id);
            if($result) {
                $result->delete();
                return true;
            }

            return false;
        }

        public function with($relations)
        {
            return $this->_model->with($relations);
        }

        public function insert($attributes) {
            return $this->_model->insert($attributes);
        }

        public function where(array $where, $columns = array('*')) 
        {
            return $this->_model->where($where)->get($columns);
        }
    }
?>