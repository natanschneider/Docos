import { GitBranch, Layers, Search } from 'lucide-react';

const features = [
    {
        icon: GitBranch,
        title: 'Usage tracking',
        description: 'See exactly where each column is used across screens, endpoints, and queries.',
    },
    {
        icon: Layers,
        title: 'Contextual documentation',
        description: 'Add descriptions, examples, and notes directly to each field in your database.',
    },
    {
        icon: Search,
        title: 'Smart search',
        description: 'Quickly find any table, column, or relationship in your system.',
    },
];

export function Features() {
    return (
        <section id="features" className="border-t border-border bg-muted/30 py-24">
            <div className="mx-auto max-w-5xl px-4">
                <div className="mb-12 text-center">
                    <h2 className="text-3xl font-bold tracking-tight">Everything you need to document your data</h2>
                    <p className="mt-3 text-muted-foreground">Powerful tools to keep your documentation always up to date.</p>
                </div>

                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {features.map((feature) => (
                        <div
                            key={feature.title}
                            className="group rounded-xl border border-border bg-background p-6 transition-colors hover:border-foreground/20"
                        >
                            <div className="mb-4 flex size-10 items-center justify-center rounded-lg bg-muted">
                                <feature.icon className="size-5 text-foreground" />
                            </div>
                            <h3 className="mb-2 font-semibold">{feature.title}</h3>
                            <p className="text-sm leading-relaxed text-muted-foreground">{feature.description}</p>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
