<?php

namespace Tests\Unit;

use App\Exceptions\OriginalLinkAlreadyExistsException;
use App\Helpers\Utilites\ShortLinkGenerator;
use App\Interfaces\LinkRepositoryInterface;
use App\Models\LinkDetails;
use Exception;
use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Link;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Services\LinkService;


class PartsTest extends TestCase
{

    public function test_shortCode_generator_works_correctly() 
    {
        $mock = $this->mock(Link::class, function (MockInterface $mock) {
            $mock->shouldReceive('first')
                ->once()
                ->andReturn(false);
        });

        $generator = new ShortLinkGenerator(new Link());

        $response = $generator->generateShortLink('http://newlink/here', 1);

        $this->assertEquals('newlink-here1', $response);
    }

    public function test_user_cant_see_links_without_authorization()
    {
        $response = $this->getJson('/api/links');

        $response->assertUnauthorized();
    }

    public function test_receives_all_data_when_returns_link()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create([
            'userId' => $user['id'],
        ]);
        $response = $this->actingAs($user)->getJson('/api/links/' . $link['id']);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'userId',
                'originalUrl',
                'shortCode',
                'isPublic',
                'createdDate'
            ]
        ]);
    }

    public function test_originalLinkAlreadyExistsException_is_thrown()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create([
            'userId' => $user['id'],
        ]);

        $linkDetails = new LinkDetails(new Link());
        $linkDetails->setOriginalUrl($link['originalUrl']);
        $linkDetails->setIsPublic($link['isPublic']);

        $this->expectException(OriginalLinkAlreadyExistsException::class);
        app(LinkService::class)->create($user['id'], $linkDetails, 0); //попытка создать идентичную ссылку
    }

    public function test_InvalidArgumentException_is_thrown_on_link_delete()
    {
        $this->mock(LinkRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')
                ->with(2)
                ->once()
                ->andThrow(new Exception('you dont have access'));
        });

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();
        Log::shouldReceive('info')->once()->with('you dont have access');

        $this->expectException(\InvalidArgumentException::class);
        app(LinkService::class)->delete(2);
    }
}