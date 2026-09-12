<?php
/**
 * Native rich text editor for article create/edit pages.
 *
 * Keeps the existing article layout, adding only a thin toolbar above the
 * writing area. The hidden textarea preserves the existing save flow.
 */
$initialContent = $content ?? '';
$editorContent = render_article_content($initialContent);
$part = $part ?? 'editor';
?>

<style>
.article-editor-toolbar-card {
    width: fit-content;
    max-width: 100%;
    border: 1px solid <?= colors('border_light') ?>;
    background: <?= colors('white_secondary') ?>;
}

.article-editor-toolbar-card button,
.article-editor-toolbar-card select {
    height: 34px;
    border: 1px solid transparent;
    border-radius: 10px;
    background: <?= colors('white_neutral') ?>;
    color: <?= colors('black_highlight') ?>;
    font-size: 13px;
    line-height: 1;
    transition: all 0.15s ease;
}

.article-editor-toolbar-card button {
    min-width: 34px;
    padding: 0 10px;
    font-weight: 700;
}

.article-editor-toolbar-card select {
    padding: 0 10px;
    outline: none;
}

.article-editor-toolbar-card button:hover,
.article-editor-toolbar-card select:hover {
    border-color: <?= colors('border_soft') ?>;
    color: <?= colors('primary') ?>;
}

.article-rich-editor {
    min-height: 100%;
}

.article-rich-editor:empty::before {
    content: attr(data-placeholder);
    color: <?= colors('white_soft') ?>;
}

.article-rich-editor p,
.article-rich-editor ul,
.article-rich-editor ol,
.article-rich-editor blockquote,
.article-rich-editor h2,
.article-rich-editor h3,
.article-rich-editor h4 {
    margin-bottom: 14px;
}

.article-rich-editor h2 {
    font-size: 26px;
    line-height: 1.35;
    font-weight: 700;
    color: <?= colors('black_soft') ?>;
}

.article-rich-editor h3 {
    font-size: 22px;
    line-height: 1.4;
    font-weight: 700;
    color: <?= colors('black_soft') ?>;
}

.article-rich-editor h4 {
    font-size: 18px;
    line-height: 1.45;
    font-weight: 700;
    color: <?= colors('black_soft') ?>;
}

.article-rich-editor ul {
    list-style: disc;
    padding-left: 26px;
}

.article-rich-editor ol {
    list-style: decimal;
    padding-left: 26px;
}

.article-rich-editor blockquote {
    border-left: 4px solid <?= colors('primary') ?>;
    padding-left: 16px;
    color: <?= colors('black_highlight') ?>;
}

.article-rich-editor a {
    color: <?= colors('primary') ?>;
    text-decoration: underline;
}
</style>

<?php if ($part === 'toolbar'): ?>
    <div class="flex justify-center mb-4">
        <div id="article-editor-toolbar" class="article-editor-toolbar-card rounded-xl px-3 py-2 flex items-center justify-center gap-2 flex-wrap">
        <select id="article-editor-block" title="Format paragraf">
            <option value="P">Paragraf</option>
            <option value="H2">Judul Besar</option>
            <option value="H3">Sub Judul</option>
            <option value="BLOCKQUOTE">Quote</option>
        </select>

        <span class="h-[22px] w-px bg-border-light"></span>

        <button type="button" data-command="bold" title="Bold">B</button>
        <button type="button" data-command="italic" title="Italic"><em>I</em></button>
        <button type="button" data-command="underline" title="Underline"><u>U</u></button>
        <button type="button" data-command="strikeThrough" title="Coret"><s>S</s></button>

        <span class="h-[22px] w-px bg-border-light"></span>

        <button type="button" data-command="insertUnorderedList" title="Bullet list">• List</button>
        <button type="button" data-command="insertOrderedList" title="Numbered list">1. List</button>

        <span class="h-[22px] w-px bg-border-light"></span>

        <button type="button" data-command="createLink" title="Tambah link">Link</button>
        <button type="button" data-command="removeFormat" title="Hapus format">Clear</button>
        </div>
    </div>
    <?php return; ?>
<?php endif; ?>

<textarea id="article-content" class="hidden"><?= e($editorContent) ?></textarea>

<div class="flex-1 overflow-y-auto mb-5">
    <div
        id="article-editor"
        contenteditable="true"
        data-placeholder="Mulai menulis artikel..."
        class="article-rich-editor w-full h-full font-normal text-[16px] leading-[28px] text-black-soft bg-transparent border-none outline-none focus:ring-0"
    ><?= $editorContent ?></div>
</div>

<script>
(function() {
    function initArticleRichEditor() {
        const editor = document.getElementById('article-editor');
        const textarea = document.getElementById('article-content');
        const toolbar = document.getElementById('article-editor-toolbar');
        const blockSelect = document.getElementById('article-editor-block');

        if (!editor || !textarea || !toolbar || editor.dataset.initialized === 'true') {
            return;
        }

        editor.dataset.initialized = 'true';

        function normalizeEmptyEditor() {
            const plainText = editor.textContent.replace(/\u00a0/g, ' ').trim();
            const html = editor.innerHTML.trim().toLowerCase();

            if (!plainText && (html === '<br>' || html === '<p><br></p>')) {
                editor.innerHTML = '';
            }
        }

        function syncArticleEditorContent() {
            normalizeEmptyEditor();
            textarea.value = editor.innerHTML.trim();
            textarea.dispatchEvent(new Event('input', { bubbles: true }));
            return textarea.value;
        }

        window.syncArticleEditorContent = syncArticleEditorContent;
        window.getArticleEditorContent = syncArticleEditorContent;

        editor.addEventListener('input', syncArticleEditorContent);
        editor.addEventListener('blur', syncArticleEditorContent);

        toolbar.querySelectorAll('button[data-command]').forEach(function(button) {
            button.addEventListener('click', function() {
                const command = button.dataset.command;
                editor.focus();

                if (command === 'createLink') {
                    const selectedText = window.getSelection().toString();
                    const inputUrl = window.prompt('Masukkan URL link', 'https://');

                    if (!inputUrl || inputUrl === 'https://') {
                        return;
                    }

                    let url = inputUrl.trim();
                    if (!/^(https?:\/\/|mailto:|tel:|\/|#)/i.test(url)) {
                        url = 'https://' + url;
                    }

                    document.execCommand('createLink', false, url);

                    if (!selectedText) {
                        document.execCommand('insertText', false, url);
                    }
                } else {
                    document.execCommand(command, false, null);
                }

                syncArticleEditorContent();
            });
        });

        if (blockSelect) {
            blockSelect.addEventListener('change', function() {
                editor.focus();
                document.execCommand('formatBlock', false, blockSelect.value);
                syncArticleEditorContent();
                blockSelect.value = 'P';
            });
        }

        syncArticleEditorContent();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initArticleRichEditor);
    } else {
        initArticleRichEditor();
    }
})();
</script>
