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

 // In Gameleader.php - Pas de playCard functie aan
private function playCard($cardId) {
    $currentPlayer = $this->players[$this->turn];
    if (!isset($currentPlayer->cards[$cardId])) return;
    
    $chosenCard = $currentPlayer->cards[$cardId];
    $topCard = end($this->discardPile->cards);

    // Validatie: mag deze kaart gespeeld worden?
    if ($topCard === null || 
        $chosenCard->getWorth() == $topCard->getWorth() || 
        $chosenCard->getSign() == $topCard->getSign() || 
        $chosenCard->getWorth() == 'J') { 

        $playedCard = $currentPlayer->removeCard($cardId); 
        $this->discardPile->placeCard($playedCard);

        // Check voor winnaar
        if (count($currentPlayer->cards) === 0) {
            echo "<h1>Speler " . ($this->turn + 1) . " heeft gewonnen!</h1>";
            session_destroy();
            exit;
        }

        // Effecten
        switch ($playedCard->getWorth()) {
            case '2':
                $this->nextPlayer();
                for($i=0; $i<2; $i++) $this->players[$this->turn]->addCard($this->deck->draw());
                break;
            case '8':
                $this->nextPlayer(); // Sla een beurt over
                break;
            case 'A':
                $this->leftToRight = !$this->leftToRight;
                break;
            // Bij een 'J' (Boer) mag je altijd, maar de beurt gaat gewoon door

            case '7':
            // "Zeven blijft kleven": we roepen nextPlayer() NIET aan.
            break;

            default:
                // Gewone kaart, geen extra actie nodig
                break;
 
        }
        
        $this->nextPlayer();
        } 
    }
   }
