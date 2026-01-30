<?php

class Gameleader 
{   
    public $deck; 
    public $discardPile; 
    public $players; 
    private $turn; 
    private $leftToRight = true;

    public function __construct($numberOfPlayers) {
        $this->deck = new Deck();
        $this->discardPile = new DiscardPile();
        $this->players = [];

        // Maak 4 spelers aan
        for ($i = 0; $i < 4; $i++) {
            $this->players[$i] = new Hand($i + 1);
        }

        $this->turn = 0;
    

            // Initial deal: deel 7 kaarten uit aan elke speler
    for ($i = 0; $i < 7; $i++) {
        $this->players[0]->addCard($this->deck->draw());
        $this->players[1]->addCard($this->deck->draw());
        $this->players[2]->addCard($this->deck->draw());
        $this->players[3]->addCard($this->deck->draw());
        }
    }
    

    /** Geeft de huidige beurt terug voor index.php */
    public function getTurn() {
        return $this->turn;
    }

    public function nextPlayer() { 
        if ($this->leftToRight) {
            $this->turn++;
        } else {
            $this->turn--;
        }

        // Zorg dat we binnen de array blijven (0 tot 3)
        if ($this->turn >= count($this->players)) { 
            $this->turn = 0;
        } 
        if ($this->turn < 0) {
            $this->turn = count($this->players) - 1;
        }
    } 



    public function handleInput($action) { 
        if ($action == "draw") { 
            $this->drawCard(); 
            $this->nextPlayer(); 
        } else {
            // $action is de index van de kaart die geklikt is
          if (is_numeric($action)) {
            $this->playCard((int)$action);
          }
        }
    }

    private function drawCard() {
        if (isset($this->players[$this->turn])) {
            $card = $this->deck->draw(); // Gebruik draw() uit je Deck class
            if ($card instanceof Card) {
                $this->players[$this->turn]->addCard($card);
            }

            // Als het deck bijna leeg is, schud de aflegstapel er weer in
            if (count($this->deck->cards) < 3) {
                $oldCards = $this->discardPile->drawAllCards(); 
                foreach ($oldCards as $c) {
                    if ($c instanceof Card) $this->deck->cards[] = $c;
                }
                $this->deck->shuffle(); 
            }
        }

    }

    private function playCard($cardId) {
        if (!isset($this->players[$this->turn])) return;
        
        $currentPlayer = $this->players[$this->turn];
        
        // Controleer of de kaart bestaat in de hand
        if (!isset($currentPlayer->cards[$cardId])) return;
        
        $chosenCard = $currentPlayer->cards[$cardId];
        
        // Pak de bovenste kaart van de aflegstapel
        $topCard = !empty($this->discardPile->cards) ? end($this->discardPile->cards) : null;

        // Regels: Dezelfde waarde, hetzelfde teken, of een Boer (J)
        if ($topCard === null || 
            $chosenCard->getWorth() == $topCard->getWorth() || 
            $chosenCard->getSign() == $topCard->getSign() || 
            $chosenCard->getWorth() == 'J') { 

            // Verwijder de kaart uit de hand van de speler
            $playedCard = $currentPlayer->removeCard($cardId); 
            
            // Speciale kaart effecten
            switch ($playedCard->getWorth()) {
                case '2':
                    // Volgende speler moet 2 kaarten pakken
                    $this->nextPlayer();
                    $this->players[$this->turn]->addCard($this->deck->draw());
                    $this->players[$this->turn]->addCard($this->deck->draw());
                    $this->nextPlayer();
                    break;

                case '8':
                    // 8 overslaan: we gaan twee stappen verder
                    $this->nextPlayer();
                    $this->nextPlayer();
                    break;

                case 'A':
                    // Aas: richting omdraaien
                    $this->leftToRight = !$this->leftToRight;
                    break;

                default:
                    // Gewone kaart: beurt naar de volgende
                    $this->nextPlayer();
                    break;
            } 

            // Leg de gespeelde kaart op de aflegstapel
            $this->discardPile->placeCard($playedCard);
        } 
    }
}
