import HeadingSmall from '@/components/heading-small';
import ResourceListItem from '@/components/resource-list-item';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import * as DatabaseRoute from '@/routes/database';
import { type BreadcrumbItem } from '@/types';
import { DatabaseNavItems, type databaseModel } from '@/types/resources.d';
import { Head } from '@inertiajs/react';
import { TriangleAlert } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Database',
        href: DatabaseRoute.index().url,
    },
];

export default function ListDatabases({ databases }: { databases: databaseModel[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List of databases" />

            <ResourcesLayout title="Database" description="Manage your database information" sidebarNavItems={DatabaseNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    {databases.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-16 text-center">
                            <div className="mb-4 rounded-full bg-muted p-6">
                                <TriangleAlert className="h-12 w-12 text-muted-foreground" />
                            </div>
                            <h3 className="mb-2 text-xl font-semibold text-foreground">No Databases Found</h3>
                            <p className="text-sm text-muted-foreground">There are no databases available at the moment.</p>
                            <p className="mt-1 text-xs text-muted-foreground/70">Please add a database to get started.</p>
                        </div>
                    ) : (
                        <>
                            <HeadingSmall title="Databases" description="List of all databases" />

                            {databases.map((database) => (
                                <ResourceListItem
                                    key={database.id}
                                    resource={{
                                        id: database.id,
                                        name: database.name,
                                        description: database.engine.name,
                                        edit_url: DatabaseRoute.edit(database.id).url,
                                        delete_url: DatabaseRoute.destroy(database.id).url,
                                        list_url: DatabaseRoute.index().url,
                                    }}
                                />
                            ))}
                        </>
                    )}
                </div>
            </ResourcesLayout>
        </AppLayout>
    );
}
