<?php

declare(strict_types=1);

namespace Terpz710\BasicHealer\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;

use Terpz710\BasicHealer\Main;

class FeedCommand extends Command {

    private $config;

    public function __construct(Config $config) {
        parent::__construct("feed", "Feed yourself");
        $this->config = $config;
        $this->setPermission("basichealer.feed");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used by players.");
            return true;
        }

        if (!$sender->hasPermission("basichealer.feed")) {
            $sender->sendMessage("§cYou don't have permission to use this command!");
            return true;
        }

        $hungerManager = $sender->getHungerManager();
        $foodLevel = $hungerManager->getFood();

        if ($foodLevel < 20) {
            $hungerManager->setFood(20);
            $hungerManager->setSaturation(20);
            $sender->sendTitle(
                $this->config->get("feed_title"),
                $this->config->get("feed_subtitle"),
                $this->config->get("feed_title_fade_in"),
                $this->config->get("feed_title_stay"),
                $this->config->get("feed_title_fade_out")
            );
            $feedMessage = $this->config->get("feed_message", "§l§f(§a!§f)§r§f You have been §efed§f!");
            if ($feedMessage !== null) {
                $sender->sendMessage($feedMessage);
            }
        }

        return true;
    }
}
