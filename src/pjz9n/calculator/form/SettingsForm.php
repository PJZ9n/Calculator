<?php

/**
 * Copyright (c) 2020 PJZ9n.
 *
 * This file is part of Calculator.
 *
 * Calculator is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Calculator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Calculator. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace pjz9n\calculator\form;

use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class SettingsForm implements Form
{
    /** @var Plugin */
    private $plugin;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            return;
        }
        $title = $data[0];
        $this->plugin->getConfig()->set("title", $title);
        $player->sendMessage(
            TextFormat::AQUA . "タイトルを" .
            $title .
            TextFormat::RESET . TextFormat::AQUA . "に設定しました！"
        );
    }

    public function jsonSerialize(): array
    {
        return [
            "type" => "custom_form",
            "title" => (string)$this->plugin->getConfig()->get("title", "Unknown"),
            "content" => [
                [
                    "type" => "input",
                    "text" => "タイトル",
                    "default" => (string)$this->plugin->getConfig()->get("title", "Unknown"),
                ],
            ],
        ];
    }
}
