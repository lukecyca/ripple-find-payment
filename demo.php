<?php
error_reporting( E_ALL );
include "ripple_find_payment.php";


/* Valid transaction */
print("Searching for rMePKzb8A712GfrQh2ii5ndGpjiKT2oY3a -> r4mdZAcwwzdCu2915DuUkYhnY7BsS26J7G 1 XRP\n");
$tx = ripple_find_payment("rMePKzb8A712GfrQh2ii5ndGpjiKT2oY3a", "r4mdZAcwwzdCu2915DuUkYhnY7BsS26J7G", 1, "XRP");
if ($tx === false) {
    print("Payment not made\n");
} elseif ($tx === null) {
    print("Ripple server error\n");
} else {
    print("Payment made\n");
    //print_r($tx);
}
print("\n");


/* Valid transaction */
print("Searching for r45dBj4S3VvMMYXxr9vHX4Z4Ma6ifPMCkK -> rhJZsAg8Gwc5M13im5UWeiuKxhdYNq6AFk 1000 CNY\n");
$tx = ripple_find_payment("r45dBj4S3VvMMYXxr9vHX4Z4Ma6ifPMCkK", "rhJZsAg8Gwc5M13im5UWeiuKxhdYNq6AFk", 1000, "CNY");
if ($tx === false) {
    print("Payment not made\n");
} elseif ($tx === null) {
    print("Ripple server error\n");
} else {
    print("Payment made\n");
    //print_r($tx);
}
print("\n");


/* Old transaction - not found */
print("Searching for r4mdZAcwwzdCu2915DuUkYhnY7BsS26J7G -> rsUbREfLAryRoENV8BgQMBPyGH8pSnx7J4 5 CAD\n");
$tx = ripple_find_payment("r4mdZAcwwzdCu2915DuUkYhnY7BsS26J7G", "rsUbREfLAryRoENV8BgQMBPyGH8pSnx7J4", 5, "CAD");
if ($tx === false) {
    print("Payment not made\n");
} elseif ($tx === null) {
    print("Ripple server error\n");
} else {
    print("Payment made\n");
    //print_r($tx);
}
print("\n");


/* Non-existant transaction - not found
   Since r3ADD8kXSUKHd6zTCKfnKT3zV9EZHjzp1S is a VERY active account, this can take a long time to return
   if $RIPPLE_MAX_LEDGER_HISTORY is set to a large value. */
print("Searching for r4mdZAcwwzdCu2915DuUkYhnY7BsS26J7G -> r3ADD8kXSUKHd6zTCKfnKT3zV9EZHjzp1S 5 LOL\n");
$tx = ripple_find_payment("r4mdZAcwwzdCu2915DuUkYhnY7BsS26J7G", "r3ADD8kXSUKHd6zTCKfnKT3zV9EZHjzp1S", 5, "LOL");
if ($tx === false) {
    print("Payment not made\n");
} elseif ($tx === null) {
    print("Ripple server error\n");
} else {
    print("Payment made\n");
    //print_r($tx);
}
print("\n");



/* Invalid transaction - not found */
print("Searching for r3gHXhK1pwZFG9ESiaosxjufEVQjwGuJUd -> rE2RoQE8fSMyaZo9UjAsq22oFby9NJ5uqb 90 DVC\n");
$tx = ripple_find_payment("r3gHXhK1pwZFG9ESiaosxjufEVQjwGuJUd", "rE2RoQE8fSMyaZo9UjAsq22oFby9NJ5uqb", 90, "DVC");
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
