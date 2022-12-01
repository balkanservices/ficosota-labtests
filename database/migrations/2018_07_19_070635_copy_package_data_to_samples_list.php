<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SamplesList;
class CopyPackageDataToSamplesList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $samplesLists = SamplesList::all();
        foreach ($samplesLists as $samplesList) {
            $samplesPackage = $samplesList->packages()->first();
            if (!$samplesPackage) {
                continue;
            }
            $samplesList->manifacturing_time = $samplesPackage->manifacturing_time;
            $samplesList->samples_count = $samplesPackage->samples_count;
            $samplesList->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
