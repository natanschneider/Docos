import ColumnController from '@/actions/App/Http/Controllers/ViewResources/ColumnController';
import ColumnSidebarSelect from '@/components/column-sidebar-select';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { ChevronsUpDown } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import column from '@/routes/column';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { columnModel, ColumnNavItems, tableModel, databaseModel, typeModel, constraintsModel } from '@/types/resources.d';
import { Transition } from '@headlessui/react';
import { Form, Head, usePage } from '@inertiajs/react';
import React, { useRef } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Column',
        href: column.index().url,
    },
];

export default function ManipulateColumn({
    column,
    tables,
    databases,
    primaryKey,
    foreignKey,
    types,
    constraints
}: {
    column: columnModel[];
    tables: tableModel[];
    databases: databaseModel[];
    primaryKey: columnModel[];
    foreignKey: columnModel[];
    types: typeModel[];
    constraints: constraintsModel[];
}) {
    const { currentTable } = usePage<SharedData>().props;
    const name = useRef<HTMLInputElement>(null);
    const [isConstraintOpen, setIsConstraintOpen] = React.useState(true);
    const [constraint, setConstraint] = React.useState<string[]>(column !== null && column[0]?.constraints
        ? column[0]?.constraints?.map((constraint) => constraint.id.toString())
        : []
    );
    const [selectedConstraint, setSelectedConstraint] = React.useState<string | undefined>(undefined);

    const [isPkOpen, setIsPkOpen] = React.useState(true);
    const [selectedPk, setSelectedPk] = React.useState<string | undefined>(undefined);
    const [selectedPkArr, setSelectedPkArr] = React.useState<string[] | undefined>(() => {
        if (!column || !column[0]?.related_pks) return [];

        const uniqueItems = Array.from(new Set(
            column[0].related_pks
            .map((col) => col.id.toString())
        ));

        return uniqueItems;
    });
    const [selectedPkTable, setSelectedPkTable] = React.useState<string | undefined>(undefined);
    const [selectedPkTableArr, setSelectedPkTableArr] = React.useState<string[] | undefined>(() => {
        if (!column || !column[0]?.related_pks) return [];

        const uniqueItems = Array.from(new Set(
            column[0].related_pks
            .map((col) => col.table_id.toString())
        ));

        return uniqueItems;
    });

    const [isFkOpen, setIsFkOpen] = React.useState(true);
    const [selectedFk, setSelectedFk] = React.useState<string | undefined>(undefined);
    const [selectedFkArr, setSelectedFkArr] = React.useState<string[] | undefined>(() => {
        if (!column || !column[0]?.related_fks) return [];

        const uniqueItems = Array.from(new Set(
            column[0].related_fks
            .map((col) => col.id.toString())
        ));

        return uniqueItems;
    });
    const [selectedFkTable, setSelectedFkTable] = React.useState<string | undefined>(undefined);
    const [selectedFkTableArr, setSelectedFkTableArr] = React.useState<string[] | undefined>(() => {
        if (!column || !column[0]?.related_fks) return [];

        const uniqueItems = Array.from(new Set(
            column[0].related_fks
            .map((col) => col.table_id.toString())
        ));

        return uniqueItems;
    });

    const constraintArr = constraints.reduce(
        (acc, constraint) => {
            acc[constraint.id] = constraint.name;
            return acc;
        },
        {} as Record<string, string>,
    );

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

    const tablePkArr = primaryKey.reduce<Record<string, Record<number, columnModel>>>(
        (acc, pk) => {
            const tableId = pk.table_id.toString();
            if (!acc[tableId]) acc[tableId] = {};
            acc[tableId][pk.id] = pk;
            return acc;
        },
        {},
    );

    const tableFkArr = foreignKey.reduce<Record<string, Record<number, columnModel>>>(
        (acc, fk) => {
            const tableId = fk.table_id.toString();
            if (!acc[tableId]) acc[tableId] = {};
            acc[tableId][fk.id] = fk;
            return acc;
        },
        {},
    );

    const addConstraint = (item: string) => {
        if (!constraint.includes(item)) {
            setConstraint([...constraint, item]);
        }

        setSelectedConstraint('');
    };

    const removeConstraint = (item: string) => {
        setConstraint(constraint.filter((i) => i !== item));
        setSelectedConstraint('');
    };

    const addPkTable = (item: string) => {
        if (!selectedPkTableArr?.includes(item)) {
            setSelectedPkTableArr([...selectedPkTableArr??[], item]);
        }

        setSelectedPkTable(undefined);
    };

    const addPk = (item: string) => {
        if (!selectedPkArr?.includes(item)) {
            setSelectedPkArr([...selectedPkArr??[], item]);
        }

        setSelectedPk(undefined);
    };

    const removePk = (item: string) => {
        setSelectedPkArr(selectedPkArr?.filter((i) => i !== item));
        setSelectedPk(undefined);
    };

    const addFkTable = (item: string) => {
        if (!selectedFkTableArr?.includes(item)) {
            setSelectedFkTableArr([...selectedFkTableArr??[], item]);
        }

        setSelectedFkTable(undefined);
    };

    const addFk = (item: string) => {
        if (!selectedFkArr?.includes(item)) {
            setSelectedFkArr([...selectedFkArr??[], item]);
        }

        setSelectedFk(undefined);
    };

    const removeFk = (item: string) => {
        setSelectedFkArr(selectedFkArr?.filter((i) => i !== item));
        setSelectedFk(undefined);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={column !== null ? 'Edit a column' : 'Create a column'} />

            <ResourcesLayout
                title="Column"
                description="Manage your column information"
                sidebarNavItems={ColumnNavItems}
                sidebarExtraNavItems={ColumnSidebarSelect({databases, tables})}
            >
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title={column !== null ? 'Edit a column' : 'Create a column'}
                        description="Edit the information of your column"
                    />

                    <Form
                        {...(column !== null
                            ? ColumnController.update.form({
                                  column: column[0]?.id,
                              })
                            : ColumnController.store.form())}
                        options={{
                            preserveScroll: true,
                        }}
                        resetOnError={['name']}
                        resetOnSuccess={['name']}
                        className="space-y-6"
                    >
                        {({ errors, processing, recentlySuccessful }) => (
                            <>
                                {column !== null ? <input type="hidden" name="id" value={column[0]?.id} /> : null}
                                <input type="hidden" name="table_id" value={currentTable ?? ''} />
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Name</Label>

                                    <Input
                                        id="name"
                                        ref={name}
                                        name="name"
                                        type="text"
                                        className="mt-1 block w-full"
                                        autoComplete="name"
                                        defaultValue={ column !== null ? column[0]?.name : undefined }
                                        required
                                    />

                                    <InputError message={errors.name} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="type_id">Type</Label>

                                    <Select name="type_id" required defaultValue={column !== null ? column[0]?.type_id?.toString() : undefined}>
                                        <SelectTrigger className="w-[180px]">
                                            <SelectValue placeholder="Select an type" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectLabel>Types</SelectLabel>
                                                {types.map((type) => (
                                                    <SelectItem key={type.id} value={type.id.toString()}>
                                                        {type.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>

                                    <InputError message={errors.type_id} />
                                </div>

                                <div className="grid gap-2">
                                    <Collapsible open={isConstraintOpen} onOpenChange={setIsConstraintOpen} className="flex w-[350px] flex-col gap-2">
                                        <div className="flex items-center justify-between gap-4 px-4">
                                            <Label htmlFor="constraints_id">Constraints</Label>
                                            <CollapsibleTrigger asChild>
                                                <Button variant="ghost" size="icon" className="size-8">
                                                    <ChevronsUpDown />
                                                    <span className="sr-only">Toggle</span>
                                                </Button>
                                            </CollapsibleTrigger>
                                        </div>

                                        <Select value={selectedConstraint} name="database_id" onValueChange={(value) => addConstraint(value)}>
                                            <SelectTrigger className="w-[180px]">
                                                <SelectValue placeholder="Select an constraint" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectLabel>Constraints</SelectLabel>
                                                    {constraints.map((constraint) => (
                                                        <SelectItem key={constraint.id} value={constraint.id.toString()}>
                                                            {constraint.name}
                                                        </SelectItem>
                                                    ))}
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>

                                        <input type="hidden" name="constraints[]" value={constraint} />
                                        <CollapsibleContent className="mt-3 flex flex-col gap-2">
                                            {constraint.map((item, index) => (
                                                <div className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
                                                    <p className="grow">{constraintArr[item]}</p>
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        className="min-w-[4rem] text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                        onClick={() => removeConstraint(item)}
                                                    >
                                                        Delete
                                                    </Button>
                                                </div>
                                            ))}
                                        </CollapsibleContent>

                                        <InputError message={errors.constraints} />
                                    </Collapsible>
                                </div>

                                <div className='w-full'>
                                    <Collapsible open={isPkOpen} onOpenChange={setIsPkOpen} className='flex w-full flex-col gap-2'>
                                        <div className='flex items-center justify-between gap-4 px-4'>
                                            <Label htmlFor='tablePk'>Primary Keys</Label>
                                            <CollapsibleTrigger asChild>
                                                <Button variant='ghost' size='icon' className='size-8'>
                                                    <ChevronsUpDown />
                                                    <span className='sr-only'>Toggle</span>
                                                </Button>
                                            </CollapsibleTrigger>
                                        </div>

                                        <Select value={selectedPkTable} name='tablePk' onValueChange={(value) => addPkTable(value)}>
                                            <SelectTrigger className='w-[180px]'>
                                                <SelectValue placeholder='Select a table' />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectLabel>Tables</SelectLabel>
                                                    {Object.entries(tablePkArr ?? {}).map((table) => (
                                                        <SelectItem key={table[0].toString() ?? ''} value={table[0].toString()}>
                                                            {tableArr[table[0].toString()]?.name}
                                                        </SelectItem>
                                                    ))}
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>

                                        <input type="hidden" id='related_columns' name="related_columns[pk][]" value={selectedPkTableArr} />
                                        <CollapsibleContent className='grid gap-2 auto-cols-max grid-flow-col w-full'>
                                            {selectedPkTableArr?.map((table) => (
                                                <Card key={table}>
                                                    <CardHeader>
                                                        <CardTitle>{tableArr[table]?.name}</CardTitle>
                                                        <CardDescription>
                                                            <Label htmlFor={`${table}_columns_id_pk`}>Columns</Label>
                                                            <Select value={selectedPk} name={`${table}_columns_id_pk`} onValueChange={(value) => addPk(value)}>
                                                                <SelectTrigger className='w-[180px]'>
                                                                    <SelectValue placeholder='Select a column' />
                                                                </SelectTrigger>
                                                                <SelectContent>
                                                                    <SelectGroup>
                                                                        <SelectLabel>Columns</SelectLabel>
                                                                        {Object.entries(tablePkArr[table] ?? {}).map((column) => (
                                                                            <SelectItem key={column[0].toString() ?? ''} value={column[0].toString()}>
                                                                                {columnArr[column[0].toString()]?.name}
                                                                            </SelectItem>
                                                                        ))}
                                                                    </SelectGroup>
                                                                </SelectContent>
                                                            </Select>
                                                        </CardDescription>
                                                    </CardHeader>
                                                    <CardContent>
                                                        <InputError message={errors[`${table}_columns_id_pk`]} />
                                                        {selectedPkArr?.map((item) => columnArr[item]?.table_id.toString() === table && selectedPkArr?.includes(item) && (
                                                            <div
                                                                className="flex rounded-md border px-4 py-2 my-2 font-mono text-sm items-center"
                                                                key={item}
                                                            >
                                                                <p className="grow">{columnArr[item]?.name}</p>
                                                                <Button
                                                                    size="sm"
                                                                    variant="outline"
                                                                    className="min-w-[4rem] text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                                    onClick={() => removePk(item)}
                                                                >
                                                                    Delete
                                                                </Button>
                                                            </div>
                                                        ))}
                                                    </CardContent>
                                                </Card>
                                            ))}
                                        </CollapsibleContent>
                                    </Collapsible>

                                    <Collapsible open={isFkOpen} onOpenChange={setIsFkOpen} className='flex w-full flex-col gap-2'>
                                        <div className='flex items-center justify-between gap-4 px-4'>
                                            <Label htmlFor='tableFk'>Foreing Keys</Label>
                                            <CollapsibleTrigger asChild>
                                                <Button variant='ghost' size='icon' className='size-8'>
                                                    <ChevronsUpDown />
                                                    <span className='sr-only'>Toggle</span>
                                                </Button>
                                            </CollapsibleTrigger>
                                        </div>

                                        <Select value={selectedFkTable} name='tableFk' onValueChange={(value) => addFkTable(value)}>
                                            <SelectTrigger className='w-[180px]'>
                                                <SelectValue placeholder='Select a table' />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectLabel>Tables</SelectLabel>
                                                    {Object.entries(tableFkArr ?? {}).map((table) => (
                                                        <SelectItem key={table[0].toString() ?? ''} value={table[0].toString()}>
                                                            {tableArr[table[0].toString()]?.name}
                                                        </SelectItem>
                                                    ))}
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>

                                        <input type="hidden" id='related_columns' name="related_columns[fk][]" value={selectedFkTableArr} />
                                        <CollapsibleContent className='grid gap-2 auto-cols-max grid-flow-col w-full'>
                                            {selectedFkTableArr?.map((table) => (
                                                <Card key={table}>
                                                    <CardHeader>
                                                        <CardTitle>{tableArr[table]?.name}</CardTitle>
                                                        <CardDescription>
                                                            <Label htmlFor={`${table}_columns_id_fk`}>Columns</Label>
                                                            <Select value={selectedFk} name={`${table}_columns_id_fk`} onValueChange={(value) => addFk(value)}>
                                                                <SelectTrigger className='w-[180px]'>
                                                                    <SelectValue placeholder='Select a column' />
                                                                </SelectTrigger>
                                                                <SelectContent>
                                                                    <SelectGroup>
                                                                        <SelectLabel>Columns</SelectLabel>
                                                                        {Object.entries(tableFkArr[table] ?? {}).map((column) => (
                                                                            <SelectItem key={column[0].toString() ?? ''} value={column[0].toString()}>
                                                                                {columnArr[column[0].toString()]?.name}
                                                                            </SelectItem>
                                                                        ))}
                                                                    </SelectGroup>
                                                                </SelectContent>
                                                            </Select>
                                                        </CardDescription>
                                                    </CardHeader>
                                                    <CardContent>
                                                        <InputError message={errors[`${table}_columns_id_fk`]} />
                                                        {selectedFkArr?.map((item) => columnArr[item]?.table_id.toString() === table && selectedFkArr?.includes(item) && (
                                                            <div
                                                                className="flex rounded-md border px-4 py-2 my-2 font-mono text-sm items-center"
                                                                key={item}
                                                            >
                                                                <p className="grow">{columnArr[item]?.name}</p>
                                                                <Button
                                                                    size="sm"
                                                                    variant="outline"
                                                                    className="min-w-[4rem] text-destructive hover:bg-destructive/10 hover:text-destructive"
                                                                    onClick={() => removeFk(item)}
                                                                >
                                                                    Delete
                                                                </Button>
                                                            </div>
                                                        ))}
                                                    </CardContent>
                                                </Card>
                                            ))}
                                        </CollapsibleContent>
                                    </Collapsible>

                                    <InputError message={errors.related_columns} />
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>Save column</Button>

                                    <Transition
                                        show={recentlySuccessful}
                                        enter="transition ease-in-out"
                                        enterFrom="opacity-0"
                                        leave="transition ease-in-out"
                                        leaveTo="opacity-0"
                                    >
                                        <p className="text-sm text-neutral-600">Saved</p>
                                    </Transition>
                                </div>
                            </>
                        )}
                    </Form>
                </div>
            </ResourcesLayout>
        </AppLayout>
    );
}
