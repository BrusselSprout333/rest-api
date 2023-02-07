<?php

namespace Tests\Unit;

use App\Exceptions\OriginalLinkAlreadyExistsException;
use App\Helpers\Utilites\ShortLinkGenerator;
use App\Interfaces\LinkRepositoryInterface;
use App\Mail\CreateLinkMail;
use App\Mail\DeleteLinkMail;
use App\Models\LinkDetails;
use App\Services\NotificationsService;
use App\Services\UserService;
use Exception;
use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Link;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Services\LinkService;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Utilites\SmsCredentials;
use App\Helpers\Utilites\SmsMessage;


class PartsTest extends TestCase
{
    public function test_shortCode_generator_works_correctly() 
    {
        $this->mock(LinkRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getByShortCode') 
                ->with('newlink-here1')
                ->once()
                ->andReturn(false);
        });

        $response = app(ShortLinkGenerator::class)->generateShortLink('http://newlink/here', 1);

        $this->assertEquals('newlink-here1', $response);
    }

    public function test_user_cant_see_links_without_authorization() 
    {
        $response = $this->getJson('/api/links');

        $response->assertUnauthorized();
    }

    public function test_mailable_content_on_link_create() 
    {
        $link = 'http://new-link.com';
        $mailable = new CreateLinkMail($link);

        $mailable->assertHasSubject("You've created a link");
        $mailable->assertFrom('linkShortener@gmail.com');
        $mailable->assertSeeInHtml($link);
        $mailable->assertSeeInOrderInHtml(["LinkShortener.com", "You've created a new link"]);
    }

    public function test_send_mail_on_link_delete() 
    {
        $email = 'jake@morris.com';
        $link = 'http://delete-link.com';

        $notification = 
            new NotificationsService(
                new UserController(
                    new UserService()
                ),
                SmsCredentials::getInstance(),
                new SmsMessage()
        );
        Mail::fake();
        $notification->linkDeletedMail($email, $link);

        Mail::assertSent(DeleteLinkMail::class, function ($mail) use ($email) {
        return $mail->hasTo($email) &&
                $mail->hasFrom('linkShortener@gmail.com') &&
                $mail->hasSubject("You've deleted a link");
        });
    }

    public function test_originalLinkAlreadyExistsException_is_thrown() 
    {
        $user = User::factory()->create();
        //создаем первую ссылку
        $link = Link::factory()->create([ 
            'userId' => $user['id'],
        ]); 
        
        $linkDetails = new LinkDetails(new Link());
        $linkDetails->setOriginalUrl($link['originalUrl']);
        $linkDetails->setIsPublic($link['isPublic']);

        $this->mock(LinkRepositoryInterface::class, function (MockInterface $mock) use ($link, $linkDetails) {
            $mock->shouldReceive('getByOriginalLink')
                ->with($linkDetails->getOriginalUrl())
                ->once()
                ->andReturn([$link]);
        });

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