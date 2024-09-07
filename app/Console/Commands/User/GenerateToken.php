<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;
use Laravel\Passport\Passport;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:generate-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate user token';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->ask('输入用户ID');

        $user = User::find($userId);

        if (!$user) {
            return $this->error('用户不存在');
        }

        Passport::personalAccessTokensExpireIn(now()->addDays(365));

        $token = $user->createToken('User token generate');

        $this->info($token->accessToken);

    }
}
