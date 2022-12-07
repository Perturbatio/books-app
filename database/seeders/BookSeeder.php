<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all()->keyBy('name');

        $robinNixon = Author::factory()->createOne(['full_name' => 'Robin Nixon']);
        Book::factory()
            ->hasAttached([$robinNixon])
            ->hasAttached([$categories['PHP'], $categories['Javascript']])
            ->createOne([
                'isbn' => '978-1491918661',
                'title' => 'Learning PHP, MySQL & JavaScript: With jQuery, CSS & HTML',
                'price' => '9.99',
            ]);

        Book::factory()
            ->hasAttached([$robinNixon])
            ->hasAttached([$categories['Linux']])
            ->createOne([
                'isbn' => '978-0596804848',
                'title' => 'Ubuntu: Up and Running: A Power User\'s Desktop Guide',
                'price' => '12.99',
            ]);

        $christopherNegus = Author::factory()->createOne(['full_name' => 'Christopher Negus']);
        Book::factory()
            ->hasAttached([$christopherNegus])
            ->hasAttached([$categories['Linux']])
            ->createOne(
                [
                    'isbn' => '978-1118999875',
                    'title' => 'Linux Bible',
                    'price' => '19.99',
                ]);

        $douglasCrockford = Author::factory()->createOne(['full_name' => 'Douglas Crockford']);
        Book::factory()
            ->hasAttached([$douglasCrockford])
            ->hasAttached([$categories['Javascript']])
            ->createOne([
                'isbn' => '978-0596517748',
                'title' => 'JavaScript: The Good Parts',
                'price' => '8.99',
            ]);
    }
}
