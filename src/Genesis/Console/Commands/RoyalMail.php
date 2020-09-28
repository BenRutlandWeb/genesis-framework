<?php

namespace Genesis\Console\Commands;

use Genesis\Console\Command;

class RoyalMail extends Command
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'royalmail {name : The name of the person to send to}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Post a letter with royalmail';

    /**
     * Handle the command call.
     *
     * @return void
     */
    protected function handle(): void
    {
        $this->multiline("
    ____                        __   __  ___        _  __
   / __ \ ____   __  __ ____ _ / /  /  |/  /____ _ (_)/ /
  / /_/ // __ \ / / / // __ `// /  / /|_/ // __ `// // /
 / _, _// /_/ // /_/ // /_/ // /  / /  / // /_/ // // /
/_/ |_| \____/ \__, / \__,_//_/  /_/  /_/ \__,_//_//_/
              /____/
");

        if (!$this->confirm('Do you want to send a message?')) {
            $this->terminate('No problem, another time perhaps.');
        }

        $to = $this->argument('name');

        $from = $this->ask('Who is the letter from?') ?: 'Royal Mail';

        $this->line('-------------------------------------------------------------')
            ->line("Hello {$to},")
            ->line('')
            ->line('I hope you are well.')
            ->line('This message was posted to you by the royalmail console command.')
            ->line('We hope you are completely satisfied with this service.')
            ->line('')
            ->line('')
            ->line('Yours sincerely,')
            ->line('')
            ->line($from)
            ->line('-------------------------------------------------------------');

        $this->terminate();
    }
}
