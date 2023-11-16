<?php

declare(strict_types=1);

namespace Terpz710\BasicHealer;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Terpz710\BasicHealer\Commands\FeedCommand;
use Terpz710\BasicHealer\Commands\HealCommand;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->saveResource("heal&feed.yml");
        $this->config = new Config($this->getDataFolder() . "heal&feed.yml", Config::YAML);

        $this->getServer()->getCommandMap()->register("feed", new FeedCommand($this));
        $this->getServer()->getCommandMap()->register("heal", new HealCommand($this));
    }
}
