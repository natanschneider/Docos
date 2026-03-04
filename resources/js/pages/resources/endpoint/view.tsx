import HeadingSmall from "@/components/heading-small";
import { Item, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/item";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from "@/layouts/app-layout";
import ResourcesLayout from "@/layouts/resources/layout";
import endpoint from "@/routes/endpoint";
import { BreadcrumbItem } from "@/types";
import { applicationModel, endpointModel, EndpointNavItems, projectModel, tableModel, columnModel } from "@/types/resources.d";
import { Head, Link } from "@inertiajs/react";
import { Label } from "@/components/ui/label";
import { MarkdownRenderer } from "@/components/markdown-renderer";
import application from "@/routes/application";
import table from "@/routes/table";
import column from "@/routes/column";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Endpoint',
        href: endpoint.index().url
    }
];

const endpointRoute = endpoint;

export default function ViewEndpoint({
    endpoint,
    doc,
    tables
}: {
    endpoint: endpointModel[];
    doc: string;
    tables: tableModel[];
    projects: projectModel[];
    applications: applicationModel[];
}) {
    const selectedTables = endpoint && endpoint[0]?.columns
        ? Array.from(new Set(
            endpoint[0].columns.map((col) => col.table_id.toString())
        ))
        : [];

    const items = endpoint && endpoint[0]?.columns
        ? Array.from(new Set(
            endpoint[0].columns.map((col) => col.id.toString())
        ))
        : [];

    const tableArr = tables.reduce(
        (acc, tables) => {
            acc[tables.id] = tables;
            return acc;
        },
        {} as Record<string, tableModel>,
    );

    const columnArr = tables.reduce((acc, tableItem) => {
        const columns = tableItem?.columns;
        columns?.forEach((column) => {
            acc[column?.id?.toString()] = column;
        });
        return acc;
    }, {} as Record<string, columnModel>);

    const columnTable = tables.reduce((acc, tableItem) => {
        const columns = tableItem?.columns;
        columns?.forEach((column) => {
            acc[column?.id?.toString()] = tableItem?.id?.toString();
        });
        return acc;
    }, {} as Record<string, string>);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View endpoint" />

            <ResourcesLayout title="Endpoint" description="Manage your endpoint information" sidebarNavItems={EndpointNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title="View endpoint"
                        editUrl={endpointRoute.edit(endpoint[0]?.id).url}
                    />

                    <div className="space-y-6">
                        <Link href={application.show(endpoint[0].application_id).url}>
                            <Item variant='outline'>
                                <ItemContent>
                                    <ItemTitle>Application</ItemTitle>
                                    <ItemDescription>{endpoint[0].application?.name}</ItemDescription>
                                </ItemContent>
                            </Item>
                        </Link>
                    </div>

                    <div className="space-y-6">
                        <Item variant='outline'>
                            <ItemContent>
                                <ItemTitle>Name</ItemTitle>
                                <ItemDescription>{ endpoint !== null ? endpoint[0]?.name : undefined }</ItemDescription>
                            </ItemContent>
                        </Item>
                    </div>

                    {items.length > 0 && (
                        <div className='w-full'>
                            <div className="flex w-full flex-col gap-2">
                                <div className="flex items-center justify-between gap-4 px-4">
                                    <Label>Tables</Label>
                                </div>
                                <div className='grid gap-2 auto-cols-max grid-flow-col w-full'>
                                    {selectedTables?.map((tableItem) => (
                                        <Card key={tableItem}>
                                            <Link href={table.show(tableItem).url}>
                                                <CardHeader>
                                                    <CardTitle>{tableArr[tableItem]?.name}</CardTitle>
                                                    <CardDescription>
                                                        <Label htmlFor={`${tableItem}_columns_id`}>Columns</Label>
                                                    </CardDescription>
                                                </CardHeader>
                                            </Link>
                                            <CardContent>
                                                {items?.map((item, index) => columnTable[item] === tableItem && (
                                                    <Link
                                                        className="flex rounded-md border px-4 py-2 my-2 font-mono text-sm items-center"
                                                        key={index}
                                                        href={column.show(item).url}
                                                    >
                                                        <p className="grow">{ columnArr[item]?.name }</p>
                                                    </Link>
                                                ))}
                                            </CardContent>
                                        </Card>
                                    ))}
                                </div>
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
            </ResourcesLayout>
        </AppLayout>
    );
}
