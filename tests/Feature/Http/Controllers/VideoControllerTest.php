<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\VideoController
 */
final class VideoControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $videos = Video::factory()->count(3)->create();

        $response = $this->get(route('video.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\VideoController::class,
            'store',
            \App\Http\Requests\VideoStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $url = $this->faker->url();

        $response = $this->post(route('video.store'), [
            'url' => $url,
        ]);

        $videos = Video::query()
            ->where('url', $url)
            ->get();
        $this->assertCount(1, $videos);
        $video = $videos->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $video = Video::factory()->create();

        $response = $this->get(route('video.show', $video));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\VideoController::class,
            'update',
            \App\Http\Requests\VideoUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $video = Video::factory()->create();
        $url = $this->faker->url();

        $response = $this->put(route('video.update', $video), [
            'url' => $url,
        ]);

        $video->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($url, $video->url);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $video = Video::factory()->create();

        $response = $this->delete(route('video.destroy', $video));

        $response->assertNoContent();

        $this->assertModelMissing($video);
    }
}
