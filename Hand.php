<?php

class Hand {
    /** @var Card[] */
    public $cards = [];
    public $playerNumber;

    public function __construct($playerNumber) {
        $this->cards = [];
        $this->playerNumber = $playerNumber;
    }

    /**
     * Adds a card to the player's hand
     */
    public function addCard($card) {
        $this->cards[] = $card;
    }

    /**
     * Renders the hand of cards
     * @param bool $isVisible Whether the cards should be shown face-up
     */
public function render($isVisible) {
    echo "<hand>";
    foreach ($this->cards as $key => $card) {
        if ($card instanceof Card) {
            // Alleen een onclick toevoegen als de kaarten zichtbaar zijn (jouw beurt)
            $onclick = $isVisible ? "onclick=\"window.location.href='index.php?card=$key'\"" : "";
            $style = $isVisible ? "cursor: pointer;" : "cursor: default;";
            
            echo "<card $onclick style='$style'>";
            $card->render($isVisible);
            echo "</card>";
        }
    }
    echo "</hand>";
}

    /**
     * Removes a card from the hand by its ID/index
     * @param int $id The index of the card in the hand
     * @return Card|null
     */
    public function removeCard($id) {
        if (!isset($this->cards[$id])) {
            return null;
        }

        $card = $this->cards[$id];
        unset($this->cards[$id]);

        // Re-index the array so there are no gaps
        $this->cards = array_values($this->cards);

        return $card;
    }
}