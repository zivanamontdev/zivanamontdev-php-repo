<?php
/**
 * Tabs Component
 *
 * @param array $tabs - Array of tab labels
 * @param int $active - Index of active tab (default: 0)
 */
$tabs = $tabs ?? [];
$active = $active ?? 0;
$tabBg = colors('tab_bg');
$tabText = colors('tab_text');
$borderColor = colors('border_light');
$selectedBg = colors('white_neutral');
$selectedShadow = '0px 2px 5.5px 0px rgba(0,0,0,0.07)';
?>
<div class="flex gap-[4px]" style="background:<?= $tabBg ?>; border:1px solid <?= $borderColor ?>; border-radius:12px; padding:8px; width:fit-content; height:fit-content;">
    <?php foreach ($tabs as $i => $label): ?>
        <?php $isActive = ($i === $active); ?>
        <button type="button"
            class="tab-button transition-all"
            data-tab-index="<?= $i ?>"
            style="
                width:148px;
                border-radius:8px;
                padding:8px 4px;
                background:<?= $isActive ? $selectedBg : 'transparent' ?>;
                box-shadow:<?= $isActive ? $selectedShadow : 'none' ?>;
                color:<?= $tabText ?>;
                font-weight:400;
                font-size:12px;
                line-height:20px;
                border:none;
                cursor:pointer;
            "
        ><?= e($label) ?></button>
    <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Reset all buttons
            tabButtons.forEach(btn => {
                btn.style.background = 'transparent';
                btn.style.boxShadow = 'none';
            });
            
            // Set active button
            this.style.background = '<?= $selectedBg ?>';
            this.style.boxShadow = '<?= $selectedShadow ?>';
        });
    });
});
</script>
