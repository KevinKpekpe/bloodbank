<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class FixUserEligibility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:fix-eligibility {email?} {--all : Fix all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix user eligibility for blood donation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            $this->fixAllUsers();
        } elseif ($email = $this->argument('email')) {
            $this->fixSpecificUser($email);
        } else {
            $this->interactiveMode();
        }
    }

    private function fixAllUsers()
    {
        $this->info('Fixing eligibility for all users...');

        $users = User::all();
        $fixed = 0;

        foreach ($users as $user) {
            if ($user->role && $user->role->name === 'donor' && !$user->is_eligible_donor) {
                $user->update(['is_eligible_donor' => true]);
                $this->line("✓ Fixed: {$user->name} ({$user->email})");
                $fixed++;
            }
        }

        $this->info("Fixed {$fixed} users.");
    }

    private function fixSpecificUser($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return;
        }

        $this->info("User: {$user->name} ({$user->email})");
        $this->info("Current eligibility: " . ($user->is_eligible_donor ? 'Eligible' : 'Not eligible'));
        $this->info("Role: " . ($user->role ? $user->role->name : 'No role'));

        if ($this->confirm('Do you want to make this user eligible for donation?')) {
            $user->update(['is_eligible_donor' => true]);
            $this->info("✓ User is now eligible for donation.");
        }
    }

    private function interactiveMode()
    {
        $this->info('Interactive mode - List of users:');

        $users = User::with('role')->get();
        $choices = [];

        foreach ($users as $user) {
            $role = $user->role ? $user->role->name : 'No role';
            $eligible = $user->is_eligible_donor ? '✓' : '✗';
            $choices[$user->id] = "{$eligible} {$user->name} ({$user->email}) - {$role}";
        }

        $selectedId = $this->choice('Select a user to fix:', $choices);
        $user = User::find($selectedId);

        if ($user) {
            $this->fixSpecificUser($user->email);
        }
    }
}
