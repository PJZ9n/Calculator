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
use pjz9n\resourcepacktools\generator\SimpleResourcePack;
use pjz9n\resourcepacktools\manifest\Version;
use pjz9n\resourcepacktools\ResourcePack;
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
        $path = $this->getDataFolder() . "resource.zip";
        $pack = new SimpleResourcePack($this, new Version(2, 0, 0));
        $pack->setPackIcon("images/icon.png");
        //Tip: ファイルの衝突を防止するために、固有のプラグイン名ディレクトリの下に配置します。
        //Tip: Place it under a directory with a unique plugin name to prevent file conflicts.
        $pack->addFile("images/calculator.png", "calculator/form/calculator.png");
        $pack->addFile("images/settings.png", "calculator/form/settings.png");
        $pack->generate($path);
        ResourcePack::register($path);
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
