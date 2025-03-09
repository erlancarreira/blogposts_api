<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();
        
        Post::create([
            'title'   => 'Notion',
            'author'  => $userIds[0],
            'content' => 'Sed soluta nemo et consectetur reprehenderit ea reprehenderit sit. Aut voluptate sit omnis qui repudiandae. Cum sit provident eligendi tenetur facere ut quo. Commodi voluptate ut aut deleniti.',
            'tags'    => ['organization', 'planning', 'collaboration', 'writing', 'calendar'],
        ]);

        Post::create([
            'title'   => 'json-server',
            'author'  => $userIds[0],
            'content' => 'Laudantium illum modi tenetur possimus natus. Sed tempora molestiae fugiat id dolor rem ea aliquam. Ipsam quibusdam quam consequuntur. Quis aliquid non enim voluptatem nobis. Error nostrum assumenda ullam error eveniet. Ut molestiae sit non suscipit.
Qui et eveniet vel. Tenetur nobis alias dicta est aut quas itaque non. Omnis iusto architecto commodi molestiae est sit vel modi. Necessitatibus voluptate accusamus.',
            'tags'    => ['api', 'json', 'schema', 'node', 'github', 'rest'],
        ]);

        Post::create([
            'title'   => 'fastify',
            'author'  => $userIds[1],
            'content' => 'Eos corrupti qui omnis error repellendus commodi praesentium necessitatibus alias. Omnis omnis in. Labore aut ea minus cumque molestias aut autem ullam. Consectetur et labore odio quae eos eligendi sit. Quam placeat repellendus.
Odio nisi dolores dolorem ea. Qui dicta nulla eos quidem iusto. Voluptatibus qui est accusamus sint perferendis est quae recusandae. Qui repudiandae cupiditate fugiat est.',
            'tags'    => ['web', 'framework', 'node', 'http2', 'https', 'localhost'],
        ]);

        Post::factory(10)->create();
    }
}
