import {
    MDXEditor,
    headingsPlugin,
    listsPlugin,
    quotePlugin,
    thematicBreakPlugin,
    markdownShortcutPlugin,
    UndoRedo,
    BoldItalicUnderlineToggles,
    toolbarPlugin,
    BlockTypeSelect,
    linkPlugin,
    linkDialogPlugin,
    InsertTable,
    codeMirrorPlugin,
    ConditionalContents,
    ChangeCodeMirrorLanguage,
    ShowSandpackInfo,
    InsertCodeBlock,
    InsertSandpack,
    type MDXEditorMethods,
    type MDXEditorProps
} from '@mdxeditor/editor';
import '@mdxeditor/editor/style.css'
import type { ForwardedRef } from 'react';

export default function TextEditor ({
    editorRef,
    ...props
}: {
    editorRef: ForwardedRef<MDXEditorMethods> | null
} & MDXEditorProps
) {
    return (
        <div className="w-full h-full">
            <MDXEditor
                {...props}
                ref={editorRef}
                plugins={[
                    headingsPlugin(),
                    listsPlugin(),
                    quotePlugin(),
                    thematicBreakPlugin(),
                    markdownShortcutPlugin(),
                    linkPlugin(),
                    linkDialogPlugin(),
                    codeMirrorPlugin(),
                    toolbarPlugin({
                        toolbarContents: () => (
                            <>
                                <UndoRedo />
                                <BoldItalicUnderlineToggles />
                                <BlockTypeSelect />
                                <InsertTable />
                                <ConditionalContents
                                    options={[
                                        { when: (editor) => editor?.editorType === 'codeblock', contents: () => <ChangeCodeMirrorLanguage /> },
                                        { when: (editor) => editor?.editorType === 'sandpack', contents: () => <ShowSandpackInfo /> },
                                        {
                                            fallback: () => (
                                                <>
                                                <InsertCodeBlock />
                                                <InsertSandpack />
                                                </>
                                            )
                                        }
                                    ]}
                                />
                            </>
                        )
                    })
                ]}
            />
        </div>
    )
}
