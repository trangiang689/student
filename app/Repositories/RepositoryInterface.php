<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all
     * @return mixed
     */
    public function getAll();

    /**
     * Get one
     * @param $id
     * @param relatetion $with
     * @return mixed
     */
    public function find($id, $with = []);

    /**
     * Get one
     * @param $slug
     * @param relatetion $with
     * @return mixed
     */
    public function findSlug($slug, $with = []);

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes = []);

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, $attributes = []);

    /**
     * Delete
     * @param $id
     * @param array $relations
     * @param array $relationsManyToMany
     * @return mixed
     */
    public function delete($id, $relations = [], $relationsManyToMany = []);

    /**
     * @return mixed
     */
    public function newModel();

    public function getPaginate($limit);

    public function getRelatedSlugs($slug);

    public function makeSlug($str, $field = 'name');
}
