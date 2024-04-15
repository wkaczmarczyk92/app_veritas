<?php

namespace App\Services\Compendium;

use App\Models\Courses\Compendium;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;

class CompendiumDestroyService
{
    public function destroy($id)
    {
        // $compendium = DB::table('compendia')->where('id', $id)->delete();

        $compendium = Compendium::find($id)->delete();

        if ($compendium) {
            return Response::success('Dane zostały usunięte. Za chwilę zostaniesz przeniesiony...');
        } else {
            return Response::danger('Coś poszło nie tak. Spróbuj ponownie później.');
        }
    }
}
