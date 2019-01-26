<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-25
 * Time: 11:54
 */

namespace WebAppId\User\Commands;

use Illuminate\Console\Command;

class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webappid:user:seed';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed database';
    
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
        \Artisan::call('db:seed', ['--class' => 'WebAppId\User\Seeds\DatabaseSeeder']);
        $this->info('Seeded: WebAppId\User\Seeds\UserSeeder');
    }
}