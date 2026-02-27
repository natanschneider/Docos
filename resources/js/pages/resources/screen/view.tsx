import HeadingSmall from "@/components/heading-small";
import { Item, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/item";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from "@/layouts/app-layout";
import ResourcesLayout from "@/layouts/resources/layout";
import screen from "@/routes/screen";
import { BreadcrumbItem } from "@/types";
import { applicationModel, screenModel, ScreenNavItems, projectModel, tableModel, columnModel } from "@/types/resources.d";
import { Head } from "@inertiajs/react";
import { Label } from "@/components/ui/label";
import { MarkdownRenderer } from "@/components/markdown-renderer";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Screen',
        href: screen.index().url
    }
];

export default function ViewScreen({
    screen,
    doc,
    tables
}: {
    screen: screenModel[];
    doc: string;
    tables: tableModel[];
    projects: projectModel[];
    applications: applicationModel[];
}) {
    const selectedTables = screen && screen[0]?.columns
        ? Array.from(new Set(
            screen[0].columns.map((col) => col.table_id.toString())
        ))
        : [];

    const items = screen && screen[0]?.columns
        ? Array.from(new Set(
            screen[0].columns.map((col) => col.id.toString())
        ))
        : [];

    const tableArr = tables.reduce(
        (acc, tables) => {
            acc[tables.id] = tables;
            return acc;
        },
        {} as Record<string, tableModel>,
    );

    const columnArr = tables.reduce((acc, table) => {
        const columns = table?.columns;
        columns?.forEach((column) => {
            acc[column?.id?.toString()] = column;
        });
        return acc;
    }, {} as Record<string, columnModel>);

    const columnTable = tables.reduce((acc, table) => {
        const columns = table?.columns;
        columns?.forEach((column) => {
            acc[column?.id?.toString()] = table?.id?.toString();
        });
        return acc;
    }, {} as Record<string, string>);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View screen" />

            <ResourcesLayout title="Screen" description="Manage your screen information" sidebarNavItems={ScreenNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title="View screen"
                    />

                    <div className="space-y-6">
                        <Item variant='outline'>
                            <ItemContent>
                                <ItemTitle>Name</ItemTitle>
                                <ItemDescription>{ screen !== null ? screen[0]?.name : undefined }</ItemDescription>
                            </ItemContent>
                        </Item>
                    </div>

                    {items && items.length > 0 && (
                        <div className='w-full'>
                            <div className="flex w-full flex-col gap-2">
                                <div className="flex items-center justify-between gap-4 px-4">
                                    <Label>Tables</Label>
                                </div>

                                <div className='grid gap-2 auto-cols-max grid-flow-col w-full'>
                                    {selectedTables?.map((table) => (
                                        <Card key={table}>
                                            <CardHeader>
                                                <CardTitle>{tableArr[table]?.name}</CardTitle>
                                                <CardDescription>
                                                    <Label htmlFor={`${table}_columns_id`}>Columns</Label>
                                                </CardDescription>
                                            </CardHeader>
                                            <CardContent>
                                                {items?.map((item, index) => columnTable[item] === table && (
                                                    <div
                                                        className="flex rounded-md border px-4 py-2 my-2 font-mono text-sm items-center"
                                                        key={index}
                                                    >
                                                        <p className="grow">{ columnArr[item]?.name }</p>
                                                    </div>
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
