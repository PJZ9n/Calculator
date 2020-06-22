<?php

/**
 * Copyright (c) 2020 PJZ9n.
 *
 * This file is part of PluginTemplate.
 *
 * PluginTemplate is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PluginTemplate is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PluginTemplate. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace pjz9n\calculator;

use pjz9n\calculator\form\SelectForm;
use pjz9n\resourcepacktools\FileResourcePack;
use pjz9n\resourcepacktools\ResourcePackVersion;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use ReflectionException;

class Main extends PluginBase
{
    /**
     * @throws ReflectionException
     */
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $pack = new FileResourcePack($this->getDataFolder() . "resource.zip", $this, new ResourcePackVersion(1, 0, 0));
        $pack->setIcon("images/icon.png");
        $pack->addFile("images/calculator.png", "form/calculator.png");
        $pack->addFile("images/settings.png", "form/settings.png");
        $pack->registerResourcePack();
    }

    public function onDisable(): void
    {
        $this->saveConfig();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch ($command) {
            case "calculator":
                if (!($sender instanceof Player)) {
                    $sender->sendMessage(TextFormat::RED . "このコマンドはプレイヤーから実行してください。");
                    return true;
                }
                $sender->sendForm(new SelectForm($this));
                return true;
        }
        return false;
    }
}
