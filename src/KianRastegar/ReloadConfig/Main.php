<?php

namespace KianRastegar\ReloadConfig;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\Server;

class Main extends PluginBase
{
    public function onEnable(): void
    {
        $this->getLogger()->info("ServerReload Enabled!");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (strtolower($command->getName()) === "reload") {
            if (!$sender->hasPermission("server.reload")) {
                $sender->sendMessage("§cYou don't have permission to use this command.");
                return true;
            }

            $sender->sendMessage("§eReloading configs of all plugins...");
            $this->reloadAllConfigs($sender);
            return true;
        }

        return false;
    }

    private function reloadAllConfigs(CommandSender $sender): void
    {
        $pluginManager = Server::getInstance()->getPluginManager();
        $plugins = $pluginManager->getPlugins();

        $reloaded = 0;
        foreach ($plugins as $plugin) {
            if (method_exists($plugin, "reloadConfig")) {
                $plugin->reloadConfig();
                $reloaded++;
            }
        }

        $sender->sendMessage("§aReloaded config for §f$reloaded §aplugin(s).");
    }
}
