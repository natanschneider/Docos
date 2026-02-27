import type { Components } from 'react-markdown';

export const markdownComponents: Components = {
    /* -- Headings -- */
    h1: ({ children, ...props }) => (
        <h1 className="mt-10 mb-4 text-left text-3xl font-bold tracking-tight text-foreground first:mt-0" {...props}>
            {children}
        </h1>
    ),
    h2: ({ children, ...props }) => (
        <h2 className="mt-8 mb-3 border-b border-border pb-2 text-left text-2xl font-semibold tracking-tight text-foreground" {...props}>
            {children}
        </h2>
    ),
    h3: ({ children, ...props }) => (
        <h3 className="mt-6 mb-2 text-left text-xl font-semibold text-foreground" {...props}>
            {children}
        </h3>
    ),
    h4: ({ children, ...props }) => (
        <h4 className="mt-4 mb-2 text-left text-lg font-semibold text-foreground" {...props}>
            {children}
        </h4>
    ),

    /* -- Text blocks -- */
    p: ({ children, ...props }) => (
        <p className="my-4 text-left leading-7 text-muted-foreground first:mt-0 last:mb-0 [&:not(:first-child)]:mt-4" {...props}>
            {children}
        </p>
    ),
    blockquote: ({ children, ...props }) => (
        <blockquote className="my-6 border-l-4 border-border pl-4 text-left text-muted-foreground italic" {...props}>
            {children}
        </blockquote>
    ),

    /* -- Lists -- */
    ul: ({ children, ...props }) => (
        <ul className="my-4 ml-6 list-disc text-left text-muted-foreground [&>li]:mt-2" {...props}>
            {children}
        </ul>
    ),
    ol: ({ children, ...props }) => (
        <ol className="my-4 ml-6 list-decimal text-left text-muted-foreground [&>li]:mt-2" {...props}>
            {children}
        </ol>
    ),
    li: ({ children, ...props }) => (
        <li className="leading-7" {...props}>
            {children}
        </li>
    ),

    /* -- Code -- */
    code: ({ children, className, ...props }) => {
        const isBlock = className?.includes('language-');
        if (isBlock) {
            return (
                <code className={`${className} block text-sm`} {...props}>
                    {children}
                </code>
            );
        }
        return (
            <code className="rounded-sm bg-secondary px-1.5 py-0.5 font-mono text-[0.85em] font-medium text-secondary-foreground" {...props}>
                {children}
            </code>
        );
    },
    pre: ({ children, ...props }) => (
        <pre
            className="my-6 overflow-x-auto rounded-lg border border-border bg-secondary p-4 font-mono text-sm leading-relaxed text-secondary-foreground"
            {...props}
        >
            {children}
        </pre>
    ),

    /* -- Table -- */
    table: ({ children, ...props }) => (
        <div className="my-6 w-full overflow-x-auto">
            <table className="w-full border-collapse text-sm" {...props}>
                {children}
            </table>
        </div>
    ),
    thead: ({ children, ...props }) => (
        <thead className="border-b border-border" {...props}>
            {children}
        </thead>
    ),
    th: ({ children, ...props }) => (
        <th className="px-4 py-2 text-left text-sm font-semibold text-foreground" {...props}>
            {children}
        </th>
    ),
    td: ({ children, ...props }) => (
        <td className="border-t border-border px-4 py-2 text-left text-muted-foreground" {...props}>
            {children}
        </td>
    ),

    /* -- Links & misc -- */
    a: ({ children, ...props }) => (
        <a
            className="font-medium text-foreground underline underline-offset-4 transition-colors hover:text-muted-foreground"
            target="_blank"
            rel="noopener noreferrer"
            {...props}
        >
            {children}
        </a>
    ),
    hr: () => <hr className="my-8 border-t border-border" />,
    img: ({ alt, ...props }) => <img className="my-6 rounded-md border border-border" alt={alt ?? ''} loading="lazy" {...props} />,
    strong: ({ children, ...props }) => (
        <strong className="font-semibold text-foreground" {...props}>
            {children}
        </strong>
    ),
    em: ({ children, ...props }) => (
        <em className="italic" {...props}>
            {children}
        </em>
    ),
};
