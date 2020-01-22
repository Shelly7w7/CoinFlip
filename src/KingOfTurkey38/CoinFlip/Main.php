<?php

declare(strict_types=1);

namespace KingOfTurkey38\CoinFlip;

use KingOfTurkey38\CoinFlip\libs\muqsit\invmenu\InvMenuHandler;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use SQLite3;

class Main extends PluginBase implements Listener
{
    /** @var Main */
    private static $instance;
    /** @var SQLite3 */
    private $database;
    /** @var EconomyAPI */
    private $economy;

    public function onEnable(): void
    {
        $this->economy = $this->getServer()->getPluginManager()->getPlugin("EconomyAPi");
        if (!$this->economy) {
            $this->getLogger()->critical("You need EconomyAPI (https://poggit.pmmp.io/p/EconomyAPI/) to make CoinFlip work");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }
    }

    public function initDatabase(): void
    {
        $this->database = new SQLite3($this->getDataFolder() . "Database.db");
        $this->database->query("CREATE TABLE IF NOT EXISTS CoinFlips (uuid VARCHAR(40), username VARCHAR(40), type INTEGER, money INTEGER)");
    }

    /**
     * @return Main
     */
    public static function getInstance(): Main
    {
        return self::$instance;
    }

    /**
     * @return SQLite3
     */
    public function getDatabase(): SQLite3
    {
        return $this->database;
    }

    public function onDisable(): void
    {
    }
}
