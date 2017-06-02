<?php

namespace Bhutanio\Laravel\Contracts\Data;

interface DataServiceInterface
{
    /**
     * Cleanup and Format data for saving.
     *
     * @param array $data
     *
     * @return array
     */
    public function format(array $data);

    /**
     * Insert data into database.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Update data.
     *
     * @param int $id primary key
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model|bool
     */
    public function update($id, array $data);

    /**
     * Delete Data.
     *
     * @param int $id primary key
     *
     * @return bool
     */
    public function delete($id);
}
