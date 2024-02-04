<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Audio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AudioController
 */
final class AudioControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $audio = Audio::factory()->count(3)->create();

        $response = $this->get(route('audio.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AudioController::class,
            'store',
            \App\Http\Requests\AudioStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $url = $this->faker->url();

        $response = $this->post(route('audio.store'), [
            'url' => $url,
        ]);

        $audio = Audio::query()
            ->where('url', $url)
            ->get();
        $this->assertCount(1, $audio);
        $audio = $audio->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $audio = Audio::factory()->create();

        $response = $this->get(route('audio.show', $audio));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AudioController::class,
            'update',
            \App\Http\Requests\AudioUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $audio = Audio::factory()->create();
        $url = $this->faker->url();

        $response = $this->put(route('audio.update', $audio), [
            'url' => $url,
        ]);

        $audio->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($url, $audio->url);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $audio = Audio::factory()->create();

        $response = $this->delete(route('audio.destroy', $audio));

        $response->assertNoContent();

        $this->assertModelMissing($audio);
    }
}
