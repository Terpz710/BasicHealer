<?php

namespace Terpz710\BasicHealer;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Terpz710\Commands\FeedCommand;
use Terpz710\Commands\HealCommand;

class Main extends PluginBase {

    /** @var Config */
    private $config;

    public function onEnable(): void {
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $this->getServer()->getCommandMap()->register("feed", new FeedCommand($this->config));
        $this->getServer()->getCommandMap()->register("heal", new HealCommand($this->config));
    }
}
