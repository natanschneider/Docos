import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { formatDate } from '@/lib/utils';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type Category, type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { Clock, ClockAlert, TriangleAlert } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

export default function Dashboard({ categories }: { categories: Category[] }) {
    const { companies, currentCompany } = usePage<SharedData>().props;

    if (companies.length === 0 || currentCompany === null) {
        return (
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Dashboard" />
                <div className="flex flex-col items-center justify-center py-16 text-center">
                    {companies.length === 0 ? (
                        <>
                            <div className="mb-4 rounded-full bg-muted p-6">
                                <TriangleAlert className="h-12 w-12 text-muted-foreground" />
                            </div>
                            <h3 className="mb-2 text-xl font-semibold text-foreground">No Companies Found</h3>
                            <p className="text-sm text-muted-foreground">There are no companies available at the moment.</p>
                            <p className="mt-1 text-xs text-muted-foreground/70">Please add a company to get started.</p>
                        </>
                    ) : (
                        <>
                            <div className="mb-4 rounded-full bg-muted p-6">
                                <ClockAlert className="h-12 w-12 text-muted-foreground" />
                            </div>
                            <h3 className="mb-2 text-xl font-semibold text-foreground">No Company was Selected</h3>
                            <p className="text-sm text-muted-foreground">Please select a company to get started.</p>
                        </>
                    )}
                </div>
            </AppLayout>
        );
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div>
                    <h2 className="mb-6 text-2xl font-semibold text-foreground">Recently Updated</h2>
                    <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-2">
                        {categories.map((category) => (
                            <Card key={category.id} className="transition-shadow hover:shadow-md">
                                <CardHeader className="pb-4">
                                    <CardTitle className="text-xl text-card-foreground">{category.name}</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    {category.items.length === 0 ? (
                                        <div className="flex flex-col items-center justify-center py-8 text-center">
                                            <div className="mb-2 rounded-full bg-muted p-3">
                                                <Clock className="h-6 w-6 text-muted-foreground" />
                                            </div>
                                            <p className="text-sm text-muted-foreground">No recent updates</p>
                                            <p className="mt-1 text-xs text-muted-foreground/70">Items will appear here when updated</p>
                                        </div>
                                    ) : (
                                        <div className="space-y-3">
                                            {category.items.map((item) => (
                                                <div
                                                    key={item.id}
                                                    className="flex items-start justify-between gap-4 border-b border-border pb-3 last:border-0 last:pb-0"
                                                >
                                                    <span className="text-sm leading-relaxed font-medium text-foreground">{item.name}</span>
                                                    <div className="flex items-center gap-1.5 text-xs whitespace-nowrap text-muted-foreground">
                                                        <Clock className="h-3.5 w-3.5" />
                                                        <span>{formatDate(item.updated_at)}</span>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
