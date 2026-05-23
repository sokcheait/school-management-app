<?php

namespace Modules\Student\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class StudentPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Student';
    }

    public function getId(): string
    {
        return 'student';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
