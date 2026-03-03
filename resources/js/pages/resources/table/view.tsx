import DatabaseSidebarSelect from "@/components/database-sidebar-select"
import HeadingSmall from "@/components/heading-small"
import { MarkdownRenderer } from "@/components/markdown-renderer"
import { Item, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/item"
import { Label } from "@/components/ui/label"
import AppLayout from "@/layouts/app-layout"
import ResourcesLayout from "@/layouts/resources/layout"
import column from "@/routes/column"
import database from "@/routes/database"
import table from "@/routes/table"
import { BreadcrumbItem } from "@/types"
import { databaseModel, tableModel, TableNavItems } from "@/types/resources.d"
import { Head, Link } from "@inertiajs/react"

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Table',
        href: table.index().url
    }
]

const tableRoute = table;

export default function ViewTable({
    table,
    doc,
    databases
}: {
    table: tableModel;
    doc: string;
    databases: databaseModel[];
}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View table" />

            <ResourcesLayout
                title="Table"
                description="Manage your table information"
                sidebarNavItems={TableNavItems}
                sidebarExtraNavItems={DatabaseSidebarSelect(databases)}
            >
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title="View table"
                        editUrl={tableRoute.edit(table.id).url}
                        deleteUrl={tableRoute.destroy(table.id).url}
                        id={table.id}
                    />

                    <div className="space-y-6">
                        <div className="grid gap-2">
                            <Link href={database.show(table.database_id).url}>
                                <Item variant='outline'>
                                    <ItemContent>
                                        <ItemTitle>Database</ItemTitle>
                                        <ItemDescription>{table?.database?.name}</ItemDescription>
                                    </ItemContent>
                                </Item>
                            </Link>
                        </div>

                        <div className="grid gap-2">
                            <Item variant='outline'>
                                <ItemContent>
                                    <ItemTitle>Name</ItemTitle>
                                    <ItemDescription>{table.name}</ItemDescription>
                                </ItemContent>
                            </Item>
                        </div>

                        {table?.columns && table.columns.length > 0 && (
                            <div className="grid gap-2">
                                <div className="flex w-[350px] flex-col gap-2">
                                    <div className="flex items-center justify-between gap-4 px-4">
                                        <Label htmlFor="constraints_id">Columns</Label>
                                    </div>
                                </div>
                                <div className="mt-3 flex flex-col gap-2">
                                    {table.columns.map((item, index) => (
                                        <Link href={column.show(item.id).url} className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
                                            <p className="grow">{item.name}</p>
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        )}

                        {doc && doc.length > 0 && (
                            <div className="w-full">
                                <div className="flex items-center my-4">
                                    <Label>Documetation</Label>
                                </div>
                                <MarkdownRenderer source={doc ?? ''} />
                            </div>
                        )}
                    </div>
                </div>
            </ResourcesLayout>
        </AppLayout>
    )
}
