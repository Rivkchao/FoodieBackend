<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Categories
        $categoriesData = [
            ['slug' => 'all', 'name' => 'Semua Menu', 'icon' => 'UtensilsCrossed'],
            ['slug' => 'Burgers', 'name' => 'Burgers', 'icon' => 'Flame'],
            ['slug' => 'Pizza', 'name' => 'Pizza', 'icon' => 'Pizza'],
            ['slug' => 'Chicken', 'name' => 'Chicken', 'icon' => 'Drumstick'],
            ['slug' => 'Pasta', 'name' => 'Pasta', 'icon' => 'Wheat'],
            ['slug' => 'Asian', 'name' => 'Asian', 'icon' => 'Soup'],
            ['slug' => 'Drinks', 'name' => 'Drinks', 'icon' => 'Coffee'],
            ['slug' => 'Desserts', 'name' => 'Desserts', 'icon' => 'IceCream'],
        ];

        $categoryMap = [];
        foreach ($categoriesData as $c) {
            $cat = Category::updateOrCreate(['slug' => $c['slug']], $c);
            $categoryMap[$c['slug']] = $cat->id;
        }

        // 2. Seed Foods
        $foodsData = [
            [
                'id' => 1,
                'name' => 'Truffle Mushroom Burger',
                'category' => 'Burgers',
                'description' => 'Juicy Australian Angus beef patty dipadukan dengan tumisan jamur champignon liar, saus black truffle mayo yang creamy, dan keju Swiss meleleh di atas brioche bun panggang bermentega.',
                'price' => 68000,
                'rating' => 4.9,
                'reviews' => 248,
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Angus Beef Patty', 'Wild Truffle Sauce', 'Swiss Melted Cheese', 'Sautéed Champignon', 'Brioche Bun'],
                'prepTime' => '15-20 min',
                'calories' => '650 kkal',
                'popular' => true
            ],
            [
                'id' => 2,
                'name' => 'Crispy BBQ Bacon Burger',
                'category' => 'Burgers',
                'description' => 'Daging sapi panggang tebal dengan lapisan bacon renyah, saus smoky barbecue racikan rahasia, keju cheddar matang, dan onion rings krispi.',
                'price' => 62000,
                'rating' => 4.8,
                'reviews' => 189,
                'image' => 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Prime Beef', 'Smoked Beef Bacon', 'Cheddar Cheese', 'Smoky BBQ Sauce', 'Crispy Onion Ring'],
                'prepTime' => '15-20 min',
                'calories' => '710 kkal',
                'popular' => true
            ],
            [
                'id' => 3,
                'name' => 'Double Wagyu Smash Burger',
                'category' => 'Burgers',
                'description' => 'Dua lapis patty wagyu yang di-smash hingga pinggirannya karamel renyah, dilapisi double American cheese, acar segar, dan saus spesial Foodie.',
                'price' => 78000,
                'rating' => 4.9,
                'reviews' => 312,
                'image' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Double Wagyu Patty', 'American Cheese', 'Secret Foodie Sauce', 'House Pickles', 'Butter Brioche'],
                'prepTime' => '10-15 min',
                'calories' => '820 kkal',
                'popular' => false
            ],
            [
                'id' => 4,
                'name' => 'Margherita Artisan Pizza',
                'category' => 'Pizza',
                'description' => 'Pizza Neapolitan otentik dengan fermentasi adonan 48 jam, saus tomat San Marzano manis gurih, keju fresh mozzarella fior di latte, dan daun basil segar.',
                'price' => 85000,
                'rating' => 4.9,
                'reviews' => 420,
                'image' => 'https://images.unsplash.com/photo-1604382355076-af4b0eb60143?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['San Marzano Tomato', 'Fresh Mozzarella', 'Extra Virgin Olive Oil', 'Fresh Basil Leaf', '48h Fermented Crust'],
                'prepTime' => '20-25 min',
                'calories' => '780 kkal',
                'popular' => true
            ],
            [
                'id' => 5,
                'name' => 'Quattro Formaggi Pizza',
                'category' => 'Pizza',
                'description' => 'Kombinasi surgawi empat keju pilihan dunia: Mozzarella mulur, Gorgonzola aromatik, Parmigiano-Reggiano gurih, dan Ricotta lembut dengan tetesan madu liar.',
                'price' => 98000,
                'rating' => 4.8,
                'reviews' => 174,
                'image' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Mozzarella', 'Gorgonzola Cheese', 'Parmesan Aged 24m', 'Creamy Ricotta', 'Wild Honey Drizzle'],
                'prepTime' => '20-25 min',
                'calories' => '890 kkal',
                'popular' => false
            ],
            [
                'id' => 6,
                'name' => 'Pepperoni Supreme Pizza',
                'category' => 'Pizza',
                'description' => 'Dibalut keju mozzarella melimpah dengan irisan premium beef pepperoni yang melengkung garing saat dipanggang di tungku kayu bakar tradisi Italia.',
                'price' => 89000,
                'rating' => 4.9,
                'reviews' => 356,
                'image' => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Crispy Beef Pepperoni', 'Italian Herb Sauce', 'Melted Mozzarella', 'Oregano', 'Woodfired Crust'],
                'prepTime' => '15-20 min',
                'calories' => '840 kkal',
                'popular' => true
            ],
            [
                'id' => 7,
                'name' => 'Crispy Garlic Herb Fried Chicken',
                'category' => 'Chicken',
                'description' => 'Ayam goreng renyah bumbu marinasi 12 rempah rahasia selama 24 jam, digoreng keemasan lalu diglasir minyak bawang putih panggang dan parsley segar.',
                'price' => 48000,
                'rating' => 4.8,
                'reviews' => 290,
                'image' => 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Fresh Cut Chicken', 'Roasted Garlic Butter', '12 Secret Spices', 'Chopped Parsley', 'Honey Dip Sauce'],
                'prepTime' => '15-20 min',
                'calories' => '590 kkal',
                'popular' => true
            ],
            [
                'id' => 8,
                'name' => 'Nashville Hot Chicken Wings',
                'category' => 'Chicken',
                'description' => 'Sayap ayam krispi ekstra pedas khas Nashville dengan bumbu cabai cayenne membara, disajikan dengan irisan acar timun dingin dan saus ranch.',
                'price' => 52000,
                'rating' => 4.7,
                'reviews' => 145,
                'image' => 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Chicken Wings', 'Nashville Cayenne Glaze', 'Dill Pickles', 'Buttermilk Ranch Sauce'],
                'prepTime' => '15-18 min',
                'calories' => '610 kkal',
                'popular' => false
            ],
            [
                'id' => 9,
                'name' => 'Creamy Truffle Carbonara',
                'category' => 'Pasta',
                'description' => 'Fettuccine al dente dibalut saus kuning telur kaya rasa khas Roma, keju pecorino romano, potongan smoked beef gurih, dan taburan minyak black truffle.',
                'price' => 65000,
                'rating' => 4.9,
                'reviews' => 280,
                'image' => 'https://images.unsplash.com/photo-1612874742237-6526221588e3?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Fettuccine Pasta', 'Pecorino Romano', 'Egg Yolk Crema', 'Smoked Beef', 'Truffle Oil Drizzle'],
                'prepTime' => '15-20 min',
                'calories' => '680 kkal',
                'popular' => true
            ],
            [
                'id' => 10,
                'name' => 'Spicy Aglio Olio Smoked Beef',
                'category' => 'Pasta',
                'description' => 'Spaghetti tumis minyak zaitun murni kualitas tinggi dengan aroma bawang putih semerbak, cabai rawit merah, smoked beef iris, dan daun parsley.',
                'price' => 55000,
                'rating' => 4.7,
                'reviews' => 198,
                'image' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Spaghetti', 'EV Olive Oil', 'Golden Garlic', 'Birdseye Chili', 'Smoked Beef Ribs'],
                'prepTime' => '12-15 min',
                'calories' => '540 kkal',
                'popular' => false
            ],
            [
                'id' => 11,
                'name' => 'Spicy Chicken Chashu Ramen',
                'category' => 'Asian',
                'description' => 'Mie ramen kenyal dalam kaldu ayam kolagen kental yang dimasak 16 jam, dilengkapi irisan chicken chashu bakar empuk, ajitsuke tamago lembut, dan chili oil aromatik.',
                'price' => 58000,
                'rating' => 4.9,
                'reviews' => 490,
                'image' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Ramen Noodle', 'Rich Chicken Broth', 'Torched Chashu', 'Ajitsuke Soft Egg', 'Nori & Scallion'],
                'prepTime' => '15-18 min',
                'calories' => '640 kkal',
                'popular' => true
            ],
            [
                'id' => 12,
                'name' => 'Beef Teriyaki Donburi',
                'category' => 'Asian',
                'description' => 'Irisan daging sapi US Shortplate empuk ditumis dengan saus teriyaki manis gurih autentik Tokyo, disajikan di atas nasi hangat Jepang, wijen sangrai, dan telur onsen.',
                'price' => 62000,
                'rating' => 4.8,
                'reviews' => 215,
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['US Beef Shortplate', 'Japanese Rice', 'Teriyaki Glaze', 'Onsen Soft Egg', 'Toasted Sesame'],
                'prepTime' => '12-15 min',
                'calories' => '610 kkal',
                'popular' => true
            ],
            [
                'id' => 13,
                'name' => 'Salmon Mentai Rice Bowl',
                'category' => 'Asian',
                'description' => 'Nasi pulen Jepang bertabur nori furikake dengan potongan salmon panggang segar dan saus tobiko mentai creamy yang di-blowtorch harum menggoda.',
                'price' => 69000,
                'rating' => 4.9,
                'reviews' => 310,
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Fresh Salmon', 'Tobiko Caviar', 'Secret Mentai Crema', 'Japanese Furikake Rice', 'Torched Mayo'],
                'prepTime' => '15-18 min',
                'calories' => '570 kkal',
                'popular' => true
            ],
            [
                'id' => 14,
                'name' => 'Matcha Cream Oat Latte',
                'category' => 'Drinks',
                'description' => 'Matcha Uji ceremonial grade asal Kyoto dipadukan dengan creamy oat milk bebas laktosa dan sentuhan madu kelapa organik yang menyegarkan.',
                'price' => 34000,
                'rating' => 4.8,
                'reviews' => 165,
                'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Ceremonial Uji Matcha', 'Barista Oat Milk', 'Organic Coconut Nectar', 'Ice'],
                'prepTime' => '5-8 min',
                'calories' => '180 kkal',
                'popular' => true
            ],
            [
                'id' => 15,
                'name' => 'Sparkling Peach Yuzu Tea',
                'category' => 'Drinks',
                'description' => 'Seduhan teh melati dingin segar dipadukan dengan sari buah persik matang, citrus yuzu Jepang yang asam segar, dan soda berkarbonasi lembut.',
                'price' => 28000,
                'rating' => 4.7,
                'reviews' => 142,
                'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Jasmine Cold Brew', 'Japanese Yuzu Puree', 'Sweet Peach Slices', 'Sparkling Soda', 'Fresh Mint'],
                'prepTime' => '5 min',
                'calories' => '120 kkal',
                'popular' => false
            ],
            [
                'id' => 16,
                'name' => 'Caramel Macchiato Cold Brew',
                'category' => 'Drinks',
                'description' => 'Cold brew arabika single origin yang diseduh 18 jam dengan susu vanila kental, es batu, dan drizzle saus karamel mentega gurih manis.',
                'price' => 35000,
                'rating' => 4.9,
                'reviews' => 210,
                'image' => 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['18h Arabica Cold Brew', 'Fresh Whole Milk', 'Salted Butter Caramel', 'Madagascar Vanilla'],
                'prepTime' => '5 min',
                'calories' => '210 kkal',
                'popular' => true
            ],
            [
                'id' => 17,
                'name' => 'Molten Chocolate Lava Cake',
                'category' => 'Desserts',
                'description' => 'Cake cokelat Belgia lembut hangat dengan lelehan cokelat pekat lumer di bagian tengah, disajikan dengan es krim vanilla bourbon dan buah berry segar.',
                'price' => 42000,
                'rating' => 4.9,
                'reviews' => 320,
                'image' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['70% Belgian Dark Chocolate', 'Bourbon Vanilla Bean Gelato', 'Organic Butter', 'Fresh Strawberries'],
                'prepTime' => '12-15 min',
                'calories' => '490 kkal',
                'popular' => true
            ],
            [
                'id' => 18,
                'name' => 'Basque Burnt Cheesecake',
                'category' => 'Desserts',
                'description' => 'Cheesecake panggang gaya San Sebastian dengan permukaan karamel cokelat terbakar yang eksotis, bagian dalam creamy meleleh, dan rasa keju yang intens.',
                'price' => 45000,
                'rating' => 4.8,
                'reviews' => 188,
                'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=800&auto=format&fit=crop&q=80',
                'ingredients' => ['Cream Cheese Philladelphia', 'Heavy Cream', 'Caramelized Top Layer', 'Sea Salt Flakes'],
                'prepTime' => '5 min',
                'calories' => '420 kkal',
                'popular' => false
            ],
        ];

        foreach ($foodsData as $item) {
            $catId = $categoryMap[$item['category']] ?? null;
            Food::updateOrCreate(['id' => $item['id']], [
                'name' => $item['name'],
                'category' => $item['category'],
                'category_id' => $catId,
                'description' => $item['description'],
                'price' => $item['price'],
                'rating' => $item['rating'],
                'reviews' => $item['reviews'],
                'image' => $item['image'],
                'ingredients' => $item['ingredients'],
                'prepTime' => $item['prepTime'],
                'calories' => $item['calories'],
                'popular' => $item['popular'],
            ]);
        }

        // 3. Seed Restaurants
        $restaurantsData = [
            [
                'id' => 1,
                'name' => 'Burger Garage Artisanal',
                'cuisine' => 'Burgers & American',
                'rating' => 4.9,
                'distance' => '1.2 km',
                'duration' => '20 min',
                'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=700&auto=format&fit=crop&q=80',
                'badge' => 'Trending #1'
            ],
            [
                'id' => 2,
                'name' => 'Bella Napoli Woodfired Pizza',
                'cuisine' => 'Authentic Italian',
                'rating' => 4.8,
                'distance' => '2.4 km',
                'duration' => '25 min',
                'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=700&auto=format&fit=crop&q=80',
                'badge' => 'Chef Choice'
            ],
            [
                'id' => 3,
                'name' => 'Tokyo Ramen Street',
                'cuisine' => 'Japanese Ramen & Don',
                'rating' => 4.9,
                'distance' => '1.8 km',
                'duration' => '18 min',
                'image' => 'https://images.unsplash.com/photo-1552611052-33e04de081de?w=700&auto=format&fit=crop&q=80',
                'badge' => 'Top Rated'
            ]
        ];

        foreach ($restaurantsData as $rest) {
            Restaurant::updateOrCreate(['id' => $rest['id']], $rest);
        }
    }
}
