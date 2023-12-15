<?php

namespace App\Services;

class Service
{
    protected $options = [
        'order_by'  => 'created_at',
        'sort'      => 'desc',
        // 'limit'
    ];

    private function _model_name() {
        return str_replace('Service', '', str_replace('Services', 'Models', static::class));
    }

    public function get(
        string | array $with = '',
        null | array $options = null,
        bool | int $paginate = false,
        ?array $conditions = null) {
            $model_name = $this->_model_name();

            $this->options = $options == null ? $this->options : array_merge($this->options, array_intersect_key($options, $this->options));
            $with = is_array($with) ? implode(', ', $with) : $with;

            $data = $model_name::query();

            if (!empty($with) and is_string($with)) {
                $data->with($with);
            }

            if ($conditions !== null and is_array($conditions)) {
                foreach ($conditions as $c) {
                    if (count($c) != 4) {
                        throw new \Exception('To few arguments.');
                    }

                    $data->{$c[0]}($c[1], $c[2], $c[3]);
                }
            }

            $data->orderBy(
                $this->options['order_by'],
                $this->options['sort']
            );

            ($paginate and is_numeric($paginate)) ? $data = $data->paginate($paginate) : $data = $data->get();

            return $data;
    }

    public function find($id, $relations = [], $class = null) {
        $model_name = $class ? $class : $this->_model_name();

        $relations = is_array($relations) ? $relations : func_get_args();
        $relations = array_filter($relations, function($item) {
            return !is_numeric($item);
        });

        $data = $model_name::query();

        if (!empty($relations)) {
            $data->with($relations);
        }

        return $data->find($id);
    }
}
