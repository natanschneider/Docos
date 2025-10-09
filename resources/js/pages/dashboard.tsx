import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Clock } from 'lucide-react';
import { formatDate } from '@/lib/utils';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type Category } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

export default function Dashboard(categories: Category[]) {
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
                                    <div className="space-y-3">
                                        {category.items.map((item) => (
                                            <div
                                                key={item.id}
                                                className="flex items-start justify-between gap-4 border-b border-border pb-3 last:border-0 last:pb-0"
                                            >
                                                <span className="text-sm leading-relaxed font-medium text-foreground">{item.name}</span>
                                                <div className="flex items-center gap-1.5 text-xs whitespace-nowrap text-muted-foreground">
                                                    <Clock className="h-3.5 w-3.5" />
                                                    <span>{formatDate(item.updatedAt)}</span>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
