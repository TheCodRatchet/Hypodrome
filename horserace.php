<?php

function breedHorses(int $number, int $multiplier): stdClass
{
    $horse = new stdClass();
    $horse->number = $number;
    $horse->multiplier = $multiplier;

    return $horse;
}

$wallet = (int)readline("Enter amount of cash You have($): ");
while ($wallet < 1) {
    echo "Invalid amount!!!";
    $wallet = (int)readline("Enter amount of cash You have($): ");
}

$horses = [
    breedHorses(5, rand(1, 10)),
    breedHorses(7, rand(1, 10)),
    breedHorses(99, rand(1, 10)),
    breedHorses(69, rand(1, 10)),
    breedHorses(96, rand(1, 10)),
    breedHorses(12, rand(1, 10)),
    breedHorses(1, rand(1, 10)),
    breedHorses(24, rand(1, 10)),
];

foreach ($horses as $id => $horse) {
    echo "$id - {$horse->number}  (Multiplier for this race: $horse->multiplier)" . PHP_EOL;
}

$bets = [];
$bettingTime = true;

while ($bettingTime) {

    $selection = readline("Please select horses ID You want to bet on: ");
    if (isset($horses[$selection]) == false) {
        echo "This horse doesn't exist!!!" . PHP_EOL;
        continue;
    }

    if (isset($bets[$selection])) {
        echo "You already put bet on this horse!!!" . PHP_EOL;
        continue;
    }

    $betting = readline("Place your bet amount($): ");
    while (is_numeric($betting) == false || $betting <= 0) {
        echo "Incorrect bet amount!!!" . PHP_EOL;
        $betting = readline("Place your bet amount($): ");
    }

    while ($betting > $wallet) {
        echo "You don't have enough money!!!" . PHP_EOL;
        $betting = readline("Place your bet amount($): ");

    }

    $wallet -= $betting;

    if ($wallet <= 0) {
        echo "You don't have enough money to bet more" . PHP_EOL;
        break;
    }

    $continue = strtolower(readline("Do you want to bet on other horse [y/n]: "));
    while ($continue !== "y" && $continue !== "n") {
        echo "Invalid input!!!" . PHP_EOL;
        $continue = strtolower(readline("Do you want to bet on other horse [y/n]: "));
    }

    $bets[$selection] = $betting;

    if ($continue == "y") {
        $bettingTime = true;
    } else {
        $bettingTime = false;
    }
}

$track = [];

function drawTrack($lanes)
{
    foreach ($lanes as $lane) {
        foreach ($lane as $sector) {
            echo "$sector ";
        }
        echo PHP_EOL;
    }
    echo "___________________________" . PHP_EOL;
}

$position = [0];

for ($i = 0; $i < count($horses); $i++) {
    $position[$i] = 0;
}

$finish = [];

while (count(array_unique(($finish))) != count($horses)) {
    for ($c = 0; $c < count($horses); $c++) {
        $track[$c] = array_fill(0, 11, "_");
        if ($position[$c] >= 10) {
            $track[$c][10] = $horses[$c]->number;
        } else {
            $track[$c][$position[$c]] = $horses[$c]->number;
            $position[$c] += rand(1, 2);
        }
    }
    foreach ($track as $lane) {
        if ($lane[10] !== "_") {
            $finish[] = $lane[10];
        }
    }
    drawTrack($track);
    sleep(1);
}

$places = array_values(array_unique($finish));

foreach ($places as $place => $number) {
    echo "Horse NR.$number has taken: " . ($place + 1) . ". place" . PHP_EOL;
}

foreach ($bets as $entry => $bet) {
    if ($places[0] == ($horses[$entry]->number)) {
        echo "You won " . $bet * $horses[$entry]->multiplier . "$" . PHP_EOL;
        $wallet += $bet * $horses[$entry]->multiplier;
        exit;
    } else {
        echo "You Lost." . PHP_EOL;
        exit;
    }
}
