<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\GroupNameSuffix;
use App\Models\Nameable;
use App\Models\User;
use App\Models\UserNameSuffix;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NameableSuffixTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_cannot_have_morphTo_and_hasNested_combined() {
        // given a group with a name suffix
        $givenGroup = Group::factory()->has(GroupNameSuffix::factory(), 'suffixes')->create();

        // and a user with a name suffix
        $givenUser = User::factory()->has(UserNameSuffix::factory(), 'suffixes')->create();

        // and one more group and user without suffixes
        $givenGroupWithoutSuffix = Group::factory()->create();
        $givenUserWithoutSuffix = User::factory()->create();

        // when fetching all nameables that has suffixes
        $results = Nameable::whereHas('entity.suffixes')->get();

        // then it should only return the nameables with suffixes
        $this->assertCount(2, $results);
        $resultsIds = $results->pluck('entity_id')->toArray();
        $expectedIds = [
            $givenGroup->id,
            $givenUser->id
        ];

        $this->assertEqualsCanonicalizing($expectedIds, $resultsIds);
    }
}