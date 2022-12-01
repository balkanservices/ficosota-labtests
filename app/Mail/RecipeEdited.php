<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Recipe;

class RecipeEdited extends Mailable
{
    use Queueable, SerializesModels;

    public $recipe;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('LabTests: "' . $this->recipe->getName() . '" е променена. ')
            ->view('recipes/notification_email');
    }
}
