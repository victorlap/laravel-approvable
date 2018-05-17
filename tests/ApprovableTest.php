<?php

namespace Victorlap\Approvable\Tests;

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

        $this->assertEquals('Bad Post', $post->fresh()->title);
    }

    public function test_unnapprovable_cannot_be_edited()
    {
        $post = $this->createPost(PostCannotBeApproved::class);

        $post->title = 'Bad Post';
        $post->save();

        $this->assertEquals('Cool Post', $post->fresh()->title);
    }

    public function test_unapprovable_cannot_add_attribute()
    {
        $post = $this->createPost(PostCannotBeApproved::class);

        $post->password = 'secret';
        $post->save();

        $this->assertEquals('', $post->fresh()->password);
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
}
