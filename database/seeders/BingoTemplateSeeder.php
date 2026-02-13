<?php

namespace Database\Seeders;

use App\Models\BingoCard;
use Illuminate\Database\Seeder;

class BingoTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Templates with topic-related cell content (like bingocardcreator.com).
     */
    public function run(): void
    {
        $gridSize = 5;
        $maxCells = $gridSize * $gridSize;

        $templates = [
            [
                'title' => '1-75 bingo',
                'description' => 'Classic number bingo with random numbers from 1 to 75.',
                'category' => 'Number',
                'words' => null, // filled with random 1-75 below
            ],
            [
                'title' => 'Baby Shower Bingo',
                'description' => 'Baby-themed words for shower games and gifts.',
                'category' => 'Party',
                'words' => [
                    'Diapers', 'Baby bottle', 'Pacifier', 'Bib', 'Stork',
                    'Cradle', 'Rattle', 'Onesie', 'Booties', 'Lullaby',
                    'Nursery', 'Baby powder', 'Teddy bear', 'Baby blanket', 'Crib',
                    'Mobile', 'Diaper bag', 'Baby food', 'Baby socks', 'Welcome baby',
                    'It\'s a boy!', 'It\'s a girl!', 'Baby shower', 'Binky', 'Wipes',
                ],
            ],
            [
                'title' => 'Fruit Bingo',
                'description' => 'Learn and match fruits—perfect for kids and parties.',
                'category' => 'Fun',
                'words' => [
                    'Apple', 'Banana', 'Orange', 'Grapes', 'Strawberry',
                    'Watermelon', 'Pineapple', 'Mango', 'Peach', 'Pear',
                    'Cherry', 'Lemon', 'Kiwi', 'Blueberry', 'Raspberry',
                    'Coconut', 'Avocado', 'Plum', 'Melon', 'Papaya',
                    'Lime', 'Pomegranate', 'Cranberry', 'Blackberry', 'Fig',
                ],
            ],
            [
                'title' => 'Reading Bingo',
                'description' => 'Book and reading-themed squares for classrooms and libraries.',
                'category' => 'Educational',
                'words' => [
                    'Book', 'Chapter', 'Character', 'Plot', 'Setting',
                    'Author', 'Library', 'Bookmark', 'Novel', 'Page',
                    'Story', 'Fiction', 'Dictionary', 'Poem', 'Genre',
                    'Shelf', 'Cover', 'Illustration', 'Glossary', 'Index',
                    'Title', 'Prologue', 'Epilogue', 'Chapter title', 'Quote',
                ],
            ],
            [
                'title' => 'Summer Scavenger Hunt',
                'description' => 'Spot these items on a summer scavenger hunt or road trip.',
                'category' => 'Fun',
                'words' => [
                    'Sunscreen', 'Flip-flops', 'Ice cream', 'Beach ball', 'Sunglasses',
                    'Towel', 'Sandcastle', 'Seashell', 'Sun hat', 'Lemonade',
                    'Watermelon', 'Pool', 'Picnic', 'Barbecue', 'Camping',
                    'Hiking', 'Bicycle', 'Frisbee', 'Kite', 'Popsicle',
                    'Sandals', 'Swimsuit', 'Cooler', 'Sunflower', 'Firefly',
                ],
            ],
            [
                'title' => 'Movie Night Bingo',
                'description' => 'Tropes and moments to spot during movie night.',
                'category' => 'Party',
                'words' => [
                    'Popcorn', 'Remote', 'Blanket', 'Sofa', 'Credits',
                    'Trailer', 'Sequel', 'Pause', 'Snacks', 'Dim lights',
                    'Plot twist', 'Cliffhanger', 'Comedy', 'Drama', 'Action',
                    'Romance', 'Documentary', 'Animation', 'Classic', 'New release',
                    'Villain', 'Hero', 'Love scene', 'Car chase', 'Happy ending',
                ],
            ],
            [
                'title' => 'Party Icebreaker',
                'description' => 'Get everyone talking with fun icebreaker prompts.',
                'category' => 'Icebreaker',
                'words' => [
                    'Find someone who traveled', 'Share a fun fact', 'Birthday month', 'Same hobby', 'Pet owner',
                    'Favorite food', 'Dream job', 'Bucket list item', 'Hidden talent', 'Superpower choice',
                    'Best vacation', 'First concert', 'Coffee or tea', 'Morning person', 'Night owl',
                    'Would rather game', 'Favorite season', 'Pizza topping', 'Movie genre', 'Book lover',
                    'Speaks 2+ languages', 'Has a garden', 'Loves hiking', 'Plays an instrument', 'Danced today',
                ],
            ],
            [
                'title' => 'Classroom Rewards',
                'description' => 'Positive behaviors and achievements for students.',
                'category' => 'Educational',
                'words' => [
                    'Helped a classmate', 'Finished early', 'Quiet hand raise', 'Clean desk', 'On task',
                    'Shared supplies', 'Great question', 'Neat work', 'Participated', 'Listened well',
                    'Kind words', 'Turned in homework', 'Stayed in seat', 'Followed directions', 'Team player',
                    'Creative idea', 'Improved score', 'Read quietly', 'Organized', 'Positive attitude',
                    'Helped teacher', 'No reminders', 'Best effort', 'Growth mindset', 'Star reader',
                ],
            ],
            [
                'title' => 'Team Standup',
                'description' => 'Keep remote standups fun and engaging.',
                'category' => 'Work',
                'words' => [
                    'Blocked', 'Unblocked', 'Deployed', 'In progress', 'Done',
                    'Review', 'Merge', 'Sprint goal', 'Bug fix', 'Feature',
                    'Meeting', 'Slack', 'Async', 'Follow-up', 'Blocker',
                    'PR open', 'Testing', 'Documentation', 'Retro', 'Demo',
                    'On call', 'Pairing', 'Code review', 'Ship it', 'Celebrate',
                ],
            ],
            [
                'title' => 'Holiday Party',
                'description' => 'Festive traditions and funny party moments.',
                'category' => 'Seasonal',
                'words' => [
                    'Ornaments', 'Candy cane', 'Snowman', 'Gingerbread', 'Stocking',
                    'Lights', 'Tree', 'Carol', 'Presents', 'Hot cocoa',
                    'Cookie swap', 'Ugly sweater', 'Mistletoe', 'Reindeer', 'Elf',
                    'Snow', 'Fireplace', 'Family dinner', 'Midnight countdown', 'Sparklers',
                    'Gift wrap', 'Wish list', 'Thank you note', 'New Year', 'Cheers',
                ],
            ],
            [
                'title' => 'Nature Scavenger Hunt',
                'description' => 'Spot these in the park, garden, or neighborhood.',
                'category' => 'Fun',
                'words' => [
                    'Bird', 'Butterfly', 'Leaf', 'Flower', 'Pine cone',
                    'Rock', 'Spider web', 'Ant', 'Ladybug', 'Bee',
                    'Tree bark', 'Mushroom', 'Feather', 'Acorn', 'Berry',
                    'Cloud', 'Puddle', 'Moss', 'Stick', 'Seed',
                    'Caterpillar', 'Dandelion', 'Clover', 'Grasshopper', 'Rainbow',
                ],
            ],
            [
                'title' => 'Math Bingo',
                'description' => 'Numbers and math terms for classroom bingo.',
                'category' => 'Educational',
                'words' => [
                    'Add', 'Subtract', 'Multiply', 'Divide', 'Equals',
                    'Sum', 'Difference', 'Product', 'Quotient', 'Fraction',
                    'Decimal', 'Percent', 'Square', 'Circle', 'Triangle',
                    'Graph', 'Equation', 'Variable', 'Even', 'Odd',
                    'Prime', 'Factor', 'Multiple', 'Perimeter', 'Area',
                ],
            ],
        ];

        foreach ($templates as $template) {
            $words = $template['words'] ?? null;

            if ($words !== null) {
                $words = array_slice($words, 0, $maxCells);
                while (count($words) < $maxCells) {
                    $words[] = 'Free';
                }
            } else {
                // 1-75 bingo: fill with 25 random numbers from 1 to 75
                $pool = range(1, 75);
                shuffle($pool);
                $words = array_map('strval', array_slice($pool, 0, $maxCells));
            }

            $cells = [];
            foreach (range(0, $maxCells - 1) as $index) {
                $row = intdiv($index, $gridSize);
                $col = $index % $gridSize;
                $cells[] = [
                    'row' => $row,
                    'col' => $col,
                    'text' => $words[$index],
                    'image_prompt' => null,
                ];
            }

            BingoCard::updateOrCreate(
                [
                    'title' => $template['title'],
                    'is_template' => true,
                ],
                [
                    'description' => $template['description'],
                    'grid_size' => $gridSize,
                    'cells' => $cells,
                    'thumbnail_url' => null,
                    'category' => $template['category'],
                    'source' => 'template',
                    'user_id' => null,
                ]
            );
        }
    }
}
