<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Support\Facades\Route;

// Route::get('/generate-dummy-files', function () {
   // Generate dummy files with data for file types .xlsx and .csv
    return GenerateDummyFiles::generateFiles();
});
Route::get('/test-1', function () {
    /**
     The goal of the task is primarily to demonstrate a good understanding of OOP principles.
     Notes: 
     ● No need to write HTML/CSS code. 
     ● No need to create a database. 
     ● The emphasis is on OOP programming. 
     ● If possible, it is necessary to implement the following at the appropriate places: 
         ● Inheritance 
         ● Interfaces 
         ● Polymorphism 
         ● Exception handling 
     Description: 
     1. The Fruit class describes a fruit, which is determined by it’s color and volume. 
     2. An Apple class is a fruit that can be rotten. 
     3. The Juicer consists of three parts: a Fruit Container, Juice Container and a Strainer. 
     4. The Fruit Container has its capacity and can hold fruits. 
     5. The Juice Container has its capacity and can hold juice.
     5. In the Fruit Container, I can add a fruit, up to 500 grams. I can see how many fruits are inside, and how much space is left. 
     6. The Strainer is responsible for squeezing the fruits. With each squeeze, I can see how much juice is obtained in Juice Container. 
     7. Juicing one fruit yields an amount of juice equal to 50% of the fruit's volume. For example, one squeeze of an apple with 70 grams will yield 35 mL of juice.
     Task Simulation: 
     ● Simulate the operation of a Juice Container with a volume of 20 liters and with a Fruit Container filled with random apples of volume between 70-100 grams. But when last one exceed maximum capacity of Fruit Container, it will be discarded and  action of squeezing start with the rest of apples in it:
         ○ Actions are performed on the juicer, and each action is logged as output on the screen. 
         ○ The juicer is programmed to perform 100 consecutive actions of squeezing. 
         ○ Every 9 squeezing actions, an additional apple is added. 
         ○ An apple has a volume from 70 grams that will represent as 1, 77.5 will be as 2, 85 will be as 3, 92.5 will be as 4 and 100 grams will be as represent 5. So apple volume will go from 1 to 5
         ○ You can squeeze only one apple at a time. You can squeeze every apple 3 times, then move to next one and repeat fore every apple. [Apple-1, Apple-2, Apple-3, Apple-4, Apple-5] it will go first Apple-1 3x then Apple-2 3x and so on.
         ○ With each subsequent squeeze, it will lose 50% of its existing volume. For example, if the apple has 100 grams (Represent as Apple-5), after the first squeeze, it will lose 50 grams and it will give 50 mL of juice after the second squeeze, it will have 25 grams and it will give 25 mL of juice, and after the third squeeze, it will give 12.5 mL when third time finis, it will move to next fruit in Fruit Container.
         ○ An apple has a 20% chance of being rotten.   
         ○ If the apple is rotten, it will not be squeezed and will be discarded and replaced with a new apple that is not rotten to Fruit Container. Just count of rotten apples will be displayed after every filling of Fruit Container.
         ○ At the end of the simulation, the total amount of juice produced that is in Juicer Container will be displayed
     */

    // Interface for fruits that can rot
    interface RottenInterface {
        public function isRotten(): bool;
    }
    // Interface for all fruits
    interface FruitInterface {
        public function getVolume(): float;
        public function getColor(): string;
    }
    // Interface for squeezable items
    interface Squeezable {
        public function squeeze(): float;
    }
    // Custom Exception for invalid fruit volumes
    class InvalidFruitVolumeException extends Exception {
        private $invalidVolume;

        public function __construct(float $invalidVolume, string $message = "", int $code = 0, Throwable $previous = null) {
            $this->invalidVolume = $invalidVolume;
            if (empty($message)) {
                $message = "Invalid fruit volume: " . $invalidVolume;
            }
            parent::__construct($message, $code, $previous);
        }
    
        public function getInvalidVolume(): float {
            return $this->invalidVolume;
        }
    }
    // Abstract class for Fruit implementing FruitInterface and Squeezable
    abstract class Fruit implements FruitInterface, Squeezable {
        protected $color;
        protected $volume;
        protected $originalVolume;
    
        public function __construct($color, $volume) {
            $this->color = $color;
            $this->volume = $volume;
            $this->originalVolume = $volume;
        }
        // Get the volume of the fruit
        public function getVolume(): float {
            return $this->volume;
        }
        // Get the color of the fruit
        public function getColor(): string {
            return $this->color;
        }
    }
    // Apple class extending Fruit and implementing RottenInterface
    class Apple extends Fruit implements RottenInterface {
        private $isRotten;
        const JUICE_YIELD = 0.5; // 50% juice yield
        const ROTTEN_CHANCE = 20; // 20% chance of being rotten
    
        public function __construct($color, $volume) {
            parent::__construct($color, $volume); // Inheritance from Fruit
            $this->isRotten = (rand(1, 100) <= self::ROTTEN_CHANCE); // Randomly determine if the apple is rotten
        }
        // Check if the apple is rotten
        public function isRotten(): bool {
            return $this->isRotten;
        }
        // Squeeze the apple for juice
        public function squeeze(): float {
            if ($this->volume > 0) {
                $juice = $this->volume * self::JUICE_YIELD; // Calculate the amount of juice
                $this->volume *= (1 - self::JUICE_YIELD); // Reduce the volume of the apple
                return $juice;
            }
            return 0;
        }
        // Convert the apple to a string representation using a match statement
        // The average apple weighs between 70 and 100 grams, which is about 0.33 pounds. This can vary depending on the type and size of the apple.
        public function __toString(): string {
            $weightCategory = match (true) {
                $this->originalVolume <= 70 => 1, // 70 ml or less
                $this->originalVolume <= 77.5 => 2, // 71-77.5 ml
                $this->originalVolume <= 85 => 3, // 78-85 ml
                $this->originalVolume <= 92.5 => 4, // 86-92.5 ml
                default => 5, // 93 ml or more
            };
            return "apple-" . $weightCategory;
        }
    }
    // // Example of another fruit class: Orange
    // class Orange extends Fruit implements RottenInterface {
    //     private $isRotten;
    //     const JUICE_YIELD = 0.6;
    //     const ROTTEN_CHANCE = 15;

    //     public function __construct($color, $volume) {
    //         parent::__construct($color, $volume); // Inheritance from Fruit
    //         $this->isRotten = (rand(1, 100) <= self::ROTTEN_CHANCE);
    //     }
    //     // Check if the orange is rotten
    //     public function isRotten(): bool {
    //         return $this->isRotten;
    //     }
    //     // Squeeze the orange for juice
    //     public function squeeze(): float {
    //         if ($this->volume > 0) {
    //             $juice = $this->volume * self::JUICE_YIELD;
    //             $this->volume *= (1 - self::JUICE_YIELD);
    //             return $juice;
    //         }
    //         return 0;
    //     }
    //     // Convert the orange to a string representation
    //     public function __toString(): string {
    //         return "orange-" . $this->volume;
    //     }
    // }
    // FruitContainer class to hold fruits
    class FruitContainer {
        private $capacity;
        private $fruits = [];
    
        public function __construct($capacity) {
            $this->capacity = $capacity;
        }
        // Add a fruit to the container if it fits
        public function addFruit(FruitInterface $fruit): bool { // Polymorphism with FruitInterface
            $currentVolume = array_sum(array_map(fn($f) => $f->getVolume(), $this->fruits)); // Get the current volume of all fruits in the container
            if ($currentVolume + $fruit->getVolume() <= $this->capacity) {
                $this->fruits[] = $fruit;
                return true;
            }
            return false;
        }
        // Get all fruits in the container
        public function getFruits(): array {
            return $this->fruits;
        }
        // Clear all fruits from the container
        public function clear(): void {
            $this->fruits = [];
        }
        // Get the current volume of fruits in the container
        public function getCurrentVolume(): float {
            return array_sum(array_map(fn($f) => $f->getVolume(), $this->fruits)); // Get the current volume of all fruits in the container
        }
    }
    
    // JuiceContainer class to hold juice
    class JuiceContainer {
        private $capacity;
        private $currentVolume = 0;
    
        public function __construct($capacity) {
            $this->capacity = $capacity;
        }
        // Add juice to the container if it fits
        public function addJuice(float $amount): bool {
            if ($this->currentVolume + $amount <= $this->capacity) { // Check if the juice fits in the container
                $this->currentVolume += $amount;
                return true;
            }
            return false;
        }
        // Get the current volume of juice in the container
        public function getCurrentVolume(): float {
            return $this->currentVolume;
        }
    }
    // Strainer class responsible for squeezing fruits
    class Strainer {
        public function squeezeFruit(Squeezable $fruit, JuiceContainer $juiceContainer): ?float { // Polymorphism with Squeezable interface and JuiceContainer
            try {
                $juice = $fruit->squeeze(); // Squeeze the fruit for juice
                if ($juice > 0) {
                    $juiceContainer->addJuice($juice); // Add the juice to the juice container
                }
                return $juice;
            } catch (Exception $e) {
                error_log("Error squeezing fruit: " . $e->getMessage()); // Log the error
                return null; // Or throw the exception if you want it to propagate
            }
        }
    }
    // Juicer class to manage the juicing process
    class Juicer {
        private FruitContainer $fruitContainer; // Polymorphism with FruitContainer
        private JuiceContainer $juiceContainer; // Polymorphism with JuiceContainer
        private Strainer $strainer;
        const ADD_FRUIT_INTERVAL = 9; // Add fruits every 9 actions
        const MAX_FRUIT_VOLUME = 500; // Maximum volume of fruits that can be added to the container at once
    
        public function __construct(FruitContainer $fruitContainer, JuiceContainer $juiceContainer, Strainer $strainer) {
            $this->fruitContainer = $fruitContainer;
            $this->juiceContainer = $juiceContainer;
            $this->strainer = $strainer;
        }
        // Simulate the juicing process
        public function simulate(int $actions): void {
            echo "Start:<br>"; 
            $this->addRandomApples(); // Add random apples to the container
    
            $rottenCount = 0;
    
            for ($actionNumber = 1; $actionNumber <= $actions;) { // Loop through the actions
                // Refill container every 9 actions
                if (($actionNumber - 1) % self::ADD_FRUIT_INTERVAL === 0 && $actionNumber > 1) {
                    echo "--<br>";
                    $this->addRandomApples();  // Add random apples to the container
                }
    
                $fruits = $this->fruitContainer->getFruits(); // Get the fruits from the container
                $this->fruitContainer->clear(); // Clear the container
    
                foreach ($fruits as $fruit) {
                    if ($fruit instanceof RottenInterface && $fruit->isRotten()) { // Check if the fruit is rotten
                        $rottenCount++;
                        continue;
                    }
                    // Squeeze each fruit three times or until depleted
                    for ($squeezeCount = 1; $squeezeCount <= 3; $squeezeCount++) { // Squeeze the fruit three times or until depleted
                        if ($actionNumber > $actions) break;
    
                        $juice = $this->strainer->squeezeFruit($fruit, $this->juiceContainer); // Squeeze the fruit for juice
                        echo "Action $actionNumber: Squeeze [$fruit] - " . number_format($juice, 1) . " ml Juice added to Juice Container<br>";
                        $actionNumber++;
    
                        if ($fruit->getVolume() < 10 || $actionNumber % self::ADD_FRUIT_INTERVAL === 1) break; // Break if no more juice can be squeezed
                    }
                    // Add remaining fruit back to container
                    if ($fruit->getVolume() >= 10) {
                        $this->fruitContainer->addFruit($fruit); // Add the remaining fruit back to the container
                    }
                    // Break if no more juice can be squeezed
                    if ($actionNumber % self::ADD_FRUIT_INTERVAL === 1) {
                        break;
                    }
                }
            }
            echo "--<br>Total Juice Produced: " . round($this->juiceContainer->getCurrentVolume(), 2) . " ml<br>";
        }
        // Add random apples to the container
        private function addRandomApples(): void {
            $this->fruitContainer->clear(); // Clear the container
            $totalVolume = 0; // Total volume of fruits added to the container
            $rottenDiscarded = 0; // Number of rotten apples discarded
            // 1 = 70g, 2 = 77.5g, 3 = 85g, 4 = 92.5g, 5 = 100g
            $volumes = [70, 77.5, 85, 92.5, 100]; // Possible volumes of apples in grams that will be represented as numbers from 1 to 5
            while ($totalVolume < self::MAX_FRUIT_VOLUME) {
                $volume = $volumes[array_rand($volumes)]; // Randomly select a volume from the possible volumes
                if ($totalVolume + $volume > self::MAX_FRUIT_VOLUME) { // If adding the selected volume of fruit would exceed the maximum volume, break the loop
                    break;
                }
                try{
                    $apple = new Apple('red', $volume); // Create a new Apple object with the selected volume
                    if (!$apple->isRotten()) { // Check if the apple is not rotten
                        if ($this->fruitContainer->addFruit($apple)) {
                            $totalVolume += $volume;
                        }
                    } else {
                        $rottenDiscarded++; // If the apple is rotten, discard it and increment the count of discarded apples
                    }
                } catch (InvalidFruitVolumeException $e) {
                    dd("Invalid fruit volume encountered: " . $e->getInvalidVolume()); // If an exception is thrown, display the error message
                    // Or get the message: $e->getMessage(); which would be "Invalid fruit volume: -10"
                }

            }    
            echo "Add fruits to Fruit Container: [" . implode(", ", array_map(fn($f) => (string) $f, $this->fruitContainer->getFruits())) . "]<br>";
            echo "Rotten discarded: $rottenDiscarded<br>";
            echo "Fruit Container " . $this->fruitContainer->getCurrentVolume() . " grams of maximum " . self::MAX_FRUIT_VOLUME . " grams<br>--<br>";
        }
    }
    // Simulation setup
    $fruitContainer = new FruitContainer(500); // 500 grams of maximum FruitContainer capacity
    $juiceContainer = new JuiceContainer(20000); // 20 liters of maximum JuiceContainer capacity
    $strainer = new Strainer(); // Create a new Strainer object

    $juicer = new Juicer($fruitContainer, $juiceContainer, $strainer); // Create a new Juicer object with the FruitContainer, JuiceContainer and Strainer
    
    $juicer->simulate(100); // Simulate the operation of the Juicer for 100 actions
     
});
// Route::get('/dashboard', function () {
//     return view('pages.dashboard');
// })->name('dashboard');

// Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management routes (protected by user-management permission)
    // Route::middleware([PermissionMiddleware::class])->group(function () {
        // Users CRUD
        Route::resource('users', UserController::class);
        
        // Permissions CRUD
        Route::prefix('permissions')->controller(PermissionController::class)->group(function () {
            // Basic CRUD routes
            Route::get('/', 'index')->name('permissions.index');
            Route::get('/create', 'create')->name('permissions.create');
            Route::post('/', 'store')->name('permissions.store');
            Route::get('/{permission}', 'show')->name('permissions.show');
            Route::get('/{permission}/edit', 'edit')->name('permissions.edit');
            Route::put('/{permission}', 'update')->name('permissions.update');
            Route::delete('/{permission}', 'destroy')->name('permissions.destroy');
            // User assignment routes
            Route::get('/assign/form', 'assignForm')->name('permissions.assignForm');
            Route::post('/user-permissions', 'userPermissions')->name('permissions.userPermissions');
            Route::post('/assign', 'assign')->name('permissions.assign');
            // Show users with specific permission
            Route::get('/{permission}/users', 'showUsers')->name('permissions.showUsers');
        });
        
        // User Permission Management
        Route::post('users/{user}/permissions', [UserController::class, 'updatePermissions'])
            ->name('users.permissions.update');
        Route::get('users/{user}/permissions', [UserController::class, 'editPermissions'])
            ->name('users.permissions.edit');
    // });

    // // Data Import routes
    // Route::prefix('data-import')->group(function () {
    //     Route::get('/', [DataImportController::class, 'index'])->name('data-import.index');
    //     Route::post('/upload', [DataImportController::class, 'upload'])->name('data-import.upload');
    //     Route::get('/preview', [DataImportController::class, 'preview'])->name('data-import.preview');
    //     Route::post('/process', [DataImportController::class, 'process'])->name('data-import.process');
    // });
// });

require __DIR__.'/auth.php';
// require __DIR__.'/admin.php';


