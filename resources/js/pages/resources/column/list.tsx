import ColumnSidebarSelect from '@/components/column-sidebar-select';
import HeadingSmall from '@/components/heading-small';
import ResourceListItem from '@/components/resource-list-item';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import * as ColumnRoute from '@/routes/column';
import { type BreadcrumbItem } from '@/types';
import { columnModel, ColumnNavItems, databaseModel, tableModel } from '@/types/resources.d';
import { Head } from '@inertiajs/react';
import { TriangleAlert } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Column',
        href: ColumnRoute.index().url,
    },
];

export default function ListColumns({
    columns,
    tables,
    databases
}: {
    columns: columnModel[];
    tables: tableModel[];
    databases: databaseModel[];
}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List of columns" />

            <ResourcesLayout
                title="Columns"
                description="Manage your columns information"
                sidebarNavItems={ColumnNavItems}
                sidebarExtraNavItems={ColumnSidebarSelect({databases, tables})}
            >
                <div className="border-spacing-x-60 space-y-6">
                    {columns.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-16 text-center">
                            <div className="mb-4 rounded-full bg-muted p-6">
                                <TriangleAlert className="h-12 w-12 text-muted-foreground" />
                            </div>
                            <h3 className="mb-2 text-xl font-semibold text-foreground">No Columns Found</h3>
                            <p className="text-sm text-muted-foreground">There are no columns available at the moment.</p>
                            <p className="mt-1 text-xs text-muted-foreground/70">Please add a column to get started.</p>
                        </div>
                    ) : (
                        <>
                            <HeadingSmall title="Columns" description="List of all Columns" />

                            {columns.map((column) => (
                                <ResourceListItem
                                    key={column.id}
                                    resource={{
                                        id: column.id,
                                        name: column.name,
                                        description: column?.type?.name,
                                        view_url: ColumnRoute.show(column.id).url,
                                        edit_url: ColumnRoute.edit(column.id).url,
                                        delete_url: ColumnRoute.destroy(column.id).url,
                                        list_url: ColumnRoute.index().url,
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
