<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface;
use Illuminate\Support\Str;

abstract class BaseRepository implements RepositoryInterface
{
    //model muốn tương tác
    protected $model;

    //khởi tạo
    public function __construct()
    {
        $this->setModel();
    }

    //lấy model tương ứng
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function find($id, $with = [])
    {
        $result = $this->model->with($with)->find($id);

        return $result;
    }

    public function findSlug($slug, $with = [])
    {
        $result = $this->model->with($with)->where('slug', 'like', $slug )->first();

        return $result;
    }

    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }

    public function update($id, $attributes = [])
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }

    public function delete($id, $relations = [], $relationsManyToMany = [])
    {
        $result = $this->find($id);
        $currentRelation = $result;
        if ($result) {
            if (!empty($relations)) {
                $currentRelations = [];
                foreach ($relations as $key => $relation) {
                    $currentRelation = $currentRelation->$relation();
                    $currentRelations[] = $currentRelation;
                }

                $currentRelations = array_reverse($currentRelations);
                foreach ($currentRelations as $currentRelation) {
                    $currentRelation->delete();
                }
            }

            if (!empty($relationsManyToMany)) {
                $currentRelations = [];
                foreach ($relationsManyToMany as $key => $relationManyToMany) {
                    $currentRelation = $result;
                    $currentRelation = $currentRelation->$relationManyToMany();
                    $currentRelations[] = $currentRelation;
                }

                $currentRelations = array_reverse($currentRelations);
                foreach ($currentRelations as $currentRelation) {
                    $currentRelation->detach();
                }
            }

            $result->delete();

            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function newModel()
    {
        return $this->model;
    }

    public function getPaginate($limit)
    {
        return $this->model->paginate($limit);
    }

    public function getRelatedSlugs($slug)
    {
        return $this->model->select('slug')->where('slug', 'like', $slug . '%')->get();
    }

    public function makeSlug($str, $field = 'name')
    {
        $slug = Str::slug($str);

        $allSlugs = $this->getRelatedSlugs($slug);

        // If we haven't used it before then we are all good.
        if (!$allSlugs->contains($field, $slug)) {
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        $count = 1;
        do {
            $newSlug = $slug . '-' . $count;
            $count++;
        } while ($allSlugs->contains($field, $newSlug));
        return $newSlug;
    }

}
