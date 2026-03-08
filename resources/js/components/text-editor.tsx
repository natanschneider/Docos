"use client";

import { useAppearance } from "@/hooks/use-appearance";
import {
	AdmonitionDirectiveDescriptor,
	BlockTypeSelect,
	BoldItalicUnderlineToggles,
	codeBlockPlugin,
	codeMirrorPlugin,
	CodeToggle,
	diffSourcePlugin,
	directivesPlugin,
	frontmatterPlugin,
	headingsPlugin,
	imagePlugin,
	InsertCodeBlock,
	InsertTable,
	InsertThematicBreak,
	linkDialogPlugin,
	linkPlugin,
	listsPlugin,
	ListsToggle,
	markdownShortcutPlugin,
	MDXEditor,
	MDXEditorMethods,
	MDXEditorProps,
	quotePlugin,
	SandpackConfig,
	sandpackPlugin,
	tablePlugin,
	thematicBreakPlugin,
	toolbarPlugin,
	UndoRedo,
} from "@mdxeditor/editor";
import "@mdxeditor/editor/style.css";
import { ForwardedRef } from "react";

const defaultSnippetContent = `
export default function App() {
    return (
        <div className="App">
            <h1>Hello CodeSandbox</h1>
            <h2>Start editing to see some magic happen!</h2>
        </div>
    );
}
`.trim();

const reactSandpackConfig: SandpackConfig = {
	defaultPreset: "react",
	presets: [
		{
			label: "React",
			name: "react",
			meta: "live",
			sandpackTemplate: "react",
			sandpackTheme: "light",
			snippetFileName: "/App.js",
			snippetLanguage: "jsx",
			initialSnippetContent: defaultSnippetContent,
		},
	],
};

const allPlugins = (diffMarkdown: string) => [
	toolbarPlugin({
		toolbarContents: () => (
			<>
				<BlockTypeSelect />
				<BoldItalicUnderlineToggles />
				<CodeToggle />
				<InsertCodeBlock />
				<InsertThematicBreak />
				<InsertTable />
				<ListsToggle />
				<UndoRedo />
			</>
		),
	}),
	listsPlugin(),
	quotePlugin(),
	headingsPlugin(),
	linkPlugin(),
	linkDialogPlugin(),
	imagePlugin({ imageUploadHandler: async () => "/sample-image.png" }),
	tablePlugin(),
	thematicBreakPlugin(),
	frontmatterPlugin(),
	codeBlockPlugin({ defaultCodeBlockLanguage: "txt" }),
	sandpackPlugin({ sandpackConfig: reactSandpackConfig }),
	codeMirrorPlugin({
		codeBlockLanguages: {
			js: "JavaScript",
			css: "CSS",
			txt: "text",
			tsx: "TypeScript",
		},
	}),
	directivesPlugin({ directiveDescriptors: [AdmonitionDirectiveDescriptor] }),
	diffSourcePlugin({ viewMode: "rich-text", diffMarkdown }),
	markdownShortcutPlugin(),
];

export default function TextEditor({
	markdown,
	editorRef,
	...props
}: {
	markdown: string;
	editorRef: ForwardedRef<MDXEditorMethods> | null;
} & MDXEditorProps) {
	const { appearance } = useAppearance();
	return (
		<MDXEditor
			ref={editorRef}
			markdown={markdown}
			{...props}
			className={`prose max-w-full font-sans ${appearance === "dark" || appearance === "system" ? "dark-theme mdxeditor" : "mdxeditor"}`}
			contentEditableClassName="prose max-w-full font-sans"
			plugins={allPlugins(markdown)}
		/>
	);
}
