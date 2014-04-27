<?php
include "ripple_find_payment.php";


print("Searching for rMePKzb8A712GfrQh2ii5ndGpjiKT2oY3a -> r4mdZAcwwzdCu2915DuUkYhnY7BsS26J7G 1 XRP\n");
$tx = ripple_find_payment("rMePKzb8A712GfrQh2ii5ndGpjiKT2oY3a", "r4mdZAcwwzdCu2915DuUkYhnY7BsS26J7G", 1, XRP);
if ($tx === false) {
    print("Payment not made\n");
} elseif ($tx === null) {
    print("Ripple server error\n");
} else {
    print("Payment made\n");
    //print_r($tx);
}
print("\n");


print("Searching for r4mdZAcwwzdCu2915DuUkYhnY7BsS26J7G -> rsUbREfLAryRoENV8BgQMBPyGH8pSnx7J4 5 CAD\n");
$tx = ripple_find_payment("r4mdZAcwwzdCu2915DuUkYhnY7BsS26J7G", "rsUbREfLAryRoENV8BgQMBPyGH8pSnx7J4", 5, CAD);
if ($tx === false) {
    print("Payment not made\n");
} elseif ($tx === null) {
    print("Ripple server error\n");
} else {
    print("Payment made\n");
    //print_r($tx);
}
print("\n");


print("Searching for r45dBj4S3VvMMYXxr9vHX4Z4Ma6ifPMCkK -> rhJZsAg8Gwc5M13im5UWeiuKxhdYNq6AFk 1000 CNY\n");
$tx = ripple_find_payment("r45dBj4S3VvMMYXxr9vHX4Z4Ma6ifPMCkK", "rhJZsAg8Gwc5M13im5UWeiuKxhdYNq6AFk", 1000, CNY);
if ($tx === false) {
    print("Payment not made\n");
} elseif ($tx === null) {
    print("Ripple server error\n");
} else {
    print("Payment made\n");
    //print_r($tx);
}
print("\n");



?>
