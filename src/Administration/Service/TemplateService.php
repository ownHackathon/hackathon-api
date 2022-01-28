<?php declare(strict_types=1);

namespace Administration\Service;

use Mezzio\Exception\InvalidArgumentException;

class TemplateService
{
    /** @param array<string> $settings */
    public function __construct(
        private array $settings
    ) {
    }

    public function getTemplateContent(string $section = 'app', string $file = 'index'): string
    {
        $templateFile = $this->settings['paths'][$section] . '/' . $file . '.phtml';

        if (!is_file($templateFile)) {
            throw new InvalidArgumentException('Templatefile not found', 400);
        }

        ob_start();

        require $templateFile;

        $templateContent = ob_get_clean();

        return $templateContent;
    }
}
