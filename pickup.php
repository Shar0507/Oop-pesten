<?php

class Draw {
    /** @var Card[] */
    public $cards = [];


    public function __construct(array $deck) {
        // Assuming $deck is an array of Card objects
        $this->cards = $deck;
    }

    /**
     * Draws the top card from the pile
     * @return mixed|null
     */
public function drawCard() {
    if ($this->getCount() == 0) { // FOUT: Dit betekent "als er kaarten zijn, stop"
        return null;
    }
    return array_shift($this->cards);
}
    
    /**
     * Returns the remaining number of cards
     */
    public function getCount() {
        return count($this->cards);
    }

// In je Draw/Deck class:
public function render() {
    echo "<deck>";
    if (count($this->cards) > 0) {
        // Link naar pickup.php of naar de huidige pagina met een actie
        echo "<a href='?action=draw'><img src='Foto/blauw.png'></a>";
    }
    echo "</deck>";
}
}

?>