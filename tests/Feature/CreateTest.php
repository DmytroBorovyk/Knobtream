<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\JobVacancy;
use App\Models\User;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_job_creation()
    {
        $user = User::factory()->create();
        $user->balance = 5;
        $user->save();

        $response = $this
            ->actingAs($user)
            ->post('/api/catalog/job', ['title' => 'test', 'description' => 'test', 'tags' => [1]]);

        $response->assertStatus(201);
    }

    /**
     *
     * @return void
     */
    public function test_response_creation()
    {
        $user_job_creator = User::factory()->create();

        $user = User::factory()->create();
        $user->balance = 5;
        $user->save();

        $vacancy = new JobVacancy();
        $vacancy->user_id = $user_job_creator->getKey();
        $vacancy->title = 'test';
        $vacancy->description = 'description';
        $vacancy->save();

        $response = $this
            ->actingAs($user)
            ->post('/api/response/', ['job_id' => $vacancy->getKey(), 'review_text' => 'test']);

        $response->assertStatus(201);
    }
}
