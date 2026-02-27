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
import { Head } from "@inertiajs/react";
import { MarkdownRenderer } from "@/components/markdown-renderer";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Column',
        href: column.index().url
    },
];

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

    const columnArr = tables.reduce((acc, table) => {
        const columns = table?.columns;
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
                    />

                    <div className="space-y-6">
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

                        <div className="grid gap-2">
                            <div className="flex w-[350px] flex-col gap-2">
                                <div className="flex items-center justify-between gap-4 px-4">
                                    <Label htmlFor="constraints_id">Constraints</Label>
                                </div>
                            </div>
                            <div className="mt-3 flex flex-col gap-2">
                                {constraint.map((item, index) => (
                                    <div className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
                                        <p className="grow">{constraintArr[item]}</p>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="grid gap-2">
                            <div className="flex w-full flex-col gap-2">
                                <div className="flex items-center justify-between gap-4 px-4">
                                    <Label>Primary Key</Label>
                                </div>
                            </div>
                            <div className="grid gap-2 auto-cols-max grid-flow-col w-full">
                                {selectedPkTableArr?.map((table) => (
                                    <Card key={table}>
                                        <CardHeader>
                                            <CardTitle>{tableArr[table]?.name}</CardTitle>
                                            <CardDescription>
                                                <Label htmlFor={`${table}_columns_id_pk`}>Columns</Label>
                                            </CardDescription>
                                        </CardHeader>
                                        <CardContent>
                                            {selectedPkArr?.map((item) => columnArr[item]?.table_id.toString() === table && selectedPkArr?.includes(item) && (
                                                <div
                                                    className="flex rounded-md border px-4 py-2 my-2 font-mono text-sm items-center"
                                                    key={item}
                                                >
                                                    <p className="grow">{columnArr[item]?.name}</p>
                                                </div>
                                            ))}
                                        </CardContent>
                                    </Card>
                                ))}
                            </div>
                        </div>

                        <div className="grid gap-2">
                            <div className="flex w-full flex-col gap-2">
                                <div className="flex items-center justify-between gap-4 px-4">
                                    <Label>Foreign Key</Label>
                                </div>
                            </div>
                            <div className="grid gap-2 auto-cols-max grid-flow-col w-full">
                                {selectedFkTableArr?.map((table) => (
                                    <Card key={table}>
                                        <CardHeader>
                                            <CardTitle>{tableArr[table]?.name}</CardTitle>
                                            <CardDescription>
                                                <Label htmlFor={`${table}_columns_id_fk`}>Columns</Label>
                                            </CardDescription>
                                        </CardHeader>
                                        <CardContent>
                                            {selectedFkArr?.map((item) => columnArr[item]?.table_id.toString() === table && selectedFkArr?.includes(item) && (
                                                <div
                                                    className="flex rounded-md border px-4 py-2 my-2 font-mono text-sm items-center"
                                                    key={item}
                                                >
                                                    <p className="grow">{columnArr[item]?.name}</p>
                                                </div>
                                            ))}
                                        </CardContent>
                                    </Card>
                                ))}
                            </div>
                        </div>

                        <div className="w-full">
                            <div className="flex items-center my-4">
                                <Label>Documetation</Label>
                            </div>
                            <MarkdownRenderer source={doc ?? ''} />
                        </div>
                    </div>
                </div>
            </ResourcesLayout>
        </AppLayout>
    );
}