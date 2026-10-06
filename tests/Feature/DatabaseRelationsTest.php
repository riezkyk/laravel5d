<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Category;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\JournalEntry;
use App\Models\MoodEntry;
use App\Models\Profile;
use App\Models\Reminder;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_one_profile_and_profile_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->profile->is($profile));
        $this->assertTrue($profile->user->is($user));
    }

    public function test_user_has_many_habits_and_habit_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $this->assertTrue($user->habits->contains($habit));
        $this->assertTrue($habit->user->is($user));
    }

    public function test_category_has_many_habits_and_habit_belongs_to_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $this->assertTrue($category->habits->contains($habit));
        $this->assertTrue($habit->category->is($category));
    }

    public function test_habit_has_many_logs_and_log_belongs_to_habit(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
        $log = HabitLog::factory()->create(['habit_id' => $habit->id]);

        $this->assertTrue($habit->logs->contains($log));
        $this->assertTrue($log->habit->is($habit));
    }

    public function test_user_has_many_through_habit_logs(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
        $log = HabitLog::factory()->create(['habit_id' => $habit->id]);

        $this->assertTrue($user->habitLogs->contains($log));
    }

    public function test_category_has_many_through_habit_logs(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
        $log = HabitLog::factory()->create(['habit_id' => $habit->id]);

        $this->assertTrue($category->habitLogs->contains($log));
    }

    public function test_habit_has_many_reminders_and_reminder_belongs_to_habit(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
        $reminder = Reminder::factory()->create(['habit_id' => $habit->id]);

        $this->assertTrue($habit->reminders->contains($reminder));
        $this->assertTrue($reminder->habit->is($habit));
    }

    public function test_habit_belongs_to_many_tags_and_tag_belongs_to_many_habits(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
        $tag = Tag::factory()->create();

        $habit->tags()->attach($tag->id);

        $this->assertTrue($habit->tags->contains($tag));
        $this->assertTrue($tag->habits->contains($habit));
    }

    public function test_user_has_many_mood_entries_and_mood_entry_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $mood = MoodEntry::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->moodEntries->contains($mood));
        $this->assertTrue($mood->user->is($user));
    }

    public function test_user_has_many_journal_entries_and_journal_entry_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $journal = JournalEntry::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->journalEntries->contains($journal));
        $this->assertTrue($journal->user->is($user));
    }

    public function test_mood_entry_has_one_journal_entry_and_journal_entry_belongs_to_mood_entry(): void
    {
        $user = User::factory()->create();
        $mood = MoodEntry::factory()->create(['user_id' => $user->id]);
        $journal = JournalEntry::factory()->create([
            'user_id' => $user->id,
            'mood_entry_id' => $mood->id,
        ]);

        $this->assertTrue($mood->journalEntry->is($journal));
        $this->assertTrue($journal->moodEntry->is($mood));
    }

    public function test_user_belongs_to_many_achievements_with_pivot(): void
    {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create();

        $user->achievements()->attach($achievement->id, ['earned_at' => now()]);

        $this->assertTrue($user->achievements->contains($achievement));
        $this->assertNotNull($user->achievements->first()->pivot->earned_at);
        $this->assertTrue($achievement->users->contains($user));
    }
}
