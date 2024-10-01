<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Randomly select either 0 or 1 for the initial sender ID
        $senderId = $this->faker->randomElement([0, 1]);
        if ($senderId === 0) {
            // Select a random user ID from the users table as the Sender ID, excluding the user with ID 1
            $senderId = $this->faker->randomElement(User::where('id', '!=', 1)
                ->pluck('id')
                ->toArray());
            // Set the Receiver ID to 1
            $receiverId = 1;
        } else {
            $receiverId = $this->faker->randomElement(User::where('id', '!=', 1)
                ->pluck('id')
                ->toArray());
        }
        $groupId = null;
        if ($this->faker->boolean()) {
            $groupId = $this->faker->randomElement(Group::pluck('id')->toArray());
            $group = Group::find($groupId);
            $senderId = $this->faker->randomElement($group->users->pluck('id')->toArray());
            $receiverId = null;
        }
        return [
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'group_id' => $groupId,
            'message' => $this->faker->realText(200),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
