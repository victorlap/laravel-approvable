<?php

namespace Victorlap\Approvable\Tests\Models;

use Victorlap\Approvable\Approval;
use Victorlap\Approvable\Tests\TestCase;

class ApprovalTest extends TestCase
{
    public function test_class_approvals()
    {
        $post = $this->createPost(PostCannotBeApproved::class);

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

    public function test_accept_method()
    {
        $post = $this->createPost(PostCannotBeApproved::class);
        $post->title = 'Bad Post';
        $post->save();

        $this->assertEquals('Cool Post', $post->title);
        $this->assertNull(Approval::first()->approved);

        $post->approvals->each->accept();

        //TODO: Fix test below
        //$this->assertEquals('Bad Post', $post->fresh()->title);
        $this->assertEquals('Bad Post', Post::first()->title);
        $this->assertEquals(1, Post::count());
        $this->assertTrue(Approval::first()->approved);
    }

    public function test_reject_method()
    {
        $post = $this->createPost(PostCannotBeApproved::class);
        $post->title = 'Bad Post';
        $post->save();

        $this->assertEquals('Cool Post', $post->title);
        $this->assertNull(Approval::first()->approved);

        $post->approvals->each->reject();

        $this->assertEquals('Cool Post', $post->fresh()->title);
        $this->assertFalse(Approval::first()->approved);
    }
}
