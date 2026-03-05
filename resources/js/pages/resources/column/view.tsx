import ColumnSidebarSelect from "@/components/column-sidebar-select";
import HeadingSmall from "@/components/heading-small";
import { Item, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/item";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from "@/layouts/app-layout";
import ResourcesLayout from "@/layouts/resources/layout";
import column from "@/routes/column";
import { BreadcrumbItem } from "@/types";
import { columnModel, ColumnNavItems, constraintsModel, databaseModel, tableModel, typeModel } from "@/types/resources.d";
import { Head, Link } from "@inertiajs/react";
import { MarkdownRenderer } from "@/components/markdown-renderer";
import screen from "@/routes/screen";
import endpoint from "@/routes/endpoint";
import table from "@/routes/table";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Column',
        href: column.index().url
    },
];

const colRoute = column;

export default function ViewColumn({
    column,
    doc,
    tables,
    databases,
    types,
    constraints
}: {
    column: columnModel[];
    doc: string;
    tables: tableModel[];
    databases: databaseModel[];
    primaryKey: columnModel[];
    foreignKey: columnModel[];
    types: typeModel[];
    constraints: constraintsModel[];
}) {
    const type = types.find((type) => type.id === column[0]?.type_id);
    const constraintArr = constraints.reduce(
        (acc, constraint) => {
            acc[constraint.id] = constraint.name;
            return acc;
        },
        {} as Record<string, string>,
    );
    const constraint = column !== null && column[0]?.constraints
        ? column[0]?.constraints?.map((constraint) => constraint.id.toString())
        : [];

    const selectedPkArr = column && column[0]?.related_pks
        ? Array.from(new Set(
            column[0].related_pks.map((col) => col.id.toString())
        ))
        : [];
    const selectedPkTableArr = column && column[0]?.related_pks
        ? Array.from(new Set(
            column[0].related_pks.map((col) => col.table_id.toString())
        ))
        : [];

    const selectedFkArr = column && column[0]?.related_fks
        ? Array.from(new Set(
            column[0].related_fks.map((col) => col.id.toString())
        ))
        : [];
    const selectedFkTableArr = column && column[0]?.related_fks
        ? Array.from(new Set(
            column[0].related_fks.map((col) => col.table_id.toString())
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

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View column" />

            <ResourcesLayout
                title="Column"
                description="Manage your column information"
                sidebarNavItems={ColumnNavItems}
                sidebarExtraNavItems={ColumnSidebarSelect({databases, tables})}
            >
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title="View column"
                        editUrl={colRoute.edit(column[0]?.id).url}
                    />

                    <div className="space-y-6">
                        <div className="grid gap-2">
                            <Link href={table.show(column[0]?.table_id)}>
                                <Item variant='outline'>
                                    <ItemContent>
                                        <ItemTitle>Table</ItemTitle>
                                        <ItemDescription>{column[0]?.table?.name}</ItemDescription>
                                    </ItemContent>
                                </Item>
                            </Link>
                        </div>

                        <div className="grid gap-2">
                            <Item variant='outline'>
                                <ItemContent>
                                    <ItemTitle>Name</ItemTitle>
                                    <ItemDescription>{column !== null ? column[0]?.name : undefined}</ItemDescription>
                                </ItemContent>
                            </Item>
                        </div>

                        <div className="grid gap-2">
                            <Item variant='outline'>
                                <ItemContent>
                                    <ItemTitle>Type</ItemTitle>
                                    <ItemDescription>{type !== null ? type?.name : undefined}</ItemDescription>
                                </ItemContent>
                            </Item>
                        </div>

                        {constraint.length > 0 && (
                            <div className="grid gap-2">
                                <div className="flex w-87.5 flex-col gap-2">
                                    <div className="flex items-center justify-between gap-4 px-4">
                                        <Label htmlFor="constraints_id">Constraints</Label>
                                    </div>
                                </div>
                                <div className="mt-3 flex flex-col gap-2">
                                    {constraint.map((item, index) => (
                                        <div className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={`${index}_${item}`}>
                                            <p className="grow">{constraintArr[item]}</p>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        {selectedPkArr.length > 0 && (
                            <div className="grid gap-2">
                                <div className="flex w-full flex-col gap-2">
                                    <div className="flex items-center justify-between gap-4 px-4">
                                        <Label>Primary Key</Label>
                                    </div>
                                </div>
                                <div className="flex flex-wrap gap-2 w-full">
                                    {selectedPkTableArr?.map((tableItem) => (
                                        <Card key={tableItem}>
                                            <Link href={table.show(tableItem)}>
                                                <CardHeader>
                                                    <CardTitle>{tableArr[tableItem]?.name}</CardTitle>
                                                    <CardDescription>
                                                        <Label htmlFor={`${tableItem}_columns_id_pk`}>Columns</Label>
                                                    </CardDescription>
                                                </CardHeader>
                                            </Link>
                                            <CardContent>
                                                {selectedPkArr?.map((item) => columnArr[item]?.table_id.toString() === tableItem && selectedPkArr?.includes(item) && (
                                                    <Link
                                                        className="flex rounded-md border px-4 py-2 my-2 font-mono text-sm items-center"
                                                        key={item}
                                                        href={colRoute.show(item)}
                                                    >
                                                        <p className="grow">{columnArr[item]?.name}</p>
                                                    </Link>
                                                ))}
                                            </CardContent>
                                        </Card>
                                    ))}
                                </div>
                            </div>
                        )}

                        {selectedFkArr.length > 0 && (
                            <div className="grid gap-2">
                                <div className="flex w-full flex-col gap-2">
                                    <div className="flex items-center justify-between gap-4 px-4">
                                        <Label>Foreign Key</Label>
                                    </div>
                                </div>
                                <div className="flex flex-wrap gap-2 w-full">
                                    {selectedFkTableArr?.map((tableItem) => (
                                        <Card key={tableItem}>
                                            <Link href={table.show(tableItem)}>
                                                <CardHeader>
                                                    <CardTitle>{tableArr[tableItem]?.name}</CardTitle>
                                                    <CardDescription>
                                                        <Label htmlFor={`${tableItem}_columns_id_fk`}>Columns</Label>
                                                    </CardDescription>
                                                </CardHeader>
                                            </Link>
                                            <CardContent>
                                                {selectedFkArr?.map((item) => columnArr[item]?.table_id.toString() === tableItem && selectedFkArr?.includes(item) && (
                                                    <Link
                                                        className="flex rounded-md border px-4 py-2 my-2 font-mono text-sm items-center"
                                                        key={item}
                                                        href={colRoute.show(item)}
                                                    >
                                                        <p className="grow">{columnArr[item]?.name}</p>
                                                    </Link>
                                                ))}
                                            </CardContent>
                                        </Card>
                                    ))}
                                </div>
                            </div>
                        )}

                        {column[0].screens !== null && column[0]?.screens.length > 0 && (
                            <div className="grid gap-2">
                                <div className="flex w-87.5 flex-col gap-2">
                                    <div className="flex items-center justify-between gap-4 px-4">
                                        <Label htmlFor="screen_id">Screens</Label>
                                    </div>

                                    {column[0]?.screens.map((item, index) => (
                                        <Link href={screen.show(item.id)} className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
                                            <p className="grow">{item.name}</p>
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        )}

                        {column[0].endpoints !== null && column[0]?.endpoints.length > 0 && (
                            <div className="grid gap-2">
                                <div className="flex w-87.5 flex-col gap-2">
                                    <div className="flex items-center justify-between gap-4 px-4">
                                        <Label htmlFor="screen_id">Endpoints</Label>
                                    </div>

                                    {column[0].endpoints.map((item, index) => (
                                        <Link href={endpoint.show(item.id)} className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
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
                                <MarkdownRenderer source={doc} />
                            </div>
                        )}
                    </div>
                </div>
            </ResourcesLayout>
        </AppLayout>
    );
}