<?php

namespace App\Services\Search;

use App\Models\Courses\Lesson;
use App\Models\Courses\Compendium;

class CourseSearchService
{
    public function search($input)
    {
        $lessons = Lesson::with('lessonable', 'lessonable.type')
            ->where('name', 'like', "%$input%")
            ->orWhere('description', 'like', "%$input%")
            ->limit(5)
            ->get();

        foreach ($lessons as $lesson) {
            $lesson->description = preg_replace('/\s\s+/', ' ', html_entity_decode(strip_tags($lesson->description)));
        }

        // dla rekruterow ma sie szukac tylko w opublikowanych
        // $all = Compendium::where('visibility_id', 2)->where(function ($query) use ($input) {
        //     $query->where('name', 'like', "%$input%")->orWhere('description', 'like', "%$input%");
        // })->get();

        $all = Compendium::where('name', 'like', "%$input%")->orWhere('description', 'like', "%$input%")->get();

        $all = $all->map(function ($compendium) {
            $compendium->description = preg_replace('/\s\s+/', ' ', html_entity_decode(strip_tags($compendium->description)));
            return $compendium;
        });

        $compendiums = $all->filter(function ($compendium) {
            return $compendium->compendium_type_id === 1;
        });

        $vademecums = $all->filter(function ($compendium) {
            return $compendium->compendium_type_id === 2;
        });

        if (count($compendiums) > 5) {
            $compendiums = $compendiums->slice(0, 5)->values();
        } else {
            $compendiums = $compendiums->values();
        }

        if (count($vademecums) > 5) {
            $vademecums = $vademecums->slice(0, 5)->values();
        } else {
            $vademecums = $vademecums->values();
        }

        return [
            'lessons' => $lessons,
            'compendiums' => $compendiums,
            'vademecums' => $vademecums
        ];
    }
}
