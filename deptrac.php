<?php

use Deptrac\Deptrac\Contract\Config\Collector\DirectoryConfig;
use Deptrac\Deptrac\Contract\Config\DeptracConfig;
use Deptrac\Deptrac\Contract\Config\Layer;
use Deptrac\Deptrac\Contract\Config\Ruleset;

return static function (DeptracConfig $config): void {
  // Hier lag der Fehler: String statt Array übergeben
  $config->paths('src');

  // 1. Core / Shared Kernel Layer definieren
  $coreLayer = Layer::withName('Core')->collectors(
          DirectoryConfig::create('src/Core/.*')
  );

  // Alle Modulordner unter src/ ermitteln (außer Core)
  $modules = [];
  foreach (glob(__DIR__ . '/src/*', GLOB_ONLYDIR) as $dir) {
    $moduleName = basename($dir);
    if ($moduleName !== 'Core') {
      $modules[] = $moduleName;
    }
  }

  $allLayers = [$coreLayer];
  $apiLayers = [];
  $moduleLayerMap = [];

  // 2. Pro Modul Schichten (Api + Internal) erstellen
  foreach ($modules as $module) {
    $apiLayer = Layer::withName($module . 'Api')->collectors(
            DirectoryConfig::create("src/{$module}/Api/.*")
    );
    $internalLayer = Layer::withName($module . 'Internal')->collectors(
            DirectoryConfig::create("src/{$module}/.*")
    );

    $allLayers[] = $apiLayer;
    $allLayers[] = $internalLayer;

    $apiLayers[] = $apiLayer;
    $moduleLayerMap[$module] = [
            'api' => $apiLayer,
            'internal' => $internalLayer,
    ];
  }

  $config->layers(...$allLayers);

  // 3. Regeln festlegen
  $rulesets = [];

  foreach ($moduleLayerMap as $module => $layers) {
    // Interner Code darf:
    // - Core aufrufen
    // - die eigene Public API aufrufen
    // - JEDE fremde Public API aufrufen
    $rulesets[] = Ruleset::forLayer($layers['internal'])->accesses(
            $coreLayer,
            ...$apiLayers
    );

    // Public APIs dürfen Core nutzen
    $rulesets[] = Ruleset::forLayer($layers['api'])->accesses($coreLayer);
  }

  // Core darf von keinem Modul abhängen
  $rulesets[] = Ruleset::forLayer($coreLayer);

  $config->rulesets(...$rulesets);
};
