<?php
// include 'Card.php';

class Deck {

    /** @var Card[] */
    public $cards = []; 

    private $worths;
    private $sign;  

    function __construct() {
        $this->cards = [];
        $this->worths = ['2','3','4','5','6','7','8','9','10','J','Q','K','A'];
        $this->sign  = ['J','S','Q','K'];

        $this->createDeck();
        $this->shuffle(); 
    }
    private function createDeck() {
        foreach ($this->sign as $s) {
            foreach ($this->worths as $w) {
                // We use 'Card' (singular) as defined in the previous step
                $this->cards[] = new Card($w, $s);
            }
        }
    }

    /**
     * Randomizes the order of the cards
     */
    public function shuffle() {
        shuffle($this->cards);
    }

    /** * Pulls the top card from the deck
     * @return Card|null
     */
    public function draw() {
        if (empty($this->cards)) {
            return null;
        }
        return array_shift($this->cards);
    }

    /**
     * Renders the deck stack for the UI
     * While rendering we only want to show 5 cards of the stack
     */
   // In je Draw/Deck class:
public function render() {
    // We voegen een visuele check toe: alleen de achterkant tonen
    echo '<deck onclick="window.location.href=\'index.php?card=draw\'" style="cursor:pointer;">';
    if (count($this->cards) > 0) {
        echo '<img src="Foto/blauw.svg" alt="Pickup" height="154">';
    } else {
        echo '<div style="width:110px; height:154px; border:1px solid red;">Leeg</div>';
    }
    echo '</deck>';
}
}
        