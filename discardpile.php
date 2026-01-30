<?php

class DiscardPile {
    /** @var Card[] */
    public $cards = []; 

    public function __construct() {
        $this->cards = [];
    }

    /**
     * Adds a card to the top of the discard pile
     */
    public function placeCard(Card $card) {
        $this->cards[] = $card;
    }

    /**
     * Empties the pile and returns all cards (used for reshuffling)
     * @return Card[]
     */
    public function drawAllCards() {
        $allCards = $this->cards;
        $this->cards = [];
        return $allCards;
    }

    /**
     * Renders the discard pile HTML
     */
    public function render() { 
        echo "<discardpile>";
        foreach($this->cards as $card) {
            // We use 'render' to match the updated Card class
            $card->render(true);
        }
        echo "</discardpile>";
    }
}