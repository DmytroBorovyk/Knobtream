<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\JobVacancy;
use App\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_job_update()
    {
        $user = User::factory()->create();

        $vacancy = new JobVacancy();
        $vacancy->user_id = $user->getKey();
        $vacancy->title = 'test';
        $vacancy->description = 'description';
        $vacancy->save();

        $response = $this
            ->actingAs($user)
            ->put('/api/catalog/job/'.$vacancy->getKey(),
                ['title' => 'test2', 'description' => 'test2', 'tags' => [1]]);

        $response->assertStatus(200);
    }
}
