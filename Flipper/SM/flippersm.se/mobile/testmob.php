<?php
	require_once('mobile.php');
	require_once(dirname(__FILE__) . '/simpletest/autorun.php');

	class TestOfMobile extends UnitTestCase {
		function __construct() {
			parent::__construct('Mobile test');
		}

		function testPlayer() {
			$oPlayer = new MPlayer();
			$player = $oPlayer->getPlayer("28");
			$this->assertEqual("Andreas", $player->firstName);
			$this->assertEqual("Thorsen", $player->lastName);
			$this->assertEqual("BWK", $player->initials);
			$this->assertNotEqual("KUK", $player->initials);
		}

		function testEntry() {
			$oEntry = new MEntry();
			$e = $oEntry->fromPlayerAndDivision("28", "1");
			$this->assertEqual('418', $e->id);
			$this->assertEqual(true, $oEntry->isValidEntryID('418'));
			$this->assertNotEqual(true, $oEntry->isValidEntryID('9999'));

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
			$oGame = new MGame();

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
			$oString = new MString();

			$this->assertEqual("123", $oString->stripNonNumericChars("abc123"));
			$this->assertEqual("123", $oString->stripNonNumericChars("abc123xyz"));
			$this->assertEqual("123", $oString->stripNonNumericChars("123xyz"));
		}

		function testPlayerLabel() {
			$oPlayerLabel = new MPlayerLabel();
			$oPlayerLabel->FromPlayer('111');

			$this->assertEqual("Helena", $oPlayerLabel->firstName());
			$this->assertEqual("Walter", $oPlayerLabel->lastName());
			$this->assertEqual("YOO", $oPlayerLabel->initials());
			$this->assertEqual("Sweden", $oPlayerLabel->country());

			$this->assertEqual("Sweden", $oPlayerLabel->country());
			
			$this->assertTrue($oPlayerLabel->image());
		}

		function testGameLabel() {
			$oGameLabel = new MGameLabel();
			$oGameLabel->FromGame('192');

			$this->assertEqual("Fish Tales", $oGameLabel->name());
			
			$this->assertTrue($oGameLabel->image());
		}

		function testValidator() {
			$oValidator = new MValidator();

			$this->assertEqual(true, $oValidator->positiveInt(1));
			$this->assertEqual(false, $oValidator->positiveInt(-1));
			$this->assertEqual(false, $oValidator->positiveInt("integer"));
		}
	}
?>
