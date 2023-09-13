<?php

namespace Terpz710\BasicHealer\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use Terpz710\BasicHeal\Main;

class HealCommand extends Command {

    private $config;

    public function __construct(Config $config) {
        parent::__construct("heal", "Heal yourself");
        $this->config = $config;
        $this->setPermission("basichealer.heal");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
     
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used by players.");
            return true;
        }

        if (!$sender->hasPermission("basichealer.heal")) {
            $sender->sendMessage("You don't have permission to use this command.");
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
            $healMessage = $this->config->get("heal_message", "You have been healed!");
            if ($healMessage !== null) {
                $sender->sendMessage($healMessage);
            }
        }

        return true;
    }
}
