<?php

namespace Victorlap\Approvable\Tests;

use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Victorlap\Approvable\Tests\Models\Post;
use Victorlap\Approvable\Tests\Models\PostCanBeApproved;
use Victorlap\Approvable\Tests\Models\PostCannotBeApproved;

class ApprovableTest extends TestCase
{
    public function test_approvable_can_be_created()
    {
        $post = $this->createPost(PostCanBeApproved::class);

        $this->assertTrue($post->exists);
    }

    public function test_unapprovable_can_be_created()
    {
        $post = $this->createPost(PostCannotBeApproved::class);

        $this->assertTrue($post->exists);
    }

    public function test_approvable_can_be_edited()
    {
        $post = $this->createPost(PostCanBeApproved::class);

        $post->title = 'Bad Post';
        $post->save();

        $this->assertEquals('Bad Post', $post->title);
    }

    public function test_unnapprovable_cannot_be_edited()
    {
        $post = $this->createPost(PostCannotBeApproved::class);

        $post->title = 'Bad Post';
        $post->save();

        $this->assertEquals('Cool Post', $post->title);
    }

    public function test_unnapprovable_cannot_add_attribute()
    {
        $post = $this->createPost(PostCannotBeApproved::class);

        $post->body = 'Bad Body';
        $post->save();

        $this->assertNull($post->body);
    }

    public function test_approve_of_array()
    {
        $post = $this->createPost(PostCannotBeApproved::class);
        $post->approveOf = ['title'];

        $post->title = 'Bad Post';
        $post->body = 'Bad Body';
        $post->save();

        $this->assertEquals('Cool Post', $post->title);
        $this->assertEquals('Bad Body', $post->body);
    }

    public function test_dont_approve_of_array()
    {
        $post = $this->createPost(PostCannotBeApproved::class);
        $post->dontApproveOf = ['title'];

        $post->title = 'Bad Post';
        $post->body = 'Bad Body';
        $post->save();

        $this->assertEquals('Bad Post', $post->title);
        $this->assertNull($post->body);
    }

    public function test_is_pending_approval()
    {
        $post = $this->createPost(PostCannotBeApproved::class);

        $this->assertFalse($post->isPendingApproval());

        $post->title = 'Bad Post';
        $post->save();

        $this->assertTrue($post->isPendingApproval());
    }

    public function test_has_pending_attributes()
    {
        $post = $this->createPost(PostCannotBeApproved::class);

        $this->assertFalse($post->isPendingApproval('title'));

        $post->title = 'Bad Post';
        $post->save();

        $this->assertTrue($post->isPendingApproval('title'));
    }

    public function test_list_pending_attributes()
    {
        $post = $this->createPost(PostCannotBeApproved::class);

        $this->assertEquals(collect(), $post->getPendingApprovalAttributes());

        $post->title = 'Bad Post';
        $post->save();
        $this->assertEquals(collect('title'), $post->getPendingApprovalAttributes());
    }

    public function test_default_authorization_approved()
    {
        $post = $this->createPost(Post::class);
        $user = Mockery::mock(Authenticatable::class)
            ->shouldReceive('can')
            ->with('approve', $post)
            ->andReturnTrue()
            ->getMock();
        Auth::shouldReceive('check')->once()->andReturnTrue();
        Auth::shouldReceive('user')->once()->andreturn($user);

        $post->title = 'Bad Post';
        $post->save();
        $this->assertEquals('Bad Post', $post->title);
    }

    public function test_default_authorization_fails()
    {
        $post = $this->createPost(Post::class);
        $user = Mockery::mock(Authenticatable::class)
            ->shouldReceive('can')
            ->with('approve', $post)
            ->andReturnFalse()
            ->getMock();
        Auth::shouldReceive('id')->once()->andReturn(1);
        Auth::shouldReceive('check')->once()->andReturnTrue();
        Auth::shouldReceive('user')->once()->andreturn($user);

        $post->title = 'Bad Post';
        $post->save();
        $this->assertEquals('Cool Post', $post->title);
    }
}