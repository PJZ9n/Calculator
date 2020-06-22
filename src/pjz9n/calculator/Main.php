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

use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Label;
use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pjz9n\resourcepacktools\FileResourcePack;
use pjz9n\resourcepacktools\ResourcePackVersion;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\form\FormValidationException;
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

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch ($command) {
            case "calculator":
                if (!($sender instanceof Player)) {
                    $sender->sendMessage(TextFormat::RED . "このコマンドはプレイヤーから実行してください。");
                    return true;
                }
                $sender->sendForm(new MenuForm(
                    (string)$this->getConfig()->get("title", "Unknown"),
                    "選択してください！",
                    [
                        new MenuOption(
                            "計算",
                            new FormIcon(
                                "calculator/form/calculator",
                                FormIcon::IMAGE_TYPE_PATH
                            ),
                        ),
                        new MenuOption(
                            "設定",
                            new FormIcon(
                                "calculator/form/settings",
                                FormIcon::IMAGE_TYPE_PATH
                            ),
                        ),
                    ],
                    function (Player $player, int $selectedOption): void {
                        switch ($selectedOption) {
                            case 0:
                                //計算
                                $player->sendForm(new CustomForm(
                                    (string)$this->getConfig()->get("title", "Unknown"),
                                    [
                                        new Input(
                                            "a",
                                            "A"
                                        ),
                                        new Label(
                                            "+",
                                            "+"
                                        ),
                                        new Input(
                                            "b",
                                            "B"
                                        ),
                                    ],
                                    function (Player $player, CustomFormResponse $customFormResponse): void {
                                        $a = $customFormResponse->getString("a");
                                        $b = $customFormResponse->getString("b");
                                        if (!is_numeric($a) ||
                                            !is_numeric($b)) {
                                            $player->sendMessage(TextFormat::RED . "入力された値が不正です。");
                                            return;
                                        }
                                        $a = (int)$a;
                                        $b = (int)$b;
                                        if ($a < PHP_INT_MIN ||
                                            $a > PHP_INT_MAX ||
                                            $b < PHP_INT_MIN ||
                                            $b > PHP_INT_MAX) {
                                            $player->sendMessage(TextFormat::RED . "入力された値の範囲が不正です。");
                                            return;
                                        }
                                        $player->sendMessage(
                                            TextFormat::AQUA . (string)$a .
                                            TextFormat::YELLOW . " + " .
                                            TextFormat::AQUA . (string)$b .
                                            TextFormat::YELLOW . " = " .
                                            TextFormat::GREEN . ($a + $b)
                                        );
                                    }
                                ));
                                break;
                            case 1:
                                //設定
                                $player->sendForm(new CustomForm(
                                    (string)$this->getConfig()->get("title", "Unknown"),
                                    [
                                        new Input(
                                            "title",
                                            "タイトル",
                                            "",
                                            (string)$this->getConfig()->get("title", "Unknown"),
                                        ),
                                    ],
                                    function (Player $player, CustomFormResponse $customFormResponse): void {
                                        $this->getConfig()->set("title", $customFormResponse->getString("title"));
                                        $player->sendMessage(
                                            TextFormat::AQUA . "タイトルを" .
                                            $customFormResponse->getString("title") .
                                            TextFormat::RESET . TextFormat::AQUA . "に設定しました！"
                                        );
                                    }
                                ));
                                break;
                            default:
                                throw new FormValidationException("unexcepted option " . $selectedOption);
                        }
                    }
                ));
                return true;
        }
        return false;
    }
}
