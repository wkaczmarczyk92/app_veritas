<?php

class Mapper
{
    public $mapped = [];

    public function map($array_map, $values)
    {
        while (!empty($values)) {
            $index = 0;

            if (array_key_exists($values[$index], $array_map)) {
                $current_map_item = $array_map[$values[$index]];

                if (isset($current_map_item['first_label'])) {
                    $second_line = $values[$index + 2];

                    if (array_key_exists($second_line, $array_map)) {
                        $this->mapped[$current_map_item['single']] = $values[$index + 1];
                        $values = $this->remove($values, $index);
                    } else {
                        $this->mapped[$current_map_item['first_label']] = $values[$index + 1];
                        $this->mapped[$current_map_item['second_label']] = $values[$index + 2];
                        $values = $this->remove($values, $index, true);
                    }
                } else {
                    if (isset($current_map_item['multiple']) && $current_map_item['multiple'] === true) {
                        $items = $values[$index + 1];

                        if (isset($current_map_item['exception']) && $current_map_item['exception'] === true) {
                            $items = $this->{$current_map_item['callback']}($items);
                        }

                        $items = explode(',', $items);
                        $items = array_map('trim', $items);

                        $this->mapped[$current_map_item['label']] = array_filter($items, fn ($el) => !empty($el));

                        if (isset($current_map_item['nested_value_name'])) {
                            $this->mapped[$current_map_item['nested_label']] = $this->{$current_map_item['nested_value_name']};
                        }
                    } else {
                        $this->mapped[$current_map_item['label']] = $values[$index + 1];
                    }

                    $values = $this->remove($values, $index);
                }
            } else {
                unset($values[$index]);
            }

            $values = array_values($values);
        }
    }

    public $additional_diseases = [];

    public function extract_diseases($diseases_string)
    {
        $additional_diseases_values = [
            'Ansteckende Krankheit',
            'Allergien',
            'Sonstige'
        ];

        $max = count($additional_diseases_values);
        $i = 0;


        do {
            if (($position = strpos($diseases_string, $additional_diseases_values[$i])) !== false) {
                $additional_diseases_string = substr($diseases_string, $position);
                $diseases_string = substr($diseases_string, 0, $position);
            }

            $i++;
        } while ($i < $max);

        if (isset($additional_diseases_string)) {
            $additional_diseases_texts = explode('wenn ja, welche:', $additional_diseases_string);
            $labels = $additional_diseases_texts[0];

            $labels = explode(',', $labels);
            $labels = array_map('trim', $labels);
            $labels = array_values($labels);

            unset($additional_diseases_texts[0]);
            $additional_diseases_texts = array_values(array_map('trim', $additional_diseases_texts));

            for ($i = 0; $i < count($additional_diseases_texts); $i++) {
                $this->additional_diseases[$i]['label'] = $labels[$i];
                $this->additional_diseases[$i]['value'] = $additional_diseases_texts[$i];
            }
        }

        return $diseases_string;
    }

    public function remove($array, $index, $double = false)
    {
        unset($array[$index]);
        unset($array[$index + 1]);

        if ($double) {
            unset($array[$index + 2]);
        }

        return $array;
    }
}
