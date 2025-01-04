<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->hears('{message}', function ($botman, $message) {
            if (strtolower($message) == 'bonjour') {
                $this->askQuestion($botman);
            } else {
                $botman->reply("Commencez la conversation par un 'Bonjour'.");
            }
        });

        $botman->listen();
    }

    public function askQuestion($botman)
    {
        $botman->ask('Bonjour, comment puis-je vous aider aujourd’hui ?', function (Answer $answer) {
            $question = $answer->getText();
            $this->say('Votre question est bien la suivante : ' . $question);
        });
    }
}
