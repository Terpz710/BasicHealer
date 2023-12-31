<?php

declare(strict_types=1);

namespace Terpz710\BasicHealer\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;
use pocketmine\utils\Config;

use Terpz710\BasicHealer\Main as Plugin;

class HealCommand extends Command implements PluginOwned {

    /** @var Plugin */
    private $plugin;
    private $config;

    public function __construct(Plugin $plugin, Config $config) {
        parent::__construct("heal", "Heal yourself");
        $this->config = $config;
        $this->plugin = $plugin;
        $this->setPermission("basichealer.heal");
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {

        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used by players.");
            return true;
        }

        if (!$sender->hasPermission("basichealer.heal")) {
            $sender->sendMessage("§cYou don't have permission to use this command!");
            return true;
        }

        $health = $sender->getHealth();
        $maxHealth = $sender->getMaxHealth();

        if ($health < $maxHealth) {
            $sender->setHealth($maxHealth);
            $sender->sendTitle(
                $this->config->get("heal_title"),
                $this->config->get("heal_subtitle"),
                $this->config->get("heal_title_fade_in"),
                $this->config->get("heal_title_stay"),
                $this->config->get("heal_title_fade_out")
            );
            $healMessage = $this->config->get("heal_message", "§f(§a!§f) You have been §bhealed§f!");
            if ($healMessage !== null) {
                $sender->sendMessage($healMessage);
            }
        } else {
            $fullHealthMessage = $this->config->get("full_health_message", "§l§f(§c!§f)§r§f You already have full health!");
            $sender->sendMessage($fullHealthMessage);
        }

        return true;
    }
}
