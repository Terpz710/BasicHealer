<?php

declare(strict_types=1);

namespace Terpz710\BasicHealer\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;
use pocketmine\utils\Config;

use Terpz710\BasicHealer\Main as Plugin;

class FeedCommand extends Command implements PluginOwned {

    /** @var Plugin */
    private $plugin;
    private $config;

    public function __construct(Plugin $plugin, Config $config) {
        parent::__construct("feed", "Feed yourself");
        $this->config = $config;
        $this->plugin = $plugin;
        $this->setPermission("basichealer.feed");
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
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
            $feedMessage = $this->config->get("feed_message", "§f(§a!§f) You have been §bfed§f!");
            if ($feedMessage !== null) {
                $sender->sendMessage($feedMessage);
            }
        } else {
            $fullHungerMessage = $this->config->get("full_hunger_message", "§l§f(§c!§f)§r§f You are not hungry!");
            $sender->sendMessage($fullHungerMessage);
        }

        return true;
    }
}
