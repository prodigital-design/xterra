<?php


namespace Tests\Unit\Blog;


use App\Blog\Tag;
use App\Blog\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_tag_has_a_slug()
    {
        $tag = Tag::create(['tag_name' => 'test tag']);
        $this->assertEquals('test-tag', $tag->slug);
    }

    /**
     *@test
     */
    public function tags_can_be_scoped_to_in_use()
    {
        $usedA = Tag::create(['tag_name' => 'tag one']);
        $usedB = Tag::create(['tag_name' => 'tag two']);
        $unused = Tag::create(['tag_name' => 'tag three']);
        $un_published = Tag::create(['tag_name' => 'tag four']);

        $translationA = factory(Translation::class)->state('live')->create();
        $translationB = factory(Translation::class)->state('live')->create();
        $translationC = factory(Translation::class)->state('live')->create();
        $translationD = factory(Translation::class)->state('draft')->create();

        $translationA->tags()->attach($usedA->id);
        $translationB->tags()->attach($usedA->id);
        $translationC->tags()->attach($usedB->id);
        $translationD->tags()->attach($un_published->id);

        $used_tags = Tag::inUse()->get();

        $this->assertCount(2, $used_tags);
        $this->assertTrue($used_tags->contains($usedA));
        $this->assertTrue($used_tags->contains($usedB));
        $this->assertFalse($used_tags->contains($unused));
        $this->assertFalse($used_tags->contains($un_published));


        $used_tags->each(function($tag) {
            $this->assertTrue($tag->translations_count > 0);
        });
    }

    /**
     *@test
     */
    public function tags_can_be_scoped_to_english()
    {
        $for_enA = Tag::create(['tag_name' => 'tag one']);
        $for_enB = Tag::create(['tag_name' => 'tag two']);
        $for_zh = Tag::create(['tag_name' => 'tag three']);

        $en = factory(Translation::class)->state('en')->create();
        $zh = factory(Translation::class)->state('zh')->create();

        $en->tags()->attach($for_enA->id);
        $en->tags()->attach($for_enB->id);
        $zh->tags()->attach($for_zh->id);

        $result_en = Tag::forLang('en')->get();
        $result_zh = Tag::forLang('zh')->get();

        $this->assertCount(2, $result_en);
        $this->assertContains($for_enA->id, $result_en->pluck('id')->all());
        $this->assertContains($for_enB->id, $result_en->pluck('id')->all());
        $this->assertNotContains($for_zh->id, $result_en->pluck('id')->all());

        $this->assertCount(1, $result_zh);
        $this->assertNotContains($for_enA->id, $result_zh->pluck('id')->all());
        $this->assertNotContains($for_enB->id, $result_zh->pluck('id')->all());
        $this->assertContains($for_zh->id, $result_zh->pluck('id')->all());
    }

}
