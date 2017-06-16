<?php

namespace Bhutanio\Laravel\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DataService
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Set model.
     *
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Cleanup and Format data for saving.
     *
     * @param array $data
     *
     * @return array
     */
    public function format(array $data)
    {
        return $this->fillableData($this->model, $data);
    }

    /**
     * Insert data into database.
     *
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data)
    {
        $data = $this->format($data);

        return $this->model->create($data);
    }

    /**
     * Get fillable table columns for the model
     *
     * @param Model $model
     * @param array $data
     *
     * @return array
     */
    public function fillableData($model, $data)
    {
        $fill_data = [];
        foreach ($model->getFillable() as $fill) {
            if (array_key_exists($fill, $data)) {
                $fill_data[$fill] = !empty($data[$fill]) ? $data[$fill] : null;
            }
        }

        return $fill_data;
    }

    /**
     * Update data.
     *
     * @param int $id primary key
     * @param array $data
     *
     * @return Model|bool
     * @throws ModelNotFoundException
     */
    public function update($id, array $data)
    {
        $data = $this->format($data);
        $model = $this->model->findOrFail($id);

        $model->update($data);

        return $model;
    }

    /**
     * Delete Data.
     *
     * @param int $id primary key
     *
     * @return bool
     */
    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}
