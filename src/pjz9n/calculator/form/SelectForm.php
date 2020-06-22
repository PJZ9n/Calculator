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

class SelectForm implements Form
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
        switch ($data) {
            case 0:
                $player->sendForm(new CalculationForm($this->plugin));
                return;
            case 1:
                $player->sendForm(new SettingsForm($this->plugin));
                return;
        }
    }

    public function jsonSerialize(): array
    {
        return [
            "type" => "form",
            "title" => (string)$this->plugin->getConfig()->get("title", "Unknown"),
            "content" => "選択してください！",
            "buttons" => [
                [
                    "text" => "計算",
                    "image" => [
                        "type" => "path",
                        "data" => "calculator/form/calculator",
                    ],
                ],
                [
                    "text" => "設定",
                    "image" => [
                        "type" => "path",
                        "data" => "calculator/form/settings",
                    ],
                ],
            ],
        ];
    }
}
