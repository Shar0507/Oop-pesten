<?php
include 'Card.php';
include 'Deck.php';
include 'Hand.php';
include 'Gameleader.php';
include 'DiscardPile.php';
include 'pickup.php';

session_start();

// 1. Reset logica
if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// 2. Game initialisatie
if (isset($_SESSION['Game'])) {
    $game = $_SESSION['Game'];
} else {
    $game = new Gameleader(4);
    // Trek de eerste kaart voor de aflegstapel
    $game->discardPile->placeCard($game->deck->draw());
    $_SESSION['Game'] = $game;
}

// 3. Input verwerking
if (isset($_GET['card'])) {
    $game->handleInput($_GET['card']);
    $_SESSION['Game'] = $game;
    header("Location: index.php");
    exit;
}

// 4. Belangrijk: haal de beurt ALTIJD op voor de weergave
$currentTurn = $game->getTurn(); 

// CSS variabelen
$deckCount = count($game->deck->cards);
$discardCount = count($game->discardPile->cards);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pesten - Card Game</title>
    <style type="text/css">
        body {
            background-image: url("Foto/poker.avif"); 
            background-size: cover;
            background-color: white;
            font-family: system-ui, sans-serif;
            margin: 0;
            overflow: hidden;
        }
        
        card img { height: 154px; }

        hand {
            width: 300px;
            height: 200px;
            display: block;
            position: absolute;
        }

        hand card {
            position: absolute;
            bottom: 0px;
            transition: bottom 0.2s ease;
        }

 /* When hovering over a card in your hand */
hand card:hover {
    bottom: 30px; /* Card pops up higher */
    z-index: 100;
    cursor: pointer;
    filter: brightness(1.1); /* Highlights the card */
    transform: scale(1.05); /* Slightly enlarges it */
}

/* Container voor de stapels in het midden */
.middle-board {
    position: absolute;
    top: 50%;
    left: 50%;
    gap:60px;
    transform: translate(-50%, -50%); /* Zet het exacte midden van de div in het midden van het scherm */
    width: 400px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    z-index: 10;
}

/* Voeg dit toe aan je <style> in index.php */
.active-player {
    border: 3px solid gold;
    box-shadow: 0 0 20px gold;
    border-radius: 10px;
    background: rgba(255, 215, 0, 0.1);
}

.turn-indicator {
    position: absolute;
    top: 10px;
    left: 10px;
    background: white;
    padding: 10px;
    border-radius: 5px;
}
/* Container voor label + stapel */
.stack-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 110px; /* Breedte van één kaart */
}

.stack-container label {
    margin-bottom: 10px;
    font-weight: bold;
    height: 20px; /* Vaste hoogte voor labels zodat de kaarten eronder gelijk starten */
}

/* De individuele stapels */
deck, discardpile {
    position: relative; /* Belangrijk: kaarten binnenin positioneren t.o.v. de stapel */
    width: 110px;
    height: 154px;
    border: 1px dashed #cccccc
    }

/* Zorg dat kaarten in de aflegstapel netjes op elkaar liggen */
discardpile card, discardpile img {
    position: absolute;
    top: 0;
    left: 0;
}
/* Verwijder eventuele witruimte onder afbeeldingen */
deck img, discardpile img {
    position: absolute;
    height: 154px;
    width: 110px;
}
        /* Posities van de spelers op het scherm */
.P0 { left: 50%; bottom: 20px; transform: translateX(-50%); } /* Onderaan (Jij) */
.P1 { left: 50%; top: 20px; transform: translateX(-50%) rotate(180deg); } /* Bovenaan (Tegenstander) */
.P2 { left: 20px; top: 50%; transform: translateY(-50%) rotate(90deg); } /* Links */
.P3 { right: 20px; top: 50%; transform: translateY(-50%) rotate(-90deg); } /* Rechts */

/* Genereer dynamisch de verschuiving per kaart */
<?php 
foreach($game->players as $playerIndex => $player) {
    $cardCount = count($player->cards);
    for ($i = 0; $i < $cardCount; $i++) {
        // Bereken de horizontale verschuiving (offset)
        // We trekken de helft van het totaal af om de hand te centreren
        $offset = ($i - ($cardCount / 2)) * 30; 
        
        // Let op: we gebruiken nth-child(i+1) omdat CSS bij 1 begint te tellen
        $childIndex = $i + 1;
        echo ".P$playerIndex card:nth-child($childIndex) { left: {$offset}px; }\n";
    }
    
}
?>

    </style>
    </head>
<body>
<?php $currentTurn = $game->getTurn(); ?>

<hand class="P0"><?php $game->players[0]->render($currentTurn === 0); ?></hand>
<hand class="P1"><?php $game->players[1]->render($currentTurn === 1); ?></hand>
<hand class="P2"><?php $game->players[2]->render($currentTurn === 2); ?></hand>
<hand class="P3"><?php $game->players[3]->render($currentTurn === 3); ?></hand>

<div class="middle-board">
    <div class="stack-container">
        <label>Pakken</label>
        <?php $game->deck->render(); ?>
    </div>
    
    <div class="stack-container">
        <label>Opleggen</label>
        <?php $game->discardPile->render(); ?>
    </div>
</div>
</body>
    </html>
