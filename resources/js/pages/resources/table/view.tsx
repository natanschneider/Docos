import DatabaseSidebarSelect from "@/components/database-sidebar-select"
import HeadingSmall from "@/components/heading-small"
import { MarkdownRenderer } from "@/components/markdown-renderer"
import { Item, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/item"
import { Label } from "@/components/ui/label"
import AppLayout from "@/layouts/app-layout"
import ResourcesLayout from "@/layouts/resources/layout"
import table from "@/routes/table"
import { BreadcrumbItem } from "@/types"
import { databaseModel, tableModel, TableNavItems } from "@/types/resources.d"
import { Head } from "@inertiajs/react"

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Table',
        href: table.index().url
    }
]

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
                    />

                    <div className="space-y-6">
                        <div className="grid gap-2">
                            <Item variant='outline'>
                                <ItemContent>
                                    <ItemTitle>Name</ItemTitle>
                                    <ItemDescription>{table.name}</ItemDescription>
                                </ItemContent>
                            </Item>
                        </div>

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
