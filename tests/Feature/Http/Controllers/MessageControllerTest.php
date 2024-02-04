<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\MessageController
 */
final class MessageControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $messages = Message::factory()->count(3)->create();

        $response = $this->get(route('message.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\MessageController::class,
            'store',
            \App\Http\Requests\MessageStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $text = $this->faker->word();

        $response = $this->post(route('message.store'), [
            'user_id' => $user->id,
            'group_id' => $group->id,
            'text' => $text,
        ]);

        $messages = Message::query()
            ->where('user_id', $user->id)
            ->where('group_id', $group->id)
            ->where('text', $text)
            ->get();
        $this->assertCount(1, $messages);
        $message = $messages->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $message = Message::factory()->create();

        $response = $this->get(route('message.show', $message));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\MessageController::class,
            'update',
            \App\Http\Requests\MessageUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $message = Message::factory()->create();
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $text = $this->faker->word();

        $response = $this->put(route('message.update', $message), [
            'user_id' => $user->id,
            'group_id' => $group->id,
            'text' => $text,
        ]);

        $message->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user->id, $message->user_id);
        $this->assertEquals($group->id, $message->group_id);
        $this->assertEquals($text, $message->text);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $message = Message::factory()->create();

        $response = $this->delete(route('message.destroy', $message));

        $response->assertNoContent();

        $this->assertModelMissing($message);
    }
}
