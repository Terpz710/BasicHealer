<?php

declare(strict_types=1);

namespace Terpz710\BasicHealer\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\Plugin;
use pocketmine\player\Player;
use pocketmine\utils\Config;

use Terpz710\BasicHealer\Main;

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
            $sender->sendMessage($this->config->get("not_ingame_health"));
            return true;
        }

        if (!$sender->hasPermission("basichealer.heal")) {
            $sender->sendMessage($this->config->get("no_permission_health"));
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
            $healMessage = $this->config->get("heal_message");
            if ($healMessage !== null) {
                $sender->sendMessage($healMessage);
            }
        } else {
            $fullHealthMessage = $this->config->get("full_health_message");
            $sender->sendMessage($fullHealthMessage);
        }

        return true;
    }
}
