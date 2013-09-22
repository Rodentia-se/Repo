<?php
	require_once('mobile.php');
	require_once(dirname(__FILE__) . '/simpletest/autorun.php');

	class TestOfMobile extends UnitTestCase {
		function __construct() {
			parent::__construct('Mobile test');
		}

		function testPlayer() {
			$oPlayer = new Player();
			$player = $oPlayer->getPlayer("111");
			$this->assertEqual("Helena", $player->firstName);
			$this->assertEqual("Walter", $player->lastName);
			$this->assertEqual("YOO", $player->initials);
			$this->assertNotEqual("KUK", $player->initials);
		}

		function testEntry() {
			$oEntry = new Entry();
			$e = $oEntry->fromPlayerAndDivision("111", "1");
			$this->assertEqual('723', $e->id);
			$this->assertEqual(true, $e->isValidEntryID('723'));
			$this->assertNotEqual(true, $e->isValidEntryID('9999'));

			$scores = $e->getScores('724', '192');
			$this->assertEqual(false, $scores);

			$scores = $e->getScores('723', '192');
			$this->assertEqual('FT', $scores[0]->gameAcronym);
			$this->assertEqual('FT', $scores[1]->gameAcronym);

			$scores = $e->getScores('723', '193');
			$this->assertEqual('SS', $scores[0]->gameAcronym);
			$this->assertEqual('SS', $scores[1]->gameAcronym);

			$scores = $e->getScores('723', '194');
			$this->assertEqual('HS2', $scores[0]->gameAcronym);
			$this->assertEqual('HS2', $scores[1]->gameAcronym);

			$scores = $e->getScores('723', '195');
			$this->assertEqual('MM', $scores[0]->gameAcronym);
			$this->assertEqual('MM', $scores[1]->gameAcronym);
		}

		function testGame() {
			$oGame = new Game();

			# Main
			$div = $oGame->getDivision('192');
			$this->assertEqual('1', $div);

			$div = $oGame->getDivision('193');
			$this->assertEqual('1', $div);

			$div = $oGame->getDivision('194');
			$this->assertEqual('1', $div);

			$div = $oGame->getDivision('195');
			$this->assertEqual('1', $div);

			# Classics
			$div = $oGame->getDivision('158');
			$this->assertEqual('2', $div);

			$div = $oGame->getDivision('159');
			$this->assertEqual('2', $div);

			$div = $oGame->getDivision('160');
			$this->assertEqual('2', $div);

			$div = $oGame->getDivision('161');
			$this->assertEqual('2', $div);
		}

		function testString() {
			$oString = new String();

			$this->assertEqual("123", $oString->stripNonNumericChars("abc123"));
			$this->assertEqual("123", $oString->stripNonNumericChars("abc123xyz"));
			$this->assertEqual("123", $oString->stripNonNumericChars("123xyz"));
		}

		function testPlayerLabel() {
			$oPlayerLabel = new PlayerLabel();
			$oPlayerLabel->FromPlayer('111');

			$this->assertEqual("Helena", $oPlayerLabel->firstName());
			$this->assertEqual("Walter", $oPlayerLabel->lastName());
			$this->assertEqual("YOO", $oPlayerLabel->initials());
			$this->assertEqual("Sweden", $oPlayerLabel->country());

			$this->assertEqual("Sweden", $oPlayerLabel->country());
			
			$this->assertTrue($oPlayerLabel->image());
		}

		function testGameLabel() {
			$oGameLabel = new GameLabel();
			$oGameLabel->FromGame('192');

			$this->assertEqual("Fish Tales", $oGameLabel->name());
			
			$this->assertTrue($oGameLabel->image());
		}

		function testValidator() {
			$oValidator = new Validator();

			$this->assertEqual(true, $oValidator->positiveInt(1));
			$this->assertEqual(false, $oValidator->positiveInt(-1));
			$this->assertEqual(false, $oValidator->positiveInt("integer"));
		}
	}
?>
