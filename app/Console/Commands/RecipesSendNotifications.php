<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Recipe;
use App\User;
use App\Mail\RecipeEdited;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RecipesSendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recipe:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for edited recipes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all('email');

        $editedRecipes = Recipe
            ::where('final_version_edited', 1)
            ->where('final_version_edited_at', '<', date('Y-m-d H:i:s', strtotime('-15 minutes')))
            ->get();

        foreach ($editedRecipes as $recipe) {
            foreach ($users as $user) {
                $email = $user->email;

                if (strpos($email, 'ficosota.com') === false) {
                    continue;
                }

                if (strpos($email, 'ivanita.ivanova') === false) {
                    continue;
                }

                Mail::to($email)->send(new RecipeEdited($recipe));
                Log::info("Notification about '" . $recipe->getName() . "' was sent to '" . $email . "'");
            }

            $recipe->final_version_edited = 0;
            $recipe->update();
        }
    }
}
