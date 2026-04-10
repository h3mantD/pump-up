<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');
        $user = User::first();

        $products = [
            // Cardio Machines
            ['name' => 'ProForm Carbon TL Treadmill', 'description' => 'Foldable treadmill with 20x55 inch running deck, 10% incline, and 10 MPH max speed. Features a 5-inch backlit display with 18 built-in workout programs. QuickSpeed and QuickIncline buttons for easy adjustments mid-workout. Ideal for home cardio sessions.', 'price' => 899.99, 'stock' => 12, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1576678927484-cc907957088c?w=600&h=400&fit=crop', 'category' => 'cardio-machines'],
            ['name' => 'Schwinn 270 Recumbent Bike', 'description' => 'Comfortable recumbent exercise bike with 25 resistance levels and 29 preset workout programs. Features a ventilated seat with lumbar support, dual LCD displays, and Bluetooth connectivity. USB charging port and built-in speakers. Perfect for low-impact cardio.', 'price' => 649.00, 'stock' => 8, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1591291621164-2c6367723315?w=600&h=400&fit=crop', 'category' => 'cardio-machines'],
            ['name' => 'Concept2 RowErg', 'description' => 'The gold standard rowing machine used by Olympic athletes and home gym enthusiasts alike. PM5 performance monitor tracks distance, pace, calories, and watts. Flywheel design minimizes noise while providing smooth resistance. Separates in two pieces for easy storage.', 'price' => 1095.00, 'stock' => 6, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1519505907962-0a6cb0167c73?w=600&h=400&fit=crop', 'category' => 'cardio-machines'],
            ['name' => 'NordicTrack Commercial S22i Studio Cycle', 'description' => 'Interactive studio cycling bike with a 22-inch HD touchscreen. Features -10 to 20% incline/decline, 24 digital resistance levels, and auto-adjust technology for immersive riding. Rotating screen for off-bike workouts.', 'price' => 1499.00, 'stock' => 4, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&h=400&fit=crop', 'category' => 'cardio-machines'],
            ['name' => 'Assault AirBike Classic', 'description' => 'Fan resistance air bike delivering unlimited resistance — the harder you pedal, the more resistance you get. Steel frame construction with 25-inch diameter fan. Features a 6-way adjustable seat, 20-inch steel cranks, and an LCD console tracking calories, heart rate, watts, and distance.', 'price' => 699.00, 'stock' => 0, 'status' => 'out_of_stock', 'image' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=600&h=400&fit=crop', 'category' => 'cardio-machines'],

            // Free Weights
            ['name' => 'Hex Rubber Dumbbell Set (5-50 lb)', 'description' => 'Complete set of rubber hex dumbbells from 5 to 50 pounds in 5-pound increments. Rubber-coated heads protect floors and reduce noise. Chrome contoured handles with medium knurling for a secure grip. 10 pairs total, rack not included.', 'price' => 1299.00, 'stock' => 15, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1638536532686-d610adfc8e5c?w=600&h=400&fit=crop', 'category' => 'free-weights'],
            ['name' => 'Olympic Barbell 45 lb', 'description' => '7-foot Olympic barbell made from cold-rolled steel with a tensile strength of 190,000 PSI. Chrome finish with medium-depth knurling. 28.5mm shaft diameter, 16.25-inch loadable sleeve length. Suitable for bench press, squats, deadlifts, and Olympic lifts.', 'price' => 249.99, 'stock' => 25, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1526401485004-46910ecc8e51?w=600&h=400&fit=crop', 'category' => 'free-weights'],
            ['name' => 'Adjustable Kettlebell (10-40 lb)', 'description' => 'Space-saving adjustable kettlebell that replaces 6 individual kettlebells. Quickly adjust from 10 to 40 pounds in 5-pound increments with a simple turn-dial mechanism. Comfortable handle with smooth grip, compact design perfect for home gyms.', 'price' => 149.99, 'stock' => 30, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1602827114685-efbb2717da9f?w=600&h=400&fit=crop', 'category' => 'free-weights'],
            ['name' => 'Rubber Olympic Weight Plate Set (300 lb)', 'description' => 'Complete 300-pound Olympic weight plate set with rubber coating. Includes: 2x45lb, 2x35lb, 2x25lb, 2x10lb, 2x5lb, and 2x2.5lb plates. Stainless steel inserts fit standard Olympic bars. Low-bounce rubber protects floors.', 'price' => 549.00, 'stock' => 10, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1521804906057-1df8fdb718b7?w=600&h=400&fit=crop', 'category' => 'free-weights'],
            ['name' => 'EZ Curl Bar', 'description' => 'Angled EZ curl bar designed to reduce wrist and forearm strain during bicep and tricep exercises. 47-inch total length, 28mm grip diameter, with medium knurling. Chrome-plated steel construction. Accepts standard Olympic plates.', 'price' => 79.99, 'stock' => 40, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=600&h=400&fit=crop', 'category' => 'free-weights'],

            // Strength Equipment
            ['name' => 'Cable Crossover Machine', 'description' => 'Dual adjustable pulley system with 17 height positions per side. 200 lb weight stack on each side with a 2:1 ratio. Includes straight bar, rope, and D-handle attachments. Wide base for stability, compact footprint for home gyms.', 'price' => 2499.00, 'stock' => 3, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=600&h=400&fit=crop', 'category' => 'strength-equipment'],
            ['name' => 'Leg Press Machine', 'description' => 'Heavy-duty 45-degree leg press with a 1000 lb weight capacity. Extra-wide diamond plate foot platform, adjustable safety catches, and linear bearings for smooth travel. Four Olympic-size weight posts with band pegs.', 'price' => 1899.00, 'stock' => 2, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?w=600&h=400&fit=crop', 'category' => 'strength-equipment'],
            ['name' => 'Lat Pulldown / Low Row Combo', 'description' => 'Dual-function machine for lat pulldowns and seated cable rows. 300 lb weight stack, high and low pulleys, adjustable thigh pads, and wide grip lat bar included. Smooth cam system for consistent resistance throughout range of motion.', 'price' => 1299.00, 'stock' => 5, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=600&h=400&fit=crop', 'category' => 'strength-equipment'],
            ['name' => 'Smith Machine', 'description' => 'Commercial-grade Smith machine with linear bearings and counterbalanced bar system. Includes pull-up bar, weight storage pegs, and adjustable safety stops. 7-degree bar path angle mimics natural movement. 600 lb weight capacity.', 'price' => 2199.00, 'stock' => 0, 'status' => 'out_of_stock', 'image' => 'https://images.unsplash.com/photo-1605296867424-35fc25c9212a?w=600&h=400&fit=crop', 'category' => 'strength-equipment'],

            // Benches & Racks
            ['name' => 'Adjustable Weight Bench', 'description' => 'Heavy-duty FID bench (flat, incline, decline) with 7 back positions and 3 seat positions. 1000 lb weight capacity, 2.5-inch thick padding, and transport wheels. Compact design stores upright. Steel frame with rubber end caps.', 'price' => 349.99, 'stock' => 18, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1620188467120-5042ed1eb5da?w=600&h=400&fit=crop', 'category' => 'benches-racks'],
            ['name' => 'Power Rack with Pull-Up Bar', 'description' => 'Full-size power rack with 1000 lb capacity, Westside hole spacing, and numbered uprights. Includes multi-grip pull-up bar, J-hooks, safety pins, and band pegs. 2x3 inch 11-gauge steel construction. Bolt-down hardware included.', 'price' => 799.00, 'stock' => 7, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1623874514711-0f321325f318?w=600&h=400&fit=crop', 'category' => 'benches-racks'],
            ['name' => 'Squat Stand Pair', 'description' => 'Independent squat stands with 72-inch height adjustment range in 2-inch increments. 600 lb capacity per stand, wide triangle base for stability. Includes J-hooks and spotter arms. Easy to move and reposition.', 'price' => 299.99, 'stock' => 14, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1598268030450-7a476f602bf0?w=600&h=400&fit=crop', 'category' => 'benches-racks'],
            ['name' => 'Dumbbell Rack (3-Tier)', 'description' => 'Heavy-duty 3-tier dumbbell rack holding up to 15 pairs of dumbbells. Angled shelves with saddle design keep dumbbells secure. Rubber feet protect floors. 62 inches wide, made from 2x2 inch steel tubing.', 'price' => 199.99, 'stock' => 20, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1558611848-73f7eb4001a1?w=600&h=400&fit=crop', 'category' => 'benches-racks'],

            // Resistance Training
            ['name' => 'Resistance Band Set (5 Bands)', 'description' => 'Set of 5 loop resistance bands with varying resistance levels: extra light (5 lb), light (10 lb), medium (20 lb), heavy (30 lb), and extra heavy (40 lb). Made from natural latex, includes carrying bag and exercise guide. Great for warm-ups, rehab, and strength training.', 'price' => 29.99, 'stock' => 100, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1598289431512-b97b0917affc?w=600&h=400&fit=crop', 'category' => 'resistance-training'],
            ['name' => 'TRX Suspension Trainer', 'description' => 'Original TRX Suspension Training system that leverages body weight and gravity for hundreds of exercises. Includes door anchor, suspension anchor, and mesh carry bag. Rated for users up to 350 lb. Used by military, pro athletes, and everyday fitness enthusiasts.', 'price' => 169.95, 'stock' => 35, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=600&h=400&fit=crop', 'category' => 'resistance-training'],
            ['name' => 'Battle Rope (40 ft x 1.5 in)', 'description' => 'Heavy-duty 40-foot poly dacron battle rope with 1.5-inch diameter. Weighs approximately 28 lb. Heat-shrunk handles prevent fraying. Develops explosive power, grip strength, and cardiovascular endurance. Includes wall-mount anchor.', 'price' => 89.99, 'stock' => 22, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1599058945522-28d584b6f0ff?w=600&h=400&fit=crop', 'category' => 'resistance-training'],

            // Accessories
            ['name' => 'Weightlifting Belt (Leather)', 'description' => 'Premium 10mm thick genuine leather powerlifting belt with single-prong buckle. 4-inch width throughout for maximum core support. Break-in period produces a custom mold to your body. Available in sizes S through XXL.', 'price' => 69.99, 'stock' => 45, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=600&h=400&fit=crop', 'category' => 'accessories'],
            ['name' => 'Lifting Straps (Pair)', 'description' => 'Heavy-duty cotton lifting straps with neoprene padding for comfort. 21.5-inch length wraps securely around any barbell or dumbbell. Reinforced stitching for durability. Reduces grip fatigue during heavy pulls like deadlifts and rows.', 'price' => 14.99, 'stock' => 80, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=600&h=400&fit=crop', 'category' => 'accessories'],
            ['name' => 'Gym Chalk Block (8-Pack)', 'description' => 'Magnesium carbonate gym chalk for enhanced grip during lifting. 8 blocks of 2 oz each. Absorbs moisture and sweat for a secure hold. Essential for deadlifts, pull-ups, and Olympic lifts. Resealable packaging keeps chalk dry.', 'price' => 12.99, 'stock' => 150, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=600&h=400&fit=crop', 'category' => 'accessories'],
            ['name' => 'Workout Timer / Interval Clock', 'description' => 'LED gym timer with 4-inch digits visible from 60+ feet. Programs EMOM, Tabata, AMRAP, and custom intervals. Includes wall mount, remote control, and buzzer. Rechargeable battery lasts 12+ hours.', 'price' => 129.99, 'stock' => 0, 'status' => 'unavailable', 'image' => 'https://images.unsplash.com/photo-1593079831268-3381b0db4a77?w=600&h=400&fit=crop', 'category' => 'accessories'],

            // Gym Apparel
            ['name' => 'Training Gloves (Pair)', 'description' => 'Padded workout gloves with silicone grip palms and breathable mesh back. Velcro wrist wrap for secure fit. Protects hands from calluses during weight training. Machine washable. Available in S, M, L, XL.', 'price' => 24.99, 'stock' => 60, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1556817411-31ae72fa3ea0?w=600&h=400&fit=crop', 'category' => 'gym-apparel'],
            ['name' => 'Compression Knee Sleeves (Pair)', 'description' => '7mm neoprene knee sleeves providing warmth and compression for squats, Olympic lifts, and general training. Reinforced stitching, anti-slip silicone strips. Reduces joint stiffness and supports injury prevention.', 'price' => 39.99, 'stock' => 50, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1434682881908-b43d0467b798?w=600&h=400&fit=crop', 'category' => 'gym-apparel'],
            ['name' => 'Wrist Wraps (18 inch, Pair)', 'description' => 'Stiff wrist wraps with thumb loop for overhead pressing, bench press, and front squats. 18-inch length with Velcro closure. Provides wrist stability under heavy loads. Competition-approved.', 'price' => 19.99, 'stock' => 70, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=400&fit=crop', 'category' => 'gym-apparel'],

            // Recovery & Mobility
            ['name' => 'Foam Roller (High-Density, 36 inch)', 'description' => 'High-density EVA foam roller for myofascial release and muscle recovery. 36-inch length and 6-inch diameter. Firm enough for deep tissue work, smooth surface. Ideal for back, legs, IT band, and general stretching.', 'price' => 29.99, 'stock' => 55, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1600881333168-2ef49b341f30?w=600&h=400&fit=crop', 'category' => 'recovery-mobility'],
            ['name' => 'Massage Gun (Percussion Therapy)', 'description' => 'Handheld percussion massage device with 6 speed settings (1200-3200 RPM) and 4 interchangeable heads. Quiet brushless motor, 6-hour battery life. Helps relieve muscle soreness, improve blood flow, and accelerate recovery.', 'price' => 149.99, 'stock' => 28, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=600&h=400&fit=crop', 'category' => 'recovery-mobility'],
            ['name' => 'Yoga Mat (6mm, Non-Slip)', 'description' => 'Extra-thick 6mm yoga and exercise mat with non-slip textured surface on both sides. 72x24 inch dimensions, closed-cell construction resists moisture and bacteria. Includes carrying strap. Suitable for yoga, stretching, and floor exercises.', 'price' => 34.99, 'stock' => 40, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=600&h=400&fit=crop', 'category' => 'recovery-mobility'],
            ['name' => 'Lacrosse Ball Set (3-Pack)', 'description' => 'Set of 3 solid rubber lacrosse balls for targeted trigger point therapy. Firm density works deep into knots and tight muscles. Great for feet, shoulders, back, and glutes. Compact and portable for gym bag or travel.', 'price' => 9.99, 'stock' => 90, 'status' => 'available', 'image' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=600&h=400&fit=crop', 'category' => 'recovery-mobility'],
        ];

        foreach ($products as $productData) {
            $categorySlug = $productData['category'];
            unset($productData['category']);
            $productData['category_id'] = $categories[$categorySlug];

            $product = Product::create($productData);

            Review::factory(fake()->numberBetween(2, 8))->create([
                'model_type' => Product::class,
                'model_id' => $product->id,
                'author_type' => User::class,
                'author_id' => $user->id,
            ]);
        }
    }
}
