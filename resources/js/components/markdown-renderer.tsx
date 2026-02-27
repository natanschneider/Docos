import ReactMarkdown from 'react-markdown';
import remarkGfm from 'remark-gfm';
import { markdownComponents } from './markdown-components';

interface MarkdownRendererProps {
    source: string;
    className?: string;
}

export function MarkdownRenderer({ source, className = '' }: MarkdownRendererProps) {
    return (
        <article className={`border-t border-b max-w-none text-left p-2 ${className}`}>
            <ReactMarkdown remarkPlugins={[remarkGfm]} components={markdownComponents}>
                {source}
            </ReactMarkdown>
        </article>
    );
}
