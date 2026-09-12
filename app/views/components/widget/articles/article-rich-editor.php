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
.article-editor-format-trigger,
.article-editor-format-option {
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

.article-editor-format-wrapper {
    position: relative;
}

.article-editor-format-trigger {
    min-width: 132px;
    padding: 0 34px 0 12px !important;
    outline: none;
    cursor: pointer;
    font-weight: 600;
    text-align: left;
    display: inline-flex;
    align-items: center;
    justify-content: flex-start;
}

.article-editor-format-trigger:focus {
    border-color: <?= colors('primary') ?>;
    box-shadow: 0 0 0 3px rgba(201, 44, 47, 0.12);
}

.article-editor-format-menu {
    position: absolute;
    top: calc(100% + 8px);
    left: 50%;
    z-index: 70;
    width: 176px;
    padding: 8px;
    border: 1px solid <?= colors('border_light') ?>;
    border-radius: 14px;
    background: <?= colors('white_neutral') ?>;
    box-shadow: 0 14px 34px rgba(17, 24, 39, 0.12);
    transform: translateX(-50%);
}

.article-editor-format-menu.hidden {
    display: none;
}

.article-editor-format-menu::before {
    content: "";
    position: absolute;
    top: -6px;
    left: 50%;
    width: 12px;
    height: 12px;
    border-top: 1px solid <?= colors('border_light') ?>;
    border-left: 1px solid <?= colors('border_light') ?>;
    background: <?= colors('white_neutral') ?>;
    transform: translateX(-50%) rotate(45deg);
}

.article-editor-format-option {
    position: relative;
    z-index: 1;
    width: 100%;
    justify-content: flex-start !important;
    padding: 0 12px !important;
    background: transparent !important;
    color: <?= colors('black_highlight') ?>;
    font-weight: 500 !important;
    text-align: left;
}

.article-editor-format-option + .article-editor-format-option {
    margin-top: 4px;
}

.article-editor-format-option:hover,
.article-editor-format-option.is-active {
    border-color: <?= colors('border_light') ?>;
    background: <?= colors('white_secondary') ?> !important;
    color: <?= colors('primary') ?>;
}

.article-editor-format-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    width: 14px;
    height: 14px;
    color: <?= colors('black_highlight') ?>;
    pointer-events: none;
    transform: translateY(-50%);
}

.article-editor-toolbar-card button:hover,
.article-editor-format-wrapper:hover .article-editor-format-trigger,
.article-editor-format-wrapper:hover .article-editor-format-icon {
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

.article-link-modal-input {
    width: 100%;
    height: 52px;
    padding: 12px 24px;
    border: 1px solid <?= colors('border_light') ?>;
    border-radius: 12px;
    background: <?= colors('white_neutral') ?>;
    color: <?= colors('black_soft') ?>;
    font-size: 16px;
    line-height: 28px;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.article-link-modal-input::placeholder {
    color: <?= colors('white_shadow') ?>;
}

.article-link-modal-input:focus {
    border-color: <?= colors('primary') ?>;
    box-shadow: 0 0 0 3px rgba(201, 44, 47, 0.12);
}
</style>

<?php if ($part === 'toolbar'): ?>
    <div class="flex justify-center mb-4">
        <div id="article-editor-toolbar" class="article-editor-toolbar-card rounded-xl px-3 py-2 flex items-center justify-center gap-2 flex-wrap">
        <div class="article-editor-format-wrapper">
            <button id="article-editor-block-trigger" class="article-editor-format-trigger" type="button" aria-haspopup="listbox" aria-expanded="false">
                <span id="article-editor-block-label">Paragraf</span>
            </button>
            <svg class="article-editor-format-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
            </svg>
            <div id="article-editor-block-menu" class="article-editor-format-menu hidden" role="listbox" aria-label="Format paragraf">
                <button type="button" class="article-editor-format-option is-active" data-block="P" role="option" aria-selected="true">Paragraf</button>
                <button type="button" class="article-editor-format-option" data-block="H2" role="option" aria-selected="false">Judul Besar</button>
                <button type="button" class="article-editor-format-option" data-block="H3" role="option" aria-selected="false">Sub Judul</button>
                <button type="button" class="article-editor-format-option" data-block="BLOCKQUOTE" role="option" aria-selected="false">Quote</button>
            </div>
        </div>

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

    <div id="article-link-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[520px] max-w-[calc(100vw-32px)] px-[24px] py-[20px]">
            <div class="flex items-start justify-between mb-[28px]">
                <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Tambah Link</h3>
                <button type="button" id="article-link-modal-close" class="text-black-highlight hover:text-black-soft transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-[20px]">
                <div>
                    <label for="article-link-text" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                        Teks Link
                    </label>
                    <input
                        type="text"
                        id="article-link-text"
                        class="article-link-modal-input"
                        placeholder="Contoh: Baca informasi lengkap"
                    >
                </div>

                <div>
                    <label for="article-link-url" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                        URL Link
                    </label>
                    <input
                        type="url"
                        id="article-link-url"
                        class="article-link-modal-input"
                        placeholder="https://example.com"
                    >
                </div>
            </div>

            <div class="pt-[28px] flex items-center justify-center gap-[12px]">
                <button
                    type="button"
                    id="article-link-modal-cancel"
                    class="btn-component inline-flex items-center justify-center transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light h-[52px] hover:bg-white-secondary cursor-pointer"
                >
                    Batalkan
                </button>
                <button
                    type="button"
                    id="article-link-modal-apply"
                    class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-6 rounded-xl bg-primary text-white text-base h-[52px] hover:opacity-90 active:scale-95 cursor-pointer"
                >
                    Terapkan Link
                </button>
            </div>
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
        const blockTrigger = document.getElementById('article-editor-block-trigger');
        const blockLabel = document.getElementById('article-editor-block-label');
        const blockMenu = document.getElementById('article-editor-block-menu');
        const linkModal = document.getElementById('article-link-modal');
        const linkTextInput = document.getElementById('article-link-text');
        const linkUrlInput = document.getElementById('article-link-url');
        const linkModalClose = document.getElementById('article-link-modal-close');
        const linkModalCancel = document.getElementById('article-link-modal-cancel');
        const linkModalApply = document.getElementById('article-link-modal-apply');
        let savedLinkRange = null;

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

        try {
            document.execCommand('styleWithCSS', false, false);
        } catch (error) {
            console.warn('styleWithCSS command is not supported:', error);
        }

        editor.addEventListener('input', syncArticleEditorContent);
        editor.addEventListener('blur', syncArticleEditorContent);
        editor.addEventListener('mouseup', saveEditorSelection);
        editor.addEventListener('keyup', saveEditorSelection);
        editor.addEventListener('click', function(e) {
            const link = e.target.closest('a');

            if (!link || !editor.contains(link)) {
                return;
            }

            if (e.ctrlKey || e.metaKey) {
                e.preventDefault();
                window.open(link.href, '_blank', 'noopener,noreferrer');
            }
        });

        function isRangeInsideEditor(range) {
            if (!range) {
                return false;
            }

            let node = range.commonAncestorContainer;
            if (node.nodeType === Node.TEXT_NODE) {
                node = node.parentNode;
            }

            return node === editor || editor.contains(node);
        }

        function saveEditorSelection() {
            const selection = window.getSelection();
            if (!selection || selection.rangeCount === 0) {
                return;
            }

            const range = selection.getRangeAt(0);
            if (isRangeInsideEditor(range)) {
                savedLinkRange = range.cloneRange();
            }
        }

        function restoreEditorSelection() {
            if (!savedLinkRange || !isRangeInsideEditor(savedLinkRange)) {
                editor.focus();
                return null;
            }

            editor.focus();
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(savedLinkRange);
            return savedLinkRange;
        }

        function closeLinkModal() {
            if (linkModal) {
                linkModal.classList.add('hidden');
            }

            document.body.style.overflow = '';
        }

        function openLinkModal() {
            if (!linkModal || !linkTextInput || !linkUrlInput) {
                return;
            }

            saveEditorSelection();

            const selectedText = savedLinkRange && !savedLinkRange.collapsed
                ? savedLinkRange.toString().trim()
                : '';

            linkTextInput.value = selectedText;
            linkUrlInput.value = '';
            linkModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(function() {
                if (selectedText) {
                    linkUrlInput.focus();
                } else {
                    linkTextInput.focus();
                }
            }, 50);
        }

        function normalizeLinkUrl(url) {
            url = url.trim();
            if (!url) {
                return '';
            }

            if (!/^(https?:\/\/|mailto:|tel:|\/|#)/i.test(url)) {
                return 'https://' + url;
            }

            return url;
        }

        function insertLinkFromModal() {
            if (!linkTextInput || !linkUrlInput) {
                return;
            }

            const url = normalizeLinkUrl(linkUrlInput.value);
            const text = linkTextInput.value.trim() || url;

            if (!url) {
                showToast('URL link tidak boleh kosong', 'error', 3000);
                linkUrlInput.focus();
                return;
            }

            if (!text) {
                showToast('Teks link tidak boleh kosong', 'error', 3000);
                linkTextInput.focus();
                return;
            }

            const range = restoreEditorSelection();
            const anchor = document.createElement('a');
            anchor.href = url;
            anchor.textContent = text;

            if (/^https?:\/\//i.test(url)) {
                anchor.target = '_blank';
                anchor.rel = 'noopener noreferrer';
            }

            if (range) {
                range.deleteContents();
                range.insertNode(anchor);
                range.setStartAfter(anchor);
                range.setEndAfter(anchor);

                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
            } else {
                editor.appendChild(anchor);
            }

            closeLinkModal();
            syncArticleEditorContent();
        }

        toolbar.querySelectorAll('button[data-command]').forEach(function(button) {
            button.addEventListener('mousedown', function(e) {
                e.preventDefault();
                saveEditorSelection();
            });

            button.addEventListener('click', function() {
                const command = button.dataset.command;

                if (command === 'createLink') {
                    openLinkModal();
                } else {
                    restoreEditorSelection();
                    document.execCommand(command, false, null);
                    saveEditorSelection();
                    syncArticleEditorContent();
                }
            });
        });

        if (linkModal) {
            linkModal.addEventListener('click', function(e) {
                if (e.target === linkModal) {
                    closeLinkModal();
                }
            });
        }

        [linkModalClose, linkModalCancel].forEach(function(button) {
            if (button) {
                button.addEventListener('click', closeLinkModal);
            }
        });

        if (linkModalApply) {
            linkModalApply.addEventListener('click', insertLinkFromModal);
        }

        if (linkUrlInput) {
            linkUrlInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    insertLinkFromModal();
                }
            });
        }

        function closeBlockMenu() {
            if (blockMenu) {
                blockMenu.classList.add('hidden');
            }

            if (blockTrigger) {
                blockTrigger.setAttribute('aria-expanded', 'false');
            }
        }

        if (blockTrigger && blockMenu) {
            blockTrigger.addEventListener('mousedown', function(e) {
                e.preventDefault();
                saveEditorSelection();
            });

            blockTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                const willOpen = blockMenu.classList.contains('hidden');
                blockMenu.classList.toggle('hidden', !willOpen);
                blockTrigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            });

            blockMenu.querySelectorAll('[data-block]').forEach(function(option) {
                option.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                });

                option.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const blockValue = option.dataset.block;
                    const blockText = option.textContent.trim();

                    restoreEditorSelection();
                    document.execCommand('formatBlock', false, blockValue);
                    saveEditorSelection();

                    if (blockLabel) {
                        blockLabel.textContent = blockText;
                    }

                    blockMenu.querySelectorAll('[data-block]').forEach(function(item) {
                        const isActive = item === option;
                        item.classList.toggle('is-active', isActive);
                        item.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    });

                    closeBlockMenu();
                    syncArticleEditorContent();
                });
            });

            document.addEventListener('click', function(e) {
                if (!toolbar.contains(e.target)) {
                    closeBlockMenu();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeBlockMenu();
                    closeLinkModal();
                }
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
