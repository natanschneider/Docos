import { register } from '@/routes';
import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';

export function Hero() {
    return (
        <section className="mx-auto flex max-w-5xl flex-col items-center px-4 py-24 text-center md:py-32">
            <div className="mb-4 inline-flex items-center rounded-full border border-border bg-muted px-3 py-1 text-sm text-muted-foreground">
                <span className="mr-2 size-2 rounded-full bg-foreground" />
                Now in beta
            </div>

            <h1 className="max-w-3xl text-4xl font-bold tracking-tight text-balance md:text-5xl lg:text-6xl">
                Document your data.
                <br />
                <span className="text-muted-foreground">Know where every column is used.</span>
            </h1>

            <p className="mt-6 max-w-xl text-lg text-pretty text-muted-foreground">
                docos connects your database tables to screens and endpoints in your application. Never lose track of where your data is consumed.
            </p>

            <div className="mt-10 flex flex-col items-center gap-4 sm:flex-row">
                <Link href={register()} className="flex items-center gap-2 rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                    Start for free
                    <ArrowRight className="size-4" />
                </Link>
            </div>

            <div className="mt-16 w-full">
                <div className="relative overflow-hidden rounded-xl border border-border bg-muted/50 p-2">
                    <div className="rounded-lg border border-border bg-background p-4">
                        <div className="flex items-center gap-2 border-b border-border pb-4">
                            <div className="size-3 rounded-full bg-muted-foreground/20" />
                            <div className="size-3 rounded-full bg-muted-foreground/20" />
                            <div className="size-3 rounded-full bg-muted-foreground/20" />
                            <div className="ml-4 h-4 w-48 rounded bg-muted" />
                        </div>
                        <div className="mt-4 grid gap-4 md:grid-cols-3">
                            <div className="space-y-3 rounded-lg border border-border p-4">
                                <div className="flex items-center gap-2">
                                    <div className="size-6 rounded bg-foreground" />
                                    <span className="text-sm font-medium">users</span>
                                </div>
                                <div className="space-y-2">
                                    <div className="flex items-center justify-between text-xs">
                                        <span className="text-muted-foreground">id</span>
                                        <span className="rounded bg-muted px-1.5 py-0.5">uuid</span>
                                    </div>
                                    <div className="flex items-center justify-between text-xs">
                                        <span className="text-muted-foreground">email</span>
                                        <span className="rounded bg-muted px-1.5 py-0.5">varchar</span>
                                    </div>
                                    <div className="flex items-center justify-between text-xs">
                                        <span className="text-muted-foreground">name</span>
                                        <span className="rounded bg-muted px-1.5 py-0.5">varchar</span>
                                    </div>
                                </div>
                            </div>
                            <div className="space-y-3 rounded-lg border border-dashed border-border p-4">
                                <div className="text-xs font-medium text-muted-foreground">Relationships</div>
                                <div className="space-y-2">
                                    <div className="flex items-center gap-2 text-xs">
                                        <div className="size-1.5 rounded-full bg-foreground" />
                                        <span>/profile</span>
                                    </div>
                                    <div className="flex items-center gap-2 text-xs">
                                        <div className="size-1.5 rounded-full bg-foreground" />
                                        <span>/api/users</span>
                                    </div>
                                    <div className="flex items-center gap-2 text-xs">
                                        <div className="size-1.5 rounded-full bg-foreground" />
                                        <span>Dashboard</span>
                                    </div>
                                </div>
                            </div>
                            <div className="space-y-3 rounded-lg border border-border p-4">
                                <div className="flex items-center gap-2">
                                    <div className="size-6 rounded bg-muted" />
                                    <span className="text-sm font-medium">orders</span>
                                </div>
                                <div className="space-y-2">
                                    <div className="flex items-center justify-between text-xs">
                                        <span className="text-muted-foreground">id</span>
                                        <span className="rounded bg-muted px-1.5 py-0.5">uuid</span>
                                    </div>
                                    <div className="flex items-center justify-between text-xs">
                                        <span className="text-muted-foreground">user_id</span>
                                        <span className="rounded bg-muted px-1.5 py-0.5">uuid</span>
                                    </div>
                                    <div className="flex items-center justify-between text-xs">
                                        <span className="text-muted-foreground">total</span>
                                        <span className="rounded bg-muted px-1.5 py-0.5">decimal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
