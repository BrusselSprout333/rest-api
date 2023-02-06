<?php

namespace Tests\Unit;

use App\Exceptions\OriginalLinkAlreadyExistsException;
use App\Helpers\Utilites\NewClass;
use App\Helpers\Utilites\ShortLinkGenerator;
use App\Interfaces\LinkRepositoryInterface;
use App\Interfaces\LinkServiceInterface;
use App\Models\LinkDetails;
use Exception;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\MockInterface;
use PhpParser\ErrorHandler\Collecting;
use Tests\TestCase;
use App\Models\Link;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Services\LinkService;
use App\Repositories\LinkRepository;


class PartsTest extends TestCase
{
/*
    public function test_notification() {
        //$mock = $this->getMockClass('ShortLinkGenerator');
        $mock = $this->getMockBuilder('ShortLinkGenerator')
                ->setConstructorArgs(array())
                ->setMockClassName('')
                // отключив вызов конструктора, можно получить Mock объект "одиночки"
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableAutoload()
                ->getMock();

        $mock->expects($this->any())->method('')
        // проверяем, что в $mock находится экземпляр класса MyClass
        $this->assertInstanceOf('ShortLinkGenerator', $mock);
    }*/
    
//db query
/*
    public function test_shortCode_generator_works_correctly() 
    {

        // $this->partialMock(ShortLinkGenerator::class, function (MockInterface $mock) {
        //     $mock->shouldReceive('db_search')
        //         ->with('num')
        //         ->once()
        //         ->andReturn(false);
        // });
        // $mock = Mockery::partialM(new ShortLinkGenerator(new Link));
        // $mock->shouldReceive('db_search')
        //         ->once()
        //         ->with('shortCode')
        //         ->andReturn(false);

        
        $generator = new ShortLinkGenerator(new Link());

        $response = $generator->generateShortLink('http://newlink/here', 1);
        //app(LinkService::class)->create($user['id'], $linkDetails, 0);
        var_dump($response);
        $this->assertEquals(true, $response);
    }*/

    public function test_user_cant_see_links_without_authorization() //ok
    {
        $response = $this->getJson('/api/links');

        $response->assertUnauthorized();
    }

    /*
    //miss all before LinkRepository - db query
    public function test_receives_all_data_when_returns_link() 
    {
        $user = User::factory()->create();
        $link = Link::factory()->create([
            'userId' => $user['id'],
        ]);
        $response = $this->actingAs($user)->getJson('/api/links/' . $link['id']);
        //$response = app(LinkRepository::class)->getById($link['id']);

        //$this->assertEquals(3, $response);
        //print_r($response); //object

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
    }*/
/*
    //запрос в бд
    public function test_originalLinkAlreadyExistsException_is_thrown()
    {
        // $user = User::factory()->create();
        // $link = Link::factory()->create([
        //     'userId' => $user['id'],
        // ]);
        $this->mock(LinkRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getLinkByOriginalLink')
                ->with('123')
                ->once()
                ->andReturn(
                    [1 => [
                        'userId' => 1
                    ],
                    2 => [
                        'userId' => 2
                    ],]);
        });

        // $this->assertModelExists($link);
        
        $linkDetails = new LinkDetails(new Link());
        $linkDetails->setOriginalUrl($link['originalUrl']);
        $linkDetails->setIsPublic($link['isPublic']);

       // $mock = Mockery::mock(new Link);

        // $mock->shouldReceive('where')
        //     ->once()
        //     ->with(1, $linkDetails)
        //     ->andReturn(Mockery::mock('Illuminate\Database\Eloquent\Builder'), function ($mock) {
        //         $mock->shouldReceive('geet')
        //             ->once()
        //             ->with()
        //             ->andReturn(false);
        //         }); //Query\Builder
        // $mock->shouldReceive('get')
        //     ->once()
        //     ->with()
        //     ->andReturn([1 => 'f',2 => 'g']);

        $this->expectException(OriginalLinkAlreadyExistsException::class);
        app(LinkService::class)->create($user['id'], $linkDetails, 0); //попытка создать идентичную ссылку
    }
*/
    /*
    public function test_InvalidArgumentException_is_thrown_on_link_delete() //ok
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
    }*/
/*
    public function test_update()
    {
        $linkDetails = new LinkDetails(new Link());
        $linkDetails->setIsPublic((bool)1);
        $linkDetails->setOriginalUrl('http://newlink');
        // $linkDetails->setIsPublic((bool)1);
        // $linkDetails->setOriginalUrl('http://newlink');
        //var_dump($linkDetails);
       // $linkDetails = 'dfdfd';

        $this->mock(LinkRepositoryInterface::class, function (MockInterface $mock) {
            $linkDetails = new LinkDetails(new Link());
            $mock->shouldReceive('update')
                ->once()
                ->with(2, 'ssss', $linkDetails)
                ->andThrow(new Exception('you dont have access'));
        });

        // DB::shouldReceive('beginTransaction')->once();
        // DB::shouldReceive('rollBack')->once();
        // Log::shouldReceive('info')->once()->with('you dont have access');

        $this->expectException(\InvalidArgumentException::class);
        app(LinkService::class)->update(2,'ssss', $linkDetails);
    }*/
}