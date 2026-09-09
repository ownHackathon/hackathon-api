<?php

use Deptrac\Deptrac\Contract\Config\Collector\BoolConfig;
use Deptrac\Deptrac\Contract\Config\Collector\DirectoryConfig;
use Deptrac\Deptrac\Contract\Config\DeptracConfig;
use Deptrac\Deptrac\Contract\Config\Layer;
use Deptrac\Deptrac\Contract\Config\Ruleset;

return static function (DeptracConfig $config): void {
  $config->paths('src');

  // 1. Core / Shared Kernel definieren
  $coreLayer = Layer::withName('Core')->collectors(
          DirectoryConfig::create('src/Core/.*')
  );

  // 2. Alle Module dynamisch ermitteln (sucht rekursiv nach "Api"-Ordnern)
  $modules = [];

  $iterator = new RecursiveIteratorIterator(
          new RecursiveDirectoryIterator(__DIR__ . '/src', FilesystemIterator::SKIP_DOTS),
          RecursiveIteratorIterator::SELF_FIRST
  );

  foreach ($iterator as $item) {
    if ($item->isDir() && $item->getFilename() === 'Api') {
      $moduleDir = dirname($item->getPathname());
      $relativePath = ltrim(str_replace(__DIR__ . '/src', '', $moduleDir), '/\\');

      if (str_starts_with($relativePath, 'Core')) {
        continue;
      }

      $layerIdentifier = str_replace(['/', '\\'], '_', $relativePath);
      $modules[$relativePath] = $layerIdentifier;
    }
  }

  $allLayers = [$coreLayer];
  $apiLayers = [];
  $moduleLayerMap = [];

  // 3. Schichten pro Modul erzeugen
  foreach ($modules as $relPath => $identifier) {
    // Api-Layer (matcht Api und alle darin liegenden Unterordner)
    $apiLayer = Layer::withName($identifier . '_Api')->collectors(
            DirectoryConfig::create("src/{$relPath}/Api/.*")
    );

    // Internal-Layer (matcht alles im Modul, SCHLIESST aber den Api-Ordner aus)
    $internalLayer = Layer::withName($identifier . '_Internal')->collectors(
            BoolConfig::create()
                    ->must(DirectoryConfig::create("src/{$relPath}/.*"))
                    ->mustNot(DirectoryConfig::create("src/{$relPath}/Api/.*"))
    );

    $allLayers[] = $apiLayer;
    $allLayers[] = $internalLayer;

    $apiLayers[] = $apiLayer;
    $moduleLayerMap[$identifier] = [
            'api' => $apiLayer,
            'internal' => $internalLayer,
    ];
  }

  $config->layers(...$allLayers);

  // 4. Regeln festlegen
  $rulesets = [];

  foreach ($moduleLayerMap as $identifier => $layers) {
    // Interner Code darf Core, die eigene API und alle Fremd-APIs nutzen
    $rulesets[] = Ruleset::forLayer($layers['internal'])->accesses(
            $coreLayer,
            ...$apiLayers
    );

    // Public APIs dürfen Core und alle anderen APIs nutzen
    $rulesets[] = Ruleset::forLayer($layers['api'])->accesses($coreLayer, ...$apiLayers);
  }

  // Core darf von nichts abhängen
  $rulesets[] = Ruleset::forLayer($coreLayer);

  $config->rulesets(...$rulesets);
};
