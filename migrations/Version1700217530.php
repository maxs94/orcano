<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version1700217530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'adds default code editor settings to user';
    }

    public function up(Schema $schema): void
    {
        $codeEditorConfig = [
            'keybinding' => User::DEFAULT_CODE_EDITOR_KEYBINDING,
        ];

        $this->addSql('UPDATE user SET code_editor_config = :codeEditorConfig', [
            'codeEditorConfig' => json_encode($codeEditorConfig),
        ]);

    }
}
