<?php

namespace Victorlap\Approvable\Tests;

use Victorlap\Approvable\Approval;
use Victorlap\Approvable\Tests\Models\PostCanBeApproved;
use Victorlap\Approvable\Tests\Models\PostCannotBeApproved;

class BaseTest extends TestCase
{
    public function test_approvable_can_be_created()
    {
        $post = $this->returnPostInstance(PostCanBeApproved::class);
        
        $this->assertTrue($post->exists);
    }

    public function test_unapprovable_can_be_created()
    {
        $post = $this->returnPostInstance(PostCannotBeApproved::class);
        
        $this->assertTrue($post->exists);
    }

    public function test_approvable_can_be_edited()
    {
        $post = $this->returnPostInstance(PostCanBeApproved::class);

        $post->title = 'Bad Post';
        $post->save();

        $this->assertEquals('Bad Post', $post->fresh()->title);
    }

    public function test_unnapprovable_cannot_be_edited()
    {
        $post = $this->returnPostInstance(PostCannotBeApproved::class);

        $post->title = 'Bad Post';
        $post->save();

        $this->assertEquals('Cool Post', $post->fresh()->title);
    }

    public function test_unapprovable_cannot_add_attribute()
    {
        $post = $this->returnPostInstance(PostCannotBeApproved::class);

        $post->password = 'secret';
        $post->save();

        $this->assertEquals('', $post->fresh()->password);
    }

    public function test_is_pending_approval()
    {
        $post = $this->returnPostInstance(PostCannotBeApproved::class);

        $this->assertFalse($post->isPendingApproval());

        $post->title = 'Bad Post';
        $post->save();

        $this->assertTrue($post->isPendingApproval());
    }

    public function test_has_pending_attributes()
    {
        $post = $this->returnPostInstance(PostCannotBeApproved::class);

        $this->assertFalse($post->isPendingApproval('title'));

        $post->title = 'Bad Post';
        $post->save();

        $this->assertTrue($post->isPendingApproval('title'));
    }

    public function test_list_pending_attributes()
    {
        $post = $this->returnPostInstance(PostCannotBeApproved::class);

        $this->assertEquals(collect(), $post->getPendingApprovalAttributes());

        $post->title = 'Bad Post';
        $post->save();
        $this->assertEquals(collect('title'), $post->getPendingApprovalAttributes());
    }

    public function test_class_approvals()
    {
        $post = $this->returnPostInstance(PostCannotBeApproved::class);

        $this->assertEquals([], Approval::ofClass(PostCannotBeApproved::class)->get()->toArray());

        $post->title = 'Bad Post';
        $post->save();

        $this->assertEquals(Approval::all(), Approval::ofClass(PostCannotBeApproved::class)->get());
        $this->assertEquals([], Approval::ofClass(PostCanBeApproved::class)->get()->toArray());
    }

    public function test_open_scope()
    {
        $this->createApproval(
            ['approved' => null]
        );

        $this->assertCount(1, Approval::open()->get());
        $this->assertCount(0, Approval::accepted()->get());
        $this->assertCount(0, Approval::rejected()->get());
    }


    public function test_accepted_scope()
    {
        $this->createApproval(
            ['approved' => true]
        );

        $this->assertCount(0, Approval::open()->get());
        $this->assertCount(1, Approval::accepted()->get());
        $this->assertCount(0, Approval::rejected()->get());
    }

    public function test_rejected_scope()
    {
        $this->createApproval(
            ['approved' => false]
        );

        $this->assertCount(0, Approval::open()->get());
        $this->assertCount(0, Approval::accepted()->get());
        $this->assertCount(1, Approval::rejected()->get());
    }
}
