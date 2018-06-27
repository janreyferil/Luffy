<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    // $this->call(UsersTableSeeder::class);
      factory(App\User::class,5)->create();
      factory(App\Status::class,4)->create();
   //  factory(App\Report::class,5)->create();
    // factory(App\Post::class,20)->create();
     //factory(App\PostComment::class,40)->create();
     //factory(App\PostReact::class,5)->create();
     //factory(App\CommentReact::class,5)->create();
    }
}
