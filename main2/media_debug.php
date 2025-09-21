<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Media Debug Panel for catalog.element (main2)
 * Показывает ключевые данные про обложку и видео.
 *
 * Как работает:
 *   - Перед подключением этого файла в template.php,
 *     подготовьте массив $arMediaDbg с данными (или дайте ему собраться тут).
 *
 * ВАЖНО: это отладка. Удалите include или установите $arMediaDbg['enabled']=false,
 *        когда всё проверите.
 */

// Собираем данные, если не передали заранее
if (!isset($arMediaDbg) || !is_array($arMediaDbg)) {
    $arMediaDbg = [];
}

$arMediaDbg['enabled'] = $arMediaDbg['enabled'] ?? true;

// Достаём значения из $arResult
$coverId = $arResult['PROPERTIES']['PREVIEW_VIDEO_IMG']['VALUE'] ?? null;
$coverPath = $coverId ? CFile::GetPath($coverId) : '';
$showVideoFirst = $arResult['PROPERTIES']['SHOW_VIDEO_FIRST']['VALUE'] ?? null;
$rutube = $arResult['PROPERTIES']['VIDEO_RUTUBE']['VALUE'] ?? null;

// Попробуем дернуть локальный файл видео, если у вас такое свойство используется
$videoFile = '';
if (!empty($arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['path'])) {
    $videoFile = $arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['path'];
} elseif (!empty($arResult['PROPERTIES']['VIDEO_FILE']['VALUE']['path'])) {
    $videoFile = $arResult['PROPERTIES']['VIDEO_FILE']['VALUE']['path'];
}

$arMediaDbg['product_id']      = $arResult['ID'] ?? null;
$arMediaDbg['cover_id']        = $coverId;
$arMediaDbg['cover_path']      = $coverPath;
$arMediaDbg['has_cover']       = (bool)$coverPath;
$arMediaDbg['show_video_first']= $showVideoFirst;
$arMediaDbg['rutube_url']      = $rutube;
$arMediaDbg['video_file']      = $videoFile;

// Мини-панель
if (!empty($arMediaDbg['enabled'])): ?>
  <style>
    .media-dbg-panel{
      position:fixed; right:12px; bottom:12px; z-index:99999;
      max-width:420px; font:12px/1.4 system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      background:#0b1220; color:#d3e1ff; border:1px solid #2c3a5c; border-radius:10px;
      box-shadow:0 10px 30px rgba(0,0,0,.35); padding:10px 12px; opacity:.92;
    }
    .media-dbg-panel summary{cursor:pointer; font-weight:600}
    .media-dbg-panel code, .media-dbg-panel pre{
      white-space:pre-wrap; word-break:break-word; color:#bfe0ff;
    }
    .media-dbg-badge{display:inline-block; padding:1px 6px; border-radius:999px; background:#203157; color:#bfe0ff; margin-left:6px; font-size:11px}
  </style>
  <details class="media-dbg-panel" open>
    <summary>MEDIA DEBUG<span class="media-dbg-badge">product <?=$arMediaDbg['product_id']?></span></summary>
    <div>
      <div><b>PREVIEW_VIDEO_IMG (ID):</b> <?=htmlspecialcharsbx((string)$arMediaDbg['cover_id'])?></div>
      <div><b>PREVIEW_VIDEO_IMG (SRC):</b> <code><?=htmlspecialcharsbx((string)$arMediaDbg['cover_path'])?></code></div>
      <div><b>Has cover:</b> <?= $arMediaDbg['has_cover'] ? 'YES' : 'NO' ?></div>
      <div><b>SHOW_VIDEO_FIRST:</b> <code><?=htmlspecialcharsbx((string)$arMediaDbg['show_video_first'])?></code></div>
      <div><b>VIDEO_RUTUBE:</b> <code><?=htmlspecialcharsbx((string)$arMediaDbg['rutube_url'])?></code></div>
      <div><b>VIDEO_FILE:</b> <code><?=htmlspecialcharsbx((string)$arMediaDbg['video_file'])?></code></div>
      <?php if ($arMediaDbg['has_cover']): ?>
        <div style="margin-top:8px"><img src="<?=htmlspecialcharsbx((string)$arMediaDbg['cover_path'])?>" alt="cover preview" style="max-width:100%;border-radius:6px;border:1px solid #2c3a5c"></div>
      <?php endif; ?>
    </div>
  </details>
  <script>
    try {
      console.groupCollapsed('PRODUCT_MEDIA_DEBUG');
      console.log(<?=json_encode($arMediaDbg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)?>);
      console.groupEnd();
    } catch(e){}
  </script>
<?php endif; ?>
