<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject\Page;

use App\DataObject\DataObjectInterface;

class PageMessageDataObject implements DataObjectInterface
{
    public const TYPE_PRIMARY = 'primary';
    public const TYPE_SUCCESS = 'success';
    public const TYPE_WARNING = 'warning';
    public const TYPE_DANGER = 'danger';
    public const TYPE_DARK = 'dark';
    public const TYPE_SECONDARY = 'secondary';
    public const TYPE_LIGHT = 'light';
    public const TYPE_INFO = 'info';

    public const VALID_MESSAGE_TYPES = [
        self::TYPE_PRIMARY,
        self::TYPE_SUCCESS,
        self::TYPE_WARNING,
        self::TYPE_DANGER,
        self::TYPE_DARK,
        self::TYPE_SECONDARY,
        self::TYPE_LIGHT,
        self::TYPE_INFO,
    ];

    public function __construct(private string $text = '', private string $type = self::TYPE_PRIMARY) {}

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        if (!in_array($type, self::VALID_MESSAGE_TYPES)) {
            throw new \Exception('Invalid message type, valid types are: ' . implode(', ', self::VALID_MESSAGE_TYPES) . '');
        }

        $this->type = $type;

        return $this;
    }
}
