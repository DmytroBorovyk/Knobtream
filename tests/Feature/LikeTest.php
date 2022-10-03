<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\JobVacancy;
use App\Models\User;
use Tests\TestCase;

class LikeTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_job_like()
    {
        $user = User::factory()->create();

        $vacancy = new JobVacancy();
        $vacancy->user_id = $user->getKey();
        $vacancy->title = 'test';
        $vacancy->description = 'description';
        $vacancy->save();

        $response = $this
            ->actingAs($user)
            ->post('/api/like-toggle', ['liked_id' => $vacancy->getKey(), 'type' => 'job']);

        $response->assertStatus(200);
    }

    /**
     *
     * @return void
     */
    public function test_user_like()
    {
        $user_for_like = User::factory()->create();
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/api/like-toggle', ['liked_id' => $user_for_like->getKey(), 'type' => 'user']);

        $response->assertStatus(200);
    }
}
