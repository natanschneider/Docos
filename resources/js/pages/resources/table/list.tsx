import HeadingSmall from '@/components/heading-small';
import DatabaseSidebarSelect from '@/components/database-sidebar-select';
import ResourceListItem from '@/components/resource-list-item';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import * as TableRoute from '@/routes/table';
import { type BreadcrumbItem } from '@/types';
import { TableNavItems, databaseModel, type tableModel } from '@/types/resources.d';
import { Head } from '@inertiajs/react';
import { TriangleAlert } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Table',
        href: TableRoute.index().url,
    },
];

export default function ListTables({ tables, databases }: { tables: tableModel[]; databases: databaseModel[]; }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List of tables" />

            <ResourcesLayout
                title="Table"
                description="Manage your table information"
                sidebarNavItems={TableNavItems}
                sidebarExtraNavItems={DatabaseSidebarSelect(databases)}
            >
                <div className="border-spacing-x-60 space-y-6">
                    {tables.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-16 text-center">
                            <div className="mb-4 rounded-full bg-muted p-6">
                                <TriangleAlert className="h-12 w-12 text-muted-foreground" />
                            </div>
                            <h3 className="mb-2 text-xl font-semibold text-foreground">No Tables Found</h3>
                            <p className="text-sm text-muted-foreground">There are no tables available at the moment.</p>
                            <p className="mt-1 text-xs text-muted-foreground/70">Please add a table to get started.</p>
                        </div>
                    ) : (
                        <>
                            <HeadingSmall title="Tables" description="List of all tables" />

                            {tables.map((table) => (
                                <ResourceListItem
                                    key={table.id}
                                    resource={{
                                        id: table.id,
                                        name: table.name,
                                        edit_url: TableRoute.edit(table.id).url,
                                        delete_url: TableRoute.destroy(table.id).url,
                                        list_url: TableRoute.index().url,
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
