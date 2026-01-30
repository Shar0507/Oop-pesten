<?php
/**
 * Summary of Card class
 */
class Card {
    private $worth; // 'A', '2'..'10', 'J', 'Q', 'K'
    private $sign;  // 'S', 'J', 'K', 'Q'

    public function __construct($worth, $sign) {
        $this->worth = strtoupper((string)$worth);
        $this->sign = strtoupper((string)$sign);
    } 

    public function getWorth() {
        return $this->worth;
    } 
    
    public function getSign() {
        return $this->sign;
    }

    /**
     * Renders the card image HTML
     * @param bool $showCard Whether to show the front or the back
     * @param string $backFile The filename for the back of the card
     */
    public function render($showCard = false, $backFile = 'blauw') {
        if ($showCard) {
            // Filename based on suit and worth (e.g., "Foto/H2.svg")
            $filename = "Foto/" . $this->sign . $this->worth . ".svg";
        } else {
            // Filename for the back of the card
            $filename = "Foto/" . $backFile . ".svg";
        }
        
        $src = htmlspecialchars($filename, ENT_QUOTES, 'UTF-8');
        echo '<img class="card" src="' . $src . '" width="110" alt="Playing card">';
    }  
}